<?php 
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['name']) && !empty($_REQUEST['name']) && isset($_REQUEST['address_type']) && !empty($_REQUEST['address_type'])){

    $userid = $_REQUEST['userID'];
    $name = $_REQUEST['name'];
    $address_type = (int)$_REQUEST['address_type'];
    $socket = Socket::get();
    $send = new SendFunction();
    $share_time = time();
    $sql = "select * from web_gameconfig";
      $c = $db -> getAll($sql);
      $config = [];
      foreach($c as $k => $v){
          $config[$c[$k]['key']] = $c[$k]['value'];
    }

    $beginTime = $config['share_begin_time']; // 开始
    $endTime = $config['share_end_time']; // 结束

    $share_send_jewels = (int)$config['share_send_jewels'];
    $share_send_money = (int)$config['share_send_money'];
    // 开始时间
    if(time() > $beginTime && time() < $endTime) {
          $sql = "select top 1 *  from web_sharegame where userid=".$userid." order by share_time desc";
          $record = $db -> getRow($sql);
 
        // 第一次分享
        if(!$record){
            $packet = $send -> makeSharePacket($userid,2,$share_send_jewels);  
            $res = $socket -> send($send::ShareID,1,0,$packet);
            if(!$res){
              $share_send_jewels = 0;
            };

            $packet_res_money = $send -> makeSharePacket($userid,1,$share_send_money);  
            $res_money = $socket -> send($send::ShareID,1,0,$packet_res_money);
            if(!$res_money){
              $share_send_money = 0;
            };
            //记录
            $sql = "insert into web_sharegame (userid,name,share_address,share_time,send_money,send_jewels)values($userid,'$name',$address_type,$share_time,$share_send_money,$share_send_jewels)";
            $db ->add($sql);
            $data = [
            'status'=>1,
            'msg'=>'成功1',
          ];
           exit(return_json($data));
      } else {
        // 用户时间 4 天后的时间
        $user_share_time = $record['share_time'] + $config['share_interval'] * 24 * 3600;
     
        if(time() > $user_share_time){
            $packet = $send -> makeSharePacket($userid,2,$share_send_jewels);  
            $res = $socket -> send($send::ShareID,1,0,$packet);
            if(!$res){
              $share_send_jewels = 0;
            };

            $packet_res_money = $send -> makeSharePacket($userid,1,$share_send_money);  
            $res_money = $socket -> send($send::ShareID,1,0,$packet_res_money);
            if(!$res_money){
              $share_send_money = 0;
            };
            //记录
            $sql = "insert into web_sharegame (userid,name,share_address,share_time,send_money,send_jewels)values($userid,'$name',$address_type,$share_time,$share_send_money,$share_send_jewels)";
            $db ->add($sql);
            $data = [
                'status'=>1,
                'msg'=>'成功1',
          ];
          exit(return_json($data));
        } else {
            $data = [
                'status'=>1,
                'msg'=>'不在范围内',
          ];
          exit(return_json($data));
        }
    }
}
} else {
    $data = [
        'status'=>1,
        'msg'=>'缺少参数',
  ];
  exit(return_json($data));
}

?>
