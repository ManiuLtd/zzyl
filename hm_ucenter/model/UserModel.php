<?php
namespace model;
use config\GameRedisConfig;
use config\RedisConfig;
use manager\RedisManager;

/**
 * 玩家模块
 * Class UserModel
 */
class UserModel extends AppModel
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

    /**
     * 离开房间
     * @param $userID
     * @return bool|LONG|void
     */
    public function leaveRoom($userID)
    {
        $leaveData = [
            "roomID" => 0,
            "deskIdx" => -1,
        ];
        return $this->updateUserInfo($userID, $leaveData);
    }

    /**
     * 获取在线玩家数量
     * @return int
     */
    public function getAllOnlineCount()
    {
        return RedisManager::getGameRedis()->sCard(GameRedisConfig::Set_allServerOnlineRealUserSet);
    }

    /**
     * 获取在线玩家userIDList
     */
    public function getAllOnlineUserIDList()
    {
        return RedisManager::getGameRedis()->sMembers(GameRedisConfig::Set_allServerOnlineRealUserSet);
    }

    /**
     * 获取在线玩家详细信息
     * @param array $array
     * @return array
     */
    public function getAllOnlineUserInfo($array = [])
    {
        $userIDList = $this->getAllOnlineUserIDList();
        $userInfoList = [];
        foreach ($userIDList as $userID) {
            $userInfo = $this->getUserInfo($userID, $array);
            if (!empty($userInfo)) {
                $userInfoList[] = $userInfo;
            }
        }
        return $userInfoList;
    }

    /**
     * 修改所有玩家的信息
     * @param string $keys
     * @param string $value
     */
    public function updateAllUserData($keys = '', $value = '')
    {
        $userIDKeys = RedisManager::getGameRedis()->keys(GameRedisConfig::Hash_userInfo . '|*');
        foreach ($userIDKeys as $userIDKey) {
            $infoArray = explode('|', $userIDKey);
            $this->updateUserInfo($infoArray[1], $keys, $value);
        }
    }

    /**
     * Hash_webUserInfo 字段注释
     * @return array
     */
    public function defaultWebUserInfo()
    {
        $arrayDataValue = [
            'shareCount' => 0, //分享次数金币数
            'shareGetMoney' => 0, //分享获得金币数
            'shareGetJewels' => 0, //分享获得钻石数
            'feedbackCount' => 0, //反馈次数
            'hornCount' => 0, //发送世界广播次数
            'hornCostJewels' => 0, //发送世界广播消耗金币数
            'turntableCount' => 0, //转盘抽奖次数
            'turntableGetMoney' => 0, //转盘抽奖获得金币数
            'turntableGetJewels' => 0, //转盘抽奖获得钻石数
            'signCount' => 0, //签到次数
            'signGetMoney' => 0, //签到获得金币数
            'signGetJewels' => 0, //签到获得钻石数
            'giveMoneyCount' => 0, //转赠金币次数
            'giveJewelsCount' => 0, //转赠钻石次数
            'giveCostMoney' => 0, //转赠消耗金币数
            'giveCostJewels' => 0, //转赠消耗钻石数
            'giveGetMoney' => 0, //转赠获得金币数
            'giveGetJewels' => 0, //转赠获得钻石数
            'supportCount' => 0, //补助次数
            'supportGetMoney' => 0, //补助获得金币数
            'bankCount' => 0, //银行转账次数
            'bankCostMoney' => 0, //银行转账消耗金币数
            'bankGetMoney' => 0, //银行转账获得金币数
            'makeOrderCount' => 0,//生成订单次数
            'rechargeCount' => 0, //充值次数
            'rechargeMoney' => 0, //充值金额数
            'rechargeGetMoney' => 0, //充值获得金币数
            'rechargeGetJewels' => 0, //充值获得钻石数
            'friendRewardCount' => 0, //打赏好友次数
            'getFriendRewardCount' => 0, //领取好友打赏次数
            'friendRewardGetMoney' => 0, //领取好友打赏获取金币数
            'passwordBankCount' => 0, //找回银行密码次数
            'getTotalEmailCount' => 0,//接受邮件总数
            'getGoodsEmailCount' => 0,//带附件邮件数
            'sendAudioCount' => 0,//发送语音次数
            'loginLobbyCount' => 0,//登录大厅次数
        ];
        return $arrayDataValue;
    }

    /**
     * 增加统计数据的值
     * @param $userID
     * @param string $key
     * @param int $value
     * @return int
     */
    public function addWebUserInfoValue($userID, $key = '', $value = 1)
    {
        if (empty($key)) {
            return false;
        }
        $redisKey = RedisManager::makeKey(RedisConfig::Hash_webUserInfo,$userID);
        return RedisManager::getRedis()->hIncrBy($redisKey, $key, $value);
    }

    /**
     * 更新用户统计数据
     * @param $userID
     * @param string $keys
     * @param string $value
     * @return bool|LONG
     */
    public function updateWebUserInfo($userID, $keys = '', $value = '')
    {
        if (empty($keys)) {
            return false;
        }
        $redisKey = RedisManager::makeKey(RedisConfig::Hash_webUserInfo,$userID);
        if (is_array($keys)) {
            $result = RedisManager::getRedis()->hMset($redisKey, $keys);
        } else {
            $result = RedisManager::getRedis()->hSet($redisKey, $keys, $value);
        }
        return $result;
    }

    /**
     * 获取用户统计数据
     * @param $userID
     * @param string|array $keys 为空获取全部信息
     * @return string|array
     */
    public function getWebUserInfo($userID, $keys = [])
    {
        $redisKey = RedisManager::makeKey(RedisConfig::Hash_webUserInfo,$userID);
        if (empty($keys)) {
            $webUserInfo = RedisManager::getRedis()->hGetAll($redisKey);
        } elseif (is_array($keys)) {
            $webUserInfo = RedisManager::getRedis()->hMget($redisKey, $keys);
        } else {
            $webUserInfo = RedisManager::getRedis()->hGet($redisKey, $keys);
            if ($webUserInfo === false){
                $webUserInfo = 0;
            }
        }
        return $webUserInfo;
    }

    public function getSumUserOnline() {
//
        return RedisManager::getGameRedis()->sCard(GameRedisConfig::Set_allServerOnlineUserSet);
    }

    /**
     * 更新密码
     * @param $phone
     * @param $password
     */
    public function updatePassword($phone, $password)
    {
        $data = ['phonePasswd' => $password];
        $userID = $this->getBindPhoneUserID($phone);
        $this->updateUserInfo($userID, $data);
    }
}
