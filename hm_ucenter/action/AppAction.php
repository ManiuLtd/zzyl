<?php
namespace action;

use config\DynamicConfig;
use config\ErrorConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;
use model\AppModel;

/**
 * 通用业务
 * Class AppAction
 */
abstract class AppAction
{
    //检测开关
    const CHECK_REQUEST = true;
    //请求时间误差范围
    const TIME_ERROR_RANGE = 5 * 60;

    //检查是否游戏
    protected $_check_play_game = false;

    public static function factory($name)
    {
        $actionClassName = ucfirst($name) . "Action";
        $actionInstance = call_user_func(array($actionClassName, 'getInstance'));
        if (!$actionInstance instanceof self) {
            LogHelper::printError(['AppAction factory call_user_func 失败 name = ', $name]);
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
        }
        return $actionInstance;
    }

    protected function __construct()
    {
        if (self::CHECK_REQUEST && null !== DynamicConfig::DEBUG['CHECK_REQUEST'] && true === DynamicConfig::DEBUG['CHECK_REQUEST']) {
            if (null !== $this->whileAction && in_array($_REQUEST['action'], $this->whileAction)) {
                return true;
            }
//            if ($_SERVER['REMOTE_ADDR'] != '192.168.0.72') {
//                return true;
//            }
            $this->checkRequest();
        }

        if ($this->_check_play_game) {
            $this->checkUserPlayGame($_REQUEST['userID']);
        }
    }

    // protected function __destruct() {
        
    // }

    private function __clone()
    {
    }

    public function checkRequest()
    {
        $this->checkTimestamp($_REQUEST['timestamp']);
        $this->checkUUID($_REQUEST['uuid']);
        $this->checkApiRecord($_REQUEST['sign']);
        $this->checkSign($_REQUEST);
        $this->addApiRecord($_REQUEST);
    }

    /**
     * 获取签名
     * 1、筛选参数 所有必须参数除（uuid, sign）
     * 2、排序 将筛选的参数按照第一个字符的键值ASCII码递增排序
     * 3、拼接：将排序后的参数与其对应值，组合成“参数=参数值”的格式 并且把这些参数用&字符连接起来，之后继续拼接 uuid
     * 4、md5加密
     * 5、base64_encode
     * @param $data
     * @return string
     */
    private function getSign($data)
    {
        //1、筛选参数
        $keyArray = array();
        foreach ($data as $key => $value) {
            if ($key != 'uuid' && $key != 'sign' && $key != 'clientID') {
                $keyArray[] = $key;
            }
        }
        //2、排序
        sort($keyArray);
        $keyArray[] = 'uuid';
        //3、拼接
        $joinString = '';
        for ($i = 0; $i < count($keyArray); $i++) {
            $value = $keyArray[$i];
            $temp = "{$value}={$data[$value]}";
            if ($i != count($keyArray) - 1) {
                $temp = "{$temp}&";
            }
            $joinString = "{$joinString}{$temp}";
        }
        //4、md5加密
        $md5String = md5($joinString);
        //5、base64_encode
        $sign = base64_encode($md5String);
        LogHelper::printDebug('--------------------------------------signnnnnnnnnnnn:' . var_export($sign, true));
        return $sign;
    }
    private function getSignV2($data)
    {
        //1、筛选参数
        $uuid = $data['uuid'];
        unset($data['uuid']);
        unset($data['sign']);
        unset($data['clientID']);
        //2、排序
        ksort($data);
        $data['uuid'] = $uuid;
        $joinString = http_build_query($data);
        //4、md5加密
        $md5String = md5($joinString);
        //5、base64_encode
        $sign = base64_encode($md5String);
        return $sign;
    }

    /**
     * 检测timestamp
     * @param int $timestamp
     */
    private function checkTimestamp($timestamp = 0)
    {
        $timestamp = (int)$timestamp;
        //请求的时间不在10秒的误差范围内
        if ($timestamp > time() + self::TIME_ERROR_RANGE || $timestamp < time() - self::TIME_ERROR_RANGE) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TIMESTAMP);
        }
    }

    /**
     * 检测UUID
     * @param string $uuid
     */
    private function checkUUID($uuid = '')
    {
        if ($uuid != PLAT_UUID) {
            LogHelper::printDebug('uuid not match:' . $uuid);
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_UUID, $uuid);
        }
    }

    /**
     * 检查API sign
     * @param string $sign
     */
    private function checkApiRecord($sign = '')
    {
        $where = "sign = '{$sign}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_api_record, [], $where);
        if (!empty($result)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_REPEAT);
        }
    }

    /**
     * 签名检测
     * @param $data
     */
    private function checkSign($data)
    {
        $sign = $data['sign'];
        if ($sign != $this->getSignV2($data)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_SIGN);
        }
    }

    /**
     * 用户是否游戏中
     * @param $userID
     */
    private function checkUserPlayGame($userID)
    {
        $roomID = RedisManager::getGameRedis()->hGet(GameRedisConfig::Hash_userInfo . '|' . $userID, 'roomID');
        if ($roomID != 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PLAY_GAME);
        }
    }

    /**
     * 添加API请求记录
     * @param $data
     * @return mixed
     */
    private function addApiRecord($data)
    {
        $apiArrayData = array(
            "api" => $data['api'],
            "action" => $data['action'],
            "uuid" => $data['uuid'],
            "timestamp" => $data['timestamp'],
            'deviceType' => $data['deviceType'],
        );
        $arrayDataValue = array(
            "api" => json_encode($apiArrayData, JSON_UNESCAPED_UNICODE),
            "sign" => $data['sign'],
            "ip" => FunctionHelper::get_client_ip(),
            "time" => time(),
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_api_record, $arrayDataValue);
    }
}
