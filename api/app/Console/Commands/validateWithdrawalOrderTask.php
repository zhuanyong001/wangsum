<?php

namespace App\Console\Commands;

use App\Dao\UserAssetDao;
use App\Models\WithdrawalOrder;
use Illuminate\Console\Command;

class validateWithdrawalOrderTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate-withdrawal-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '提现订单状态轮询';

    protected $userAssetDao;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserAssetDao $userAssetDao)
    {
        parent::__construct();
        $this->userAssetDao = $userAssetDao;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = WithdrawalOrder::where('status', WithdrawalOrder::STATUS_PENDING)->get();

        foreach ($orders as $order) {
            try {
                $target = $this->userAssetDao->checkWithdrawalOrderStatus($order);
                if ($target->status == WithdrawalOrder::STATUS_SUCCESS) {
                    echo "订单号：{$order->id} 成功" . PHP_EOL;
                }
                if ($target->status == WithdrawalOrder::STATUS_FAIL) {
                    echo "订单号：{$order->id} 失败" . PHP_EOL;
                }
                if ($target->status == WithdrawalOrder::STATUS_PENDING) {
                    echo "订单号：{$order->id} 等待中" . PHP_EOL;
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}
