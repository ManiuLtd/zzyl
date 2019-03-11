<?php 
if(isset($_REQUEST['c_id']) && !empty($_REQUEST['c_id']) && isset($_REQUEST['c_content']) && !empty($_REQUEST['c_content'])){
	$userid = $_REQUEST['userid'];
	$c_id = $_REQUEST['c_id'];
	$c_content = $_REQUEST['c_content'];
    //判断该条反馈是否结束
    $sql ="select * from web_adminfeedback where id=".$c_id;
    $f = $db ->getRow($sql);
    if(!$f){
    	$data = array('status'=>0,'msg'=>'反馈id不存在');
      	exit(return_json($data));
    }
    if($f['read_type'] == 3){
    	$data = array('status'=>0,'msg'=>'该条反馈已经结束');
      	exit(return_json($data));
    }
    //回复成功状态改为已读
    $sql = "update web_adminfeedback set read_type=2 where id=".$c_id;
    $db -> add($sql);
    //插入回复表
    $c_time = time();
    $c_type = 2;
    $sql = "insert into web_adminfeedbackcallback(c_content,c_id,c_type,c_time)values('".$c_content."',".$c_id.",".$c_type.",".$c_time.")";
    $res = $db -> add($sql);
    if(!$res){
    	$data = array('status'=>0,'msg'=>'回复失败');
      	exit(return_json($data));
    }
    //修改是否有回复为有  has_back = 1;
    $sql = "update web_adminfeedback set has_back=1 where id=".$c_id;
    $db -> add($sql);
    $data = array('status'=>1,'msg'=>'回复成功');
    exit(return_json($data));
}
$data = ['status'=>0,'msg'=>'参数不正确'];
exit(return_json($data));
?>
