<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkOrderRequest;
use App\Http\Triats\ApiResponse;
use App\Models\ServiceOrder;
use App\Services\WorkOrderService;

class ApiController extends Controller
{
    use ApiResponse;

    public function createWorkOrder(WorkOrderRequest $request)
    {
        echo '<pre>';
        $serviceOrder = WorkOrderService::pushOrder($request);
        if ($serviceOrder &&($serviceOrder instanceof ServiceOrder)) {
            //保存成功 触发事件
            Event(new \App\Events\CreateWorkOrderEvent($serviceOrder));//触发事件
            return 'success';
        } else {
            return 'error';
        }


    }
}
