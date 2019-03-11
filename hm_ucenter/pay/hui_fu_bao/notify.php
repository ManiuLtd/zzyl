<?php

use config\EnumConfig;
use logic\NotifyLogic;
use model\AgentModel;
use model\AppModel;
use model\LobbyModel;
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
//TODO 参数验证太简单了 应该要使用第三方提供的签名算法验证参数
if (!isset($data['agent_bill_id']) || !isset($data['result'])) {
    AppModel::returnString('parameter error');
}

//订单是否存在
$order_sn = $data['agent_bill_id'];
$order = PayModel::getInstance()->getPayOrder($order_sn);
if (empty($order)) {
    AppModel::returnString('order is empty');
}

//订单状态是否NEW
if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
    AppModel::returnString('order is not NEW');
}

//支付成功
if ($data['result'] == 1) {
    //充值增加玩家资源
    $userID = $order['userID'];
    $resourceType = $order['buyType'];
    $change = $order['buyNum'];// + $order['sendNum'];
    $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
    if ($ret) {
        HuiFuBaoPay::getInstance()->sendOrderToPayCenter($order_sn, $order['consumeNum'] / 100);
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
        AgentModel::getInstance()->rechargeCommission($order);
        $user = LobbyModel::getInstance()->getUserInfo($userID, ['jewels', 'name']);
//        NotifyLogic::getInstance()->sendNormalNotify('玩家' . $user['name'] . '一掷千金，充值' . $change . '金币，壕气冲天！');
        NotifyLogic::getInstance()->sendNormalNotify('玩家' . $user['name'] . '一掷千金，充值' . $change . '金币，壕气冲天！');
        AppModel::returnString('ok');
    } else {
        //如果发货失败需要补单
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
        AppModel::returnString('ok');
    }
} else {
    //支付失败
    PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['PAY_FAIL']);
    AppModel::returnString('ok');
}

