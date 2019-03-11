<?php
//胜局榜数据
$sql = "select TOP 10 userID,account,name,headURL,wincount from userInfo where winCount > 0 and  isVirtual=0  and name!='棋牌游戏开发' and userID !=7082 order by wincount DESC,userID";
$user = $db -> getAll($sql);
if(!$user){
    $data = array('status'=>1,'msg'=>'请求失败');
    $data['Victory'] = array('code'=>false);
    exit(return_json($data));
}
foreach($user as $k => $v){
  $user[$k]['userID'] = intval($user[$k]['userID']);
  $user[$k]['wincount'] = intval($user[$k]['wincount']);
}
$data = array('status'=>1,'code'=>true,'msg'=>'请求成功');
$data['Victory'] = array('code'=>true,'info'=>$user);
exit(return_json($data));
 ?>
