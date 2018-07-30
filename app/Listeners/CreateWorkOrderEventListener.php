<?php

namespace App\Listeners;

use App\Events\CreateWorkOrderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateWorkOrderEventListener implements ShouldQueue
{
    public $queue = 'event'; // 任务应该发送到的队列的名称
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
     * 创建订单事件
     * @param CreateWorkOrderEvent $event
     */
    public function handle(CreateWorkOrderEvent $event)
    {
        //订单对象
        $workOrder = $event->workOrder;
        //订单号
        $orderId = $workOrder->order_id;
        Log::info('订单创建事件：'.$orderId);
        //判断是否有渠道信息
        if(empty($workOrder->channel_id)){
            //无渠道信息 做渠道优选
            Event(new \App\Events\OptimizationChannelEvent($workOrder));//触发渠道优选事件
        }else{
            //有渠道信息 触发渠道对接事件
            Event(new \App\Events\PushChannelOrderEvent($workOrder));//触发渠道对接事件
        }

    }

    /**
     * 处理队列任务执行失败
     * @param CreateWorkOrderEvent $event
     * @param $exception
     */
    public function failed(CreateWorkOrderEvent $event, $exception)
    {
        Log::error('订单创建事件队列执行失败：'.$event->workOrder->order_id.' '.$exception->getMessage());
    }
}
