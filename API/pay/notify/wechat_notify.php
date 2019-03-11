<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting();
include '../../../common/config.php';
include '../../../common/function.php';
include '../../../common/db.php';
include '../../SendFunction.class.php';
include '../../Socket.class.php';
include '../../GetRedis.class.php';
$db = new db();
//接收回调信息
$data = $_REQUEST;
if (!isset($data['agent_bill_id'])) {
    echo 'error';
}
$order_sn = $data['agent_bill_id'];
$sql      = "SELECT * FROM web_orders WHERE order_sn='$order_sn'";
$order    = $db->getRow($sql);
if ($order && $order['status'] == 0 && $data['result'] == 1) {
    $socket   = Socket::get();
    $send     = new SendFunction();
    $userID   = intval($order['userID']);
    $amount   = intval($order['buyNum'] + $order['sendNum']);
    $type     = $order['buyType'];
    $pay_desc = '支付成功，但资源交易失败。';
    $sql      = "UPDATE web_orders SET status=3,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
    if ($socket->connet == false) {
        $db->add($sql);
        echo 'ok';exit();
    }
    $rechargePacket = $send->makeRechargePacket($userID, $amount, $type);
    $res            = $socket->send($send::RechargeID, 1, 0, $rechargePacket);
    if (!$res) {
        $db->add($sql);
        echo 'ok';exit();
    }
    $read = unpack('i*', $socket->read_data(1024));
    if (!$read || $read[4] != 0) {
        $db->add($sql);
        echo 'ok';exit();
    }
    //修改订单状态为支付成功
    $pay_desc = '支付成功,数据处理成功';
    $sql      = "UPDATE web_orders SET status=1,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
    $res      = $db->add($sql);
    if ($res) {
        commission($order['userID'], $order['name'], $order['consumeNum'], $db, $order['buyType'], $amount);
	pay_success($order_sn);
        $data['pay_result'] = 1;
        echo 'ok';
        exit();
    }
}
//分佣
function commission($userid, $name, $amount, $db, $buyType, $buyNum)
{
    //获取代理分成配置
    $config = get_agent_config($db);
    //判断用户是属于代理用户还是普通用户
    $sql   = "select * from web_agentmember where userid=" . $userid;
    $agent = $db->getRow($sql);
    $_desc = '三级代理返佣';
    if ($agent) {
        //计算出三级各得佣金
        $c1    = $amount * $config['agent_recharge_level' . $agent['agent_level'] . '_ratio'] / 100;
        $c2    = $amount * $config['recharge_ratio2'] / 100;
        $c3    = $amount * $config['recharge_ratio3'] / 100;
        $level = get_agent_level($agent, $db);
        //判断各级是否存在进行分佣
        if ($level['level1']) {
            $sql = "update web_agentmember set balance=balance+" . $c1 . " where id=" . $level['level1']['id'];
            $db->add($sql);
            //加直属收入
            $sql = "update web_agentmember set under_money=under_money+" . $c1 . " where id=" . $level['level1']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level1']['username'], $level['level1']['agent_level'], $level['level1']['balance'], $c1, $level['level1']['balance'] + $c1, $_desc, time(), $userid, $name, $amount, $c1, 0, 0, $db);
        }
        if ($level['level2']) {
            $sql = "update web_agentmember set balance=balance+" . $c2 . " where id=" . $level['level2']['id'];
            $db->add($sql);
            $sql = "update web_agentmember set not_under_money=not_under_money+" . $c2 . " where id=" . $level['level2']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level2']['username'], $level['level2']['agent_level'], $level['level2']['balance'], $c2, $level['level2']['balance'] + $c2, $_desc, time(), $userid, $name, 0, 0, $amount, $c2, $db);
            $level2_username          = $level['level2']['username'];
            $level2_member_commission = $c2;
            $level2_userid            = $level['level2']['userid'];
        }
        if ($level['level3']) {
            $sql = "update web_agentmember set balance=balance+" . $c3 . " where id=" . $level['level3']['id'];
            $db->add($sql);
            $sql = "update web_agentmember set not_under_money=not_under_money+" . $c3 . " where id=" . $level['level3']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level3']['username'], $level['level3']['agent_level'], $level['level3']['balance'], $c3, $level['level3']['balance'] + $c3, $_desc, time(), $userid, $name, 0, 0, $amount, $c3, $db);
            $level3_member_username   = $level['level3']['username'];
            $level3_member_commission = $c3;
            $level3_userid            = $level['level3']['userid'];
        }
        if (!$level['level2']) {
            $level2_username          = '';
            $level2_member_commission = 0;
            $level2_userid            = 0;}
        if (!$level['level3']) {
            $level3_member_username   = '';
            $level3_member_commission = 0;
            $level3_userid            = 0;}
        //用户类型
        commission_record($userid, $name, $agent['agent_level'], $amount, $agent['agentid'], $agent['username'], $agent['agent_level'], $c1, $level2_username, $level2_member_commission, $level3_member_username, $level3_member_commission, time(), 1, $userid, $level2_userid, $level3_userid, $db, $buyType, $buyNum);
    } else {
        $level = get_user_level($userid, $db);
        //计算出三级各得佣金
        //判断各级是否存在进行分佣
        if ($level['level1']) {
            $c1  = $amount * $config['user_recharge_level' . $level['level1']['agent_level'] . '_ratio'] / 100;
            $sql = "update web_agentmember set balance=balance+" . $c1 . " where id=" . $level['level1']['id'];
            $db->add($sql);
            $sql = "update web_agentmember set under_money=under_money+" . $c1 . " where id=" . $level['level1']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level1']['username'], $level['level1']['agent_level'], $level['level1']['balance'], $c1, $level['level1']['balance'] + $c1, $_desc, time(), $userid, $name, $amount, $c1, 0, 0, $db);
            $bind_agentid           = $level['level1']['agentid'];
            $bind_username          = $level['level1']['username'];
            $bind_type              = $level['level1']['agent_level'];
            $bind_member_commission = $c1;
            $bind_userid            = $level['level1']['userid'];
        }
        if ($level['level2']) {
            $c2  = $amount * $config['recharge_ratio2'] / 100;
            $sql = "update web_agentmember set balance=balance+" . $c2 . " where id=" . $level['level2']['id'];
            $db->add($sql);
            $sql = "update web_agentmember set not_under_money=not_under_money+" . $c2 . " where id=" . $level['level2']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level2']['username'], $level['level2']['agent_level'], $level['level2']['balance'], $c2, $level['level2']['balance'] + $c2, $_desc, time(), $userid, $name, 0, 0, $amount, $c2, $db);
            $level2_username          = $level['level2']['username'];
            $level2_member_commission = $c2;
            $level2_userid            = $level['level2']['userid'];
        }
        if ($level['level3']) {
            $c3  = $amount * $config['recharge_ratio3'] / 100;
            $sql = "update web_agentmember set balance=balance+" . $c3 . " where id=" . $level['level3']['id'];
            $db->add($sql);
            $sql = "update web_agentmember set not_under_money=not_under_money+" . $c3 . " where id=" . $level['level3']['id'];
            $db->add($sql);
            //生成该账单
            make_bill_detail($level['level3']['username'], $level['level3']['agent_level'], $level['level3']['balance'], $c3, $level['level3']['balance'] + $c3, $_desc, time(), $userid, $name, 0, 0, $amount, $c3, $db);
            $level3_member_username   = $level['level3']['username'];
            $level3_member_commission = $c3;
            $level3_userid            = $level['level3']['userid'];
        }
        if (!$level['level1']) {
            $bind_agentid           = '';
            $bind_username          = '';
            $bind_type              = 4;
            $bind_member_commission = 0;
            $bind_userid            = 0;
        }
        if (!$level['level2']) {
            $level2_username          = '';
            $level2_member_commission = 0;
            $level2_userid            = 0;
        }
        if (!$level['level3']) {
            $level3_member_username   = '';
            $level3_member_commission = 0;
            $level3_userid            = 0;
        }
        //用户类型
        $user_type = 0;
        commission_record($userid, $name, $user_type, $amount, $bind_agentid, $bind_username, $bind_type, $bind_member_commission, $level2_username, $level2_member_commission, $level3_member_username, $level3_member_commission, time(), 1, $bind_userid, $level2_userid, $level3_userid, $db, $buyType, $buyNum);
    }
}

//分佣记录
function commission_record($recharge_userid, $recharge_name, $user_type, $recharge_amount, $bind_agentid, $bind_username, $bind_type, $bind_member_commission, $level2_username, $level2_member_commission, $level3_member_username, $level3_member_commission, $recharge_time, $status, $bind_userid, $level2_userid, $level3_userid, $db, $buyType, $buyNum)
{
    $sql = "insert into web_rechargecommission (recharge_userid,recharge_name,user_type,recharge_amount,bind_agentid,bind_username,bind_type,bind_member_commission,level2_username,level2_member_commission,level3_member_username,level3_member_commission,recharge_time,status,bind_userid,level2_userid,level3_userid,buy_type,buy_num)values($recharge_userid,'$recharge_name',$user_type,$recharge_amount,'$bind_agentid','$bind_username',$bind_type,$bind_member_commission,'$level2_username',$level2_member_commission,'$level3_member_username',$level3_member_commission,$recharge_time,$status,$bind_userid,$level2_userid,$level3_userid,$buyType,$buyNum)";
    $db->add($sql);
}

//获取普通用户的三层级关系
function get_user_level($userid, $db)
{
    $level = [];
    //获取直属用户
    $sql        = "select  * from web_bindagentid where userID=" . $userid;
    $level1     = $db->getRow($sql);
    $agentname1 = $level1['agentname'];
    $sql        = "select * from web_agentmember where username='$agentname1'";
    $level1     = $db->getRow($sql);
    if (!$level1) {
        $level['level1'] = false;
        $level['level2'] = false;
        $level['level3'] = false;
        return $level;
    } else {
        if (!$level1['superior_username']) {
            $level['level1'] = $level1;
            $level['level2'] = false;
            $level['level3'] = false;
            return $level;
        } else {
            $username = $level1['superior_username'];
            $sql      = "select * from web_agentmember where username='$username'";
            $level2   = $db->getRow($sql);
            if (!$level2['superior_username']) {
                $level['level1'] = $level1;
                $level['level2'] = $level2;
                $level['level3'] = false;
                return $level;
            } else {
                $username        = $level2['superior_username'];
                $sql             = "select * from web_agentmember where username='$username'";
                $level3          = $db->getRow($sql);
                $level['level1'] = $level1;
                $level['level2'] = $level2;
                $level['level3'] = $level3;
                return $level;
            }
        }
    }
}
//获取代理用户的三层级关系
function get_agent_level($agent, $db)
{
    $level = [];
    if (!$agent['superior_username']) {
        $level['level1'] = $agent;
        $level['level2'] = false;
        $level['level3'] = false;
        return $level;
    } else {
        $username = $agent['superior_username'];
        $sql      = "select * from web_agentmember where username='$username'";
        $level2   = $db->getRow($sql);
        if (!$level2['superior_username']) {
            $level['level1'] = $agent;
            $level['level2'] = $level2;
            $level['level3'] = false;
            return $level;
        } else {
            $username        = $level2['superior_username'];
            $sql             = "select * from web_agentmember where username='$username'";
            $level3          = $db->getRow($sql);
            $level['level1'] = $agent;
            $level['level2'] = $level2;
            $level['level3'] = $level3;
            return $level;
        }
    }

}
//生成账单
function make_bill_detail($username, $agent_level, $front_balance, $handle_money, $after_balance, $_desc, $make_time, $make_userid, $make_name, $amount, $commission, $under_amount, $under_commission, $db)
{
    $sql = "insert into web_billdetail(username,agent_level,front_balance,handle_money,after_balance,_desc,make_time,make_userid,make_name,amount,commission,under_amount,under_commission)values('$username',$agent_level,$front_balance,$handle_money,$after_balance,'$_desc',$make_time,$make_userid,'$make_name',$amount,$commission,$under_amount,$under_commission)";
    $db->add($sql);
}

//订单统一处理
function order_handle($db, $status, $pay_desc, $order_sn)
{
    $sql = "UPDATE web_orders SET status=$status,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
    $db->add($sql);
    echo 'error';exit();
}

//获取代理分成配置
function get_agent_config($db)
{
    $sql    = "select * from web_agentconfig";
    $c      = $db->getAll($sql);
    $config = [];
    foreach ($c as $k => $v) {
        $config[$c[$k]['key']] = $c[$k]['value'];
    }
    return $config;
}
