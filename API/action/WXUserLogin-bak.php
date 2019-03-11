<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
if (isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['unionID']) && !empty($_REQUEST['unionID'])) {
    $userID  = $_REQUEST['userID']; // 用户
    $unionID = $_REQUEST['unionID']; // 用户唯一id

    //获取礼品
    $sql    = "select * from web_gameconfig";
    $config = $db->getAll($sql);
    $c      = array();
    foreach ($config as $key => $value) {
        $c[$config[$key]['key']] = $config[$key]['value'];
    }

    //给服务端发送消息
    $socket = Socket::get();
    $send   = new SendFunction();
    if ($socket->connet == false) {
        $arr = array('status' => 0, 'msg' => '请求失败，服务未启动');
        exit(return_json($arr));
    }

    // $send_jewels = (int)$c['bind_agentid_send_jewels'];
    $send_money       = (int) $c['bind_agentid_send_money']; // 玩家绑定代理 赠送玩家金币
    $send_agent_money = (int) $c['send_agent']; // 代理邀请玩家绑定 赠送自己金币数

    $send_user_money       = (int) $c['send_user']; // 玩家邀请玩家 被邀请者
    $send_agent_user_money = (int) $c['send_agent_user']; // 玩家邀请玩家 邀请者

    // 查询分享表 是否点击分享二维码

    $sql      = "select * from web_share_code where unionid='$unionID' order by id desc";
    $sharCode = $db->getRow($sql);
    if (!$sharCode) {
        $sql = "insert into web_share_code values('$unionID',0,0,0,1,{$userID})";
        $res = $db->getRow($sql);
    }

    if ($sharCode && $sharCode['status'] == 0) {

        if ($sharCode['agentid'] == 0) {
            // 没有邀请码 玩家邀请玩家
            // 发送奖励
            $packet1 = $send->makeSendAgent($userID, 1, $send_agent_user_money); // 邀请的玩家
            $packet2 = $send->makeSendAgent($sharCode['agent_userid'], 1, $send_user_money); // 被邀请的玩家
            $res1    = $socket->send($send::AgentID, 1, 0, $packet1);
            $res2    = $socket->send($send::AgentID, 1, 0, $packet2);
            if (!$res2) {
                $arr = array('status' => 0, 'msg' => '请求失败，与服务器通信出现错误');
                exit(return_json($arr));
            }
            $read = unpack('i*', $socket->read_data(1024));
            if (!$read) {
                $arr = array('status' => 0, 'msg' => '请求失败，接收服务器消息失败');
                exit(return_json($arr));
            }
            if ($read[4] != 0) {
                $arr = array('status' => 0, 'msg' => '请求失败,错误码' . $read[4]);
                exit(return_json($arr));
            }
            //$db1 = db::getInstance();
            $sql = "update web_share_code set status=1,userid={$userID} where id={$sharCode['id']}";
            $res  = $db->getRow($sql);
            // 进行统计
            $sql1 = "insert into web_codeInvitation values({$userID},".time().",{$send_user_money},{$sharCode['agent_userid']},0)"; 
            $sql2 = "insert into web_codeInvitation values({$sharCode['agent_userid']},".time().",{$send_agent_user_money},0,0)" ;
            $db->getRow($sql1);
            $db->getRow($sql2);
            $arr = array('status' => 1, 'msg' => '邀请成功');
            exit(return_json($arr));

        }

        // 代理邀请玩家
        $sql    = "select * from web_agentmember where agentid={$sharCode['agentid']}";
        $member = $db->getRow($sql);
        $redis  = GetRedis::get();
        if ($redis->connect == false) {
            $arr = array('status' => 0, 'msg' => '缓存服务器未启动');
            exit(return_json($arr));
        }
        $user = $redis->redis->hgetall('userInfo|' . $userID);
        if (!$user) {
            $arr = array('status' => 0, 'msg' => '用户不存在');
            exit(return_json($arr));
        }

        //判断是否绑定过
        $sql = "select * from web_bindagentid where userID=$userID";
        $res = $db->getRow($sql);
        if ($res) {
            $arr = array('status' => 0, 'msg' => '您已经绑定过了');
            exit(return_json($arr));
        }

        if (!$member) {
            $arr = array('status' => 0, 'msg' => '邀请码不存在');
            exit(return_json($arr));
        }
        //判断他绑定的是不是自己
        if ($member['userid'] == $userID) {
            $arr = array('status' => 0, 'msg' => '您不能绑定您自己的邀请码');
            exit(return_json($arr));
        }
        //获取我的信息
        $sql = "select * from web_agentmember where userid=" . $userID;
        $my  = $db->getRow($sql);
        function get_my_member($agentid, $db)
        {
            $arr = [];
            //获取我的所有二级代理的agentid
            $sql        = "select agentid from web_agentmember where superior_agentid='$agentid'";
            $l1_agnetid = $db->getAll($sql);
            //遍历获取我的所有三级代理
            foreach ($l1_agnetid as $k => $v) {
                $arr[]      = $l1_agnetid[$k]['agentid'];
                $sql        = "select agentid from web_agentmember where superior_agentid='" . $l1_agnetid[$k]['agentid'] . "'";
                $l2_agentid = $db->getAll($sql);
                foreach ($l2_agentid as $key => $value) {
                    $arr[] = $l2_agentid[$k]['agentid'];
                }
            }
            return $arr;
        }
        if ($my) {
            if ($my['superior_agentid']) {
                $arr = array('status' => 0, 'msg' => '您已经绑定过了');
                exit(return_json($arr));
            }
            //下两级代理不能绑定
            $arr = get_my_member($my['agentid'], $db);
            if (in_array($agentID, $arr)) {
                $arr = array('status' => 0, 'msg' => '您不能绑定您自己的三级代理');
                exit(return_json($arr));
            }
        }
        $user['name'] = iconv('GB2312', 'UTF-8', $user['name']);
        //通过验证给玩家绑定代理号并且赠送礼品
        $sql = "insert into web_bindagentid(userID,agentID,agentname,bind_time,username)values(" . $userID . "," . $sharCode['agentid'] . ",'" . $member['username'] . "'," . time() . ",'" . $user['name'] . "')";
        $res = $db->add($sql);

        if (!$res) {
            $arr = array('status' => 0, 'msg' => '绑定失败，插入数据时发送错误');
            exit(return_json($arr));
        }

        // 同时更新状态
        $usql = "update web_share_code set status=1,userid=$userID where id={$sharCode['id']}";
        $res  = $db->getRow($usql);
        /*    if($res === false){
        $arr = array('status'=>0,'msg'=>'绑定成功,更新状态失败');
        exit(return_json($arr));
        }*/
        //发送消息失败时回滚绑定
        function leave_agentid($userID, $db)
        {
            $sql = "delete from web_bindagentid where userID=$userID";
            $db->add($sql);
        }
	
        $packet1 = $send->makeSendAgent($userID, 1, $send_money); // 赠送给玩家
        $packet2 = $send->makeSendAgent($sharCode['agent_userid'], 1, $send_agent_money); // 赠给上级代理
        $res1    = $socket->send($send::AgentID, 1, 0, $packet1);
        $res2    = $socket->send($send::AgentID, 1, 0, $packet2);
        // var_dump($res1);
        // var_dump($res2);
	 if (!$res2) {
            leave_agentid($userID, $db);
            $arr = array('status' => 0, 'msg' => '请求失败，与服务器通信出现错误');
            exit(return_json($arr));
        }
        $read = unpack('i*', $socket->read_data(1024));
        // var_dump($read);die;
        if (!$read) {
            leave_agentid($userID, $db);
            $arr = array('status' => 0, 'msg' => '请求失败，接收服务器消息失败');
            exit(return_json($arr));
        }
        if ($read[4] != 0) {
            leave_agentid($userID, $db);
            $arr = array('status' => 0, 'msg' => '请求失败,错误码' . $read[4]);
            exit(return_json($arr));
        }
        //同时对代理进行绑定
        if ($my) {
            if (!$my['superior_agentid']) {
                if($member['agentid'] != $sharCode['agentid']) {
                    $sql = "update web_agentmember set superior_agentid='" . $member['agentid'] . "',superior_username='" . $member['username'] . "' where username='" . $my['username'] . "'";
                    $db->add($sql);
                }
            }
        }

        $sql1 = "insert into web_codeInvitation values({$userID},".time().",{$send_user_money},{$sharCode['agent_userid']},1)"; 
        $sql2 = "insert into web_codeInvitation values({$sharCode['agent_userid']},".time().",{$send_agent_user_money},0,1)" ;
        $db->getRow($sql1);
        $db->getRow($sql2);
        //返回客户端
        $arr = array('status' => 1, 'msg' => '绑定成功');
        exit(return_json($arr));
    } else {
        $arr = [
            'status' => 0,
            'msg'    => '未点击过邀请链接,或已绑定过',
        ];
        exit(return_json($arr));
    }
} else {

    $arr = [
        'status' => 0,
        'msg'    => '传递参数不完整',
    ];
    exit(return_json($arr));

}
