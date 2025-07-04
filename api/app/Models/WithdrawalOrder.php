<?php

namespace App\Models;

use App\Traits\Web3Trait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalOrder extends Model
{
    use HasFactory;
    use Web3Trait;

    const STATUS_WAIT = 1; //待审核
    const STATUS_PENDING = 2; //已发送 待确认
    const STATUS_SUCCESS = 3; //成功
    const STATUS_EXC = 4; //异常
    const STATUS_FAIL = -1; //失败


    protected $fillable = [
        'order_no',
        'user_id',
        'currency',
        'currency_id',
        'amount',
        'fee',
        'destination_address',
        'status',
    ];


    public static function sumAmountByUserTeam($user_id)
    {
        $team_ids = TeamRelation::where('inviter_id', $user_id)->pluck('invitee_id'); //包含自己
        $team_ids[] = $user_id;
        $res = WithdrawalOrder::WithoutInternalIds()->whereIn('user_id', $team_ids)
            ->where('status', WithdrawalOrder::STATUS_SUCCESS)
            ->selectRaw('sum(amount-fee) as amount,currency_id')
            ->with('currency:id,name,price')
            ->groupBy('currency_id')->get();
        return $res;
    }
}
