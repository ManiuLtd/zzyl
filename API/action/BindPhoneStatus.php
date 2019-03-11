<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

if (isset($_REQUEST['userID']) && !empty($_REQUEST['userID'])) {

    $userid = $_REQUEST['userID'];
    $sql    = "select * from userInfo where userID={$userid}";
    $find   = $db->getRow($sql);
    $phone = (int) $find['phone'];
    if (!$phone) {
        $data = [
            'status'          => 1,
            'msg'             => '未绑定',
            'BindPhoneStatus' => [
                'isBind' => 0,
            ],
        ];
    } else {
        $data = [
            'status'          => 1,
            'msg'             => '已绑定',
            'BindPhoneStatus' => [
                'isBind' => 1,
                'phone'  => $find['phone'],
                'wechat' => $find['wechat'],
            ],
        ];
    }

    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
} else {
    $data = ['status' => 0, 'msg' => '缺少参数'];
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
