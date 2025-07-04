<?php

namespace App\Http\Controllers\admin;

use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use App\Models\DepositOrder;
use App\Models\MiningPoolOrder;
use App\Models\User;
use App\Models\WithdrawalOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    protected $date = [];
    public function __construct(Request $request)
    {
        $type = $request->get('type', 'day');
        $date = $request->get('date', []);
        if (count($date) !== 2) {
            $now =  Carbon::now();
            switch ($type) {
                case 'day':
                    $date = [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
                    break;
                case 'yesterday':
                    $date = [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()];
                    break;
                case 'week': //本周
                    $date = [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
                    break;
                case 'month':
                    $date = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
                    break;
                case 'year':
                    $date = [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
                    break;
                default:
                    $date = [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
                    break;
            }
        }
        $this->date = $date;
    }
    //
    public function userStatistics(UserDao $userDao)
    {
        $date = $this->date;
        //注册用户
        $registerUser = User::WithAdminAuth()->whereBetween('created_at', $date)->count();
        //首充用户
        $firstRechargeOrders =  $userDao->getFirstRechargeOrderByDate($date);
        $firstRechargeUser = $firstRechargeOrders->count();
        //首充金额
        $firstRechargeAmounts = $firstRechargeOrders->groupBy('currency_id')
            ->map(function ($orders) {
                $currency = $orders->first()->currency;
                $amount = $orders->sum('amount');
                return [
                    'currency' => $currency,
                    'amount' => $amount
                ];
            })->values()->toArray();
        //购买活期金额
        $huoqiOrdersAmounts = MiningPoolOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('type', 1)->where('status', MiningPoolOrder::STATUS_RUNING)->whereBetween('created_at', $date)->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
        //购买定期的
        $dingqiOrdersAmounts = MiningPoolOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('type', 2)->where('status', MiningPoolOrder::STATUS_RUNING)->whereBetween('created_at', $date)->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
        return $this->success([
            'register_user' => $registerUser,
            'first_recharge_user' => $firstRechargeUser,
            'first_recharge_amounts' => $firstRechargeAmounts,
            'huoqi_amounts' => $huoqiOrdersAmounts,
            'dingqi_amounts' => $dingqiOrdersAmounts
        ]);
    }

    // 充值信息
    public function rechargeStatistics()
    {
        $date = $this->date;
        //充值人数
        $rechargeUser = DepositOrder::WithoutInternalIds()->WithAdminAuth()->where('status',  DepositOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->distinct()->count('user_id');
        //充值金额
        $rechargeAmounts = DepositOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('status', DepositOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->groupBy('currency_id')->selectRaw('currency_id, sum(amount-fee) as amount')->get();
        //充值次数
        $rechargeCount = DepositOrder::WithoutInternalIds()->WithAdminAuth()->where('status',  DepositOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->count();
        return $this->success([
            'recharge_user' => $rechargeUser,
            'recharge_amount' => $rechargeAmounts,
            'recharge_count' => $rechargeCount
        ]);
    }
    //提现
    public function withdrawStatistics()
    {
        $date = $this->date;
        //提现人数
        $withdrawalUser = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->where('status',  WithdrawalOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->distinct()->count('user_id');
        //提现金额
        $withdrawalAmounts = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('status', WithdrawalOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->groupBy('currency_id')->selectRaw('currency_id, sum(amount-fee) as amount')->get();
        //提现次数
        $withdrawalCount = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->where('status',  WithdrawalOrder::STATUS_SUCCESS)->whereBetween('completed_at', $date)->count();
        return $this->success([
            'withdrawal_user' => $withdrawalUser,
            'withdrawal_amount' => $withdrawalAmounts,
            'withdrawal_count' => $withdrawalCount
        ]);
    }

    public function allStatistics()
    {
        //总充值金额
        $totalRechargeAmounts = DepositOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('status', DepositOrder::STATUS_SUCCESS)->groupBy('currency_id')->selectRaw('currency_id, sum(amount-fee) as amount')->get();
        //总充值次数
        $totalRechargeCount = DepositOrder::WithoutInternalIds()->WithAdminAuth()->where('status', DepositOrder::STATUS_SUCCESS)->count();
        //总充值人数
        $totalRechargeUser = DepositOrder::WithoutInternalIds()->WithAdminAuth()->where('status', DepositOrder::STATUS_SUCCESS)->distinct()->count('user_id');
        //总提现金额
        $totalWithdrawalAmounts = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('status', WithdrawalOrder::STATUS_SUCCESS)->groupBy('currency_id')->selectRaw('currency_id, sum(amount-fee) as amount')->get();
        //总提现次数
        $totalWithdrawalCount = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->where('status', WithdrawalOrder::STATUS_SUCCESS)->count();
        //总提现人数
        $totalWithdrawalUser = WithdrawalOrder::WithoutInternalIds()->WithAdminAuth()->where('status', WithdrawalOrder::STATUS_SUCCESS)->distinct()->count('user_id');
        //总注册用户
        $totalRegisterUser = User::where('is_internal', 0)->WithAdminAuth()->count();
        //活期订单总金额
        $totalHuoqiAmounts = MiningPoolOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('type', 1)->where('status', MiningPoolOrder::STATUS_RUNING)->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
        //定期订单总金额
        $totalDingqiAmounts = MiningPoolOrder::WithoutInternalIds()->WithAdminAuth()->with('currency:id,name,price')->where('type', 2)->where('status', MiningPoolOrder::STATUS_RUNING)->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
        return $this->success([
            'total_recharge_amounts' => $totalRechargeAmounts,
            'total_recharge_count' => $totalRechargeCount,
            'total_recharge_user' => $totalRechargeUser,
            'total_withdrawal_amounts' => $totalWithdrawalAmounts,
            'total_withdrawal_count' => $totalWithdrawalCount,
            'total_withdrawal_user' => $totalWithdrawalUser,
            'total_register_user' => $totalRegisterUser,
            'total_huoqi_amounts' => $totalHuoqiAmounts,
            'total_dingqi_amounts' => $totalDingqiAmounts
        ]);
    }

    public function getNewMessage(Request $request)
    {

        $last_r_id = DepositOrder::where('status', DepositOrder::STATUS_SUCCESS)->max('id');
        $last_w_id = WithdrawalOrder::where('status', WithdrawalOrder::STATUS_WAIT)->max('id');
        $last_u_id = User::max('id');
        return [
            'last_r_id' => $last_r_id,
            'last_w_id' => $last_w_id ?? 0,
            'last_u_id' => 0
        ];
    }
}
