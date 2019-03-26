<?php 
  namespace config;

 /**
 * 连接配置
 * 线上环境主要修改数据库配置
 * reids配置可在数据库配置
 * socket配置可在数据配置
 * 开发环境可不使用数据库配置 自行修改开关和配置
 * Class ConnectConfig
 */
final class ConnectConfig
{
    /*
     TODO mysql 配置
     */
    const MYSQL_TYPE_MASTER = 1;

    //目前用到的mysql配置
    const MYSQL_CONFIG_MASTER = array(
        'dbtype' => 'mysql',
        //'host' => '172.18.194.16', //TODO
        'host' => '127.0.0.1',
        'port' => 3306, //TODO
        'dbname' => 'old_zhizunyule', //TODO
        'username' => 'root',//TODO
        'password' => 'root', //TODO
        'charset' => 'utf8',
        'pconnect' => false,
    );

    /*
     TODO redis 配置
     */
    const REDIS_CONFIG_USE_MYSQL = false;
    const REDIS_TYPE_GAME = 1; //类型不能随意更改 数据库有对应配置
    const REDIS_TYPE_LOCAL = 2; //类型不能随意更改 数据库有对应配置

    //游戏redis配置
    const REDIS_CONFIG_GAME = array(
        'host' => '192.168.0.64', //TODO
 //       'host' => '47.107.147.29', //TODO
        'port' => 6380, //TODO
        'password' => 'Yy304949708', //TODO
        'dbname' => null,
        'pconnect' => true,
    );
    //本地redis配置
    const REDIS_CONFIG_LOCAL = array(
 //       'host' => '47.107.147.29', //TODO
        'host' => '192.168.0.64', //TODO
        'port' => 6381, //TODO
        'password' => 'Yy304949708', //TODO
        'dbname' => null,
        'pconnect' => true, //长链接
    );


    /*
     TODO socket 配置
     */
    const SOCKET_CONFIG_USE_MYSQL = false;
    const SOCKET_TYPE_CENTER = 1; //类型不能随意更改 数据库有对应配置
    const SOCKET_CONFIG_CENTER = array(
        'host' => '192.168.0.64', //TODO
        'port' => 4015, //TODO
        'ipv6' => false,
        'udp' => false,
        'nonblock' => true,
    );
}
