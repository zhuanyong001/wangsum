<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'menus',
        'permissions',
    ];

    protected $casts = [
        'menus' => 'array',
    ];
}
