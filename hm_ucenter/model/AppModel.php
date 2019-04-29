<?php
namespace model;
use config\EnumConfig;
use config\ErrorConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use config\RedisConfig;
use config\StrategyConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;
use notify\CenterNotify;

/**
 * 通用模块
 * Class AppModel
 */
abstract class AppModel
{
    protected $tableName = null;
    protected function __construct()
    {
    }
    public function makeWhere($arrWhere) {
        $where = [];
        if (empty($arrWhere)) {
            return '';
        }
        foreach ($arrWhere as $k => $v) {
            if (is_string($v) || is_numeric($v)) {
                $where[] = $k . '="' . $v . '"';
            } elseif (is_array($v)) {
                switch ($v[0]) {
                    case 'gt':
                        $where[] = $k . '>' . $v[1];
                        break;
                    case 'lt':
                        $where[] = $k . '<' . $v[1];
                        break;
                    case 'egt':
                        $where[] = $k . '>=' . $v[1];
                        break;
                    case 'lgt':
                        $where[] = $k . '<=' . $v[1];
                        break;
                    case 'between':
                        $where[] = $k . '>=' . $v[1][0];
                        $where[] = ' AND ' . $k . '<=' . $v[1][1];
                        break;
                }
            }
        }
        $res = ' where ' . implode(' and ', $where);
        return $res;
    }

    public function makePager($page, $limit) {
        return " limit " . ($page - 1) * $limit . "," . $limit . ' ';
    }

    protected function makeOrder($field) {
        if (empty($field)) {
            return '';
        }
        return ' order by ' . $field . ' ';
    }
    /**
     * 检测用户
     */
    public function checkUser()
    {
        if (defined('PLAT_UUID')) {
            if (isset($_REQUEST['userID'])) {
                $userID = $_REQUEST['userID'];
                $exists = $this->isUserExists($userID);
                if (!$exists) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
                }
            }
        }
    }

    /**
     * 用户是否存在
     * @param $userID
     * @return bool
     */
    public function isUserExists($userID)
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        $exists = RedisManager::getGameRedis()->exists($redisKey);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 获取用户信息
     * @param $userID
     * @param string|array $keys 为空获取全部信息
     * @return string|array
     */
    public function getUserInfo($userID, $keys = [])
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        if (empty($keys)) {
            $userInfo = RedisManager::getGameRedis()->hGetAll($redisKey);
        } elseif (is_array($keys)) {
            $userInfo = RedisManager::getGameRedis()->hMget($redisKey, $keys);
        } else {
            $userInfo = RedisManager::getGameRedis()->hGet($redisKey, $keys);
        }
        FunctionHelper::iconvEncode($userInfo);
        return $userInfo;
    }

    /**
     * 用户是否在游戏中
     * @param $userID
     * @return bool
     */
    public function isUserInGame($userID) {
        $res = $this->getUserInfo($userID, ['roomID']);
        LogHelper::printDebug('---------ingame:' . var_export($res, true));
        if (isset($res['roomID']) && $res['roomID']) {
            return true;
        }
        return false;
    }

    /**
     * 更新用户数据
     * @param $userID
     * @param string $keys
     * @param string $value
     * @return bool|LONG|void
     */
    public function updateUserInfo($userID, $keys = '', $value = '')
    {
        if (empty($keys)) {
            return false;
        }
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        if (is_array($keys)) {
            $result = RedisManager::getGameRedis()->hMset($redisKey, $keys);
        } else {
            $result = RedisManager::getGameRedis()->hSet($redisKey, $keys, $value);
        }
        if ($result) {
            $where = AgentModel::getInstance()->makeWhere(['userID' => $userID]);
            if (is_array($keys)) {
                $data = AgentModel::getInstance()->makeWhere($keys);
            } else {
                $data = $keys . '=' . $value;
            }
            DBManager::getMysql()->execSql('update ' . MysqlConfig::Table_userinfo . ' set ' . $data . ' ' . $where);
            $this->addNeedUpdateUser($userID);
        }
        return $result;
    }

    /**
     * 获取用户在线状态
     * @param $userID
     * @return int
     */
    public function getUserOnlineStatus($userID)
    {
        $exists = RedisManager::getGameRedis()->sIsMember(GameRedisConfig::Set_allServerOnlineRealUserSet, $userID);
        if ($exists == 1) {
            return EnumConfig::E_UserOnlineStatus['ON'];
        }
        return EnumConfig::E_UserOnlineStatus['OFF'];
    }

    /**
     * 用户是否游戏中
     * @param $userID
     * @return bool
     */
    public function isUserPlayGame($userID)
    {
        $roomID = $this->getUserInfo($userID, 'roomID');
        return $roomID != 0;
    }

    /**
     * 用户是否代理
     * @param $userID
     * @return bool
     */
    public function isAgent($userID)
    {
        $exists = RedisManager::getGameRedis()->sIsMember(GameRedisConfig::Set_web_agentmember, $userID);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 用户是否为加盟商
     * @param $userID
     * @return bool
     */
    public function isFranchisee($userID)
    {
        $exists = DBManager::getMysql()->queryAll('select id from ' . MysqlConfig::Table_web_agent_member . ' where userid = ' . intval($userID) . ' and is_franchisee = 1 limit 1');
        if ($exists) {
            return true;
        }
        return false;
    }

    /**
     * 获取本地Redis自增String 唯一ID
     * @param $key
     * @param int $start
     * @param int $increment
     * @return int
     */
    public function getRedisIncrementID($key, $start = 1, $increment = 1)
    {
        $exists = RedisManager::getRedis()->exists($key);
        if (!$exists) {
            RedisManager::getRedis()->set($key, $start);
        }
        RedisManager::getRedis()->incr($key, $increment);
        return (int)RedisManager::getRedis()->get($key);
    }

    /**
     * 获取gameRedis自增ID 唯一ID
     * @param $key
     * @param int $start
     * @param int $increment
     * @return int
     */
    public function getGameRedisIncrementID($key, $start = 1, $increment = 1)
    {
        $exists = RedisManager::getGameRedis()->exists($key);
        if (!$exists) {
            RedisManager::getGameRedis()->set($key, $start);
        }
        RedisManager::getGameRedis()->incr($key, $increment);
        return (int)RedisManager::getGameRedis()->get($key);
    }

    /**
     * 获取所有配置
     * @return array
     */
    public function getConfig()
    {
        $otherConfig = $this->getOtherConfig();
        $gameConfig = $this->getGameConfig();
        $config = array_merge($otherConfig, $gameConfig);
        return $config;
    }

    /**
     * 获取其他配置（游戏配置）
     * @return array
     */
    public function getOtherConfig()
    {
        // otherConfig
        $sql = "select * from " . MysqlConfig::Table_otherconfig;
        $data = DBManager::getMysql()->queryAll($sql);

        $config = [];
        foreach ($data as $v) {
            $config[$v['keyConfig']] = $v['valueConfig'];
        }
        return $config;
    }
    /**
     * 获取后台配置
     * @return array
     */
    public function getHomeConfig()
    {
        // 网页相关配置
        $sql = "select * from " . MysqlConfig::Table_web_home_config;
        $data = DBManager::getMysql()->queryAll($sql);
        $config = [];
        foreach ($data as $v) {
            $config[$v['key']] = $v['value'];
        }
        return $config;
    }
    /**
     * 获取后台配置
     * @return array
     */
    public function getGameConfig()
    {
        // web_gameconfig 后台配置
        $sql = "select * from " . MysqlConfig::Table_web_game_config;
        $data = DBManager::getMysql()->queryAll($sql);
        $config = [];
        foreach ($data as $v) {
            $config[$v['key']] = $v['value'];
        }
        return $config;
    }

    /**
     * 改变用户的资源
     * @param $userID
     * @param $resourceType
     * @param $change
     * @param $changeReason
     * @param int $roomID
     * @param int $friendsGroupID
     * @param int $rateMoney
     * @param int $isAllowInGame 在游戏中是否允许改变
     * @return bool
     */
    public function changeUserResource($userID, $resourceType, $change, $changeReason, $roomID = 0, $friendsGroupID = 0, $rateMoney = 0, $isAllowInGame = 1)
    {
        if ($change == 0) {
            return false;
        }
        if (0 == $isAllowInGame) {
            $isInGame = UserModel::getInstance()->getUserInfo($userID, ['roomID']);
            if (!empty($isInGame)) {
                return false;
            }
        }
        if ($resourceType == EnumConfig::E_ResourceType['MONEY']) {
            $value = -1;
            if (StrategyConfig::M_RESOURCE_HANDLE_MODE['PHP'] == StrategyConfig::M_RESOURCE_HANDLE_MODE_ON) {
                $value = $this->changeUserMoney($userID, $change);
            }
        } else if ($resourceType == EnumConfig::E_ResourceType['JEWELS']) {
            $value = $this->changeUserJewels($userID, $change);
        } else if ($resourceType == EnumConfig::E_ResourceType['BANKMONEY']) {
            $value = $this->changeUserBankMoney($userID, $change);
        } else if ($resourceType == EnumConfig::E_ResourceType['FIRECOIN']) {
            if ($friendsGroupID == 0) {
                return false;
            }
            $value = $this->changeUserFireCoin($friendsGroupID, $userID, $change);
        } else {
            LogHelper::printError(['changeUserResource resourceType error'
                , [$userID, $resourceType, $change, $changeReason, $roomID, $friendsGroupID, $rateMoney]]);
            return false;
        }
        $this->addNeedUpdateUser($userID);
        if ($resourceType == EnumConfig::E_ResourceType['FIRECOIN']) {
            $fireCoinChangeInfoList = [];
            $fireCoinChangeInfo = array(
                'userID' => $userID,
                'newFireCoin' => $value,
            );
            $fireCoinChangeInfoList[] = $fireCoinChangeInfo;
            //推送火币变化 给俱乐部所有人
            CenterNotify::friendsGroupMemberFireCoinChangeAll($friendsGroupID, $fireCoinChangeInfoList);
        } else {
            $res = CenterNotify::resourceChange($userID, $resourceType, $value, $change, $changeReason);
            LogHelper::printDebug('recharge----------------------------' . var_export($res, true));
        }
        if (StrategyConfig::M_RESOURCE_HANDLE_MODE['PHP'] == StrategyConfig::M_RESOURCE_HANDLE_MODE_ON) {
            $this->resourceChangeLog($userID, $resourceType, $value, $change, $changeReason, $roomID, $friendsGroupID, $rateMoney);
        }
        return true;
    }

    /**
     * 资源改变日志
     * @param $userID
     * @param $resourceType
     * @param $value
     * @param $change
     * @param $changeReason
     * @param $roomID
     * @param $friendsGroupID
     * @param $rate
     */
    public function resourceChangeLog($userID, $resourceType, $value, $change, $changeReason, $roomID, $friendsGroupID, $rate)
    {
        if ($resourceType == EnumConfig::E_ResourceType['MONEY']) {
            $this->moneyChangeLog($userID, $value, $change, $changeReason, $roomID, $friendsGroupID, $rate);
        } else if ($resourceType == EnumConfig::E_ResourceType['JEWELS']) {
            $this->jewelsChangeLog($userID, $value, $change, $changeReason, $roomID, $friendsGroupID, $rate);
        } else if ($resourceType == EnumConfig::E_ResourceType['FIRECOIN']) {
            $this->fireCoinChangeLog($userID, $value, $change, $changeReason, $roomID, $friendsGroupID, $rate);
        }
    }

    /**
     * 改变用户的金币
     * @param $userID
     * @param $change
     * @return int
     */
    public function changeUserMoney($userID, $change)
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        return RedisManager::getGameRedis()->hIncrBy($redisKey, 'money', $change);
    }

    /**
     * 改变用户的银行金币
     * @param $userID
     * @param $change
     * @return int
     */
    public function changeUserBankMoney($userID, $change)
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        return RedisManager::getGameRedis()->hIncrBy($redisKey, 'bankMoney', $change);
    }

    /**
     * 改变俱乐部用户的火币
     * @param $friendsGroupID
     * @param $userID
     * @param $change
     * @return int
     */
    public function changeUserFireCoin($friendsGroupID, $userID, $change)
    {
        $redisKey = RedisManager::makeKey(RedisConfig::Hash_friendsGroupToUser, $friendsGroupID, $userID);
        return RedisManager::getRedis()->hIncrBy($redisKey, 'carryFireCoin', $change);
    }

    /**
     * 改变用户的钻石
     * @param $userID
     * @param $change
     * @return mixed
     */
    public function changeUserJewels($userID, $change)
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userInfo, $userID);
        return RedisManager::getGameRedis()->hIncrBy($redisKey, 'jewels', $change);
    }

    /**
     * 金币变化日志
     * @param $userID
     * @param $money
     * @param $changeMoney
     * @param int $reason
     * @param int $roomID
     * @param int $friendsGroupID
     * @param int $rateMoney
     * @return mixed
     */
    public function moneyChangeLog($userID, $money, $changeMoney, $reason = 0, $roomID = 0, $friendsGroupID = 0, $rateMoney = 0)
    {
        $arrayData = array(
            'userID' => $userID,
            'time' => time(),
            'money' => $money,
            'changeMoney' => $changeMoney,
            'reason' => $reason,
            'roomID' => $roomID,
            'friendsGroupID' => $friendsGroupID,
            'rateMoney' => $rateMoney,
        );
        $result = DBManager::getMysql()->insert(MysqlConfig::Table_statistics_moneychange, $arrayData);
        if (empty($result)) {
            LogHelper::printError([['金币变化日志写入失败'], [$userID, $money, $changeMoney, $reason, $roomID, $friendsGroupID, $rateMoney]]);
        }
        return $result;
    }

    /**
     * 钻石变化日志
     * @param $userID
     * @param $jewels
     * @param $changeJewels
     * @param int $reason
     * @param int $roomID
     * @param int $friendsGroupID
     * @param int $rateJewels
     * @return mixed
     */
    public function jewelsChangeLog($userID, $jewels, $changeJewels, $reason = 0, $roomID = 0, $friendsGroupID = 0, $rateJewels = 0)
    {
        $arrayData = array(
            'userID' => $userID,
            'time' => time(),
            'jewels' => $jewels,
            'changeJewels' => $changeJewels,
            'reason' => $reason,
            'roomID' => $roomID,
            'friendsGroupID' => $friendsGroupID,
            'rateJewels' => $rateJewels,
        );
        $result = DBManager::getMysql()->insert(MysqlConfig::Table_statistics_jewelschange, $arrayData);
        if (empty($result)) {
            LogHelper::printError([['钻石变化日志写入失败'], [$userID, $jewels, $changeJewels, $reason, $roomID, $friendsGroupID, $rateJewels]]);
        }
        return $result;
    }

    /**
     * 俱乐部火币变化日志
     * @param $userID
     * @param $fireCoin
     * @param $changeFireCoin
     * @param int $reason
     * @param int $roomID
     * @param int $friendsGroupID
     * @param int $rateFireCoin
     * @return mixed
     */
    public function fireCoinChangeLog($userID, $fireCoin, $changeFireCoin, $reason = 0, $roomID = 0, $friendsGroupID = 0, $rateFireCoin = 0)
    {
        $arrayData = array(
            'userID' => $userID,
            'time' => time(),
            'fireCoin' => $fireCoin,
            'changeFireCoin' => $changeFireCoin,
            'reason' => $reason,
            'roomID' => $roomID,
            'friendsGroupID' => $friendsGroupID,
            'rateFireCoin' => $rateFireCoin,
        );
        $result = DBManager::getMysql()->insert(MysqlConfig::Table_statistics_firecoinchange, $arrayData);
        if (empty($result)) {
            LogHelper::printError([['俱乐部火币变化日志写入失败'], [$userID, $fireCoin, $changeFireCoin, $reason, $roomID, $friendsGroupID, $rateFireCoin]]);
        }
        return $result;
    }

    /**
     * 增加需要更新的用户
     * @param $userID
     */
    public function addNeedUpdateUser($userID)
    {
        RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_cacheUpdateSet, GameRedisConfig::Hash_userInfo . '|' . $userID);
    }

    /**
     * 返回处理结果 Json形式
     * @param string $code
     * @param string $msg
     * @param array $data
     */
    public static function returnJson($code = '', $msg = '', $data = [])
    {
        FunctionHelper::iconvEncode($data);
        $json = json_encode(['status' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
        //debug
        LogHelper::printDebug('routeMark:api=' . isset($_REQUEST['api']) ? $_REQUEST['api'] : 'unknown api' . ' action=' . isset($_REQUEST['action'] ) ? $_REQUEST['action'] : 'unknown action');
        LogHelper::printDebug($json);
        //结束统计耗时
        LogHelper::mark(LOG_MARK_END);
        exit($json);
    }

    /**
     * 返回处理结果 Json形式
     * @param string $code
     * @param string $msg
     * @param array $data
     */
    public static function returnJsonNew($code = '', $msg = '', $data = [])
    {
        FunctionHelper::iconvEncode($data);
        $json = json_encode(['status' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_SLASHES);
        //debug
        LogHelper::printDebug('routeMark:api=' . isset($_REQUEST['api']) ? $_REQUEST['api'] : 'unknown api' . ' action=' . isset($_REQUEST['action'] ) ? $_REQUEST['action'] : 'unknown action');
        LogHelper::printDebug($json);
        //结束统计耗时
        LogHelper::mark(LOG_MARK_END);
        exit($json);
    }

    /**
     * 返回处理结果 string形式
     * 更新的时候需要用到
     * @param string $data
     */
    public static function returnString($data = '')
    {
        FunctionHelper::iconvEncode($data);
        //结束统计耗时
        LogHelper::mark(LOG_MARK_END);
        exit($data);
    }

    /**
     * 返回处理结果 xml 微信支付有用到
     * @param array $data
     */
    public static function returnXml($data = [])
    {
        FunctionHelper::iconvEncode($data);
        //结束统计耗时
        LogHelper::mark(LOG_MARK_END);
        $xml = FunctionHelper::arrayToXml($data);
        exit($xml);
    }

    final public function select($where, $field = '*', $page = 1, $limit = 10, $order = null) {
        $where = DBManager::getMysql()->makeWhere($where);
        $pager = $this->makePager($page, $limit);
        $order = $this->makeOrder($order);
        if (empty($this->tableName)) {
            throw new \Exception('table 不能为空');
        }
        return DBManager::getMysql()->queryAll('select ' . $field . ' from ' . $this->tableName . $where . $order . $pager);
    }
}
