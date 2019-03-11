<?php 
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
	$userID = $_REQUEST['userID'];
	$sql = "select * from web_bindagentid where userID=$userID";
	$user = $db -> getRow($sql);
	//获取礼品信息
	$sql = "select * from web_gameconfig";
  	$config = $db -> getAll($sql);
	$c = array();
	foreach($config as $key => $value){
	    $c[$config[$key]['key']] = $config[$key]['value'];
	}
	$send_jewels = (int)$c['bind_agentid_send_jewels'];
	$send_money = (int)$c['bind_agentid_send_money'];
	if(!$user){
		$array = [
			'agentid'	=>	'',
			'jewels'	=>	$send_jewels,
			'money'	=>	$send_money,
		];
		$arr = [
			'status'	=> 1,
			'msg'		=> '未绑定',
			'getInviteCodeInfo'	=> $array,
		];
		exit(return_json($arr));
	}else{
		$array = [
			'agentid'	=>	$user['agentID'],
			'jewels'	=>	$send_jewels,
			'money'	=>	$send_money,
		];
		$arr = [
			'status'	=> 1,
			'msg'		=> '已绑定',
			'getInviteCodeInfo'	=> $array,
		];
		exit(return_json($arr));
	}
}
$arr = [
	'status'	=> 0,
	'msg'		=> '未传递参数',
];
exit(return_json($arr));
?>