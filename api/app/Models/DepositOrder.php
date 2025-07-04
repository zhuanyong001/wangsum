<?php

namespace App\Models;

use App\Traits\Web3Trait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositOrder extends Model
{
    use Web3Trait;
    //待充值
    const STATUS_WAIT = 1;
    const STATUS_PENDING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_FAIL = -1;

    use HasFactory;
    protected $fillable = [
        'order_no', //订单号格式'RC' . date('YmdHis') . mt_rand(1000, 9999),
        'user_id',
        'currency',
        'currency_id',
        'amount',
        'fee',
        'source_address',
        'destination_address'
    ];

    public static function sumAmountByUserTeam($user_id)
    {

        $team_ids = TeamRelation::where('inviter_id', $user_id)->pluck('invitee_id'); //包含自己
        $team_ids[] = $user_id;
        $res = DepositOrder::WithoutInternalIds()->whereIn('user_id', $team_ids)
            ->where('status', DepositOrder::STATUS_SUCCESS)
            ->selectRaw('sum(amount-fee) as amount,currency_id')
            ->with('currency:id,name,price')
            ->groupBy('currency_id')->get();

        return $res;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
