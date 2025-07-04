<?php

namespace App\Models;

use App\Traits\Web3Trait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPoolOrder extends Model
{
    use HasFactory, Web3Trait;
    const STATUS_RUNING = 1; //进行中
    const STATUS_SUCCESS = 2; //完成的
    const STATUS_OVERDUE = 3; //逾期
    const STATUS_CLOSE = 4;   //平仓
    protected $fillable = [];

    public function loanPool()
    {
        return $this->belongsTo(LoanPool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanCoin()
    {
        return $this->belongsTo(Currency::class, 'loan_coin_id')->select('id', 'name', 'code', 'icon');
    }

    public function pledgeCoin()
    {
        return $this->belongsTo(Currency::class, 'pledge_coin_id')->select('id', 'name', 'code', 'icon');
    }
}
