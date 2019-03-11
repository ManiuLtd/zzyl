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


$wxpay = new Pay();
$wxpay->money=0.1;
$wxpay->bargainor_id='108582';
$wxpay->key='eb92cbf0bac1a9cc26cb0a9d3c2afc49';
$wxpay->return_url=$config['wechat_notify_15173'];
//$wxpay->return_url='https://2e999f8.tunnel.echomod.cn/API/pay/notify/wechat_notify_njj.php';
//echo $config['wechat_notify_15173'];
$type=1;
$wxpay->url=$wxpay->type($type);
$order_sn=date('YmdHis',time()).$userID;
$wxpay->order=$order_sn;
$wxpay->sign=$wxpay->md5Sign();
$url = $wxpay->pay();
//var_dump($url);
$status = 0;
$create_time = time();
$desc = '新订单';
$sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc)values('".$order_sn."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$desc."')";
$res = $db -> add($sql);
if(!$res){
	$date['status'] = 0;
    $date['msg'] = '生成订单时发生错误';
    exit(return_json($date));
}
$array['recharge_url'] = $url;
$data = array(
		'status'	=>	1,
		'msg'		=>	'请求成功',
		'array'		=>	$array,
	);
exit(return_json($data));
}
$data = array(
		'status'	=>	0,
		'msg'		=>	'参数不正确',
	);
exit(return_json($data));
 ?>}
