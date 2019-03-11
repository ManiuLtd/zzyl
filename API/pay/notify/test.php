<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting();
include '../../../common/config.php';
include '../../../common/db.php';
include '../../../common/function.php';
include '../../SendFunction.class.php';
include '../../Socket.class.php';
include '../../GetRedis.class.php';
$db = new db();
$socket = Socket::get();
$send = new SendFunction();
/* $sql = "select max(id)+1 as id from web_notice";
            $id = $db -> getRow($sql);
	    var_dump($id);
            $fh = fopen('test.txt','w');
	    fwrite($fh,$id['id']);
	    fclose($fh);
            $beginTime = time()+60;
	    $endTime = time()+200;
            /*$noticePacket = $send -> makeNoticePacket($id['id'],'测试内容',1,5,$beginTime,$endTime()+100,0);
            $res = $socket->send($send::NoticeID,1,0,$noticePacket);

	if(!$res){

                echo '发布失败,给服务器发送消息失败';die();
            }
	var_dump($res);
            $read = unpack('i*',$socket->read_data(1024));
            if(!$read){
                echo '发布失败，服务器未响应';die;
            }
echo $read[4];
            if($read[4] != 0){
                echo '发布失败，服务器响应错误码为'.$read[4];die;
            }*/

$sql = "select max(id)+1 as id from web_notice";
            $id = $db -> getRow($sql);
            $beginTime = time()+60;
            $endTime = time()+200;
            $interval = 1;
            $times = 5;
            $content = '测试';
            $noticePacket = $send -> makeNoticePacket((int)$id['id'],$content,$interval,$times,$beginTime,$endTime,0);
            $res = $socket->send($send::NoticeID,1,0,$noticePacket);
            //数据库记录
            $sql ="insert into web_notice(id,content,interval,times,beginTime,endTime,type,isNew)values(".$id['id'].",'".$content."',".$interval.",".$times.",".$beginTime.",".$endTime.",0,1)";
            $db->add($sql);






