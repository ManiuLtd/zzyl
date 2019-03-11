<?php
// +----------------------------------------------------------------------
// | 文件说明：俱乐部抽水  
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.wuwuseo.com All rights reserved.--
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2018-月-日
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Think\Controller;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');

class ClubPumpController extends Controller
{

    public function index()
    {
        // 魔法表情,开具消耗
        $map['reason']         = ['in', '15,16'];
        $map['friendsGroupID'] = ['neq', 0];
        $map['money_status']   = 0;
        $time                  = strtotime("-1 day");

        $start       = strtotime(date('Y-m-d', $time));
        $stop        = $start + 3600 * 24 - 1;
        $map['time'] = ['between', [$start, $stop]];

        $data = M()->table('statistics_moneyChange')->where($map)->select();
        if ($data) {
            foreach ($data as $v) {
                // 获取 群主ID
                $userid = M()->table('friendsGroup')->where(['friendsGroupID' => $v['friendsgroupid']])->getfield('masterID');
                // 发送奖励
                $this->sendMoney($userid, abs($v['changemoney']));
                // 记录日志
                $this->writeLog($v['reason'], $v['userid'], abs($v['changemoney']), $v['time'], $v['friendsgroupid'],$v['roomid']);
                // 更新
                M()->table('statistics_moneyChange')->where(['id' => $v['id']])->setfield('money_status', 1);
            }
        }
    }

    // 发送奖励
    public function sendMoney($userID, $money)
    {
        $socket  = \Socket::get();
        $send    = new \SendFunction();
        $packet1 = $send->makeAgentPercent($userID, 1, (int) $money);
        $res1    = $socket->send($send::AgentPercentID, 1, 0, $packet1);
    }

    // 记录
    public function writeLog($reason, $userID, $money, $time, $friendsGroupID, $roomid)
    {
        $data = [
            'reason'         => $reason,
            'userID'         => $userID,
            'moneychange'    => $money,
            'create_time'    => $time,
            'friendsgroupid' => $friendsGroupID,
            'roomid'         => $roomid,
        ];

        M('clubmoney')->add($data);
    }
}
