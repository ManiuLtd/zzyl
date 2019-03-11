<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use config\GameRedisConfig;
use helper\FunctionHelper;
use manager\RedisManager;
use model\AppModel;
use model\FriendModel;
use model\UserModel;
use notify\CenterNotify;

/**
 * 好友业务
 * Class FriendAction
 */
class FriendAction extends AppAction
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
     * 好友推荐
     * @param $param
     */
    public function recommendFriends($param)
    {
        $userID = (int)$param['userID'];
        $recommendFriends = FriendModel::getInstance()->getRecommendFriends($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $recommendFriends);
    }

    /**
     * 获取好友列表
     * @param $param
     */
    public function friendList($param)
    {
        $userID = (int)$param['userID'];
        $userFriends = FriendModel::getInstance()->getUserFriends($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $userFriends);
    }

    /**
     * 好友打赏
     * @param $param
     */
    public function friendReward($param)
    {
        $userID = (int)$param['userID'];
        $friendID = (int)$param['friendID'];

        //目标用户是否存在
        $isExists = FriendModel::getInstance()->isUserExists($friendID);
        if (!$isExists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DOES_NOT_EXIST);
        }
        //是否好友
        $isFriend = FriendModel::getInstance()->checkUserFriend($userID, $friendID);
        if (!$isFriend) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DONTS_ALREADY_FRIEND);
        }

        //今天是否打赏过
        $rewardStatus = FriendModel::getInstance()->getFriendRewardStatus($userID, $friendID);
        if ($rewardStatus == EnumConfig::E_FriendRewardStatus['ON']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_IS_REWARD_USER);
        }

        $config = FriendModel::getInstance()->getConfig();
        // 打赏次数
        $count = FriendModel::getInstance()->getFriendRewardCount($userID);
        if ($count > $config['friendRewardMoneyCount']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "一天只能打赏{$config['friendRewardMoneyCount']}次");
        }

        // 写入好友通知
        $insertID = FriendModel::getInstance()->addFriendNotify($friendID, EnumConfig::E_FriendNotifyType['REWARD'], $userID);
        // 写入打赏记录
        FriendModel::getInstance()->addFriendReward($userID, $friendID, $insertID, $config['friendRewardMoney']);
        // 写入小红点
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $friendID, 'friendNotifyList', 1);
        // 推送好友通知
        CenterNotify::sendRedSport($friendID, EnumConfig::E_RedSpotType['FRIEND']); // 小红点
        CenterNotify::friendNotify($friendID, $insertID, EnumConfig::E_FriendNotifyType['REWARD'], $userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 搜索好友
     * @param $param
     */
    public function searchFriend($param)
    {
        $targetID = (int)$param['targetID'];

        $isExists = FriendModel::getInstance()->isUserExists($targetID);
        if (!$isExists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }

        $targetUser = FriendModel::getInstance()->getUserInfo($targetID, ['name', 'userID', 'headURL']);
        FunctionHelper::arrayValueToInt($targetUser, ['userID']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, [$targetUser]);
    }

    /**
     * 添加好友
     * @param $param
     */
    public function addFriend($param)
    {
        $userID = (int)$param['userID'];
        $targetID = (int)$param['targetID'];

        if ($userID == $targetID) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_YOU_CANT_MAKE_FRIENS_WITH_YOURSELF);
        }

        $isExists = FriendModel::getInstance()->isUserExists($targetID);
        if (!$isExists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }

        // 是否是好友
        $isFriend = FriendModel::getInstance()->checkUserFriend($userID, $targetID);
        if ($isFriend) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_IT_IS_ALREADY_FRIEND);
        }

        //是否已经发送添加好友请求
        $isAddFriendRecord = FriendModel::getInstance()->findAddFriendRecord($targetID, $userID);
        if ($isAddFriendRecord) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ADD_FRIEND_NOTICE);
        }

        // 发送好友消息
        $insertID = FriendModel::getInstance()->addFriendNotify($targetID, EnumConfig::E_FriendNotifyType['REQ_ADD'], $userID);
        // 推送小红点
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $targetID, 'friendNotifyList', 1);
        // 推送好友通知
        CenterNotify::friendNotify($targetID, $insertID, EnumConfig::E_FriendNotifyType['REQ_ADD'], $userID);
        CenterNotify::sendRedSport($targetID, EnumConfig::E_RedSpotType['FRIEND']); // 小红点
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 删除好友
     * @param $param
     */
    public function delFriend($param)
    {
        $userID = $param['userID'];
        $friendID = $param['friendID'];

        $isExists = FriendModel::getInstance()->isUserExists($friendID);
        if (!$isExists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }

        // 检测是不是好友
        $isFriend = FriendModel::getInstance()->checkUserFriend($userID, $friendID);
        if (!$isFriend) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DONTS_ALREADY_FRIEND);
        }

        // 删除表
        FriendModel::getInstance()->delFriendRecode($userID, $friendID);
        // 消息通知
        $insertID = FriendModel::getInstance()->addFriendNotify($friendID, EnumConfig::E_FriendNotifyType['DEL'], $userID);
        // 推送小红点
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $friendID, 'friendNotifyList', 1);
        // 推送好友通知
        CenterNotify::sendRedSport($friendID, EnumConfig::E_RedSpotType['FRIEND']); // 小红点
        CenterNotify::friendNotify($friendID, $insertID, EnumConfig::E_FriendNotifyType['DEL'], $userID);
        CenterNotify::delFriend($friendID, $userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 处理好友消息
     * @param $param
     */
    public function handleFriendMessage($param)
    {
        $id = (int)$param['id'];
        $type = (int)$param['type'];
        $action = (int)$param['actionType']; // 操作类型
        $find = FriendModel::getInstance()->findFriendNotify($id);
        if (empty($find)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_HAVA_BEEN_TREATED);
        }
        switch ($type) {
            case 1: // + 好友消息
                if ($action == EnumConfig::E_OperateType['REFUSE']) {
                    // 拒绝消息
                    $insertID = FriendModel::getInstance()->addFriendNotify($find['targetID'], EnumConfig::E_FriendNotifyType['ANSWER_ADD'], $find['userID'], $action);
                    FriendModel::getInstance()->delFriendNotify($id);
                    // + 小红点
                    RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $find['userID'], 'friendNotifyList', 1);
                    // 推送好友通知
                    CenterNotify::sendRedSport($find['targetID'], EnumConfig::E_RedSpotType['FRIEND']); // 小红点
                    CenterNotify::friendNotify($find['targetID'], $insertID, EnumConfig::E_FriendNotifyType['ANSWER_ADD'], $find['userID'], $action);
                } else if ($action == EnumConfig::E_OperateType['AGREE']) {
                    // 同意消息
                    $insertID = FriendModel::getInstance()->addFriendNotify($find['targetID'], EnumConfig::E_FriendNotifyType['ANSWER_ADD'], $find['userID'], $action);
                    // 已加
                    FriendModel::getInstance()->addFriendRecord($find['targetID'], $find['userID']);
                    // 删除原来的消息
                    FriendModel::getInstance()->delFriendNotify($id);
                    // 小红点
                    RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $find['userID'], 'friendNotifyList', 1);
                    // 推送好友通知
                    CenterNotify::sendRedSport($find['targetID'], EnumConfig::E_RedSpotType['FRIEND']); // 小红点
                    CenterNotify::friendNotify($find['targetID'], $insertID, EnumConfig::E_FriendNotifyType['ANSWER_ADD'], $find['userID'], $action);
                    //推送好友信息
                    CenterNotify::addFriend($find['userID'], FriendModel::getInstance()->getUserFriend($find['targetID'], $find['userID']));
                    CenterNotify::addFriend($find['targetID'], FriendModel::getInstance()->getUserFriend($find['userID'], $find['targetID']));
                } else {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                }
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            case 2: // 删除好友消息
                FriendModel::getInstance()->delFriendNotify($id);
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            case 3: // 领取好友打赏
                $money = FriendModel::getInstance()->findUserReward($id);
                if (!$money) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_HAVE_BEEN_RECEIVED);
                }

                FriendModel::getInstance()->changeUserResource($find['userID'], EnumConfig::E_ResourceType['MONEY'], $money, EnumConfig::E_ResourceChangeReason['FRIEND_REWARD']);
                // 删除记录
                FriendModel::getInstance()->delFriendNotify($id);
                //好友打赏统计信息
                UserModel::getInstance()->addWebUserInfoValue($find['userID'], 'getFriendRewardCount');
                UserModel::getInstance()->addWebUserInfoValue($find['userID'], 'friendRewardGetMoney', $money);
                
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            default:
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                break;
        }
    }

    /**
     * 好友消息列表
     * @param $param
     */
    public function friendNotifyList($param)
    {
        $userID = (int)$param['userID'];
        $msg = FriendModel::getInstance()->getAllFriendNotify($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $msg);
    }

    /**
     * 删除小红点
     * @param $param
     */
    public function delFriendRedSpot($param)
    {
        $userID = (int)$param['userID'];
        $redSpotType = (int)$param['redSpotType'];

        $type = [1 => 'friendList', 'friendNotifyList'];

        RedisManager::getGameRedis()->hMset(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, [$type[$redSpotType] => 0]);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 邀请好友玩游戏
     * @param $param
     */
    public function inviteFriendPlay($param)
    {
        $userID = (int)$param['userID'];
        $passwd = (int)$param['passwd'];
        $friendID = (int)$param['friendID'];

        $isExists = FriendModel::getInstance()->isUserExists($friendID);
        if (!$isExists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }

        // 检测是不是好友
        $isFriend = FriendModel::getInstance()->checkUserFriend($userID, $friendID);
        if (!$isFriend) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DONTS_ALREADY_FRIEND);
        }

        //是否邀请过
        $isInviteFriendGame = FriendModel::getInstance()->isInviteFriendGame($userID, $friendID, $passwd);
        if ($isInviteFriendGame) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_YOU_HAVA_INVITED_THE_PLAYER);
        }

        //房间是否存在
        $deskID = RedisManager::getGameRedis()->get(GameRedisConfig::String_privateDeskPasswd . '|' . $passwd);
        if ($deskID === false) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_ROOM_DOES_NOT_EXIST);
        }

        $roomID = RedisManager::getGameRedis()->hGet(GameRedisConfig::Hash_privateDeskInfo . '|' . $deskID, 'roomID');

        $insertID = FriendModel::getInstance()->addFriendNotify($friendID, EnumConfig::E_FriendNotifyType['INVITE_PLAYGAME'], $userID, $deskID, $passwd);
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $friendID, 'friendNotifyList', 1);
        CenterNotify::sendRedSport($friendID, EnumConfig::E_RedSpotType['FRIEND']); // 小红点
        CenterNotify::friendNotify($friendID, $insertID, EnumConfig::E_FriendNotifyType['INVITE_PLAYGAME'], $userID, $roomID, $passwd);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
}
