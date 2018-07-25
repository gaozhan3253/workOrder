<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
  public function createWorkOrder(Request $request){

      //校验订单

      //保存订单

      //保存成功
         //触发事件
            Event(new \App\Events\CreateWorkOrderEvent());//触发事件
         //返回成功

      //保存失败
         //返回失败
  }
}
