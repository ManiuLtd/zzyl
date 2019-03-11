<?php
namespace model;
use config\EnumConfig;
use config\GameRedisConfig;
use manager\RedisManager;

/**
 * 配置模块
 * Class ConfigModel
 */
class ConfigModel extends AppModel
{
    private static $_instance = null;

    const BANK_KEY = array(
        'bankMinSaveMoney',
        'bankSaveMoneyMuti',
        'bankMinTakeMoney',
        'bankTakeMoneyMuti',
        'bankMinTransfer',
        'bankMinTransfer',
        'bankTransferMuti',
    );
    const GIVE_KEY = array(
        'sendGiftMyLimitMoney',
        'sendGiftMyLimitJewels',
        'sendGiftMinMoney',
        'sendGiftMinJewels',
        'sendGiftRate',
    );

    const FG_KEY = array(
        'groupCreateCount',
        'groupMemberCount',
        'groupJoinCount',
        'groupManageRoomCount',
        'groupRoomCount',
        'groupAllAlterNameCount',
        'groupEveAlterNameCount',
        'groupTransferCount',
    );

    const FTP_KEY = array(
        'ftpIP',
        'ftpUser',
        'ftpPasswd',
    );

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

    private function getConfigKeyType($configKey)
    {
        if (in_array($configKey, self::BANK_KEY)) {
            return EnumConfig::E_SystemConfigType['BANK'];
        } elseif (in_array($configKey, self::GIVE_KEY)) {
            return EnumConfig::E_SystemConfigType['GIVE'];
        } elseif (in_array($configKey, self::FG_KEY)) {
            return EnumConfig::E_SystemConfigType['FG'];
        } elseif (in_array($configKey, self::FTP_KEY)) {
            return EnumConfig::E_SystemConfigType['FTP'];
        } else {
            return EnumConfig::E_SystemConfigType['OTHER'];
        }
    }

    public function updateOtherConfigToRedis($configKey, $configValue)
    {
        if (empty($configKey)) {
            return;
        }
        if (is_array($configKey)) {
            foreach ($configKey as $key => $value) {
                $configType = $this->getConfigKeyType($key);
                $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_otherConfig, $configType);
                RedisManager::getGameRedis()->hSet($redisKey,$key ,$value);
            }
        } elseif (is_string($configKey)) {
            $configType = $this->getConfigKeyType($configKey);
            $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_otherConfig, $configType);
            RedisManager::getGameRedis()->hSet($redisKey, $configKey, $configValue);
        }
    }

    public function getAll($table = RedisConfig::Hash_web_turntable_config) {
        //获取缓存数据
        $config = RedisManager::getRedis()->hGetAll($table);
        if (is_array($config)) {
            array_walk($config, function (&$v, $k) {
                $v = json_decode($v, true);
            });
            sort($config);
        }
        return $config;
    }

    public function hSet($table, $id, $arrData) {
        array_walk($arrData, function (&$v, $k) {
            $v = json_encode($v);
        });
        $res = RedisManager::getRedis()->hSet($table, $id, $arrData);
        return $res;
    }

//    public function hMSet($table, $arrData) {
//        $arrID = array_column($arrData, 'id');
//        array_multisort($arrData, $arrID);
//    }

    public function getTableCache($table) {
        if (true) {
            $res = $this->getAll($table);
        }
        return $res;
    }

    public function setTableCache($table, $arrData) {
        if (true) {
            foreach ($arrData as $k => $v) {
                $this->hSet($table, $v['id'], json_encode($v));
            }
        }
        return true;
    }
}
