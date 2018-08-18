<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/30
 * Time: 19:52
 */

namespace App\Channel\Core;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Exception;

abstract class BaseChannel
{
    const PUSH_ORDER = 'pushOrder';  //推送订单
    const GET_TRUNKING = 'getTrunking';  //获取追踪码
    const GET_LABEL = 'getLabel'; //获取面单
    const UPDATE_WEIGHT = 'updateWeight';   //更新重量

    const REQUEST_METHOD_CURL = 'CURL'; //请求方式
    const REQUEST_METHOD_SOAP = 'SOAP'; //请求方式

    const REQUEST_METHOD_CURL_POST = 'POST'; //POST请求方式
    const REQUEST_METHOD_CURL_GET = 'GET'; //GET请求方式


    protected $errors = [];    //错误信息
    protected $orderData = [];  //订单数据

    public function __construct($config = [])
    {
        if(is_array($config)){
            $this->config = array_merge($this->config, $config);
        }
        $this->setCurrentDockType($this->pushDockSequence[0]);
        $this->init();
    }

    /**
     * 配置信息
     * @var array
     */
    protected $config = [
        // 请求方式 curl|soap
        'requestMethod' => 'curl',
        // 请求方式 get|post
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
        'pushDockSequence' => [self::PUSH_ORDER, self::GET_TRUNKING, self::GET_LABEL, self::UPDATE_WEIGHT],
        //接口对接链接接口超时时间
        'connectTimeOut' => 10,
        //接口对接过程超时时间
        'timeOut' => 60,
    ];


    /**
     * 初始化,子类要用请覆盖
     */
    public function init()
    {

    }


    /**
     * 魔术方法 获取
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    /**
     * 魔术方法 设置
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 魔术方法 isset配置
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }


    /**
     * 请求体
     * @var string
     */
    protected $requestBody = '';

    /**
     * 获取请求体
     * @return string|array
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * 设置请求体
     * @param string|array $requestBody
     * @return void
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }


    /**
     * 获取错误信息
     * @return array
     */
    protected function getError()
    {
        return $this->errors;
    }

    /**
     * 设置错误信息
     * @param string|array $message
     * @return $this
     * @author longli
     */
    protected function setError($message)
    {
        $this->errors = is_array($message)?  $message :  [$message];
        return $this;
    }

    /**
     * 响应体
     * @var array
     */
    protected $responseBody = [];

    /**
     * 获取响应体
     * @return string|array
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * 设置响应体
     * @param string|array $responseBody
     * @return void
     */
    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    /**
     * 当前执行的流程
     * @var
     */
    protected $currentDockType;

    /**
     * 设置当前执行的流程
     * @param $currentDockType
     * @return $this
     */
    public function setCurrentDockType($currentDockType)
    {
        $this->currentDockType = $currentDockType;
        $this->config = array_merge($this->config,$this->config[$currentDockType]);
        return $this;
    }

    /**
     * 获取当前执行的流程
     * @return mixed
     */
    public function getCurrentDockType()
    {
        return $this->currentDockType;
    }

    /**
     * 订单模型
     * @var
     */
    protected $workOrder;

    /**
     * 设置订单model
     * @param Model $workOrder
     * @return $this
     */
    public function setWorkOrder(Model $workOrder)
    {
        $this->workOrder = $workOrder;
        return $this;
    }

    /**
     * 获取订单model
     * @return mixed
     */
    public function getWorkOrder()
    {
        return $this->workOrder;
    }

    /**
     * 设置订单下一步流程
     * @param $nextDockType
     */
    public function nextDockType($nextDockType)
    {
        $this->workOrder->current_dock_type = $nextDockType;
        $this->workOrder->save();
    }

    protected function send()
    {
        $requestMethod = mb_strtoupper($this->requestMethod);

        switch ($requestMethod) {
            case self::REQUEST_METHOD_CURL:
                Log::info('curl');
                $this->sendCurlRequest();
                break;

            case self::REQUEST_METHOD_SOAP:
                Log::info('soap');
                $this->sendSoapRequest();
                break;
        }
        return $this->finish();

    }

    /**
     * curl发送请求
     * @return mixed
     */
    protected function sendCurlRequest()
    {
        $method = mb_strtoupper($this->method);
        $requestBody = $this->getRequestBody();
        try{
            //创建一个新cURL资源
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectTimeOut);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeOut);

            //设置URL和相应的选项
            if ($method == self::REQUEST_METHOD_CURL_POST) {
                curl_setopt($curl, CURLOPT_URL, $this->url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
            } else {
                if (is_array($requestBody)) {
                    $requestBody = http_build_query($requestBody);
                }
                $queryString = strpos($this->url, '?') !== false ? '&' : '?';
                curl_setopt($curl, CURLOPT_URL, $this->url . $queryString . $requestBody);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
            if ($this->https) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
            }

            //抓取URL并把它传递给浏览器
            $response = curl_exec($curl);
            //关闭cURL资源，并且释放系统资源
            curl_close($curl);
            if ($response === FALSE){
                return false;
            }
            $jsonResponse = json_decode($response, true);

            $this->setResponseBody($jsonResponse?$jsonResponse:$response);
        }catch (\Exception $exception){
            $this->setError($exception->getMessage());
        }

        return $this;
    }

    /**
     * soap请求
     * @return mixed
     */
    protected function sendSoapRequest()
    {
        try {
            $client = new \SoapClient ($this->url, $this->headers);
            $response = $client->__soapCall($this->method, $this->getRequestBody());
            $jsonResponse = json_decode($response, true);
            $this->setResponseBody($jsonResponse ? $jsonResponse : $response);
        }catch (\SoapFault $fault){
            $this->setError($fault->getMessage());
        }
        return $this;
    }

    /**
     * 执行入口
     * @return mixed
     */
    public function run()
    {
        if(!empty($this->workOrder)){
            return $this->formatData()->send();
        }
    }

    /**
     * 请求成功后回调
     * @param array $data 请求信息
     * @return bool 当前对接对象是否对接成功
     */
    abstract protected function finish();

    /**
     * 组装请求的数据
     * @return array
     */
    abstract protected function formatData();


    /**
     * 日志
     * @param $prefix
     * @param $body
     * @return mixed
     */
    abstract protected function writeLog($prefix, $body);
}