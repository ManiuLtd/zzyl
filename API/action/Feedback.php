<?php
if(isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && isset($_REQUEST['content']) && !empty($_REQUEST['content']) && isset($_REQUEST['style']) && !empty($_REQUEST['style'])){
    $userid = $_REQUEST['userid'];
    $content = $_REQUEST['content'];
    $encode = mb_detect_encoding($content, array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
    $fh = fopen('code.txt','w');
    fwrite($fh,$encode);
    fclose($fh);
    $type = $_REQUEST['style'];
    $redis = GetRedis::get();
    if($redis->connect == false){
      $data= array('status'=>0,'msg'=>'缓存服务器未启动');
      exit(return_json($data));
    }
    $user =  $redis->redis->hgetall('userInfo|'.$userid);
    if(!$user){
      $data = array('status'=>0,'msg'=>'用户不存在');
      exit(return_json($data));
    }
    $read_type = 0;
    $f_time = time();
    $user['name'] = iconv('GB2312','UTF-8',$user['name']);
    $sql = "insert into web_adminfeedback(userid,username,content,type,f_time,read_type)values(".$userid.",'".$user['name']."','".$content."',".$type.",".$f_time.",".$read_type.")";
    $res = $db -> add($sql);
    if(!$res){
      $data =array('status'=>0,'msg'=>'发生错误');
      exit(return_json($data));
    }
    $sql = "select top 1 * from web_adminfeedback where userID=".$userid." order by id desc";
    $feedback = $db -> getRow($sql);
    unset($feedback['userID']);
    unset($feedback['username']);
    unset($feedback['has_back']);
    $encode = mb_detect_encoding($feedback['content'], array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
    //echo $encode; 
    $feedback['id'] = (int)$feedback['id'];
    $feedback['type'] = (int)$feedback['type'];
    $feedback['f_time'] = (int)$feedback['f_time'];
    $feedback['read_type'] = (int)$feedback['read_type'];
    $data =array('status'=>1,'msg'=>'反馈成功','Feedback'=>$feedback);
    exit(return_json($data));
}

$data = array('status'=>0,'msg'=>'传入参数错误');
exit(return_json($data));
 ?>
