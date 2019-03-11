<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID'])){
	$gameID = intval(m_request('gameID',0));
	$sql = "SELECT * FROM gameBaseInfo WHERE gameID=".$gameID;
	$gameInfo = $db -> getRow($sql);
	if(!$gameInfo){
		$data['status'] = 0;
		$data['msg'] = '传递参数错误';
		exit(return_json($data));
	}
	foreach($gameInfo as $k => $v){
		$gameInfo['gameID'] = intval($gameInfo['gameID']);
		$gameInfo['deskPeople'] = intval($gameInfo['deskPeople']);
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['GetGameInfo'] = $gameInfo;
	exit(return_json($data)); 
}else{
	$sql = "SELECT * FROM gameBaseInfo";

	$gameInfo = $db -> getAll($sql);

	foreach($gameInfo as $k => $v){
		$gameInfo[$k]['gameID'] = intval($gameInfo[$k]['gameID']);
		$gameInfo[$k]['deskPeople'] = intval($gameInfo[$k]['deskPeople']);
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['GetGameInfo'] = $gameInfo;
	exit(return_json($data)); 
}
 ?>