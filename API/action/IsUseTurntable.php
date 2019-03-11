<?php
//是否可以进行大转盘抽奖
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
  //查询用户上一次的抽奖时间判断
  $userid = intval($_REQUEST['userID']);
  $sql = 'SELECT TOP 1 turntableTime FROM web_turntableRecord WHERE userID='.$userid.' ORDER BY id DESC';
  $record = $db -> getRow($sql);
  $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
	$endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
  //为空说明是第一次抽奖
  if(empty($record)){
    $data = array('status'=>1,'msg'=>'可以抽奖');
    $data['IsUseTurntable'] = array('code'=>true);
    exit(return_json($data));
  }
  //上一次抽奖时间在今天所以不能抽奖
  if($record['turntableTime'] > $beginToday && $record['turntableTime'] < $endToday){
     $data = array('status'=>1,'msg'=>'今天已经抽过一次');
    $data['IsUseTurntable'] = array('code'=>false);
    exit(return_json($data));
  }
  $data = array('status'=>1,'msg'=>'可以抽奖');
    $data['IsUseTurntable'] = array('code'=>true);
  exit(return_json($data));
}
 $data= array('status'=>0,'msg'=>'未传递userid');
exit(return_json($data));
 ?>
