<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
if (isset($_REQUEST['phone']) && !empty($_REQUEST['phone']) && isset($_REQUEST['code']) && !empty($_REQUEST['code']) && isset($_REQUEST['password']) && !empty($_REQUEST['password']) && isset($_REQUEST['repassword']) && !empty($_REQUEST['repassword'])) {

    $phone      = $_REQUEST['phone'];
    $code       = $_REQUEST['code'];
    $password   = $_REQUEST['password'];
    $repassword = $_REQUEST['repassword'];

    // 验证手机
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

    // 手机是否绑定
    function checkPhone($phone)
    {
        global $db;
        $sql = "select * from userInfo where phone='$phone'";
        $res = $db->getRow($sql);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    //验证密码 格式
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
    $isPhone = checkPhone($phone);
    if (!$isPhone) {
        $data = ['status' => 0, 'msg' => '该手机号还未关联微信号'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 验证密码
    /*$isPwd = checkPassword($password);
    if (!$isPwd) {
        $data = ['status' => 0, 'msg' => '长度只能在6-20个字符之间'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }*/

    if ($password != $repassword) {
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
	
    $res = $isPhone;
    $redis  = GetRedis::get();
    $prefix = 'userInfo|' . $res['userID'];
    $user   = $redis->redis->hgetall($prefix);
    if (!$user) {
        $data = ['status' => 0, 'msg' => '该用户不存在'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

	// 写入redis
    $data = ['phonePasswd' => $password];
    $redis->redis->hmset($prefix, $data);

	// 更新数据
    $sql  = "update userInfo set phonePasswd='$password' where userID={$res['userID']}";
    $res  = $db->getRow($sql);
    $data = ['status' => 1, 'msg' => '重置密码成功'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));

} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
