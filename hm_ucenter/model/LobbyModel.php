<?php
namespace model;
use config\GameRedisConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;

/**
 * 大厅模块
 * Class LobbyModel
 */
class LobbyModel extends AppModel
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
        $this->checkUser();
    }

    private function __clone()
    {
    }

    /**
     * 获取游戏列表
     * @return array|bool
     */
    public function getGameList($is_recommend = 0, $field = '*')
    {
        $where = '';
        $arrWhere['is_hide'] = 0;

        if (!empty($is_recommend)) {
            $arrWhere['is_Recommend'] = $is_recommend;
//            $where = ' where is_Recommend = ' . $is_recommend . ' and ';
        }
        $where = $this->makeWhere($arrWhere);

        //数据库查询 roomBaseInfo
        $sql = "select {$field} from " . MysqlConfig::Table_gamebaseinfo . $where . ' order by sort desc ';
        //查询列表
        $gameBaseInfoList = DBManager::getMysql()->queryAll($sql);
        //数据处理 值为1的表示需要处理为int
        $needKeyArray = array(
            "gameID" => 1,
            "name" => 0,
            "dllName" => 0,
            "deskPeople" => 1,
            "watcherCount" => 1,
            "deskCount" => 1,
        );
        FunctionHelper::arrayNeedValueToInt($gameBaseInfoList, $needKeyArray);
        return $gameBaseInfoList;
    }

    /**
     * 获取房间列表
     * @return array|bool
     */
    public function getRoomList($gameID = 0, $is_Recommend = 0)
    {
        $arrWhere = [];
        $arrWhere['is_hide'] = 0;
        if (!empty($gameID)) {
            $arrWhere['gameID'] = $gameID;
        }
        if (!empty($is_Recommend)) {
            $arrWhere['is_Recommend'] = $is_Recommend;
        }
        $where = $this->makeWhere($arrWhere);

        //数据库查询 roomBaseInfo
        $sql = "select * from " . MysqlConfig::Table_roombaseinfo . $where;
        //查询列表
        $roomBaseInfoList = DBManager::getMysql()->queryAll($sql);

        //数据处理 值为1的表示需要处理为int
        $needKeyArray = array(
            "roomID" => 1,
            "gameID" => 1,
            "name" => 0,
            "port" => 1,
            "type" => 1,
            "deskCount" => 1,
            "maxPeople" => 1,
            "minPoint" => 1,
            'maxPoint' => 1,
            'status' => 1,
            'ip' => 0,
            'describe' => 0,
            'roomSign' => 1,
            'gameBeginCostMoney' => 1,
            'basePoint' => 1,
            'sort' => 1,
            'level' => 1,
        );
        FunctionHelper::arrayNeedValueToInt($roomBaseInfoList, $needKeyArray);
        return $roomBaseInfoList;
    }

    /**
     * 获取购买信息列表
     * @param $gameID
     * @return array|bool
     */
    public function buyDeskList($gameID)
    {
        //数据库查询 privateDeskConfig
        $sql = "select * from " . MysqlConfig::Table_privatedeskconfig . " where gameID = {$gameID}";
        //查询列表
        $privateDeskConfigList = DBManager::getMysql()->queryAll($sql);

        //数据处理 值为1的表示需要处理为int
        $needKeyArray = array(
            "gameID" => 1,
            "count" => 1,
            "roomType" => 1,
            "costResType" => 1,
            "costNums" => 1,
            "AAcostNums" => 1,
            "otherCostNums" => 1,
            "peopleCount" => 1,
        );
        FunctionHelper::arrayNeedValueToInt($privateDeskConfigList, $needKeyArray);
        return $privateDeskConfigList;
    }

    // 获取开房列表
    public function getBuyRoomList($userID)
    {
        $buyDeskSet = RedisManager::getGameRedis()->sMembers(GameRedisConfig::Set_cacheUserBuyDeskSet . '|' . $userID);

        $buyDeskList = [];

        foreach ($buyDeskSet as $passwd) {
            $buyDesk = $this->getBuyRoomInfo($passwd);
            if (!empty($buyDesk)) {
                $buyDeskList[] = $buyDesk;
            }
        }
        return $buyDeskList;
    }

    public function getBuyRoomInfo($passwd)
    {
        if (!$this->isBuyRoomExists($passwd)) {
            return [];
        }
        $deskID = RedisManager::getGameRedis()->get(GameRedisConfig::String_privateDeskPasswd . '|' . $passwd);
        $privateDeskInfo = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_privateDeskInfo . '|' . $deskID);
        $needKeyArray = array(
            'deskIdx' => 1,
            'createTime' => 1,
            'roomID' => 1,
            'buyGameCount' => 1,
            'curGameCount' => 1,
            'passwd' => 0,
            'gameRules' => 0,
            'maxDeskUserCount' => 1,
        );
        FunctionHelper::arrayNeedValueToInt($privateDeskInfo, $needKeyArray);
        return $privateDeskInfo;
    }

    /**
     * 房间是否存在
     * @param $passwd
     * @return bool
     */
    public function isBuyRoomExists($passwd)
    {
        $exists = RedisManager::getGameRedis()->exists(GameRedisConfig::String_privateDeskPasswd . '|' . $passwd);
        return $exists;
    }

    /**
     * 添加世界广播
     * @param $userID
     * @param $content
     * @param $cost
     * @return mixed
     */
    public function addHorn($userID, $content, $cost)
    {
        $arrayData = array(
            'userID' => $userID,
            'content' => $content,
            'cost' => $cost,
            'reqTime' => time(),
            'total_cost_jewels' => 0,
            'total_count' => 0,
        );
        //发送世界广播统计信息
        $where = "userID={$userID} order by reqTime desc";
        $arrayKeyValue = ['total_count', 'total_cost_jewels'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_horn, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_cost_jewels'] = $data['total_cost_jewels'];
            $arrayData['total_count'] = $data['total_count'];
        }
        UserModel::getInstance()->addWebUserInfoValue($userID, 'hornCount');
        UserModel::getInstance()->addWebUserInfoValue($userID, 'hornCostJewels', $cost);
        $arrayData['total_count'] += 1;
        $arrayData['total_cost_jewels'] += $cost;
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_horn, $arrayData);
    }

    /**
     * 获取奖池数据
     * @param int $roomID
     * @param array $keys
     * @return array|string
     */
    public function getRewardsPoolToRedis($roomID = 0, $keys = [])
    {
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_rewardsPool, $roomID);
        if (empty($keys)) {
            $rewardsPool = RedisManager::getGameRedis()->hGetAll($redisKey);
        } elseif (is_array($keys)) {
            $rewardsPool = RedisManager::getGameRedis()->hMget($redisKey, $keys);
        } else {
            $rewardsPool = RedisManager::getGameRedis()->hGet($redisKey, $keys);
        }
        return $rewardsPool;
    }

    /**
     * 更新奖池配置
     * @param int $roomID
     * @param array $rewardsPool
     * @return bool
     */
    public function updateRewardsPool($roomID = 0, $rewardsPool = [])
    {
        LogHelper::printDebug($roomID);
        LogHelper::printDebug($rewardsPool);
        if ($roomID == 0 || empty($rewardsPool)) {
            return false;
        }
        if ($this->updateRewardsPoolToRedis($roomID, $rewardsPool)) {
            return $this->updateRewardsPoolToDB($roomID, $rewardsPool);
        }
        return false;
    }

    /**
     * 更新奖池配置 to redis
     * @param int $roomID
     * @param array $rewardsPool
     * @return bool
     */
    public function updateRewardsPoolToRedis($roomID = 0, $rewardsPool = [])
    {
        if ($roomID == 0 || empty($rewardsPool)) {
            return false;
        }
        $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_rewardsPool, $roomID);
        return RedisManager::getGameRedis()->hMset($redisKey, $rewardsPool);
    }

    /**
     * 更新奖池配置 to DB
     * @param int $roomID
     * @param array $rewardsPool
     * @return bool
     */
    public function updateRewardsPoolToDB($roomID = 0, $rewardsPool = [])
    {
        if ($roomID == 0 || empty($rewardsPool)) {
            return false;
        }
        $where = "roomID = {$roomID}";
        return DBManager::getMysql()->update(MysqlConfig::Table_rewardspool, $rewardsPool, $where);
    }
}
