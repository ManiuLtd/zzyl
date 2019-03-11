<?php
//获取大转盘数据
$sql = 'select * from web_turntable_config';
$turntable = $db -> getAll($sql);
foreach($turntable as $k => $v){
  $turntable[$k]['id'] = intval($turntable[$k]['id']);
  $turntable[$k]['num'] = intval($turntable[$k]['num']);
  $turntable[$k]['prizeType'] = intval($turntable[$k]['prizeType']);
}
$data['GetTurntable'] = array('code'=>true,'turntable_config'=>$turntable);
$data['status'] = 1;
$data['msg'] = '请求成功';
exit(return_json($data));
?>
