<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class I18nLang extends Model
{
    use HasFactory;
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
