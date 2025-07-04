<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterRoom extends Model
{
    use HasFactory;
    protected $fillable = ['wdxAccount', 'userIp', 'gameId', 'roomId', 'accountId', 'playerId', 'machineCode'];
}
