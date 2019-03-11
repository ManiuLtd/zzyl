<?php 
$socket = Socket::get();
$send = new SendFunction();
$id = 44;
$content = '';
$interval = 10;
$times = 10000;
$beginTime = time();
$endTime = time()+1000000;
$type = 0;
$isNew = 0;
$packet = $send -> makeNoticePacket($id,$content,$interval,$times,$beginTime,$endTime,$type);   //包体
	if($socket->connet == false){
		echo '连接服务器失败';die();
	}
	$res = $socket -> send($send::NoticeID,1,0,$packet);
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
    //插入数据库
    $sql = "insert into web_notice(id,content,interval,times,beginTime,endTime,type,isNew)values($id,'$content',$interval,$times,$beginTime,$endTime,$type,$isNew)";
    $res = $db -> add($sql);
    if($res){
    	echo '插入数据成功';
    }else{
    	echo '插入数据失败';
    }
?>
