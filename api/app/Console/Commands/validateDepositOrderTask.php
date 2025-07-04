<?php

namespace App\Console\Commands;

use App\Dao\UserAssetDao;
use App\Models\Currency;
use App\Models\DepositOrder;
use Illuminate\Console\Command;
use App\Services\CoinMarketCapService;
use Illuminate\Support\Facades\DB;

class validateDepositOrderTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate-deposit-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '充值订单处理';

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
     * @return mixed
     */
    public function handle()
    {

        $orders = DepositOrder::where(['status' => 2, 'scene' => 'dfpay'])->get();

        foreach ($orders as $order) {
            try {
                $target = $this->userAssetDao->validateDepositOrder($order, $order->user, $order->transaction_id);
                if ($target->status == 2) {
                    echo "订单号：{$order->id} 未完成" . PHP_EOL;
                }
                if ($target->status == -1) {
                    echo "订单号：{$order->id} 失败" . PHP_EOL;
                }
                if ($target->status == 3) {
                    echo "订单号：{$order->id} 成功" . PHP_EOL;
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }


        // if ($price !== null) {
        //     $this->info("The price of {$symbol} is $${price}");
        // } else {
        //     $this->error("Could not retrieve the price for {$symbol}");
        // }
    }
}
