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


function pay_log($order_sn,$consume_num){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://47.106.144.97/index/API/add?order_sn={$order_sn}&merchant_id=100002&sign=c7cebd4a6e29cb59b0d679025d124702&buy_type=1&consume_num={$consume_num}&status=0");
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);
}

$wxpay = new Pay();
 $wxpay->pay_amt=$goods['consumeNum']/100;
//$wxpay->bargainor_id='108874';
//$wxpay->key='c85a3e0fb36258b738b88cf87f7581a3';
$wxpay->notify_url=$config['wechat_notify_15173'];
//$type=1;
//$wxpay->url=$wxpay->type($type);
$order_sn=date('YmdHis',time()).$userID;
$wxpay->agent_bill_id=$order_sn;
$wxpay->agent_bill_time=date('YmdHis', time());
$wxpay->goods_name = iconv('UTF-8','GB2312',$goods['buyGoods'].'购买');
//$wxpay->sign=$wxpay->md5Sign();
$url = $wxpay->curl();
//$url = urldecode($url);
$user['name'] = iconv('GB2312','UTF-8',$user['name']);
$status = 0;
$create_time = time();
$desc = '新订单';
//$sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc,name,sendNum)values('".$order_sn."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$desc."','".$user['name']."')";
//echo $sql;die;
$sql = "insert into web_orders (order_sn,userID,buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,status,create_time,pay_desc)values('".$order_sn."',".$userID.",'".$goods['buyGoods']."',".$goods['buyNum'].",".$goods['buyType'].",'".$goods['consumeGoods']."',".$goods['consumeNum'].",".$goods['consumeType'].",".$status.",".$create_time.",'".$desc."')";

$res = $db -> add($sql);
if(!$res){
	$date['status'] = 0;
    $date['msg'] = '生成订单时发生错误';
    exit(return_json($date));
}

pay_log($order_sn,$goods['consumeNum']/100);
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
 ?>
