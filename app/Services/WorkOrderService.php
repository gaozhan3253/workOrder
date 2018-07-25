<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/25
 * Time: 20:53
 */

namespace App\Services;


use App\Models\WorkModel;

class WorkOrderService extends Service
{
    public function getWorkOrder($orderId){
        return (new WorkModel())->find($orderId);
    }
}