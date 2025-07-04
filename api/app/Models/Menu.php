<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'badge',
        'cacheable',
        'component',
        'icon',
        'link',
        'name',
        'parent',
        'path',
        'permission',
        'renderMenu',
        'target',
        'title',
        'sort'
    ];
}
