<?php

namespace App\Http\Controllers\api;

use App\Dao\UserAssetDao;
use App\Dao\UserDao;
use App\Exceptions\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\LoanPoolOrder;
use App\Models\MiningPool;
use App\Models\MiningPoolCycleItem;
use App\Models\MiningPoolOrder;
use App\Models\TeamRelation;
use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MiningPoolController extends Controller
{
    //获取矿池列表
    public function getMiningPoolList(Request $request, $cate = 'pool')
    {

        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $type = $request->input('type', 0);
        $only_my = $request->input('my', 0);
        if ($cate == 'depoosit') {
            $cate = MiningPool::CATE_DEP;
        } else {
            $cate = MiningPool::CATE_POOL;
        }
        $map = [];
        $pools = MiningPool::where('status', 1)->where('cate', $cate);
        if ($type) {
            // $map['type'] = $type;
            $cycles =  MiningPoolCycleItem::where('type', $type)->pluck('id');
            $pools->where(function ($query) use ($cycles) {
                foreach ($cycles as $id) {
                    $query->orWhereJsonContains('cycle', $id);
                }
            });
        }
        if ($only_my) {
            $user = Auth::guard('sanctum')->user();
            $has_id = MiningPoolOrder::where('user_id', $user->id)->where('status', 1)->pluck('mining_pool_id');
            $pools->whereIn('id', $has_id);
        }

        $data = $pools->where($map)->paginate($pageSize, ['*'], 'page', $page);
        $list = $data->items();
        foreach ($list as $item) {
            $item->coins();
            $item->cycles();
            $item->sumAmount();

            if (Auth::guard('sanctum')->check()) {
                $user = Auth::guard('sanctum')->user();
                $item->my_pool = MiningPoolOrder::selectRaw('sum(amount) as amount, SUM(total_award) as total,currency_id')->with('currency:name,id,code,price')
                    ->where(['user_id' => $user->id, 'mining_pool_id' => $item->id, 'status' => 1, 'cate' => $cate])
                    ->groupBy('currency_id')->get();
            }
        }
        return $this->success(['list' => $list, 'total' => $data->total()]);
    }


    //创建质押订单
    public function createOrder(Request $request, UserAssetDao $userAssetDao, UserDao $userDao)
    {
        $data = $request->all();
        $user = $request->user();
        $pool = MiningPool::findOrFail($data['mining_pool_id']);
        if (!in_array($data['currency_id'], $pool->coin) || !in_array($data['cycle'], $pool->cycle)) {
            throw new ApiError('Invalid data');
        }
        $cycleItem = MiningPoolCycleItem::findOrFail($data['cycle']);
        $currency = Currency::findOrFail($data['currency_id']);
        $userAsset = UserAsset::where(['user_id' => $user->id, 'currency_id' => $data['currency_id']])->first();
        if (!$userAsset) {
            throw new ApiError('message.insufficient_balance');
        }

        if ($cycleItem->limit > 0) {

            $cur_count = $userDao->getUserMiningPoolCycleOrderCount($user, $pool->id, $cycleItem->days, $currency->id);

            if ($cur_count >= $cycleItem->limit) {
                throw new ApiError('message.mining_pool_cycle_limit');
            }
        }


        if (!$user->referrer_id) {
            if (!isset($data['share_code']) && !$data['share_code']) {
                throw new ApiError('Invalid referrer');
            }
            $inviter = User::where('share_code', $data['share_code'])->first();
            if (!$inviter) {
                throw new ApiError('Invalid referrer');
            }
        }
        DB::beginTransaction();
        try {

            if (isset($inviter)) {
                DB::update('update users set referrer_id = ? where id = ?', [$inviter->id, $user->id]);
                $userDao->createTeamRelation($inviter->id, $user->id);
            }
            $userAsset->lockForUpdate()->find($userAsset->id);
            //活期
            if ($cycleItem->type == 1) {
                //活期存款
                $userAssetDao->MiningPoolCurrentOrder($pool, $cycleItem, $userAsset, $currency, $data['amount'], 0);
            } else {
                //定期存款
                $userAssetDao->createMiningPoolOrder($pool, $cycleItem, $userAsset, $currency, $data['amount'], $data['cycle']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success([], 200, 'message.success');
    }

    //获取质押订单列表
    public function getOrderList(Request $request, $cate = 'pool')
    {
        $request->validate([
            'status' => 'required|integer'
        ]);

        $pageSize = $request->input('size', 10);
        $status = $request->input('status', 1);
        $map = [
            'user_id' => $request->user()->id,
        ];
        if ($cate == 'deposit') {
            $map['cate'] = MiningPool::CATE_DEP;
        } else {
            $map['cate'] = MiningPool::CATE_POOL;
        }
        if ($status) {
            $map['status'] = $status;
        }

        $model = MiningPoolOrder::with('miningPool:name,id,description')->with('currency:id,name,code,icon,price')->with('df_currency:id,name,code,icon,price')->where($map);
        $data = $model->paginate($pageSize);

        if ($cate == 'deposit') {
            foreach ($data->items() as &$item) {
                $item->df_amount = $item->getDfAmount();
            }
        }


        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }
    //获取质押订单详情
    public function getOrderDetail(Request $request, $id)
    {
        $data = MiningPoolOrder::with('miningPool:name,id,description')->with('currency:id,name,code,icon,price')->with('df_currency:id,name,code,icon,price')->findOrFail($id);
        $data->df_amount = $data->getDfAmount();

        return $this->success($data);
    }

    //活期质押提现
    public function withdraw(Request $request, UserAssetDao $userAssetDao)
    {
        $request->validate([
            'id' => 'required',
            //金额为正数
            'amount' => 'required|numeric'
        ]);
        $id = $request->input('id');
        $amount = $request->input('amount');
        if ($amount <= 0) {
            throw new ApiError('Invalid data');
        }
        $user = $request->user();
        $order = MiningPoolOrder::where(['user_id' => $user->id, 'status' => 1])->where('amount', '>=', $amount)->findOrFail($id);
        if ($order->type != MiningPoolOrder::TYPE_CURRENT_DEPOSIT) {
            throw new ApiError('Invalid data');
        }

        $userAssetDao->withdrawMiningPoolCurrentOrder($order, $amount);
        return $this->success([], 200, 'message.success');
    }

    //获取矿池统计
    public function miningPoolStatistics(Request $request)
    {
        //redis 缓存下结果1分钟
        $redis = app('redis.connection');
        $key = 'mining_pool_statistics';
        $res = $redis->get($key);
        if ($res) {
            return $this->success(json_decode($res, true));
        }
        $total_data = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->groupBy('currency_id')->get();
        $currency_ids = Currency::whereIn('code', ['USDT', 'TRX'])->pluck('id');
        $data1 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->whereIn('currency_id', $currency_ids)->groupBy('currency_id')->get();
        $data2 = $total_data;
        $GDFY_ID = Currency::where('name', 'DGFY')->first()->id ?? 0;
        $data3 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->where('currency_id', $GDFY_ID)->groupBy('currency_id')->get();
        $userAssetDao = new UserAssetDao();
        //流动性挖矿价值（定期挖矿所有货币的总金额）
        $data4 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->where(['cate' => 1, 'type' => 2])->groupBy('currency_id')->get();
        //3.质押挖矿价值  （活期挖矿所有货币的总金额）
        $data5 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->where(['cate' => 1, 'type' => 1])->groupBy('currency_id')->get();
        //4：矿池存款价值 （存款的货币的总金额）
        $data6 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->where(['cate' => 2])->groupBy('currency_id')->get();



        $config = json_decode(get_system_config('HOME_DATA_STATISTICS', json_encode([
            "TOTAL_PLEDGE_VALUE" => 0,
            "STABLE_COIN_PLEDGE_VALUE" => 0,
            "DRAGONFLYSWAP_PLEDGE_VALUE" => 0,
            "PLEDGE_VALUE_OF_MINING_POOL" => 0,
            "DATA4" => 0,
            "DATA5" => 0,
            "DATA6" => 0,
        ])), true);

        $sum = $userAssetDao->amountListToTotalUSD($total_data) + floatval($config['TOTAL_PLEDGE_VALUE'] ?? 0);
        $sum1 = $userAssetDao->amountListToTotalUSD($data1) + floatval($config['STABLE_COIN_PLEDGE_VALUE'] ?? 0);
        $sum2 = $userAssetDao->amountListToTotalUSD($data2) + floatval($config['DRAGONFLYSWAP_PLEDGE_VALUE'] ?? 0);
        $sum3 = $userAssetDao->amountListToTotalUSD($data3) + floatval($config['PLEDGE_VALUE_OF_MINING_POOL'] ?? 0);
        $sum4 = $userAssetDao->amountListToTotalUSD($data4) + floatval($config['DATA4'] ?? 0);
        $sum5 = $userAssetDao->amountListToTotalUSD($data5) + floatval($config['DATA5'] ?? 0);
        $sum6 = $userAssetDao->amountListToTotalUSD($data6) + floatval($config['DATA6'] ?? 0);
        $sum = $sum4 + $sum5 + $sum6;
        $res = compact('sum', 'sum1', 'sum2', 'sum3', 'sum4', 'sum5', 'sum6');
        $redis->setex($key, 60, json_encode($res));
        return $this->success($res);
    }

    public function getDepositPoolStat(Request $request)
    {
        $data1 = MiningPoolOrder::selectRaw('sum(amount) as amount,currency_id')->with('currency:name,id,code,price')->where('status', 1)->groupBy('currency_id')->get();
        return $this->success($data1);
    }


    public function getAllPoolOrderList(Request $request)
    {
        $pageSize = $request->input('size', 10);
        $status = $request->input('status', 1);
        $map = [];
        $user = Auth::guard('sanctum')->user();
        $pool_order_ids = MiningPoolOrder::where('user_id', $user->id)->where('status', 2)->select('id', 'updated_at', DB::raw("'pool' as table_name"));
        $loan_order_ids = LoanPoolOrder::where('user_id', $user->id)->whereIn('status', [2, 3, 4])->select('id', 'updated_at', DB::raw("'loan' as table_name"));

        $res  = $pool_order_ids->union($loan_order_ids)->orderBy('updated_at', 'desc')->paginate($pageSize);
        $items = $res->items();
        foreach ($items as $k => $item) {
            if ($item->table_name == 'pool') {
                $order = MiningPoolOrder::with('miningPool:name,id')->with('currency:id,name,code,icon,price')->with('df_currency:id,name,code,icon,price')->find($item->id);
                if ($order) {
                    $order->df_amount = $order->getDfAmount();
                }
            } else {
                $order = LoanPoolOrder::with('loanCoin', 'pledgeCoin')->find($item->id);
            }
            $items[$k] = $order;
            $items[$k]['order_type'] = $item->table_name;
        }


        return $this->success(['list' => $items, 'total' => $res->total()]);
    }
}
