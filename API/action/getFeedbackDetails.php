<?php 
if(isset($_REQUEST['c_id']) && !empty($_REQUEST['c_id'])){
	$c_id = $_REQUEST['c_id'];
	$sql ="select * from web_adminfeedback where id=".$c_id;
	$f = $db -> getRow($sql);
	if(!$f){
		$data = ['status'=>0,'msg'=>'反馈ID错误'];
		exit(return_json($data));
	}
	//用户回复
	$sql = "select * from web_adminfeedbackcallback where c_id=".$c_id." and c_type=2";
	$user = $db -> getAll($sql);
	//系统回复列表
	$sql = "select * from web_adminfeedbackcallback where c_id=".$c_id." and c_type=1";
	$system = $db -> getAll($sql);
	$msg = array_merge ($user,$system); 
	$sort = array(    
            'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序    
            'field'     => 'c_time',       //排序字段    
    );    
    $arrSort = array();    
    foreach($msg AS $uniqid => $row){    
        foreach($row AS $key=>$value){    
            $arrSort[$key][$uniqid] = $value;    
        }    
    }    
    if($sort['direction']){    
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $msg);    
    }  
    //回复成功状态改为已读
    if($f['read_type'] != 3){
    $sql = "update web_adminfeedback set read_type=2 where id=".$c_id;
    $db -> add($sql);    
    }
foreach($msg as $k => $v){
	$msg[$k]['c_type'] = (int)$msg[$k]['c_type'];
	$msg[$k]['c_time'] = (int)$msg[$k]['c_time'];
	unset($msg[$k]['c_id']);
}

	$data = ['status'=>1,'msg'=>'请求成功','getFeedbackDetails'=>$msg];
	exit(return_json($data));
}
$data = ['status'=>0,'msg'=>'参数不正确'];
exit(return_json($data));
?>
