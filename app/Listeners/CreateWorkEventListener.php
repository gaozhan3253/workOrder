<?php

namespace App\Listeners;

use App\Events\CreateWorkOrderEvent;
use App\Jobs\PushChannel;
use App\Services\WorkOrderService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateWorkEventListener
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
     * @param  CreateWorkOrderEvent  $event
     * @return void
     */
    public function handle(CreateWorkOrderEvent $event)
    {
        //
        $orderId = $event->workOrder->orderId;
        $workOrder = (new WorkOrderService())->getWorkOrder($orderId);

        $workOrderChannel = (new WorkOrderService())->getChannel($workOrder->channel);
        dispatch(new PushChannel($workOrderChannel));
    }
}
