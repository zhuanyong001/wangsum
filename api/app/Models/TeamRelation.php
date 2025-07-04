<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamRelation extends Model
{
    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'level',
    ];


    // 邀请人
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    // 被邀请人
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id')->with('membership:level,name');
    }

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
