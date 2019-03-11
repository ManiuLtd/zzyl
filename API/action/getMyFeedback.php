<?php 
if(isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid = $_REQUEST['userid'];
	$sql = "select id,content,type,read_type,f_time from web_adminfeedback where userID=".$userid." order by id desc";
	$getMyFeedback = $db -> getAll($sql);
	foreach($getMyFeedback as $k => $v){	
		$getMyFeedback[$k]['id']	= (int)$getMyFeedback[$k]['id'];
		$getMyFeedback[$k]['type']	= (int)$getMyFeedback[$k]['type'];
		$getMyFeedback[$k]['read_type']	= (int)$getMyFeedback[$k]['read_type'];
		$getMyFeedback[$k]['f_time']	= (int)$getMyFeedback[$k]['f_time'];
	}
	$data = ['status'=>1,'msg'=>'请求成功','getMyFeedback'=>$getMyFeedback];
	exit(return_json($data)); 
}
$data = ['status'=>0,'msg'=>'参数不正确'];
exit(return_json($data));
?>
