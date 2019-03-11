<?php 
if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
	$id = $_REQUEST['id'];
	$sql = "delete from web_adminfeedback where id=".$id;
	$res = $db -> add($sql);
	if(!$res){
		$arr = ['status'=>0,'msg'=>'删除失败'];
		exit(return_json($arr));
	}
	$sql = "delete from web_adminfeedbackcallback where c_id=".$id;
	$res = $db -> add($sql);
	$arr = ['status'=>1,'msg'=>'删除成功'];
	exit(return_json($arr));
}
$arr = ['status'=>0,'msg'=>'未传递参数id'];
exit(return_json($arr));
?>
