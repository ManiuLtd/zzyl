<?php
namespace manager;

use config\ConnectConfig;
use helper\LogHelper;
use helper\RedisHelper;
use helper\FunctionHelper;

/**
 * RedisHelper instance 管理类
 * Class RedisManager
 */
class RedisManager
{
    private static $_instance = array();

    private function __clone()
    {
    }

    /**
     * 获取RedisHelper的一个实例
     * @param $redisType
     * @param $redisConfig
     * @return mixed|RedisHelper
     */
    private static function getInstance($redisType, $redisConfig)
    {
        $instance = isset(self::$_instance[$redisType]) ? self::$_instance[$redisType] : null;
        if (!$instance instanceof RedisHelper) {
            if (ConnectConfig::REDIS_CONFIG_USE_MYSQL) {
                $config = self::getRedisConfig($redisType);
                if (!empty($config)) {
                    $redisConfig['host'] = $config['ip'];
                    $redisConfig['port'] = $config['port'];
                    $redisConfig['password'] = $config['passwd'];
                } else {
                    //抛出异常，并记录错误日志
                    FunctionHelper::Err("getRedisConfig失败", 500, 0, ['getRedisConfig失败', [$redisType, $redisConfig]]);
                }
            }
            $host = $redisConfig['host'];
            $port = $redisConfig['port'];
            $password = $redisConfig['password'];
            $dbname = $redisConfig['dbname'];
            $pconnect = $redisConfig['pconnect'];

            LogHelper::printInfo(['redisConfig', $redisConfig]);
            $instance = new RedisHelper($host, $port, $password, $dbname, $pconnect);
            self::$_instance[$redisType] = $instance;
        }
        //检查连接
        $instance->checkConnect();
        return $instance;
    }

    /**
     * 本地redis
     * @return mixed|RedisHelper
     */
    public static function getRedis()
    {
        return self::getInstance(ConnectConfig::REDIS_TYPE_LOCAL, ConnectConfig::REDIS_CONFIG_LOCAL);
    }

    /**
     * 游戏redis
     * @return mixed|RedisHelper
     */
    public static function getGameRedis()
    {
        return self::getInstance(ConnectConfig::REDIS_TYPE_GAME, ConnectConfig::REDIS_CONFIG_GAME);
    }

    /**
     * 获取redis配置
     * @param $redisTypeID
     * @return bool
     */
    public static function getRedisConfig($redisTypeID)
    {
        $mysql = DBManager::getMysql();
        $sql = "select * from " . MysqlConfig::Table_redisbaseinfo . " where redisTypeID = {$redisTypeID}";
        $redisConfig = $mysql->queryRow($sql);
        $redisConfig['port'] = (int)$redisConfig['port'];
        return $redisConfig;
    }

    /**
     * 关闭所有redis连接
     */
    public static function closeAllRedis()
    {
        foreach (self::$_instance as $key => &$instance) {
            if ($instance instanceof RedisHelper) {
                $instance->close();
                $instance = NULL;
            }
        }
    }

    /**
     * 生成redis用的key
     * @param $key
     * @param string $key1
     * @param string $key2
     * @return string
     */
    public static function makeKey($key, $key1 = '', $key2 = '')
    {
        if ($key1 != ''){
            $key .= '|' . $key1;
        }
        if ($key2 != ''){
            $key .= ',' . $key2;
        }
        return $key;
    }
}