<?php

namespace App\Http\Controllers\admin;

use App\Dao\UserAssetDao;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\WithdrawalOrder;
use App\Services\Pay\PayService;
use App\Services\TronService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalOrderController extends Controller
{
    protected $tronService;

    public function __construct(TronService $tronService)
    {
        $this->tronService = $tronService;
    }
    /**
     * Display a listing of the withdrawal orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->get('size', 20);
        $date = $request->get('date', []);
        $username = $request->get('username', '');
        $model = WithdrawalOrder::with('user');
        if (count($date) == 2) {
            $model = $model->whereBetween('created_at', $date);
        }
        if ($username) {
            $model = $model->whereHas('user', function ($query) use ($username) {
                $query->where('name', 'like', "%$username%")->whereOr('tron_address', 'like', "%$username%")->whereOr('share_code', 'like', "%$username%");
            });
        }
        //时间查询
        $orders = $model->orderByDesc('id')->paginate($size);
        $orders->getCollection()->each(function ($item) {
            $item->user->makeVisible('remark');
        });
        return $this->success(['list' => $orders->items(), 'total' => $orders->total()]);
    }

    /**
     * 待审核列表
     *
     */

    public function pendingList(Request $request)
    {
        $size = $request->get('size', 20);
        $date = $request->get('date', []);
        $username = $request->get('username', '');
        $model = WithdrawalOrder::with('user');
        if (count($date) == 2) {
            $model = $model->whereBetween('created_at', $date);
        }
        if ($username) {
            $model = $model->whereHas('user', function ($query) use ($username) {
                $query->where(function ($q) use ($username) {
                    $q->where('name', 'like', "%$username%")
                        ->orWhere('tron_address', 'like', "%$username%")
                        ->orWhere('share_code', 'like', "%$username%");
                });
            });
        }
        $orders = $model->whereIn('status', [1, 4])->orderByDesc('id')->paginate($size);
        $orders->getCollection()->each(function ($item) {
            $item->user->makeVisible('remark');
        });
        return $this->success(['list' => $orders->items(), 'total' => $orders->total()]);
    }



    public function processWithdrawal(Request $request, $id)
    {
        $order = WithdrawalOrder::findOrFail($id);

        if ($order->status != WithdrawalOrder::STATUS_WAIT && $order->status != WithdrawalOrder::STATUS_EXC) {
            return $this->error('Only pending orders can be processed');
        }
        $order_owner = $order->user;
        if ($order_owner->is_internal == 1) {
            $order->status = WithdrawalOrder::STATUS_SUCCESS; // 直接设置为成功
            $order->processed_at = now();
            $order->completed_at = date('Y-m-d H:i:s');
            $order->save();
            return $this->success();
        }

        // $contractAddress = $this->getContractAddress($order->currency_id);
        $currency = Currency::findOrFail($order->currency_id);
        try {
            // $amount = $order->amount - $order->fee;
            // if ($currency->code !== 'TRX') {
            //     $result = $this->tronService->sendTransaction($order->destination_address, $amount, $currency->contract_address, $currency->unit); // 转换为最小单位
            // } else {
            //     $result = $this->tronService->sendTransaction($order->destination_address, $amount); // 转换为最小单位
            // }
            $scene = get_system_config('withdraw_scene', 'dfpay'); //dfpay,nmpay
            $payservice = new PayService($scene);
            $result = $payservice->makeXFOrder($order);
            $order->scene = $scene;
            if (isset($result['result']) && $result['result'] === true) {
                $order->status = WithdrawalOrder::STATUS_PENDING; // 设置为处理中
                $order->transaction_id = $result['txID'] ?? ""; // 假设返回结果包含交易ID
                $order->processed_at = now();
                $order->save();
                // 异步检查交易状态
                //$this->checkTransactionStatus($order);
                return $this->success(); // 返回成功
            } else {
                $order->status = WithdrawalOrder::STATUS_EXC; // 设置为异常
                $order->response_message = json_encode($result);
                $order->save();
                return $this->error('Transaction failed');
            }
        } catch (\Exception $e) {
            // throw $e;
            $order->status = WithdrawalOrder::STATUS_EXC; // 设置为异常
            $order->response_message = $e->getMessage();
            $order->save();
            return $this->error('Transaction failed:' . $e->getMessage());
        }
    }

    //拒绝提现
    public function rejectWithdrawal(UserAssetDao $userAssetDao, Request $request, $id)
    {
        $order = WithdrawalOrder::findOrFail($id);

        if ($order->status != WithdrawalOrder::STATUS_WAIT && $order->status != WithdrawalOrder::STATUS_EXC) {
            return $this->error('Only pending orders can be rejected');
        }
        DB::beginTransaction();
        try {
            $order->status = WithdrawalOrder::STATUS_FAIL; // 设置为失败
            $order->save();
            // 返还用户资产
            $userAsset = $order->user->assets()->where('currency_id', $order->currency_id)->first();
            $userAssetDao->updateUserAsset($userAsset, $order->amount, UserAssetDao::TYPE_WITHDRAW_FAIL, 'Withdrawal rejected');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info('提现拒绝异常:' . $e->getMessage());
            return $this->error($e->getMessage());
        }
        return $this->success();
    }

    protected function getContractAddress($currency_id)
    {
        $currency = Currency::find($currency_id);
        return $currency->contract_address ?? null;
    }

    protected function checkTransactionStatus($order)
    {
        // 使用队列或其他异步处理方法
        dispatch(function () use ($order) {
            $tronService = app(TronService::class);
            $status = 'pending';
            while ($status == 'pending') {
                sleep(10); // 每10秒检查一次
                $transactionInfo = $tronService->getTransactionInfoById($order->transaction_id);
                if (isset($transactionInfo['ret']) && $transactionInfo['ret'][0]['contractRet'] == 'SUCCESS') {
                    $status = 'success';
                    $order->status = 3; // 设置为完成
                    $order->save();
                    Log::info("Transaction {$order->transaction_id} completed successfully.");
                } elseif (isset($transactionInfo['ret']) && $transactionInfo['ret'][0]['contractRet'] == 'REVERT') {
                    $status = 'failed';
                    $order->status = -1; // 设置为失败
                    $order->save();
                    Log::error("Transaction {$order->transaction_id} failed.");
                }
            }
        });
    }
}
