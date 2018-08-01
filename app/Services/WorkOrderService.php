<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/25
 * Time: 20:53
 */

namespace App\Services;
use DB;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderDetail;
use TheSeer\Tokenizer\Exception;

class WorkOrderService extends Base
{
    /**
     * 创建订单业务逻辑
     * @param $request
     * @return bool
     * @throws Exception
     */
    public static function pushOrder($request)
    {

        DB::beginTransaction();
        $orderData = $request->order;
        $orderDetailData = $request->orderdetail;
        $order = self::createOrder($orderData);
        if($order){
            $orderid = $order->order_id;
            $orderDetailData = array_map(function ($data) use($orderid){
                    $data['order_id'] = $orderid;
                return $data;
            },$orderDetailData);
            $detail = self::createDetail($orderDetailData);
            if($detail){
                DB::commit();
                return $order;
            }
        }
        DB::rollback();
        return false;
    }

    /**
     * 创建订单信息
     * @param $order
     * @return mixed
     * @throws Exception
     */
    protected  static function createOrder($order)
    {
      try{
          return ServiceOrder::create($order);
      }catch (Exception $exception){
          throw $exception;
      }
    }

    /**
     * 创建订单详情
     * @param $detail
     * @return mixed
     * @throws Exception
     */
    protected static function createDetail($detail)
    {
        try{
            return ServiceOrderDetail::insert($detail);
        }catch (Exception $exception){
            throw $exception;
        }
    }


    /**
     * 根据订单号查询订单
     * @param $orderId
     * @return mixed
     */
    public static function getWorkOrder($orderId)
    {
        return ServiceOrder::where(['order_id'=>$orderId])->first();
    }

    /**
     * 根据订单号获取订单商品的总重量
     * @param $orderId
     * @return int
     */
    public static function getOrderSubWeight($orderId)
    {
        $subWeight = 0;
        $serviceDetails= ServiceOrderDetail::where(['order_id'=>$orderId])->get(['pack_weight','qty']);
        foreach ($serviceDetails as $detail){
            $subWeight += round($detail->pack_weight) * $detail->qty;
        }
        return (int) $subWeight;

    }


}