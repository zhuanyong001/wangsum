<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'contract_address',
        'price',
        'price_ratio',
        'price_url',
        'icon',
        'status',
        'sort',
        'fixed_fee',
        'percentage_fee',
        'unit',
        'change_24h'
    ];
}
