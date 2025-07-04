<?php

namespace App\Console\Commands;

use App\Dao\LoanPoolDao;
use App\Models\LoanPoolOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class loanPoolInterestTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan_pool_interest {--order_no=}';

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
        if ($this->option('order_no')) {
            $orders = LoanPoolOrder::where('order_no', $this->option('order_no'));
        } else {
            $orders = LoanPoolOrder::where('status', 1);
        }
        $ids = $orders->pluck('id');
        foreach ($ids as $id) {
            echo '订单ID:' . $id . PHP_EOL;
            $order = LoanPoolOrder::find($id);
            try {
                //code...
                $loanPoolDao->updateInterest($order);
                echo '订单利息结算成功:' . $order->id . PHP_EOL;
            } catch (\Exception $e) {
                echo '订单结算收益失败:' . $order->id . PHP_EOL;
                Log::error('订单结算收益失败:' . $order->id . $e->getMessage());
            }
        }
        // $orders->chunk(100, function ($orders) use ($loanPoolDao) {
        //     foreach ($orders as $order) {
        //         try {
        //             //code...
        //             $loanPoolDao->updateInterest($order);
        //             echo '订单利息结算:' . $order->id . PHP_EOL;
        //         } catch (\Exception $e) {
        //             echo '订单结算收益失败:' . $order->id . PHP_EOL;
        //             Log::error('订单结算收益失败:' . $order->id . $e->getMessage());
        //         }
        //     }
        // });
    }
}
