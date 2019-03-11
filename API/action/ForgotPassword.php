<?php 
if(isset($_REQUEST['userID']) || !empty($_REQUEST['userID'])){
	//判断该用户是否存在
	$userID = $_REQUEST['userID'];
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
    if(!$user['mail']){
    	$data = array('status'=>0,'msg'=>'邮箱账号在缓存中不存在');
    	exit(return_json($data));
    }
    if(!$user['bankPasswd']){
    	$data = array('status'=>0,'msg'=>'银行密码在缓存中不存在');
    	exit(return_json($data));
    }
    $res = send_mail($user['mail'],'找回密码','您的银行密码是'.$user['bankPasswd']);
    $res = json_decode($res);
    if($res->statusCode == 200){
    	$data = array('status'=>1,'msg'=>'发送成功');
    	exit(return_json($data));
    }else{
    	$data = array('status'=>0,'msg'=>'发送失败');
    	exit(return_json($data));
    }
}
$data = [
	'status'=>0,
	'msg'=>'请传递userID',
];
exit(return_json($data));
function send_mail($to,$title,$content) {
        $url = 'http://api.sendcloud.net/apiv2/mail/send';
        $API_USER = 'Va_Dly_test_Fyumqj';
        $API_KEY = 'bat22TFh4Y2yuSZA';
        $param = array(
            'apiUser' => $API_USER, # 使用api_user和api_key进行验证
            'apiKey' => $API_KEY,
            'from' => 'sendcloud@sendcloud.org', # 发信人，用正确邮件地址替代
            'fromName' => '至尊纸',
            'to' => $to,# 收件人地址, 用正确邮件地址替代, 多个地址用';'分隔  
            'subject' => $title,
            'html' => $content,
            'respEmailId' => 'true'
        );
        $data = http_build_query($param);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
        ));
        $context  = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);
        return $result;
}
?>
