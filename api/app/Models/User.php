<?php

namespace App\Models;

use App\Dao\UserAssetDao;
use App\Dao\UserDao;
use App\Traits\Web3Trait;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const STATUS_FROZEN = 0;
    const STATUS_NORMAL = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'device_id',
        'invite_code',
        'tron_address',
        'share_code',
        'api_token',
        'remark'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'name',
        'email',
        'email_verified_at',
        'api_token',
        'remark'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userAssetLogs()
    {
        return $this->hasMany(UserAssetLog::class)->with('userAsset:id,currency_id');
    }

    // 用户资产
    public function assets()
    {
        return $this->hasMany(UserAsset::class);
    }

    //用户推荐人
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function membership()
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level', 'level');
    }

    public function depositOrders()
    {
        return $this->hasMany(DepositOrder::class);
    }

    public function withdrawalOrders()
    {
        return $this->hasMany(WithdrawalOrder::class);
    }


    //更新会员等级
    public function getMembershipLevel()
    {
        if (!$this->id) {
            Log::info('getMembershipLevel用户不存在', ['user_id' => $this->id]);
            $this->curr_level = 1;
            return;
        }

        //查询缓存
        $level = Cache::get('user_membership_level_' . $this->id);
        if ($level) {
            $this->curr_level = $level;
            return;
        }
        //节点金额
        $this->pool_amount_usdt = UserDao::getTeamPoolAmountUsdByDeep($this->id);
        $this->l8_pool_amount_usdt = UserDao::getTeamPoolAmountUsdByDeep($this->id, 8);

        $this->team_count = TeamRelation::where('inviter_id', $this->id)->count();
        if ($this->pre_level) {
            $this->curr_level = $this->pre_level;
        } else {
            $this->curr_level = MembershipLevel::isMeetLevel(1, $this);
        }
        if ($this->curr_level !== $this->membership_level) {
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/user/level_change.log'),  // 使用动态路径
                'level' => 'info',
            ])->info('会员等级变更', ['user_id' => $this->id, 'old_level' => $this->membership_level, 'new_level' => $this->curr_level, 'pool_amount_usdt' => $this->pool_amount_usdt, 'pre_level' => $this->pre_level, 'direct_lower_levels' => UserDao::getDirectTeamCount($this->id, $this->curr_level)]);
            DB::update('update users set membership_level = ? where id = ?', [$this->curr_level, $this->id]);
        }
        //缓存会员等级
        Cache::put('user_membership_level_' . $this->id, $this->curr_level, 60);
    }





    //下级数量统计

    public function getDownStat()
    {
        $team = TeamRelation::select(DB::raw('count(id) as count, level'))
            ->where('inviter_id', $this->id)
            ->groupBy('level')
            ->get();
        $this->star_down_agent = $team;
    }


    //团队总数

    public function teamCount()
    {
        return TeamRelation::where('inviter_id', $this->id)->count();
    }
    //活期
    public function miningPoolOrders()
    {
        return $this->hasMany(MiningPoolOrder::class);
    }

    //定期订单
    public function fixedMiningPoolOrders()
    {
        return $this->hasMany(MiningPoolOrder::class)->where('type', MiningPoolOrder::TYPE_FIXED_DEPOSIT);
    }



    //获取伞下用户统计

    public function teamMiningPoolTotalAmounts()
    {
        $this->team_mining_pool_stat =  MiningPoolOrder::sumAmountByUserTeam($this->id);
    }

    public function teamDepositTotalAmounts()
    {
        $this->team_deposit_stat =  DepositOrder::sumAmountByUserTeam($this->id);
    }

    public function teamWithdrawalTotalAmounts()
    {
        $this->team_withdrawal_stat =  WithdrawalOrder::sumAmountByUserTeam($this->id);
    }


    public function getTeamActiveUserCount()
    {
        $team_ids = TeamRelation::where('inviter_id', $this->id)->pluck('invitee_id'); //包含自己
        // $team_ids[] = $this->id;
        $dao = new UserDao();
        $this->team_active_user_count =  $dao->getTeamActiveUserCount($team_ids);
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
        return $query->whereIn('id', $team_ids);
    }

    public function lastLoginIp()
    {
        return $this->hasOne(UserLoginIp::class)->latest();
    }

    public function getPoolAmountUsd()
    {
        if (!$this->id) {
            return 0;
        }
        $cache_key = 'user_pool_amount_usd:' . $this->id;
        $usd = Cache::remember($cache_key, 60 * 10, function () {
            $dao = new UserAssetDao();
            return $dao->getUserSumRunningOrderAmount2USD($this->id);
        });
        return  intval($usd);
    }
}
