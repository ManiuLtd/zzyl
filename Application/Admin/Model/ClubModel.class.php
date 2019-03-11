<?php
namespace Admin\Model;

use config\EnumConfig;
use Think\Model;
use model\ClubModel as Hm_ClubModel;

class ClubModel extends Model
{
    // 俱乐部列表
    public function clubList($map = [])
    {
        $web_club = M()->table('web_club as g');
        $count = $web_club->where($map)->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $clubList = M()
            ->table('web_club as g')
            ->order('createTime desc')
            ->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        foreach ($clubList as &$club) {
            $club = Hm_ClubModel::getInstance()->getFriendsGroup($club['friendsgroupid']);
            $club['masterName'] = Hm_ClubModel::getInstance()->getUserInfo($club['masterID'], 'name');
        }
        return ['data' => $clubList, 'page' => $show];
    }

    // 俱乐部成员列表
    public function getUserList($map)
    {
        $web_club_member = M()->table('web_club_member as f');
        $count = $web_club_member
            ->join('left join userInfo as u on u.userID=f.userID')
            ->where($map)
            ->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $memberList = M()->table('web_club_member as f')
            ->join('left join userInfo as u on u.userID=f.userID')
            ->where($map)
            ->order('power desc ,joinTime asc')
            ->field('f.*,u.name as userName')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        foreach ($memberList as &$member) {
            $userName = $member['username'];
            $member = Hm_ClubModel::getInstance()->getFriendsGroupMember($member['friendsgroupid'], $member['userid']);
            
            $member['userName'] = $userName;
        }

        return ['_data' => $memberList, '_page' => $show];
    }

    // 获取群管理员ID
    protected function getClubManager($groupID)
    {
        $str = M()->table('friendsGroup')->where(['friendsGroupID' => $groupID])->getfield('manager');
        $arr = explode(',', $str);
        array_pop($arr);
        if ($arr) {
            return $arr;
        } else {
            return [];
        }
    }

    // 牌桌战绩
    public function getTableRecord($map = [], $limit = 100)
    {
        $User = M()->table('friendsGroupAccounts');
        $count = $User->where($map)->count();
        $Page = new \Think\Page($count, $limit);
        $show = $Page->show();
        $list = M()
            ->table('friendsGroupAccounts')
            ->alias('ga')
            ->join('left join userInfo as u on u.userID=ga.userID')
            ->join('left join roomBaseInfo as b on b.roomID=ga.roomID')
            ->where($map)
            ->order('time desc')
            ->field('ga.*,u.name as username,b.name')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($list as &$v) {
            // 查询服务费
            switch ($v['roomtype']) {
                case EnumConfig::E_RoomType['CARD']: // 积分 无服务费
                    break;

                case EnumConfig::E_RoomType['PRIVATE']: // 金币
                    $v['pump_money'] = M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'passwd' => $v['passwd'], 'time' => $v['time']])->getfield('moneyPump');
                    break;

                case EnumConfig::E_RoomType['MATCH']: // 火币
                    $v['pump_money'] = M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'passwd' => $v['passwd'], 'time' => $v['time']])->getfield('fireCoinPump');
                    break;
            }

            // 参与用户
            $user = explode('#', $v['userinfolist']);
            $v['user_count'] = substr_count($user[0], ',');
            $arr = explode('|', $user[0]);
            $u = '';

            array_pop($arr);
            foreach ($arr as $vv) {
                $find = explode(',', $vv);
                $u .= M()->table('userInfo')->where(['userID' => $find[0]])->getfield('name') . "，";
            }
            $u = rtrim($u, "，");
            $v['user'] = $u;

            $pump = ["无", "所有赢家:{$v['roomtiptypenums']}%", "大赢家:{$v['roomtiptypenums']}%"];
            $v['pumptype'] = $pump[$v['roomtiptype']];
        }

        return ['data' => $list, 'page' => $show];
    }

    // 获取战绩 战绩导出
    public function getClubTableRecord($map)
    {
        $data = M()
            ->table('friendsGroupAccounts')
            ->alias('ga')
            ->join('left join userInfo as u on u.userID=ga.userID')
            ->join('left join roomBaseInfo as b on b.roomID=ga.roomID')
            ->where($map)
            ->order('time desc')
            ->field('ga.*,u.name as username,b.name')
            ->select();

        foreach ($data as &$v) {
            // 查询服务费
            switch ($v['roomtype']) {
                case EnumConfig::E_RoomType['CARD']: // 积分 无服务费
                    break;

                case EnumConfig::E_RoomType['PRIVATE']: // 金币
                    $v['pump_money'] = M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'passwd' => $v['passwd'], 'time' => $v['time']])->getfield('moneyPump');
                    break;

                case EnumConfig::E_RoomType['MATCH']: // 火币
                    $v['pump_money'] = M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'passwd' => $v['passwd'], 'time' => $v['time']])->getfield('fireCoinPump');
                    break;
            }

            // 参与用户
            $user = explode('#', $v['userinfolist']);
            $v['user_count'] = substr_count($user[0], ',');
            $arr = explode('|', $user[0]);
            $u = '';

            array_pop($arr);
            foreach ($arr as $vv) {
                $find = explode(',', $vv);
                $u .= M()->table('userInfo')->where(['userID' => $find[0]])->getfield('name') . "，";
            }

            $v['user'] = $u;
        }

        return $data;
    }

    // 用户战绩
    public function userRecord($map = [], $userID)
    {
        $start = I('start');
        $stop = I('stop');

        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop);
            $where['ga.time'] = ['between', [$start, $stop]];
        }

        $list = M()
            ->table('friendsGroupAccounts as ga')
            ->join('left join friendsGroupDeskListCost as d on d.passwd=ga.passwd and d.time=ga.time')
            ->join('left join roomBaseInfo as r on r.roomID=ga.roomID')
            ->where($map)
            ->order('time desc')
            ->field('ga.*,d.moneyPump,d.fireCoinPump,r.name as roomname')
            ->select();

        // echo M()->_sql();
        foreach ($list as $k => &$v) {

            if ($this->strPosUser($userID, $v['userinfolist'])) {
                $userinfolist = explode('#', $v['userinfolist']);
                array_pop($userinfolist);
                $userInfo = explode('|', $userinfolist[0]);
                array_pop($userInfo);
                foreach ($userInfo as $vv) {
                    $my = explode(',', $vv);
                    if ($my[0] == $userID) {
                        $v['score'] = $my[1] > 0 ? '+' . $my[1] : $my[1];
                    }
                    $v['user_count'] = $this->countUser($v['userinfolist']);

                    // 服务费
                    $userPump = explode('|', $userinfolist[1]);
                    array_pop($userPump);
                    $v['pumpnums'] = 0;
                    foreach ($userPump as $p) {
                        $pump = explode(',', $p);
                        if ($pump[0] == $userID) {
                            $v['pumpnums'] = $pump[1];
                        }
                    }

                    // 胜局
                    $userVictory = explode('|', $userinfolist[2]);
                    array_pop($userVictory);
                    foreach ($userVictory as $vv) {
                        $victory = explode(',', $vv);
                        if ($victory[0] == $userID) {
                            $v['victory'] = $victory[1];
                        }
                    }
                }
            } else {
                unset($list[$k]);
            }
        }
        $userClubRecordCount = $this->userClubRecordCount($list, $userID);
        return ['data' => $list, 'userClubRecordCount' => $userClubRecordCount];
    }

    // 字符串user是佛存在
    protected function strPosUser($userid, $str)
    {
        $userInfo = explode('#', $str);
        $arr = explode("|", $userInfo[0]);
        array_pop($arr);
        foreach ($arr as $v) {
            $find = explode(",", $v);
            if ($find[0] == $userid) {
                return true;
            }
        }

        return false;
    }

    // 单个用户对局统计 金币.积分.Vip
    public function userClubRecordCount($data = [], $userID = 0)
    {
        $count = [
            // 金币 2
            'money' => 0, // 对局
            'moneyDetermined' => 0, // 输赢
            'moneyVictory' => 0, // 胜局
            'moneyPump' => 0, // 服务费
            // 积分 1
            'score' => 0,
            'scoreDetermined' => 0,
            'scoreVictory' => 0,
            //vip 3
            'vip' => 0,
            'vipDetermined' => 0,
            'vipVictory' => 0,
            'vipPump' => 0,
        ];

        foreach ($data as $v) {

            // 对局
            switch ($v['roomtype']) {
                case EnumConfig::E_RoomType['CARD']: // 积分
                    $count['score'] += $v['playcount'];
                    break;

                case EnumConfig::E_RoomType['PRIVATE']: // 金币
                    $count['money'] += $v['playcount'];
                    break;

                case EnumConfig::E_RoomType['MATCH']: // vip
                    $count['vip'] += $v['playcount'];
                    break;
            }

            // 输赢
            $money = $this->strInArray($userID, $v, 0);
            $count['scoreDetermined'] += $money[0];
            $count['moneyDetermined'] += $money[1];
            $count['vipDetermined'] += $money[2];

            // 抽水
            $pump = $this->strInArray($userID, $v, 1);
            $count['moneyPump'] += $pump[1];
            $count['vipPump'] += $pump[2];

            //胜局
            $victory = $this->strInArray($userID, $v, 2);
            $count['scoreVictory'] += $victory[0];
            $count['moneyVictory'] += $victory[1];
            $count['vipVictory'] += $victory[2];

            // 总
            $count['allCount'] = (int)$count['money'] + $count['score'] + $count['vip'];
            $count['winCount'] = (int)$count['moneyVictory'] + $count['scoreVictory'] + $count['vipVictory'];
            $count['win'] = sprintf("%.2f", ($count['winCount'] / $count['allCount']) * 100);
        }

        return $count;
    }

    // 字符串拆分 用户id,字符串,键
    protected function strInArray($userID, $f, $index)
    {
        // 拆分
        $data = explode('#', $f['userinfolist']);
        array_pop($data);
        $dataIndex = explode('|', $data[$index]); // 1 输赢 2 抽水 3 胜局
        array_pop($dataIndex);
        foreach ($dataIndex as $value) {
            $find = explode(',', $value);
            if ($find[0] == $userID) {
                switch ($f['roomtype']) {
                    case EnumConfig::E_RoomType['CARD']:
                        $count1 = $find[1];
                        break;

                    case EnumConfig::E_RoomType['PRIVATE']:
                        $count2 = $find[1];
                        break;

                    case EnumConfig::E_RoomType['MATCH']:
                        $count3 = $find[1];
                        break;
                }
            }
        }

        return [$count1, $count2, $count3];
    }

    // 战绩统计
    public function getClubCount($friendsGroupID)
    {
        $data = M()
            ->table('friendsGroupAccounts as g')
            ->join('left join friendsGroupDeskListCost as c on c.passwd=g.passwd')
            ->field('g.roomType,g.playCount,g.userInfoList,c.*')
            ->where(['g.friendsGroupID' => $friendsGroupID])
            ->select();

        $count = [
            'money' => 0, // 金币
            'moneyUserCount' => 0, //参与人户
            'moneyPumpCount' => 0, // 抽水
            'score' => 0,
            'scoreUserCount' => 0,
            'vip' => 0,
            'vipUserCount' => 0,
            'vipPumpCount' => 0,
        ];

        foreach ($data as $v) {
            switch ($v['roomtype']) {

                case EnumConfig::E_RoomType['CARD']: // 积分
                    $count['score'] += $v['playcount'];
                    $count['scoreUserCount'] += $this->countUser($v['userinfolist']);
                    break;

                case EnumConfig::E_RoomType['PRIVATE']: // 金币
                    $count['money'] += $v['playcount'];
                    $count['moneyPumpCount'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('moneyPump');
                    $count['moneyUserCount'] += $this->countUser($v['userinfolist']);
                    break;

                case EnumConfig::E_RoomType['MATCH']: // vip
                    $count['vip'] += $v['playcount'];
                    $count['vipPumpCount'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('fireCoinPump');
                    $count['vipUserCount'] += $this->countUser($v['userinfolist']);
                    break;
            }
        }

        $count['total'] = (int)$count['score'] + $count['vip'] + $count['money'];
        $count['totalUser'] = (int)$count['scoreUserCount'] + $count['moneyUserCount'] + $count['vipUserCount'];

        return $count;
    }

    // 统计参与人数
    // 1043,0|1042,0|#1023,2|1025,5|#
    public function countUser($str)
    {
        $count = substr_count($str, ",") / 3;
        return $count;
    }

    // 消耗统计
    public function clubCount($map, $limit = 100)
    {
        $User = M()->table('friendsGroupDeskListCost as f');
        $count = $User->join('friendsGroupAccounts as fga on fga.passwd=f.passwd')->where($map)->count();
        $Page = new \Think\Page($count, $limit);
        $show = $Page->show();
        $data = M()
            ->table('friendsGroupDeskListCost as f')
            ->join('friendsGroupAccounts as fga on fga.passwd=f.passwd')
            ->join('roomBaseInfo as b on b.roomID=fga.roomID')
            ->where($map)
            ->order('time desc')
            ->field('fga.*,f.costMoney,f.costJewels,f.moneyPump,f.fireCoinPump,b.name as roomname')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($data as &$v) {
            $arr = explode("#", $v['userinfolist']);
            $v['user_count'] = substr_count($arr[0], ",");

            $v['costnums'] = $v['costmoney'] + $v['costjewels'];
            $v['pumpnums'] = $v['moneypump'] + $v['firecoinpump'];

            $pump = ["无", "所有赢家:{$v['roomtiptypenums']}%", "大赢家:{$v['roomtiptypenums']}%"];
            $v['pumptype'] = $pump[$v['roomtiptype']];
        }

        // 消耗统计
        $consumeCount = $this->clubCountStatistics($data);

        return ['data' => $data, 'page' => $show, 'consumeCount' => $consumeCount];
    }

    // 消耗总统计
    protected function clubCountStatistics($data = [])
    {
        $count = [
            'vip' => 0,
            'money' => 0,
            'score' => 0,
            'vipCostJewels' => 0,
            'moneyCostMoney' => 0,
            'scoreCostJewels' => 0,
            'moneyPump' => 0,
            'fireCoinPump' => 0,
        ];

        foreach ($data as $v) {
            switch ($v['roomtype']) {
                case EnumConfig::E_RoomType['CARD']:
                    $count['score']++;
                    $count['scoreCostJewels'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('costJewels');
                    break;

                case EnumConfig::E_RoomType['PRIVATE']:
                    $count['money']++;
                    $count['moneyCostMoney'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('costMoney');
                    $count['moneyPump'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('moneyPump');
                    break;

                case EnumConfig::E_RoomType['MATCH']:
                    $count['vip']++;
                    $count['vipCostJewels'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('costJewels');
                    $count['fireCoinPump'] += M()->table('friendsGroupDeskListCost')->where(['friendsGroupID' => $v['friendsgroupid'], 'time' => $v['time'], 'passwd' => $v['passwd']])->sum('fireCoinPump');
                    break;
            }
        }

        return $count;
    }
}
