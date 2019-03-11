<?php

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

$sql = 'select * from web_gameconfig';
$data = $db->getAll($sql);

$config = [];

foreach($data as $v){
    $config[$v['key']] = $v['value'];
}
$s['testStatus'] = (int)$config['test_status'];
exit(json_encode(['status'=>1,'msg'=>'获取成功','data'=>$s],JSON_UNESCAPED_UNICODE));
