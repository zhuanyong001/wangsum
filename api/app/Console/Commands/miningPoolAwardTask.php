<?php

namespace App\Console\Commands;

use App\Dao\UserAssetDao;
use App\Models\MiningPoolOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class miningPoolAwardTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string  //mining_pool_award --order_no=DP202411291456354559
     */
    protected $signature = 'mining_pool_award {--order_no=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '矿池质押奖励结算';

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
    public function handle(UserAssetDao $userAssetDao)
    {
        //$orders = MiningPoolOrder::where('status', 1)->get();
        if ($this->option('order_no')) {
            $orders = MiningPoolOrder::where('order_no', $this->option('order_no'));
        } else {
            $orders = MiningPoolOrder::where('status', 1);
        }
        $ids = $orders->pluck('id');
        foreach ($ids as $id) {
            echo '订单ID:' . $id . PHP_EOL;
            $order = MiningPoolOrder::find($id);
            try {
                //code...
                $userAssetDao->miningPoolAward($order);
                echo '订单结算收益成功:' . $order->id . PHP_EOL;
                //判断订单是否已经到期，如果到期,则结算到余额
                if ($order->type != 1 && $order->expire_time && $order->expire_time < now()) {
                    $userAssetDao->withdrawMiningPoolOrder($order);
                    echo '订单到期自动结算成功:' . $order->id . PHP_EOL;
                }
            } catch (\Exception $e) {
                //throw $th;
                echo '订单结算收益失败:' . $order->id . PHP_EOL;
                echo $e->getMessage() . PHP_EOL;
                Log::error('订单结算收益失败:' . $order->id);
                Log::error('订单结算收益失败:' . $e->getMessage());
                // throw $e;
            }
        }

        return 0;
    }
}
