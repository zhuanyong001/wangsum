<?php

namespace App\Models;

use App\Dao\UserDao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'level',
        'participation_commission',
        'equal_level_commission',
        'pool_amount_usdt',
        'l8_pool_amount_usdt',
        'direct_lower_levels',
        'umbrella_people_count',
        'name_cn',
        'remarks',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];



    /**
     * 判断当前满足的等级 function
     *
     * @param [type] $level  
     * @return boolean
     */
    public static function isMeetLevel($level, $user)
    {
        //$le 下个等级
        $le =  $level + 1;
        $vip = MembershipLevel::where('level', $le)->first();
        if (!$vip) {
            return $level;
        }
        //矿池金额
        if ($vip->pool_amount_usdt > 0) {
            if ($user->pool_amount_usdt < $vip->pool_amount_usdt) {
                return $level;
            }
        }

        //下8代矿池金额
        if ($vip->l8_pool_amount_usdt > 0) {
            if ($user->l8_pool_amount_usdt < $vip->l8_pool_amount_usdt) {
                return $level;
            }
        }


        //团队人数
        if ($vip->umbrella_people_count > 0) {
            if ($user->team_count < $vip->umbrella_people_count) {
                return $level;
            }
        }
        //直推人数
        if ($vip->direct_lower_levels > 0) {
            $direct_lower_levels = UserDao::getDirectTeamCount($user->id, $level);
            // $l1_users = User::where('referrer_id', $user->id)->get();
            // foreach ($l1_users as $l1_user) {
            //     /** @var User $l1_user  */
            //     //激活状态才算
            //     if ($l1_user->getPoolAmountUsd() < 100) continue;
            //     $l1_user->getMembershipLevel();
            //     if ($l1_user->curr_level >= $level) $direct_lower_levels++;
            //     if ($direct_lower_levels >= $vip->direct_lower_levels) break;
            // }
            if ($direct_lower_levels < $vip->direct_lower_levels) {
                $user->direct_lower_levels = $direct_lower_levels;
                return $level;
            }
        }







        //判断下个等级
        return  self::isMeetLevel($le, $user);
    }
}
