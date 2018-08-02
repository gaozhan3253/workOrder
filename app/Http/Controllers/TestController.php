<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderBuyer;
use App\Services\WorkOrderService;
use Illuminate\Http\Request;
use App\Http\Requests\WorkOrderRequest;

class TestController extends Controller
{
    //
    public function test(WorkOrderRequest $request)
    {
       
    }
}
