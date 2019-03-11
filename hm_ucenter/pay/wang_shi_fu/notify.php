<?php

use config\EnumConfig;
use model\AgentModel;
use model\AppModel;
use model\PayModel;

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//控制浏览器缓存
header("Cache-Control: no-cache, must-revalidate");
header('Content-Type: text/html; charset=utf-8');
header("Pragma: no-cache");

//设置默认时区
date_default_timezone_set('Asia/Shanghai');

//自动加载帮助类
require_once dirname(dirname(__DIR__)) . '/' . 'helper' . '/' . 'LoadHelper.php';

$data = $_REQUEST;
if (!isset($data['p2_ordernumber'])) {
    AppModel::returnString('parameter error');
}

//订单是否存在
$order_sn = $data['p2_ordernumber'];
$order = PayModel::getInstance()->getPayOrder($order_sn);
if (empty($order)) {
    AppModel::returnString('order is empty');
}

//订单状态是否NEW
if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
    AppModel::returnString('order is not NEW');
}

$sign = WangShiFuPay::getInstance()->pay_sign($data);
if ($sign != $data['p10_sign']) {
    AppModel::returnString('sign is error');
}

//支付成功
if ($data['p4_zfstate'] == 1) {
    //充值增加玩家资源
    $userID = $order['userID'];
    $resourceType = $order['buyType'];
    $change = $order['buyNum'] + $order['sendNum'];
    $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
    if ($ret) {
        HuiFuBaoPay::getInstance()->sendOrderToPayCenter($order_sn, $order['consumeNum'] / 100);
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
        AgentModel::getInstance()->rechargeCommission($order);
        AppModel::returnString('success');
    } else {
        //如果发货失败需要补单
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
        AppModel::returnString('success');
    }
} else {
    //支付失败
    PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['PAY_FAIL']);
    AppModel::returnString('success');
}

