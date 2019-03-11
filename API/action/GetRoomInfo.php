<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID'])){
	$gameID = intval(m_request('gameID',0));
        $sqlly = "SELECT * FROM web_adminmember";
	$sql = "SELECT * FROM roomBaseInfo WHERE gameID=".$gameID;
	$roomInfo = $db -> getAll($sql);
	if(!$roomInfo){
		$data['status'] = 0;
		$data['msg'] = '传递参数错误';
		exit(return_json($data));
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	foreach($roomInfo as $k => $v){
		$roomInfo['roomID'] = intval($roomInfo['roomID']);
		$roomInfo['gameID'] = intval($roomInfo['gameID']);
		$roomInfo['port'] = intval($roomInfo['port']);
		$roomInfo['type'] = intval($roomInfo['type']);
		$roomInfo['deskCount'] = intval($roomInfo['deskCount']);
		$roomInfo['maxPeople'] = intval($roomInfo['maxPeople']);
		$roomInfo['minPoint'] = intval($roomInfo['minPoint']);
		$roomInfo['maxPoint'] = intval($roomInfo['maxPoint']);
	}
	$data['GetRoomInfo'] = $roomInfo;
	exit(return_json($data)); 
}else{
     
	$sql = "SELECT * FROM roomBaseInfo";
	$roomInfo = $db -> getAll($sql);
	$res = array();
	foreach($roomInfo as $k => $v){
		$roomInfo[$k]['roomID'] = intval($roomInfo[$k]['roomID']);
		$roomInfo[$k]['gameID'] = intval($roomInfo[$k]['gameID']);
		$roomInfo[$k]['port'] = intval($roomInfo[$k]['port']);
		$roomInfo[$k]['type'] = intval($roomInfo[$k]['type']);
		$roomInfo[$k]['deskCount'] = intval($roomInfo[$k]['deskCount']);
		$roomInfo[$k]['maxPeople'] = intval($roomInfo[$k]['maxPeople']);
		$roomInfo[$k]['minPoint'] = intval($roomInfo[$k]['minPoint']);
		$roomInfo[$k]['maxPoint'] = intval($roomInfo[$k]['maxPoint']);
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['GetRoomInfo'] = $roomInfo;
	exit(return_json($data)); 
}
 ?>
