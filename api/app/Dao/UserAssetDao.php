<?php

namespace App\Dao;

use App\Events\minigPoolOrderReferrerRebateEvent;
use App\Exceptions\ApiError;
use App\Jobs\ProcessMinigPoolOrderReferrerRebate;
use App\Models\Currency;
use App\Models\DepositOrder;
use App\Models\MembershipLevel;
use App\Models\MiningPool;
use App\Models\MiningPoolAwardLog;
use App\Models\MiningPoolCycleItem;
use App\Models\MiningPoolOrder;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserAssetLog;
use App\Models\UserStateLog;
use App\Models\WithdrawalOrder;
use App\Services\TronService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class UserAssetDao
{
    const TYPE_RECHARGE = 1; //充值
    const TYPE_WITHDRAW = 2; //提现
    const TYPE_TRANSFER = 3; //兑换
    const TYPE_AWARD = 4; //奖励
    const TYPE_FEE = 5; //手续费
    const TYPE_MP_FREEZE = 6; //质押冻结
    const TYPE_MP_UNFREEZE = 7;  //质押解冻
    const TYPE_MP_AWARD = 8; //质押收益
    const TYPE_WITHDRAW_FAIL = 9; //提现失败
    const TYPE_ADMIN_RECHARGE = 10; //管理员 手动充值
    const TYPE_DP_FREEZE = 11; //存款冻结
    const TYPE_DP_UNFREEZE = 12;  //存款解冻
    const TYPE_DP_AWARD = 13; //存款收益
    const TYPE_LOAN_PLEDGE = 14; //贷款抵押
    const TYPE_LOAN_LOAN = 15; //贷款
    const TYPE_LOAN_REPAY = 16; //贷款还款本金
    const TYPE_LOAN_PLEDGE_UNFREEZE = 17; //贷款质押解冻
    const TYPE_LOAN_REPAY_INTEREST = 18; //贷款还款利息
    const TYPE_AIR_DROP = 19; //空投
    const TYPE_RECOMMEND_AWARD = 20; //推荐奖励


    /**
     * Record balance change for a user.
     *
     * @param UserAsset $userAsset //用户资产
     * @param float $amount //变动数量
     * @param int $type  //变动类型
     * @param string|null $description
     * @return void
     */
    public function updateUserAsset(UserAsset $userAsset, float $amount, $type, ?string $description = null)
    {
        DB::transaction(function () use ($userAsset, $amount, $type, $description) {
            // 锁定行以防止并发更新
            $userAsset = UserAsset::lockForUpdate()->find($userAsset->id);
            //记录日志
            // 创建资产变动日志
            UserAssetLog::create([
                'user_asset_id' => $userAsset->id,
                'user_id' => $userAsset->user_id,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ]);
            // 使用查询构建器更新用户余额,变动后余额不能为负
            $row = DB::update('update user_assets set amount = amount + ? where id = ? and amount >= ?', [$amount, $userAsset->id, -$amount]);
            if ($row == 0) {
                Log::info('更新余额错误:', [$userAsset, $amount, $type, $description]);
                if ($userAsset->user_id !== 1) {  //1号资产金额过大，更新小金额会有错误
                    throw new ApiError('message.insufficient_balance');
                }
            }
        });
    }

    /**
     * 质押挖矿 活期
     * 
     */

    public function MiningPoolCurrentOrder(MiningPool $miningPool, MiningPoolCycleItem $cycleItem, UserAsset $userAsset, Currency $coin, float $amount)
    {
        if ($amount <= 0) {
            throw new ApiError('Invalid data');
        }
        DB::transaction(function () use ($miningPool, $cycleItem, $userAsset, $coin, $amount) {
            try {
                $currentOrder = MiningPoolOrder::where(['user_id' => $userAsset->user_id, 'currency_id' => $coin->id, 'type' => 1, 'status' => 1])->first();
                if (!$currentOrder) {
                    $this->createMiningPoolOrder($miningPool, $cycleItem, $userAsset, $coin, $amount);
                } else {
                    $this->updateUserAsset($userAsset, -$amount, self::TYPE_MP_FREEZE, '质押挖矿-活期');
                    $currentOrder->amount += $amount;
                    $currentOrder->save();
                }
            } catch (Exception $e) {
                //回滚
                throw new ApiError('Invalid data');
            }
        });
    }

    /**
     * 质押挖矿订单
     * 
     */

    public function createMiningPoolOrder(MiningPool $miningPool, MiningPoolCycleItem $cycleItem, UserAsset $userAsset, Currency $coin, float $amount)
    {
        if ($amount <= 0) {
            throw new ApiError('Invalid data amount');
        }
        DB::transaction(function () use ($miningPool, $cycleItem, $userAsset, $coin, $amount) {
            try {
                $this->updateUserAsset($userAsset, -$amount, [self::TYPE_MP_FREEZE, self::TYPE_MP_FREEZE, self::TYPE_DP_FREEZE][$miningPool->cate], ['质押挖矿', '质押挖矿', '存款'][$miningPool->cate]);
                $data = [
                    'mining_pool_id' => $miningPool->id,
                    'order_no' => ['A', 'MP', 'DP'][$miningPool->cate] . date('YmdHis') . mt_rand(1000, 9999),
                    'user_id' => $userAsset->user_id,
                    'coin_code' => $coin->code,
                    'currency_id' => $coin->id,
                    'amount' => $amount,
                    'daily_rate' => $cycleItem->daily_rate,
                    'cycle' => $cycleItem->days,
                    'status' => 1,
                    'type' => $cycleItem->type,
                    'compound' => $cycleItem->compound,
                    "df_currency_id" => $cycleItem->df_currency_id ?? 0,
                    "df_rate" => $cycleItem->df_rate ?? 0,
                    "df_amount" => $this->getDfAmount($amount, $coin, $cycleItem),
                    'cate' => $miningPool->cate,
                ];
                //到期时间
                $data['expire_time'] = $cycleItem->type == 1 ? null : date('Y-m-d 23:59:59', strtotime('+' .  $cycleItem->days . ' day', time()));
                $order =  MiningPoolOrder::create($data);
                //存款订单 上级返利
                if ($miningPool->cate == MiningPool::CATE_DEP) {
                    //$this->PoolOrderRebate($order);
                    $this->PoolOrderRebateLN($order);
                    //空投
                    MiningPoolOrderDao::makeAirDrop($order);
                }
            } catch (Exception $e) {

                if ($e instanceof ApiError) {
                    throw $e;
                } else {
                    throw new ApiError('sys err');
                }
                //回滚

            }
        });
    }

    /**
     * 活期质押提现
     * 
     */
    public function withdrawMiningPoolCurrentOrder(MiningPoolOrder $order, $amount)
    {
        if ($amount <= 0) {
            throw new ApiError('Invalid data');
        }
        DB::transaction(function () use ($order, $amount) {
            $userAsset = UserAsset::where(['user_id' => $order->user_id, 'currency_id' => $order->currency_id])->first();
            //本金结算
            $this->updateUserAsset($userAsset, $amount, self::TYPE_MP_UNFREEZE, '质押提现');
            //收益结算 
            $row = DB::update("update mining_pool_orders set amount = amount - ?,settlement_base = CASE WHEN settlement_base - ? < 0 THEN 0 ELSE settlement_base - ? END  where id = ? and amount >= ?", [$amount, $amount, $amount, $order->id, $amount]);
            if ($row == 0) {
                throw new ApiError('message.insufficient_balance');
            }
            //关闭订单
            $order->refresh();
            if ($order->amount == 0) {
                $order->status = 2;
                $order->save();
            }
        });
    }

    /**
     * 定期质押提现
     */
    public function withdrawMiningPoolOrder(MiningPoolOrder $order)
    {
        DB::transaction(function () use ($order) {
            $unfreeze_type = $order->cate == MiningPool::CATE_POOL ? self::TYPE_MP_UNFREEZE : self::TYPE_DP_UNFREEZE;

            $userAsset = UserAsset::where(['user_id' => $order->user_id, 'currency_id' => $order->currency_id])->first();
            //本金结算
            $this->updateUserAsset($userAsset, $order->amount, $unfreeze_type, '质押提现');
            //收益结算
            if ($order->total_award > 0 && $order->cate == MiningPool::CATE_POOL) {
                $this->updateUserAsset($userAsset, $order->total_award, self::TYPE_MP_AWARD, '质押收益');
            }
            //关闭订单
            $order->status = 2;
            $order->save();
        });
    }

    //立即结算存款池收益
    public function settleDepositAward(MiningPoolOrder $order, $award)
    {

        if ($award > 0) {
            $userAwardAsset = $this->getAssetsByCurrency($order->user_id, $order->currency_id);
            $this->updateUserAsset($userAwardAsset, $award, self::TYPE_DP_AWARD, '存款收益');
            if ($order->df_amount > 0) {
                $df_amount =  $order->getDfAmount();
                $userDfAsset = $this->getAssetsByCurrency($order->user_id, $order->df_currency_id);
                $this->updateUserAsset($userDfAsset, $df_amount, self::TYPE_DP_AWARD, '存款蜻蜓币奖励');
            }
        }
    }


    //质押收益结算到订单
    public function miningPoolAward(MiningPoolOrder $order)
    {

        DB::transaction(function () use ($order) {
            $prefix = get_system_config('mining_pool_award_prefix', '');
            //code...
            $settlement_base = $order->settlement_base;
            $award = $settlement_base * $order->daily_rate;
            $order->total_award += $award;
            if ($order->compound == 1 && $order->cate == MiningPool::CATE_POOL) {
                //复利
                $order->amount += $award;
            }
            //存款订单 立即结算收益
            if ($order->cate == MiningPool::CATE_DEP) {
                //立即结算
                $this->settleDepositAward($order, $award);
            }
            $order->settlement_base = $order->amount;
            $order->save();
            $this->addMiningPoolAwardLog($order->id, $order->user_id, $award, $order->user_id, 0);
            //上级返利
            //按收益结算
            $base_money =  $award;
            dispatch(new ProcessMinigPoolOrderReferrerRebate($order, $base_money));
            //event(new minigPoolOrderReferrerRebateEvent($order));

        });
    }

    //创建提现订单
    public function createWithdrawOrder(UserAsset $userAsset, Currency $currency, $amount, $fee, $destinationAddress)
    {
        if ($amount <= 0) {
            throw new ApiError('Invalid data');
        }
        DB::beginTransaction();
        try {
            $data = [
                'order_no' => 'WD' . date('YmdHis') . mt_rand(1000, 9999), //订单号格式'WD' . date('YmdHis') . mt_rand(1000, 9999),
                'user_id' => $userAsset->user_id,
                'currency' => $currency->code,
                'currency_id' => $currency->id,
                'amount' => $amount,
                'fee' => $fee,
                'destination_address' => $destinationAddress,
                'status' => 1,
            ];
            $order = WithdrawalOrder::create($data);
            //扣除用户资产
            $this->updateUserAsset($userAsset, -$amount, self::TYPE_WITHDRAW, '提现');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            throw new ApiError('Invalid data');
        }
    }
    //创建充值订单
    public function createDepositOrder(UserAsset $userAsset, Currency $currency, $amount, $fee, $sourceAddress, $destinationAddress)
    {
        if ($amount <= 0) {
            throw new ApiError('Invalid data');
        }
        DB::beginTransaction();
        try {
            $data = [
                'order_no' => 'RC' . date('YmdHis') . mt_rand(1000, 9999), //订单号格式'RC' . date('YmdHis') . mt_rand(1000, 9999),
                'user_id' => $userAsset->user_id,
                'currency' => $currency->code,
                'currency_id' => $currency->id,
                'amount' => $amount,
                'fee' => $fee,
                'source_address' => $sourceAddress,
                'destination_address' => $destinationAddress,
                'status' => 1,
            ];
            $order = DepositOrder::create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            throw new ApiError('Invalid data');
        }
        return $order;
    }

    /**
     * 提现订单支付成功
     * @param $order_no 订单号
     * @param $currency 币种
     * @param $amount 金额
     * @param $status 状态
     * 
     */
    public function WithdrawalOrderPaySuccess($order_no, $txid, $result = true)
    {
        $order = WithdrawalOrder::where('order_no', $order_no)->where('status', WithdrawalOrder::STATUS_PENDING)->first();
        if (!$order) return '订单状态不正确';
        DB::transaction(function () use ($order, $txid, $result) {
            if ($result) {
                $order->status = WithdrawalOrder::STATUS_SUCCESS;
                $order->completed_at = date('Y-m-d H:i:s');
                $txid && $order->transaction_id = $txid;
                $order->save();
            } else {
                $order->status = WithdrawalOrder::STATUS_FAIL;
                $order->response_message = json_encode($result);
                $order->completed_at = date('Y-m-d H:i:s');
                $order->save();
                // 返还用户资产
                $userAsset = $order->user->assets()->where('currency_id', $order->currency_id)->first();
                $this->updateUserAsset($userAsset, $order->amount, UserAssetDao::TYPE_WITHDRAW_FAIL, '提现失败:交易失败');
            }
        });
        return true;
    }


    /**
     * 充值订单支付成功
     * @param $order_no 订单号
     * @param $currency 币种
     * @param $amount 金额
     * @param $status 状态
     */

    public function DepositOrderPaySuccess($order_no, $coin_type, $amount, $status = false)
    {
        DB::transaction(function () use ($order_no, $coin_type, $status, $amount) {
            $order = DepositOrder::LockForUpdate()->where('order_no', $order_no)->first();
            if ($order->status != DepositOrder::STATUS_WAIT && $order->status != DepositOrder::STATUS_PENDING) {
                throw new ApiError('Order status error');
            }
            $currency = Currency::find($order->currency_id);
            if ($currency->code !== $coin_type) {
                throw new ApiError('Order currency error');
            }
            $user = $order->user;
            $order->status = DepositOrder::STATUS_PENDING;
            if ($status) {
                $order->status = DepositOrder::STATUS_SUCCESS;
                // $amount = $data['amount'];
                $userAsset = UserAsset::firstOrCreate(
                    ['user_id' => $user->id, 'currency_id' => $currency->id],
                    ['amount' => 0]
                );
                // $amount = $result['amount'] / $currency->unit;
                $order->amount = $amount;
                $order->completed_at = date('Y-m-d H:i:s');
                $this->updateUserAsset($userAsset, $amount, UserAssetDao::TYPE_RECHARGE, '充值');
            } else {
                $order->status = DepositOrder::STATUS_FAIL;
                $order->completed_at = date('Y-m-d H:i:s');
            }
            $order->save();
        });
    }

    //校验充值
    public function validateDepositOrder(DepositOrder $order, User $user, $tx_id)
    {
        $tronService = new TronService();
        $currency = Currency::findOrFail($order->currency_id);
        $result = $tronService->validateTransactionAddress($tx_id);


        if (!$result || $result['from_address'] != $order->source_address  || $result['to_address'] != $order->destination_address || ($currency->code !== 'TRX' && $result['contract_address'] != $currency->contract_address)) {
            info('转账验证失败');
            if ($result) {
                # code...
                info(json_encode($result));
            }

            throw new ApiError('Transaction error');
        }

        if ($currency->code == 'TRX' && $result['contract_address'] != '') {
            throw new ApiError('Transaction  contract address error');
        }


        //刷新下订单状态
        // $order->refresh();
        DB::beginTransaction();
        try {
            $order = DepositOrder::LockForUpdate()->find($order->id);
            if ($order->status != DepositOrder::STATUS_WAIT && $order->status != DepositOrder::STATUS_PENDING) {
                throw new ApiError('Order status error');
            }
            $order->status = DepositOrder::STATUS_PENDING;
            if ($result['status'] == 'SUCCESS') {
                $order->status = DepositOrder::STATUS_SUCCESS;
                // $amount = $data['amount'];
                $userAsset = UserAsset::firstOrCreate(
                    ['user_id' => $user->id, 'currency_id' => $currency->id],
                    ['amount' => 0]
                );
                $amount = $result['amount'] / $currency->unit;
                $order->amount = $amount;
                $order->completed_at = date('Y-m-d H:i:s');
                $this->updateUserAsset($userAsset, $amount, UserAssetDao::TYPE_RECHARGE, '充值');
            }
            if ($result['status'] == 'REVERT' || $result['status'] == 'OUT_OF_ENERGY') {
                $order->status = DepositOrder::STATUS_FAIL;
                $order->completed_at = date('Y-m-d H:i:s');
            }
            $order->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiError('Recharge failed');
        }

        return $order;
    }

    //检查提现状态
    public function checkWithdrawalOrderStatus(WithdrawalOrder $order)
    {
        $tronService = new TronService();
        $currency = Currency::findOrFail($order->currency_id);
        $result = $tronService->validateTransactionAddress($order->transaction_id);
        DB::beginTransaction();
        try {
            if ($result['status'] == 'SUCCESS') {
                $order->status = WithdrawalOrder::STATUS_SUCCESS;
                $order->completed_at = date('Y-m-d H:i:s');
                $order->save();
            }
            if ($result['status'] == 'REVERT' || $result['status'] == 'OUT_OF_ENERGY') {
                $order->status = WithdrawalOrder::STATUS_FAIL;
                $order->response_message = json_encode($result);
                $order->completed_at = date('Y-m-d H:i:s');
                $order->save();
                // 返还用户资产
                $userAsset = $order->user->assets()->where('currency_id', $order->currency_id)->first();
                $this->updateUserAsset($userAsset, $order->amount, UserAssetDao::TYPE_WITHDRAW_FAIL, '提现失败:交易失败');
            }
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            throw new ApiError('Withdrawal failed');
        }

        return $order;
    }


    //下级收益返利
    public function processMinigPoolOrderReferrerRebate(MiningPoolOrder $order, $base_amount)
    {
        // $userAssetsDao = new UserAssetDao();
        if (!$base_amount) return;
        // echo "minigPoolOrderReferrerRebateListener: " . $order->id . "\n";
        //获取推荐人
        $l = 1;
        /** @var User $target_user */
        $target_user = $order->user;
        $target_user->getMembershipLevel();
        $last_award = 0;
        $l_awards = [];
        $max_membership = null;
        //极差奖励
        while ($target_user->referrer_id) {
            /** @var User $referrer */
            $referrer = $target_user->referrer;
            $referrer->getMembershipLevel();
            $membership = MembershipLevel::where('level', $referrer->curr_level)->first();
            $userAsset = UserAsset::firstOrCreate(
                ['user_id' => $referrer->id, 'currency_id' => $order->currency_id],
                ['amount' => 0]
            );
            if (!$max_membership) $max_membership = $membership;

            DB::beginTransaction();
            try {
                //code...
                if ($l == 1) {
                    //直推奖励
                    $rebate = bcmul($base_amount, $membership->participation_commission, 8);
                    echo $userAsset->user_id . "直推奖励:" . $rebate . PHP_EOL;
                    if ($rebate > 0)  $this->updateUserAsset($userAsset, $rebate, UserAssetDao::TYPE_AWARD, '直推奖励');
                    $max_membership = $membership;
                } else {
                    //平级奖励
                    if ($referrer->curr_level <= $max_membership->level) {
                        $rebate = bcmul($last_award, $membership->equal_level_commission, 8);
                        echo  $userAsset->user_id . '层级奖励_平级' . $l . ':' . $rebate . PHP_EOL;
                        if ($rebate > 0) $this->updateUserAsset($userAsset, $rebate, UserAssetDao::TYPE_AWARD, '层级奖励_平级' . $l);
                    } else {
                        //极差奖励
                        $rebate = bcmul($base_amount, bcsub($membership->participation_commission, $max_membership->participation_commission, 8), 8);
                        echo  $userAsset->user_id . '层级奖励_级差' . $l . ':' . $rebate . PHP_EOL;
                        if ($rebate > 0)  $this->updateUserAsset($userAsset, $rebate, UserAssetDao::TYPE_AWARD, '层级奖励_级差' . $l);
                        $max_membership = $membership;
                    }
                }
                if ($rebate > 0) $this->addMiningPoolAwardLog($order->id, $referrer->id, $rebate, $order->user_id, $l);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            //推荐奖励 最多返8代，满足条件的
            if (UserDao::isMeetRecommendAward($referrer->id, $l)) {
                $tj_rebate = bcmul($base_amount, 0.05, 8);
                echo $referrer->id . "推荐奖励:" . $tj_rebate . PHP_EOL;
                if ($tj_rebate > 0) {
                    $this->updateUserAsset($userAsset, $tj_rebate, UserAssetDao::TYPE_RECOMMEND_AWARD, '推荐奖励_' . $l);
                    $this->addMiningPoolAwardLog($order->id, $referrer->id, $tj_rebate, $order->user_id, $l, 'TJ');
                }
            }

            $l++;
            $target_user = $referrer;
            $last_award = $referrer->curr_level == 1 ? $last_award : $rebate;
            $l_awards[] = $rebate;
        }
    }
    /**
     * 添加奖励记录
     * @param $order_id //订单id
     * @param $user_id //用户id
     * @param $award //奖励金额
     * @param $from_user_id //来源用户id
     * @param $level //代理等级
     * 
     */

    public function addMiningPoolAwardLog($order_id, $user_id, $award, $from_user_id, $level, $prefix = '')
    {
        MiningPoolAwardLog::create([
            'mining_pool_order_id' => $order_id,
            'user_id' => $user_id,
            'from_user_id' => $from_user_id,
            'amount' => $award,
            'type' => [1, 2][$level] ?? 3,
            'trade_no' => $prefix . date('Ymd') . '_' . $order_id . '_' . $user_id . '_' . $from_user_id . '_' . $level,  //日期_订单id_用户id_来源用户id_代理等级
        ]);
    }



    public function amountListToTotalUSD($list)
    {
        $total = 0;
        foreach ($list as $item) {
            $total += $item->amount * $item->currency->price;
        }
        return $total;
    }

    public function getAssetsByCurrency($user_id, $currency_id)
    {

        $userAsset = UserAsset::firstOrCreate(
            ['user_id' => $user_id, 'currency_id' => $currency_id],
            ['amount' => 0]
        );
        return $userAsset;
    }

    public function getDfAmount($amount, $coin, $cycleItem)
    {
        if (!$cycleItem->df_currency) return 0;
        $df_amount = $amount * $cycleItem->df_rate * $coin->price / $cycleItem->df_currency->price;
        return $df_amount;
    }

    //当前池子中的总金额
    public function getUserSumRunningOrderAmount2USD($user_id)
    {
        $orders = MiningPoolOrder::where(['user_id' => $user_id, 'status' => MiningPoolOrder::STATUS_RUNING])->with('currency:name,id,code,price')->get();
        return $this->amountListToTotalUSD($orders);
    }



    //存款订单返利

    public function PoolOrderRebate(MiningPoolOrder $order)
    {
        $referrer = $order->user->referrer;
        if (!$referrer) return;
        $userAsset = UserAsset::firstOrCreate(
            ['user_id' => $referrer->id, 'currency_id' => $order->currency_id],
            ['amount' => 0]
        );
        //利率
        $rate = get_system_config('mining_pool_dep_order_referrer_rebate_rate', 0);
        $rebate = bcmul($order->amount, $rate, 8);
        if ($rebate > 1e-8) {
            $this->addMiningPoolAwardLog($order->id, $referrer->id, $rebate, $order->user_id, 1, 'DPZJ');
            $this->updateUserAsset($userAsset, $rebate, UserAssetDao::TYPE_AWARD, '存款订单返利');
        }
    }


    //存款满500 自动开通 返利
    public function autoOpenDepositPoolLNRate($user_id)
    {

        //判断开关是否打开
        $is_open = get_system_config('mining_pool_dep_order_referrer_rebate_ln_switch', 0);
        if (!$is_open) return;
        $user = User::find($user_id);
        if ($user->is_ln_rebate) return;
        $total_amount = MiningPoolOrderDao::getUserCycleUSDTDepositPoolAmount($user_id, 365);

        if ($total_amount < 500) return;
        $user->is_ln_rebate = 1;
        $user->save();
        UserStateLog::create([
            'user_id' => $user_id,
            'state' => 'is_ln_rebate',
            'old_value' => 0,
            'new_value' => 1,
            'changed_by' => 0 // 假设有登录用户
        ]);
    }


    //存款订单返利    多代返款机制
    public function PoolOrderRebateLN(MiningPoolOrder $order)
    {

        //订单的周期限制 0 不限制
        $min_cycle = get_system_config('mining_pool_dep_order_referrer_rebate_min_cycle', 0);

        if ($min_cycle > 0) {
            if ($order->cycle < $min_cycle) return;
        }


        $rate_arr = get_system_config('mining_pool_dep_order_referrer_rebate_rate_ln', [0, 0, 0, 0, 0, 0, 0, 0]);

        if (!$rate_arr) return true;
        $last_referrer = $order->user->referrer;
        foreach ($rate_arr as $key => $rate) {
            $l = $key + 1;
            if ($l == 1) {
                $referrer = $last_referrer;
            } else {
                $referrer = $last_referrer->referrer;
            }
            if (!$referrer) break;
            $last_referrer = $referrer;
            if (!$referrer->is_ln_rebate) continue;
            if ($rate <= 0) continue;
            $userAsset = UserAsset::firstOrCreate(
                ['user_id' => $referrer->id, 'currency_id' => $order->currency_id],
                ['amount' => 0]
            );
            //利率
            $rebate = bcmul($order->amount, $rate, 8);
            if ($rebate > 1e-8) {
                $this->addMiningPoolAwardLog($order->id, $referrer->id, $rebate, $order->user_id, $l, 'DPZJ');
                $this->updateUserAsset($userAsset, $rebate, UserAssetDao::TYPE_AWARD, '存款订单返利L' . $l);
            }
        }
        return true;
    }
}
