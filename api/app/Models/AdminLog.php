<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'note', 'url', 'method', 'ip', 'status', 'response', 'parameters', 'admin_id',
    ];
}
