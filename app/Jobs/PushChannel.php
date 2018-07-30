<?php

namespace App\Jobs;

use App\Services\WorkOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PHPUnit\Framework\MockObject\Stub\Exception;

class PushChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $workChannel;
    public $orderId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($workChannel,$orderId)
    {
        //
        $this->workChannel = $workChannel;
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info('进入渠道对接队列'.$this->orderId);

        $pushDockSequence = $this->workChannel->pushDockSequence;
        foreach ($pushDockSequence as $key=>$dockType){
            //获取订单数据
            $workOrderData = WorkOrderService::getWorkOrder($this->orderId);
            if(empty($workOrderData)){
                //TODO 需要想错误的处理流程
            }
            //订单表记录的当前标记的流程
            $current_dock_type = $workOrderData->current_dock_type;
            //如果当前标记流程不为空并且不等于循环到的流程  或者  当前标记流程为空 并且 循环到的流程序号不为0  不做任何处理
            if((!empty($current_dock_type) && $current_dock_type !=$dockType)|| ($key>0 && empty($current_dock_type))){
                continue;
            }

            //传入数据
            $this->workChannel->formatData($workOrderData, $dockType);
            if($this->workChannel->send()){
               //TODO 需要想成功的处理流程
            }
        }
    }
    public function failed(Exception $e)
    {
        Log::info('执行渠道对接队列失败');
    }
}
