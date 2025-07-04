<?php

namespace App\Dao;

use App\Models\User;
use App\Models\BalanceLog;
use App\Models\Currency;
use App\Models\DepositOrder;
use App\Models\MiningPoolOrder;
use App\Models\TeamRelation;
use App\Models\UserAsset;
use App\Services\TronService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UserDao
{
    /**
     * Record balance change for a user.
     *
     * @param User $user
     * @param float $amount
     * @param string|null $description
     * @return void
     */
    public function updateBalance(User $user, float $amount, ?string $description = null)
    {
        DB::beginTransaction();
        try {
            // 锁定行以防止并发更新
            $user = User::lockForUpdate()->find($user->id);
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $amount;
            // 创建余额变动日志
            BalanceLog::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
            ]);
            // 使用查询构建器更新用户余额,变动后余额不能为负
            $row = DB::selectRaw('update users set balance = balance - ? where id = ? and balance >= ?', [$amount, $user->id, $amount]);
            if ($row == 0) {
                throw new Exception('message.insufficient_balance');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // 记录异常日志或其他处理
            throw $e;
        }
    }

    /**
     * 根据签名验证用户
     * @param mixed $address
     * @param mixed $signature
     * @param mixed $messageHex
     * @return mixed
     */

    public function web3VerifySignature($address, $signature, $message)
    {
        // $url = "https://api.trongrid.io/wallet/validateaddress";
        // $data = [
        //     'address' => $address,
        //     'message' => $messageHex,
        //     'signature' => $signature
        // ];

        // $response = Http::withOptions(['verify' => false])->post($url, $data);

        $tronServe = new TronService();
        $response = $tronServe->verifyTronSignature($message, $signature, $address);


        return ['result' => $response];
    }
    /**
     * 根据地址 获取用户
     * @param mixed $address
     * @return User
     */
    public function getUserByAddress($address)
    {
        $user = User::firstOrCreate(
            ['tron_address' => $address],
            ['name' => $address,  'email' => $address . '@example.com', 'password' => Hash::make(Str::random(16))]
        );

        //检查资产
        $currencys = Currency::where('status', 1)->get();
        foreach ($currencys as $currency) {
            UserAsset::firstOrCreate(
                ['user_id' => $user->id, 'currency_id' => $currency->id],
                ['amount' => 0]
            );
        }


        //$this->createShareCode($user);
        return $user;
    }

    public function createShareCode($user)
    {
        $count = 0;
        while ($user->share_code == null) {
            if ($count++ > 10) {
                $share_code =  mt_rand(1000000, 9999999);
            } else {
                $share_code =  mt_rand(100000, 999999);
            }
            //6位数字
            if (!User::where('share_code', $share_code)->exists()) {
                $user->share_code = $share_code;
                $user->save();
            }
        }
    }

    /**
     * 创建团队关系
     * @param mixed $inviterId 邀请人ID
     * @param mixed $inviteeId  被邀请人ID
     * @param int $level 等级
     * @return void
     */
    public function createTeamRelation($inviterId, $inviteeId, $level = 1)
    {
        TeamRelation::create([
            'inviter_id' => $inviterId,
            'invitee_id' => $inviteeId,
            'level' => $level,
        ]);
        // 递归创建多级关系
        // $parentRelations = TeamRelation::where('invitee_id', $inviterId)->get();
        // foreach ($parentRelations as $relation) {
        //     TeamRelation::create([
        //         'inviter_id' => $relation->inviter_id,
        //         'invitee_id' => $inviteeId,
        //         'level' => $relation->level + 1,
        //     ]);
        // }
        $parent_user = User::find($inviterId);
        if ($parent_user->referrer_id) {
            $this->createTeamRelation($parent_user->referrer_id, $inviteeId, $level + 1);
        }
    }

    public function resetTeamRelation($userId)
    {
        // 删除所有团队关系
        TeamRelation::where('invitee_id', $userId)->delete();
        // 重新创建团队关系
        $user = User::find($userId);
        if ($user->referrer_id) {
            $this->createTeamRelation($user->referrer_id, $userId);
        }
    }


    public function getUserInfo(User &$user)
    {
        $user->load('assets.currency', 'membership', 'lastLoginIp');
        foreach ($user->assets as $asset) {
            /** @var UserAsset $asset **/
            $asset->getPoolsAssets();
            $asset->getWithdrawalAmount();
            $asset->getDepositAmount();
            $asset->getTeamAssets();
        }
        $user->getDownStat();
        $user->teamDepositTotalAmounts();
        $user->teamWithdrawalTotalAmounts();
        $user->getTeamActiveUserCount();
        $user->pool_amount_usdt = UserDao::getTeamPoolAmountUsdByDeep($user->id);
        $user->l8_pool_amount_usdt = UserDao::getTeamPoolAmountUsdByDeep($user->id, 8);
    }


    public function getFirstRechargeOrderByDate($date)
    {
        $firstRechargeOrders = DepositOrder::WithoutInternalIds()->WithAdminAuth()->select('user_id', 'amount', 'currency_id')
            ->where('status', 3)
            ->whereBetween('completed_at', $date)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('deposit_orders')
                    ->where('status', 3)
                    ->groupBy('user_id');
            })
            ->get();
        return $firstRechargeOrders;
    }

    /**
     * 获取用户的挖矿池订单数量
     * @param User $user
     * @param int $mining_pool_id 矿池id
     * @param int $cycle    周期
     * @param int $currency_id  币种id
     */
    public function getUserMiningPoolCycleOrderCount(User $user, $mining_pool_id, $cycle, $currency_id)
    {
        $count = $user->miningPoolOrders()
            ->where('status', MiningPoolOrder::STATUS_RUNING)
            ->where('mining_pool_id', $mining_pool_id)
            ->where('cycle', $cycle)
            ->where('currency_id', $currency_id)
            ->count();
        return $count;
    }

    public function getTeamIds($user_id)
    {

        $team_ids = TeamRelation::whereHas('invitee', function ($query) {
            $query->where('status', User::STATUS_NORMAL);
        })->where('inviter_id', $user_id)->pluck('invitee_id'); //包含自己
        $team_ids[] = $user_id;
        return $team_ids;
    }

    public function getDirectTeamIds($user_id)
    {
        $team_ids = TeamRelation::whereHas('invitee', function ($query) {
            $query->where('status', User::STATUS_NORMAL);
        })->where('inviter_id', $user_id)->where('level', 1)->pluck('invitee_id'); //包含自己
        $team_ids[] = $user_id;
        return $team_ids;
    }



    public function getTeamRegisterUserCount($team_ids, $between_date = [])
    {
        $query = User::query()
            ->whereIn('id', $team_ids);
        if (!empty($between_date)) {
            $query->whereBetween('created_at', $between_date);
        }
        $count = $query->count();
        return $count;
    }
    //获取时间段内  第一次下单的用户
    public function getTeamActiveUserCount($team_ids, $between_date = [])
    {
        // $query = User::query()
        //     ->whereIn('id', $team_ids)
        //     ->withMin('fixedMiningPoolOrders as first_order_time', 'created_at');
        // if (!empty($between_date)) {
        //     $query->havingBetween('first_order_time', $between_date);
        // } else {
        //     $query->havingRaw('first_order_time IS NOT NULL');
        // }
        // $count = $query->count();
        // return $count;

        $count = 0;
        $users = User::whereIn('id', $team_ids)->get();
        foreach ($users as $user) {
            if ($user->getPoolAmountUsd() > 100) {
                $count++;
            }
        }

        return $count;
    }

    public static function getTeamPoolAmountUsdByDeep($user_id, $deep = 0)
    {
        //下8代节点金额

        $ids_model = TeamRelation::whereHas('invitee', function ($query) {
            $query->where('status', User::STATUS_NORMAL);
        })->where('inviter_id', $user_id);
        if ($deep) {
            $ids_model = $ids_model->where('level', '<=', $deep);
        }
        $team_ids = $ids_model->pluck('invitee_id');
        $team_ids[] = $user_id;
        $user_orders = MiningPoolOrder::with('currency')->where(['status' => 1])->whereIn('user_id', $team_ids)->get();
        $usd_amount = 0;
        foreach ($user_orders as $order) {
            $usd_amount += $order->amount * $order->currency->price;
        }
        return $usd_amount;
    }
    //获取团队时间段内购买矿池的总金额
    /**
     * Undocumented function
     *
     * @param [type] $team_ids
     * @param array $between_date
     * @param mixed $type 1:活期 2:定期 3:存币
     * @return 
     */
    public function getTeamMiningPoolAmount($team_ids, $between_date = [], $type = false)
    {
        $query = MiningPoolOrder::whereIn('user_id', $team_ids);
        if (!empty($between_date)) {
            $query->whereBetween('created_at', $between_date);
        }
        $query->selectRaw('sum(amount) as amount,currency_id')
            ->with('currency:id,name,price')
            ->groupBy('currency_id');
        if ($type !== false) {
            if ($type == 1) $query->where('type', $type);
            if ($type == 2) $query->where('type', 2)->where('cate', 1);
            if ($type == 3) $query->where('type', 2)->where('cate', 2);
        }

        $amount = $query->get();

        $usd_amount = array_reduce($amount->toArray(), function ($carry, $item) {
            return $carry + $item['amount'] * $item['currency']['price'];
        }, 0);

        return $usd_amount;
    }
    //
    //获取直推人数

    public static function getDirectTeamCount($user_id, $level)
    {
        $l1_users = User::where('referrer_id', $user_id)->get();
        $direct_lower_levels = 0;
        foreach ($l1_users as $l1_user) {
            /** @var User $l1_user  */
            //激活状态才算
            if ($l1_user->getPoolAmountUsd() < 100) continue;
            $l1_user->getMembershipLevel();
            if ($l1_user->curr_level >= $level) $direct_lower_levels++;
        }
        return $direct_lower_levels;
    }


    //获取直推激活人数  矿池金额大于100 算激活


    public static function getDirectTeamActiveUserCount($user_id)
    {
        $l1_users = User::where('referrer_id', $user_id)->get();
        $count = 0;
        foreach ($l1_users as $l1_user) {
            /** @var User $l1_user  */
            if ($l1_user->getPoolAmountUsd() < 100) continue;
            $count++;
        }
        return $count;
    }


    //是否满足推荐奖励
    public static function isMeetRecommendAward($user_id, $level)
    {
        $level_config = [
            1 => ['money' => 100, 'count' => 1],
            2 => ['money' => 300, 'count' => 2],
            3 => ['money' => 500, 'count' => 3],
            4 => ['money' => 700, 'count' => 4],
            5 => ['money' => 900, 'count' => 5],
            6 => ['money' => 1100, 'count' => 6],
            7 => ['money' => 1300, 'count' => 7],
            8 => ['money' => 1500, 'count' => 8]
        ];

        $config = $level_config[$level] ?? null;
        if (!$config) return false;
        $user = User::find($user_id);
        if (!$user) return false;
        $userUseMoney = $user->getPoolAmountUsd();
        $directTeamActiveUserCount = self::getDirectTeamActiveUserCount($user_id);
        if ($userUseMoney >= $config['money'] && $directTeamActiveUserCount >= $config['count']) {
            return true;
        }
        return false;
    }
}
