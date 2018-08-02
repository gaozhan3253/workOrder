<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/25
 * Time: 20:53
 */

namespace App\Services;

use App\Models\ServiceOrderBuyer;
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

        $orderData = $request->order;
        $orderDetailData = $request->orderdetail;
        $orderBuyerData = $request->orderbuyer;
        return DB::transaction(function () use ($orderData, $orderDetailData, $orderBuyerData) {
            //创建订单
            $serviceOrder = ServiceOrder::create($orderData);
            //关联模型创建收件信息
            $serviceOrder->serviceOrderBuyer()->save(new ServiceOrderBuyer($orderBuyerData));
            //关联模型创建订单详情
            $serviceDetailArr = [];
            foreach ($orderDetailData as $data) {
               $serviceDetailArr[] =  new ServiceOrderDetail($data);
            }
            $serviceOrder->serviceOrderDetails()->saveMany($serviceDetailArr);
            return $serviceOrder;
        });
    }


    /**
     * 根据订单号查询订单
     * @param $orderId
     * @return mixed
     */
    public static function getWorkOrder($orderId)
    {
        return ServiceOrder::where(['order_id' => $orderId])->first();
    }

    /**
     * 根据订单号获取订单商品的总重量
     * @param $orderId
     * @return int
     */
    public static function getOrderSubWeight($orderId)
    {
        $subWeight = 0;
        $serviceDetails = ServiceOrderDetail::where(['order_id' => $orderId])->get(['pack_weight', 'qty']);
        foreach ($serviceDetails as $detail) {
            $subWeight += round($detail->pack_weight) * $detail->qty;
        }
        return (int)$subWeight;

    }


}