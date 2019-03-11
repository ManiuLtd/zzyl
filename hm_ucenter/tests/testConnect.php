<?php

use manager\SocketManager;
use manager\RedisManager;
use manager\DBManager;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 11:04
 */

require_once dirname(__DIR__) . '/helper/LoadHelper.php';
class testConnect
{
    public function __construct()
    {
        $this->socketConnect();
        $this->redisConnect();
        $this->mysqlConnect();
        echo '如果测试通过还有问题，请尝试重启服务';
    }

    public function socketConnect() {
        try {
            $res = SocketManager::getCenterSocket();
        } catch (Exception $e) {
            echo "socket连接错误<br>\n";
            var_dump($e);
        }
    }

    public function redisConnect() {
        try {
            RedisManager::getRedis();
            RedisManager::getGameRedis();
        } catch (Exception $e) {
            echo "redis连接失败<br>\n";
            var_dump($e);
        }
    }

    public function mysqlConnect() {
        try {
            DBManager::getMysql();
        } catch (Exception $e) {
            echo "mysql连接失败<br>\n";
            var_dump($e);
        }
    }
}

new testConnect();