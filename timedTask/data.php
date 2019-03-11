<?php 
include '/usr/local/nginx/html/common/config.php';
include '/usr/local/nginx/html/common/db.php';
//此文件用于跑定时脚本记录一些数据
class Data{
	private static $db='';
	public function __construct($db){
		self::$db = $db;
	}
	//更新最多的在线用户数，以及日期
	public function max_online(){
		//获取当天的在线用户
		$sql = "select * from statistics_logonAndLogout where type=1";
		$res = self::$db->getAll($sql);
		$user = [];
		foreach($res as $k=>$v){
			$user[] = $res[$k]['userID'];
		}
		//var_dump($user);	
		//去重
		$user = array_unique($user);
		$count = count($user);
		//var_dump($user);
		//$count = 5;
		//读取文件内最新的值，进行对比
		$str = file_get_contents('/usr/local/nginx/html/timedTask/data.txt');
		$new = explode('|',$str);
		if($count > $new[0]){
			$time = date('Y-m-d',time());
			//更新
			$fh = fopen('/usr/local/nginx/html/timedTask/data.txt','w');
			fwrite($fh,$count.'|'.$time);
			fclose($fh);
		}
	}


	//记录每天的活跃用户 ，流失用户数 ，以及时间戳
	public function active_loss_record(){
		$time = time();
		$beginTime = $time - 7*24*3600;
		//获取7天之内所有登录过的用户
		$sql = "SELECT * FROM statistics_logonAndLogout WHERE type=1 AND time BETWEEN $beginTime AND $time";
		$res = self::$db->getAll($sql);
		$active_user = [];
		foreach($res as $k=>$v){
			$active_user[] = $res[$k]['userID'];
		}
		$active_user = array_unique($active_user);
		//获取所有用户数量
		$sql = "select * from userInfo where isVirtual=0";
		$user = self::$db->getAll($sql);
		$loss_count = count($user)-count($active_user);
		$active_count = count($active_user);
		$fh = fopen('/usr/local/nginx/html/timedTask/active_loss_record.txt','a+');
		fwrite($fh,$active_count.'|'.$loss_count.'|'.$time.',');
		fclose($fh);
	}
}
$db = new db();
$data = new Data($db);
$data->max_online();
$data->active_loss_record();
?>
