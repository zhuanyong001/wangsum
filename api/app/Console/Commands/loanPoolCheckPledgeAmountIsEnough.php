<?php

namespace App\Console\Commands;

use App\Dao\LoanPoolDao;
use App\Models\LoanPoolOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class loanPoolCheckPledgeAmountIsEnough extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan_pool_check_pledge_amount_is_enough';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '借款利息结算';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LoanPoolDao $loanPoolDao)
    {
        //$orders = MiningPoolOrder::where('status', 1)->get();
        $orders = LoanPoolOrder::where('status', 1);
        $orders->chunk(100, function ($orders) use ($loanPoolDao) {
            foreach ($orders as $order) {
                try {
                    $res = $loanPoolDao->checkPledgeAmountIsNotEnough($order);
                    if ($res) {
                        $order->remark = date('Y-m-d H:i:s') . '质押金额不足,自动平仓:' . json_encode($res);
                        $order->status = LoanPoolOrder::STATUS_CLOSE;
                        $order->save();
                        echo 'checkPledgeAmountIsEnough:订单关闭:' . $order->id . PHP_EOL;
                        Log::info('checkPledgeAmountIsEnough:订单关闭:' . $order->id);
                    }
                } catch (\Exception $e) {
                    echo 'checkPledgeAmountIsEnough:异常';
                    Log::error('checkPledgeAmountIsEnough:异常:' . $order->id . $e->getMessage());
                }
            }
        });
    }
}
