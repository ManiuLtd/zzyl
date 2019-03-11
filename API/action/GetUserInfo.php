<?php 
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
	$userID = intval(m_request('userID',0));
	$sql = "SELECT name,headURL FROM userinfo WHERE userID=".$userID;
	$userInfo = $db -> getRow($sql);
	if(!$userInfo){
		$data['status'] = 0;
		$data['msg'] = '传递参数错误';
		exit(return_json($data));
	}
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['GetUserInfo'] = $userInfo;
	exit(return_json($data)); 
}else{
	$sql = "SELECT name,headURL FROM userinfo";
	$userInfo = $db -> getAll($sql);
	$data['status'] = 1;
	$data['msg'] = '请求成功';
	$data['GetUserInfo'] = $userInfo;
	exit(return_json($data)); 
}
 ?>