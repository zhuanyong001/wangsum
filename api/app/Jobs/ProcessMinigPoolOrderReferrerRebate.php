<?php

namespace App\Jobs;

use App\Dao\UserAssetDao;
use App\Models\MembershipLevel;
use App\Models\MiningPoolAwardLog;
use App\Models\UserAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMinigPoolOrderReferrerRebate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $base_amount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $base_amount)
    {
        //
        $this->order = $order;
        $this->base_amount = $base_amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $order = $this->order;

        $userAssetsDao = new UserAssetDao();

        echo "minigPoolOrderReferrerRebateListener: " . $order->id . "\n";
        //获取推荐人
        $userAssetsDao->processMinigPoolOrderReferrerRebate($order, $this->base_amount);
    }
}
