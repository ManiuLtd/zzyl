<?php 
if(isset($_REQUEST['userID']) || !empty($_REQUEST['userID']) || isset($_REQUEST['goodsID']) || !empty($_REQUEST['goodsID'])){
$goodsID = intval($_REQUEST['goodsID']);
$userID = intval($_REQUEST['userID']);
//根据商品ID查询商品详细信息
$sql = "select * from web_goods where goodsID=".$goodsID;
$goods = $db -> getRow($sql);
if(!$goods){
	$data = array(
		'status'	=>	0,
		'msg'		=>	'商品不存在',
	);
	exit(return_json($data));
}
//查询用户信息
$redis = GetRedis::get();
if($redis->connect == false){
    $data= array('status'=>0,'msg'=>'缓存服务器未启动');
    exit(return_json($data));
  }
$user =  $redis->redis->hgetall('userInfo|'.$userID);
if(!$user){
    $data = array('status'=>0,'msg'=>'用户不存在');
    exit(return_json($data));
}
//获取配置参数
$key            =       $config['wechat_key'];
$appid 			= 		$config['wechat_appid'];
$mch_id			=		$config['wechat_mch_id'];
$nonce_str		=		createNoncestr();
$body 			=		'您确定要用'.sprintf("%.2f",$goods['consumeNum']/100).'元购买'.$goods['buyNum'].$goods['buyGoods'].'吗?';
$out_trade_no 	=		 date('YmdHis',time()).$userID;
$total_fee		=	(float)$goods['consumeNum']/100 * 100;
$ip 			=		get_client_ip();
$notify_url 	=		$config['wechat_notify'];
//echo $notify_url;
$trade_type		=		'APP';
$array = array(
    'appid'				=>		$appid,
    'mch_id'			=>		$mch_id,
    'nonce_str'			=>		$nonce_str,
    'body'				=>		$body,
    'out_trade_no'		=>		$out_trade_no,
    'total_fee'			=>		$total_fee,
    'spbill_create_ip'	=>		$ip,
    'notify_url'		=>		$notify_url,
    'trade_type'		=>		$trade_type
    );
    $sign = MakeSign($array,$key);
$param=array(
    'appid'				=>		$appid,
    'mch_id'			=>		$mch_id,
    'nonce_str'			=>		$nonce_str,
    'body'				=>		$body,
    'out_trade_no'		=>		$out_trade_no,
    'total_fee'			=>		$total_fee,
    'spbill_create_ip'	=>		$ip,
    'notify_url'		=>		$notify_url,
    'trade_type'		=>		$trade_type,
    'sign'				=>		$sign,	
    );
$xml = arrayToXml($param);

$url        = 'https://api.mch.weixin.qq.com/pay/unifiedorder';  //请求地址
$response = postXmlCurl($xml,$url);
if(!$response){
    $date['status'] = 0;
    $date['msg'] = '通信失败';
    exit(return_json($date));
}
//var_dump($response);exit();
//将返回的数据转为数组
$response = xmlToArray($response);
//生成订单
$status = 0;
$create_time = time();
$desc = '新订单,支付方式：微信';
/*$encode = mb_detect_encoding($user['name'], array("ASCII",'UTF-8','GB2312',"GBK",'BIG5'));
    switch ($encode) {
      case 'ASCII':
          $user['name'] = iconv('ASCII','UTF-8',$user['name']);
      break;
      case 'GB2312':
          $user['name'] = iconv('GB2312','UTF-8',$user['name']);
      break;
      case 'GBK':
          $user['name'] = iconv('GBK','UTF-8',$user['name']);
      break;
      case 'BIG5':
          $user['name'] = iconv('BIG5','UTF-8',$user['name']);
      break;  
    }*/
$user['name'] = iconv('GB2312','UTF-8',$user['name']);
//echo $desc;
$sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc,name)values('".$out_trade_no."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$desc."','".$user['name']."')";
$res = $db -> add($sql);
if(!$res){
	$date['status'] = 0;
    $date['msg'] = '生成订单时发生错误';
    exit(return_json($date));
}
$array= array(
                'appId'             =>      $response['appid'],
                'partnerId'         =>      $mch_id,  
                'nonceStr'          =>      $response['nonce_str'],  
                'timeStamp'         =>      (string)time(),  
                'packageValue'      =>      'Sign=WXPay',  
                'prepayId'          =>        $response['prepay_id']
            );
$arr= array(
                'appid'             =>      $response['appid'],
                'partnerid'         =>      $mch_id,  
                'noncestr'          =>      $response['nonce_str'],  
                'timestamp'         =>      time(),  
                'package'           =>      'Sign=WXPay',  
                'prepayid'          =>        $response['prepay_id'] 
            );
$appsign = MakeSign($arr,$key);
$array['order_sn'] = $out_trade_no;
$array['sign'] = $appsign;
$data = array(
		'status'	=>	1,
		'msg'		=>	'请求成功',
		'array'		=>	$array
	);
exit(return_json($data));
}
$data = array(
		'status'	=>	0,
		'msg'		=>	'参数不正确',
	);
exit(return_json($data));
 ?>
