<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPoolCycleItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'days',
        'type',
        'daily_rate',
        'compound',
        'df_currency_id',
        "df_rate",
        "limit",
        'leader_rebate_rate',
        'leader_referral_fee_rate'
    ];

    public function df_currency()
    {
        return $this->belongsTo(Currency::class, 'df_currency_id');
    }
}
