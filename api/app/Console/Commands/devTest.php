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

class devTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        // $user_ids = User::pluck('id');
        // foreach ($user_ids as $user_id) {
        //     echo '' . $user_id . "\n";
        //     $user = User::find($user_id);
        //     $user->getMembershipLevel();
        //     //var_dump(intval('1e-5'));
        // }
        // UserDao::getDirectTeamCount(3345, 1);
        // echo 222222;
        // //RSA2048Encrypt::main();
        // // $pay = new AnonyPay();
        // // $res =  $pay->getToken([]);
        // // echo $res;
        // // $cachedValue = Cache::get('anony_pay_token');
        // //$cachedTime = Cache::getMetadata('anony_pay_token')['time'];
        // // echo "Value: " . $cachedValue . ", Cached Time: "; //. date('Y-m-d H:i:s', $cachedTime);
        // //Cache::put('test_key', 11111, 10);
        // // return;
        // $user = User::find(7);
        // $user->referrer_id = 6;
        // $user->save();
        // $dao = new UserDao();

        // $dao->resetTeamRelation($user->id);

        //return UserDao::getDirectTeamCount(3345, 1);
        // echo MiningPoolOrderDao::getUserDepositPoolUSDAmount(1);
        $dao = new UserAssetDao();
        $dao->autoOpenDepositPoolLNRate(1);
    }
}
