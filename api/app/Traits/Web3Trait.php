<?php

namespace App\Traits;

use App\Models\Currency;
use App\Models\TeamRelation;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Web3Trait
{
    /**
     * 查询范围：排除指定的内部记录
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutInternalIds(Builder $query, $ids = [])
    {
        if (empty($ids)) {
            $ids = User::where('is_internal', 1)->pluck('id')->toArray();
        }
        return $query->whereNotIn('user_id', $ids);
    }
    //
    public function scopeWithAdminAuth(Builder $query)
    {
        $agent = Auth::user();
        if ($agent->is_super) {
            return $query;
        }
        $user_id = $agent->user_id;
        $team_ids = TeamRelation::where('inviter_id', $user_id)->pluck('invitee_id');
        //包含自己
        $team_ids[] = $user_id;
        return $query->whereIn('user_id', $team_ids);
    }
    //
    public function scopeWithUsername(Builder $query, $username)
    {
        return $query->whereHas('user', function ($query) use ($username) {
            $query->where('name', 'like', "%$username%")->orWhere('tron_address', 'like', "%$username%")->orWhere('share_code', 'like', "%$username%");
        });
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



    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
