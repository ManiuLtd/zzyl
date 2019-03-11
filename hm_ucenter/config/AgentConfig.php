<?php
namespace config;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 19:40
 */

class AgentConfig
{
    const COMMISSION_TYPE = [
        'recharge' => 1,//充值
        'battle' => 2,//对战
    ];

    const BOTTOM_COMMISSION_RATE = 20;//如果小于该比率，则无法继续发展代理
}