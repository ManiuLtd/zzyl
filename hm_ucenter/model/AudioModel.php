<?php 
namespace model;
use config\RedisConfig;
use manager\RedisManager;

/**
 * 语音模块
 * Class AudioModel
 */
class AudioModel extends AppModel
{
    const DEAD_TIME = 60;
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

    /**
     * 保存录音
     * @param string $audio
     * @return int
     */
    public function saveAudio($audio)
    {
        $audioID = $this->getRedisIncrementID(RedisConfig::String_audioID);
        RedisManager::getRedis()->setex(RedisConfig::String_audioID . '|' . $audioID, self::DEAD_TIME, $audio);
        return (int)$audioID;
    }

    /**
     * 获取录音
     * @param int $audioID
     * @return array|bool
     */
    public function getAudio($audioID)
    {
        $audio = RedisManager::getRedis()->get(RedisConfig::String_audioID . '|' . $audioID);
        return $audio;
    }
}
