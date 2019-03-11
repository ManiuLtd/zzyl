<?php
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
include dirname(dirname(__FILE__)) . '/aliyun-dysms/SmsDemo.php';
if (isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['phone']) && !empty($_REQUEST['phone'])) {
    
    $userid = $_REQUEST['userID'];
    $phone  = $_REQUEST['phone'];

    function isPhone($phone)
    {
        $myreg = "/^1[34578]{1}\d{9}$/";
        $res   = preg_match($myreg, $phone);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    // 记录绑定次数
    function sendPhoneCount($userid)
    {
        $time = date('ymd', time());
        if (empty($_SESSION['user_' . $userid . '_SendCount_' . $time])) {
            $_SESSION['user_' . $userid . '_SendCount_' . $time] = 3;
        }

        return $_SESSION['user_' . $userid . '_SendCount_' . $time];
    }

    // 验证手机
    $isPhone = isPhone($phone);
    if (!$isPhone) {
        $data = ['status' => 0, 'msg' => '手机号不合法'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /*// 手机是否绑定过
    $sql   = "select * from userInfo where phone={$phone}";
    $phone1 = $db->getRow($sql);
    if ($phone1) {
        $data = ['status' => 0, 'msg' => '手机号已经被绑定过'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 用户是否存在
    $sql  = "select * from userInfo where userID={$userid}";
    $find = $db->getRow($sql);
    if (!$find) {
        $data = ['status' => 0, 'msg' => '用户不存在'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    if ($find && !(int) $find['phone'] == 0) {
        $data = ['status' => 1, 'msg' => '你已经绑定过', 'SendPhoneCode' => ['phone' => $find['phone']]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }*/


   // 用户是否存在
    $redis  = GetRedis::get();
    $userData   = $redis->redis->keys('userInfo|*');
    foreach($userData as $v){
        $find   = $redis->redis->hgetall($v);
	if($find){
           if($find['phone'] == $phone){
             $data = ['status' => 0, 'msg' => '该手机已经绑定其他用户微信'];
             exit(json_encode($data, JSON_UNESCAPED_UNICODE));
           }
	}
    }	
    
    $prefix = 'userInfo|' . $userid;
    $user   = $redis->redis->hgetall($prefix);
    if (!$user) {
        $data = ['status' => 0, 'msg' => '该用户不存在'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
	
    if ($user['phone'] != '' || (int) $user['phone'] != 0) {
        $data = ['status' => 0, 'msg' => '你已经绑定过', 'SendPhoneCode' => ['phone' => $user['phone']]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }


    $count = sendPhoneCount($userid);
    // echo $count;
    if ($count == 0) {
        $data = ['status' => 0, 'msg' => '发送已达上限,请明天再试!', 'sendPhoneCode' => ['sendCount' => $count]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 绑定
    $code = rand(111111, 999999);
    $response = SmsDemo::sendSms($phone,$code);
    //$response = 1;
    if ($response->Code == 'OK') {
        // 发送验证码
        $time                                                = date('ymd', time());
        $count                                               = sendPhoneCount($userid) - 1;
       // $_SESSION['user_' . $userid . '_SendCount_' . $time] = $count;
       // $_SESSION['code_' . $phone]                          = $code;
        //$_SESSION['codeTime_' . $phone]                      = time() + 600;
	$t = time() + 600;
        file_put_contents('./phone_code/code_' . $phone.'.txt', $code . '|' . $t);
	$data                                                = ['status' => 1, 'msg' => '发送成功', 'sendPhoneCode' => ['code' => $code, 'sendCount' => $count]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    } else {
        $data = ['status' => 0, 'msg' => '发送失败'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
