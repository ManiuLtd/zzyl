<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 10:48
 */

namespace config;


final class StrategyConfig
{
    const M_BIND_AGENT_MODE = [
        'BIND2USER' => 1,//绑定成为玩家
        'BIND2AGENT' => 2,//绑定成为代理
    ];

    //资源变化处理模式（目前金币）
    const M_RESOURCE_HANDLE_MODE = [
        'PHP' => 1,
        'CENTER' => 2,//中心服
    ];
    //资源变化处理模式选择开关，选择不涉及中心服
    const M_RESOURCE_HANDLE_MODE_ON = self::M_RESOURCE_HANDLE_MODE['CENTER'];
}