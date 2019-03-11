<?php 
//获取所有的游戏数据
$sql = "select * from otherConfig";

$config = [];
$data = $db ->getAll($sql);
foreach($data as $v){
	$config[$v['keyConfig']] = $v['valueConfig'];
}
//获取php配置
$sql ="select * from web_gameconfig";
$phpconfig = $db -> getAll($sql);
$config['kefu_phone_1'] = $phpconfig[0]['value'];
$config['bind_agent_send_jewels'] = (int)$phpconfig[1]['value'];
$config['bind_agent_send_money'] = (int)$phpconfig[2]['value'];
$config['share_url'] = $phpconfig[9]['value'];
$config['share_img'] = $phpconfig[8]['value'];
//foreach($config as $v => $k){
//$config[$v] = (int)$config[$v];
//}
//var_dump($config);
$data = [
	'status'=>1,
	'msg'=>'请求成功',
	'config'=>$config,
];
exit(return_json($data));
?>
