<?php

namespace Admin\Controller;
use config\EnumConfig;
use config\ErrorConfig;
use config\GameRedisConfig;
use config\GeneralConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use logic\NotifyLogic;
use manager\RedisManager;
use model\AgentModel;
use model\EmailModel;
use model\UserModel;
use notify\CenterNotify;

//用户中心
class UserController extends AdminController
{
    //每次充值最多金额
    const MAX_USER_RECHARGE = 100000000;

    //用户管理
    public function user_list()
    {
        $type = I('type');
        $search = I('search');
        $is_super = I('is_super', false);
        $is_online = I('is_online', false);

        //只查玩家
        $where['U.isVirtual'] = EnumConfig::E_GameUserType['PLAYER'];

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['U.userID'] = $search;
                    break;
                case 2:
                    $where['U.name'] = ['like', "%{$search}%"];
                    break;
            }
        } else {
            $search = '';
        }

        // 查超端用户
        if ($is_super) {
            //权限最大值 或运算
            $status_length = 0;
            foreach (EnumConfig::E_UserStatusType as $s_key => $s_value) {
                $status_length |= $s_value;
            }
            //超端权限可能值数组
            $super_status_array = [];
            for ($status = 0; $status <= $status_length; $status++) {
                //与运算
                if (($status & EnumConfig::E_UserStatusType['SUPER']) == EnumConfig::E_UserStatusType['SUPER']) {
                    $super_status_array[] = $status;
                }
            }
            $where['U.status'] = ['in', $super_status_array];
            unset($where['U.registerTime']);
        }

        //查在线玩家
        if ($is_online) {
            $where['U.IsOnline'] = EnumConfig::E_UserOnlineStatus['ON'];
            unset($where['U.registerTime']);
        }

        $count = M()->table('userInfo as U')->where($where)->count();
        $page = new \Think\Page($count, 20);
        $dbUserList = M()
            ->table('userInfo as U')
            ->join('left join web_pay_orders as wpo on wpo.userID=U.userID')
            ->join('left join user_cash_application as uca on uca.userID=U.userID')
            ->join('left join web_admin_action as waaa on waaa.userID=U.userID and waaa.actionType = 1')
            ->join('left join web_admin_action as wbbb on wbbb.userID=U.userID and wbbb.actionType = 2')
            ->where($where)
            ->field('U.userID, U.phone, wpo.id as wpoid, waaa.id as waaaid, uca.Id as ucaid, wbbb.id as wbbbid')
            ->group('U.userID')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        //var_dump($dbUserList);exit;
        $userInfoList = [];
        foreach ($dbUserList as $key => $dbUser) {
            $userID = $dbUser['userid'];
            $user = UserModel::getInstance()->getUserInfo($userID);
            /*if($userID == 122010){
                var_dump($user);exit;
            }*/
            $user = array_merge($user, $dbUser);
//            dump($user);
//            $user['userid'] = $userID;
            $user['user_type_name'] = '普通用户';
            $user['bind_agent_userid'] = '无';
            $user['bind_agent_id'] = '无';

            //判断我的类型
            $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($userID);
            if ($user['isVirtual'] == EnumConfig::E_GameUserType['ROBOT']) {
                $user['user_type_name'] = '机器人';
            } elseif (!empty($agentMember)) {
                $user['user_type_name'] = EnumConfig::E_AgentLevelName[$agentMember['agent_level']];
            }

            //获取绑定代理信息
            $agentBind = AgentModel::getInstance()->getAgentBindByUserID($userID);
            if (!empty($agentBind) && !empty($agentBind['agentID'])) {
                $user['bind_agent_id'] = $agentBind['agentID'];
                $user['bind_time'] = date('Y-m-d H:i:s', $agentBind['bind_time']);
                $bind_agent = AgentModel::getInstance()->getAgentMemberByAgentID($user['bind_agent_id']);
                if (!empty($bind_agent)) {
                    $user['bind_agent_userid'] = $bind_agent['userid'];
                }
            }

            $where = ['userID' => $userID, 'type' => EnumConfig::E_UserLoginType['LOBBY_IN']];
            //上次登录时间
            $user['last_login_time'] = M()->table('statistics_logonandlogout')->where($where)->order('time desc')->getField('time');
            //今天在线时长 分钟
            $user['today_online_time'] = sprintf('%.2f', $this->today_online_time($userID) / 3600) . '小时';
            //累计充值金额
            $user['recharge_money'] = sprintf('%.2f', UserModel::getInstance()->getWebUserInfo($userID, 'rechargeMoney') / 100);
            //性别名字
            $user['sex_name'] = EnumConfig::E_UserSexTypeName[$user['sex']];
            $userInfoList[] = $user;
        }
//        var_export($userInfoList);
        //金币倍数处理
        FunctionHelper::moneyInArrayOutput($userInfoList, GeneralConfig::USERINFO_MONEY);
//        var_export($userInfoList);
//        foreach ($userInfoList as $k => &$v) {
//            // var_export($v);
//            $v['money'] = FunctionHelper::MoneyOutput((int)$v['money']);
//        }
        //var_dump($userInfoList);exit;
        $this->assign('_page', $page->show());
        $this->assign('_data', $userInfoList);
        $this->assign('search', $search);
        $this->assign('type', $type);
        $this->display();
    }

    // 在线列表
    public function online_list()
    {
        $userIDList = UserModel::getInstance()->getAllOnlineUserIDList();
        foreach ($userIDList as $userID) {
            $user = UserModel::getInstance()->getUserInfo($userID);
            $user['userid'] = $userID;
            $user['user_type_name'] = '普通用户';
            $user['bind_agent_userid'] = '无';
            $user['bind_agent_id'] = '无';

            //判断我的类型
            $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($userID);
            if ($user['isVirtual'] == EnumConfig::E_GameUserType['ROBOT']) {
                $user['user_type_name'] = '机器人';
            } elseif (!empty($agentMember)) {
                $user['user_type_name'] = EnumConfig::E_AgentLevelName[$agentMember['agent_level']];
            }

            //获取绑定代理信息
            $agentBind = AgentModel::getInstance()->getAgentBindByUserID($userID);
            if (!empty($agentBind)) {
                $user['bind_agent_id'] = $agentBind['agentID'];
                $bind_agent = AgentModel::getInstance()->getAgentMemberByAgentID($user['bind_agent_id']);
                if (!empty($bind_agent)) {
                    $user['bind_agent_userid'] = $bind_agent['userid'];
                    $user['bind_agent_username'] = UserModel::getInstance()->getUserInfo($bind_agent['userid'], 'name');
                }
            }

            $where = ['userID' => $userID, 'type' => EnumConfig::E_UserLoginType['LOBBY_IN']];
            //上次登录时间
            $user['last_login_time'] = M()->table('statistics_logonandlogout')->where($where)->order('time desc')->getField('time');
            //今天在线时长 分钟
            $user['today_online_time'] = sprintf('%.2f', $this->today_online_time($userID) / 3600) . '小时';
            //累计充值金额
            $user['recharge_money'] = UserModel::getInstance()->getWebUserInfo($userID, 'rechargeMoney');
            //累计登录大厅次数
            $user['login_lobby_count'] = UserModel::getInstance()->getWebUserInfo($userID, 'loginLobbyCount');
            //性别名字
            $user['sex_name'] = EnumConfig::E_UserSexTypeName[$user['sex']];
            $userInfoList[] = $user;
        }
        $this->assign('_data', $userInfoList);
        $this->display();
    }

    //用户详情页
    public function user_info()
    {
        $userID = I('userid');
        if (empty($userID)) {
            $this->error('userid 不能为空');
        }
        //玩家信息
        $user = UserModel::getInstance()->getUserInfo($userID);
        $user['userid'] = $userID;
        //统计信息
        $webUserData = UserModel::getInstance()->getWebUserInfo($userID);
        //判断我的类型
        $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($userID);

        $user['user_type_name'] = '普通用户';
        $user['bind_agent_userid'] = '无';
        $user['agent_id'] = '无';
        $user['bind_agent_id'] = '无';
        $user['agent_count'] = '无';
        $user['agent_balance'] = '无';
        $user['pos_all_money'] = '无';
        $user['last_pos_money'] = '无';
        if ($user['isVirtual'] == EnumConfig::E_GameUserType['ROBOT']) {
            $user['user_type_name'] = '机器人';
        } elseif (!empty($agentMember)) {
            $user['agent_id'] = $agentMember['agentid'];
            $user['user_type_name'] = EnumConfig::E_AgentLevelName[$agentMember['agent_level']];;
            //代理下级代理数
            $agent_count = M('agent_member')->where(['superior_username' => $agentMember['username']])->count();
            $user['agent_count'] = $agent_count;
            //代理余额
            $user['agent_balance'] = sprintf('%.2f', $agentMember['balance'] / 100);
            //提现
            $user['pos_all_money'] = sprintf('%.2f', $agentMember['history_pos_money']);
            $user['last_pos_money'] = sprintf('%.2f', M('agent_apply_pos')->where(['agentid' => $agentMember['agentid']])->getField('apply_amount'));
        }
        //获取绑定代理信息
        $agentBind = AgentModel::getInstance()->getAgentBindByUserID($userID);
        if (!empty($agentBind)) {
            $user['bind_agent_id'] = $agentBind['agentID'];
            $bind_agent = AgentModel::getInstance()->getAgentMemberByAgentID($user['bind_agent_id']);
            if (!empty($bind_agent)) {
                $user['bind_agent_userid'] = $bind_agent['userid'];
            }
        }

        //上次登录时间
        $where = ['userID' => $userID, 'type' => EnumConfig::E_UserLoginType['LOBBY_IN']];
        $user['last_login_time'] = M()->table('statistics_logonandlogout')->where($where)->order('time desc')->getField('time');

        $user['last_recharge_money'] = '无';
        $user['last_recharge_time'] = '无';
        $user['recharge_money'] = '无';
        //最近成交订单
        $order = M('pay_orders')->where(['userID' => $userID, 'status' => EnumConfig::E_OrderStatus['GIVE'], 'consumeType' => EnumConfig::E_ResourceType['RMB']])->order('create_time desc')->find();
        if (!empty($order)) {
            //最近充值数
            $user['last_recharge_money'] = sprintf('%.2f', $order['consumenum'] / 100);
            //充值时间
            $user['last_recharge_time'] = $order['create_time'];
            //累计充值金额
            $user['recharge_money'] = sprintf('%.2f', UserModel::getInstance()->getWebUserInfo($userID, 'rechargeMoney') / 100);
        }

        $turnOverMoneySource = ['signGetMoney', 'shareGetMoney', 'turntableGetMoney', 'giveCostMoney', 'giveGetMoney', 'supportGetMoney', 'friendRewardGetMoney'];

        foreach ($webUserData as $k => &$v) {
            if (in_array($k, $turnOverMoneySource)) {
                $v = FunctionHelper::moneyOutput($v);
            }
        }
        $turnOverJewelsSource = ['shareGetJewels', 'signGetJewels', 'turntableGetJewels', 'giveCostJewels', 'giveGetJewels', 'hornCostJewels', ];
        foreach ($turnOverJewelsSource as $k => &$v) {
            if (in_array($k, $turnOverMoneySource)) {
                $v = FunctionHelper::moneyOutput($v, EnumConfig::E_ResourceType['JEWELS']);
            }
        }

        #分享次数
        $user['share_count'] = $webUserData['shareCount'];
        $user['share_get_jewels'] = $webUserData['shareGetJewels'];
        $user['share_get_money'] = $webUserData['shareGetMoney'];
        #登录次数
        $user['login_count'] = $webUserData['loginLobbyCount'];
        #反馈次数
        $user['feedback_count'] = $webUserData['feedbackCount'];

        #签到次数
        $user['sign_count'] = $webUserData['signCount'];
        $user['sign_get_jewels'] = $webUserData['signGetJewels'];
        $user['sign_get_money'] = $webUserData['signGetMoney'];

        #抽奖次数
        $user['turntable_count'] = $webUserData['turntableCount'];
        $user['turntable_get_jewels'] = $webUserData['turntableGetJewels'];
        $user['turntable_get_money'] = $webUserData['turntableGetMoney'];

        // 转赠统计
        $user['give_cost_money'] = $webUserData['giveCostMoney']; //累计转赠金币
        $user['give_cost_jewels'] = $webUserData['giveCostJewels']; //累计转赠房卡
        $user['give_get_money'] = $webUserData['giveGetMoney']; //累计接受金币
        $user['give_get_jewels'] = $webUserData['giveGetJewels']; //累计接受钻石
        //领取补助
        $user['support_count'] = $webUserData['supportCount']; //累计领取补助次数
        $user['support_get_money'] = $webUserData['supportGetMoney']; //累计领取补助金币数
        //世界广播
        $user['horn_count'] = $webUserData['hornCount'];
        $user['horn_cost_jewels'] = $webUserData['hornCostJewels'];

        #打赏好友
        $user['friend_reward_count'] = $webUserData['friendRewardCount'];
        $user['get_friend_reward_count'] = $webUserData['getFriendRewardCount'];
        $user['friend_reward_get_money'] = $webUserData['friendRewardGetMoney'];


        //累计对局数
        $user['all_count'] = $user['totalGameCount'];
        //累计胜局属
        $user['win_count'] = $user['totalGameWinCount'];

        //累计在线时长
        $user['all_online_time'] = sprintf('%.2f', $user['allOnlineToTime'] / 3600) . '小时';

        //累计登录大厅次数
        $user['login_lobby_count'] = UserModel::getInstance()->getWebUserInfo($userID, 'loginLobbyCount');

        //在线状态
        $user['online_status'] = UserModel::getInstance()->getUserOnlineStatus($userID);
        $user['online_status_name'] = EnumConfig::E_UserOnlineStatusName[$user['online_status']];
        //性别名字
        $user['sex_name'] = EnumConfig::E_UserSexTypeName[$user['sex']];

        if (UID == 1) {
            $user_operation = 1;
        } else {
            $user_operation = M('admin_member')->where(['id' => UID])->getField('user_operation');
        }

        //金币倍数处理
        $user['money'] = FunctionHelper::MoneyOutput($user['money']);
        $this->assign([
            'leftBar' => \config\EnumConfig::E_AdminPageHideType['LEFT_BAR'],
        ]);
        $this->assign('user', $user);
        $this->assign('user_operation', $user_operation);
        $this->display();
    }

    #登入情况
    public function logon_record()
    {
        $map = [];

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        $type = I('type');
        $search = trim(I('search'));

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $map['u.userID'] = $search;
                    break;
                case 2:
                    $map['u.name'] = $search;
                    break;
                case 3:
                    $map['ip'] = $search;
                    break;
            }
        }
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $map['time'] = ['between', [$start, $stop]];
        // } else {
        //     $start = strtotime(date('Y-m-d' . ' 00:00:00'));
        //     $stop = $start + 24 * 3600 - 1;
        //     $map['time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $map['time'] = $res['data'];
        }
        $map['c.type'] = EnumConfig::E_UserLoginType['LOBBY_IN'];

        $count = M()->table('statistics_logonandlogout')
            ->alias('c')
            ->join('left join userInfo as u on u.userID=c.userID')
            ->where($map)
            ->order('time desc')
            ->count();
        $Page = new \Think\Page($count, 10);
        $list = M()->table('statistics_logonandlogout')
            ->alias('c')
            ->join('left join userInfo as u on u.userID=c.userID')
            ->where($map)
            ->field('c.*,u.name as username,u.account,u.sex')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        foreach ($list as &$user) {
            $user['sex_name'] = EnumConfig::E_UserSexTypeName[$user['sex']];
        }

        $this->assign([
            'type' => $type,
            'search' => $search,
        ]);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('list', $list);
        $this->assign('page', $Page->show());
        $this->assign('login_type_name', EnumConfig::E_UserLoginTypeName[$map['c.type']]);
        $this->display();
    }

    //游戏登陆记录
    public function game_login()
    {
        $map = [];

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        $type = I('type');
        $search = I('search');

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $map['u.userID'] = $search;
                    break;
                case 2:
                    $map['u.name'] = $search;
                    break;
                case 3:
                    $map['ip'] = $search;
                    break;
            }
        }
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $map['time'] = ['between', [$start, $stop]];
        // } else {
        //     $start = strtotime(date('Y-m-d' . ' 00:00:00'));
        //     $stop = $start + 24 * 3600 - 1;
        //     $map['time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $map['time'] = $res['data'];
        }
        $map['c.type'] = EnumConfig::E_UserLoginType['GAME_IN'];

        $count = M()->table('statistics_logonandlogout')
            ->alias('c')
            ->join('left join userInfo as u on u.userID=c.userID')
            ->where($map)
            ->order('time desc')
            ->count();
        $Page = new \Think\Page($count, 10);
        $list = M()->table('statistics_logonandlogout')
            ->alias('c')
            ->join('left join userInfo as u on u.userID=c.userID')
            ->where($map)
            ->field('c.*,u.name as username,u.account,u.sex')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($list as &$user) {
            $user['sex_name'] = EnumConfig::E_UserSexTypeName[$user['sex']];
            $user['room_name'] = M()->table('roomBaseInfo')->where(['roomID' => $user['platformtype']])->getField('name');
            $user['room_type'] = M()->table('roomBaseInfo')->where(['roomID' => $user['platformtype']])->getField('type');
            $user['room_type_name'] = EnumConfig::E_RoomTypeName[$user['room_type']];
        }

        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('list', $list);
        $this->assign('page', $Page->show());
        $this->assign('login_type_name', EnumConfig::E_UserLoginTypeName[$map['c.type']]);
        $this->display();
    }

    public function today_online_time($userID)
    {
        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;

        $today_online_time = 0;
        //查询这个用户今天的在线时长
        $where = ['userID' => $userID, 'type' => ['in', [EnumConfig::E_UserLoginType['LOBBY_IN'], EnumConfig::E_UserLoginType['LOBBY_OUT']]]];
        $where['time'] = ['between', [$start, $end]];
        $recordList = M()->table('statistics_logonandlogout')->order('time asc')->where($where)->select();
        if (empty($recordList)) {
            return $today_online_time;
        }
        foreach ($recordList as $key => $record) {
            if ($record['type'] == EnumConfig::E_UserLoginType['LOBBY_IN']) {
                $next = $recordList[$key + 1];
                if (!empty($next)) {
                    if ($next['type'] == EnumConfig::E_UserLoginType['LOBBY_OUT']) {
                        $time = $next['time'] - $record['time'];
                        $today_online_time += $time;
                    }
                } else {
                    //登录没登出
                    if ($key + 1 == count($recordList)) {
                        //并且玩家在线
                        if (UserModel::getInstance()->getUserOnlineStatus($userID) == EnumConfig::E_UserOnlineStatus['ON']) {
                            $time = time() - $record['time'];
                            $today_online_time += $time;
                        }
                    }
                }
            }
        }
        return $today_online_time;
    }

    // 解绑邀请码
    public function remove_bind()
    {
        $userID = I('userid');
        if (empty($userID)) {
            $this->error('userid 不能为空');
        }
        //查看是否绑定
        $agent_bind = AgentModel::getInstance()->getAgentBindByUserID($userID);
        if (empty($agent_bind)) {
            $this->error('未绑定邀请码');
        }

        $result = AgentModel::getInstance()->delAgentBindByUserID($userID);
        if (empty($result)) {
            $this->error('解绑邀请码失败');
        }
        operation_record(UID, '解除用户绑定');
        $this->success('解绑邀请码成功');
    }

    protected function chkEmpty() {

    }

    // 绑定邀请码
    public
    function bind_code()
    {
        if (IS_POST) {
            $userID = I('userid', 0);
            $agent_id = I('code', 0);

            if (empty($userID)) {
                $this->error('userid 不能为空');
            }
            if (empty($agent_id)) {
                $this->error('code 不能为空');
            }

            if (empty($agent_id)) {
                $this->error('请输入要绑定的邀请码');
            }

            //查看是否绑定
            $agent_bind = AgentModel::getInstance()->getAgentBindByUserID($userID);
            if (!empty($agent_bind)) {
                $this->error(ErrorConfig::ERROR_MSG_BIND_TOO);
            }
            //验证代理号和用户是否存在
            $agent_member = AgentModel::getInstance()->getAgentMemberByAgentID($agent_id);
            //代理是否存在
            if (empty($agent_member)) {
                $this->error(ErrorConfig::ERROR_MSG_INVITE_NOT);
            }
            //不能绑定自己的邀请码
            if ($agent_member['userid'] == $userID) {
                $this->error(ErrorConfig::ERROR_MSG_INVITE_OWN);
            }

            //获得我的代理信息
            $my_agent = AgentModel::getInstance()->getAgentMemberByUserID($userID);
            if (!empty($my_agent)) {
                if ($my_agent['superior_agentid']) {
                    $this->error(ErrorConfig::ERROR_MSG_BIND_TOO);
                }
                //手下 AgentModel::RECHARGE_COMMISSION_LEVEL - 1 级不能相互绑定
                $allAgentID = AgentModel::getInstance()->getDownAgentID($my_agent['agentid']);
                if (in_array($agent_id, $allAgentID)) {
                    $this->error(ErrorConfig::ERROR_CODE, "不能绑定手下代理");
                }
            }
            //添加记录
            $name = AgentModel::getInstance()->getUserInfo($userID, 'name');
            $addData = [];
            $addData['userID'] = $userID;
            $addData['agentID'] = $agent_id;
            $addData['agentname'] = $agent_member['username'];
            $addData['username'] = $name;
            $addData['bind_time'] = time();

            $addResult = AgentModel::getInstance()->addBindAgent($addData);
            if (empty($addResult)) {
                $this->error(ErrorConfig::ERROR_MSG_BIND_FAIL);
            }

            //更新我的代理信息的绑定信息
            if (!empty($my_agent)) {
                $my_agent['superior_agentid'] = $agent_id;
                $my_agent['superior_username'] = $agent_member['username'];
                AgentModel::getInstance()->updateAgentMember($my_agent['agentid'], $my_agent);
            }
            $bindAgentReward = AgentModel::getInstance()->getBindAgentReward($userID);
            if (empty($bindAgentReward)) {
                //获得礼品
                $config = AgentModel::getInstance()->getConfig();
                $bind_agentid_send_jewels = (int)$config['bind_agentid_send_jewels'];
                $bind_agentid_send_money = (int)$config['bind_agentid_send_money'];

                //添加绑定代理奖励记录
                AgentModel::getInstance()->addBindAgentReward($userID, $bind_agentid_send_money, $bind_agentid_send_jewels);

                $msgRewards = [];
                if (!empty($bind_agentid_send_jewels)) {
                    $msgRewards[] = "{$bind_agentid_send_jewels}钻石";
                }
                if (!empty($bind_agentid_send_money)) {
                    $msgRewards[] = FunctionHelper::MoneyOutput($bind_agentid_send_money) . "金币";
                }
                // 创建邮件
                $title = "绑定邀请码成功";
                $content = "绑定邀请码{$addData['agentID']}成功，\n获得奖励：" . implode(',', $msgRewards);
                $goodsArray = [
                    EnumConfig::E_ResourceType['JEWELS'] => $bind_agentid_send_jewels,
                    EnumConfig::E_ResourceType['MONEY'] => $bind_agentid_send_money,
                ];
                $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
//                $goodsArray[EnumConfig::E_RechargeType['MONEY']] = FunctionHelper::MoneyOutput($bind_agentid_send_money);
                $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BIND_AGENT'], $title, $content, $goodsList);
                // 添加邮件
                EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);

                // 小红点
                CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
            }
            $this->success('绑定成功');

        } else {

            $userid = I('userid', '');
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    public
    function getAgent($agentid)
    {
        $arr = [];
        //获取我的所有二级代理的agentid
        $data = M('agent_member')->where(['superior_agentid' => $agentid])->getField('agentid');
        //遍历获取我的所有三级代理
        foreach ($data as $k => $v) {
            $arr[] = $data[$k]['agentid'];
            $data1 = M('agent_member')->where(['superior_agentid' => $v['agentid']])->getField('agentid');
            foreach ($data1 as $key => $value) {
                $arr[] = $data1[$k]['agentid'];
            }
        }

        return $arr;
    }

    //用户充值
    public function user_recharge()
    {
        if (IS_POST) {
            $userID = (int)I('userid');
            $type = (int)I('type');
            $num = $numLog = (int)I('amount');
            if (empty($userID)) {
                $this->error('userID 不能为空');
            }
            if ($num <= 0) {
                $this->error('请输入正确的充值数量');
            }

            if ($type == EnumConfig::E_RechargeType['MONEY']) {
                $num = FunctionHelper::MoneyInput($num);
            }

            if ($num > self::MAX_USER_RECHARGE) {
                $this->error("单次充值不能超过" . self::MAX_USER_RECHARGE);
            }

            $res = UserModel::getInstance()->getUserInfo($userID, ['roomID']);
            if (isset($res['roomID']) && $res['roomID']) {
                $this->error('用户在游戏中，不能充值');
            }
            $result = UserModel::getInstance()->changeUserResource($userID, $type, $num, EnumConfig::E_ResourceChangeReason['BACK_RECHARGE']);

            if (empty($result)) {
                $this->success('充值失败');
            }

            $user = M()->table('userInfo')->where(['userID' => $userID])->find();
            $num = FunctionHelper::MoneyOutput($num);
            // '玩家' . $user['name'] . '一掷千金，充值' . $num . '金币，壕气冲天！'
            $strNotify = json_encode(['name' => $user['name'], 'money' => $num]);
            NotifyLogic::getInstance()->sendNormalNotify($strNotify);

            operation_record(UID, '给用户' . $userID . '充值' . EnumConfig::E_ResourceTypeName[$type] . $numLog);
            $user = M('admin_member')->where(['id' => UID])->find();
            $data = [
                'adminuser' => $user['username'],
                'actionType' => 1,
                'resourceType' => $type,
                'resourceNum' => $num,
                'actionTime' => time(),
                'userID' => $userID,
                'actionDesc' => '管理员' . $user['username'] . '为玩家' . $userID . '充值' . EnumConfig::E_ResourceTypeName[$type] . $num,
            ];
            M('admin_action')->add($data);
            $this->success('充值成功');
        } else {
            $userid = I('userid');
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    //用户提取
    public function user_pos()
    {
        if (IS_POST) {
            $userID = (int)I('userid');
            $type = (int)I('type');
            $num = $numLog = (float)I('amount');
            if (empty($userID)) {
                $this->error('userID 不能为空');
            }

            if ($num <= 0) {
                $this->error('请输入正确的提取数量');
            }

//            if ($type == EnumConfig::E_RechargeType['MONEY']) {
            if (in_array($type, GeneralConfig::TIMES_RESOURCE_TYPE)) {
                $num = FunctionHelper::MoneyInput($num, $type);
            }

            if ($num > self::MAX_USER_RECHARGE) {
                $this->error("单次提取不能超过" . self::MAX_USER_RECHARGE);
            }

            $res = UserModel::getInstance()->getUserInfo($userID, ['roomID']);
            if (isset($res['roomID']) && $res['roomID']) {
                $this->error('用户在游戏中，不能充值');
            }

            //获取个人信息
            $find = UserModel::getInstance()->getUserInfo($userID, ['money', 'jewels']);
            if ($type == EnumConfig::E_ResourceType['MONEY']) {
                if ($num > $find['money']) {
                    $this->error('提取金币数量不能超过玩家金币数量');
                }
            } elseif ($type == EnumConfig::E_ResourceType['JEWELS']) {
                if ($num > $find['jewels']) {
                    $this->error('提取钻石数量不能超过玩家钻石数量');
                }
            }

            $result = UserModel::getInstance()->changeUserResource($userID, $type, -$num, EnumConfig::E_ResourceChangeReason['BACK_UNRECHARGE']);

            if (empty($result)) {
                $this->success('提取失败');
            }

            operation_record(UID, '给用户' . $userID . '提取' . EnumConfig::E_ResourceTypeName[$type] . $numLog);
            $user = M('admin_member')->where(['id' => UID])->find();
            $data = [
                'adminuser' => $user['username'],
                'actionType' => 2,
                'resourceType' => $type,
                'resourceNum' => $num,
                'actionTime' => time(),
                'userID' => $userID,
                'actionDesc' => '管理员' . $user['username'] . '为玩家' . $userID . '提取' . EnumConfig::E_ResourceTypeName[$type] . $num,
            ];
            M('admin_action')->add($data);
            $this->success('提取成功');
        } else {
            $userid = I('userid');
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    //机器人管理
    public
    function vbot_list()
    {
        $type = I('type');
        $search = I('search');

        $where['U.isVirtual'] = 1;
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['registerTime'] = ['between', [$start, $stop]];
        // } else {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['registerTime'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['registerTime'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['U.userID'] = $search;
                    break;
                case 2:
                    $where['U.name'] = $search;
                    break;
                case 3:
                    $where['U.account'] = $search;
                    break;
                case 4:
                    $where['U.logonIP'] = $search;
                    break;
                case 5:
                    $where['B.agentID'] = $search;
                    break;
            }
        }
        $count = M()->table('userInfo as U')->where($where)->count();
        $page = new \Think\Page($count, 20);
        $data = M()
            ->table('userInfo as U')
            ->join('left join roomBaseInfo as R on R.roomID=U.roomID')
            ->join('left join web_agent_bind as B on B.userID=U.userID')
            ->where($where)
            ->field('B.agentid,U.userID,U.status,U.account,U.name,U.sex,U.phone,U.money,U.jewels,U.logonIP,U.winCount,R.name as roomname')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($data as $k => $v) {
            if ($data[$k]['agentid']) {
                $agent = M('agent_member')->where(['agentid' => $data[$k]['agentid']])->find();
                $data[$k]['bind_userid'] = $agent['userid'];
                $data[$k]['bind_username'] = $agent['username'];
            }
            //判断用户身份
            $agent = M('agent_member')->where(['userid' => $data[$k]['userID']])->find();
            if ($agent) {
                $data[$k]['user_type'] = $agent[$k]['agent_level'];
            } else {
                $data[$k]['user_type'] = 0;
            }
        }
        $this->assign('_page', $page->show());
        $this->assign('_data', $data);
        $this->display();
    }

    //设置超端
    public
    function set_supper_user()
    {
        $userID = I('userid');
        CenterNotify::userStatus($userID, 1, 1, 0);
        operation_record(UID, '设置用户' . $userID . '为超端');
        $this->success('设置超端成功');
    }

    //取消超端
    public
    function cancel_supper_user()
    {
        $userID = I('userid');
        CenterNotify::userStatus($userID, 0, 1, 0);
        operation_record(UID, '取消用户' . $userID . '的超端');
        $this->success('取消超端成功');
    }

    //个人充值记录
    public
    function personal_recharge_record($userid)
    {
        $where = [];
        $where['userID'] = $userid;
        $where['status'] = EnumConfig::E_OrderStatus['GIVE'];
        $where['consumeType'] = EnumConfig::E_ResourceType['RMB'];
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }
        $data = D('Data')->get_all_data('pay_orders', $where, 10, 'create_time desc');
        foreach ($data['_data'] as $k => &$v) {
            $v['consumenum'] = sprintf('%.2f', $v['consumenum'] / 100);
            $v['buy_type_name'] = EnumConfig::E_ResourceTypeName[$v['buytype']];
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //个人金币变化
    public
    function personal_money_change($userID)
    {
        $where = [];
        $where['S.userID'] = $userID;
        $count = M()->table('statistics_moneychange as S')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_moneychange as S')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.time,S.money,S.changeMoney,S.reason,R.name as rname')
            ->select();
        foreach ($res as $k => &$v) {
            $v['reason_name'] = EnumConfig::E_ResourceChangeReasonName[$v['reason']];
        }

        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人房卡变化
    public
    function personal_jewels_change($userID)
    {
        $where = [];
        $where['S.userID'] = $userID;
        $count = M()->table('statistics_jewelschange as S')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_jewelschange as S')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.time,S.jewels,S.changeJewels,S.reason,R.name as rname')
            ->select();
        foreach ($res as $k => &$v) {
            $v['reason_name'] = EnumConfig::E_ResourceChangeReasonName[$v['reason']];
        }
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人房卡对局记录
    public
    function personal_game_jewsels_record($userID)
    {
        $where['userIDList'] = ['like', "%{$userID}%"];
        $where['finishedTime'] = ['neq', 0];
        $where['passwd'] = ['neq', 'aa'];
        $passwd = M()->table('statistics_gamerecordinfo')->order('finishedtime desc')->where($where)->field('passwd,finishedtime')->select();
        $arr = [];
        foreach ($passwd as $v) {
            if (!in_array($v['passwd'], $arr)) {
                $arr[] = $v['passwd'];
            }
        }
        $map['GRI.passwd'] = array('in', implode(',', $arr));
        $map['GRI.finishedTime'] = ['neq', 0];
        $count = M()
            ->table('statistics_gamerecordinfo as GRI')
            ->where($map)
            ->count();

        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_gamerecordinfo as GRI')
            ->join('left join roomBaseInfo as RBI on GRI.roomID = RBI.roomID')
            ->join('left join gameBaseInfo as GBI on RBI.gameID = GBI.gameID')
            ->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('finishedTime desc,createTime desc')
            ->field('GRI.passwd,GRI.createTime,GRI.beginTime,GRI.finishedTime,GRI.userIDList,RBI.name as rname,GBI.name as gname')
            ->select();
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    public
    function roominfo()
    {
        $id = I('roomid', '');
        $map['passwd'] = $id;
        $count = M()
            ->table('statistics_gamerecordinfo')
            ->where($map)
            ->count();
        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_gamerecordinfo as GRI')
            ->join('left join roomBaseInfo as RBI on GRI.roomID = RBI.roomID')
            ->join('left join gameBaseInfo as GBI on RBI.gameID = GBI.gameID')
            ->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('finishedTime desc')
            ->field('GRI.passwd,GRI.deskIdx,GRI.createTime,GRI.beginTime,GRI.finishedTime,GRI.userIDList,RBI.name as rname,GBI.name as gname')
            ->select();

        foreach ($res as $k => $v) {
            if (!$res[$k]['useridlist']) {
                $res[$k]['useridlist'] = '';
            } else {
                //解析用户
                $str = substr($res[$k]['useridlist'], 0, strlen($res[$k]['useridlist']) - 1);
                $user = explode(',', $str);
                //过滤机器人
                foreach ($user as $z => $j) {
                    $u = M()->table('userInfo')->where(['userID' => (int)$user[$z]])->field('userID,name,isVirtual')->find();
                    if (0 == $u['isvirtual']) {
                        $res[$k]['user'] .= $u['name'] . '&emsp;';
                    }
                }
            }
        }

        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //房卡变化日志
    public
    function jewels_change_record()
    {
        $where = [];
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['S.userID'] = $search;
                    break;
                case 2:
                    $where['U.account'] = $search;
                    break;
                case 3:
                    $where['U.name'] = $search;
                    break;
            }
        }
        $type2 = I('type2');
        if (!$type2) {
            $where['S.reason'] = $type2;
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['S.time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['S.time'] = $res['data'];
        }
        $where['U.isVirtual'] = 0;
        $count = M()->table('statistics_jewelschange as S')->join('left join userInfo as U on U.UserID = S.UserID')->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_jewelschange as S')
            ->join('left join userInfo as U on U.userID = S.userID')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.userID,U.name,U.account,S.time,S.jewels,S.changeJewels,S.reason,R.name as rname')
            ->select();

        foreach ($res as $k => &$v) {
            $v['reason_name'] = EnumConfig::E_ResourceChangeReasonName[$v['reason']];
        }
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //向个人发送邮件
    public
    function personal_send_email()
    {
        if (IS_POST) {
            $userID = I('userid');
            $title = I('title');
            $content = I('content');
            if (empty($title) || empty($content)) {
                $this->error('标题或者内容不能为空');
            }
            //邮件附件
            $jewels_count = I('jewels_count', 0);
            $money_count = I('money_count', 0);
            if ($jewels_count < 0 || $money_count < 0) {
                $this->error('钻石和金币数不能小于0');
            }
            $goodsArray = [
                EnumConfig::E_ResourceType['JEWELS'] => $jewels_count,
                EnumConfig::E_ResourceType['MONEY'] => $money_count,
            ];
            $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
            //创建邮件
            $emailDetailInfo = EmailModel::getInstance()->createEmail(UID, EnumConfig::E_ResourceChangeReason['USER_MAIL'], $title, $content, $goodsList);
            if (empty($emailDetailInfo)) {
                $this->error('发送用户邮件失败');
            }
            operation_record(UID, "发布用户邮件，用户ID为{$userID} 标题为{$title} 内容为{$content}");
            EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
            // 小红点
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
            $this->success('发送用户邮件成功');

        } else {
            $userid = I('userid');
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    //个人对局记录
    public
    function personal_game_record($userID)
    {
        $where['GRI.userIDList'] = ['like', "%{$userID}%"];
        $count = M()->table('statistics_gamerecordinfo as GRI')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 20);
        $res = M()->table('statistics_gamerecordinfo as GRI')
            ->join('left join roomBaseInfo as RBI on GRI.roomID = RBI.roomID')
            ->join('left join gameBaseInfo as GBI on RBI.gameID = GBI.gameID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('finishedTime desc')
            ->field('GRI.passwd,GRI.createTime,GRI.beginTime,GRI.finishedTime,GRI.userIDList,RBI.name as rname,GBI.name as gname')
            ->select();

        foreach ($res as $k => $v) {
            if (!$res[$k]['useridlist']) {
                $res[$k]['useridlist'] = '';
                $res[$k]['userMoneyChange'] = "";   //  用户金币牌局输赢情况
            } else {
                //解析用户
                $str = substr($res[$k]['useridlist'], 0, strlen($res[$k]['useridlist']) - 1);
                $user = explode(',', $str);
                //过滤机器人
                foreach ($user as $z => $j) {
                    $u = M()->table('userInfo')
                        ->where(['userID' => (int)$user[$z]])
                        ->field('userID,name,isVirtual')
                        ->find();
                    if (0 == $u['isvirtual']) {
                        $res[$k]['user'] .= $u['name'] . '&emsp;';
                    }
                }

            }
        }

        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人登录记录
    public
    function personal_login_record($userID)
    {
        $where['userID'] = $userID;
        $count = M()
            ->table('statistics_logonAndLogout')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 20);
        $res = M()
            ->table('statistics_logonAndLogout')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('id desc')
            ->select();

        foreach ($res as $k => &$v) {
            $v['login_type_name'] = EnumConfig::E_UserLoginTypeName[$v['type']];
            $v['platform_type_name'] = EnumConfig::E_LoginDeviceTypeName[$v['platformtype']];
            if ($v['type'] == EnumConfig::E_UserLoginType['GAME_IN']) {
                $where['roomID'] = $v['platformtype'];
                $v['platform_type_name'] = M()->table('roomBaseInfo')
                    ->where($where)
                    ->getfield('name');
            }
        }

        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人签到记录
    public
    function personal_sign_record($userID)
    {
        $where['userID'] = $userID;
        $data = D('Data')->get_all_data('sign_record', $where, 20, 'signTime desc');
        foreach ($data['_data'] as $k => &$v) {
            $v['reward_name'] = EnumConfig::E_ResourceTypeName[$v['prizetype']];
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //个人抽奖记录
    public
    function personal_turntable_record($userID)
    {
        $where['userID'] = $userID;
        $data = D('Data')->get_all_data('turntable_record', $where, 15, 'turntableTime desc');
        foreach ($data['_data'] as $k => &$v) {
            if ($v['prizetype'] == 0) {
                $v['reward_name'] = '未中奖';
            } else {
                $v['reward_name'] = EnumConfig::E_ResourceTypeName[$v['prizetype']];
            }
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //个人分享记录
    public
    function personal_share_record($userID)
    {
        $where['userid'] = $userID;
        $data = D('Data')->get_all_data('share_record', $where, 12, 'share_time desc');
        //获取用户信息
        foreach ($data['_data'] as $k => &$v) {
            $v['share_type_name'] = EnumConfig::E_ShareTypeName[$v['share_address']];
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //个人实物兑换记录
    public
    function personal_convert_record($userID)
    {
        $where['buyType'] = 4;
        $where['userID'] = $userID;
        $data = D('Data')->get_all_data('pay_orders', $where, 12, 'handle asc,create_time desc');
        foreach ($data['_data'] as $k => $v) {
            if ($data['_data'][$k]['buyGoods'] == '房卡') {
                $data['_data'][$k]['handle'] = 1;
            }
            $user = M()->table('userInfo')->where(['userID' => $data['_data'][$k]['userid']])->find();
            $data['_data'][$k]['name'] = $user['name'];
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //个人转赠记录
    public
    function personal_given_record($userID)
    {
        $where['userID'] = $userID;
        $count = M('give_record')->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $record = M('give_record')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('time desc')->select();
        foreach ($record as $k => &$v) {
            $v['name'] = UserModel::getInstance()->getUserInfo($v['userid'], 'name');
            $v['targetname'] = UserModel::getInstance()->getUserInfo($v['targetid'], 'name');
            $v['deduction'] = $v['value'] - $v['real_value'];
            $v['reward_name'] = EnumConfig::E_ResourceTypeName[$v['type']];
            $v['total_count'] = $v['total_money_count'] + $v['total_jewels_count'];
        }
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人发送世界广播记录
    public
    function personal_radio_record($userID)
    {
        $where['userID'] = $userID;
        $count = M('horn')->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $record = M('horn')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //个人发送魔法表情记录
    public
    function personal_magic_record($userID)
    {
        $where['userID'] = $userID;
        $where['reason'] = EnumConfig::E_ResourceChangeReason['MAGIC_EXPRESS'];
        $count = M()->table('statistics_moneychange')->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $record = M()->table('statistics_moneychange')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->display();
    }

    //银行管理
    public
    function personal_bank_record($userID)
    {
        $where['userID'] = $userID;
        $order = 'time desc';
        $data = D('Data')->get_all_data('bank_record', $where, 20, $order);
        $res = $data['_data'];
        foreach ($res as $k => &$v) {
            $user = UserModel::getInstance()->getUserInfo($v['userid'], ['name']);
            $v = array_merge($v, $user);
            $v['operate_type_name'] = EnumConfig::E_BankOperateTypeName[$v['type']];
            if ($v['type'] == EnumConfig::E_BankOperateType['TRAN']) {
                //转账
                $targetUser = UserModel::getInstance()->getUserInfo($v['targetid'], ['name']);
                $v['targetname'] = $targetUser['name'];
            } else {
                $v['targetid'] = $v['userid'];
                $v['targetname'] = $v['name'];
            }
        }
        $this->assign('_data', $res);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    // 限制ip
    public
    function limit_ip()
    {
        $this->display();
    }

    // //限制玩家管理
    public
    function get_limit_ip()
    {
        $keys = GameRedisConfig::Hash_userInfo . '|*';
        $keysList = RedisManager::getGameRedis()->keys($keys);
        $userList = [];
        foreach ($keysList as $key) {
            $infoArray = explode('|', $key);
            $userID = $infoArray[1];
            $user = UserModel::getInstance()->getUserInfo($userID, ['userID', 'isVirtual', 'status', 'sealFinishTime', 'name']);
            if (!empty($user)) {
                if ($user['isVirtual'] == EnumConfig::E_GameUserType['PLAYER'] && ($user['status'] & EnumConfig::E_UserStatusType['BAN'])) {
                    $url = U("User/user_info", array("userid" => $user["userID"]));
                    $edit_url = U("User/set_user_status", array("userID" => $user["userID"]));
                    $user['info_url'] = "admin_edit('用户详情','$url','2','1200','800')";
                    $user['edit_url'] = "admin_edit('设置用户状态','$edit_url','2','1200','800')";

                    $user['time'] = '无';
                    if ($user['sealFinishTime'] > 0) {
                        $user['time'] = sprintf('%.2f', ($user['sealFinishTime'] - time()) / 3600) . ' 小时';
                    } elseif ($user['sealFinishTime'] < 0) {
                        $user['time'] = '永久封号';
                    }
                    $user['status_desc'] = '';
                    foreach (EnumConfig::E_UserStatusTypeName as $s_key => $s_value) {
                        if ($s_key != EnumConfig::E_UserStatusType['NONE'] && ($user['status'] & $s_key) == $s_key) {
                            $user['status_desc'] .= $s_value . '|';
                        }
                    }
                    $user['status_desc'] = rtrim($user['status_desc'], '|');
                    $userList[] = $user;
                }
            }
        }
        if (empty($userList)) {
            echo json_encode(['status' => 0, 'msg' => '']);
        } else {
            echo json_encode(['status' => 1, 'msg' => '获取成功', 'data' => $userList], JSON_UNESCAPED_SLASHES);
        }
    }

    // 用户超时 离开房间
    public
    function clearRoom()
    {
        $userID = I('userid');
        $result = UserModel::getInstance()->leaveRoom($userID);
        if ($result) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    //账单明细
    public
    function bill_detail()
    {
        $userid = I('userID');
        $where['userid'] = $userid;
        $data = D('Data')->get_all_data('bill_detail', $where, 15, 'make_time desc');
        foreach ($data['_data'] as $k => &$v) {
            $v['front_balance'] = sprintf('%.2f', $v['front_balance'] / 100);
            $v['handle_money'] = sprintf('%.2f', $v['handle_money'] / 100);
            $v['after_balance'] = sprintf('%.2f', $v['after_balance'] / 100);
            $v['amount'] = sprintf('%.2f', $v['amount'] / 100);
            $v['commission'] = sprintf('%.2f', $v['commission'] / 100);
            $v['under_amount'] = sprintf('%.2f', $v['under_amount'] / 100);
            $v['under_commission'] = sprintf('%.2f', $v['under_commission'] / 100);
            $v['agent_level_name'] = EnumConfig::E_AgentLevelName[$v['agent_level']];
        }
        $this->assign('userid', $userid);
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //导出数据
    public
    function execl()
    {
        $time = time();
        $filename = "代理收入明细-{$time}.csv";
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename={$filename}");
        header('Cache-Control: max-age=0');
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息
        $head = array('代理账号', '用户名', '代理等级', '申请提现金额', '操作前余额', '操作后余额', '描述', '时间', '行为者ID', '行为者昵称', '直属总额', '直属提成', '非直属总额', '非直属提成', '代理ID', '代理用户ID');
        FunctionHelper::iconvArrayEncode($head, 'gbk');

        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        $userid = I('userid');
        $where['userid'] = $userid;
        $data = M('bill_detail')->order('make_time desc')->where($where)->select();
        foreach ($data['_data'] as $k => &$v) {
            $v['front_balance'] = sprintf('%.2f', $v['front_balance'] / 100);
            $v['handle_money'] = sprintf('%.2f', $v['handle_money'] / 100);
            $v['after_balance'] = sprintf('%.2f', $v['after_balance'] / 100);
            $v['amount'] = sprintf('%.2f', $v['amount'] / 100);
            $v['commission'] = sprintf('%.2f', $v['commission'] / 100);
            $v['under_amount'] = sprintf('%.2f', $v['under_amount'] / 100);
            $v['under_commission'] = sprintf('%.2f', $v['under_commission'] / 100);
            $v['make_time'] = date("Y-m-d H:i:s", $v['make_time']);
        }
        FunctionHelper::iconvArrayEncode($data, 'gbk');

        foreach ($data as $k => $v) {
            fputcsv($fp, $v);
        }
        fclose($fp);
        exit();
    }

    //后台充值提取记录
    public
    function admin_action_record()
    {
        $where = [];
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));

        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['actionTime'] = ['between', [$start, $stop]];
        // } else {
        //     if (!$search && !$type) {
        //         $start = strtotime(date('Y-m-d', time()));
        //         $stop = $start + 24 * 3600 - 1;
        //         $where['actionTime'] = ['between', [$start, $stop]];
        //     }
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['actionTime'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['adminuser'] = $search;
                    break;
                case 2:
                    $where['userID'] = $search;
                    break;
            }
        }

        $map = $where;
        $map['actionType'] = 1;
        $recharge_money_num = M('admin_action')->where($map)->where(['resourceType' => 1])->sum('resourceNum');
        $recharge_jewels_num = M('admin_action')->where($map)->where(['resourceType' => 2])->sum('resourceNum');
        $map['actionType'] = 2;
        $pos_money_num = M('admin_action')->where($map)->where(['resourceType' => 1])->sum('resourceNum');
        $pos_jewels_num = M('admin_action')->where($map)->where(['resourceType' => 1])->sum('resourceNum');
        $data = D('Data')->get_all_data('admin_action', $where, 15, 'id desc');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->assign('recharge_money_num', $recharge_money_num);
        $this->assign('recharge_jewels_num', $recharge_jewels_num);
        $this->assign('pos_money_num', $pos_money_num);
        $this->assign('pos_jewels_num', $pos_jewels_num);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    // 代理信息
    public
    function agentinfo()
    {
        $userid = I('userID');
        $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($userid);

        //获取我的手下所有代理
        $allDownAgent = AgentModel::getInstance()->getDownAgentInfo($agentMember['agentid']);
        foreach ($allDownAgent as $k => &$v) {
            //获取会员数下级代理数
            $v['user_count'] = M('agent_bind')->where(['agentID' => $v['agentid']])->count();
            $v['agent_count'] = M('agent_member')->where(['superior_agentid' => $v['agentid']])->count();
            $v['balance'] = sprintf('%.2f', $v['balance'] / 100);
            $v['history_pos_money'] = sprintf('%.2f', $v['history_pos_money'] / 100);
        }

        $this->assign('_data', $allDownAgent);

        // 手下会员
        $search = I('user_search');
        if ('' != $search) {
            if (0 == (int)$search) {
                $where['username'] = array('like', $search . '%');
            } else {
                $where['userID'] = $search;
            }
        }

        $where['agentID'] = $agentMember['agentid'];
        $data = D('Data')->get_all_data('agent_bind', $where, 15, 'bind_time desc');
        //获取会员为我带来的收入以及他们充值的总数
        $user = $data['_data'];
        foreach ($user as $k => &$v) {
            //代理充值数
            $v['recharge'] = sprintf('%.2f', M('recharge_commission')->where(['recharge_userid' => $v['userid']])->sum('recharge_amount') / 100);
            //带来的收益
            $v['c'] = sprintf('%.2f', M('recharge_commission')->where(['recharge_userid' => $v['userid'], 'agent_userid' => $agentMember['agentid']])->sum('agent_commission') / 100);
        }
        $this->assign('_page', $data['_page']);
        $this->assign('user', $user);

        // 账单明细
        $search = I('search', '');
        if ('' != $search) {
            if (0 == (int)$search) {
                $where['make_name|username'] = array('like', $search . '%');
            } else {
                $where['make_userid'] = $search;
            }
        }

        $start = urldecode(I('start'));
        $end = urldecode(I('end'));
        if ($start && $end) {
            if ($start > $end) {
                $this->error('开始时间不能大于结束时间');
            }
            $where['make_time'] = ['between', [strtotime($start), strtotime($end)]];
        }

        $data = D('Data')->get_all_data('bill_detail', $where, 15, 'make_time desc');
        foreach ($data['_data'] as $k => &$v) {
            $v['front_balance'] = sprintf('%.2f', $v['front_balance'] / 100);
            $v['handle_money'] = sprintf('%.2f', $v['handle_money'] / 100);
            $v['after_balance'] = sprintf('%.2f', $v['after_balance'] / 100);
            $v['amount'] = sprintf('%.2f', $v['amount'] / 100);
            $v['commission'] = sprintf('%.2f', $v['commission'] / 100);
            $v['under_amount'] = sprintf('%.2f', $v['under_amount'] / 100);
            $v['under_commission'] = sprintf('%.2f', $v['under_commission'] / 100);
        }

        $this->assign('_zdpage', $data['_page']);
        $this->assign('_bill', $data['_data']);
        $this->assign('time', time());

        $this->display();
    }

    //设置用户身份
    public
    function set_user_status()
    {
        if (IS_POST) {
            $userID = I('userid');
            $type = I('type');
            $statusValue = I('statusValue');
            $moneyLimit  = I('moneyLimit');
            if ($statusValue == 0) {
                $this->error('请选择需要设置的身份');
            }
            $otherValue = I('otherValue', -1);
            if ($otherValue > 0) {
                $otherValue = $otherValue * 24 * 3600;
            }
            CenterNotify::userStatus($userID, $type, $statusValue, $otherValue, $moneyLimit);
            $this->success('设置成功');
        } else {
            //获取我的身份
            $userID = I('userID');
            $user = UserModel::getInstance()->getUserInfo($userID, ['status', 'sealFinishTime']);
            $user['time'] = '无';
            if ($user['sealFinishTime'] > 0) {
                $user['time'] = sprintf('%.2f', ($user['sealFinishTime'] - time()) / 3600) . ' 小时';
            } elseif ($user['sealFinishTime'] < 0) {
                $user['time'] = '永久封号';
            }
            $user['status_desc'] = '';
            foreach (EnumConfig::E_UserStatusTypeName as $s_key => $s_value) {
                if ($s_key != EnumConfig::E_UserStatusType['NONE'] && ($user['status'] & $s_key) == $s_key) {
                    $user['status_desc'] .= $s_value . '|';
                }
            }
            $user['status_desc'] = rtrim($user['status_desc'], '|');
            $info = RedisManager::getGameRedis()->hGetAll('especialIdentityUser|'.$userID);
            $this->assign("info",$info);
            $this->assign("user", $user);
            $this->assign('userid', $userID);
            $this->display();
        }
    }

    // 好友俱乐部
    public
    function userClub()
    {
        $where = [];
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['g.name'] = $search;
                    break;
                case 2:
                    $where['g.friendsGroupID'] = $search;
                    break;
            }
        }
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['g.createTime'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['g.createTime'] = $res['data'];
        }

        $User = M()->table('friendsGroup as g');
        $count = $User->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $list = M()
            ->table('friendsGroup as g')
            ->join('left join userInfo as u on u.userID=g.masterID')
            ->order('createTime desc')
            ->where($where)
            ->field('g.*,u.name as username')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('start', '' == $start ? $this->start : $start);
        $this->assign('stop', '' == $stop ? $this->stop : $stop);
        $this->display();
    }

    // 俱乐部成员
    public
    function clubUserList()
    {
        $map = [];

        $id = I('id');
        $map['f.friendsGroupID'] = $id;

        $search = I('search');
        $type = I('type');

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $map['u.name'] = $search;
                    break;

                case 2:
                    $map['u.userID'] = $search;
                    break;
            }
        }

        $data = D('User')->getUserList($map);
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('id', $id);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    // 俱乐部战绩导出
    public
    function csv_data()
    {
        $map = [];
        $map['friendsGroupID'] = I('friendsgroupid', 0); // 俱乐部ID

        $start = I('start', '');
        $stop = I('stop', '');

        // if ($start && $stop) {
        //     $map['time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $map['time'] = $res['data'];
        }

        $a = 1;

        $data = $this->get_table_data($map);
        foreach ($data as &$v) {
            $v['time'] = date('Y-m-d H:i:s', $v['time']);
            switch ($v['roomtype']) {
                case 1:
                    $v['roomtype'] = '积分房';
                    break;

                case 2:
                    $v['roomtype'] = '金币房';
                    break;

                case 3:
                    $v['roomtype'] = 'VIP房';
                    break;

                default:
                    $v['roomtype'] = '金币场';
                    break;
            }

            $str = '';
            unset($v['row_number']);
            $arr = explode('|', $v['userinfolist']);
            array_pop($arr);
            foreach ($arr as $vv) {
                $find = explode(',', $vv);
                $str .= '用户ID :' . $find[0] . ' ';
                $str .= '昵称 :' . M()->table('userInfo')->where(['userID' => $find[0]])->getfield('name') . ' ';
                $str .= '输赢 :' . $find[1] . "\n";
            }

            $v['userinfoList'] = $str;
        }

        $filename = '俱乐部战绩.csv';
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename='$filename'");
        header('Cache-Control: max-age=0');
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息
        $head = array('房间类型', '时间', '房主', '房间号', '游戏类型', '局数', '桌号', '输赢');
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        foreach ($data as $k => &$vv) {
            foreach ($vv as &$z) {
                $z = iconv('utf-8', 'gbk', $z);
            }
        }

        $arr = $data;

        for ($i = 0; $i <= count($arr); ++$i) {
            fputcsv($fp, $arr[$i]);
        }
    }

    // 消耗统计
    public
    function clubCount()
    {
        $map = [];
        $roomType = I('roomType');
        $start_time = I('start');
        $stop_time = I('stop');
        $friendsgroupid = I('friendsgroupid');

        if ($roomType) {
            $map['fga.roomType'] = $roomType;
        }

        if ($start_time && $stop_time) {
            $start_time = strtotime($start_time);
            $stop_time = strtotime($stop_time);
            $map['f.time'] = ['between', [$start_time, $stop_time]];
        }

        $map['f.friendsGroupID'] = $friendsgroupid;

        $data = D('User')->clubCount($map);

        $this->assign('data', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign('consumeCount', $data['consumeCount']);
        $this->assign('friendsgroupid', $friendsgroupid);
        $this->assign('start_time', '' == $start_time ? $this->start : $start_time);
        $this->assign('stop_time', '' == $stop_time ? $this->stop : $stop_time);
        $this->display();
    }

    // 开放列表统计
    public
    function deskListCount($map = [], $limit = 4)
    {
        $User = M()->table('friendsgroupaccounts');
        $count = $User->where($map)->count();
        $Page = new \Think\Page($count, $limit);
        $show = $Page->show();
        $data = M()
            ->table('friendsgroupaccounts')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->field('usernfolist,time')
            ->where($map)
            ->select();

        $userArray = [];
        // $t         = [1 => 'score', 'money'];
        foreach ($data as $v) {
            $userlist = explode('|', $v['userinfolist']);
            array_pop($userlist);
            foreach ($userlist as $vv) {
                $find = explode(',', $vv);
                $user['name'] = M()->table('userInfo')->where(['userID' => $find[0]])->getField('name');
                // $user[$t[$v['roomtype']]] = $find[1] ? $find[1] : 0;
                $userArray[] = $user;
            }
        }

        return ['data' => $userArray, 'page' => $show];
    }

    // 牌桌统计
    public
    function deskCount($map, $limit = 10)
    {
        $mod = M()->table('friendsgroupaccounts');
        $count = $mod->where($map)->count();
        $Page = new \Think\Page($count, $limit);
        $show = $Page->show();
        $data = M()
            ->table('friendsgroupaccounts')
            ->order('time desc')
            ->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        return ['data' => $data, 'page' => $show];
    }

    // 牌桌统计 拆分
    public
    function HandledeskStringToArray($arr)
    {
        foreach ($arr as $v) {
            $newArr = explode('|', $v['userinfolist']);
            array_pop($newArr);
            foreach ($newArr as $vv) {
                $user = explode(',', $vv);
                $this->HandledeskCount($v['roomtype'], $user[0], $user[1]);
            }
        }

        foreach ($this->data as &$v) {
            $v['name'] = M()->table('userInfo')->where(['userID' => $v['name']])->getfield('name');
            $v['money'] = $v['money'] > 0 ? '+' . $v['money'] : 0;
            $v['score'] = $v['score'] > 0 ? '+' . $v['score'] : 0;
        }
    }

    // 牌桌统计 统计
    public
    function HandledeskCount($type, $userid, $num = 0)
    {
        $t = [1 => 'score', 'money'];
        if (!in_array($userid, $this->userid)) {
            $user['name'] = $userid;
            $user[$t[$type]] = $num ? $num : 0;
            $this->data[] = $user;
            array_push($this->userid, $userid);
        } else {
            foreach ($this->data as &$v) {
                if ($v['name'] == $userid) {
                    $v[$t[$type]] += $num;
                }
            }
        }
    }

    // 拆分字符串
    public
    function stringToArray($string)
    {
        $arr = explode('|', $string);
        array_pop($arr);

        $list = [];
        foreach ($arr as $v) {
            $find = explode(',', $v);
            $user['name'] = M()->table('userInfo')->where(['userID' => $find[0]])->getfield('name');
            $user['userid'] = $find[0];
            $user['sorce'] = $find[1] > 0 ? '+' . $find[1] : $find[1];
            $list[] = $user;
        }

        return $list;
    }

    // 俱乐部金币返还
    public
    function club_money()
    {
        $map = [];

        $map['friendsgroupid'] = I('friendsgroupid');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));

        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $map['create_time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $map['create_time'] = $res['data'];
        }

        $User = M('club_money');
        $count = $User->where($map)->count();
        $Page = new \Think\Page($count, 50);
        $show = $Page->show();
        $list = M('club_money')
            ->alias('c')
            ->join('left join userInfo as u on u.userID=c.userID')
            ->where($map)
            ->field('c.*,u.name as username')
            ->order('create_time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public
    function leave_room()
    {
        if (IS_POST) {
            $userID = I('userID');

            $isExists = UserModel::getInstance()->isUserExists($userID);
            if (!$isExists) {
                $this->error('用户ID不存在');
            }
            $result = UserModel::getInstance()->leaveRoom($userID);
            if (!$result) {
                $this->error('修改失败');
            }
            $this->success('修改成功');
        } else {
            $this->display();
        }
    }

    public
    function relieve_bind()
    {
        if (IS_POST) {
            $userID = I('userID');

            $isExists = UserModel::getInstance()->isUserExists($userID);
            if (!$isExists) {
                $this->error('用户ID不存在');
            }
            $result = UserModel::getInstance()->updateUserInfo($userID, ['macAddr' => '']);
            if (!$result) {
                $this->error('解绑失败');
            }
            $this->success('解绑成功');
        } else {
            $this->display();
        }
    }

    /**
     * @param $year 给定的年份
     * @param $month 给定的月份
     * @param $legth 筛选的区间长度 取前六个月就输入6
     * @param int $page 分页
     * @return array
     */
    public
    function getLastTimeArea($year, $month, $legth, $page = 1)
    {
        if (!$page) {
            $page = 1;
        }
        $monthNum = $month + $legth - $page * $legth;
        $num = 1;
        if ($monthNum < -12) {
            $num = ceil($monthNum / (-12));
        }
        $timeAreaList = [];
        for ($i = 0; $i < $legth; $i++) {
            $temMonth = $monthNum - $i;
            $temYear = $year;
            if ($temMonth <= 0) {
                $temYear = $year - $num;
                $temMonth = $temMonth + 12 * $num;
                if ($temMonth <= 0) {
                    $temMonth += 12;
                    $temYear -= 1;
                }
            }
            $startMonth = strtotime($temYear . '/' . $temMonth . '/01'); //该月的月初时间戳
            //    $endMonth = strtotime($temYear.'/'.($temMonth + 1).'/01') - 86400;//该月的月末时间戳
            if ($temMonth == '12') {
                $endMonth = strtotime($temYear . '/12/31'); //该月的月末时间戳
            } else {
                $endMonth = strtotime($temYear . '/' . ($temMonth + 1) . '/01') - 86400; //该月的月末时间戳
            }

            $res['month'] = $temYear . '/' . $temMonth; //该月的月初格式化时间
            $res['begin2'] = $startMonth;
            $res['end2'] = $endMonth;
            $timeAreaList[] = $res;
        }
        return $timeAreaList;
    }

    public function unbindPhone() {
        $userID = (int)I("userID");
        if (empty($userID)) {
            $this->error('userID 参数错误');
        }
        M()->startTrans();
        $phone = M()->table(MysqlConfig::Table_userinfo)->where(['userID' => $userID])->getField('phone');
        if (empty($phone)) {
            $this->error('该用户没有绑定手机号');
        }
        $res = M()->table(MysqlConfig::Table_userinfo)->where(['userID' => $userID])->save(['phone' => '']);
        if (!$res) {
            $this->error('解绑失败');
        }
        $res = RedisManager::getGameRedis()->del(GameRedisConfig::String_phoneToUserID . '|' . $phone);
        if (!$res) {
            M()->rollback();
            $this->error('缓存清除失败');
        }
//        $res = RedisManager::getGameRedis()->hDel(GameRedisConfig::Hash_userInfo . '|' . $userID, 'phone');
        $res = RedisManager::getGameRedis()->hSet(GameRedisConfig::Hash_userInfo . '|' . $userID, 'phone', '');
        if (!$res) {
            M()->rollback();
            $this->error('缓存清除失败');
        }
        M()->commit();
        $this->success('解绑成功');
    }

    /*
     * 提现管理列表
     * */
    function cash_management_list()
    {
        $is_super = I('is_super', false);
        $is_online = I('is_online', false);
        $is_jujue = I('is_jujue', false);

        $where = [];
        // 查询提现中
        if ($is_super) {
            $where['U.cash_status'] =1;
        }

        //查寻提现完成
        if ($is_online) {
            $where['U.cash_status'] =2;
        }

        //查询拒绝
        if ($is_jujue) {
            $where['U.cash_status'] =3;
        }

        $count = M()->table('user_cash_application as U')->where($where)->count();
        $page = new \Think\Page($count, 20);
        $dbUserList = M()
            ->table('user_cash_application as U')
            ->where($where)
            ->field('U.Id, U.userID, U.transferable_amount, U.remarks, U.nickname, U.create_time, U.process_time, U.cash_account_type, U.cash_status, U.cash_money, U.cash_withdrawal, U.cash_rate, U.cash_remarks')
            ->order('U.Id desc')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();

        foreach ($dbUserList as &$dbUser) {
            $dbUser['create_time'] = date('Y-m-d H:i:s', $dbUser['create_time']);
            $dbUser['process_time'] = isset($dbUser['process_time']) ? date('Y-m-d H:i:s', $dbUser['process_time']) : '';
            $dbUser['cash_account_type_text'] = $dbUser['cash_account_type'] == 1 ? '银行卡' : '支付宝';
        }

        $this->assign('_page', $page->show());
        $this->assign('_data', $dbUserList);
        $this->display();
    }

    //提现审批通过
    public function approval_adopt()
    {
        $type = I('post.id', '', 'int');
        empty($type) && $this->error('参数错误');
        M()->startTrans();
        try{
            //查询出申请信息
            /*$cashInfo = M()->table('user_cash_application as U')->where(['Id' => $type])->field('userID,cash_money')->find();
            if (empty($cashInfo)) $this->error('审批失败');*/

            //更改提现申请状态
            $updateInfo['process_time'] = time();
            $updateInfo['payment_time'] = time();
            $updateInfo['cash_status'] = 2;
            $updateInfo['approver_user_id'] = session('admin_user_info.id');
            $updateInfo['approver_username'] = session('admin_user_info.username');
            if (empty(M()->table('user_cash_application as U')->where(['Id' => $type])->save($updateInfo))) {
                M()->rollback();
                $this->error('审批失败');
            }

            //添加用户金币变化表的记录
            /*$money = M()->table('userInfo')->where("userID = ".$cashInfo['userid'])->getField('money');
            $moneychangeInfo['userID'] = $cashInfo['userid'];
            $moneychangeInfo['time'] = time();
            $moneychangeInfo['money'] = $money - $cashInfo['cash_money']*100;
            $moneychangeInfo['changeMoney'] = -100 * $cashInfo['cash_money'];
            $moneychangeInfo['reason'] = '1022';
            if (empty(M()->table('statistics_moneychange')->add($moneychangeInfo))) {
                M()->rollback();
                $this->error('审批失败');
            }*/

            //更改用户的金币数
            /*if (empty(M()->table('userInfo')->where("userID = ".$cashInfo['userid'])->setDec('money', $cashInfo['cash_money']*100))) {
                M()->rollback();
                $this->error('审批失败');
            }*/

            //记录日志
            operation_record(session('admin_user_info.id'), session('admin_user_info.username').' 审批同意提现信息');
            $logstr = '后台管理员 =》 '.session('admin_user_info.username').' 审批通过user_cash_application这张表id为 '.$type.' 的提现信息';
            \Think\Log::write($logstr);
            M()->commit();
            $this->success('审批成功');
        }catch (Exception $e){
            \Think\Log::write('审批通过错误信息:'.$e->getMessage());
            M()->rollback();
            $this->error('审批失败');
        }

    }

    //提现审批拒绝
    public function approval_refuse()
    {
        $type = I('post.id', '', 'int');
        empty($type) && $this->error('参数错误');

        M()->startTrans();
        try{
            $updateInfo['process_time'] = time();
            $updateInfo['payment_time'] = time();
            $updateInfo['cash_status'] = 3;
            $updateInfo['approver_user_id'] = session('admin_user_info.id');
            $updateInfo['approver_username'] = session('admin_user_info.username');
            $applicationres = M()->table('user_cash_application as U')->where(['Id' => $type])->save($updateInfo);
            if (empty($applicationres)) {
                M()->rollback();
                $this->error('审批失败');
            }

            //查询出申请信息
            $cashInfo = M()->table('user_cash_application as U')->where(['Id' => $type])->field('userID,cash_money')->find();
            if (empty($cashInfo)) $this->error('审批失败');

            //添加用户金币变化表的记录
            /*$money = M()->table('userInfo')->where("userID = ".$cashInfo['userid'])->getField('money');
            $moneychangeInfo['userID'] = $cashInfo['userid'];
            $moneychangeInfo['time'] = time();
            $moneychangeInfo['money'] = $money + $cashInfo['cash_money']*100;
            $moneychangeInfo['changeMoney'] = 100 * $cashInfo['cash_money'];
            $moneychangeInfo['reason'] = '1023';
            if (empty(M()->table('statistics_moneychange')->add($moneychangeInfo))) {
                M()->rollback();
                $this->error('审批失败');
            }

            //更改用户的金币数
            if (empty(M()->table('userInfo')->where("userID = ".$cashInfo['userid'])->setInc('money', $cashInfo['cash_money']*100))) {
                M()->rollback();
                $this->error('审批失败');
            }*/
            $userID = $cashInfo['userid'];
            $type = 1;
            $num = FunctionHelper::MoneyInput($cashInfo['cash_money'], $type);
            $result = UserModel::getInstance()->changeUserResource($userID, $type, $num, EnumConfig::E_ResourceChangeReason['CASH_WITHDRAWAL_JUJUE']);
            if (empty($result)) {
                M()->rollback();
                $this->error('审批失败');
            }

            //记录日志
            operation_record(session('admin_user_info.id'), session('admin_user_info.username').' 审批拒绝提现信息');
            $logstr = '后台管理员 =》 '.session('admin_user_info.username').' 审批拒绝user_cash_application这张表id为 '.$type.' 的提现信息';
            \Think\Log::write($logstr);
            M()->commit();
            $this->success('审批成功');
        }catch (Exception $e){
            \Think\Log::write('审批拒绝错误信息:'.$e->getMessage());
            M()->rollback();
            $this->error('审批失败');
        }
    }

    //玩家资金列表
    public function player_Funds_List()
    {
        $type = I('type');
        $search = I('search');
        $is_super = I('is_super', false);
        $is_online = I('is_online', false);

        //只查玩家
        $where['U.isVirtual'] = EnumConfig::E_GameUserType['PLAYER'];

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['U.userID'] = $search;
                    break;
                case 2:
                    $where['U.name'] = ['like', "%{$search}%"];
                    break;
            }
        } else {
            $search = '';
        }

        // 查超端用户
        if ($is_super) {
            //权限最大值 或运算
            $status_length = 0;
            foreach (EnumConfig::E_UserStatusType as $s_key => $s_value) {
                $status_length |= $s_value;
            }
            //超端权限可能值数组
            $super_status_array = [];
            for ($status = 0; $status <= $status_length; $status++) {
                //与运算
                if (($status & EnumConfig::E_UserStatusType['SUPER']) == EnumConfig::E_UserStatusType['SUPER']) {
                    $super_status_array[] = $status;
                }
            }
            $where['U.status'] = ['in', $super_status_array];
            unset($where['U.registerTime']);
        }

        //查在线玩家
        if ($is_online) {
            $where['U.IsOnline'] = EnumConfig::E_UserOnlineStatus['ON'];
            unset($where['U.registerTime']);
        }

        $count = M()->table('userInfo as U')->where($where)->count();
        $page = new \Think\Page($count, 20);
        $dbUserList = M()
            ->table('userInfo as U')
            ->join('left join roomBaseInfo as rbi on rbi.roomID = U.roomID')
            ->where($where)
            ->field('U.userID, U.name, U.money, U.sealFinishTime, rbi.name as roomname, U.lastCrossDayTime, U.registerTime, U.IsOnline')
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        //var_dump($dbUserList);exit;

        foreach ($dbUserList as $key => &$dbUser) {
            //后台充值总金额
            $adminRechargeMoney = M()->table('web_admin_action')->where(['actionType' => 1, 'userID' => $dbUser['userid']])->sum('resourceNum');
            //客户端充值总金额
            $clientRechargeMoney = M()->table('web_pay_orders')->where(['status' => 1, 'userID' => $dbUser['userid']])->sum('buyNum');
            //总充值
            /*if($dbUser['userid'] == 122004){
                var_dump($adminRechargeMoney);
                var_dump($clientRechargeMoney);exit;
            }*/
            $dbUser['sumRechargeMoney'] = $adminRechargeMoney + $clientRechargeMoney/100;

            //后台提现
            $adminCash = M()->table('web_admin_action')->where(['actionType' => 2, 'userID' => $dbUser['userid']])->field('sum(resourceNum) as sumresourceNum, count(*) as number')->find();
            //客户端提现即兑换
            $clientCash = M()->table('user_cash_application')->where(['cash_status' => 2, 'userID' => $dbUser['userid']])->field('sum(cash_money) as sumcash_money, count(*) as number')->find();
            //总提现
            /*if($dbUser['userid'] == 122004){
                var_dump($adminCash);
                var_dump($clientCash);exit;
            }*/
            $dbUser['sumCash'] = $adminCash['sumresourcenum'] + $clientCash['sumcash_money'];

            //提现次数
            $dbUser['cashCount'] = $adminCash['number'] + $clientCash['number'];

            if($dbUser['isonline'] == 1){
                $dbUser['IsOnline'] = '在线';
            }else{
                $dbUser['IsOnline'] = '不在线';
            }

            if($dbUser['sealfinishtime'] == -1){
                $dbUser['sealfinishtime'] = '永久封号';
            }else{
                $dbUser['sealfinishtime'] = '正常';
            }

        }

        //金币倍数处理
        FunctionHelper::moneyInArrayOutput($dbUserList, GeneralConfig::USERINFO_MONEY);
        //var_dump($dbUserList);exit;

        $this->assign('_page', $page->show());
        $this->assign('_data', $dbUserList);
        $this->assign('search', $search);
        $this->assign('type', $type);
        $this->display();
    }




}
