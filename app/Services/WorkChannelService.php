<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/7/25
 * Time: 20:53
 */

namespace App\Services;
use App\Models\ChannelConfig;
use DB;
use App\Models\ServiceOrder;
use App\Models\ServiceDetail;
use TheSeer\Tokenizer\Exception;

class WorkChannelService extends Service
{
    /**
     * 根据渠道id 获取渠道对象
     * @param $channel_id
     */
    public static function getChannelConfig($channel_id)
    {
        return ChannelConfig::where(['status'=>1,'channel_code'=>$channel_id])->first();
    }

    public static function getChannel(ChannelConfig $channelConfig)
    {
        $classes = $channelConfig->classes;
        $config = $channelConfig->config;
        if(empty($classes) || empty($config)){
            return false;
        }
        if(!class_exists($classes)) return false;
        return new $classes;
    }
}