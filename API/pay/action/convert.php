<?php 
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['goodsID']) && !empty($_REQUEST['goodsID'])){
	$userID = $_REQUEST['userID'];
	$goodsID = $_REQUEST['goodsID'];
	$realname = trim($_REQUEST['realname']);
	$phone = trim($_REQUEST['phone']);
	$address = trim($_REQUEST['address']);	
	//查询商品信息
	$sql = "select * from web_goods where goodsID=".$goodsID;
	$goods = $db -> getRow($sql);
	if(!$goods){
		$arr = array(
			'status'	=>	0,
			'msg'		=>	'商品不存在',
		);
		exit(return_json($arr));
	}
	//查询用户信息
	$redis = GetRedis::get();
	if($redis->connect == false){
	    $arr= array('status'=>0,'msg'=>'缓存服务器未启动');
	    exit(return_json($arr));
	  }
	$user =  $redis->redis->hgetall('userInfo|'.$userID);
	if(!$user){
	    $arr = array('status'=>0,'msg'=>'用户不存在');
	    exit(return_json($arr));
	}
	//判断用户金币数
	if($user['money'] < $goods['consumeNum']){
		$arr = array('status'=>0,'msg'=>'金币不足');
	    exit(return_json($arr));
	}
	//下单函数
	function order($userID,$goods,$pay_desc,$db,$realname,$phone,$address,$handle){
		$order_sn = date('YmdHis').$userID;
		$status = 1;
		$create_time = time();
		$sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc,realname,phone,address,handle)values('".$order_sn."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$pay_desc."','".$realname."','".$phone."','".$address."',".$handle.")";
		$res = $db -> add($sql);
	}

	$socket = Socket::get();
  	$send = new SendFunction(); 
	//判断是否是房卡
	if($goodsID == '10017'){
		$arr = array('status'=>0,'msg'=>'兑换失败，暂时不支持');
		exit(return_json($arr));
		//进行兑换
		$packet = $send -> makePosPacket($userID,$goods['consumeNum'],1);
		$res = $socket -> send($send::PosID,1,0,$packet);
		if(!$res){
			$arr = array('status'=>0,'msg'=>'兑换失败，向服务器发送消息失败');
	    	exit(return_json($arr));
		}
		$read = unpack('i*', $socket->read_data(1024));
		if(!$read || $read['4'] != 0){
			$arr = array('status'=>0,'msg'=>'兑换失败，接收服务器消息失败');
	    	exit(return_json($arr));
		}
		$packet = $send -> makeRechargePacket($userID,$goods['buyNum'],2);
		$res = $socket -> send($send::RechargeID,1,0,$packet);
		if(!$res){
			$arr = array('status'=>0,'msg'=>'兑换失败，向服务器发送消息失败');
	    	exit(return_json($arr));
		}
		$read = unpack('i*', $socket->read_data(1024));
		if(!$read || $read['4'] != 0){
			$arr = array('status'=>0,'msg'=>'兑换失败，接收服务器消息失败');
	    	exit(return_json($arr));
		}
		//发送成功调用下单函数
		$pay_desc = '兑换房卡成功';
		$handle = 1;
		order($userID,$goods,$pay_desc,$db,$realname,$phone,$address,$handle);
		$arr = array('status'=>1,'msg'=>'兑换成功');
	    exit(return_json($arr));
	}else{
		//验证真实姓名，手机号，地址
		if(empty($realname) || empty($phone) || empty($address)){
			$arr = array('status'=>0,'msg'=>'请填写完整信息');
	    	exit(return_json($arr));
		}
		//验证手机号格式
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){  
    		$arr = array('status'=>0,'msg'=>'手机号格式不正确');
	    	exit(return_json($arr));  
		}
		//其他实物,先扣除金币然后记录订单
		$packet = $send -> makePosPacket($userID,$goods['consumeNum'],1);
		$res = $socket -> send($send::PosID,1,0,$packet);
		if(!$res){
			$arr = array('status'=>0,'msg'=>'兑换失败，向服务器发送消息失败');
	    	exit(return_json($arr));
		}
		$read = unpack('i*', $socket->read_data(1024));
		if(!$read || $read['4'] != 0){
			$arr = array('status'=>0,'msg'=>'兑换失败，接收服务器消息失败');
	    	exit(return_json($arr));
		}
		$pay_desc = '兑换成功，请尽快发货';
		$handle = 0;
		order($userID,$goods,$pay_desc,$db,$realname,$phone,$address,$handle);
		$arr = array('status'=>1,'msg'=>'兑换成功，请耐心等候运营人员与你联系');
	        exit(return_json($arr));
	}
}
$arr = [
	'status'	=>	0,
	'msg'		=>	'参数不完整',
];
exit(return_json($arr));
?>
