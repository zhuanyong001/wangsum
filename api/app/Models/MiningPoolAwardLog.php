<?php

namespace App\Models;

use App\Traits\Web3Trait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPoolAwardLog extends Model
{
    use HasFactory, Web3Trait;

    //枚举类型
    const TYPE_REWARD = 1; //订单奖励
    const TYPE_REBATE_L1 = 2; //直属下级返利
    const TYPE_REBATE_LX = 3; //间接下级返利


    protected $fillable = [
        'mining_pool_order_id',
        'user_id',
        'amount',
        'trade_no',
        'type',
        'from_user_id'
    ];

    public function miningPoolOrder()
    {
        return $this->belongsTo(MiningPoolOrder::class);
    }


    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public static function sumAmountsByUserId($userId)
    {
        return self::where('mining_pool_award_logs.user_id', $userId)->whereIn('mining_pool_award_logs.type', [self::TYPE_REBATE_L1, self::TYPE_REBATE_LX])
            ->leftJoin('mining_pool_orders', 'mining_pool_orders.id', '=', 'mining_pool_award_logs.mining_pool_order_id')
            ->selectRaw('sum(mining_pool_award_logs.amount) as amount, mining_pool_orders.currency_id as currency_id')->with('currency:id,name,code,price')
            ->groupBy('currency_id')
            ->get();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
