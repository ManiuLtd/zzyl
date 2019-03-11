<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID'])){
	$gameID = intval(m_request('gameID',0));
	$sql = "SELECT * FROM privateDeskConfig WHERE gameID=".$gameID;
	$gameInfo = $db -> getAll($sql);
	if(!$gameInfo){
		$data['status'] = 0;
		$data['msg'] = '传递参数错误';
		exit(return_json($data));
	}
	foreach($gameInfo as $k => $v){
		$gameInfo[$k]['gameID'] = intval($gameInfo[$k]['gameID']);
		$gameInfo[$k]['jewels'] = intval($gameInfo[$k]['jewels']);
		$gameInfo[$k]['count'] = intval($gameInfo[$k]['count']);
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['privateDeskConfig'] = $gameInfo;
	exit(return_json($data)); 
}
$data['status'] = 0; 
$data['msg'] = '未传递参数'; 
exit(return_json($data));
 ?>
