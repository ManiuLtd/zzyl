<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use config\GameRedisConfig;
use config\RedisConfig;
use helper\LogHelper;
use manager\RedisManager;
use model\AppModel;
use model\ClubModel;
use model\FriendModel;
use notify\CenterNotify;
use manager\DBManager;
use config\MysqlConfig;

/**
 * 俱乐部业务
 * Class ClubAction
 */
class ClubAction extends AppAction
{
    const CLUB_NAME_LEN = 24;
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
     * 俱乐部列表
     * @param $params
     */
    public function friendsGroupList($params)
    {
        //$t1 = microtime(true);
        //AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, []);
        $userID = (int)$params['userID'];
        $result = ClubModel::getInstance()->getFriendsGroupList($userID);
        LogHelper::printDebug($result);
        /*$t2 = microtime(true);
        echo '耗时'.round($t2-$t1,3).'秒<br>';
        exit;*/
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部通知列表
     * @param $params
     */
    public function friendsGroupNotifyList($params)
    {
        $userID = (int)$params['userID'];
        $result = ClubModel::getInstance()->getFriendsGroupNotifyList($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部成员列表
     * @param $params
     */
    public function friendsGroupMemberList($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $result = ClubModel::getInstance()->getFriendsGroupMemberList($friendsGroupID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部牌桌列表
     * @param $params
     */
    public function friendsGroupDeskList($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $deskType = (int)$params['deskType']; // 0牌桌 1VIP
        $floor = (int)$params['floor'];
        $userID = (int)$params['userID'];

        //查询出创建俱乐部的总开关
        $clubinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_club_config, ['switch'], "Id = 1");
        if($clubinfo['switch'] == 2){  //创建俱乐部的开关是关闭的
            //成为代理才能创建俱乐部
            $arrayKeyValue = ['on_trial_day','is_franchisee','register_time'];
            $where = "userID = {$userID}";
            $memberinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $arrayKeyValue, $where);
            if (empty($memberinfo['is_franchisee'])){ //不是加盟商
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "成为加盟商才能玩俱乐部,请联系客服");
            }
        }

        $result = [];
        if ($deskType == 0) {
            $result = ClubModel::getInstance()->getFriendsGroupOpenDeskList($friendsGroupID, $floor);
        } elseif ($deskType == 1) {
            $result = ClubModel::getInstance()->getFriendsGroupVIPRoomList($friendsGroupID, $floor);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部战绩列表
     * @param $params
     */
    public function friendsGroupRecordList($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $firstTime = (int)$params['firstTime'];
        $endTime = (int)$params['endTime'];
        $roomType = (int)$params['roomType'];
        $result = ClubModel::getInstance()->getFriendsGroupRecordList($friendsGroupID, $firstTime, $endTime, $roomType);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部成员战绩统计列表
     * @param $params
     */
    public function friendsGroupMemberRecordTotalList($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $firstTime = (int)$params['firstTime'];
        $endTime = (int)$params['endTime'];
        $result = ClubModel::getInstance()->getFriendsGroupMemberRecordTotalList($friendsGroupID, $firstTime, $endTime);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 俱乐部消耗统计
     * @param $params
     */
    public function friendsGroupCostTotal($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $firstTime = (int)$params['firstTime'];
        $endTime = (int)$params['endTime'];
        $result = ClubModel::getInstance()->getfriendsGroupCostTotal($friendsGroupID, $firstTime, $endTime);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 创建俱乐部
     * @param $params
     */
    public function createFriendsGroup($params)
    {
        $name = $params['name'];
        $userID = (int)$params['userID'];

        //查询出创建俱乐部的总开关
        $clubinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_club_config, ['switch'], "Id = 1");
        if($clubinfo['switch'] == 2){  //创建俱乐部的开关是关闭的
            //成为代理才能创建俱乐部
            $arrayKeyValue = ['on_trial_day','is_franchisee','register_time'];
            $where = "userID = {$userID}";
            $memberinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $arrayKeyValue, $where);
            if (empty($memberinfo['is_franchisee'])){ //不是加盟商
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "成为加盟商才能创建俱乐部,请联系客服");
            }
        }

        /*if(empty($memberinfo)) AppModel::returnJson(ErrorConfig::ERROR_CODE, "成为加盟商才能创建俱乐部,请联系客服");
        if (!$memberinfo['is_franchisee']){ //判断是否是代理
            //判断是否在试用期内
            if(time() >= ($memberinfo['register_time'] + $memberinfo['on_trial_day'] * 86400)){
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "试用创建俱乐部时间已过,成为加盟商才能创建俱乐部,请联系客服");
            }
        }*/
        //获取配置
        $config = ClubModel::getInstance()->getOtherConfig();
        //检测玩家拥有的俱乐部数量
        $totalCount = ClubModel::getInstance()->getFriendsGroupCount($userID);
        if ($totalCount >= (int)$config['groupJoinCount']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "最多能拥有{$config['groupJoinCount']}个俱乐部");
        }
        //检测玩家创建的俱乐部数量
        $createCount = ClubModel::getInstance()->getCreateFriendsGroupCount($userID);
        if ($createCount >= (int)$config['groupCreateCount']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "最多能创建{$config['groupCreateCount']}个俱乐部");
        }
        //校验俱乐部名字
        if (empty($name)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_CLUBNAME_CANT_NOT_EMPTY);
        }
        //截取俱乐部名字长度
        if (strlen($name) > self::CLUB_NAME_LEN) {
            $name = mb_substr($name, 0, self::CLUB_NAME_LEN - 1);
        }
        //检查名字是否重复
        $exists = ClubModel::getInstance()->isFriendGroupNameExists($name);
        if ($exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::THE_NAME_OF_THE_CLUB_HAS_ALREADY_EXITED);
        }

        // 创建成功 写reids
        $friendsGroupID = ClubModel::getInstance()->createFriendsGroup($userID, $name);

        //推送俱乐部增加
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID, $userID);
        CenterNotify::friendsGroup($userID, $friendsGroup);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 申请加入俱乐部
     * @param $params
     */
    public function joinFriendsGroup($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];

        //获取配置
        $config = ClubModel::getInstance()->getOtherConfig();

        //检测玩家拥有的俱乐部数量
        $totalCount = ClubModel::getInstance()->getFriendsGroupCount($userID);
        if ($totalCount >= (int)$config['groupJoinCount']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "最多能拥有{$config['groupJoinCount']}个俱乐部");
        }
        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //是否已经在俱乐部中
        $exists = ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $userID);
        if ($exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_YOU_ARE_ALREADY_A_MEMBER_OF_THE_CLUB);
        }
        //群主是否已经收到请求
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        $masterID = $friendsGroup['masterID'];
        $exists = ClubModel::getInstance()->isInTargetNotifyList($masterID, EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN'], $friendsGroupID, $userID);
        if ($exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_REQUEST_HAS_BEEN_SUBMITTED);
        }

        //增加一个俱乐部通知
        $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN'], $friendsGroupID, $userID);
        //推送通知给管理员(包括群主)
        $managerIDList = ClubModel::getInstance()->getFriendsGroupManagerIDList($friendsGroupID);
        //加入俱乐部通知
        $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
        foreach ($managerIDList as $managerUserID) {
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $managerUserID, 'FGNotifyList', 1);
            //推送俱乐部通知小红点变化
            CenterNotify::sendRedSport($managerUserID, EnumConfig::E_RedSpotType['FG']);

            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $managerUserID, time(), $notifyID);
            //推送俱乐部通知
            CenterNotify::friendsGroupNotify($managerUserID, $friendsGroupNotify);
        }

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 处理俱乐部消息
     * @param $params
     */
    public function handleFriendsGroupNotify($params)
    {
        $userID = (int)$params['userID'];
        $notifyID = (int)$params['notifyID'];
        $handleType = (int)$params['handleType']; // 0 拒绝 1 同意

        //通知是否存在或已经被处理过
        $exists = ClubModel::getInstance()->isFriendsGroupNotifyExists($notifyID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_MESSAGE_DOES_NOT_EXIST);
        }

        $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
        $friendsGroupID = $friendsGroupNotify['targetFriendsGroupID'];
        //根据通知类型做相应处理
        switch ($friendsGroupNotify['type']) {
            case EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN']: // xxx申请加入群xxx
                $tarUserID = $friendsGroupNotify['param1'];
                if ($handleType == EnumConfig::E_OperateType['AGREE']) {
                    //加入俱乐部
                    ClubModel::getInstance()->joinFriendsGroup($friendsGroupID, $tarUserID);
                    //获取俱乐部成员信息 用于推送
                    $friendsGroupMember = ClubModel::getInstance()->getFriendsGroupMember($friendsGroupID, $tarUserID);
                    //推送俱乐部成员增加 给俱乐部所有人
                    CenterNotify::friendsGroupMemberChangeAll($friendsGroupID, EnumConfig::E_ChangeType['ADD'], $friendsGroupMember);
                    //增加处理人同意加入俱乐部通知
                    $newNotifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN_OK'], $friendsGroupID, $userID);
                } elseif ($handleType == EnumConfig::E_OperateType['REFUSE']) {
                    //增加处理人拒绝加入俱乐部通知
                    $newNotifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN_FAIL'], $friendsGroupID, $userID);
                } else {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                }
                //新的俱乐部通知 推送给申请人
                $newFriendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($newNotifyID);
                //俱乐部通知列表小红点数量加1
                RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $tarUserID, 'FGNotifyList', 1);
                //推送俱乐部通知小红点变化
                CenterNotify::sendRedSport($tarUserID, EnumConfig::E_RedSpotType['FG']);

                // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
                RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $tarUserID, time(), $newNotifyID);
                //推送俱乐部通知
                CenterNotify::friendsGroupNotify($tarUserID, $newFriendsGroupNotify);

                //删除旧的通知
                ClubModel::getInstance()->delFriendsGroupNotify($notifyID);

                //删除管理员集合的通知ID 不做推送
                $managerIDList = ClubModel::getInstance()->getFriendsGroupManagerIDList($friendsGroupID);
                foreach ($managerIDList as $managerUserID) {
                    //删除集合通知ID
                    RedisManager::getRedis()->zRem(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $managerUserID, $notifyID);
                }
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            case EnumConfig::E_FriendsGroupNotifyType['INVITE_JOIN']: // xxx邀请您加入群xxx
                $tarUserID = $friendsGroupNotify['param1'];
                if ($handleType == EnumConfig::E_OperateType['AGREE']) {
                    //同意好友邀请 自己加入俱乐部
                    ClubModel::getInstance()->joinFriendsGroup($friendsGroupNotify['targetFriendsGroupID'], $userID);
                    //获取俱乐部成员信息 用于推送
                    $friendsGroupMember = ClubModel::getInstance()->getFriendsGroupMember($friendsGroupID, $userID);
                    //推送俱乐部成员增加 给俱乐部所有人
                    CenterNotify::friendsGroupMemberChangeAll($friendsGroupID, EnumConfig::E_ChangeType['ADD'], $friendsGroupMember);
                    //增加好友同意加入俱乐部通知
                    $newNotifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['AGREE_JOIN'], $friendsGroupID, $userID);
                } elseif ($handleType == EnumConfig::E_OperateType['REFUSE']) {
                    //增加好友拒绝加入俱乐部通知
                    $newNotifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['REFUSE_JOIN'], $friendsGroupID, $userID);
                } else {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                }
                //新的俱乐部通知 推送给邀请人
                $newFriendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($newNotifyID);
                //俱乐部通知列表小红点数量加1
                RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $tarUserID, 'FGNotifyList', 1);
                //推送俱乐部通知小红点变化
                CenterNotify::sendRedSport($tarUserID, EnumConfig::E_RedSpotType['FG']);

                // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
                RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $tarUserID, time(), $newNotifyID);
                //推送俱乐部通知
                CenterNotify::friendsGroupNotify($tarUserID, $newFriendsGroupNotify);

                //删除旧的通知
                ClubModel::getInstance()->delFriendsGroupNotify($notifyID);
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            case EnumConfig::E_FriendsGroupNotifyType['DELETED']: // 您已经被移除群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['QUIT']: // xxx退出群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['DISMISS']: // xxx群已经被解散
                break;
            case EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN_OK']: // xxx同意您加入群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN_FAIL']: // xxxx拒绝您加入群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['ECV_INVITE_JOIN']: // xxx邀请xxx加入群xxx
                if ($handleType == EnumConfig::E_OperateType['AGREE']) {
                    //管理员同意该邀请
                    //通知被邀请人
                    $newNotifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['INVITE_JOIN'], $friendsGroupID, $friendsGroupNotify['param1']);
                    //新的俱乐部通知 推送被邀请人
                    $tarUserID = $friendsGroupNotify['param2'];
                    $newFriendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($newNotifyID);
                    //俱乐部通知列表小红点数量加1
                    RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $tarUserID, 'FGNotifyList', 1);
                    //推送俱乐部通知小红点变化
                    CenterNotify::sendRedSport($tarUserID, EnumConfig::E_RedSpotType['FG']);

                    // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
                    RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $tarUserID, time(), $newNotifyID);
                    //推送俱乐部通知
                    CenterNotify::friendsGroupNotify($tarUserID, $newFriendsGroupNotify);
                } elseif ($handleType == EnumConfig::E_OperateType['REFUSE']) {
                    //管理员拒绝该邀请 不做推送给邀请人
                } else {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                }

                //删除旧的通知
                ClubModel::getInstance()->delFriendsGroupNotify($notifyID);
                //删除管理员集合的通知ID 不做推送
                $managerIDList = ClubModel::getInstance()->getFriendsGroupManagerIDList($friendsGroupID);
                foreach ($managerIDList as $managerUserID) {
                    //删除集合通知ID
                    RedisManager::getRedis()->zRem(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $managerUserID, $notifyID);
                }
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            case EnumConfig::E_FriendsGroupNotifyType['REFUSE_JOIN']: // xxx拒绝加入群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['AGREE_JOIN']: // xxx同意加入群xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['TRANSFER']: // 玩家xxx将俱乐部xxx转让给了玩家xxx
                break;
            case EnumConfig::E_FriendsGroupNotifyType['AUTH']: // 您已成为俱乐部XXX，ID（XXX）的管理员
                break;
            default:
                break;
        }
        AppModel::returnJson(ErrorConfig::ERROR_CODE, "通知类型为{$friendsGroupNotify['type']}不需要处理");
    }

    /**
     * 删除俱乐部通知
     * @param $params
     */
    public function delFriendsGroupNotify($params)
    {
        $userID = (int)$params['userID'];
        $notifyID = (int)$params['notifyID'];

        //通知是否存在或已经被处理过
        $exists = ClubModel::getInstance()->isFriendsGroupNotifyExists($notifyID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_MESSAGE_DOES_NOT_EXIST);
        }

        //删除集合通知ID
        RedisManager::getRedis()->zRem(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $userID, $notifyID);

        //删除旧的通知
        ClubModel::getInstance()->delFriendsGroupNotify($notifyID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 删除俱乐部小红点
     * @param $params
     */
    public function delFriendsGroupRedSpot($params)
    {
        $userID = (int)$params['userID'];
        $redSpotType = (int)$params['redSpotType'];

        if ($redSpotType == 1) {
            //通知列表
            RedisManager::getGameRedis()->hMset(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, ['FGNotifyList' => 0]);
        } elseif ($redSpotType == 2) {
            //开房消息 暂无
        } elseif ($redSpotType == 3) {
            //战绩消息 暂无
        } elseif ($redSpotType == 4) {
            //牌桌消息
        } elseif ($redSpotType == 5) {
            //VIP房间消息
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 删除俱乐部成员
     * @param $params
     */
    public function delFriendsGroupMember($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $userID = (int)$params['userID'];
        $tarUserID = (int)$params['tarUserID'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //身份比较 群主可以删管理员和普通成员 管理员可以删除普通成员
        $result = ClubModel::getInstance()->compareFriendsGroupMemberStatusWeight($friendsGroupID, $userID, $tarUserID);
        if (!$result) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您不能删除身份比自己高的俱乐部成员");
        }
        //权限检测
        $hasPower = ClubModel::getInstance()->isFriendsGroupMamberHasFuncPower($friendsGroupID, EnumConfig::E_FriendsGroupPowerType['DEL'], $userID);
        if (!$hasPower) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您没有删除俱乐部成员的权限");
        }
        //是否已经删除了
        $exists = ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $tarUserID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "该俱乐部成员已经被删除");
        }

        //增加被移除群通知
        $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['DELETED'], $friendsGroupID, $userID);
        // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $tarUserID, time(), $notifyID);

        //获取俱乐部成员信息 用于推送
        $friendsGroupMember = ClubModel::getInstance()->getFriendsGroupMember($friendsGroupID, $tarUserID);
        //删除该成员
        ClubModel::getInstance()->delFriendsGroupMember($friendsGroupID, $tarUserID);

        //推送俱乐部删除消息
        CenterNotify::friendsGroupDismiss($tarUserID, $friendsGroupID);
        //推送俱乐部成员删除 给俱乐部所有人
        CenterNotify::friendsGroupMemberChangeAll($friendsGroupID, EnumConfig::E_ChangeType['DEL'], $friendsGroupMember);
        //获取俱乐部通知
        $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
        //推送俱乐部通知
        CenterNotify::friendsGroupNotify($tarUserID, $friendsGroupNotify);
        //俱乐部通知列表小红点数量加1
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $tarUserID, 'FGNotifyList', 1);
        //推送俱乐部通知小红点变化
        CenterNotify::sendRedSport($tarUserID, EnumConfig::E_RedSpotType['FG']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 退出俱乐部
     * @param $params
     */
    public function quitFriendsGroup($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $userID = (int)$params['userID'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //是否俱乐部成员
        $exists = ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $userID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_YOU_ARE_NOT_IN_THE_CLUB);
        }
        //获取俱乐部成员信息 用于推送
        $friendsGroupMember = ClubModel::getInstance()->getFriendsGroupMember($friendsGroupID, $userID);
        //删除该成员
        ClubModel::getInstance()->delFriendsGroupMember($friendsGroupID, $userID);
        //推送俱乐部删除消息
        CenterNotify::friendsGroupDismiss($userID, $friendsGroupID);
        //推送俱乐部成员删除 给俱乐部所有人
        CenterNotify::friendsGroupMemberChangeAll($friendsGroupID, EnumConfig::E_ChangeType['DEL'], $friendsGroupMember);
        //退出俱乐部通知只推给管理员
        $managerIDList = ClubModel::getInstance()->getFriendsGroupManagerIDList($friendsGroupID);
        foreach ($managerIDList as $managerUserID) {
            //退出群通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['QUIT'], $friendsGroupID, $userID);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $managerUserID, time(), $notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $managerUserID, 'FGNotifyList', 1);
            //推送消息给在线成员
            if (ClubModel::getInstance()->getUserOnlineStatus($managerUserID) == 1) {
                //获取俱乐部通知
                $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
                //推送俱乐部通知
                CenterNotify::friendsGroupNotify($managerUserID, $friendsGroupNotify);
                //推送小红点
                CenterNotify::sendRedSport($managerUserID, EnumConfig::E_RedSpotType['FG']);
            }
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 解散俱乐部
     * @param $params
     */
    public function dismissFriendsGroup($params)
    {
        $friendsGroupID = (int)$params['friendsGroupID'];
        $userID = (int)$params['userID'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //是否群主 只有群主才能解散
        if ($friendsGroup['masterID'] != $userID) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "群主才能解散俱乐部");
        }
        //获取配置
        $config = ClubModel::getInstance()->getOtherConfig();
        //解散需要时间XX天
        $needDay = $config['dismissFriendsGroupNeedDay'];
        $needDay = empty($needDay) ? 1 : (int)$needDay;
        //创建到现在的时间
        $passTime = time() - $friendsGroup['createTime'];
        //过去的时间是否大于需要的时间
        if ($passTime < $needDay * 24 * 60 * 60) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "创建俱乐部{$needDay}天后才能解散");
        }
        //已开牌桌数量
        $deskCount = ClubModel::getInstance()->getFriendsGroupOpenDeskCount($friendsGroupID);
        //是否有未解散的俱乐部牌桌
        if ($deskCount > 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "俱乐部牌桌全部解散后才能解散");
        }
        //已开VIP房间数量
        $VIPRoomCount = ClubModel::getInstance()->getFriendsGroupVIPRoomCount($friendsGroupID);
        //是否有未解散的俱乐部VIP房间
        if ($VIPRoomCount > 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "俱乐部VIP房间全部解散后才能解散");
        }

        // 俱乐部所有的成员ID set
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        foreach ($friendsGroupToUserSet as $memberUserID) {
            //解散俱乐部通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['DISMISS'], $friendsGroupID, $userID);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $memberUserID, time(), $notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $memberUserID, 'FGNotifyList', 1);

            //推送消息给在线成员
            if (ClubModel::getInstance()->getUserOnlineStatus($memberUserID) == 1) {
                //推送俱乐部删除消息
                CenterNotify::friendsGroupDismiss($memberUserID, $friendsGroupID);
                //获取俱乐部通知
                $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
                //推送俱乐部通知
                CenterNotify::friendsGroupNotify($friendsGroupID, $friendsGroupNotify);
                //推送小红点变化
                CenterNotify::sendRedSport($memberUserID, EnumConfig::E_RedSpotType['FG']);
            }
        }

        //解散俱乐部
        ClubModel::getInstance()->dismissFriendsGroup($friendsGroupID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 俱乐部改名
     * @param $params
     */
    public function changeFriendsGroupName($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $newName = $params['newName'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //权限检测
        $hasPower = ClubModel::getInstance()->isFriendsGroupMamberHasFuncPower($friendsGroupID, EnumConfig::E_FriendsGroupPowerType['SET'], $userID);
        if (!$hasPower) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您没有更改俱乐部名字的权限");
        }
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //每天只能改名1次 总共可以改名3次
        $config = ClubModel::getInstance()->getOtherConfig();
        $configDayAlterNameCount = $config['groupEveAlterNameCount'];
        $configAllAlterNameCount = $config['groupAllAlterNameCount'];
        $configDayAlterNameCount = (int)$configDayAlterNameCount;
        $configAllAlterNameCount = (int)$configAllAlterNameCount;

        $allAlterNameCount = $friendsGroup['allAlterNameCount'];
        //修改名字总次数是否已经超过配置的总次数了
        if ($allAlterNameCount >= $configAllAlterNameCount) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "总共可以修改俱乐部名字{$configAllAlterNameCount}次");
        }

        $alterNameCount = ClubModel::getInstance()->getChangeFriendsGroupCount($friendsGroupID);
        //今天修改的次数是否超过配置
        if ($alterNameCount >= $configDayAlterNameCount) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "每天可以修改俱乐部名字{$configDayAlterNameCount}次");
        }

        //校验俱乐部名字
        if (empty($newName)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_CLUBNAME_CANT_NOT_EMPTY);
        }
        //截取俱乐部名字长度
        if (strlen($newName) > self::CLUB_NAME_LEN) {
            $newName = mb_substr($newName, 0, self::CLUB_NAME_LEN - 1);
        }

        //新的名字和旧的名字是否一样
        if ($friendsGroup['name'] == $newName) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "请输入不一样的俱乐部名字");
        }
        //检查名字是否重复
        $exists = ClubModel::getInstance()->isFriendGroupNameExists($newName);
        if ($exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::THE_NAME_OF_THE_CLUB_HAS_ALREADY_EXITED);
        }
        //改变俱乐部名字
        ClubModel::getInstance()->changeFriendsGroupName($friendsGroupID, $newName, $alterNameCount + 1, $allAlterNameCount + 1);
        //推送名字改变
        CenterNotify::friendsGroupNameChangeAll($friendsGroupID, $newName);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 更改俱乐部公告
     * @param $params
     */
    public function changeFriendsGroupNotice($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $notice = $params['notice'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //权限检测
        $hasPower = ClubModel::getInstance()->isFriendsGroupMamberHasFuncPower($friendsGroupID, EnumConfig::E_FriendsGroupPowerType['SET'], $userID);
        if (!$hasPower) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您没有修改俱乐部公告的权限");
        }
        //改变俱乐部公告
        ClubModel::getInstance()->changeFriendsGroupNotice($friendsGroupID, $notice);
        //推送公告改变
        CenterNotify::friendsGroupNoticeChangeAll($friendsGroupID, $notice);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 更改俱乐部微信(签名)
     * @param $params
     */
    public function changeFriendsGroupWechat($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $wechat = $params['wechat'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //权限检测
        $hasPower = ClubModel::getInstance()->isFriendsGroupMamberHasFuncPower($friendsGroupID, EnumConfig::E_FriendsGroupPowerType['SET'], $userID);
        if (!$hasPower) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您没有修改俱乐部签名的权限");
        }
        //改变俱乐部签名(签名)
        ClubModel::getInstance()->changeFriendsGroupWechat($friendsGroupID, $wechat);
        //推送微信(签名)改变
        CenterNotify::friendsGroupWechatChangeAll($friendsGroupID, $wechat);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 更改俱乐部成员身份
     * @param $params
     */
    public function changeFriendsGroupMemberStatus($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $tarUserID = (int)$params['tarUserID'];
        $status = (int)$params['status'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //是否群主 只有群主才能操作
        if ($friendsGroup['masterID'] != $userID) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "群主才能设置和撤销管理员");
        }
        //目标用户是否存在
        $exists = ClubModel::getInstance()->isUserExists($tarUserID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }

        //status是否正确
        if ($status != EnumConfig::E_FriendsGroupMemberStatus['MANAGER'] && $status != EnumConfig::E_FriendsGroupMemberStatus['NORMAL']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "只能设置或撤销管理员");
        }
        $tarStatus = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $tarUserID);
        //检查新旧身份是否相等
        if ($tarStatus == $status) {
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, "已经是管理员或已经撤销管理员了");
        }

        //管理员数量是否满员
        $managerCount = ClubModel::getInstance()->getFriendsGroupManagerCount($friendsGroupID);
        $otherConfig = ClubModel::getInstance()->getOtherConfig();
        if ($status == EnumConfig::E_FriendsGroupMemberStatus['MANAGER'] && $managerCount >= $otherConfig['groupTransferCount']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "最多设置个{$otherConfig['groupTransferCount']}管理员");
        }

        //改变目标玩家的身份
        ClubModel::getInstance()->changeFriendsGroupMemberStatus($friendsGroupID, $tarUserID, $status);

        //推送给俱乐部所有人 目标玩家身份改变
        $statusChangeInfoList = [];
        $newStatus = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $tarUserID);
        $newPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $tarUserID);
        $statusChangeInfo = array(
            'userID' => $tarUserID,
            'status' => $newStatus,
            'power' => $newPower,
        );
        $statusChangeInfoList[] = $statusChangeInfo;
        CenterNotify::friendsGroupMemberStatusChangeAll($friendsGroupID, $statusChangeInfoList);

        //如果是设置为管理 需要通知目标用户成为管理员
        if ($status == EnumConfig::E_FriendsGroupMemberStatus['MANAGER']) {
            //设置为管理员通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['AUTH'], $friendsGroupID, $userID);
            //获取俱乐部通知
            $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $tarUserID, time(), $notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $tarUserID, 'FGNotifyList', 1);
            //推送俱乐部通知
            CenterNotify::friendsGroupNotify($tarUserID, $friendsGroupNotify);
            //推送小红点变化
            CenterNotify::sendRedSport($tarUserID, EnumConfig::E_RedSpotType['FG']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 更改俱乐部成员权限
     * @param $params
     */
    public function changeFriendsGroupMemberPower($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $tarUserID = (int)$params['tarUserID'];
        $power = (int)$params['power'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //是否群主 只有群主才能操作
        if ($friendsGroup['masterID'] != $userID) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "群主才能设置管理员权限");
        }
        //目标用户是否存在
        $exists = ClubModel::getInstance()->isUserExists($tarUserID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }
        //目标用户是否管理员
        $tarStatus = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $tarUserID);
        if ($tarStatus != EnumConfig::E_FriendsGroupMemberStatus['MANAGER']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "只能给管理员设置权限");
        }
        //权限值是否正确
        if ($power < EnumConfig::E_FriendsGroupPowerType['NO'] || $power > EnumConfig::E_FriendsGroupPowerType['ALL']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "不能设置错误的权限值{$power}");
        }
        $tarPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $tarUserID);
        //检查新旧权限是否相等
        if ($tarPower == $power) {
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, "设置的权限和管理员的权限相等");
        }
        //改变目标玩家的权限
        ClubModel::getInstance()->changeFriendsGroupMemberPower($friendsGroupID, $tarUserID, $power);
        //推送权限变化
        $newPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $tarUserID);
        CenterNotify::friendsGroupMemberPowerChangeAll($friendsGroupID, $tarUserID, $newPower);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 转让群主
     * @param $params
     */
    public function transferFriendsGroupMaster($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $tarUserID = (int)$params['tarUserID'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //是否群主 只有群主才能转让
        if ($friendsGroup['masterID'] != $userID) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "群主才能转让俱乐部");
        }
        //目标用户是否存在
        $exists = ClubModel::getInstance()->isUserExists($tarUserID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }
        //转让的目标是否为代理
//		$isAgent = ClubModel::getInstance()->isAgent($tarUserID);
//		if (!$isAgent) {
//			CommonModel::returnJson(ErrorConfig::ERROR_CODE, "新的群主必须是代理");
//		}
        //已开牌桌数量
        $deskCount = ClubModel::getInstance()->getFriendsGroupOpenDeskCount($friendsGroupID);
        //是否有未解散的俱乐部牌桌
        if ($deskCount > 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "俱乐部牌桌全部解散后才能转让");
        }
        //已开VIP房间数量
        $VIPRoomCount = ClubModel::getInstance()->getFriendsGroupVIPRoomCount($friendsGroupID);
        //是否有未解散的俱乐部VIP房间
        if ($VIPRoomCount > 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "俱乐部VIP房间全部解散后才能转让");
        }

        //转让俱乐部
        $result = ClubModel::getInstance()->transferFriendsGroupMaster($friendsGroupID, $userID, $tarUserID);
        if (empty($result)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "转让俱乐部失败");
        }
        //推送给俱乐部所有人 目标玩家身份改变
        $statusChangeInfoList = [];
        $statusChangeUserIDList = [$userID, $tarUserID];
        foreach ($statusChangeUserIDList as $statusChangeUserID) {
            $newStatus = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $statusChangeUserID);
            $newPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $statusChangeUserID);
            $statusChangeInfo = array(
                'userID' => $statusChangeUserID,
                'status' => $newStatus,
                'power' => $newPower,
            );
            $statusChangeInfoList[] = $statusChangeInfo;
        }
        CenterNotify::friendsGroupMemberStatusChangeAll($friendsGroupID, $statusChangeInfoList);

        // 俱乐部所有的成员ID set
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        foreach ($friendsGroupToUserSet as $memberUserID) {
            //转让俱乐部通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['TRANSFER'], $friendsGroupID, $userID, $tarUserID);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $memberUserID, time(), $notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $memberUserID, 'FGNotifyList', 1);
            if (ClubModel::getInstance()->getUserOnlineStatus($memberUserID) == 1) {
                //获取俱乐部通知
                $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
                CenterNotify::friendsGroupNotify($memberUserID, $friendsGroupNotify);
            }
        }
        //推送小红点变化
        CenterNotify::sendRedSportAll($memberUserID, EnumConfig::E_RedSpotType['FG']);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 充值或者回收火币
     * @param $params
     */
    public function changeFriendsGroupMemberFireCoin($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $tarUserID = (int)$params['tarUserID'];
        $changeFireCoin = (int)$params['changeFireCoin'];

        //验证充值是否超过每个人所能够拥有金币的最大金额
        $redisKey = RedisManager::makeKey(RedisConfig::Hash_friendsGroupToUser, $friendsGroupID, $tarUserID);
        $coin = RedisManager::getRedis()->hIncrBy($redisKey, 'carryFireCoin', 0);
        $sumcoin = $changeFireCoin/100 + $coin/100;
        if($sumcoin > 19999998){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_money);
        }
        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //权限检测
        $hasPower = ClubModel::getInstance()->isFriendsGroupMamberHasFuncPower($friendsGroupID, EnumConfig::E_FriendsGroupPowerType['FIRE_COIN'], $userID);
        if (!$hasPower) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "您没有兑换或充值" . EnumConfig::E_ResourceTypeNames[EnumConfig::E_ResourceType['FIRECOIN']] . "的权限");
        }
        //目标用户是否存在
        $exists = ClubModel::getInstance()->isUserExists($tarUserID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }
        //充值或者回收0
        if ($changeFireCoin == 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "充值或者回收" . EnumConfig::E_ResourceTypeNames[EnumConfig::E_ResourceType['FIRECOIN']] . "不能为0");
        }
        //是否能回收
        $tarFireCoin = ClubModel::getInstance()->getFriendsGroupMemberCarryFireCoin($friendsGroupID, $tarUserID);
        if ($tarFireCoin + $changeFireCoin < 0) {
            $usermaney = $tarFireCoin/100;
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "回收" . EnumConfig::E_ResourceTypeNames[EnumConfig::E_ResourceType['FIRECOIN']] . "不能超过{$usermaney}");
        }

        $result = ClubModel::getInstance()->changeUserResource($tarUserID, EnumConfig::E_ResourceType['FIRECOIN'], $changeFireCoin, EnumConfig::E_ResourceChangeReason['CLUB_RECHARGE'], 0, $friendsGroupID);
        if (!$result){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "充值或者回收" . EnumConfig::E_ResourceTypeNames[EnumConfig::E_ResourceType['FIRECOIN']] . "失败");
        }
        //充值或者回收火币
        ClubModel::getInstance()->addFriendsGroupFireCoinOperationLog($friendsGroupID, $tarUserID, $changeFireCoin, $userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 邀请好友加入俱乐部
     * @param $params
     */
    public function inviteFriendJoinFriendsGroup($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $friendID = (int)$params['friendID'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //目标用户是否存在
        $exists = ClubModel::getInstance()->isUserExists($friendID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TARGETUSER_DO_NOT_EXIST);
        }
        //是否我的好友
        /*$isFriend = FriendModel::getInstance()->checkUserFriend($userID, $friendID);
        if (!$isFriend) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邀请的用户不是你的好友");
        }*/
        //是否已经在俱乐部中
        $exists = ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $friendID);
        if ($exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "好友已经是该俱乐部成员");
        }
        //俱乐部信息
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        //我的身份
        //1、普通成员邀请需要管理员同意 然后被邀请玩家同意
        //2、管理员邀请 被邀请玩家同意即可
        $status = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $userID);
        if ($status == EnumConfig::E_FriendsGroupMemberStatus['NORMAL']) {
            //群主是否已经收到请求 只检测群主
            $masterID = $friendsGroup['masterID'];
            $exists = ClubModel::getInstance()->isInTargetNotifyList($masterID, EnumConfig::E_FriendsGroupNotifyType['ECV_INVITE_JOIN'], $friendsGroupID, $userID, $friendID);
            if ($exists) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_REQUEST_HAS_BEEN_SUBMITTED);
            }
            //增加一个俱乐部通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['ECV_INVITE_JOIN'], $friendsGroupID, $userID, $friendID);
            //推送通知给管理员(包括群主)
            $managerIDList = ClubModel::getInstance()->getFriendsGroupManagerIDList($friendsGroupID);
            //加入俱乐部通知
            $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
            foreach ($managerIDList as $managerUserID) {
                //俱乐部通知列表小红点数量加1
                RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $managerUserID, 'FGNotifyList', 1);
                //推送俱乐部通知小红点变化
                CenterNotify::sendRedSport($managerUserID, EnumConfig::E_RedSpotType['FG']);

                // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
                RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $managerUserID, time(), $notifyID);
                //推送俱乐部通知
                CenterNotify::friendsGroupNotify($managerUserID, $friendsGroupNotify);
            }
        } else {
            //被邀请人是否已经收到通知
            $exists = ClubModel::getInstance()->isInTargetNotifyList($friendID, EnumConfig::E_FriendsGroupNotifyType['INVITE_JOIN'], $friendsGroupID, $userID);
            if ($exists) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_REQUEST_HAS_BEEN_SUBMITTED);
            }
            //增加一个俱乐部通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['INVITE_JOIN'], $friendsGroupID, $userID);
            //加入俱乐部通知
            $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $friendID, 'FGNotifyList', 1);
            //推送俱乐部通知小红点变化
            CenterNotify::sendRedSport($friendID, EnumConfig::E_RedSpotType['FG']);

            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $friendID, time(), $notifyID);
            //推送俱乐部通知
            CenterNotify::friendsGroupNotify($friendID, $friendsGroupNotify);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 删除俱乐部牌桌
     * @param $params
     */
    public function delFriendsGroupDesk($params)
    {
        $userID = (int)$params['userID'];
        $friendsGroupID = (int)$params['friendsGroupID'];
        $deskID = (int)$params['deskID'];
        $floor = (int)$params['floor'];

        //俱乐部是否存在
        $exists = ClubModel::getInstance()->isFriendsGroupExists($friendsGroupID);
        if (!$exists) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_THE_CLUB_DOES_NOT_EXIST);
        }
        //牌桌是否存在
        $deskInfo = ClubModel::getInstance()->getFriendsGroupDeskInfo($friendsGroupID, $deskID, $floor);
        if (empty($deskInfo)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "牌桌不存在或者已被删除");
        }
        //解散俱乐部房间
        CenterNotify::dismissFriendsGroupDesk($userID, $deskInfo['deskIdx'], $deskInfo['roomID']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 俱乐部推荐
     * @param $params
     */
    public function friendsGroupRecommendList($params)
    {
        $userID = (int)$params['userID'];

        $friendsGroupRecommendList = [];
        $friendsGroupList = ClubModel::getInstance()->getFriendsGroupListByPeopleCountDesc(10);
        foreach ($friendsGroupList as $friendsGroup) {
            //加入俱乐部状态
            $joinStatus = EnumConfig::E_FriendsGroupJoinStatus['NOT'];
            $friendsGroupID = $friendsGroup['friendsGroupID'];
            $masterID = $friendsGroup['masterID'];
            $exists = ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $userID);
            if ($exists) {
                $joinStatus = EnumConfig::E_FriendsGroupJoinStatus['IN'];
            } else {
                $exists = ClubModel::getInstance()->isInTargetNotifyList($masterID, EnumConfig::E_FriendsGroupNotifyType['REQ_JOIN'], $friendsGroupID, $userID);
                if ($exists) {
                    $joinStatus = EnumConfig::E_FriendsGroupJoinStatus['APPLY'];
                }
            }
            $friendsGroupRecommend = array(
                'friendsGroupID' => $friendsGroupID,
                'joinStatus' => $joinStatus,
                'name' => $friendsGroup['name'],
                'peopleCount' => $friendsGroup['peopleCount'],
                'masterID' => $masterID,
            );
            $friendsGroupRecommendList[] = $friendsGroupRecommend;
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $friendsGroupRecommendList);
    }

    /**
     * 进入俱乐部
     */
    public function enterPublicGroup($params) {
        $userID = (int)$params['userID'];
        $status = (int)$params['status'];
        //推送小红点变化
//        CenterNotify::sendRedSportAll($userID, EnumConfig::E_RedSpotType['FG']);
        //发送消息给中心服
        CenterNotify::enterOrLeaveCenter($userID, $status);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, []);
    }
}
