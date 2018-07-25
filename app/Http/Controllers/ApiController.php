<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkOrderRequest;
use App\Http\Triats\ApiResponse;
use App\Models\ServiceDetail;
use App\Models\ServiceOrder;
use PHPUnit\Runner\Exception;
use DB;

class ApiController extends Controller
{
    use ApiResponse;

  public function createWorkOrder(WorkOrderRequest $request){

      DB::beginTransaction();
      try{
          $orderRequest = $request->order;
        $order = ServiceOrder::create([
            'account'=>$orderRequest['account'],
        ]);
      }catch (Exception $exception){
          DB::rollback();
          return $this->status(false,['msg'=>'订单写入失败'],400);
      }
      try{
          $order = ServiceDetail::create([
            'order_id' =>$order->order_id,
          ]);
      }catch (Exception $exception){
          DB::rollback();
          return $this->status(false,['msg'=>'订单详情写入失败'],400);
      }
      //保存成功
      //触发事件
      Event(new \App\Events\CreateWorkOrderEvent($order->order_id));//触发事件
      //返回成功
      return 'true';
  }
}
