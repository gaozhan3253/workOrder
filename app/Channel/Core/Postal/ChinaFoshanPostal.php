<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/30
 * Time: 20:04
 */

namespace App\Channel\Core\Postal;


use App\Channel\Core\BaseChannel;

class ChinaFoshanPostal extends BaseChannel
{
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

    public function formatData(array $data, $dockType = '')
    {
        switch ($dockType) {
            case self::PUSH_ORDER:
                $this->pushOrderData($data);
                break;
            case self::GET_TRUNKING;
                $this->getTrunkingData($data);
                break;
            case self::GET_LABEL:
                $this->getLabel($data);
                break;
            case self::UPDATE_WEIGHT:
                $this->updateWeight($data);
                break;
        }
        return $this;
    }

    protected function pushOrderData(array $data)
    {

    }

    protected function getTrunkingData(array $data)
    {

    }

    protected function getLabel(array $data)
    {

    }

    protected function updateWeight(array $data)
    {

    }

    public function finish()
    {
        // TODO: Implement finish() method.
    }

    public function writeLog($prefix, $body)
    {
        // TODO: Implement writeLog() method.
    }
}