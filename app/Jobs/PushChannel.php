<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PHPUnit\Framework\MockObject\Stub\Exception;

class PushChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $workOrder;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($workOrder)
    {
        //
        $this->workOrder = $workOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info('执行渠道对接队列'.$this->workOrder->order_id);
    }
    public function failed(Exception $e)
    {
        Log::info('执行渠道对接队列失败');
    }
}
