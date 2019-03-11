<?php 
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['appleID']) && !empty($_REQUEST['appleID'])){
	$userID   = $_REQUEST['userID'];		//用户ID
	$appleID  = $_REQUEST['appleID'];	//商品ID
	$sql = "select * from web_goods where appleID='$appleID'";
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
			$status = 0;
		    $desc = '新订单，支付方式：苹果支付';
		    $create_time = time();
		    $out_trade_no 	=	date('YmdHis',time()).$userID;
		    $sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc)values('".$out_trade_no."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$desc."')";
			$res = $db -> add($sql);
			if(!$res){
				$arr = [
					'status'	=>	0,
					
					'msg'		=>	'生成订单时发生错误',
				];
			    exit(return_json($arr));
			}
			$array = [
				'order_sn'	=>	$out_trade_no,
				'appleID'	=>	$appleID,
			];
			$arr = [ 
				'status'	=>	1,
				
				'msg'		=>	'请求成功',
				'array'		=>	$array, 	
		];
			exit(return_json($arr));
}
$arr = [
	'status'	=> 0,
	'code'		=> -1,
	'msg'		=>	'参数传递不完整',
];
exit(return_json($arr));
 ?>
