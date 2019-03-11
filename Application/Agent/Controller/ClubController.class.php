<?php
namespace Agent\Controller;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.GetRedis', '', '.class.php');

use config\EnumConfig;
use config\GameRedisConfig;
use config\RedisConfig;
use manager\RedisManager;
use model\ClubModel;
use notify\CenterNotify;

class ClubController extends AgentController
{
    protected $user = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->user = M('agent_member')->find(UID);

    }
    // 解散俱乐部
    public function dismissClub()
    {
        $friendsGroupID = I('friendsGroupID');
        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        if (empty($friendsGroup)) {
            $this->error('俱乐部不存在');
        }

        // 俱乐部所有的成员ID set
        $friendsGroupToUserSet = RedisManager::getRedis()->zRange(RedisConfig::SSet_friendsGroupToUserSet . '|' . $friendsGroupID, 0, -1);
        foreach ($friendsGroupToUserSet as $memberUserID) {
            //解散俱乐部通知
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['DISMISS'], $friendsGroupID, $friendsGroup['masterID']);
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

        $result = ClubModel::getInstance()->dismissFriendsGroup($friendsGroupID);
        if (!empty($result)) {
            $allDeskList = ClubModel::getInstance()->getFriendsGroupAllDeskList($friendsGroupID);
            foreach ($allDeskList as $desk) {
                //解散俱乐部房间 以群主的身份
                CenterNotify::dismissFriendsGroupDesk($friendsGroup['masterID'], $desk['deskIdx'], $desk['roomID']);
            }
            $this->success('解散俱乐部成功');
        }
        $this->error('解散俱乐部失败');
    }
    // 好友俱乐部
    public function index()
    {
        $map    = [];
        $type   = I('type', '');
        $search = I('search', '');
        $start  = urldecode(I('start'));
        $stop   = urldecode(I('stop'));

        $map['g.masterID'] = $this->user['userid'];


        if ($type && $search) {
            switch ($type) {
                case 1:
                    $map['g.name'] = $search;
                    break;
                case 2:
                    $map['g.friendsGroupID'] = $search;
                    break;
            }
        } else {
            $search = '';
        }

        $data = D('Club')->clubList($map);


        $this->assign('data', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->display();
    }

    // 俱乐部成员
    public function clubUserList()
    {
        $map = [];

        $id                      = I('id');
        $map['f.friendsGroupID'] = $id;

        $search = I('search');
        $type   = I('type');

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

        $data = D('Club')->getUserList($map);
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('id', $id);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    // 单个成员战绩
    public function userRecord()
    {
        $map = [];

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        $map['ga.friendsGroupID'] = I('friendsGroupID');

        $roomType = I('roomType');

        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop) + 24 * 3600 - 1;
            $map['ga.time'] = ['between', [$start, $stop]];
        } else {
            if (!$roomType) {
                $start = strtotime(date('Y-m-d', time()));
                $stop = $start + 24 * 3600 - 1;
                $map['ga.time'] = ['between', [$start, $stop]];
            }
        }

        if ($roomType) {
            $map['ga.roomType'] = $roomType;
        }

        $userID = I('userID');
        $data = D('Club')->userRecord($map, $userID);

        $this->assign('_data', $data['data']);
        $this->assign('data', $data['userClubRecordCount']);
        $this->assign('userID', $userID);
        $this->assign('roomType', $roomType);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('friendsGroupID', $map['ga.friendsGroupID']);
        $this->display();
    }

    // 俱乐部战绩
    public function clubRecord()
    {
        $map = [];
        $friendsGroupID = I('friendsgroupid', 0);

        $roomType = I('roomType');

        $start = urldecode(I('start', ''));
        $stop = urldecode(I('stop', ''));

        if ($roomType) {
            $map['roomType'] = $roomType;
        }

        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop) + 24 * 3600 - 1;
            $map['time'] = ['between', [$start, $stop]];
        } else {
            if (!$roomType) {
                $start = strtotime(date('Y-m-d', time()));
                $stop = $start + 24 * 3600 - 1;
                $map['time'] = ['between', [$start, $stop]];
            }
        }
        if (!ClubModel::getInstance()->isFriendsGroupMemberExists($friendsGroupID, $this->agentInfoOfMime['userid'])) {
            $this->error('只有群成员可以查看俱乐部战绩');
        }

        // 2.牌桌战绩
        $map['friendsGroupID'] = $friendsGroupID;
        $poke = D('Club')->getTableRecord($map);
        $count = D('Club')->getClubCount($friendsGroupID);
       
        $this->assign('recordList', $poke['data']);
        $this->assign('recordPage', $poke['page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('friendsGroupID', $friendsGroupID);
        $this->assign('roomType', $roomType);
        $this->assign('count', $count);
        $this->display();
    }

    // 俱乐部推荐
    public function clubRecommend()
    {
        if (IS_POST) {
            $friendsGroupID = I('friendsgroupid');
            $hotCount       = I('hotCount');
            $res            = M()->table('friendsgroupaccounts')->where(['friendsGroupID' => $friendsGroupID])->setfield('hotCount', $hotCount);
            if ($res) {
                $this->success('设置成功');
            } else {
                $this->error('设置失败');
            }
        } else {
            $friendsGroupID = I('friendsgroupid');
            $hotCount       = M()->table('friendsgroupaccounts')->where(['friendsGroupID' => $friendsGroupID])->getfield('hotCount');
            $this->assign('hotCount', $hotCount);
            $this->assign('friendsGroupID', $friendsGroupID);
            $this->display();
        }

    }

    // 俱乐部战绩导出
    public function csv_data()
    {
        $map                   = [];
        $map['friendsGroupID'] = I('friendsgroupid', 0); // 俱乐部ID

        if ($start && $stop) {
            $map['time'] = ['between', [$start, $stop]];
        }

        $a = 1;

        $record = D('Club')->getClubTableRecord($map);

        $data = [];

        $roomtype = [1 => '积分房', '金币房', 'vip房'];

        foreach ($record as $v) {
            $find             = [];
            $find['passwd']   = $v['passwd'];
            $find['time']     = date('Y-m-d H:i:s', $v['time']);
            $find['roomtype'] = $roomtype[$v['roomtype']];
            $find['gametype'] = $v['name'];
            // $find['user_count'] = $v['user_count'];
            $find['play_count'] = $v['playcount'];
            $userlist           = explode('#', $v['userinfolist']);
            array_pop($userlist);
            $win = explode("|", $userlist[0]);
            array_pop($win);
            $user = '';
            foreach ($win as $vv) {
                $findUser = explode(',', $vv);
                $user .= "用户ID:" . $findUser[0];
                $user .= " 用户昵称:" . M()->table('userInfo')->where(['userID' => $findUser[0]])->getfield('name');
                $user .= " 输赢:" . $findUser[1] > 0 ? '+' . $findUser[1] : $findUser[1] . " || ";
            }

            $find['win']      = $user;
            $pump             = ["不收小费", "收取所有大赢家:{$v['roomtiptypenums']}%", "收取最大的大赢家:{$v['roomtiptypenums']}%"];
            $find['pumptype'] = $pump[$v['roomtiptype']];
            switch ($v['roomtype']) {
                case 1:
                    $find['pump_count'] = '积分无抽水';
                    break;

                case 2:

                    $find['pump_count'] = (int) M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->getfield('moneyPump');
                    break;

                case 3:
                    $find['pump_count'] = (int) M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->getfield('fireCoinPump');
                    break;
            }

            $data[] = $find;

        }

        $filename = '俱乐部战绩.csv';
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename='$filename'");
        header('Cache-Control: max-age=0');
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息
        $head = array('房间号', '时间', '房间类型', '游戏类型', '局数', '输赢', '抽水方式', '服务费');
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
    public function clubCount()
    {
        $map            = [];
        $roomType       = I('roomType');
        $start_time     = urldecode(I('start'));
        $stop_time      = urldecode(I('stop'));
        $friendsgroupid = I('friendsgroupid');

        if ($roomType) {
            $map['fga.roomType'] = $roomType;
        }

        if ($start_time && $stop_time) {
            $start_time    = strtotime($start_time);
            $stop_time     = strtotime($stop_time);
            $map['f.time'] = ['between', [$start_time, $stop_time]];
        }

        $map['f.friendsGroupID'] = $friendsgroupid;

        $data = D('Club')->clubCount($map);

        $this->assign('data', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign('count', $data['consumeCount']);
        $this->assign('friendsgroupid', $friendsgroupid);
        $this->assign('start', $start_time);
        $this->assign('stop', $stop_time);
        $this->assign('roomtype', $roomType);
        $this->display();
    }

    // 开放列表统计
    public function deskListCount($map = [], $limit = 4)
    {
        $User  = M()->table('friendsGroupAccounts');
        $count = $User->where($map)->count();
        $Page  = new \Think\Page($count, $limit);
        $show  = $Page->show();
        $data  = M()
            ->table('friendsGroupAccounts')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->field('userinfolist,time')
            ->where($map)
            ->select();

        $userArray = [];
        // $t         = [1 => 'score', 'money'];
        foreach ($data as $v) {
            $userlist = explode('|', $v['userinfolist']);
            array_pop($userlist);
            foreach ($userlist as $vv) {
                $find         = explode(',', $vv);
                $user['name'] = M()->table('userInfo')->where(['userID' => $find[0]])->getField('name');
                // $user[$t[$v['roomtype']]] = $find[1] ? $find[1] : 0;
                $userArray[] = $user;
            }
        }

        return ['data' => $userArray, 'page' => $show];
    }

    // 牌桌统计
    public function deskCount($map, $limit = 10)
    {
        $mod   = M()->table('friendsGroupAccounts');
        $count = $mod->where($map)->count();
        $Page  = new \Think\Page($count, $limit);
        $show  = $Page->show();
        $data  = M()
            ->table('friendsGroupAccounts')
            ->order('time desc')
            ->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        return ['data' => $data, 'page' => $show];
    }

    // 牌桌统计 拆分
    public function HandledeskStringToArray($arr)
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
            $v['name']  = M()->table('userInfo')->where(['userID' => $v['name']])->getfield('name');
            $v['money'] = $v['money'] > 0 ? '+' . $v['money'] : 0;
            $v['score'] = $v['score'] > 0 ? '+' . $v['score'] : 0;
        }
    }

    // 牌桌统计 统计
    public function HandledeskCount($type, $userid, $num = 0)
    {
        $t = [1 => 'score', 'money'];
        if (!in_array($userid, $this->userid)) {
            $user['name']    = $userid;
            $user[$t[$type]] = $num ? $num : 0;
            $this->data[]    = $user;
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
    public function stringToArray($string)
    {
        $arr = explode('|', $string);
        array_pop($arr);

        $list = [];
        foreach ($arr as $v) {
            $find           = explode(',', $v);
            $user['name']   = M()->table('userInfo')->where(['userID' => $find[0]])->getfield('name');
            $user['userid'] = $find[0];
            $user['sorce']  = $find[1] > 0 ? '+' . $find[1] : $find[1];
            $list[]         = $user;
        }

        return $list;
    }

    // 俱乐部金币返还
    public function clubMoney()
    {
        $map = [];

        $map['friendsgroupid'] = I('friendsgroupid');

        $start = I('start', '');
        $stop  = I('stop', '');

        if ($start && $stop) {
            $start              = strtotime($strat);
            $stop               = strtotime($stop);
            $map['create_time'] = ['between', [$start, $stop]];
        }

        $User  = M('clubmoney');
        $count = $User->where($map)->count();
        $Page  = new \Think\Page($count, 50);
        $show  = $Page->show();
        $list  = M('clubmoney')
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

    //兑换与充值
    public function clubRechargeExchange()
    {
        $where = [];
        $map = [];
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start', ''));
        $stop = urldecode(I('stop', ''));

        if ($search && $type) {
            switch ($type) {
                case 1:
                    $map['u.name'] = $search;
                    break;
                case 2:
                    $map['u.userID'] = $search;
                    break;
            }
        }

        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop);
            $where['time'] = ['between', [$start, $stop]];
        }

        $friendsGroupID = I('friendsgroupid', 0);
        $map['friendsGroupID'] = $friendsGroupID;


        $count = M()->table('web_club_member as t')
            ->join('left join userInfo as u on u.userID=t.userID')
            ->where($map)
            ->field('t.*')
            ->count();
        $Page = new \Think\Page($count, 15);
        $show = $Page->show();
        $list = M()
            ->table('web_club_member as t')
            ->join('left join userInfo as u on u.userID=t.userID')
            ->where($map)
            ->field('t.*,u.name')
            ->order('power desc ,joinTime asc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($list as &$v) {
            $v['friendsGroupID'] = $v['friendsgroupid'];
            $v['userID'] = $v['userid'];
            $v['recharge'] = M()->table('friendsGroupDeskListCost')->where($where)->where(['friendsGroupID' => $v['friendsGroupID'], 'userID' => $v['userID']])->sum('fireCoinRecharge');
            $v['exchange'] = M()->table('friendsGroupDeskListCost')->where($where)->where(['friendsGroupID' => $v['friendsGroupID'], 'userID' => $v['userID']])->sum('fireCoinExchange');
            $v['firecoinpump'] = M()->table('friendsGroupDeskListCost')->where($where)->where(['friendsGroupID' => $v['friendsGroupID'], 'userID' => $v['userID']])->sum('fireCoinPump');
            $v['exchange_time'] = M()->table('friendsGroupDeskListCost')->where($where)->where(['friendsGroupID' => $v['friendsGroupID'], 'userID' => $v['userID']])->order('time desc')->getfield('time');
        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('friendsGroupID', $friendsGroupID);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('totalRecharge', M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $friendsGroupID])->sum('fireCoinRecharge'));
        $this->assign('totalExcharge', M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $friendsGroupID])->sum('fireCoinExchange'));
        $this->display();
    }


    // 充值
    public function clubRecharge()
    {
        $map                     = [];
        $map['g.friendsGroupID'] = I('friendsgroupid');
        $map['g.userID']         = I('userid');

        $user = M()->
            table('web_club_member as g')
            ->join('left join userInfo as u on u.userID=g.userID')
            ->field('g.*,u.name')
            ->where($map)
            ->find();

        $clubMoney = explode('|', $user['fgmoney']);
        array_pop($clubMoney);
        foreach ($clubMoney as $v) {
            $find = explode(",", $v);
            if ($find[0] == I('friendsgroupid')) {
                $hb = $find[1];
            }
        }
        $fireCoin = ClubModel::getInstance()->getFriendsGroupMemberCarryFireCoin($map['g.friendsGroupID'], $map['g.userID'] );
        $this->assign('user', $user);
        $this->assign('fireCoin', $fireCoin);
        $this->assign('hb', $hb);
        $this->display();

    }

    // 兑换 跟充值一一样
    public function clubExchange()
    {
        $map                     = [];
        $map['g.friendsGroupID'] = I('friendsgroupid');
        $map['g.userID']         = I('userid');

        $user = M()->
        table('web_club_member as g')
            ->join('left join userInfo as u on u.userID=g.userID')
            ->field('g.*,u.name')
            ->where($map)
            ->find();

        $clubMoney = explode('|', $user['fgmoney']);
        array_pop($clubMoney);
        foreach ($clubMoney as $v) {
            $find = explode(",", $v);
            if ($find[0] == I('friendsgroupid')) {
                $hb = $find[1];
            }
        }

        $fireCoin = ClubModel::getInstance()->getFriendsGroupMemberCarryFireCoin($map['g.friendsGroupID'], $map['g.userID'] );
        $this->assign('user', $user);
        $this->assign('fireCoin', $fireCoin);
        $this->assign('hb', $hb);
        $this->display();

    }

    // 充值兑换
    public function rechargeExchange()
    {
        $friendsGroupID = I('friendsgroupid');
        $userID = I('userid');
        $num = I('num');
        $type = I('type'); //0 兑换 1充值
        //充值或者兑换0
        if ($num == 0) {
            $this->error($type == 1 ? '充值数量不能为0' : '兑换数量不能为0');
        }
        if ($type == 2) {
            $num = "-" . $num;
        }
        //是否能兑换
        $fireCoin = ClubModel::getInstance()->getFriendsGroupMemberCarryFireCoin($friendsGroupID, $userID);

        if ($fireCoin + $num < 0) {
            $this->error("兑换数量不能超过{$fireCoin}");
        }

       $result = ClubModel::getInstance()->changeUserResource($userID, EnumConfig::E_ResourceType['FIRECOIN'], $num, EnumConfig::E_ResourceChangeReason['BACK_CLUB_RECHARGE'], 0, $friendsGroupID);
        if ($result) {
            //充值或者回收火币
            ClubModel::getInstance()->addFriendsGroupFireCoinOperationLog($friendsGroupID, $userID, $num, EnumConfig::E_ChangeFireCoinID['BACK']);
        }
        if (!$result) {
            $this->error($type == 1 ? '充值失败' : '兑换失败');
        }
        $this->success($type == 1 ? '充值成功' : '兑换成功');
    }

    // 详细
    public function clubRechargeInfo()
    {
        $start          = urldecode(I('start'));
        $stop           = urldecode(I('stop'));
        $userid         = I('userid');
        $friendsgroupid = I('friendsgroupid');
        $type           = I('type');

        if ($start && $stop) {
            $start       = strtotime($start);
            $stop        = strtotime($stop);
            $map['time'] = ['between', [$start, $stop]];
        } else {
            $start       = strtotime(date('Y-m-d', time()));
            $stop        = $start + 24 * 3600 - 1;
            $map['time'] = ['between', [$start, $stop]];
        }

        if ($type == 1) {
            $map['d.fireCoinRecharge'] = ['gt', 0];
        } else if ($type == 2) {
            $map['d.fireCoinExchange'] = ['lt', 0];
        }

        $map['d.userID']         = $userid;
        $map['d.friendsGroupID'] = $friendsgroupid;
       

        $mod   = M()->table('friendsGroupDeskListCost as d');
        $count = $mod->join('left join userInfo as u on u.userID=d.userID')->where($map)->field('d.*,u.name')->count();
        $Page  = new \Think\Page($count, 15);
        $show  = $Page->show();
        $list  = M()
            ->table('friendsGroupDeskListCost as d')
            ->join('left join userInfo as u on u.userID=d.userID')
            ->field('d.*,u.name')
            ->where($map)
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        $hb = ClubModel::getInstance()->getFriendsGroupMemberCarryFireCoin($friendsgroupid, $userid );

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('userid', $userid);
        $this->assign('rechangeTotal', M()->table('friendsGroupDeskListCost')->where(['userID' => $userid, 'friendsGroupID' => $friendsgroupid])->sum('fireCoinRecharge'));
        $this->assign('exchangeTotal', M()->table('friendsGroupDeskListCost')->where(['userID' => $userid, 'friendsGroupID' => $friendsgroupid])->sum('fireCoinExchange'));
        $this->assign('hb', $hb);
        $this->assign('pump', M()->table('friendsGroupDeskListCost')->where(['userID' => $userid, 'friendsGroupID' => $friendsgroupid])->sum('fireCoinPump'));
        $this->assign('friendsgroupid', $friendsgroupid);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->display();
    }

    // 删除俱乐部用户
    public function delClubUser()
    {
        $friendsGroupID = I('friendsGroupID');
        $userID = I('userID');

        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        if (empty($friendsGroup)) {
            $this->error('俱乐部不存在');
        }

        //获取俱乐部成员信息 用于推送
        $friendsGroupMember = ClubModel::getInstance()->getFriendsGroupMember($friendsGroupID, $userID);
        //删除该成员
        $result = ClubModel::getInstance()->delFriendsGroupMember($friendsGroupID, $userID);
        if (!empty($result)) {
            //增加被移除群通知 (以群主的身份)
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['DELETED'], $friendsGroupID, $friendsGroup['masterID']);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $userID, time(), $notifyID);
            //推送俱乐部删除消息
            CenterNotify::friendsGroupDismiss($userID, $friendsGroupID);
            //推送俱乐部成员删除 给俱乐部所有人
            CenterNotify::friendsGroupMemberChangeAll($friendsGroupID, EnumConfig::E_ChangeType['DEL'], $friendsGroupMember);
            //获取俱乐部通知
            $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
            //推送俱乐部通知
            CenterNotify::friendsGroupNotify($userID, $friendsGroupNotify);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'FGNotifyList', 1);
            //推送俱乐部通知小红点变化
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['FG']);
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }
    // 俱乐部管理员权限
    public function clubManagerPower()
    {
        $friendsGroupID = I('friendsGroupID');
        $userID = I('userID');
        $power = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $userID);
        $userName = ClubModel::getInstance()->getUserInfo($userID,'name');
        if (IS_POST) {
            $powerList = I('id');
            $newPower = EnumConfig::E_FriendsGroupPowerType['NO'];
            foreach ($powerList as $p) {
                $newPower |= $p;
            }
            if ($newPower == $power) {
                $this->success('操作成功');
            }
            $result = ClubModel::getInstance()->changeFriendsGroupMemberPower($friendsGroupID, $userID, $newPower);
            if (empty($result)) {
                $this->error('操作失败');
            }
            //推送权限变化
            $newPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $userID);
            CenterNotify::friendsGroupMemberPowerChangeAll($friendsGroupID, $userID, $newPower);
            $this->success('操作成功');
        } else {
            $this->assign('power', $power);
            $this->assign('friendsGroupID', $friendsGroupID);
            $this->assign('userName', $userName);
            $this->assign('userID', $userID);
            $this->display();
        }
    }
    // 设置管理员
    public function setClubAdministrators()
    {
        $friendsGroupID = I('friendsgroupid');
        $userID         = I('userid');
        $type           = I('type'); // 1 设置 0 取消

        $friendsGroup = ClubModel::getInstance()->getFriendsGroup($friendsGroupID);
        if (empty($friendsGroup)) {
            $this->error('俱乐部不存在');
        }
        $status = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $userID);

        if ($type == 1 && $status == EnumConfig::E_FriendsGroupMemberStatus['MANAGER']) {
            $this->error('已经是管理员');
        }

        if ($type == 0 && $status == EnumConfig::E_FriendsGroupMemberStatus['NORMAL']) {
            $this->error('不是管理员');
        }

        //管理员数量是否满员
        $managerCount = ClubModel::getInstance()->getFriendsGroupManagerCount($friendsGroupID);
        $otherConfig = ClubModel::getInstance()->getOtherConfig();
        if ($type == 1 && $managerCount >= $otherConfig['groupTransferCount']) {
            $this->error("最多设置个{$otherConfig['groupTransferCount']}管理员");
        }

        $newStatus = $type == 1 ? EnumConfig::E_FriendsGroupMemberStatus['MANAGER'] : EnumConfig::E_FriendsGroupMemberStatus['NORMAL'];

        //改变目标玩家的身份
        $result = ClubModel::getInstance()->changeFriendsGroupMemberStatus($friendsGroupID, $userID, $newStatus);
        if ($result === false) {
            $this->error($type == 1 ? '设置管理员失败' : '撤销管理员失败');
        }

        //推送给俱乐部所有人 目标玩家身份改变
        $statusChangeInfoList = [];
        $newStatus = ClubModel::getInstance()->getFriendsGroupMemberStatus($friendsGroupID, $userID);
        $newPower = ClubModel::getInstance()->getFriendsGroupMemberPower($friendsGroupID, $userID);
        $statusChangeInfo = array(
            'userID' => $userID,
            'status' => $newStatus,
            'power' => $newPower,
        );
        $statusChangeInfoList[] = $statusChangeInfo;
        CenterNotify::friendsGroupMemberStatusChangeAll($friendsGroupID, $statusChangeInfoList);

        //如果是设置为管理 需要通知目标用户成为管理员
        if ($newStatus == EnumConfig::E_FriendsGroupMemberStatus['MANAGER']) {
            //设置为管理员通知 以群主的身份
            $notifyID = ClubModel::getInstance()->addFriendsGroupNotify(EnumConfig::E_FriendsGroupNotifyType['AUTH'], $friendsGroupID, $friendsGroup['masterID']);
            //获取俱乐部通知
            $friendsGroupNotify = ClubModel::getInstance()->getFriendsGroupNotify($notifyID);
            // 写入redis 玩家俱乐部通知集合 userToFriendsGroupNotifySet
            RedisManager::getRedis()->zAdd(RedisConfig::SSet_userToFriendsGroupNotifySet . '|' . $userID, time(), $notifyID);
            //俱乐部通知列表小红点数量加1
            RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'FGNotifyList', 1);
            //推送俱乐部通知
            CenterNotify::friendsGroupNotify($userID, $friendsGroupNotify);
            //推送小红点变化
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['FG']);
        }
        $this->success($type == 1 ? '设置管理员成功' : '撤销管理员成功');
    }

    // 火币变化日志
    public function firCoinChange()
    {
        $map    = [];
        $type   = I('type');
        $type2  = I('type2');
        $search = I('search');
        $start  = urldecode(I('start'));
        $stop   = urldecode(I('stop'));

        if ($type2) {
            $map['reason'] = $type2;
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $map['c.userID'] = $search;
                    break;

                case 2:
                    $map['c.name'] = $search;
                    break;
            }
        }

        if ($start && $stop) {
            $start         = strtotime($start);
            $stop          = strtotime($stop);
            $map['c.time'] = ['between', [$start, $stop]];
        } else {
            if (!$search && !$type || !$type2) {
                $start         = strtotime(date('Y-m-d', time()));
                $stop          = $start + 24 * 3600 - 1;
                $map['c.time'] = ['between', [$start, $stop]];
            }
        }

        $mod   = M('firecoinchange');
        $count = $mod
            ->alias('c')
            ->join('left join userInfo as u on u.userID = c.userID')
            ->where($map)
            ->field('c.*')
            ->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $list = $mod
            ->alias('c')
            ->join('left join userInfo as u on u.userID = c.userID')
            ->where($map)
            ->order('time desc')
            ->field('c.*,u.name')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($list as &$v) {
            if ($v['reason'] == 2 || $v['reason'] == 4 || $v['reason'] == 3) {
                $v['changemoney'] = '-' . abs($v['changemoney']);
            } else {
                $v['changemoney'] = '+' . abs($v['changemoney']);
            }

        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->assign('type2', $type2);
        $this->assign('search2', $search2);
        $this->display();
    }

    //俱乐部金币变化日志
    public function money_change_record()
    {
        $where  = [];
        $type   = I('type');
        $search = I('search');
        $type2  = I('type2');
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

        $where['S.friendsGroupId'] = ['neq', 0];
        $type2                     = I('type2');
        if (0 != $type2) {
            $where['S.reason'] = $type2;
        }
        $start = urldecode(I('start'));
        $stop  = urldecode(I('stop'));
        if ($start && $stop) {
            $start           = strtotime($start);
            $stop            = strtotime($stop) + 24 * 3600 - 1;
            $where['S.time'] = ['between', [$start, $stop]];
        } else {
            if (!$search && !$type && !$type2) {

                $start           = strtotime(date('Y-m-d', time()));
                $stop            = $start + 24 * 3600 - 1;
                $where['S.time'] = ['between', [$start, $stop]];
            }
        }
        $where['U.isVirtual'] = 0;
        $count                = M()->table('statistics_moneyChange as S')->join('left join userInfo as U on U.userID=S.userID')->where($where)->count();
        $Page                 = new \Think\Page($count, 15);
        $res                  = M()->table('statistics_moneyChange as S')
            ->join('left join userInfo as U on U.userID = S.userID')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.userID,U.name,U.account,S.time,S.money,S.changeMoney,S.reason,R.name as rname')
            ->select();
        foreach ($res as $k => $v) {
            if ($res[$k]['reason'] == 16 || $res[$k]['reason'] == 3 || $res[$k]['reason'] == 11 || $res[$k]['reason'] == 15) {
                $res[$k]['changemoney'] = '-' . abs($res[$k]['changemoney']);
            }
        }
        //dump($res);
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }
}
