<?php
if(isset($_REQUEST['type']) && !empty($_REQUEST['type'])){
  $type = $_REQUEST['type'];
  $arr = array('jewels','money');
  if(!in_array($type,$arr)){
    $data['Vulgar'] = array('status'=>1,'code'=>false,'msg'=>'type参数值错误');
    exit(return_json($data));
  }
  //土豪榜数据
  if($type == 'jewels'){
    $sql = "select TOP 10 userID,account,name,headURL,jewels from userInfo where isVirtual=0 and name!='棋牌游戏开发'  order by jewels DESC,userID desc";
  }elseif($type == 'money'){
    $sql = "select TOP 10 userID,account,name,headURL,money from userInfo where isVirtual=0 and name!='棋牌游戏开发' order by money DESC,userID desc";
  }
  $user = $db -> getAll($sql);
  //var_dump($user);
  if(!$user){
      $data = array('status'=>1,'msg'=>'请求失败');
      $data['Vulgar'] = array('code'=>false);
      exit(return_json($data));
  }
  foreach($user as $k => $v){
    
    $user[$k]['userID'] = intval($user[$k]['userID']);
    if($type == 'jewels'){
    $user[$k]['jewels'] = intval($user[$k]['jewels']);
  }elseif($type == 'money'){
    $user[$k]['money'] = intval($user[$k]['money']);
  }
  }
  $data = array('status'=>1,'msg'=>'请求成功');
  $data['Vulgar'] = array('code'=>true,'Vulgar'=>$user);
  exit(return_json($data));
}
$data = array('status'=>0,'msg'=>'未传递type');
exit(return_json($data));
 ?>
