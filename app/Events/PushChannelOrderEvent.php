<?php

namespace App\events;

use App\Models\ServiceOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PushChannelOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workOrder;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ServiceOrder $workOrder)
    {
        //
        $this->workOrder = $workOrder;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel_name');
    }
}
