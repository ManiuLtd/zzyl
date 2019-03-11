<?php 
namespace model;
use config\MysqlConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use manager\DBManager;
use manager\RedisManager;

/**
 * 更新模块
 * Class UpdateModel
 */
class UpdateModel extends AppModel
{
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

    const INT_KEY_ARRAY = array(
        "packet_type",
        "packet_id",
    );

    const TEST = '测试';

    /**
     * 包版本结构
     * @param $packet_type
     * @param $packet_id
     * @param $name
     * @param $version
     * @param $address
     * @param string $check_version
     * @param string $desc
     * @return array
     */
    public function packetVersionArray($packet_type, $packet_id, $name, $version, $address, $check_version = '', $desc = '')
    {
        $arrayData = array(
            "packet_type" => $packet_type,
            "packet_id" => $packet_id,
            "name" => $name,
            "version" => $version,
            "address" => $address,
            "check_version" => $check_version,
            "desc" => $desc,
        );
        return $arrayData;
    }

    /**
     * 测试包版本结构
     * @param $packetVersionArray
     * @return array
     */
    public function packetVersionTestArray($packetVersionArray)
    {
        $arrayData = array(
            "packet_type" => $packetVersionArray['packet_type'],
            "packet_id" => $packetVersionArray['packet_id'],
            "name" => self::TEST . '_' . $packetVersionArray['name'],
            "version" => $packetVersionArray['version'],
            "address" => $packetVersionArray['address'],
            "check_version" => $packetVersionArray['check_version'],
            "desc" => $packetVersionArray['desc'],
        );
        return $arrayData;
    }

    /*
     +------------------------------------------------------------------------------------------------------------------
                                    正式包接口
     +------------------------------------------------------------------------------------------------------------------
     */

    /**
     * 增加包版本接口
     * @param $packetVersionInfo
     */
    public function addPacketVersion($packetVersionInfo)
    {
        $this->addPacketVersionToDB($packetVersionInfo);
        $this->addPacketVersionToRedis($packetVersionInfo);

        //测试包数据对应增加
        $packetVersionInfo = $this->packetVersionTestArray($packetVersionInfo);
        $this->addPacketVersionTest($packetVersionInfo);
    }

    /**
     * 增加包版本信息到数据库
     * @param $packetVersionInfo
     */
    private function addPacketVersionToDB($packetVersionInfo)
    {
        DBManager::getMysql()->insert(MysqlConfig::Table_web_packet_version, $packetVersionInfo);
    }

    /**
     * 增加包版本信息到redis
     * @param $packetVersionInfo
     */
    private function addPacketVersionToRedis($packetVersionInfo)
    {
        $packet_type = $packetVersionInfo['packet_type'];
        $packet_id = $packetVersionInfo['packet_id'];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_packetVersion . '|' . $packet_type . ',' . $packet_id, $packetVersionInfo);
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_packetVersionSet . '|' . $packet_type, time(), $packet_id);
    }

    /**
     * 获取包版本信息
     * @param int $packet_type
     * @param int $packet_id
     * @return array|mixed
     */
    public function getPacketVersion($packet_type, $packet_id)
    {
       /* //先从redis获取
        $result = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_packetVersion . '|' . $packet_type . ',' . $packet_id);
        //数据库获取
        if (empty($result)) {
            $where = "packet_type={$packet_type} and $packet_id={$packet_id}";
            $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_packet_version, [], $where);
            $this->addPacketVersionToRedis($result);
        }
        FunctionHelper::arrayValueToInt($result, self::INT_KEY_ARRAY);
        return $result;*/
        $where = "packet_type={$packet_type} and packet_id={$packet_id}";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_packet_version, [], $where);
        FunctionHelper::arrayValueToInt($result, self::INT_KEY_ARRAY);
        return $result;
    }

    /**
     * 获取指定包类型的版本信息
     * @param $packet_type
     * @return array|mixed
     */
    public function getPacketVersionList($packet_type)
    {
        //先从redis获取
        $packetVersionSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_packetVersionSet . '|' . $packet_type, 0, -1);
        $packetVersionList = [];
        foreach ($packetVersionSet as $id) {
            $packetVersionInfo = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_packetVersion . '|' . $packet_type . ',' . $id);
            if (!empty($packetVersionInfo)) {
                $packetVersionList[] = $packetVersionInfo;
            }
        }
        //数据库获取
        if (empty($packetVersionList)) {
            $where = "packet_type={$packet_type}";
            $packetVersionList = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_packet_version, [], $where);
            foreach ($packetVersionList as $packetVersionInfo) {
                $this->addPacketVersionToRedis($packetVersionInfo);
            }
        }
        FunctionHelper::arrayValueToInt($packetVersionList, self::INT_KEY_ARRAY);
        return $packetVersionList;
    }

    /**
     * 更新包版本信息
     * @param $packetVersionInfo
     */
    public function updatePacketVersion($packetVersionInfo)
    {
        $packet_type = $packetVersionInfo['packet_type'];
        $packet_id = $packetVersionInfo['packet_id'];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_packetVersion . '|' . $packet_type . ',' . $packet_id, $packetVersionInfo);

        $where = "packet_type={$packet_type} and $packet_id={$packet_id}";
        DBManager::getMysql()->update(MysqlConfig::Table_web_packet_version, $packetVersionInfo, $where);

        //测试包数据对应更新
        $packetVersionInfo = $this->packetVersionTestArray($packetVersionInfo);
        $this->updatePacketVersionTest($packetVersionInfo);
    }

    /**
     * 删除包版本信息
     * @param int $packet_type
     * @param int $packet_id
     */
    public function delPacketVersion($packet_type, $packet_id)
    {
        RedisManager::getRedis()->del(RedisConfig::Hash_packetVersion . '|' . $packet_type . ',' . $packet_id);
        RedisManager::getRedis()->zRem(RedisConfig::SSet_packetVersionSet . '|' . $packet_type, $packet_id);
        //数据库
        $where = "packet_type={$packet_type} and packet_id={$packet_id}";
        DBManager::getMysql()->delete(MysqlConfig::Table_web_packet_version, $where);

        //测试包数据对应删除
        $this->delPacketVersion($packet_type, $packet_id);
    }
    /*
     +------------------------------------------------------------------------------------------------------------------
                                    测试包接口
     +------------------------------------------------------------------------------------------------------------------
     */
    /**
     * 增加包版本接口
     * @param $packetVersionInfo
     */
    public function addPacketVersionTest($packetVersionInfo)
    {
        $this->addPacketVersionTestToDB($packetVersionInfo);
        $this->addPacketVersionTestToRedis($packetVersionInfo);
    }

    /**
     * 增加包版本信息到数据库
     * @param $packetVersionInfo
     */
    private function addPacketVersionTestToDB($packetVersionInfo)
    {
        DBManager::getMysql()->insert(MysqlConfig::Table_web_packet_version_test, $packetVersionInfo);
    }

    /**
     * 增加包版本信息到redis
     * @param $packetVersionInfo
     */
    private function addPacketVersionTestToRedis($packetVersionInfo)
    {
        $packet_type = $packetVersionInfo['packet_type'];
        $packet_id = $packetVersionInfo['packet_id'];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_packetVersionTest . '|' . $packet_type . ',' . $packet_id, $packetVersionInfo);
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_packetVersionTestSet . '|' . $packet_type, time(), $packet_id);
    }

    /**
     * 获取包版本信息
     * @param int $packet_type
     * @param int $packet_id
     * @return array|mixed
     */
    public function getPacketVersionTest($packet_type, $packet_id)
    {
        /*//先从redis获取
        $result = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_packetVersionTest . '|' . $packet_type . ',' . $packet_id);
        //数据库获取
        if (empty($result)) {
            $where = "packet_type={$packet_type} and $packet_id={$packet_id}";
            $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_packet_version_test, [], $where);
            $this->addPacketVersionToRedis($result);
        }
        FunctionHelper::arrayValueToInt($result, self::INT_KEY_ARRAY);
        return $result;*/
        $where = "packet_type={$packet_type} and packet_id={$packet_id}";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_packet_version_test, [], $where);
        FunctionHelper::arrayValueToInt($result, self::INT_KEY_ARRAY);
        return $result;
    }

    /**
     * 获取指定包类型的版本信息
     * @param $packet_type
     * @return array|mixed
     */
    public function getPacketVersionTestList($packet_type)
    {
        //先从redis获取
        $packetVersionSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_packetVersionTestSet . '|' . $packet_type, 0, -1);
        $packetVersionList = [];
        foreach ($packetVersionSet as $id) {
            $packetVersionInfo = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_packetVersionTest . '|' . $packet_type . ',' . $id);
            if (!empty($packetVersionInfo)) {
                $packetVersionList[] = $packetVersionInfo;
            }
        }
        //数据库获取
        if (empty($packetVersionList)) {
            $where = "packet_type={$packet_type}";
            $packetVersionList = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_packet_version_test, [], $where);
            foreach ($packetVersionList as $packetVersionInfo) {
                $this->addPacketVersionToRedis($packetVersionInfo);
            }
        }
        FunctionHelper::arrayValueToInt($packetVersionList, self::INT_KEY_ARRAY);
        return $packetVersionList;
    }

    /**
     * 更新包版本信息
     * @param $packetVersionInfo
     */
    public function updatePacketVersionTest($packetVersionInfo)
    {
        $packet_type = $packetVersionInfo['packet_type'];
        $packet_id = $packetVersionInfo['packet_id'];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_packetVersionTest . '|' . $packet_type . ',' . $packet_id, $packetVersionInfo);

        $where = "packet_type={$packet_type} and packet_id={$packet_id}";
        DBManager::getMysql()->update(MysqlConfig::Table_web_packet_version_test, $packetVersionInfo, $where);
    }

    /**
     * 删除包版本信息
     * @param int $packet_type
     * @param int $packet_id
     */
    public function delPacketVersionTest($packet_type, $packet_id)
    {
        RedisManager::getRedis()->del(RedisConfig::Hash_packetVersionTest . '|' . $packet_type . ',' . $packet_id);
        RedisManager::getRedis()->zRem(RedisConfig::SSet_packetVersionTestSet . '|' . $packet_type, $packet_id);
        //数据库
        $where = "packet_type={$packet_type} and packet_id={$packet_id}";
        DBManager::getMysql()->delete(MysqlConfig::Table_web_packet_version_test, $where);
    }

}
