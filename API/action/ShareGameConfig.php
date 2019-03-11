<?php 
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])){

    $userid = $_REQUEST['userID'];
    $sssql = "select * from web_sharegame where userID={$userid} order by share_time desc";
    $data = $db -> getRow($sssql);
    $sql = "select * from web_gameconfig";
    $c = $db -> getAll($sql);
    $config = [];
      foreach($c as $k => $v){
          $config[$c[$k]['key']] = $c[$k]['value'];
    }

    $share['share_begin_time'] = (int)$config['share_begin_time'];
    $share['share_end_time'] = (int)$config['share_end_time'];
    $share['type'] = $config['share_address'];
    $arrImg = [$config['share_img1'],$config['share_img2'],$config['share_img3'],$config['share_img4'],$config['share_img5']];
    foreach($arrImg as $k=>$v){
      if(empty($v)){
        unset($arrImg[$k]);
      }
    }

    $count = count($arrImg);
    $share['share_img'] = $arrImg[rand(0,$count)];
    $share['send_money'] = $config['share_send_money'];
    $share['send_jewels'] = $config['share_send_jewels'];
    $share['share_link'] = $config['share_url'];
	
    if($data){
      $begin = strtotime(date('Y-m-d',time()));
      $end = $begin + 60*60*24 -1;
      if($data['share_time'] > $begin && $data['share_time'] < $end){
            (int)$share['share_count'] = 1;
            exit(json_encode(['status'=>1,'msg'=>'获取成功','ShareGameConfig'=>$share],JSON_UNESCAPED_UNICODE));
      }else {
          (int)$share['share_count'] = 0;
          exit(json_encode(['status'=>0,'msg'=>'不在范围内','ShareGameConfig'=>$share],JSON_UNESCAPED_UNICODE));
      }  
    } else {
        (int)$share['share_count'] = 1;
        exit(json_encode(['status'=>1,'msg'=>'获取成功','ShareGameConfig'=>$share],JSON_UNESCAPED_UNICODE));
    }

} else {
   exit(['status'=>0,'msg'=>'缺少参数']);
}
    
?>
