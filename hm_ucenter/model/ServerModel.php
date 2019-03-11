<?php 
namespace model;
use config\EnumConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;
use manager\RedisManager;

/**
 * 服务器模块
 * Class ServerModel
 */
class ServerModel extends AppModel
{
    const LOGON_SERVER_COUNT = 3;

    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    //服务器ID
    const SERVER_ID = 1;

    /**
     * 获取可用的登录服列表
     * @return array
     */
    public function getLogonServerList()
    {
        $logonServerList = [];
        for ($serverID = 1; $serverID <= self::LOGON_SERVER_COUNT; $serverID++) {
            $logonBaseInfo = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_logonBaseInfo . '|' . $serverID);
            if (!empty($logonBaseInfo) && (int)$logonBaseInfo['status'] == EnumConfig::E_LogonServerStatus['RUN']) {
                $logonServerList[] = $logonBaseInfo;
            }
        }
        FunctionHelper::arrayValueToInt($logonServerList, ['logonID', 'port', 'status', 'curPeople', 'maxPeople']);
        return $logonServerList;
    }

    /**
     * 获取服务器信息
     * @param int $server_id
     * @return mixed
     */
    public function getServerInfo($server_id = self::SERVER_ID)
    {
        $where = "server_id={$server_id}";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_server_info, [], $where);
        $intKeyArray = array(
            'server_status',
            'server_test_status',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 获取服务器状态
     * @param int $server_id
     * @return mixed
     */
    public function getServerStatus($server_id = self::SERVER_ID)
    {
        $result = $this->getServerInfo($server_id);
        if (empty($result)) {
            return EnumConfig::E_ServerStatus['STOP'];
        }
        return (int)$result['server_status'];
    }

    /**
     * 获取测试服开关
     * @param int $server_id
     * @return mixed
     */
    public function getServerTestStatus($server_id = self::SERVER_ID)
    {
        $result = $this->getServerInfo($server_id);
        if (empty($result)) {
            return EnumConfig::E_ServerTestStatus['CLOSE'];
        }
        return (int)$result['server_test_status'];
    }

    /**
     * 更新服务器状态
     * @param $server_status
     * @param int $server_id
     * @return mixed
     */
    public function updateServerStatus($server_status, $server_id = self::SERVER_ID)
    {
        $where = "server_id={$server_id}";
        $arrayDataValue = array(
            'server_status' => $server_status,
        );
        return DBManager::getMysql()->update(MysqlConfig::Table_web_server_info, $arrayDataValue, $where);
    }

    /**
     * 更新测试服开关
     * @param $server_test_status
     * @param int $server_id
     * @return mixed
     */
    public function updateServerTestStatus($server_test_status, $server_id = self::SERVER_ID)
    {
        $where = "server_id={$server_id}";
        $arrayDataValue = array(
            'server_test_status' => $server_test_status,
        );
        return DBManager::getMysql()->update(MysqlConfig::Table_web_server_info, $arrayDataValue, $where);
    }

    /**
     * 获取黑名单列表
     * @return mixed
     */
    public function getServerBlackList()
    {
        return DBManager::getMysql()->selectAll(MysqlConfig::Table_web_server_black);
    }

    /**
     * 获取白名单列表
     * @return mixed
     */
    public function getServerWhiteList()
    {
        return DBManager::getMysql()->selectAll(MysqlConfig::Table_web_server_white);
    }

    /**
     * 添加黑名单
     * @param $ip
     * @return mixed
     */
    public function addServerBlack($ip)
    {
        $arrayData = array(
            'ip' => $ip,
            'time' => time(),
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_server_black, $arrayData);
    }

    /**
     * 添加白名单
     * @param $ip
     * @return mixed
     */
    public function addServerWhite($ip)
    {
        $arrayData = array(
            'ip' => $ip,
            'time' => time(),
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_server_white, $arrayData);
    }

    /**
     * 删除黑名单ip
     * @param $ip
     * @return mixed
     */
    public function delServerBlack($ip)
    {
        $where = "ip='{$ip}'";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_server_black, $where);
    }

    /**
     * 删除白名单ip
     * @param $ip
     * @return mixed
     */
    public function delServerWhite($ip)
    {
        $where = "ip='{$ip}'";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_server_white, $where);
    }

    /**
     * 是否在服务器黑名单
     * @param $ip
     * @return bool
     */
    public function isInServerBlack($ip)
    {
        $where = "ip='{$ip}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_server_black, [], $where);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * 是否在服务器白名单
     * @param $ip
     * @return bool
     */
    public function isInServerWhite($ip)
    {
        $where = "ip='{$ip}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_server_white, [], $where);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

}
