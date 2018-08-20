<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/8/18
 * Time: 15:23
 */

namespace App\Channel\Core\Postal;

use App\Channel\Core\BaseChannel;
use App\Services\WorkOrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Yunlu  extends BaseChannel
{
    /**
     * 配置信息
     * @var array
     */
    protected $config = [
        // 请求方式 curl|soap
        'requestMethod' => 'curl',
        // 请求方式 get|post|soap-method
        'method' => 'post',
        //是否https
        'https' => false,
        // 请求头
        'headers' => ['charset=utf-8'],
        // 请求的数据类型，只对post请求生效, (xml|json|array)
        'dataType' => 'json',
        // 请求地址
        'url' => '',
        // 要合并的请求体，只对post请求生效
        'requestBody' => [],
        // 额外参数
        'params' => [],
        //接口对接顺序
        'pushDockSequence' => [self::PUSH_ORDER,self::GET_LABEL],
        //接口对接链接接口超时时间
        'connectTimeOut' => 10,
        //接口对接过程超时时间
        'timeOut' => 60,
    ];


    protected function formatData()
    {
        switch ($this->currentDockType) {
            case self::PUSH_ORDER:
                $this->pushOrderData();
                break;
            case self::GET_LABEL:
                $this->getLabel();
                break;
        }
        return $this;
    }

    protected function filters()
    {
        switch ($this->currentDockType) {
            case self::GET_LABEL:
                $status = $this->workOrder->status;
                if($status != 1){
                    return true;
                }
                $logistics = $this->workOrder->serviceOrderLogistics;
                if(!isset($logistics) || $logistics->track_no ==''){
                    return true;
                }
                sleep(10);
                break;
        }
        return false;
    }

    protected function pushOrderData()
    {
        $buyer = $this->workOrder->serviceOrderBuyer;
        $details = $this->workOrder->serviceOrderDetails;

        if ($this->workOrder->is_cod) {
            //货到付款
            $ncndAmt =$this->workOrder->order_total; //代收金额 总金额
        } else {
            //预付件
            $ncndAmt = 0;
        }

        $area = '';
        if($buyer->buyer_zip !=''){
            $area = 'KALIDERES';
        }
        $innerList = array();
        $totalquantity = 0;
        $remark ='';  //拣货信息
        foreach ($details as $detail) {
            $innerList[] = array(
                'itemname' => $detail['sku_name_cn'], //是	500	中文品名
                'englishName' => $detail['sku_name_en'], //是	500	英文品名  提示:可以是印尼文
                'number' => $detail['qty'],   //是	10	件数
                'itemvalue' => $detail['price'],  //	是	20	申报价值
                'pricecurrency' => 'USD',  //是	20	申报价值币别（印尼盾：IDR 美元：USD）
                'desc' =>  $detail['declaration_name_cn'],  //是	2048	物品描述
                'itemurl' => 'youkeshu', //是	200	物品URL
            );
            $totalquantity += $detail['qty'];
        }

        $postData = array(
            'eccompanyid'=>$this->eccompanyid, //是	50	消息提供者ID    由云路平台分配给客户
            'customerid'=>$this->customerid, //是	20	客户标识    由云路平台分配给客户
            'txlogisticid'=>$this->workOrder->order_id, //是	30	物流订单号    传客户自己系统的订单号
            'ordertype'=>'1', //是	1	订单类型    1=普通订单  2=转运订单  3=退货订单  4=派送订单     默认传1
            'servicetype'=>'1', //是	1	服务类型    1=国际件      默认传1
            'sender'=>array(
                'name'=>'Ms luo', //是	100	发件姓名
                'postcode'=>'518000', //否	50	发件邮编
                'mobile'=>'18118722470', //	是	50	发件手机
                'prov'=>'guangdong', //是	60	发件省份
                'city'=>'shenzhen', //是	100	发件城市
                'area'=>'pinghu', //	是	50	发件区域
                'address'=>'6th Floor,Bonded Warehouse,South China City', //	是	256	发件地址
            ),
            'receiver'=>array(
                'name'=>$buyer->buyer_name, //是	100	收件姓名
                'postcode'=>$buyer->buyer_zip, //	否	50	收件邮编
                'mobile'=>$buyer->buyer_mobile, //是	50	收件手机
                'phone'=>$buyer->buyer_phone, //否	50	收件电话
                'prov'=>$buyer->buyer_state, //是	60	收件省份
                'city'=>$buyer->buyer_city, //	是	100	收件城市
                'area'=>$area, //是	50	收件区域
                'address'=>$buyer->buyer_address, //是	256	收件地址
            ),
            'createordertime'=>date('Y-m-d H:i:s'), //是	19	订单创建时间    24小时制: yyyy-MM-dd HH:mm:ss
            'sendstarttime'=>date('Y-m-d').' 08:00:00', //是	19	物流公司上门取货开始时间    24小时制: yyyy-MM-dd HH:mm:ss
            'sendendtime'=>date('Y-m-d ',strtotime('+5 days')).'20:00:00', //是	19	物流公司上门取货结束时间    24小时制: yyyy-MM-dd HH:mm:ss
            'paytype'=>'1', //是	1	支付方式    1=寄付月结     默认传1
            'goodstype'=>'1', //否	1	货物类型    1=普货 (不带电)    2=特货(带电)
            'weight'=>'0.01', //否	10	重量 虚假重量
            'totalquantity'=>$totalquantity, //是	10	总件数
            'itemsvalue'=>$ncndAmt, //是	20	代收货款金额
            'items'=>$innerList,
            'remark' =>$remark
        );
        $postData = json_encode($postData);
        $data_digest = $this->getEncryption($postData);
        $data = array(
            'logistics_interface'=>$postData,
            'data_digest'=>$data_digest,
            'msg_type'=>$this->msgType,
            'eccompanyid'=>$this->eccompanyid,
        );

        $this->setRequestBody($data);
        return $this;
    }

    protected function getLabel()
    {
        return $this;
    }

    /**
     * 加密
     * @param $str
     * @return string
     */
    protected function getEncryption($str)
    {
        return base64_encode(md5($str . $this->key));
    }

    /**
     * 返回处理
     */
    protected function finish()
    {
        Log::info(json_encode($this->getResponseBody()));
        Log::error(json_encode($this->getError()));

        switch ($this->currentDockType) {
            case self::PUSH_ORDER:
                //对推送订单返回的信息做处理

                return true;
                break;
            case self::GET_LABEL:
                //对推送订单返回的信息做处理
                return true;
                break;
        }
    }

    protected function writeLog($prefix, $body)
    {
        // TODO: Implement writeLog() method.
    }
}