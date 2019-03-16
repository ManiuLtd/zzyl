<?php

use config\ConnectConfig;
U();
return array(
	//'配置项'=>'配置值'
    'SHOW_PAGE_TRACE' =>false,   //页面调试工具
    'URL_CASE_INSENSITIVE' => false,
    //数据库配置
    'DB_TYPE'   => ConnectConfig::MYSQL_CONFIG_MASTER['dbtype'],    // 数据库类型
    //  'DB_HOST'   => '192.168.0.64', // 服务器地址
    'DB_HOST' => ConnectConfig::MYSQL_CONFIG_MASTER['host'],
    'DB_NAME'   => ConnectConfig::MYSQL_CONFIG_MASTER['dbname'],       // 数据库名
    'DB_USER'   => ConnectConfig::MYSQL_CONFIG_MASTER['username'],        // 用户名
    'DB_PWD'    => ConnectConfig::MYSQL_CONFIG_MASTER['password'],    // 密码
    //'DB_CHARSET'=>'utf-8',      //字符集
    //'DB_DSN'	=>	'dblib:host=172.18.163.18:1433;dbname=NewHM',
    'DB_PORT'   => ConnectConfig::MYSQL_CONFIG_MASTER['port'],    // 端口
    'DB_PREFIX' => 'web_',      // 数据库表前缀
    'DEVELOP_MODE'=>true,
    'Alidayu'   =>  [
    'appkey'  =>  '',
    ],

    //定义代理充值类型
    'MemberRechargeType' => [
    'BeMember'        =>  1,  //成为代理
    'RechargeMoney'   =>  2,  //充值金币
    'RechargeJewels'  =>  3,  //充值房卡
    ],
    //定义代理充值回调地址
    'MemberRechargeUrl' =>  [
    'BeMember'        =>  'http://39.108.166.3/Notify/online_recharge.php',
    ],

    // 网站名称
    'web_name'             => '棋牌',
    'WEBSITE_URL' => 'http://'.$_SERVER['HTTP_HOST'],

    'IS_SHOW_JEWELS' => 0,
    'IS_SHOW_NOTHING' => 0,
    'CANNOT_ACCESS_INDEX_PAGE' => true,//不能访问官网主页
);
