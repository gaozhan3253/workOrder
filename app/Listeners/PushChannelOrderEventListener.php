<?php

namespace App\Listeners;

use App\Services\WorkChannelService;
use App\Services\WorkOrderService;
use App\events\PushChannelOrderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Jobs\PushChannel;

class PushChannelOrderEventListener implements ShouldQueue
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
     * Handle the event.
     *
     * @param  PushChannelOrderEvent  $event
     * @return void
     */
    public function handle(PushChannelOrderEvent $event)
    {
        //
        //订单对象
        $workOrder = $event->workOrder;
        //订单号
        $orderId = $workOrder->order_id;
        Log::info('渠道对接事件：'.$orderId);
        $workChannelConfig = WorkChannelService::getChannelConfig($workOrder->channel_id);
        if(!$workChannelConfig){
            Log::error('未获取到订单渠道配置：'.$orderId);
            return false;
        }
        Log::info('获取到订单渠道配置：'.$workChannelConfig->id);
        $workChannel = WorkChannelService::getChannel($workChannelConfig);
        var_dump($workChannel);
        if($workChannel){
            Log::info('：渠道对接对象'.$workChannel);
//                dispatch((new PushChannel($workChannel))->onQueue('push_channel_order'));
        }else{
            Log::error('：未获取到渠道信息'.$orderId);
        }

    }

    /**
     * 处理队列任务执行失败
     * @param PushChannelOrderEvent $event
     * @param $exception
     */
    public function failed(PushChannelOrderEvent $event, $exception)
    {
        Log::error('渠道对接事件执行失败：'.$event->workOrder->order_id.' '.$exception->getMessage());
    }
}
