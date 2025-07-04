<?php

namespace App\Console\Commands;

use App\Dao\UserAssetDao;
use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\CodeCoverage\Report\PHP;

class sumRunningOrderAmount2USD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sum_order_amount_usd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计运行中的金额转换成USD';

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
    public function handle()
    {
        $users = User::get();
        $sumUsd = 0;
        foreach ($users as $user) {
            $usd = $user->getPoolAmountUsd();
            //缓存数据 60分钟
            $cacheKey = 'user_sum_running_order_amount_usd:' . $user->id;
            //Cache::put($cacheKey, $usd, 60 * 60);

            if ($user->is_internal == 0) $sumUsd += $usd;
            echo '金额' . $usd . PHP_EOL;
        }
        echo '总计金额' . $sumUsd . PHP_EOL;
        return 0;
    }
}
