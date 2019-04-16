<?php
namespace action;
use config\EnumConfig;
use config\ErrorConfig;
use config\GeneralConfig;
use config\MysqlConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;
use model\AgentModel;
use model\AppModel;
use model\EmailModel;
use model\ShareModel;
use model\UserModel;
use notify\CenterNotify;
use logic\MailLogic;
use logic\UserLogic;

/**
 * 玩家业务
 * Class UserAction
 */
class UserAction extends AppAction
{
    protected $whileAction = ['getSumUserOnline'];
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
     * 刷新资源
     * @param $param
     */
    public function refreshResource($param)
    {
        $userID = $param['userID'];
        $needData = ['jewels', 'money', 'bankMoney'];
        $userInfo = UserModel::getInstance()->getUserInfo($userID, $needData);
        FunctionHelper::arrayValueToInt($userInfo);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $userInfo);
    }

    /**
     * 获取用户信息
     * @param $param
     */
    public function getUserInfo($param)
    {
        $userID = $param['userID'];

        $needData = ['name', 'headURL', 'sex', 'totalGameCount', 'winCount', 'Lng', 'Lat', 'address', 'jewels', 'money', 'logonIP', 'phone'];
        $userInfo = UserModel::getInstance()->getUserInfo($userID, $needData);
        $intKeyArray = ['sex', 'totalGameCount', 'winCount', 'jewels', 'money'];
        FunctionHelper::arrayValueToInt($userInfo, $intKeyArray);
        $userInfo['onlineStatus'] = UserModel::getInstance()->getUserOnlineStatus($userID);
        $userInfo['longitude'] = $userInfo['Lng'] == false ? '' : $userInfo['Lng'];
        $userInfo['latitude'] = $userInfo['Lat'] == false ? '' : $userInfo['Lat'];
        $userInfo['address'] = $userInfo['address'] == false ? '' : $userInfo['address'];
        $userInfo['gameCount'] = $userInfo['totalGameCount'];
        LogHelper::printDebug($userInfo);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $userInfo);
    }

    /**
     * 保存用户数据
     * @param $param
     */
    public function saveUserAddress($param)
    {
        $userID = $param['userID'];

        $arr = [
            'Lng' => $param['longitude'],
            'Lat' => $param['latitude'],
            'address' => $param['address'],
        ];

        $arr['address'] = iconv('utf-8', 'gb2312', $arr['address']);

        UserModel::getInstance()->updateUserInfo($userID, $arr);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 微信用户登录
     * @param $params
     */
    public function WXUserLogin($params)
    {
        $userID = (int)$params['userID']; // 122005
        $unionID = $params['unionID']; // 微信用户唯一ID // 2344
        $time = date('YmdHis');

        $shareCode = ShareModel::getInstance()->getShareCode($unionID);

        if (empty($shareCode)) {
            //不存在被邀请的记录 增加用户id和微信id的关系
            $shareCode = array(
                "userid" => $userID,
                "unionid" => $unionID,
                "invite_userid" => 0,
                "status" => EnumConfig::E_ShareCodeRewardStatus['NOT'],
                "time" => time(),
            );
            LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
            ShareModel::getInstance()->addShareCode($shareCode);
        } else {
            //被邀请过，第一次登陆app，更新用户新信息
            $shareCode['userid'] = $userID;
//            var_dump($shareCode['invite_userid']);
            if ($shareCode['invite_userid'] == 0 || null == $shareCode['invite_userid']) {
                //视为未被邀请
                $shareCode['status'] = EnumConfig::E_ShareCodeRewardStatus['NOT'];
            } else {
                //视为被邀请
                $shareCode['status'] = EnumConfig::E_ShareCodeRewardStatus['NONE'];
            }
            LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
            ShareModel::getInstance()->updateShareCode($unionID, $shareCode);
        }
        //发送奖励
//        var_dump($shareCode['status']);
        if ($shareCode['status'] == EnumConfig::E_ShareCodeRewardStatus['NONE']) {
            LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
            //获取邀请人信息
            $invite_userInfo = UserModel::getInstance()->getUserInfo($shareCode['invite_userid'], ['name']);
            //获取用户信息
            $userInfo = UserModel::getInstance()->getUserInfo($userID, ['name']);
            //获得配置信息
            $gameConfig = UserModel::getInstance()->getGameConfig();
            //获取代理信息
            $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($shareCode['invite_userid']);
            $shareCode['status'] = EnumConfig::E_ShareCodeRewardStatus['SEND'];
            ShareModel::getInstance()->updateShareCode($unionID, $shareCode);

//            var_dump($agentMember);
            if (empty($agentMember)) {
                LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                //邀请人不是代理
                $invite_type = EnumConfig::E_InviteEnterType['USER'];
                //玩家邀请玩家 绑定的玩家赠送的奖励
                $money = (int)$gameConfig['user_invitationUser_SendMoney'];
                $diamonds = (int)$gameConfig['user_invitationUser_SendDiamonds'];
            } else {
                LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                //邀请人是代理
                $invite_type = EnumConfig::E_InviteEnterType['AGENT'];
                //进入游戏奖励
                $money = (int)$gameConfig['bindAgent_sendUser_money'];
                $diamonds = (int)$gameConfig['bindAgent_sendUser_diamonds'];
            }

            //邀请记录
            $resInvitation = ShareModel::getInstance()->getCodeInvitation($userID, $invite_type);

            if (empty($resInvitation)) {
                $codeInvitation = array(
                    "userid" => $userID,
                    "money" => $money,
                    "invite_userid" => $shareCode['invite_userid'],
                    "type" => $invite_type,
                    "diamonds" => $diamonds,
                    "addtime" => time(),
                );

                $addResult = ShareModel::getInstance()->addCodeInvitation($codeInvitation);
                LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                /*------------------------------------奖励----------------------------------------*/
//                $this->sendRewards('首次进入游戏奖励', "{$invite_userInfo['name']}邀请您一起玩游戏" ,$userID, $money, $diamonds);
                $res = MailLogic::getInstance()->sendRewards('首次进入游戏奖励', "{$invite_userInfo['name']}邀请您一起玩游戏" ,$userID, $money, $diamonds);

            }
            $commissionRate = 95;//$agentMember['commission_rate'] - 5;
            $canBind = $commissionRate >= 15;
//            var_dump($agentMember);
            if (empty($agentMember) || 0 == $agentMember['commission_rate']) {
                LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                //邀请的玩家获得奖励(邀请人非代理)
                $userBind_sendMoney = (int)$gameConfig['userBind_sendMoney'];
                $userBind_sendDiamonds = (int)$gameConfig['userBind_sendDiamonds'];
                /*------------------------------------奖励----------------------------------------*/
//                $this->sendRewards('邀请新用户进入游戏奖励', "您邀请{$userInfo['name']}进入游戏", $shareCode['invite_userid'], $userBind_sendMoney, $userBind_sendDiamonds);
                $res = MailLogic::getInstance()->sendRewards('邀请新用户进入游戏奖励', "您邀请{$userInfo['name']}进入游戏", $shareCode['invite_userid'], $userBind_sendMoney, $userBind_sendDiamonds);
            } else {
                LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                //绑定代理的邀请码（邀请人是代理）
                $addData = [];
                $addData['userID'] = $userID;
                $addData['agentID'] = $agentMember['agentid'];
                $addData['agentname'] = $invite_userInfo['name'];
                $addData['username'] = $agentMember['username'];
                //查看是否绑定
                $agent_bind = AgentModel::getInstance()->getAgentBindByUserID($userID);
//                var_dump($agent_bind);
                if (!empty($agent_bind)) {
                    LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                    AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT.'=1');
                }

                //获得我的代理信息
                $my_agent = AgentModel::getInstance()->getAgentMemberByUserID($userID);
                if (!empty($my_agent)) {
                    LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                    //如果被邀请人是代理
                    if ($my_agent['superior_agentid']) {
                        LogHelper::printDebug($time . 'wechat_debug' . __LINE__);

                        //如果被邀请人存在上级
                        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT.'=2');
                    }
                    $allAgentID = AgentModel::getInstance()->getDownAgentID($my_agent['agentid']);
                    if (in_array($addData['agentID'], $allAgentID)) {
                        LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                        //如果邀请人是被邀请人的下级，不能绑定
                        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT.'=3');
                    }
                }

                $addData['bind_time'] = time();
                $addResult = AgentModel::getInstance()->addBindAgent($addData);
//                var_dump($addResult);
                if (!empty($addResult)) {
                    //绑定成功
                    LogHelper::printDebug($time . 'wechat_debug' . __LINE__);

                    //更新我的代理信息的绑定信息，如果被邀请人本身是代理，更新绑定关系
                    if (!empty($my_agent)) {
                        LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                        if($addData['agentID'] != $my_agent['agentid']){
                            $my_agent['superior_agentid'] = $addData['agentID'];
                            $my_agent['superior_username'] = $addData['username'];
                        }

                        /*if(!empty($my_agent['superior_agentid'])){
                            //修改该代理上级的下级代理统计
                            $this->updateAgent($my_agent['superior_agentid'], $userID);
                        }*/
                        AgentModel::getInstance()->updateAgentMember($my_agent['agentid'], $my_agent);
                    } else {
                        //被邀请人不是代理，玩家变代理 ???
                        AgentModel::getInstance()->setUser2Agent(['username' => '15696' . $userID, 'userid' => $userID, 'commission_rate' => $commissionRate], $shareCode['invite_userid']);
                    }

                    $bindAgentReward = AgentModel::getInstance()->getBindAgentReward($userID);

                    if (empty($bindAgentReward)) {
                        LogHelper::printDebug($time . 'wechat_debug' . __LINE__);
                        $bind_agentid_send_jewels = (int)$gameConfig['bind_agentid_send_jewels'];
                        $bind_agentid_send_money = (int)$gameConfig['bind_agentid_send_money'];

                        //添加绑定代理奖励记录
                        AgentModel::getInstance()->addBindAgentReward($userID, $bind_agentid_send_money, $bind_agentid_send_jewels);
                        /*------------------------------------奖励----------------------------------------*/
//                        $this->sendRewards('绑定邀请码奖励', "绑定邀请码{$agentMember['agentID']}成功", $userID, $bind_agentid_send_money, $bind_agentid_send_jewels);
                        $res = MailLogic::getInstance()->sendRewards('绑定邀请码奖励', "绑定邀请码{$agentMember['agentID']}成功", $userID, $bind_agentid_send_money, $bind_agentid_send_jewels);
                    }
                }
            }
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT.'=4');
    }


    /*
     * 修改redis代理关系信息
     * $paranm int $superior_agentid 上级代理id
     * $param int $id   添加的代理id
     * */
    public function updateAgent($superior_agentid, $id){
        //获取该用户的所有下级代理
        $xj_id_arr = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $id);

        //获取该代理的所有上级代理
        $sj_agentid_Arr = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_superiorAgentSet . '|' . $superior_agentid);

        //给所有的下级代理添加上级代理
        if(empty($xj_id_arr)){
            if(empty($sj_agentid_Arr)){
                RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, [$superior_agentid]);
            }else{
                array_push($sj_agentid_Arr, $superior_agentid);
                RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, $sj_agentid_Arr);
            }

        }else{
            array_push($xj_id_arr, $id);
            foreach ($xj_id_arr as $k => $v){
                //查询出该下级代理的上级代理
                $res = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_superiorAgentSet . '|' . $v);
                if(empty($res) && empty($sj_agentid_Arr)){
                    RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $v, [$superior_agentid]);
                }elseif (empty($res) && !empty($sj_agentid_Arr)){

                }elseif (!empty($res) && empty($sj_agentid_Arr)){

                }elseif (!empty($res) && !empty($sj_agentid_Arr)){

                }
            }

            array_push($xj_agent_id, $id);
            RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $superior_agentid, $xj_agent_id);
        }

        $res = RedisManager::getRedis()->exists(RedisConfig::Hash_superiorAgentSet . '|' . $superior_agentid);
        if(!empty($res)){ //存在上级代理,就得给自己的上级代理添加该代理
            //获取该上级代理的所有上级代理
            $sj_agent_id = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_superiorAgentSet . '|' . $superior_agentid);
            foreach ($sj_agent_id as $k => $v){
                $xj_agent_ids = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $v);
                array_push($xj_agent_ids, $id);
                RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $v, $xj_agent_ids);
            }
            array_push($sj_agent_id, $superior_agentid);
            //给添加的代理成员添加上级代理信息
            RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, $sj_agent_id);
        }else{
            //给添加的代理成员添加上级代理信息
            RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, [$superior_agentid]);
        }
    }


    /**
     * 发送奖励
     * @param $title 邮件标题
     * @param $subTitle 副标题
     * @param $receiveUserID 接收邮件用户id
     * @param $diamonds 奖励砖石数
     * @param $money 奖励金币数
     */
    protected function sendRewards($title, $subTitle, $receiveUserID, $money, $diamonds) {

//        $title = "首次进入游戏奖励";
        $content = $subTitle . "。\n您获得奖励：{$diamonds}钻石,{$money}金币";
        $goodsArray = [
            EnumConfig::E_ResourceType['JEWELS'] => $diamonds,
            EnumConfig::E_ResourceType['MONEY'] => $money,
        ];
        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['INVITE_ENTER'], $title, $content, $goodsList);
        // 添加邮件
        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $receiveUserID);
        // 小红点
        CenterNotify::sendRedSport($receiveUserID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
    }

    public function getSumUserOnline() {
        $sum = UserModel::getInstance()->getSumUserOnline();
        $sum = $sum > GeneralConfig::BOTTOM_ONLINE_USER ? $this->getFadeOnlineUserNum($sum) : $this->getFadeOnlineUserNum();
        // $sum = random_int(100, 150);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $sum);
    }

    /**
     * @return false|int
     * @throws Exception
     */
    protected function getFadeOnlineUserNum($baseSum = GeneralConfig::BOTTOM_ONLINE_USER) {
        $onlineNum = RedisManager::getRedis()->get(RedisConfig::String_fadeOnlineUserNum);
        if (!$onlineNum) {
            $onlineNum = rand($baseSum, $baseSum + 40);
            LogHelper::printDebug('The Number Of Online User:' . $onlineNum);
            RedisManager::getRedis()->setex(RedisConfig::String_fadeOnlineUserNum, 30, $onlineNum);
        }
        return (int)$onlineNum;
    }

    public function getUserHeadUrlList($params) {
        $host = FunctionHelper::getWebProtocal() . $_SERVER['HTTP_HOST'];
        $data = ['http://ht.szhuomei.com//head//man//1729.jpg', 'http://ht.szhuomei.com//head//man//1730.jpg', 'http://ht.szhuomei.com//head//man//1733.jpg', 'http://ht.szhuomei.com//head//man//1735.jpg', 'http://ht.szhuomei.com//head//man//1737.jpg', 'http://ht.szhuomei.com//head//man//1740.jpg', 'http://ht.szhuomei.com//head//man//1741.jpg', 'http://ht.szhuomei.com//head//man//1749.jpg', 'http://ht.szhuomei.com//head//man//1750.jpg', 'http://ht.szhuomei.com//head//man//1753.jpg', 'http://ht.szhuomei.com//head//man//1761.jpg', 'http://ht.szhuomei.com//head//man//1771.jpg', 'http://ht.szhuomei.com//head//man//1779.jpg', 'http://ht.szhuomei.com//head//man//1780.jpg', 'http://ht.szhuomei.com//head//man//1781.jpg'];
        $data1 = [
            $host . '/Public/home/img/avatar/1.png',
            $host . '/Public/home/img/avatar/2.png',
            $host . '/Public/home/img/avatar/3.png',
            $host . '/Public/home/img/avatar/4.png',
            $host . '/Public/home/img/avatar/5.png',
            $host . '/Public/home/img/avatar/6.png',
            $host . '/Public/home/img/avatar/7.png',
            $host . '/Public/home/img/avatar/8.png',
            $host . '/Public/home/img/avatar/9.png',
            $host . '/Public/home/img/avatar/10.png',
        ];
        $data = array_merge($data, $data1);
        shuffle($data);
        $data = array_slice($data, 0, 12);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }

    public function resetPassword($params) {
        $phone = $params['phone'];
        $code = $params['code'];
        $password = $params['password'];
        $userID = $params['userID'];
        $oldPassword = $params['oldPassword'];
        $where = AgentModel::getInstance()->makeWhere(['userID' => $userID]);
        //验证旧密码
//        $res = DBManager::getMysql()->execSql('select phone, passwd from ' . MysqlConfig::Table_userinfo . $where);
        if ($password == $oldPassword) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '新旧密码不能相同');
        }
        $userInfo = UserModel::getInstance()->getUserInfo($userID, ['userID', 'phone', 'phonePasswd', 'bankPasswd']);
        if (empty($userInfo['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '该用户不存在');
        }
        if (empty($userInfo['phone'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '该用户未绑定手机号');
        }
        if ($oldPassword != $userInfo['phonePasswd']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '旧密码不正确', $userInfo['phonePasswd']);
        }
        //新密码不能与银行密码相同
        LogHelper::printLog('PASS_WORD', '参数444'.json_encode($userInfo));
        LogHelper::printLog('PASS_WORD', '参数444'.json_encode($params));
        LogHelper::printLog('PASS_WORD', '参数444'.json_encode($password));
        if($password == md5($userInfo['bankpasswd'])){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '新密码不能与银行密码相同');
        }
        /*
         * $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['bankpasswd'], "userID = {$userID}");
        if($password == md5($resinfo['bankpasswd'])){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '新密码不能与银行密码相同');
        }
         * */
        $data = ['phonePasswd' => $password];
        $res = UserModel::getInstance()->updateUserInfo($userID, $data);
        if (!$res) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '修改失败');
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, '修改成功');

//        $res = UserLogic::getInstance()->resetPassword();
    }

    public function bindUpAgent($params) {
        $userID = $params['userID'];
        $inviteUserID = $params['invite_userID'];
        $res = UserLogic::getInstance()->bindAgent($userID, $inviteUserID);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, $res['msg']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, '绑定成功');
    }


//                $title = "邀请进入游戏奖励";
//                $content = "您邀请{$userInfo['name']}进入游戏。\n您获得奖励：{$userBind_sendDiamonds}钻石,{$userBind_sendMoney}金币";
//                $goodsArray = [
//                    EnumConfig::E_ResourceType['JEWELS'] => $userBind_sendDiamonds,
//                    EnumConfig::E_ResourceType['MONEY'] => $userBind_sendMoney,
//                ];
//                $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
//                $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['INVITE_ENTER'], $title, $content, $goodsList);
//                // 添加邮件
//                EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $shareCode['invite_userid']);
//                // 小红点
//                CenterNotify::sendRedSport($shareCode['invite_userid'], EnumConfig::E_RedSpotType['MAIL']); // 小红点


//                        $title = "绑定邀请码奖励";
//                        $content = "绑定邀请码{$agentMember['agentID']}成功，\n获得奖励：{$bind_agentid_send_jewels}钻石,{$bind_agentid_send_money}金币";
//                        $goodsArray = [
//                            EnumConfig::E_ResourceType['JEWELS'] => $bind_agentid_send_jewels,
//                            EnumConfig::E_ResourceType['MONEY'] => $bind_agentid_send_money,
//                        ];
//                        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
//                        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BIND_AGENT'], $title, $content, $goodsList);
//                        // 添加邮件
//                        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
//                        // 小红点
//                        CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
}
