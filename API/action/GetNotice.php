<?php 
if(isset($_REQUEST['num']) && !empty($_REQUEST['num'])){
	$num = $_REQUEST['num'];
	$sql = "select top ".$num."content from web_notice where special_types = 0 order by id desc";
	//echo $sql;exit();
	$notice = $db->getAll($sql);
	//var_dump($notice);exit();
	$i = 0;
	$res = [];
	foreach ($notice as $key => $value) {
		$res[$i]	=	$notice[$key]['content'];
		$i++;
	}
	$data = ['status'=>1,'msg'=>'请求成功','GetNotice'=>$res];
	exit(return_json($data));
}
$data =['status'=>0,'msg'=>'请传递条数'];
exit(return_json($data));
 ?>
