<?php

namespace App\Models;

use App\Traits\Web3Trait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPool extends Model
{
    use HasFactory, Web3Trait;
    protected $fillable = [
        'name',
        'loan_coin_ids',
        'pledge_coin_ids',
        'loan_ratio',
        'loan_rate',
        'status',
        'sort'
    ];
    protected $casts = [
        'loan_coin_ids' => 'array',
        'pledge_coin_ids' => 'array'
    ];

    public function loan_coins()
    {
        $this->loan_coins =  Currency::whereIn('id', $this->loan_coin_ids)->get(['id', 'name', 'code', 'icon', 'price']);
    }
    public function pledge_coins()
    {
        $this->pledge_coins = Currency::whereIn('id', $this->pledge_coin_ids)->get(['id', 'name', 'code', 'icon', 'price']);
    }
}
