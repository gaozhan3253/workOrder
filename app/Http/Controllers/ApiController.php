<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkOrderRequest;
use App\Http\Triats\ApiResponse;
use App\Services\WorkOrderService;

class ApiController extends Controller
{
    use ApiResponse;

    public function createWorkOrder(WorkOrderRequest $request)
    {
        echo '<pre>';
        $workOrder = WorkOrderService::pushOrder($request);
        if ($workOrder) {
            //保存成功 触发事件
            Event(new \App\Events\CreateWorkOrderEvent($workOrder));//触发事件
            return 'success';
        } else {
            return 'error';
        }


    }
}
