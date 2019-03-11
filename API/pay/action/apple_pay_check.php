<?php 
	if(isset($_REQUEST['order_sn']) && !empty($_REQUEST['order_sn']) && isset($_REQUEST['status']) && !empty($_REQUEST['status'])){
			//处理苹果支付
			$receipt  = $_REQUEST['status'];	//苹果支付状态
			$order_sn = $_REQUEST['order_sn'];		//用户ID
			$sql = "select * from web_orders where order_sn='$order_sn'";
			$order = $db -> getRow($sql);
			if(!$order){
				$arr = array('status'=>0,'msg'=>'订单号不存在');
				exit(return_json($arr));
			}
			if($order['status'] != 0){
				$arr = array('status'=>0,'msg'=>'该订单已经处理过');
				exit(return_json($arr));
			}
			if($status == 2){
				$sql = "update web_orders set status=2 where order_sn='$order_sn'";
				$res = $db -> add($sql);
				if(!$res){
					$arr = [
						'status'	=>	0,
						'msg'		=>	'修改订单状态时发生错误',
					];
				    exit(return_json($arr));
				}
				$arr = [ 
					'status'	=>	1,
					'msg'		=>	'请求成功',
				];
				exit(return_json($arr));
			}
			$socket = Socket::get();
			$send = new SendFunction();
			//连接服务器失败
			if($socket->connet == false){
		      	$arr = array(
			        'status'  =>  0,
			        'msg'     =>  '连接服务器失败',
	        	);
	      	exit(return_json($arr));
	  		}
	  		$userID = $order['userID'];
	  		$amount = $order['buyNum'];
	  		$type   = $order['buyType'];
	  		//拼装数据包
	  		$packet = $send -> makeRechargePacket($userID,$amount,$type);
	  		$res    = $socket -> send($send::RechargeID,1,0,$packet);
	  		if(!$res){
	      		$arr = array(
	        		'status'  =>  0,
	        		'msg'     =>  '与服务端通信失败',
	        	);
	     	exit(return_json($arr));
	   		}
		    $read = unpack('i*', $socket->read_data(1024));
		    //$socket->close();
		    if(!$read){
		       $arr = array(
		            'status'  =>  0,
		            'msg'     =>  '支付失败，通信失败',
		          );
		        exit(return_json($arr));
		    }
		    switch ($read[4]) {
		      case 1:
		          $arr = array(
		            'status'  =>  0,
		            'msg'     =>  '数据不对',
		          );
		        exit(return_json($arr));
		        break;
		      case 2:
		          $arr = array(
		            'status'  =>  0,
		            'msg'     =>  '用户不存在',
		          );
		        exit(return_json($arr));
		        break;
		      case 3:
		          $arr = array(
		            'status'  =>  0,
		            'msg'     =>  '资源不足',
		          );
		        exit(return_json($arr));
		        break;
		    }

		    //成功向服务端发送消息后修改订单状态
		    $sql = "update web_orders set status=1 where order_sn='$order_sn'";
			$res = $db -> add($sql);
			if(!$res){
				$arr = [
					'status'	=>	0,
					'msg'		=>	'修改订单状态时发生错误',
				];
			    exit(return_json($arr));
			}
			$arr = [ 
				'status'	=>	1,
				'msg'		=>	'请求成功',
			];
			exit(return_json($arr));
	}	
			
	$arr = [
		'status'	=>	0,
		'msg'		=>	'传递参数不完整',
	];
	exit(return_json($arr));
?>