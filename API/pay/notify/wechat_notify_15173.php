<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting();
include '../../../common/config.php';
include '../../../common/db.php';
include '../../../common/function.php';
include '../../SendFunction.class.php';
include '../../Socket.class.php';
include '../../GetRedis.class.php';
$db =  new db();
//接收回调信息
$data = $_REQUEST;
$fh = fopen('data.txt','w');
fwrite($fh,$_POST['pay_result']);
fclose($fh);
die;
if($data['pay_result'] == ""){
	$fh = fopen('log.txt','w');
	fwrite($fh,$data['sp_billno']);
	fclose($fh);
	//支付成功，向服务器发送消息，修改订单状态
	$order_sn = $data['sp_billno'];
	$sql = "SELECT * FROM web_orders WHERE order_sn='$order_sn'";
	$order = $db -> getRow($sql);
	if(!$order){
		exit();
	}
	$socket = Socket::get();
  	$send = new SendFunction(); 
  	$userID = intval($order['userID']);
  	$amount = intval($order['buyNum']);
  	$type   = intval($order['buyType']);
  	if($socket->connet == false){
		$pay_desc = '支付成功，但资源交易失败。原因：服务器连接失败';
		$sql = "UPDATE web_orders SET status=3,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
		$db -> add($sql);exit();
	}
  	$rechargePacket = $send->makeRechargePacket($userID,$amount,$type);
  	$res = $socket -> send($send::RechargeID,1,0,$rechargePacket);		
		if(!$res){
			$pay_desc = '支付成功，但资源交易失败。原因：发送数据失败';
			$sql = "UPDATE web_orders SET status=3,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
			$db -> add($sql);exit();
		}
		$read = unpack('i*', $socket->read_data(1024));
	    if(!$read){
	    	$pay_desc = '支付成功，但资源交易失败。原因：接收数据失败';
			$sql = "UPDATE web_orders SET status=3,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
			$db -> add($sql);
			exit();
	    }
	    if($read[4] != 0){
	    	$pay_desc = '支付成功，但资源交易失败。原因：与服务器通信失败';
			$sql = "UPDATE web_orders SET status=3,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
			$db -> add($sql);
			exit();
	   }
	    //修改订单状态为支付成功
	    $pay_desc = '支付成功,数据处理成功';
		$sql = "UPDATE web_orders SET status=1,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
	$res = 	$db -> add($sql);
	$fh = fopen('log.txt','w');
        fwrite($fh,$res);
        fclose($fh);

	exit();
}elseif($data['pay_result'] == 3){
	//支付失败，修改订单状态
	$order_sn = $data['sp_billno'];
	$pay_desc = '支付失败';
	$sql = "UPDATE web_orders SET status=2,pay_desc='$pay_desc' WHERE order_sn='$order_sn'";
	$db -> add($sql);exit();
}
?>
