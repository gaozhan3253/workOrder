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
     * 对接成功或失败回调
     * @param string|bool $data
     * @return bool
     */
    public function finish();

    /**
     * 生成对应的数据
     * @param array $data 数据
     * @param string $type 数据类型
     * @return array
     */
    public function formatData(array $data, $type = '');
}