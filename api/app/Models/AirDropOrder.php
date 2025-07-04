<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirDropOrder extends Model
{
    const STATUS_CLOSE = 0; //关闭
    const STATUS_RUNING = 1; //开启

    use HasFactory;
    protected $fillable = ['order_no', 'min_usd_amount', 'amount_value', 'currency_id', 'is_proportion', 'start_time', 'end_time', 'status', 'remark'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
