<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'currency_id',
        'amount',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->select('id', 'icon', 'name', 'code', 'price', 'change_24h', 'fixed_fee', 'percentage_fee');
    }


    public function getPoolsAssets()
    {
        $this->pools_amount =  MiningPoolOrder::where('user_id', $this->user_id)->where('currency_id', $this->currency_id)->where('status', 1)->sum('amount');
    }
    public function getWithdrawalAmount()
    {
        $this->withdrawal_amount = WithdrawalOrder::where('user_id', $this->user_id)->where('currency_id', $this->currency_id)->where('status', WithdrawalOrder::STATUS_SUCCESS)->sum('amount');
    }
    public function getDepositAmount()
    {
        $this->deposit_amount = DepositOrder::where('user_id', $this->user_id)->where('currency_id', $this->currency_id)->where('status', DepositOrder::STATUS_SUCCESS)->sum('amount');
    }
    public function getTeamAssets()
    {
        $team_ids = TeamRelation::whereHas('invitee', function ($query) {
            $query->where('status', User::STATUS_NORMAL);
        })->where('inviter_id', $this->user_id)->pluck('invitee_id');
        //包含自己
        $team_ids[] = $this->user_id;

        $this->team_amount = MiningPoolOrder::whereIn('user_id', $team_ids)->where('currency_id', $this->currency_id)->where('status', MiningPoolOrder::STATUS_RUNING)->sum('amount');
    }
}
