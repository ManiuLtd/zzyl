<?php
namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 好友模块
 * Class FriendModel
 */
class FriendModel extends AppModel
{
    const RECOMMEND_FRiENDS_NUM = 9;
    const USER_FRIEND_NOTIFY_NUM = 50;
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
     * 获取我的好友ID列表
     * @param $userID
     * @return array|bool
     */
    public function getUserFriendsID($userID)
    {
        $sql = "select friendID from " . MysqlConfig::Table_web_friend . " where userID = {$userID}";
        $friendsID = DBManager::getMysql()->queryAll($sql);
        FunctionHelper::arrayValueToInt($friendsID, ['friendID']);
        return $friendsID;
    }

    /**
     * 获取推荐好友
     * @param $userID
     * @return array|bool
     */
    public function getRecommendFriends($userID)
    {
        $sql = "SELECT userID,name,headURL,sex FROM "
            . MysqlConfig::Table_userinfo . " AS a"
            . " WHERE (NOT EXISTS(" . "SELECT friendID FROM " . MysqlConfig::Table_web_friend . " AS b"
            . " WHERE a.userID = b.userID))"
            . " AND (NOT EXISTS(" . "SELECT userID FROM " . MysqlConfig::Table_web_friend_notify . " AS n"
            . " WHERE targetID = {$userID} AND type=" . EnumConfig::E_FriendNotifyType['REQ_ADD']
            . " AND a.userID = n.userID ))"
            . " AND userID <> {$userID} AND isVirtual=0 ORDER BY RAND() limit "
            . self::RECOMMEND_FRiENDS_NUM;
        $recommendFriends = DBManager::getMysql()->queryAll($sql);
        FunctionHelper::arrayValueToInt($recommendFriends, ['userID', 'sex']);
        foreach ($recommendFriends as $key => &$userInfo) {
            $userInfo['onlineStatus'] = $this->getUserOnlineStatus($userInfo['userID']);
        }
        return $recommendFriends;
    }

    /**
     * 获取好友列表信息
     * @param $userID
     * @return array
     */
    public function getUserFriends($userID)
    {
        $friendsID = $this->getUserFriendsID($userID);
        $userFriendList = [];
        foreach ($friendsID as $friend) {
            $friendUserID = $friend['friendID'];
            $userFriend = $this->getUserFriend($friendUserID, $userID);
            if (!empty($userFriend)) {
                $userFriendList[] = $userFriend;
            }
        }
        return $userFriendList;
    }

    /**
     * 获取好友信息
     * @param $friendID
     * @param int $userID
     * @return array
     */
    public function getUserFriend($friendID, $userID = 0)
    {
        $userFriend = $this->getUserInfo($friendID, ['userID', 'sex', 'name', 'headURL']);
        FunctionHelper::arrayValueToInt($userFriend, ['userID', 'sex']);
        $userFriend['onlineStatus'] = $this->getUserOnlineStatus($friendID);
        if ($userID != 0) {
            $userFriend['rewardStatus'] = $this->getFriendRewardStatus($userID, $friendID);
        }
        return $userFriend;
    }

    /**
     * 添加好友通知信息
     * type 1:加好友 2: 加好友处理情况 1 param1:(1:同意 2: 拒绝) 3:打赏 6:删除好友通知 5 邀请一起玩游戏 param 桌子id param3 房间密码
     * @param $userID
     * @param $type
     * @param $targetID
     * @param int $param1
     * @param int $param2
     * @return int
     */
    public function addFriendNotify($userID, $type, $targetID, $param1 = 0, $param2 = 0)
    {
        $arrayData = array(
            "userID" => $userID,
            "type" => $type,
            "targetID" => $targetID,
            "param1" => $param1,
            "param2" => $param2,
            "time" => time(),
        );
        DBManager::getMysql()->insert(MysqlConfig::Table_web_friend_notify, $arrayData);
        return DBManager::getMysql()->insertID();
    }

    /**
     * 是否邀请过好友玩游戏
     * @param $userID
     * @param $friendID
     * @param $passwd
     * @return bool
     */
    public function isInviteFriendGame($userID, $friendID, $passwd)
    {
        $sql = "select id from " . MysqlConfig::Table_web_friend_notify
            . " where userID={$userID} and targetID={$friendID} and param2={$passwd} and type=" . EnumConfig::E_FriendNotifyType['INVITE_PLAYGAME'];
        $result = DBManager::getMysql()->queryRow($sql);
        if (!empty($result)) {
            return false;
        }
        return true;
    }

    /**
     * 查询好友通知
     * @param $id
     * @return array|mixed
     */
    public function findFriendNotify($id)
    {
        $sql = "select *  from " . MysqlConfig::Table_web_friend_notify
            . " where id={$id}";
        $result = DBManager::getMysql()->queryRow($sql);
        $result['notifyID'] = $result['id'];
        FunctionHelper::arrayValueToInt($result, ['targetID', 'type', 'notifyID', 'param1', 'param2', 'time']);
        return $result;
    }

    /**
     * 查询好友通知 多条
     * @param $userID
     * @param int $limit
     * @return array
     */
    public function getAllFriendNotify($userID, $limit = self::USER_FRIEND_NOTIFY_NUM)
    {
        $sql = "select * from " . MysqlConfig::Table_web_friend_notify
            . " where userID={$userID} order by time desc limit {$limit}";
        $result = DBManager::getMysql()->queryAll($sql);
        $notifyList = [];
        foreach ($result as $v) {
            $find = [];
            $find['targetID'] = $v['targetID'];
            $find['type'] = $v['type'];
            $find['id'] = $v['id'];
            $find['time'] = $v['time'];
            $find['param1'] = $v['param1'];
            $find['param2'] = $v['param2'];
            $find['notifyID'] = $v['id'];
            $notifyList[] = $find;
        }
        FunctionHelper::arrayValueToInt($notifyList, ['targetID', 'type', 'notifyID', 'param1', 'param2', 'time']);
        return $notifyList;
    }

    /**
     * 删除好友通知
     * @param $id
     * @return bool|mysqli_result
     */
    public function delFriendNotify($id)
    {
        $where = "id={$id}";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_friend_notify, $where);
    }

    /**
     * 记录好友打赏记录
     * @param $userID
     * @param $targetID
     * @param $notifyID
     * @param $money
     * @return bool
     */
    public function addFriendReward($userID, $targetID, $notifyID, $money)
    {
        $arrayData = array(
            "userID" => $userID,
            "targetID" => $targetID,
            "money" => $money,
            "notifyID" => $notifyID,
            "time" => time(),
        );
        //好友打赏统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'friendRewardCount');
        DBManager::getMysql()->insert(MysqlConfig::Table_web_friend_reward, $arrayData);
    }

    /**
     * 获取今天打赏次数
     * @param $userID
     * @return mixed
     */
    public function getFriendRewardCount($userID)
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));//今天开始时间戳
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;//今天结束时间戳
        $sql = "select count(*) count from " . MysqlConfig::Table_web_friend_reward
            . " where userID={$userID} and time between {$beginToday} and {$endToday} ";
        $result = DBManager::getMysql()->queryRow($sql);
        return $result['count'];
    }

    /**
     * 查询好友打赏记录
     * @param $notifyID
     * @return int
     */
    public function findUserReward($notifyID)
    {
        $sql = "select money from "
            . MysqlConfig::Table_web_friend_reward
            . " where notifyID={$notifyID}";
        $res = DBManager::getMysql()->queryRow($sql);
        return (int)$res['money'];
    }

    /**
     * 获取当天是否对该好友打赏过
     * @param $userID
     * @param $targetID
     * @return mixed
     */
    public function getFriendRewardStatus($userID, $targetID)
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));//今天开始时间戳
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;//今天结束时间戳

        $sql = "select * from " . MysqlConfig::Table_web_friend_reward
            . " where  userID={$userID} and targetID={$targetID} and time between {$beginToday} and {$endToday} ";
        $result = DBManager::getMysql()->queryRow($sql);
        if ($result) {
            return EnumConfig::E_FriendRewardStatus['ON'];
        }
        return EnumConfig::E_FriendRewardStatus['OFF'];
    }

    /**
     * 检测是否是好友
     * @param string $userID
     * @param string $friendID
     * @return bool
     */
    public function checkUserFriend($userID = '', $friendID = '')
    {
        if ($userID == '' || $friendID == '') {
            return false;
        }
        $sql = "select * from " . MysqlConfig::Table_web_friend
            . " where userID={$userID} and friendID={$friendID}";
        $result = DBManager::getMysql()->queryRow($sql);

        if (!empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * 检测是否已经有过添加记录
     * @param $userID  收到邀请的人
     * @param $friendID 发送邀请的人
     * @return bool
     */
    public function findAddFriendRecord($userID, $friendID)
    {
        $sql = "select * from " . MysqlConfig::Table_web_friend_notify
            . " where userID={$userID} and targetID={$friendID} and type = "
            . EnumConfig::E_FriendNotifyType['REQ_ADD'];
        $result = DBManager::getMysql()->queryRow($sql);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * 添加好友记录
     * @param $userID
     * @param $targetID
     */
    public function addFriendRecord($userID, $targetID)
    {
        $arrayData = array(
            "userID" => $userID,
            "friendID" => $targetID,
        );
        DBManager::getMysql()->insert(MysqlConfig::Table_web_friend, $arrayData);
        $arrayData = array(
            "userID" => $targetID,
            "friendID" => $userID,
        );
        DBManager::getMysql()->insert(MysqlConfig::Table_web_friend, $arrayData);
    }

    /**
     * 删除好友
     * @param $userID
     * @param $targetID
     */
    public function delFriendRecode($userID, $targetID)
    {
        $sql1 = "delete from " . MysqlConfig::Table_web_friend . " where userID={$userID} and friendID={$targetID}";
        $sql2 = "delete from " . MysqlConfig::Table_web_friend . " where userID={$targetID} and friendID={$userID}";
        DBManager::getMysql()->execSql($sql1);
        DBManager::getMysql()->execSql($sql2);
    }
}
