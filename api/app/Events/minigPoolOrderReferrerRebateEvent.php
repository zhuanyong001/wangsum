<?php

namespace App\Events;

use App\Models\MiningPoolOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class minigPoolOrderReferrerRebateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;
    public $base_amount;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $base_amount)
    {
        //
        $this->order = $order;
        $this->base_amount = $base_amount;
    }



    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return \Illuminate\Broadcasting\Channel|array
    //  */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('web3-channel');
    // }
}
