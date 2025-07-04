<?php

namespace App\Http\Controllers\api;

use App\Dao\LoanPoolDao;
use App\Dao\UserAssetDao;
use App\Http\Controllers\Controller;
use App\Models\LoanPool;
use App\Models\LoanPoolOrder;
use App\Models\UserAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanPoolController extends Controller
{
    //
    public function  getPoolList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $model = LoanPool::query();

        $data = $model->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        $lists = $data->items();

        foreach ($lists as  $v) {
            $v->loan_coins();
            $v->pledge_coins();
            if (Auth::guard('sanctum')->check()) {
                $user = Auth::guard('sanctum')->user();
                $v->my_pool = LoanPoolOrder::selectRaw('sum(loan_amount) as amount,loan_coin_id')->with('loanCoin:name,id,code,price')
                    ->where(['user_id' => $user->id, 'loan_pool_id' => $v->id, 'status' => 1])
                    ->groupBy('loan_coin_id')->get();
            }
        }


        return $this->success(['list' => $lists, 'total' => $data->total()]);
    }

    /**
     * 创建订单
     */
    public function createOrder(Request $request, LoanPoolDao $loanPoolDao, UserAssetDao $userAssetDao)
    {
        $this->validate($request, [
            'loan_pool_id' => 'required|integer',
            'loan_coin_id' => 'required|integer',
            'pledge_coin_id' => 'required|integer',
            'loan_amount' => 'required|numeric',
        ]);

        $loan_pool_id = $request->input('loan_pool_id');
        $loan_coin_id = $request->input('loan_coin_id');
        $pledge_coin_id = $request->input('pledge_coin_id');
        $loan_amount = $request->input('loan_amount');

        if ($loan_amount <= 0) {
            return $this->error('loan.err_loan_amount_less_0');
        }

        //借贷金额 大于多少USDT
        if ($loanPoolDao->getLoanAmountToUsdtAmount($loan_coin_id, $loan_amount) < get_system_config('min_loan_usdt', 100)) {
            return $this->error('loan.err_loan_amount_less_100');
        }


        $loanPool = LoanPool::find($loan_pool_id);
        if (!$loanPool) {
            return $this->error('loan.err_loan_pool');
        }
        $loan_coins = $loanPool->loan_coin_ids;
        $pledge_coins = $loanPool->pledge_coin_ids;
        if (!in_array($loan_coin_id, $loan_coins)) {
            return $this->error('message.err_invalid_data');
        }

        if (!in_array($pledge_coin_id, $pledge_coins)) {
            return $this->error('message.err_invalid_data');
        }
        $user = $request->user();
        $userPledgeAsset = $userAssetDao->getAssetsByCurrency($user->id, $pledge_coin_id);
        $pledgeAmount = $loanPoolDao->getPledgeAmount($loan_amount, $loan_coin_id, $pledge_coin_id, $loanPool);
        if ($userPledgeAsset->amount < $pledgeAmount || $pledgeAmount < 1e-8) {
            return $this->error('loan.err_loan_pledge_amount_less');
        }
        $order =  $loanPoolDao->createOrder($loan_amount, $loan_coin_id, $pledge_coin_id, $user, $loanPool);
        return $this->success($order);
    }


    /**
     * 借款列表
     * 
     */
    public function getOrderList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $model = LoanPoolOrder::with('loanCoin', 'pledgeCoin');
        $user = $request->user();
        $map = [
            'user_id' => $user->id
        ];
        $status = $request->input('status', 0);
        if ($status == 1) {
            $map['status'] = $status;
        } else {
            $model->whereIn('status', [2, 3, 4]);
        }
        $data = $model->orderBy('id', 'desc')->where($map)->paginate($pageSize, ['*'], 'page', $page);
        $lists = $data->items();
        return $this->success(['list' => $lists, 'total' => $data->total()]);
    }


    /**
     * 借款结算
     */

    public function settlement(Request $request, LoanPoolDao $loanPoolDao, UserAssetDao $userAssetDao)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);
        $id = $request->input('id');
        $order = LoanPoolOrder::find($id);
        if (!$order) {
            return $this->error('message.err_invalid_data');
        }
        if ($order->status != LoanPoolOrder::STATUS_RUNING) {
            return $this->error('loan.err_loan_order_status');
        }
        $userAsset = $userAssetDao->getAssetsByCurrency($order->user_id, $order->loan_coin_id);
        if ($userAsset->amount <  $loanPoolDao->getRepaymentAmount($order)) {
            return $this->error('loan.err_user_amount_less');
        }
        $loanPoolDao->settlement($order);
        return $this->success([], 200, 'message.success');
    }

    public function getLoanPoolStat(Request $request)
    {
        $data1 = LoanPoolOrder::selectRaw('sum(loan_amount) as loan_amount,sum(interest) as interest_amount,loan_coin_id')->with('loanCoin:name,id,code,price')->where('status', 1)->groupBy('loan_coin_id')->get();

        return $this->success($data1);
    }

    public function previewOrder(Request $request, LoanPoolDao $loanPoolDao, UserAssetDao $userAssetDao)
    {
        $this->validate($request, [
            'loan_pool_id' => 'required|integer',
            'loan_coin_id' => 'required|integer',
            'pledge_coin_id' => 'required|integer',
            'loan_amount' => 'required|numeric',
        ]);

        $loan_pool_id = $request->input('loan_pool_id');
        $loan_coin_id = $request->input('loan_coin_id');
        $pledge_coin_id = $request->input('pledge_coin_id');
        $loan_amount = $request->input('loan_amount');

        if ($loan_amount <= 0) {
            return $this->error('loan.err_loan_amount_less_0');
        }

        //借贷金额 大于多少USDT
        if ($loanPoolDao->getLoanAmountToUsdtAmount($loan_coin_id, $loan_amount) < get_system_config('min_loan_usdt', 100)) {
            return $this->error('loan.err_loan_amount_less_100');
        }

        $loanPool = LoanPool::find($loan_pool_id);
        if (!$loanPool) {
            return $this->error('loan.err_loan_pool');
        }
        $loan_coins = $loanPool->loan_coin_ids;
        $pledge_coins = $loanPool->pledge_coin_ids;
        if (!in_array($loan_coin_id, $loan_coins)) {
            return $this->error('message.err_invalid_data');
        }

        if (!in_array($pledge_coin_id, $pledge_coins)) {
            return $this->error('message.err_invalid_data');
        }
        $user = $request->user();
        $userPledgeAsset = $userAssetDao->getAssetsByCurrency($user->id, $pledge_coin_id);
        $pledgeAmount = $loanPoolDao->getPledgeAmount($loan_amount, $loan_coin_id, $pledge_coin_id, $loanPool);


        $order = [
            'loan_amount' => $loan_amount,
            'pledge_amount' =>  $pledgeAmount

        ];
        return $this->success(['order' => $order]);
    }
}
