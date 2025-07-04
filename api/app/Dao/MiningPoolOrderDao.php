<?php

namespace App\Dao;

use App\Jobs\AirDropJobs;
use App\Models\AirDropOrder;
use App\Models\MiningPool;
use App\Models\MiningPoolOrder;

class MiningPoolOrderDao
{

    public static function getUSDAmount($list)
    {
        $total = 0;
        foreach ($list as $item) {
            $total += $item->amount * $item->currency->price;
        }
        return $total;
    }

    //当前池子中的总金额
    public static function getUserSumRunningOrderAmount2USD($user_id)
    {
        $orders = MiningPoolOrder::where(['user_id' => $user_id, 'status' => MiningPoolOrder::STATUS_RUNING])->with('currency:name,id,code,price')->get();
        return self::getUSDAmount($orders);
    }

    //获取存款池子的金额
    public static function getUserCycleDepositPoolUSDAmount($user_id, $cycle)
    {
        $orders = MiningPoolOrder::where('cycle', $cycle)->where(['user_id' => $user_id, 'status' => MiningPoolOrder::STATUS_RUNING, 'cate' => MiningPool::CATE_DEP])->with('currency:name,id,code,price')->get();
        return self::getUSDAmount($orders);
    }

    //获取存款池子的USDT币种的金额
    public static function getUserCycleUSDTDepositPoolAmount($user_id, $cycle)
    {
        $sum_usdt = MiningPoolOrder::where('cycle', $cycle)->where(['user_id' => $user_id, 'status' => MiningPoolOrder::STATUS_RUNING, 'cate' => MiningPool::CATE_DEP, 'coin_code' => 'USDT'])->with('currency:name,id,code,price')->sum('amount');
        return $sum_usdt;
    }

    //空投
    public static function makeAirDrop(MiningPoolOrder $miningPoolOrder)
    {
        //查询是否有开启空投
        $date = date('Y-m-d H:i:s');
        $air_drop_order = AirDropOrder::where('status', AirDropOrder::STATUS_RUNING)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->first();
        if (!$air_drop_order) {
            return;
        }
        //是否满足条件
        if ($air_drop_order->min_usd_amount > $miningPoolOrder->getUsdAmount()) {
            return;
        }
        //推送job
        AirDropJobs::dispatch($air_drop_order, $miningPoolOrder);
    }
}
