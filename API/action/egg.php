<?php 
//砸金蛋
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
	$userid = $_REQUEST['userID'];
//验证用户是否存在
	$redis = GetRedis::get();
	//redis服务未启动，输出页面服务器未启动，请稍后再试~
	if($redis->connect == false){
		$error = '服务器发生错误，请稍后再来吧~';
	    header('Location:./egg/error.php?error='.$error);
	  }
	$user =  $redis->redis->hgetall('userInfo|'.$userid);
	//用户不存在，输出页面，请求失败，用户验证失败
	if(!$user){
		$error = '请求失败，用户验证失败';
	    header('Location:./egg/error.php?error='.$error);
	}
	//查询今天用户剩余抽奖机会
	$beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
  	$endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
	$sql = "select * from web_adminEggDraw where draw_time > ".$beginToday." and draw_time < ".$endToday." and userid=".$userid;
	$record = $db -> getAll($sql);
	$count = count($record);
	$count = 5 - $count;
	//验证通过后输出抽奖界面
	require('./egg/draw.php');
	exit();
}
$error = '请求失败，用户验证失败！2';
header('Location:./egg/error.php?error='.$error);
?>