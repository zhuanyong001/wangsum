<?php

namespace App\Http\Controllers\api;

use App\Dao\UserAssetDao;
use App\Exceptions\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\DepositOrder;
use App\Models\MembershipLevel;
use App\Models\SystemConfig;
use App\Models\UserAsset;
use App\Services\Pay\PayService;
use App\Services\TronService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    protected $userAssetDao;

    public function __construct(UserAssetDao $userAssetDao)
    {
        $this->userAssetDao = $userAssetDao;
    }
    //充值
    public function recharge(Request $request, TronService $tronService)
    {
        $data = $request->all();
        $user = $request->user();
        // 
        $tx_id = $data['tx_id'];

        //校验$tx_id 格式 11
        if (!preg_match('/^[a-f0-9]{64}$/', $tx_id)) {
            return $this->fail('Invalid transaction number');
        }

        $order = DepositOrder::where('user_id', $user->id)->findOrFail($data['order_id']);
        if ($order->status != DepositOrder::STATUS_WAIT && $order->status != DepositOrder::STATUS_PENDING) {
            return $this->fail('Order status error');
        }

        //不更新交易号
        if ($order->status == DepositOrder::STATUS_PENDING) {
            $tx_id = $order->tx_id;
        } else {
            //校验下交易号是否存在
            $has_order = DepositOrder::where('transaction_id', $tx_id)->first();
            if ($has_order) {
                return $this->fail('Transaction number already exists');
            }
            $order->status = DepositOrder::STATUS_PENDING;
            $order->transaction_id = $tx_id;
            $order->save();
        }
        if ($order->scene != 'dfpay') return $this->success();
        $status_order = $this->userAssetDao->validateDepositOrder($order, $user, $tx_id);
        return $status_order->status == -1 ? $this->fail() : $this->success($status_order);
    }
    //币种列表
    public function currencyList(Request $request)
    {
        $currencies = Currency::where('status', 1)->OrderByDesc('sort')->get(['id', 'name', 'icon', 'price', 'contract_address', 'change_24h', 'percentage_fee', 'fixed_fee']);
        return $this->success($currencies);
    }
    //币种互换
    public function exchange(Request $request)
    {
        $request->validate([
            'from_currency_id' => 'required|integer',
            'to_currency_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);
        $data = $request->all();
        $user = $request->user();
        $fromCurrency = Currency::findOrFail($data['from_currency_id']);
        $toCurrency = Currency::findOrFail($data['to_currency_id']);

        if ($toCurrency->code == 'DGFY' || $fromCurrency->status != 1 || $toCurrency->status != 1) {
            throw new ApiError('Exchange failed');
        }

        // if ($user->can_exchange == 0) {
        //      return $this->fail('message.exchange_disabled');
        //  }
        if ($user->is_ln_rebate == 0) {
            return $this->fail('message.exchange_disabled');
        }



        $fromAmount =    $data['amount']; //来源币数量
        $fromUserAsset = UserAsset::where('user_id', $user->id)->where('currency_id', $fromCurrency->id)->first();

        if (!$fromUserAsset) {
            return $this->fail('message.insufficient_balance');
        }
        $toUserAsset = UserAsset::where('user_id', $user->id)->where('currency_id', $toCurrency->id)->first();
        if (!$toUserAsset) {
            $toUserAsset = UserAsset::create([
                'user_id' => $user->id,
                'currency_id' => $toCurrency->id,
                'amount' => 0,
            ]);
        }
        if ($fromCurrency->price == 0) {
            return $this->fail('Price cannot be 0');
        }

        //从来源币扣手续费
        $fee = round($fromAmount * $fromCurrency->percentage_fee  + $fromCurrency->fixed_fee, 8);
        $fromAmount = $fromAmount - $fee;
        $USD = $fromCurrency->price * ($fromAmount);
        $toAmount = round($USD / $toCurrency->price, 8);
        if ($toAmount <= 0) {
            return $this->fail('Exchange amount must be greater than 0');
        }
        DB::beginTransaction();
        try {
            $fromUserAsset = UserAsset::lockForUpdate()->find($fromUserAsset->id);
            $toUserAsset = UserAsset::lockForUpdate()->find($toUserAsset->id);
            //code...
            if ($fee > 0)  $this->userAssetDao->updateUserAsset($fromUserAsset, -$fee, UserAssetDao::TYPE_FEE, '兑换手续费');
            $this->userAssetDao->updateUserAsset($fromUserAsset, -$fromAmount, UserAssetDao::TYPE_TRANSFER, '兑换');
            $this->userAssetDao->updateUserAsset($toUserAsset, $toAmount, UserAssetDao::TYPE_TRANSFER, '兑换');
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            throw new ApiError('Exchange failed');
        }
        return $this->success([], 200, 'message.success');
    }


    //币种提现
    public function withdraw(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);
        $data = $request->all();
        $user = $request->user();
        if (!$user->tron_address) {
            return $this->fail('Please bind TRON address');
        }
        $currency = Currency::findOrFail($data['currency_id']);

        if ($currency->code == 'DGFY' || $currency->status != 1) {
            throw new ApiError('message.asset_not_withrawal');
        }

        $amount = $data['amount'];

        if ($amount < 1) {
            return $this->error('message.withdrawal_amount_less_than_1');
        }


        $userAsset = UserAsset::where('user_id', $user->id)->where('currency_id', $currency->id)->first();
        if (!$userAsset) {
            return $this->error('message.insufficient_balance');
        }
        if ($userAsset->amount < $amount) {
            return $this->error('message.insufficient_balance');
        }




        //手续费
        //$fee = 0;
        $fee = round($amount * $currency->percentage_fee  + $currency->fixed_fee, 8);
        // $amount = $amount - $fee;
        if ($amount - $fee <= 10e-8) {
            return $this->error('message.withdrawal_fail_with_fee');
        }
        $this->userAssetDao->createWithdrawOrder($userAsset, $currency, $amount, $fee, $user->tron_address);
        return $this->success([], 200, 'message.success');
    }
    //提币订单记录
    public function withdrawOrders(Request $request)
    {
        $user = $request->user();
        $orders = $user->withdrawalOrders()->orderBy('id', 'desc')->paginate(10);
        return $this->success([
            'list' => $orders->items(),
            'total' => $orders->total(),
        ]);
    }

    //获取充值地址
    public function depositAddress(Request $request)
    {

        $request->validate([
            'currency_id' => 'required|integer',
            'amount' => 'nullable|numeric',
        ]);

        $amount = $request->get('amount', 10);
        $address = ''; // get_system_config('deposit_tron_address', 'TLdC9xAsu7tKwx4wCpici7jRiu6JgFKxVA');
        $currency = Currency::findOrFail($request->get('currency_id'));

        //限制充值币种
        $allow_currency = get_system_config('allow_deposit_currency', ['USDT', 'TRX']);
        if (!in_array($currency->code, $allow_currency)) {
            return $this->fail('Invalid currency');
        }
        $userAsset = UserAsset::firstOrCreate(
            ['user_id' => $request->user()->id, 'currency_id' => $currency->id],
            ['amount' => 0]
        );
        $scene = get_system_config('pay_scene', 'dfpay'); //dfpay,nmpay
        //创建充值订单
        $order =  $this->userAssetDao->createDepositOrder($userAsset, $currency,  $amount, 0, $request->user()->tron_address, $address);
        $payservice = new PayService($scene);
        $SF_res = $payservice->makeSFOrder($order);
        if ($SF_res['code'] != 200) {
            return $this->error($SF_res['msg']);
        } else {
            $order->destination_address = $SF_res['data'];
            $order->scene = $scene;
            $order->save();
        }
        return $this->success(['address' =>  $order->destination_address, 'order_id' => $order->id]);
    }

    //获取会员等级列表
    public function getMembershipList(Request $request)
    {
        $list = MembershipLevel::get(['name', 'id', 'level']);
        return $this->success($list);
    }



    public function notifyTest(Request $request, $sence)
    {
        $data = $request->all();
        info("支付回调", [
            'data' => $data
        ]);
        $service = new PayService($sence);
        return  $service->commonNotify($data);

        // die("success");
    }
}
