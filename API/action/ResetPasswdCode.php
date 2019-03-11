<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include dirname(dirname(__FILE__)) . '/aliyun-dysms/SmsDemo.php';
if (isset($_REQUEST['phone']) && !empty($_REQUEST['phone'])) {

    $phone = $_REQUEST['phone'];
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
    $repassword = isset($_REQUEST['repassword']) ? $_REQUEST['repassword'] : '';

    // 手机格式
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

    // 是否绑定手机
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

    // // 验证手机
    $isPhone = isPhone($phone);
    if (!$isPhone) {
        $data = ['status' => 0, 'msg' => '手机号码不合法'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    // 验证手机
    $checkPhone = checkPhone($phone);
    if (!$checkPhone) {
        $data = ['status' => 0, 'msg' => '该手机还未关联游戏账号'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    if($password){
        // 验证密码
    /*    $isPwd = checkPassword($password);
        if (!$isPwd) {
            $data = ['status' => 0, 'msg' => '长度只能在6-20个字符之间'];
            exit(json_encode($data, JSON_UNESCAPED_UNICODE));
        }*/


        if ($password != $repassword) {
            $data = ['status' => 0, 'msg' => '两次密码输入不一致'];
            exit(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }

    // 绑定
    $code = rand(111111, 999999);
    $response = SmsDemo::sendSms($phone,$code);
    //$response = 1;
    if ($response->Code == 'OK') {
        // 发送验证码
        $time                                                = date('ymd', time());
        $t = time() + 600;
        file_put_contents('./phone_code/code_' . $phone.'.txt', $code . '|' . $t);
    	$data                                                = ['status' => 1, 'msg' => '发送成功', 'sendPhoneCode' => ['code' => $code]];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    } else {
        $data = ['status' => 0, 'msg' => '发送失败'];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
