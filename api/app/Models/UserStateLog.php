<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStateLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'old_value',
        'new_value',
        'changed_by',
    ];
}
