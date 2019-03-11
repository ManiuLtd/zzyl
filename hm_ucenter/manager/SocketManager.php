<?php
namespace manager;

use config\ConnectConfig;
use helper\LogHelper;
use helper\SocketHelper;

/**
 * SocketHelper instance 管理类
 * Class SocketManager
 */
class SocketManager
{
    private static $_instance = array();

    private function __clone()
    {
    }

    /**
     * 获取一个SocketHelper实例
     * @param $socketType
     * @param $socketConfig
     * @return mixed|SocketHelper
     */
    private static function getInstance($socketType, $socketConfig)
    {
        $instance = isset(self::$_instance[$socketType]) ? self::$_instance[$socketType] : null;
        if (!$instance instanceof SocketHelper) {
            if (ConnectConfig::SOCKET_CONFIG_USE_MYSQL) {
                $config = self::getSocketConfig($socketType);
                if (!empty($config)) {
                    $socketConfig['host'] = $config['ip'];
                    $socketConfig['port'] = $config['port'];
                } else {
                    //抛出异常，并记录错误日志
                    FunctionHelper::Err("getSocketConfig失败", 500, 0, ['getSocketConfig失败', [$socketType, $socketConfig]]);
                }
            }
            $host = $socketConfig['host'];
            $port = $socketConfig['port'];
            $ipv6 = $socketConfig['ipv6'];
            $udp = $socketConfig['udp'];
            $nonblock = $socketConfig['nonblock'];

            LogHelper::printInfo(['socketConfig', $socketConfig]);
            $instance = new SocketHelper($host, $port, $ipv6, $udp, $nonblock);
            self::$_instance[$socketType] = $instance;
        }
        //检查连接
        $instance->checkConnect();
        return $instance;
    }

    /**
     * 获取中心服socket
     * @return mixed|SocketHelper
     */
    public static function getCenterSocket()
    {
        return self::getInstance(ConnectConfig::SOCKET_TYPE_CENTER, ConnectConfig::SOCKET_CONFIG_CENTER);
    }

    /**
     * 获取socket配置
     * @param $socketTypeID
     * @return bool
     */
    public static function getSocketConfig($socketTypeID)
    {
        $mysql = DBManager::getMysql();
        $sql = "select * from " . MysqlConfig::Table_web_socket_config . " where socketTypeID = {$socketTypeID}";
        $socketConfig = $mysql->queryRow($sql);
        $socketConfig['port'] = (int)$socketConfig['port'];
        return $socketConfig;
    }

    /**
     * 关闭所有的socket连接
     */
    public static function closeAllSocket()
    {
        foreach (self::$_instance as $key => &$instance) {
            if ($instance instanceof SocketHelper) {
                $instance->close();
                $instance = NULL;
            }
        }
    }

}