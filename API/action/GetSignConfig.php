<?php
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){
//获取签到配置信息
$sql = "select * from web_signConfig";
$signConfig = $db -> getAll($sql);
$userid = intval($_REQUEST['userID']);
//查询用户是否存在
$redis = GetRedis::get();
//exit();
//$redis->redis->auth('Yy304949708');
  if($redis->connect == false){
    $data= array('status'=>1,'msg'=>'缓存服务器未启动');
    $data['Sign'] = array('code'=>false);
  }
  $user =  $redis->redis->hgetall('userInfo|'.$userid);
  if(!$user){
    $data = array('status'=>0,'msg'=>'用户不存在');
    $data['Sign'] = array('code'=>false);
    exit(return_json($data));
  }
//查询上一次的签到记录
  $sql = "select * from web_signRecord where userID=$userid order by signTime desc";
  $record = $db -> getRow($sql); 
$beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳  
//var_dump($record);
if(!$record){
    $num = 0;
  }else{
  	if($record['dateNum'] == 6){
  		$num =0;
	}elseif($record['dateNum'] != 6 && $record['signTime'] > ($beginToday-24*3600)){
      $num = $record['dateNum'];
    }else{
      $num = 0;
    } 
 }
foreach($signConfig as $k => $v){
 // $encode = mb_detect_encoding($signConfig[$k]['prize'], array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
 // echo $encode;
  $signConfig[$k]['id'] = intval($signConfig[$k]['id']);
  $signConfig[$k]['dateNum'] = intval($signConfig[$k]['dateNum']);
  $signConfig[$k]['num'] = intval($signConfig[$k]['num']);
  $signConfig[$k]['prizeType'] = intval($signConfig[$k]['prizeType']);
  $signConfig[$k]['picNum'] = (int)$signConfig[$k]['picNum'];
 // $signConfig[$k]['prize'] = iconv('ASCII','UTF-8',$signConfig[$k]['prize']);
  $signConfig[$k]['isSign'] = false;
  $signConfig[$k]['canSign'] = false;
  if($signConfig[$k]['dateNum'] <= $num && $signConfig[$k]['dateNum'] < 7){
      $signConfig[$k]['isSign'] = true;
  		$signConfig[$k]['canSign'] = false;
  }elseif($signConfig[$k]['dateNum'] == $num + 1 && $signConfig[$k]['dateNum'] < 7){
      $signConfig[$k]['isSign'] = false;
  		$signConfig[$k]['canSign'] = true;
  }
  /*if(!$record){
    $record['signCount'] = 0;
  }
  if($record['signCount'] == $signConfig[$k]['dateNum'] && $signConfig[$k]['dateNum'] > 8){
    $signConfig[$k]['canSign'] = true;
    $signConfig[$k]['isSign'] = false;
    if($record['signCount'] < $signConfig[$k]['dateNum']){
      $signConfig[$k]['canSign'] = false;
    }
  }elseif($record['signCount'] != $signConfig[$k]['dateNum'] && $signConfig[$k]['dateNum'] > 8){
    $signConfig[$k]['canSign'] = false; 
    $signConfig[$k]['isSign'] = true; 
    if($record['signCount'] < $signConfig[$k]['dateNum']){
      $signConfig[$k]['isSign'] = false;
    }
  }*/
}
//如果上一次签到时间在今天内则不能再次签到
  $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
  $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
  if($record['signTime'] > $beginToday && $record['signTime'] < $endToday){
    foreach($signConfig as $key => $value){
      $signConfig[$key]['canSign'] = false;
    }
  }
$data['GetSignConfig'] = array('code'=>true,'signConfig'=>$signConfig);
$data['status'] = 1;
$data['msg'] = '请求成功';
exit(return_json($data));
}
$data = array('status'=>0,'msg'=>'未传递userID');
exit(return_json($data));
 ?>
