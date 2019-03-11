<?php 
if(isset($_REQUEST['goodsType']) && !empty($_REQUEST['goodsType'])){
	$goodsType = intval($_REQUEST['goodsType']);
	$sql = "select buyGoods,buyNum,buyType,consumeGoods,consumeNum,consumeType,goodsID,is_New,is_Hot from web_goods where status =1 and buyType=".$goodsType;
	$goods = $db -> getAll($sql);
	foreach($goods as $k => $v){
		$goods[$k]['buyNum'] = (int)$goods[$k]['buyNum'];
		$goods[$k]['buyType'] = (int)$goods[$k]['buyType'];
		$goods[$k]['consumeNum'] = (int)$goods[$k]['consumeNum'];
		$goods[$k]['consumeType'] = (int)$goods[$k]['consumeType'];
		$goods[$k]['goodsID'] = (int)$goods[$k]['goodsID'];
		$goods[$k]['is_New'] = (int)$goods[$k]['is_New'];
		$goods[$k]['is_Hot'] = (int)$goods[$k]['is_Hot'];
	}
	$data = array(
		'status'	=>	1,
		'msg'		=>	'请求成功',
		'GetGoods'	=>	$goods
	);
	exit(return_json($data));
}
$data = array(
		'status'	=>	0,
		'msg'		=>	'为传递参数',
	);
exit(return_json($data));
?>
