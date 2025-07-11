<?php

namespace App\Jobs;

use App\Dao\UserAssetDao;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserLoginIp;
use App\Services\IPRegionService;
use App\Services\QqwryServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UserRegisterJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserAssetDao $userAssetDao)

    {
        //获取赠送金额
        $user = $this->user;


        $register_bonus = get_system_config('register_bonus', 0);
        if (!$register_bonus) {
            return;
        }
        $cache_key = 'register_bonus_user_' . $user->id;
        if (Cache::get($cache_key)) {
            return;
        }
        $userAsset = UserAsset::firstOrCreate(
            ['user_id' => $user->id, 'currency_id' => 8],
            ['amount' => 0]
        );
        $userAssetDao->updateUserAsset($userAsset, $register_bonus, UserAssetDao::TYPE_REGISTER_BONUS, '注册赠送');

        //缓存已赠送用户
        $cache_key = 'register_bonus_user_' . $user->id;
        Cache::put($cache_key, $user->id, 60 * 60 * 24);
    }
}
