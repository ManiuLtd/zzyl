<?php
header("Content-type: text/html; charset=utf-8");
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
//先获取转盘配置信息
$sql = 'select * from web_turntable_config';
$turntable = $db -> getAll($sql);
$arr = array();
foreach($turntable as $v){
  $arr[$v['id']] = $v['chance'];
}
$actor = 100;

foreach($arr as &$v){
  $v = $v*$actor;
}
asort($arr);
$sum = array_sum($arr);
$rand = mt_rand(1,$sum);
$result = ''; //中奖产品id
foreach($arr as $k => $x){
  if($rand <= $x){
    $result = $k;
    break;
  }else{
    $rand -= $x;
  }
}
$userid = intval($_REQUEST['userID']);
$sql = 'SELECT TOP 1 turntableTime FROM web_turntableRecord WHERE userID='.$userid.' ORDER BY id DESC';
$record = $db -> getRow($sql);
$beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
$endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
//上一次抽奖时间在今天所以不能抽奖
if($record['turntableTime'] > $beginToday && $record['turntableTime'] < $endToday){
    $data = array('status'=>1,'msg'=>'今天已经抽过一次');
    $data['Turntable'] = array('code'=>false);
    exit(return_json($data));
}
$socket = Socket::get();
$redis = GetRedis::get();
$send = new SendFunction();
 //直接查缓存用户数据
  if($redis->connet == false){
    $data= array('status'=>1,'msg'=>'缓存服务器未启动');
    $data['Turntable'] = array('code'=>false);
  }
  $user =  $redis->redis->hgetall('userInfo|'.$userid); 
  if(!$user){
    $data = array('status'=>1,'msg'=>'用户不存在');
    $data['Turntable'] = array('code'=>false);
    exit(return_json($data));
  }
$num = $turntable[$result-1]['num'];
$prize = $turntable[$result-1]['prize'];
$prizeType = $turntable[$result-1]['prizeType'];
$prizeType = $turntable[$result-1]['prizeType'];
$encode = mb_detect_encoding($user['name'], array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
$username = trim(iconv('GB2312','UTF-8',$user['name']),"'");
if($prizeType == 0){
    //记录
$sql = "insert into web_turntableRecord(userID,turntableTime,num,prizeType,prize,username)values(".$userid.",".time().",".$num.",".$prizeType.",'".$prize."','".$username."')";
$res = $db -> add($sql);
if(!$res){
  $data = [
    'status'  =>  0,
    'msg'     =>  '失败',
  ];
  exit(return_json($data));
}
    $data = array('status'=>1,'msg'=>'请求成功',);
    $data['Turntable'] = array('code'=>true,'WinningID'=>intval($turntable[$result-1]['id'])); 
    exit(return_json($data));
}
 //进行连接发送
  if($socket->connet == false){
      $data = array(
        'status'  =>  0,
        'msg'     =>  '连接服务器失败',
        );
      exit(return_json($data));
  }
      $packet = $send -> makeTurntablepacket($userid,$prizeType,$num);  
      $res = $socket -> send($send::TurntableID,1,0,$packet);
    if(!$res){
      $data = array(
        'status'  =>  0,
        'msg'     =>  '与服务端通信失败',
        );
      exit(return_json($data));
    }
    $read = unpack('i*', $socket->read_data(1024));
    if(!$read){
       $data = array(
            'status'  =>  0,
            'msg'     =>  '抽奖失败，服务器未响应',
          );
        exit(return_json($data));
    }
    switch ($read[4]) {
      case 1:
          $data = array(
            'status'  =>  0,
            'msg'     =>  '数据不对',
          );
        exit(return_json($data));
        break;
      case 2:
          $data = array(
            'status'  =>  0,
            'msg'     =>  '用户不存在',
          );
        exit(return_json($data));
        break;
      case 3:
          $data = array(
            'status'  =>  0,
            'msg'     =>  '资源不足',
          );
        exit(return_json($data));
        break;
    }
//记录
$sql = "insert into web_turntableRecord(userID,turntableTime,num,prizeType,prize,username)values(".$userid.",".time().",".$num.",".$prizeType.",'".$prize."','".$username."')";
$res = $db -> add($sql);
if(!$res){
  $data = [
    'status'  =>  0,
    'msg'     =>  '失败',
  ];
  exit(return_json($data));
}
$data = array('status'=>1,'msg'=>'请求成功',);
$data['Turntable'] = array('code'=>true,'WinningID'=>intval($turntable[$result-1]['id'])); 
exit(return_json($data));
}
$data = array('status'=>0,'msg'=>'未传入userid参数');
exit(return_json($data));
 ?>
