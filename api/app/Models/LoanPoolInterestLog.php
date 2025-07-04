<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPoolInterestLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_no',
        'user_id',
        'interest_amount',
        'user_id',
        'loan_pool_order_id',
        'trade_no'
    ];
}
