<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30
 * Time: 20:15
 */

namespace config;

use manager\DBManager;


class EnumFunConfig
{
    const F_ACTIVITY_LIST = [
        [
            "content"  => "每日打卡签到可以获取丰厚的奖励哦！",
            "funKey"   => 2,
            "funName"  => "签  到",
            "funName3" => "已签到",
            "pic"      => "img_sign",
            "title"    => "每日签到",
        ],
        [
            "content"  => "每天转动轮盘，领取丰厚大奖",
            "funKey"   => 1,
            "funName"  => "抽  奖",
            "funName3" => "已抽奖",
            "pic"      => "img_zhuan",
            "title"    => "每日幸运轮盘",
        ],
        [
            "content" => "少于%s金币的时候可以领取，每次领取%s金币",
            "funKey"  => 3,
            "funName" => "领  取",
            "funName3" => "已领取",
            "pic"     => "img_gold",
            "title"   => "救济金",
        ],
        [
            "content"  => "绑定手机号使用手机号登录，绑定即送%s金币",
            "funKey"   => 4,
            "funName"  => "绑  定",
            "funName3" => "已绑定",
            "pic"      => "img_phone",
            "title"    => "绑定手机号",
        ]
    ];

    public static function initConfigData($table = MysqlConfig::Table_web_fun_config) {
        $res = DBManager::getMysql()->queryAll('select * from ' . $table);
        $initData = self::F_ACTIVITY_LIST;
        $initDataFunkey = array_column($initData, 'funKey');
//        var_export($initDataFunkey);exit;
//        unset($initData[1]);
        $data = [];
        foreach ($res as $k => $v) {
            var_export($v);
//            var_dump(array_search($v['keyConfig'], $initData));
            if (false !== $index = array_search($v['funkey'], $initDataFunkey)) {
//                var_export($index);
                unset($initData[$index]);
            }
        }
        foreach ($initData as $k => $v) {
            DBManager::getMysql()->insert($table, $v);
        }
        var_export($initData);
    }
}