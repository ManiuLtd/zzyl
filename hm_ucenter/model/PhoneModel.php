<?php 
namespace model;
use config\GameRedisConfig;
use config\MysqlConfig;
use config\RedisConfig;
use manager\DBManager;
use manager\RedisManager;

/**
 * 手机模块
 * Class PhoneModel
 */
class PhoneModel extends AppModel
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
     * 手机是否被绑定了
     * @param $phone
     * @return bool
     */
    public function isPhoneBind($phone)
    {
        $exists = RedisManager::getGameRedis()->exists(GameRedisConfig::String_phoneToUserID . '|' . $phone);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 用户绑定手机号
     * @param $userID
     * @return array|string
     */
    public function getUserBindPhone($userID)
    {
        $info = $this->getUserInfo($userID, ['phone', 'name']);
        return $info;
    }

    /**
     * 用户是否已经绑定手机
     * @param $userID
     * @return bool
     */
    public function isUserBindPhone($userID)
    {
        $phone = $this->getUserInfo($userID, 'phone');
        if (!empty($phone)) {
            return true;
        }
        return false;
    }

    /**
     * 获取当天验证码信息
     * @param $phone
     * @param $codeType
     * @return bool
     */
    function getPhoneCodeInfo($phone, $codeType)
    {
        $day = date('Ymd', time());
        $sql = "select * from " . MysqlConfig::Table_web_phone_code
            . " where phone = {$phone} and day = {$day} and  type = {$codeType}";
        $result = DBManager::getMysql()->queryRow($sql);
        return $result;
    }

    /**
     * 设置当天验证码信息
     * @param $phone
     * @param $codeType
     * @param $count
     * @param $code
     * @return bool|mysqli_result
     */
    function setPhoneCodeInfo($phone, $codeType, $count, $code)
    {
        $time = time();
        $day = date('Ymd', time());
        $res = RedisManager::getRedis()->setex(RedisConfig::String_phoneCode . '|' . $phone, 5 * 60, $code);
        if ($count == 1) {
            $arrayData = array(
                "phone" => $phone,
                "type" => $codeType,
                "day" => $day,
                "code" => $code,
                "count" => $count,
                "time" => $time,
            );
            return DBManager::getMysql()->insert(MysqlConfig::Table_web_phone_code, $arrayData);
        } else {
            $arrayData = array(
                "count" => $count,
                "code" => $code,
                "time" => $time,
            );
            $where = "phone = {$phone} and day = {$day} and  type = {$codeType}";
            return DBManager::getMysql()->update(MysqlConfig::Table_web_phone_code, $arrayData, $where);
        }
    }

    /**
     * 手机号码获得用户ID
     * @param $phone
     * @return int
     */
    public function getBindPhoneUserID($phone)
    {
        $userID = RedisManager::getGameRedis()->get(GameRedisConfig::String_phoneToUserID . '|' . $phone);
        return (int)$userID;
    }

    /**
     * 更新手机密码
     * @param $phone
     * @param $password
     */
    public function updatePhonePassword($phone, $password)
    {
        $data = ['phonePasswd' => $password];
        $userID = $this->getBindPhoneUserID($phone);
        $this->updateUserInfo($userID, $data);
    }

    /**
     * 用户绑定手机
     * @param $userID
     * @param $phone
     * @param $password
     * @param $wechat
     */
    public function userBindPhone($userID, $phone, $password, $wechat)
    {
        $userInfo = UserModel::getInstance()->getUserInfo($userID, ['name']);
        $data = ['phone' => $phone, 'wechat' => $wechat, 'phonePasswd' => $password, 'name' => str_replace('游客', '玩家', $userInfo['name'])];
        $this->updateUserInfo($userID, $data);
        RedisManager::getGameRedis()->set(GameRedisConfig::String_phoneToUserID . '|' . $phone, $userID);
        return $data['name'];
    }
}
