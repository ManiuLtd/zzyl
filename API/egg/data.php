<?php
include '../../common/config.php';
include '../../common/db.php';
include '../../common/function.php';
include '../SendFunction.class.php';
include '../Socket.class.php';
$userid = $_GET['userid'];
//写一个概率事件
function get_rand($proArr) { 
    $result = ''; 
    //概率数组的总概率精度 
    $proSum = array_sum($proArr); 
    //概率数组循环 
    foreach ($proArr as $key => $proCur) { 
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) { 
            $result = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 
    } 
    unset ($proArr); 
    return $result; 
}
//奖品数组
$prize_arr = array( 
    '0' => array('id'=>1,'prize'=>'1.5万金币','v'=>10,'num'=>15000), 
    '1' => array('id'=>2,'prize'=>'20张房卡','v'=>5,'num'=>20), 
    '2' => array('id'=>3,'prize'=>'iphone7','v'=>0,'num'=>1), 
    '3' => array('id'=>4,'prize'=>'华为P9','v'=>0,'num'=>1), 
    '4' => array('id'=>5,'prize'=>'小米移动电源','v'=>0,'num'=>1), 
    '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>85,'num'=>0), 
); 
foreach ($prize_arr as $key => $val) { 
    $arr[$val['id']] = $val['v']; 
}
$rid = get_rand($arr); //根据概率获取奖项id 
$prize = $prize_arr[$rid-1]; //中奖项数组
//向服务端发送消息，
$socket = Socket::get();
$send = new SendFunction(); 
$money_type = 1;
$jewels_type = 2;
$money_num = 15000;
$jewels_num = 20;
$cost_money = 13000;
if($socket->connet == false){
	echo '服务器错误，请稍后重试~1';exit();
}
if($prize['id'] == 1){
	$packet = $send -> makeEggPacket($userid,$money_type,$money_num,$cost_money);
}elseif($prize['id'] == 2){
	$packet = $send -> makeEggPacket($userid,$jewels_type,$jewels_num,$cost_money);
}else{
	$packet = $send -> makeEggPacket($userid,0,0,$cost_money);
}
$res = $socket -> send($send::EggID,1,0,$packet);
if(!$res){
	echo '服务器错误，请稍后重试~2';exit();
}
$read = unpack('i*', $socket->read_data(1024));
if(!$read){
	echo '服务器错误，请稍后重试~3';exit();
}
if($read[4] != 0){
	echo '服务器错误，请稍后重试~4';exit();
}
//服务器记录抽奖信息
$db = new db();
$sql = "insert into web_adminEggDraw(userid,prize,num,draw_time)values(".$userid.",'".$prize['prize']."',".$prize['num'].",".time().")";
$res = $db -> add($sql);
if(!$res){
	echo '抽奖失败，服务器错误';exit();
}
echo $prize['prize'];exit();
?>