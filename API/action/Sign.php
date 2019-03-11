<?php
//用户签到
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
  $userid = intval($_REQUEST['userID']);
  $socket = Socket::get();
  $redis = GetRedis::get();
  $send = new SendFunction();
  if($redis->connect == false){
    $data= array('status'=>0,'msg'=>'缓存服务器未启动');
    $data['Sign'] = array('code'=>false);
  }
  $user =  $redis->redis->hgetall('userInfo|'.$userid);
  if(!$user){
    $data = array('status'=>0,'msg'=>'用户不存在');
    $data['Sign'] = array('code'=>false);
    exit(return_json($data));
  }
  $num = null;
  //查询上一次的签到记录
  $sql = "select * from web_signRecord where userID=".$userid." order by signTime desc";
  $record = $db->getRow($sql);
  //如果上一次签到时间在今天内则不能再次签到
  $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
  $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
  if(!$record){
    $num = 1;
  }else{
    if($record['signTime'] > $beginToday && $record['signTime'] < $endToday){
        $data = array('status'=>1,'msg'=>'你已经签到过了');
        $data['Sign'] = array('code'=>false);
        exit(return_json($data));
    }
    if($record['dateNum'] == 6){
      $num = 1;
    }elseif($record['dateNum'] < 6  && $record['signTime'] > ($beginToday - 24*3600)){
      $num = $record['dateNum'] + 1;
    }else{
      $num = 1;
    }  
}
  //获取日常签到配置
  $sql = "select * from web_signconfig where dateNum=".$num;
  $signconfig1 = $db -> getRow($sql);
  //获取连续签到多少天配置
  //通知服务端刷新缓存
  define('RESOURCE_TYPE_MONEY',2);  //金币
  define('RESOURCE_TYPE_JEWEL',1);  //房卡
   //进行连接发送
  if($socket->connet == false){
      $data = array(
        'status'  =>  0,
        'msg'     =>  '连接服务器失败',
        );
      exit(return_json($data));
  }
      $packet = $send -> sign($userid,$signconfig1['prizeType'],$signconfig1['num']);  
      $res = $socket -> send($send::SignID,1,0,$packet);
    if(!$res){
      $data = array(
        'status'  =>  0,
        'msg'     =>  '与服务端通信失败',
        );
      exit(return_json($data));
    }
    $read = unpack('i*', $socket->read_data(1024));
    //$socket->close();
    if(!$read){
       $data = array(
            'status'  =>  0,
            'msg'     =>  '签到失败，通信失败',
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
    $encode = mb_detect_encoding($user['name'], array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
    $user['name'] = trim(iconv('GB2312','UTF-8',$user['name']),"'");
//echo $encode;exit();
  //记录签到信息
 // if(!$record){
      $sql = "insert into web_signRecord(prizeType,prize,num,dateNum,signTime,userID,username)values(".intval($signconfig1['prizeType']).",'".$signconfig1['prize']."',".intval($signconfig1['num']).",".intval($num).",".time().",".intval($userid).",'".$user['name']."')";
 // }else{
   //   $sql = "update web_signRecord set prize='".$signconfig1['prize']."',prizeType=".$signconfig1['prizeType'].",num=".$signconfig1['num'].",dateNum=".$num.",signTime=".time().",userID=".$userid.",username='".$user['name']."' where userID=".$userid;
 // }
  $db->add($sql);
 $data = array('status'=>1,'msg'=>'请求成功');
    $data['Sign'] = array('code'=>true);
    exit(return_json($data));
}
  $data['Sign'] = array('status'=>0,'msg'=>'未传入userid');
  exit(return_json($data));
?>
