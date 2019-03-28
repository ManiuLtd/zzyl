<?php
namespace model;
use config\EnumConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;

/**
 * 俱乐部模块
 * Class ClubModel
 */
class ClubModel extends AppModel
{
    const FRIENDS_GROUP_ID_START = 100000;

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
     * 创建俱乐部
     * @param $userID
     * @param $name
     * @return int
     */
    public function createFriendsGroup($userID, $name)
    {
        //获取俱乐部唯一ID
        do {
            $friendsGroupID = $this->getRedisIncrementID(RedisConfig::String_friendsGroupID, self::FRIENDS_GROUP_ID_START);
            $exists = $this->isFriendsGroupExists($friendsGroupID);
        } while ($exists);

        //俱乐部结构
        $notice = "欢迎来到{$name}，俱乐部群主可自行设置公告内容。";
        $friendsGroup = array(
            'friendsGroupID' => $friendsGroupID, //俱乐部ID
            'name' => $name, //名字
            'peopleCount' => 1, //人数
            'alterNameCount' => 0, //今天改名次数
            'alterNameTime' => 0, //上次改名时间
            'allAlterNameCount' => 0, //总共改名次数
            'createTime' => time(), //创建时间
            'masterID' => $userID, //群主ID
            'notice' => $notice, //公告
            'wechat' => '', //签名
            'hotCount' => 0, //热度 暂时保留
        );
        // 俱乐部信息 friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, $friendsGroup);
        $this->createFriendsGroupToDB($friendsGroup);

        // 俱乐部名字集合 friendsGroupNameSet
        RedisManager::getRedis()->sAdd(RedisConfig::Set_friendsGroupNameSet, $name);

        // 俱乐部ID集合 friendsGroupIDSet
        RedisManager::getRedis()->sAdd(RedisConfig::Set_friendsGroupIDSet, $friendsGroupID);

        //俱乐部成员结构
        $friendsGroupToUser = array(
            'friendsGroupID' => $friendsGroupID, //俱乐部ID
            'userID' => $userID, //成员ID
            'joinTime' => time(), //加入时间
            'score' => 0, //积分变化
            'money' => 0, //金币变化
            'fireCoin' => 0, //火币变化
            'carryFireCoin' => 0, //携带火币
        );
        // 写入redis 俱乐部成员 friendsGroupToUser
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, $friendsGroupToUser);

        // 写入redis 玩家俱乐部 userToFriendsGroup
        $userToFriendsGroup = [
            'friendsGroupID' => $friendsGroupID, //俱乐部ID
            'userID' => $userID, //玩家ID
            'status' => EnumConfig::E_FriendsGroupMemberStatus['KING'], //身份
            'power' => EnumConfig::E_FriendsGroupPowerType['ALL'], //权限
        ];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID, $userToFriendsGroup);

        $friendsGroupToUser['power'] = $userToFriendsGroup['power'];
        $this->joinFriendsGroupToDB($friendsGroupToUser);

        // 写入redis 俱乐部成员集合 friendsGroupToUserSet
        $kingScore = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['KING']];
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $kingScore, $userID);

        // 写入redis 玩家俱乐部集合 userToFriendsGroupSet
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, EnumConfig::E_Redis_U2F_Set_Score['CREATE_FG'], $friendsGroupID);
        return $friendsGroupID;
    }

    /**
     * 数据库增加俱乐部
     * @param $friendsGroup
     */
    public function createFriendsGroupToDB($friendsGroup)
    {
        $arrayDataValue = array(
            'friendsGroupID' => $friendsGroup['friendsGroupID'], //俱乐部ID
            'name' => $friendsGroup['name'], //名字
            'masterID' => $friendsGroup['masterID'], //群主名字
            'createTime' => $friendsGroup['createTime'], //创建时间
            'hotCount' => 0, //热度 暂时保留
        );
        DBManager::getMysql()->insert(MysqlConfig::Table_web_club, $arrayDataValue);
    }

    /**
     * 获取用户的俱乐部列表
     * @param $userID
     * @return array
     */
    public function getFriendsGroupList($userID)
    {
        //$t1 = microtime(true);
        $userToFriendsGroupSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, 0, -1);
        //$t1 = microtime(true);
        //$t2 = microtime(true);
        //echo '耗时'.round($t2-$t1,3).'秒<br>';
        $friendsGroupList = [];
        foreach ($userToFriendsGroupSet as $friendsGroupID) {
            //$t1 = microtime(true);
            $friendsGroup = $this->getFriendsGroup($friendsGroupID, $userID);
            //$t2 = microtime(true);
            //echo '耗时'.round($t2-$t1,3).'秒<br>';
            if (!empty($friendsGroup)) {
                //加入俱乐部到列表
                $friendsGroupList[] = $friendsGroup;
            }
        }
        exit;
        return $friendsGroupList;
    }

    /**
     * 获取俱乐部信息
     * @param $friendsGroupID
     * @param int $userID
     * @return array
     */
    public function getFriendsGroup($friendsGroupID, $userID = 0)
    {
        $friendsGroup = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID);
        $intKeyArray = array(
            'friendsGroupID',
            'peopleCount',
            'createTime',
            'masterID',
            'alterNameCount',
            'alterNameTime',
            'allAlterNameCount',
        );
        //一些值需要转为int
        FunctionHelper::arrayValueToInt($friendsGroup, $intKeyArray);
        if ($userID != 0) {
            $t1 = microtime(true);
            //俱乐部前面9个ID 用于显示头像
            $friendsGroup['frontMember'] = $this->getFriendsGroupFrontMember($friendsGroupID);
            //在线人数
            $friendsGroup['currOnlineCount'] = $this->getFriendsGroupOnlineCount($friendsGroupID);
            $t2 = microtime(true);
            echo '耗时'.round($t2-$t1,3).'秒<br>';
            //已开牌桌数量
            $t1 = microtime(true);
            $friendsGroup['deskCount'] = $this->getFriendsGroupOpenDeskCount($friendsGroupID);
            $t2 = microtime(true);
            echo '耗时'.round($t2-$t1,3).'秒<br>';
            //已开VIP房间数量
            $t1 = microtime(true);
            $friendsGroup['VIPRoomCount'] = $this->getFriendsGroupVIPRoomCount($friendsGroupID);
            //身份
            $friendsGroup['status'] = $this->getFriendsGroupMemberStatus($friendsGroupID, $userID);
            //权限
            $friendsGroup['power'] = $this->getFriendsGroupMemberPower($friendsGroupID, $userID);
            //携带火币
            $friendsGroup['fireCoin'] = $this->getFriendsGroupMemberCarryFireCoin($friendsGroupID, $userID);
            $t2 = microtime(true);
            echo '耗时'.round($t2-$t1,3).'秒<br>';
        }
        return $friendsGroup;
    }

    /**
     * 加入俱乐部
     * @param $friendsGroupID
     * @param $userID
     */
    public function joinFriendsGroup($friendsGroupID, $userID)
    {
        // 写入redis 俱乐部成员 friendsGroupToUser
        $friendsGroupToUser = [
            'friendsGroupID' => $friendsGroupID, //俱乐部ID
            'userID' => $userID, //成员ID
            'joinTime' => time(), //加入时间
            'score' => 0, //积分变化
            'money' => 0, //金币变化
            'fireCoin' => 0, //火币变化
            'carryFireCoin' => 0, //携带火币
        ];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, $friendsGroupToUser);

        // 写入redis 玩家俱乐部 userToFriendsGroup
        $userToFriendsGroup = [
            'friendsGroupID' => $friendsGroupID, //俱乐部ID
            'userID' => $userID, //玩家ID
            'status' => EnumConfig::E_FriendsGroupMemberStatus['NORMAL'], //身份
            'power' => EnumConfig::E_FriendsGroupPowerType['NO'], //权限
        ];
        RedisManager::getRedis()->hMset(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID , $userToFriendsGroup);

        $friendsGroupToUser['power'] = $userToFriendsGroup['power'];
        $this->joinFriendsGroupToDB($friendsGroupToUser);

        // 写入redis 俱乐部成员集合 friendsGroupToUserSet
        $normalScore = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['NORMAL']];
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $normalScore, $userID);

        // 写入redis 玩家俱乐部集合 userToFriendsGroupSet
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, EnumConfig::E_Redis_U2F_Set_Score['JOIN_FG'], $friendsGroupID);

        //俱乐部人数加1
        RedisManager::getRedis()->hIncrBy(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, 'peopleCount', 1);
    }

    /**
     * 加入俱乐部 To DB
     * @param $friendsGroupToUser
     */
    public function joinFriendsGroupToDB($friendsGroupToUser)
    {
        $arrayDataValue = array(
            'friendsGroupID' => $friendsGroupToUser['friendsGroupID'], //俱乐部ID
            'userID' => $friendsGroupToUser['userID'], //成员ID
            'joinTime' => $friendsGroupToUser['joinTime'], //加入时间
            'power' => $friendsGroupToUser['power'], //权限
        );
        DBManager::getMysql()->insert(MysqlConfig::Table_web_club_member, $arrayDataValue);
    }

    /**
     * 删除俱乐部成员
     * @param $friendsGroupID
     * @param $userID
     * @return mixed
     */
    public function delFriendsGroupMember($friendsGroupID, $userID)
    {
        //退出俱乐部删除火币 如果为负数 相当于加上这么多火币
        $changeFireCoin = $this->getFriendsGroupMemberCarryFireCoin($friendsGroupID, $userID);
        $this->changeUserResource($userID, EnumConfig::E_ResourceType['FIRECOIN'], -$changeFireCoin, EnumConfig::E_ResourceChangeReason['EXIT_CLUB'], 0, $friendsGroupID);
        //删除俱乐部玩家 friendsGroupToUser
        RedisManager::getRedis()->del(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID);
        //删除玩家俱乐部 userToFriendsGroup
        RedisManager::getRedis()->del(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID);
        //玩家俱乐部集合移除俱乐部ID userToFriendsGroupSet
        RedisManager::getRedis()->zRem(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, $friendsGroupID);
        //俱乐部玩家集合移除userID friendsGroupToUserSet
        RedisManager::getRedis()->zRem(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $userID);
        //俱乐部人数-1
        RedisManager::getRedis()->hIncrBy(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, 'peopleCount', -1);
        return $this->delFriendsGroupMemberToDB($friendsGroupID, $userID);
    }

    public function delFriendsGroupMemberToDB($friendsGroupID, $userID)
    {
        $where = "friendsGroupID = {$friendsGroupID} and userID = {$userID}";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_club_member, $where);
    }

    /**
     * 解散俱乐部
     * @param $friendsGroupID
     * @return mixed
     */
    public function dismissFriendsGroup($friendsGroupID)
    {
        //俱乐部信息
        $friendsGroup = $this->getFriendsGroup($friendsGroupID);
        //俱乐部哈希表 friendsGroup
        RedisManager::getRedis()->del(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID);
        //俱乐部名字集合 移除俱乐部名字 friendsGroupNameSet
        RedisManager::getRedis()->sRem(RedisConfig::Set_friendsGroupNameSet, $friendsGroup['name']);
        //俱乐部ID集合 移除俱乐部ID friendsGroupIDSet
        RedisManager::getRedis()->sRem(RedisConfig::Set_friendsGroupIDSet, $friendsGroupID);

        // 俱乐部所有的成员ID set
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        foreach ($friendsGroupToUserSet as $memberUserID) {
            //删除俱乐部成员信息 friendsGroupToUser
            RedisManager::getRedis()->del(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $memberUserID);
            //删除玩家俱乐部信息 userToFriendsGroup
            RedisManager::getRedis()->del(RedisConfig::Hash_userToFriendsGroup . '|' . $memberUserID . ',' . $friendsGroupID);
            //玩家俱乐部集合移除俱乐部ID userToFriendsGroupSet
            RedisManager::getRedis()->zRem(RedisConfig::SSet_userToFriendsGroupSet . '|' . $memberUserID, $friendsGroupID);
        }
        // 俱乐部玩家ID列表 friendsGroupToUserSet
        RedisManager::getRedis()->del(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID);

        return $this->dismissFriendsGroupToDB($friendsGroupID);
    }

    /**
     * 解散俱乐部 To DB
     * @param $friendsGroupID
     * @return mixed
     */
    public function dismissFriendsGroupToDB($friendsGroupID)
    {
        $where = "friendsGroupID = {$friendsGroupID}";
        DBManager::getMysql()->delete(MysqlConfig::Table_web_club_member, $where);
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_club, $where);
    }

    /**
     * 修改俱乐部名字
     * @param $friendsGroupID
     * @param $newName
     * @param $alterNameCount
     * @param $allAlterNameCount
     */
    public function changeFriendsGroupName($friendsGroupID, $newName, $alterNameCount, $allAlterNameCount)
    {
        //俱乐部信息
        $friendsGroup = $this->getFriendsGroup($friendsGroupID);
        //俱乐部名字集合 移除旧名字 新增新的名字 friendsGroupNameSet
        RedisManager::getRedis()->sRem(RedisConfig::Set_friendsGroupNameSet, $friendsGroup['name']);
        RedisManager::getRedis()->sAdd(RedisConfig::Set_friendsGroupNameSet, $newName);
        $changeArray = array(
            'name' => $newName,
            'alterNameTime' => time(),
            'alterNameCount' => $alterNameCount,
            'allAlterNameCount' => $allAlterNameCount,
        );
        //俱乐部哈希表 更新名字 friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, $changeArray);

        $this->changeFriendsGroupNameToDB($friendsGroupID, $newName);
    }

    /**
     * 修改俱乐部名字 To DB
     * @param $friendsGroupID
     * @param $newName
     * @return mixed
     */
    public function changeFriendsGroupNameToDB($friendsGroupID, $newName)
    {
        $arrayDataValue = array(
            'name' => $newName,
        );
        $where = "friendsGroupID = {$friendsGroupID}";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_club, $arrayDataValue, $where);
    }

    /**
     * 今天改名次数
     * @param $friendsGroupID
     * @return int|mixed
     */
    public function getChangeFriendsGroupCount($friendsGroupID)
    {
        //俱乐部信息
        $friendsGroup = $this->getFriendsGroup($friendsGroupID);
        $alterNameCount = $friendsGroup['alterNameCount'];
        $alterNameTime = $friendsGroup['alterNameTime'];
        $today = date('Y-m-d', time());
        if ($alterNameTime == 0 || date('Y-m-d', $alterNameTime) != $today) {
            return 0;
        }
        return $alterNameCount;
    }

    /**
     * 修改俱乐部公告
     * @param $friendsGroupID
     * @param $notice
     */
    public function changeFriendsGroupNotice($friendsGroupID, $notice)
    {
        //俱乐部哈希表 更新公告 friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, ['notice' => $notice]);
    }

    /**
     * 修改俱乐部微信（签名）
     * @param $friendsGroupID
     * @param $wechat
     */
    public function changeFriendsGroupWechat($friendsGroupID, $wechat)
    {
        //俱乐部哈希表 更新微信(签名) friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, ['wechat' => $wechat]);
    }

    /**
     * 修改俱乐部热度
     * @param $friendsGroupID
     * @param $hotCount
     * @return mixed
     */
    public function changeFriendsGroupHotCount($friendsGroupID, $hotCount)
    {
        //俱乐部哈希表 更新热度 friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, ['hotCount' => $hotCount]);

        return $this->changeFriendsGroupHotCountToDB($friendsGroupID, $hotCount);
    }

    /**
     * 修改俱乐部热度 To DB
     * @param $friendsGroupID
     * @param $hotCount
     * @return mixed
     */
    public function changeFriendsGroupHotCountToDB($friendsGroupID, $hotCount)
    {
        $arrayDataValue = array(
            'hotCount' => $hotCount,
        );
        $where = "friendsGroupID = {$friendsGroupID}";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_club, $arrayDataValue, $where);
    }

    /**
     * 修改俱乐部成员身份
     * @param $friendsGroupID
     * @param $userID
     * @param $status
     * @return int
     */
    public function changeFriendsGroupMemberStatus($friendsGroupID, $userID, $status)
    {
        //更新身份
        RedisManager::getRedis()->hMset(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID, ['status' => $status]);
        // 刷新玩家 friendsGroupToUserSet score
        $score = EnumConfig::E_Redis_F2U_Set_Score[$status];
        $result = RedisManager::getRedis()->zAdd(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $score, $userID);
        if ($result !== false) {
            if ($status == EnumConfig::E_FriendsGroupMemberStatus['NORMAL']) {
                //如果是普通成员 取消权限
                $this->changeFriendsGroupMemberPower($friendsGroupID, $userID, EnumConfig::E_FriendsGroupPowerType['NO']);
            } elseif ($status == EnumConfig::E_FriendsGroupMemberStatus['KING']) {
                //如果是群主 获取所有权限
                $this->changeFriendsGroupMemberPower($friendsGroupID, $userID, EnumConfig::E_FriendsGroupPowerType['ALL']);
            }
        }
        return $result;
    }

    /**
     * 修改俱乐部成员权限
     * @param $friendsGroupID
     * @param $userID
     * @param $power
     * @return mixed
     */
    public function changeFriendsGroupMemberPower($friendsGroupID, $userID, $power)
    {
        //更新权限
        RedisManager::getRedis()->hMset(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID, ['power' => $power]);

        return $this->changeFriendsGroupMemberPowerToDB($friendsGroupID, $userID, $power);
    }

    /**
     * 修改俱乐部成员权限 To DB
     * @param $friendsGroupID
     * @param $userID
     * @param $power
     * @return mixed
     */
    public function changeFriendsGroupMemberPowerToDB($friendsGroupID, $userID, $power)
    {
        $arrayDataValue = array(
            'power' => $power,
        );
        $where = "friendsGroupID = {$friendsGroupID} and userID = {$userID}";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_club_member, $arrayDataValue, $where);
    }

    /**
     * 转让俱乐部群主
     * @param $friendsGroupID
     * @param $userID
     * @param $tarUserID
     * @return mixed
     */
    public function transferFriendsGroupMaster($friendsGroupID, $userID, $tarUserID)
    {
        //俱乐部信息 群主ID更换
        $changeArray = array(
            'masterID' => $tarUserID,
        );
        //俱乐部哈希表 更新名字 friendsGroup
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID, $changeArray);
        //群主身份变为普通成员
        $this->changeFriendsGroupMemberStatus($friendsGroupID, $userID, EnumConfig::E_FriendsGroupMemberStatus['NORMAL']);
        //目标用户变为群主身份
        $this->changeFriendsGroupMemberStatus($friendsGroupID, $tarUserID, EnumConfig::E_FriendsGroupMemberStatus['KING']);

        return $this->transferFriendsGroupMasterToDB($friendsGroupID,$tarUserID);
    }

    /**
     * 转让俱乐部群主 To DB
     * @param $friendsGroupID
     * @param $newMasterID
     * @return mixed
     */
    public function transferFriendsGroupMasterToDB($friendsGroupID, $newMasterID)
    {
        $arrayDataValue = array(
            'masterID' => $newMasterID,
        );
        $where = "friendsGroupID = {$friendsGroupID}";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_club, $arrayDataValue, $where);
    }

    /**
     * 增加俱乐部火币操作日志
     * @param $friendsGroupID
     * @param $userID
     * @param $changeFireCoin
     * @param $operationID
     * @return mixed
     */
    public function addFriendsGroupFireCoinOperationLog($friendsGroupID, $userID, $changeFireCoin, $operationID = EnumConfig::E_ChangeFireCoinID['NONE'])
    {
        $arrayData = array(
            "friendsGroupID" => $friendsGroupID,
            "time" => time(),
            "userID" => $userID,
            "costMoney" => 0,
            "costJewels" => 0,
            "fireCoinRecharge" => $changeFireCoin > 0 ? $changeFireCoin : 0,
            "fireCoinExchange" => $changeFireCoin < 0 ? $changeFireCoin : 0,
            "moneyPump" => 0,
            "fireCoinPump" => 0,
            "operationID" => $operationID,
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_friendsgroupdesklistcost, $arrayData);
    }

    /**
     * 俱乐部成员列表信息
     * @param $friendsGroupID
     * @return array
     */
    public function getFriendsGroupMemberList($friendsGroupID)
    {
        // 俱乐部所有的成员ID set
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        $friendsGroupMemberList = [];
        foreach ($friendsGroupToUserSet as $memberUserID) {
            $friendsGroupMember = $this->getFriendsGroupMember($friendsGroupID, $memberUserID);
            if (!empty($friendsGroupMember)) {
                $friendsGroupMemberList[] = $friendsGroupMember;
            }
        }
        return $friendsGroupMemberList;
    }

    /**
     * 俱乐部成员信息
     * @param $friendsGroupID
     * @param $userID
     * @return array
     */
    public function getFriendsGroupMember($friendsGroupID, $userID)
    {
        $friendsGroupMember = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID);
        //全部转为int
        FunctionHelper::arrayValueToInt($friendsGroupMember);
        //身份
        $friendsGroupMember['status'] = $this->getFriendsGroupMemberStatus($friendsGroupID, $userID);
        //权限
        $friendsGroupMember['power'] = $this->getFriendsGroupMemberPower($friendsGroupID, $userID);
        //在线状态
        $friendsGroupMember['onlineStatus'] = $this->getUserOnlineStatus($userID);
        return $friendsGroupMember;
    }

    /**
     * 获取俱乐部战绩列表
     * @param $friendsGroupID
     * @param int $firstTime
     * @param int $endTime
     * @param $roomType
     * @return array
     */
    public function getFriendsGroupRecordList($friendsGroupID, $firstTime = 0, $endTime = 0, $roomType)
    {
        //数量限制 最近的50条
        $num = 50;
        //时间
        $time_sql = '';
        if ($firstTime != 0) {
            $time_sql .= " and time >= {$firstTime}";
        }
        if ($endTime != 0) {
            $time_sql .= " and time <= {$endTime}";
        }
        //数据库查询 friendsGroupAccounts
        $sql = "select * from " . MysqlConfig::Table_friendsgroupaccounts
            . " where friendsGroupID = {$friendsGroupID} and roomType = {$roomType} {$time_sql} order by id desc limit {$num}";
        //查询列表
        $friendsGroupAccountsList = DBManager::getMysql()->queryAll($sql);

        //数据处理 值为1的表示需要处理为int
        $needKeyArray = array(
            "friendsGroupID" => 1,
            "userID" => 1,
            "time" => 1,
            "roomID" => 1,
            "realPlayCount" => 1,
            "playCount" => 1,
            "playMode" => 1,
            "roomType" => 1,
            "srcType" => 1,
            'passwd' => 0,
            'userInfoList' => 0,
        );
        FunctionHelper::arrayNeedValueToInt($friendsGroupAccountsList, $needKeyArray);
        $friendsGroupRecordList = [];
        foreach ($friendsGroupAccountsList as $friendsGroupAccounts) {
            $friendsGroupRecord = $friendsGroupAccounts;
            //将 userInfoList 用#分割 第一段战绩 第二段抽水 第三段胜局
            if (strpos($friendsGroupRecord['userInfoList'], '#') !== false) {
                $friendsGroupRecord['userInfoList'] = explode('#', $friendsGroupRecord['userInfoList'])[0];
                $intKeyArray = array(
                    'userID',
                    'score',
                );
                $friendsGroupRecord['userInfoList'] = FunctionHelper::splitStringToArray($friendsGroupRecord['userInfoList'], $intKeyArray);
            }
            $friendsGroupRecord['deskID'] = $friendsGroupRecord['srcType'];
            $friendsGroupRecordList[] = $friendsGroupRecord;
        }
        return $friendsGroupRecordList;
    }

    /**
     * 获取俱乐部成员战绩统计列表
     * @param $friendsGroupID
     * @param int $firstTime
     * @param int $endTime
     * @return array
     */
    public function getFriendsGroupMemberRecordTotalList($friendsGroupID, $firstTime = 0, $endTime = 0)
    {
        //时间
        $time_sql = '';
        if ($firstTime != 0) {
            $time_sql .= " and time >= {$firstTime}";
        }
        if ($endTime != 0) {
            $time_sql .= " and time <= {$endTime}";
        }
        //数据库查询 friendsGroupAccounts
        $sql = "select * from " . MysqlConfig::Table_friendsgroupaccounts
            . " where friendsGroupID = {$friendsGroupID} {$time_sql} order by id desc";
        //查询列表
        $friendsGroupAccountsList = DBManager::getMysql()->queryAll($sql);
        //数据处理 值为1的表示需要处理为int
        $needKeyArray = array(
            "roomType" => 1,
            'userInfoList' => 0,
        );
        FunctionHelper::arrayNeedValueToInt($friendsGroupAccountsList, $needKeyArray);
        $tempTotalList = [];
        foreach ($friendsGroupAccountsList as $key => $friendsGroupAccounts) {
            //获取需要的数据存在临时的数组中
            $tempArray = $friendsGroupAccounts;
            //将 userInfoList 用#分割 第一段战绩 第二段抽水 第三段胜局
            if (strpos($tempArray['userInfoList'], '#') !== false) {
                $tempArray['userInfoList'] = explode('#', $tempArray['userInfoList'])[0];
                $userInfoArray = FunctionHelper::splitStringToArray($tempArray['userInfoList']);
                foreach ($userInfoArray as $userInfo) {
                    //获取该玩家的数据
                    $tempTotal = $tempTotalList[$userInfo[0]];
                    if (empty($tempTotal)) {
                        //不存在初始化数据
                        $tempTotal = array(
                            'userID' => $userInfo[0],
                            'score' => 0,
                            'money' => 0,
                            'fireCoin' => 0,
                        );
                    }
                    //合并数据
                    if ($tempArray['roomType'] == EnumConfig::E_RoomType['CARD']) {
                        $tempTotal['score'] = $tempTotal['score'] + $userInfo[1];
                    } elseif ($tempArray['roomType'] == EnumConfig::E_RoomType['PRIVATE']) {
                        $tempTotal['money'] = $tempTotal['money'] + $userInfo[1];
                    } elseif ($tempArray['roomType'] == EnumConfig::E_RoomType['MATCH']) {
                        $tempTotal['fireCoin'] = $tempTotal['fireCoin'] + $userInfo[1];
                    }
                    $tempTotalList[$userInfo[0]] = $tempTotal;
                }
            }
        }
        //转换为下标从0开始
        $friendsGroupMemberRecordTotalList = array_values($tempTotalList);
        return $friendsGroupMemberRecordTotalList;
    }

    /**
     * 获取俱乐部成员战绩统计列表
     * @param $friendsGroupID
     * @param $firstTime
     * @param $endTime
     * @return bool
     */
    public function getFriendsGroupCostTotal($friendsGroupID, $firstTime, $endTime)
    {
        //需要统计的列
        $sumColumnArray = array(
            "costMoney", //消耗的金币
            'costJewels', //消耗的钻石
            'fireCoinRecharge', //火币充值
            'fireCoinExchange', //火币回收
            'moneyPump', //金币抽水
            'fireCoinPump', //火币抽水
        );
        $sql_sum = '';
        foreach ($sumColumnArray as $column) {
            $sql_sum .= " SUM({$column}) {$column},";
        }
        $sql_sum = rtrim($sql_sum, ',');
        //时间
        $time_sql = '';
        if ($firstTime != 0) {
            $time_sql .= " and time >= {$firstTime}";
        }
        if ($endTime != 0) {
            $time_sql .= " and time <= {$endTime}";
        }
        //数据库查询 friendsGroupDeskListCost
        $sql = "select {$sql_sum} from " . MysqlConfig::Table_friendsgroupdesklistcost
            . " where friendsGroupID = {$friendsGroupID} {$time_sql} order by id desc";
        //查询总共的消耗
        $friendsGroupCostTotal = DBManager::getMysql()->queryRow($sql);
        //全部转为int
        FunctionHelper::arrayValueToInt($friendsGroupCostTotal);
        return $friendsGroupCostTotal;
    }

    /**
     * 俱乐部是否存在
     * @param $friendsGroupID
     * @return bool
     */
    public function isFriendsGroupExists($friendsGroupID)
    {
        $exists = RedisManager::getRedis()->exists(RedisConfig::Hash_friendsGroup . '|' . $friendsGroupID);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 俱乐部名字是否存在
     * @param $name
     * @return bool
     */
    public function isFriendGroupNameExists($name)
    {
        $exists = RedisManager::getRedis()->sIsMember(RedisConfig::Set_friendsGroupNameSet, $name);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 是否群成员
     * @param $friendsGroupID
     * @param $userID
     * @return bool
     */
    public function isFriendsGroupMemberExists($friendsGroupID, $userID)
    {
        $score = RedisManager::getRedis()->zScore(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $userID);
        if ($score === false) {
            return false;
        }
        return true;
    }

    /**
     * 获取前面9个俱乐部成员ID
     * @param $friendsGroupID
     * @return array|Mixed
     */
    public function getFriendsGroupFrontMember($friendsGroupID)
    {
        $frontMemberIDList = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, 8);
        if ($frontMemberIDList === false) {
            return [];
        }
        FunctionHelper::arrayValueToInt($frontMemberIDList);
        return $frontMemberIDList;
    }

    /**
     * 获取俱乐部在线人数
     * @param $friendsGroupID
     * @return int
     */
    public function getFriendsGroupOnlineCount($friendsGroupID)
    {
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        $onlineCount = 0;
        foreach ($friendsGroupToUserSet as $userID) {
            if ($this->getUserOnlineStatus($userID) == EnumConfig::E_UserOnlineStatus['ON']) {
                $onlineCount++;
            }
        }
        return $onlineCount;
    }

    /**
     * 获取俱乐部玩家身份
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberStatus($friendsGroupID, $userID)
    {
        $status = RedisManager::getRedis()->hGet(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID, 'status');
        if ($status === false) {
            return EnumConfig::E_FriendsGroupMemberStatus['NORMAL'];
        }
        return (int)$status;
    }

    /**
     * 获取俱乐部玩家权限
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberPower($friendsGroupID, $userID)
    {
        $power = RedisManager::getRedis()->hGet(RedisConfig::Hash_userToFriendsGroup . '|' . $userID . ',' . $friendsGroupID, 'power');
        if ($power === false) {
            return EnumConfig::E_FriendsGroupPowerType['NO'];
        }
        return (int)$power;
    }

    /**
     * 获取俱乐部玩家积分变化
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberScore($friendsGroupID, $userID)
    {
        $score = RedisManager::getRedis()->hGet(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, 'score');
        if ($score === false) {
            return 0;
        }
        return (int)$score;
    }

    /**
     * 获取俱乐部玩家金币变化
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberMoney($friendsGroupID, $userID)
    {
        $money = RedisManager::getRedis()->hGet(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, 'money');
        if ($money === false) {
            return 0;
        }
        return (int)$money;
    }

    /**
     * 获取俱乐部玩家火币变化
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberFireCoin($friendsGroupID, $userID)
    {
        $fireCoin = RedisManager::getRedis()->hGet(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, 'fireCoin');
        if ($fireCoin === false) {
            return 0;
        }
        return (int)$fireCoin;
    }

    /**
     * 获取俱乐部玩家携带火币
     * @param $friendsGroupID
     * @param $userID
     * @return int
     */
    public function getFriendsGroupMemberCarryFireCoin($friendsGroupID, $userID)
    {
        $carryFireCoin = RedisManager::getRedis()->hGet(RedisConfig::Hash_friendsGroupToUser . '|' . $friendsGroupID . ',' . $userID, 'carryFireCoin');
        if ($carryFireCoin === false) {
            return 0;
        }
        return (int)$carryFireCoin;
    }

    /**
     * 获取俱乐部数量
     * @param $userID
     * @return int
     */
    public function getFriendsGroupCount($userID)
    {
        $count = RedisManager::getRedis()->zCount(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, EnumConfig::E_Redis_U2F_Set_Score['CREATE_FG'], EnumConfig::E_Redis_U2F_Set_Score['JOIN_FG']);
        return $count;
    }

    /**
     * 获取创建俱乐部数量
     * @param $userID
     * @return int
     */
    public function getCreateFriendsGroupCount($userID)
    {
        $count = RedisManager::getRedis()->zCount(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, EnumConfig::E_Redis_U2F_Set_Score['CREATE_FG'], EnumConfig::E_Redis_U2F_Set_Score['CREATE_FG']);
        return $count;
    }

    /**
     * 获取加入俱乐部数量
     * @param $userID
     * @return int
     */
    public function getJoinFriendsGroupCount($userID)
    {
        $count = RedisManager::getRedis()->zCount(RedisConfig::SSet_userToFriendsGroupSet . '|' . $userID, EnumConfig::E_Redis_U2F_Set_Score['JOIN_FG'], EnumConfig::E_Redis_U2F_Set_Score['JOIN_FG']);
        return $count;
    }

    /**
     * 获取俱乐部已开桌子数
     * @param $friendsGroupID
     * @return int
     */
    public function getFriendsGroupOpenDeskCount($friendsGroupID, $floor = 0)
    {
        $openDeskCount = 0;
        for ($i = 1; $i <= 20; $i++) {
            $FGRoomInfo = RedisManager::getGameRedis()->get(GameRedisConfig::String_FGRoomInfo . '|' . $friendsGroupID . ',' . $floor . ',' . $i);
            if ($FGRoomInfo !== false) {
                $openDeskCount++;
            }
        }
        return $openDeskCount;
    }

    /**
     * 获取俱乐部vip房间数
     * @param $friendsGroupID
     * @return int
     */
    public function getFriendsGroupVIPRoomCount($friendsGroupID, $floor = 0)
    {
        $VIPRoomCount = 0;
        for ($i = 21; $i <= 30; $i++) {
            $FGRoomInfo = RedisManager::getGameRedis()->get(GameRedisConfig::String_FGRoomInfo . '|' . $friendsGroupID . ',' . $floor . ',' . $i);
            if ($FGRoomInfo !== false) {
                $VIPRoomCount++;
            }
        }
        return $VIPRoomCount;
    }

    /**
     * 获取俱乐部vip房间数
     * @param $friendsGroupID
     * @return int
     */
    public function getFriendsGroupAllDeskList($friendsGroupID, $floor = 0)
    {
        $deskList = [];
        for ($i = 1; $i <= 30; $i++) {
            $deskInfo = $this->getFriendsGroupDeskInfo($friendsGroupID, $i, $floor);
            if (!empty($deskInfo)) {
                $deskList[] = $deskInfo;
            }
        }
        return $deskList;
    }

    /**
     * 获取俱乐部已开牌桌列表
     * @param $friendsGroupID
     * @return array
     */
    public function getFriendsGroupOpenDeskList($friendsGroupID, $floor = 0)
    {
//        return [];
        $deskList = [];
        for ($i = 1; $i <= 18; $i++) {
            $deskInfo = $this->getFriendsGroupDeskInfo($friendsGroupID, $i, $floor);
            if (!empty($deskInfo) && !empty($deskInfo['deskID'])) {
                $deskList[] = $deskInfo;
            }
        }
        return $deskList;
    }

    /**
     * 获取俱乐部vip房间列表
     * @param $friendsGroupID
     * @return array
     */
    public function getFriendsGroupVIPRoomList($friendsGroupID, $floor = 0)
    {
        $deskList = [];
        for ($i = 1; $i <= 6; $i++) {
            $deskInfo = $this->getFriendsGroupDeskInfo($friendsGroupID, $i, $floor);
            if (!empty($deskInfo) && !empty($deskInfo['deskID'])) {
                $deskList[] = $deskInfo;
            }
        }
        return $deskList;
    }

    /**
     * 获取俱乐部房间信息
     * @param $friendsGroupID
     * @param $deskID
     * @return array
     */
    public function getFriendsGroupDeskInfo($friendsGroupID, $deskID, $floor = 0)
    {
        $deskInfo = [];
        LogHelper::printDebug( $friendsGroupID . ',' . $floor .  ',' . $deskID);
        $FGRoomInfo = RedisManager::getGameRedis()->get(GameRedisConfig::String_FGRoomInfo . '|' . $friendsGroupID . ',' . $floor . ',' . $deskID);
        LogHelper::printDebug($FGRoomInfo);
        if ($FGRoomInfo !== false) {
            $privateDeskInfo = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_privateDeskInfo . '|' . $FGRoomInfo);
            LogHelper::printDebug($privateDeskInfo);
            $needKeyArray = array(
                'friendsGroupID' => 1,
                'friendsGroupDeskNumber' => 1,
                'createTime' => 1,
                'roomID' => 1,
                'gameID' => 1,
                'roomType' => 1,
                'currDeskUserCount' => 1,
                'maxDeskUserCount' => 1,
                'passwd' => 0,
                'gameRules' => 0,
                'arrUserID' => 0,
                'buyGameCount' => 1,
                'deskIdx' => 1,
                'curGameCount' => 1,
            );
            $deskInfo = $privateDeskInfo;
            FunctionHelper::arrayNeedValueToInt($deskInfo, $needKeyArray);
            $deskInfo['userIDList'] = [];
            foreach ($deskInfo as $key => $value) {
                if ($key == 'arrUserID') {
                    if (strpos($deskInfo[$key], ',') !== false) {
                        //删除最后一个逗号
                        $temp = rtrim($deskInfo[$key], ',');
                        $deskInfo['userIDList'] = explode(',', $temp);
                        FunctionHelper::arrayValueToInt($deskInfo['userIDList']);
                    }
                }
            }
            $deskInfo['deskID'] = $deskInfo['friendsGroupDeskNumber'];
            $deskInfo['curPeopleCount'] = $deskInfo['currDeskUserCount'];
            $deskInfo['allPeopleCount'] = $deskInfo['maxDeskUserCount'];
            $deskInfo['playCount'] = $deskInfo['buyGameCount'];
            $deskInfo['gameStatus'] = $deskInfo['curGameCount'] == 0 ? 0 : 1;
        }
        return $deskInfo;
    }

    /**
     * 获取俱乐部管理员ID列表(包括群主)
     * @param $friendsGroupID
     * @return array
     */
    public function getFriendsGroupManagerIDList($friendsGroupID)
    {
        $start = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['KING']];
        $end = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['MANAGER']];
        $managerIDList = RedisManager::getRedis()->zRangeByScore(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $start, $end);
        FunctionHelper::arrayValueToInt($managerIDList);
        return $managerIDList;
    }

    /**
     * 获取俱乐部管理员数量
     * @param $friendsGroupID
     * @return int
     */
    public function getFriendsGroupManagerCount($friendsGroupID)
    {
        $start = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['MANAGER']];
        $end = EnumConfig::E_Redis_F2U_Set_Score[EnumConfig::E_FriendsGroupMemberStatus['MANAGER']];
        $count = RedisManager::getRedis()->zCount(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, $start, $end);
        return $count;
    }

    /**
     * 俱乐部成员是否有具体功能权限
     * 1.身份是群主返回true
     * 2.身份是管理员判断权限
     * 3.身份是普通成员返回false
     * @param $friendsGroupID
     * @param $funcPower
     * @param $userID
     * @return bool
     */
    public function isFriendsGroupMamberHasFuncPower($friendsGroupID, $funcPower, $userID)
    {
        $status = $this->getFriendsGroupMemberStatus($friendsGroupID, $userID);
        if ($status == EnumConfig::E_FriendsGroupMemberStatus['KING']) {
            return true;
        } elseif ($status == EnumConfig::E_FriendsGroupMemberStatus['NORMAL']) {
            return false;
        } elseif ($status == EnumConfig::E_FriendsGroupMemberStatus['MANAGER']) {
            $power = $this->getFriendsGroupMemberPower($friendsGroupID, $userID);
            return ($power & $funcPower) == $funcPower;
        }
        return false;
    }

    /**
     * 比较俩个俱乐部成员的身份权重
     * @param $friendsGroupID
     * @param $userID
     * @param $tarUserID
     * @return bool
     */
    public function compareFriendsGroupMemberStatusWeight($friendsGroupID, $userID, $tarUserID)
    {
        $status = $this->getFriendsGroupMemberStatus($friendsGroupID, $userID);
        $tarStatus = $this->getFriendsGroupMemberStatus($friendsGroupID, $tarUserID);
        return EnumConfig::E_FriendsGroupStatusWeight[$status] > EnumConfig::E_FriendsGroupStatusWeight[$tarStatus];
    }

    /**
     * 增加俱乐部通知
     * @param $type
     * @param $friendsGroupID
     * @param $param1
     * @param int $param2
     * @return int
     */
    public function addFriendsGroupNotify($type, $friendsGroupID, $param1, $param2 = 0)
    {
        //获取俱乐部通知唯一ID
        do {
            $notifyID = $this->getRedisIncrementID(RedisConfig::String_friendsGroupNotifyID);
            $exists = $this->isFriendsGroupNotifyExists($notifyID);
        } while ($exists);

        //俱乐部通知结构
        $friendsGroup = $this->getFriendsGroup($friendsGroupID);
        $friendsGroupNotify = array(
            'notifyID' => $notifyID, //通知ID
            'targetFriendsGroupID' => $friendsGroupID, //俱乐部的ID
            'name' => $friendsGroup['name'], //俱乐部名字
            'type' => $type, //通知类型
            'time' => time(), //时间
            'param1' => $param1, //int参数1
            'param2' => $param2, //int参数2
        );

        // 写入redis 俱乐部通知 friendsGroupNotify
        RedisManager::getRedis()->hMset(RedisConfig::Hash_friendsGroupNotify . '|' . $notifyID, $friendsGroupNotify);
        return $notifyID;
    }

    /**
     * 删除俱乐部通知
     * @param $notifyID
     */
    public function delFriendsGroupNotify($notifyID)
    {
        //删除通知
        RedisManager::getRedis()->del(RedisConfig::Hash_friendsGroupNotify . '|' . $notifyID);
    }

    /**
     * 获取俱乐部消息列表
     * @param $userID
     * @return array
     */
    public function getFriendsGroupNotifyList($userID)
    {
        //取最近的50条
        $max_notify_count = 50;
        $userToFriendsGroupNotifySet = RedisManager::getRedis()->zRevRange(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $userID, 0, $max_notify_count - 1);
        $friendsGroupNotifyList = [];
        foreach ($userToFriendsGroupNotifySet as $notifyID) {
            $friendsGroupNotify = $this->getFriendsGroupNotify($notifyID);
            if (!empty($friendsGroupNotify)) {
                //加入俱乐部通知到列表
                $friendsGroupNotifyList[] = $friendsGroupNotify;
            }
        }
        return $friendsGroupNotifyList;
    }

    /**
     * 获取俱乐部通知信息
     * @param $notifyID
     * @return array
     */
    public function getFriendsGroupNotify($notifyID)
    {
        $friendsGroupNotify = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_friendsGroupNotify . '|' . $notifyID);
        $intKeyArray = array(
            'notifyID',
            'userID',
            'targetFriendsGroupID',
            'type',
            'time',
            'param1',
            'param2',
        );
        //一些值需要转为int
        FunctionHelper::arrayValueToInt($friendsGroupNotify, $intKeyArray);
        return $friendsGroupNotify;
    }

    /**
     * 俱乐部通知是否存在
     * @param $notifyID
     * @return bool
     */
    public function isFriendsGroupNotifyExists($notifyID)
    {
        $exists = RedisManager::getRedis()->exists(RedisConfig::Hash_friendsGroupNotify . '|' . $notifyID);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 是否已经在目标的通知列表
     * @param $targetUserID
     * @param $type
     * @param $targetFriendsGroupID
     * @param $param1
     * @param int $param2
     * @return bool
     */
    public function isInTargetNotifyList($targetUserID, $type, $targetFriendsGroupID, $param1, $param2 = 0)
    {
        $friendsGroupNotifyList = $this->getFriendsGroupNotifyList($targetUserID);
        foreach ($friendsGroupNotifyList as $friendsGroupNotify) {
            if ($friendsGroupNotify['type'] == $type
                && $friendsGroupNotify['targetFriendsGroupID'] == $targetFriendsGroupID
                && $friendsGroupNotify['param1'] == $param1
                && $friendsGroupNotify['param2'] == $param2
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取人数前10的俱乐部
     * @param int $num
     * @return array
     */
    public function getFriendsGroupListByPeopleCountDesc($num = 10)
    {
        // 俱乐部所有的成员ID set
        $friendsGroupIDSet = RedisManager::getRedis()->sMembers(RedisConfig::Set_friendsGroupIDSet);
        $allFriendsGroupList = [];
        foreach ($friendsGroupIDSet as $friendsGroupID) {
            $friendsGroup = $this->getFriendsGroup($friendsGroupID);
            $allFriendsGroupList[] = $friendsGroup;
        }
        //按热度-人数-俱乐部ID排序
        function sort_func($a, $b)
        {
            if ($a == $b) {
                return 0;
            }
            if ($a['hotCount'] == $b['hotCount']) {
                if ($a['peopleCount'] == $b['peopleCount']) {
                    return $a['friendsGroupID'] < $b['friendsGroupID'] ? -1 : 1;
                }
                return $a['peopleCount'] > $b['peopleCount'] ? -1 : 1;
            }
            return $a['hotCount'] > $b['hotCount'] ? -1 : 1;
        }

        uasort($allFriendsGroupList, 'sort_func');
        return array_slice($allFriendsGroupList, 0, $num - 1);
    }
}
