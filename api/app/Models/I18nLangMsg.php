<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class I18nLangMsg extends Model
{
    use HasFactory;

    public function lang()
    {
        return $this->hasOne(I18nLang::class, 'id', 'langID');
    }
}
