<?php

namespace App\Listeners;

use App\Events\OptimizationChannelEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OptimizationChannelEventListener implements ShouldQueue
{
//    public $queue = 'optimization_channel_event'; // 任务应该发送到的队列的名称
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
     * @param  OptimizationChannelEvent  $event
     * @return void
     */
    public function handle(OptimizationChannelEvent $event)
    {
        //订单对象
        $workOrder = $event->workOrder;
        //订单号
        $orderId = $workOrder->order_id;
        Log::info('优选渠道事件：'.$orderId);
    }

    /**
     * 处理队列任务执行失败
     * @param OptimizationChannelEvent $event
     * @param $exception
     */
    public function failed(OptimizationChannelEvent $event, $exception)
    {
        Log::error('优选渠道事件队列执行失败：'.$event->workOrder->order_id.' '.$exception->getMessage());
    }
}
