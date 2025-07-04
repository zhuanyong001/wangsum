<?php

namespace App\Models;

use App\Traits\Web3Trait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssetLog extends Model
{
    use HasFactory, Web3Trait;
    protected $fillable = [
        "user_asset_id",
        'user_id',
        'currency_id',
        'amount',
        'type',
        'description',
    ];

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function userAsset()
    {
        return $this->belongsTo(UserAsset::class)->with('currency:id,name,code,price,change_24h');
    }
}
