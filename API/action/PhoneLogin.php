<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

if (isset($_REQUEST['phone']) && !empty($_REQUEST['phone']) && isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {

    $phone    = $_REQUEST['phone'];
    $password = $_REQUEST['password'];

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

    function checkPhone($phone)
    {
        global $db;
        $sql = "select * from userInfo where phone='$phone'";
        $res = $db->getRow($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    // // 验证密码
    function checkPassword($phone, $password)
    {
        global $db;
        $sql = "select * from userInfo where phone='$phone' and phonePasswd='$password'";
        $res = $db->getRow($sql);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    // //验证手机
    $isPhone = isPhone($phone);
    if (!$isPhone) {
        $data = ['status' => 0, 'msg' => '手机号码输入不正确'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // // 是否绑定
    $isBind = checkPhone($phone);
    if (!$isBind) {
        $data = ['status' => 0, 'msg' => '该手机号还未关联微信号'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // // 验证密码
    $isPwd = checkPassword($phone, $password);
    if (!$isPwd) {
        $data = ['status' => 0, 'msg' => '登录密码不正确'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // // 更新 redis
    $redis = GetRedis::get();
    if (!$redis || $redis->connect == false) {
        $data = ['status' => 0, 'msg' => '服务器错误!'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $prefix = 'userInfo|' . $isPwd['userID'];
    $user   = $redis->redis->hgetall($prefix);
    if (!$user) {
        $data = ['status' => 0, 'msg' => '该用户不存在'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // // 写入redis
    $data = ['isOnline' => 1, 'winCount' => $user['winCount'] + 1, 'lastCrossDayTime' => time(), 'totalGameCount' => $user['totalGameCount'] + 1];
    $redis->redis->hmset($prefix, $data);

    // // 更新数据
    $ip   = get_client_ip();
    $time = time();
    $sql  = "set nocount on update userInfo set winCount=winCount+1,isOnline=1,logonIP='$ip',lastCrossDayTime=$time where userID={$isPwd['userID']}";
    $res  = $db->getRow($sql);
    $data = ['status' => 1, 'msg' => '登录成功'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));

} else {
    $data = ['status' => 0, 'msg' => '请输入手机号或密码'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
