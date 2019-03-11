<?php
//检测服务器状态
$file = '/usr/local/nginx/html/timedTask/server_status.txt';
$status = trim(file_get_contents($file));
$ip = get_client_ip();
//echo $ip;
if($status == 'normal'){
	//正常状态所有用户都能进入游戏，截止黑名单用户
	//获取访问IP判断是否限制登录
/*	$str = file_get_contents('/usr/local/nginx/html/timedTask/black.txt');
	$arr = explode('|',$str);
	if(in_array($ip,$arr)){
		return_info('limit_login','账号异常');
	}else{
		return_info('normal','正常登录');
	}
    */
	return_info('normal','正常登录'); 
}elseif($status == 'stop'){
	//停服状态所有人都不能进入游戏
	return_info('stop','服务器停服更新中...');
}elseif($status == 'test'){
	//测试状态  白名单才能进入
	$str = file_get_contents('/usr/local/nginx/html/timedTask/white.txt');
	$arr = explode('|',$str);
	if(in_array($ip,$arr)){
		return_info('normal','白名单登录');
	}else{
		return_info('test','服务器维护中...');
	}
}
function return_info($server_status='normal',$message='正常'){
		$data = [
			'status'	=>	1,
			'msg'		=>	'请求成功',
			'CheckLogin'=>	[
				'server_status' =>	$server_status,
				'message'		=>	$message,	
			],	
		];
		exit(return_json($data));
}

	
