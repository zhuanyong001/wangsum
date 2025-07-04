<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'balance_before',
        'balance_after',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
