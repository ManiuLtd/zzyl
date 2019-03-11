<?php

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
if (isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['phone']) && !empty($_REQUEST['phone']) && isset($_REQUEST['wechat']) && !empty($_REQUEST['wechat']) && isset($_REQUEST['code']) && !empty($_REQUEST['code']) && isset($_REQUEST['password']) && !empty($_REQUEST['password']) && isset($_REQUEST['repassword']) && !empty($_REQUEST['repassword'])){

    $userid     = $_REQUEST['userID'];
    $phone      = $_REQUEST['phone'];
    $weixin     = $_REQUEST['wechat'];
    $code       = $_REQUEST['code'];
    $password   = $_REQUEST['password'];
    $repassword = $_REQUEST['repassword'];

    // 获取配置
    $sql       = "select * from  web_gameconfig";
    $config    = $db->getAll($sql);
    $sendMoney = (int) $config[23]['value'];
    //echo $sendMoney;
    //var_dump($config);die;
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

    function isWechat($weixin)
    {
        $myreg = "/^[a-zA-Z][a-zA-Z0-9_]{5,20}$/";
        $res   = preg_match($myreg, $weixin);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    // 验证密码
    function checkPassword($password)
    {
        $myreg = "/^[a-zA-Z0-9_-]{6,16}$/";
        $res   = preg_match($myreg, $password);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    //验证手机
    $isPhone = isPhone($phone);
    if (!$isPhone) {
        $data = ['status' => 0, 'msg' => '手机号不合法'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // // 验证微信
    $isWeixin = isWechat($weixin);
    if (!$isWeixin) {
        $data = ['status' => 0, 'msg' => '微信号不合法'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 验证密码
 /*   $isPwd = checkPassword($password);
    if(!$isPwd){
        $data = ['status' => 0, 'msg' => '密码长度只能在6-20个字符之间'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
*/
    if($password != $repassword){
        $data = ['status' => 0, 'msg' => '两次密码输入不一致'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $file = @file_get_contents('./phone_code/code_' . $phone . '.txt');
    $file = explode('|', $file);
    // 验证验证码
    if ($file[0] != $code || empty($file)) {
        $data = ['status' => 0, 'msg' => '验证码不正确'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 验证验证码时间
    if ($file[1] < time()) {
        $data = ['status' => 0, 'msg' => '验证码已过期'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 手机是否绑定
/*    $sql  = "select * from userInfo where userID=$userid and phone='$phone'";
    $find = $db->getRow($sql);
    if ($find) {
        $data = ['status' => 0, 'msg' => '手机号已经绑定过', 'BindPhone' => ['phone' => $find]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $redis  = GetRedis::get();
    $prefix = 'userInfo|' . $userid;
    $user   = $redis->redis->hgetall($prefix);
    if (!$user) {
        $data = ['status' => 0, 'msg' => '该用户不存在'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }*/

   
    $redis    = GetRedis::get();
    $userData = $redis->redis->keys('userInfo|*');
    foreach ($userData as $v) {
        $find = $redis->redis->hgetall($v);
        if ($find) {
            if ($find['phone'] == $phone) {
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



    $socket = Socket::get();
    $send   = new SendFunction();
    if ($redis->connect == false) {
       // $data = array('status' => 0, 'msg' => '缓存服务器未启动');
        $data = array('status' => 0, 'msg' => '数据写入失败,请稍后重试');
	exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $packet = $send->makeBindPhone($userid, 1, $sendMoney,$phone); // 送金币
    $res    = $socket->send($send::BindPhoneID, 1, 0, $packet);
    if (!$res) {
        //$data = array('status' => 0, 'msg' => '请求失败，与服务器通信出现错误');
	
        $data = array('status' => 0, 'msg' => '数据写入失败,请稍后重试');
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $read = unpack('i*', $socket->read_data(1024));
    if (!$read) {
        //$data = array('status' => 0, 'msg' => '请求失败，接收服务器消息失败');
        $data = array('status' => 0, 'msg' => '数据写入失败,请稍后重试');
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if ($read[4] != 0) {
        //$data = array('status' => 0, 'msg' => '请求失败,错误码' . $read[4]);
        $data = array('status' => 0, 'msg' => '数据写入失败,请稍后重试');
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 写入redis
    $data = ['phone' => $phone, 'wechat' => $weixin,'phonePasswd'=>$password];
    $redis->redis->hmset($prefix, $data);

    // 更新数据
 /*   $sql  = "update userInfo set phone={$phone},wechat='$weixin',phonePasswd='$password' where userID={$userid}";
    $res  = $db->getRow($sql);*/
    $data = ['status' => 1, 'msg' => '绑定成功'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));

} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
