<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use App\Traits\Web3Trait;

class MiningPoolOrder extends Model
{
    use HasFactory;
    use Web3Trait;

    const TYPE_CURRENT_DEPOSIT = 1; //活期
    const TYPE_FIXED_DEPOSIT = 2; //定期

    const STATUS_RUNING = 1; //进行中
    const STATUS_SUCCESS = 2; //完成的
    protected $fillable = [
        'mining_pool_id',
        'order_no',
        'user_id',
        'coin_code',
        'currency_id',
        'amount',
        'daily_rate',
        'cycle',
        'status',
        'expire_time',
        'start_time',
        'type',
        'compound',
        "cate",
        "df_currency_id",
        "df_rate",
        "df_amount"
    ];

    public function miningPool()
    {
        return $this->belongsTo(MiningPool::class);
    }

    public function df_currency()
    {
        return $this->belongsTo(Currency::class, 'df_currency_id');
    }

    public static function sumAmountByUserTeam($user_id)
    {
        $team_ids = TeamRelation::whereHas('invitee', function ($query) {
            $query->where('status', User::STATUS_NORMAL);
        })->where('inviter_id', $user_id)->pluck('invitee_id'); //包含自己
        $team_ids[] = $user_id;

        $query = MiningPoolOrder::whereIn('user_id', $team_ids)
            ->where('status', MiningPoolOrder::STATUS_RUNING)
            //->where('currency_id', '!=', 8) // 排除 代币
            ->selectRaw('sum(amount) as amount,currency_id')
            ->with('currency:id,name,price')
            ->groupBy('currency_id');

        $res['current_deposit'] = (clone $query)->where('type', MiningPoolOrder::TYPE_CURRENT_DEPOSIT)->get();
        $res['fixed_deposit'] =  (clone $query)->where('type', MiningPoolOrder::TYPE_FIXED_DEPOSIT)->get();
        return $res;
    }


    public function getDfAmount()
    {
        //是否即时利率结算
        $use_df_rate =  get_system_config('use_df_rate', 0);
        if (!$use_df_rate)  return $this->df_amount;
        if (!$this->df_currency) return 0;
        if (!$this->df_currency->price) return 0;
        $df_amount = $this->amount * $this->df_rate * $this->currency->price / $this->df_currency->price;
        return $df_amount;
    }

    public function getUsdAmount()
    {
        return $this->amount * $this->currency->price;
    }
}
