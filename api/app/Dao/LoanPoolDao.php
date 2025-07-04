<?php

namespace App\Dao;

use App\Exceptions\ApiError;
use App\Models\Currency;
use App\Models\LoanPool;
use App\Models\LoanPoolInterestLog;
use App\Models\LoanPoolOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoanPoolDao
{

    public $userAssetDao;
    public function __construct()
    {
        $this->userAssetDao = new UserAssetDao();
    }

    public function getPledgeAmount($loan_amount, $loan_coin_id, $pledge_coin_id, LoanPool $loanPool)
    {
        $loan_coin = Currency::find($loan_coin_id);
        $pledge_coin = Currency::find($pledge_coin_id);
        //像上取整


        return  ceil(($loan_amount * $loan_coin->price) / ($pledge_coin->price * $loanPool->loan_ratio) * 1e8) / 1e8;
    }

    public function getInterestAmount(LoanPoolOrder $loanPoolOrder)
    {
        $interest = $loanPoolOrder->loan_amount * $loanPoolOrder->loan_rate;
        return $interest;
    }


    public function getLoanAmountToUsdtAmount($loan_coin_id, $amount)
    {
        $loan_coin = Currency::find($loan_coin_id);
        $usdt_coin = Currency::where('code', 'USDT')->first();


        return $loan_coin->price * $amount / $usdt_coin->price;
    }

    /**
     * 判断质押金额是否足够
     * @param LoanPoolOrder $loanPoolOrder
     * @return bool
     */
    public function checkPledgeAmountIsNotEnough(LoanPoolOrder $loanPoolOrder)
    {
        $loan_coin = Currency::find($loanPoolOrder->loan_coin_id);
        $pledge_coin = Currency::find($loanPoolOrder->pledge_coin_id);
        $order_amount =  $loanPoolOrder->loan_amount + $loanPoolOrder->interest;
        $pledge_amount =  $loanPoolOrder->pledge_amount;
        if ($order_amount * $loan_coin->price >=  $pledge_amount * $pledge_coin->price) {
            return
                [
                    'order_amount' => $order_amount,
                    'pledge_amount' => $pledge_amount,
                    'loan_coin_price' => $loan_coin->price,
                    'pledge_coin_price' => $pledge_coin->price
                ];
        }
        return false;
    }

    /**贷款
     * 
     */

    public function createOrder($loan_amount, $loan_coin_id, $pledge_coin_id, User $user, LoanPool $loanPool)
    {


        $order = new LoanPoolOrder();
        DB::transaction(function () use ($loan_amount, $loan_coin_id, $pledge_coin_id, $user, $loanPool, &$order) {
            $pledgeAmount = $this->getPledgeAmount($loan_amount, $loan_coin_id, $pledge_coin_id, $loanPool);
            $userPledgeAsset = $this->userAssetDao->getAssetsByCurrency($user->id, $pledge_coin_id);
            if ($userPledgeAsset->amount < $pledgeAmount) {
                throw new ApiError('loan.err_loan_pledge_amount_less');
            }
            $order->order_no = 'L' . time() . rand(1000, 9999);
            $order->user_id = $user->id;
            $order->loan_pool_id = $loanPool->id;
            $order->loan_coin_id = $loan_coin_id;
            $order->pledge_coin_id = $pledge_coin_id;
            $order->loan_amount = $loan_amount;
            $order->pledge_amount =  $pledgeAmount;
            $order->loan_rate = $loanPool->loan_rate;
            $order->loan_ratio = $loanPool->loan_ratio;
            $order->status = LoanPoolOrder::STATUS_RUNING;
            $order->save();
            $this->userAssetDao->updateUserAsset($userPledgeAsset, -$order->pledge_amount,  UserAssetDao::TYPE_LOAN_PLEDGE, '借款质押');
            $userLoanAsset = $this->userAssetDao->getAssetsByCurrency($user->id, $loan_coin_id);
            $this->userAssetDao->updateUserAsset($userLoanAsset, $order->loan_amount,  UserAssetDao::TYPE_LOAN_LOAN, '借款');
        });

        $this->updateInterest($order);
        return $order;
    }

    /**
     * 更新每日利息
     */
    public function updateInterest(LoanPoolOrder $loanPoolOrder)
    {

        DB::transaction(function () use ($loanPoolOrder) {
            $interest = $this->getInterestAmount($loanPoolOrder);
            $data = [
                'interest_amount' => $interest,
                'interest_time' => date('Y-m-d H:i:s'),
                'user_id' => $loanPoolOrder->user_id,
                "loan_pool_order_id" => $loanPoolOrder->id,
                'trade_no' => date('Ymd') . 'NOA' . $loanPoolOrder->id . ''
            ];
            LoanPoolInterestLog::create($data);
            $loanPoolOrder->interest += $interest;
            $loanPoolOrder->interest_times += 1;
            $loanPoolOrder->last_interest_time = date('Y-m-d H:i:s');
            $loanPoolOrder->save();
        });
    }
    /**
     * 结算
     */
    public function settlement(LoanPoolOrder $loanPoolOrder)
    {
        DB::transaction(function () use ($loanPoolOrder) {
            $loanPoolOrder->status = LoanPoolOrder::STATUS_SUCCESS;
            $loanPoolOrder->repayment_time = date('Y-m-d H:i:s');
            $loanPoolOrder->save();
            $userLoanAsset = $this->userAssetDao->getAssetsByCurrency($loanPoolOrder->user_id, $loanPoolOrder->loan_coin_id);
            //还本金
            $this->userAssetDao->updateUserAsset($userLoanAsset, -$loanPoolOrder->loan_amount,  UserAssetDao::TYPE_LOAN_REPAY, '借款还款-本金');
            //还利息
            $this->userAssetDao->updateUserAsset($userLoanAsset,  -$loanPoolOrder->interest,  UserAssetDao::TYPE_LOAN_REPAY_INTEREST, '借款还款-利息');
            $userPledgeAsset = $this->userAssetDao->getAssetsByCurrency($loanPoolOrder->user_id, $loanPoolOrder->pledge_coin_id);
            $this->userAssetDao->updateUserAsset($userPledgeAsset, $loanPoolOrder->pledge_amount,  UserAssetDao::TYPE_LOAN_PLEDGE_UNFREEZE, '借款质押结算退还');
        });
    }

    /**
     * 获取还款金额
     * 
     */
    public function getRepaymentAmount(LoanPoolOrder $loanPoolOrder)
    {
        return $loanPoolOrder->loan_amount + $loanPoolOrder->interest;
    }
}
