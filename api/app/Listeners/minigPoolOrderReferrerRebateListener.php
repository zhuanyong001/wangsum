<?php

namespace App\Listeners;

use App\Dao\UserAssetDao;
use App\Events\minigPoolOrderReferrerRebateEvent;
use App\Models\MembershipLevel;
use App\Models\UserAsset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class minigPoolOrderReferrerRebateListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\minigPoolOrderReferrerRebateEvent  $event
     * @return void
     */
    public function handle(minigPoolOrderReferrerRebateEvent $event)
    {
        //
        $order = $event->order;
        $base_amount = $event->base_amount;
        $userAssetsDao = new UserAssetDao();
        $userAssetsDao->processMinigPoolOrderReferrerRebate($order, $base_amount);
    }
}
