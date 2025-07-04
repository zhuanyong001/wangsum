<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPool extends Model
{
    use HasFactory;
    const CATE_POOL = 1; //矿池类型
    const CATE_DEP = 2; //存款类型


    protected $fillable = [
        'name',
        'coin',
        'cycle',
        'rate',
        'status',
        'type',
        'description',
        'base_total_amount',
        'sort',
        'cate'
    ];
    protected $casts = [
        'cycle' => 'array',
        'coin' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function coins()
    {
        $this->coins =  Currency::whereIn('id', $this->coin)->get(['id', 'name', 'code', 'price']);
    }
    public function cycles()
    {
        $this->cycles = MiningPoolCycleItem::with('df_currency:id,name,price,change_24h')->whereIn('id', $this->cycle)->get(['id', 'days', 'type', 'daily_rate', 'df_rate', 'df_currency_id']);
    }

    public function sumAmount()
    {
        $this->sumAmount = MiningPoolOrder
            ::where('mining_pool_id', $this->id)
            ->with('currency:id,name,price')
            ->where('status', 1)
            ->selectRaw("sum(amount)+{$this->base_total_amount} as amount, currency_id")
            ->groupBy('currency_id')->get();
    }
}
