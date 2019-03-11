<?php
/**
* 配置文件
* @date: 2016年11月15日 下午6:12:45
* @author: dly
*/
ini_set("display_errors","Off");
$host = isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:$_SERVER["SERVER_NAME"];
$config = array(
        "DB_HOST" => "172.18.163.18",
        "DB_NAME" => "NewHM",
        "DB_USER" => "sa",
        "DB_PASS" => "sa_123456",
        "DB_PORT" => "",
        //微信APP支付配置
        "wechat_appid"          =>      "wx624ff377019b69a1",                           //开放平台应用号
        "wechat_mch_id" =>      "1487308352",                                           //商户号
        "wechat_key"            =>      "12345678123456781234567812345aab",     //32位API安全密钥
        "wechat_notify"         =>      $host."/API/pay/notify/wechat_notify.php",
        //"wechat_notify_15173"   =>      "http://download.szhuomei.com/API/pay/notify/wechat_notify_15173.php",
        "wechat_notify_15173"   =>      "http://ht.szhuomei.com/API/pay/notify/wechat_notify.php",
        //游戏服务状态配置
        "server_status"=>"stop",
);


