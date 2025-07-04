<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class I18nLangKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'key',
    ];

    public function msgs()
    {
        return $this->hasMany(I18nLangMsg::class, 'keyID', 'id');
    }
}
