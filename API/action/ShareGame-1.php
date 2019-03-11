<?php 
	$fh = fopen('test.txt','a+');
        fwrite($fh,microtime(true)."\r");
        fclose($fh);
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['name']) && !empty($_REQUEST['name']) && isset($_REQUEST['address_type']) && !empty($_REQUEST['address_type'])){

	$userid = $_REQUEST['userID'];
	$name = $_REQUEST['name'];
	$address_type = (int)$_REQUEST['address_type'];
    //当日首次分享   需要赠送金币或房卡   获取配置
  	$sql = "select * from web_gameconfig";
  	$c = $db -> getAll($sql);
  	$config = [];
  	foreach($c as $k => $v){
  		$config[$c[$k]['key']] = $c[$k]['value'];
  	}
  	$share_send_jewels = (int)$config['share_send_jewels'];
  	/*$share_send_money = (int)$share_send_money['value'];
  	$sql = "select * from web_gameconfig where key='share_send_jewels'";
  	$share_send_jewels = $db -> getRow($sql);
  	$share_send_jewels = (int)$share_send_jewels['value'];*/
	//获取上一次的分享记录
	$sql = "select top 1 *  from web_sharegame where userid=".$userid." order by share_time desc";
	$record = $db -> getRow($sql);
	//echo $sql;
	//var_dump($record);
	$socket = Socket::get();
	$send = new SendFunction();
	$share_time = time();
	//进行连接发送
	if(!$record){
		//说明是第一次分享   赠送礼物	
  		$packet = $send -> makeSharePacket($userid,2,$share_send_jewels);  
     	$res = $socket -> send($send::ShareID,1,0,$packet);
     	if(!$res){
     		$share_send_jewels = 0;
     	};
     	//记录
     	$sql = "insert into web_sharegame (userid,name,share_address,share_time,send_money,send_jewels)values($userid,'$name',$address_type,$share_time,0,$share_send_jewels)";
     	$db ->add($sql);
     	$data = [
			'status'=>1,
			'msg'=>'成功1',
		];
		exit(return_json($data));
	}
	//不是第一次分享
	//今天开始时间 和 结束时间
	$beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
  	$endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;//今天结束时间戳
  	//该分享不是今天首次分享
  	if($record['share_time'] > $beginToday && $record['share_time'] < $endToday){
  		//记录
  		$sql = "insert into web_sharegame (userid,name,share_address,share_time,send_money,send_jewels)values($userid,'$name',$address_type,$share_time,0,0)";
     	$db ->add($sql);
     	$data = [
			'status'=>1,
			'msg'=>'成功2',
		];
		exit(return_json($data));
  	}else{
  		//今天首次分享
  		$packet = $send -> makeSharePacket($userid,2,$share_send_jewels);  
     	$res = $socket -> send($send::ShareID,1,0,$packet);
     	if(!$res){
     		$share_send_jewels = 0;
     	};
     	//记录
     	$sql = "insert into web_sharegame (userid,name,share_address,share_time,send_money,send_jewels)values($userid,'$name',$address_type,$share_time,0,$share_send_jewels)";
     	$db ->add($sql);
     	$data = [
			'status'=>1,
			'msg'=>'成功3',
		];
		exit(return_json($data));
  	}
}
$data = [
	'status'=>0,
	'msg'=>'参数不完整',
];
exit(return_json($data));
?>
