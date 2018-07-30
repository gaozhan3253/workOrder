<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/30
 * Time: 19:52
 */

namespace App\Channel\Core;


use App\Channel\Channel;

abstract class BaseChannel implements Channel
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
     * 初始化调用,如有需要请用子类覆盖此方法
     * @return void
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
     * @var array
     */
    protected $requestBody = [];

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



    public function send()
    {
        $requestMethod = mb_strtoupper($this->requestMethod);

        switch ($requestMethod) {
            case self::REQUEST_METHOD_CURL:
                $this->sendCurlRequest();
                break;

            case self::REQUEST_METHOD_SOAP:
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
    }

    /**
     * soap请求
     * @return mixed
     */
    protected function sendSoapRequest()
    {
        $client = new \SoapClient ($this->url, $this->headers);
        $response = $client->__call($this->method, $this->requestBody);
        $jsonResponse = json_decode($response->out, true);
        if ($jsonResponse) {
            return $jsonResponse;
        }
        return $response;
    }


    /**
     * 请求成功后回调
     * @param array $data 请求信息
     * @return bool 当前对接对象是否对接成功
     */
    abstract public function finish();

    /**
     * 组装请求的数据
     * @param array $data 数据
     * @param string $type 数据类型
     * @return array
     */
    abstract public function formatData(array $data, $type = '');

    /**
     * 日志
     * @param $prefix
     * @param $body
     * @return mixed
     */
    abstract public function writeLog($prefix, $body);
}