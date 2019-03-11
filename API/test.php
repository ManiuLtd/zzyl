<?php 
header('Content-Type: text/html; charset=utf-8');
include './SendFunction.class.php';
include './Socket.class.php';
$socket = Socket::get();
$send = new SendFunction();
$content = '〖系统〗至尊娱乐棋牌游戏平台目前已上线五人牛牛，划水麻将，斗地主，十三水四款游戏，游戏种类将持续更新，敬请关注。';
$userID = 1001;
$amount = 5;
$type = 1;
//$packet = $send -> makeNoticePacket(50,$content,10,10000,time(),time()+1000000,0);   //包体
//$packet = $send ->sign($userID,1,10);
$packet = $send -> makeRechargePacket(1001.5,1);
	if($socket->connet == false){
		echo '连接服务器失败';die();
	}
	$res = $socket -> send($send::RechargeID,1,0,$packet);
	if(!$res){
		echo '发送失败';
	}
	$read = unpack('i*', $socket->read_data(1024));
    $socket->close();
    if(!$read){
    	echo '接收失败';exit();
    }
    if($read[4] != 0){
    	echo '失败';die();
    }
    echo '发送成功';

/*
    //充值
	function makeRechargePacket($userID,$amount,$type){
		$packet = "";
		$packet.= pack('i',$userID);
		$packet.= pack('i',$amount);
		$packet.= pack('C',$type);
		return $packet;
	}*/
?>
