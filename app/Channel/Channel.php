<?php
namespace App\Channel;

/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/30
 * Time: 19:50
 */
interface Channel
{
    /**
     * 发送请求
     * @return string|bool
     */
    public function send();


    /**
     * 请求成功后回调
     * @param array $data 请求信息
     * @return bool 当前对接对象是否对接成功
     */
    public function finish();

    /**
     * 组装请求的数据
     * @param array $data 数据
     * @param string $type 数据类型
     * @return array
     */
    public function formatData(array $data, $type = '');

    public function writeLog($prefix,$body);
}