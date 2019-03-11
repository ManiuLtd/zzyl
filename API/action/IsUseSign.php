<?php
//判断今天是否已经签到过
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
  $userid = intval($_REQUEST['userID']);
   $sql = 'SELECT * FROM web_signRecord WHERE userID='.$userid.' order by id desc';
   $record = $db -> getRow($sql);
  //从未签到过，当然可以签到
  if(!$record){
    $data = array('status'=>1,'msg'=>'可以签到');
    $data['IsUseSign'] = array('code'=>true);
    exit(return_json($data));
  }
  //如果上一次签到时间在今天内则不能再次签到
  $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
	$endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
  //上一次抽奖时间在今天所以不能抽奖
  if($record['signTime'] > $beginToday && $record['signTime'] < $endToday){
    $data = array('status'=>1,'msg'=>'已经签到过');
    $data['IsUseSign'] = array('code'=>false);
    exit(return_json($data));
  }
  $data = array('status'=>1,'msg'=>'可以签到');
  $data['IsUseSign'] = array('code'=>true);
  exit(return_json($data));
}
$data = array('status'=>0,'msg'=>'未传入userid参数');
exit(return_json($data));
 ?>
