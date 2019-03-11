<?php
namespace manager;

use config\ConnectConfig;
use helper\LogHelper;
use helper\DBHelper;
/**
 * DBHelper instance 管理类
 * Class DBManager
 */
class DBManager
{
    private static $_instance = array();

    private function __clone()
    {
    }

    /**
     * 获取一个MysqlHelper实例
     * @param $dbType
     * @param $dbConfig
     * @return mixed|DBHelper
     */
    private static function getInstance($dbType, $dbConfig)
    {
        $instance = isset(self::$_instance[$dbType]) ? self::$_instance[$dbType] : null;
        if (!$instance instanceof DBHelper) {
            $dbtype = $dbConfig['dbtype'];
            $host = $dbConfig['host'];
            $dbname = $dbConfig['dbname'];
            $port = $dbConfig['port'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];
            $charset = $dbConfig['charset'];
            $pconnect = $dbConfig['pconnect'];

            LogHelper::printInfo(['dbConfig', $dbConfig]);
            $instance = new DBHelper($dbtype, $host, $dbname, $port, $username, $password, $charset, $pconnect);
            self::$_instance[$dbType] = $instance;
        }
        //检查连接
        $instance->checkConnect();
        return $instance;
    }

    /**
     * 主要的数据库
     * @return mixed|DBHelper
     */
    public static function getMysql()
    {
        return self::getInstance(ConnectConfig::MYSQL_TYPE_MASTER, ConnectConfig::MYSQL_CONFIG_MASTER);
    }

    /**
     * 关闭所有数据库连接
     */
    public static function closeAllDB()
    {
        foreach (self::$_instance as $key => &$instance) {
            if ($instance instanceof DBHelper) {
                $instance->close();
                $instance = NULL;
            }
        }
    }
}