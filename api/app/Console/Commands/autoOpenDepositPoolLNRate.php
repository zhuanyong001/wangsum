<?php

namespace App\Console\Commands;

use App\Dao\MiningPoolOrderDao;
use App\Dao\UserAssetDao;
use App\Dao\UserDao;
use App\Models\User;
use App\Models\UserAsset;
use App\Services\Pay\Thirds\AnonyPay;
use App\Services\Pay\Thirds\helper\RSA2048Encrypt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class autoOpenDepositPoolLNRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-open-deposit-pool-ln-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动开通存款池子返利';

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

        if (get_system_config('mining_pool_dep_order_referrer_rebate_ln_switch', 0) == 0) {
            return;
        }

        $user_ids = User::where('is_ln_rebate', 0)->pluck('id');
        foreach ($user_ids as $user_id) {
            $dao = new UserAssetDao();
            $dao->autoOpenDepositPoolLNRate($user_id);
        }
    }
}
