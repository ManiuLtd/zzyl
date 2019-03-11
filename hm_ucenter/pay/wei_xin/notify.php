<?php

use config\EnumConfig;
use helper\FunctionHelper;
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

$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
//将xml格式转为数组
$data = FunctionHelper::xmlToArray($xml);
if ($data['return_code'] == 'SUCCESS') {
    $order_sn = $data['out_trade_no'];
    //订单是否存在
    $order = PayModel::getInstance()->getPayOrder($order_sn);
    if (empty($order)) {
        AppModel::returnXml(['return_code' => 'FAIL', 'return_msg' => '订单不存在']);
    }

    //订单状态是否NEW
    if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
        AppModel::returnXml(['return_code' => 'FAIL', 'return_msg' => '订单已经处理过']);
    }
    $tmpData = $data;
    unset($tmpData['sign']);
    $sign = WeiXinPay::getInstance()->make_sign($tmpData);
    if ($sign != $data['sign']) {
        AppModel::returnXml(['return_code' => 'FAIL', 'return_msg' => '签名不正确']);
    }
    //充值增加玩家资源
    $userID = $order['userID'];
    $resourceType = $order['buyType'];
    $change = $order['buyNum'] + $order['sendNum'];
    $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
    if ($ret) {
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
        AgentModel::getInstance()->rechargeCommission($order);
        AppModel::returnXml(['return_code' => 'SUCCESS', 'return_msg' => '发货成功']);
    } else {
        //如果发货失败需要补单
        PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
        AppModel::returnXml(['return_code' => 'SUCCESS', 'return_msg' => '发货失败']);
    }
} else {
    //支付失败
    PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['PAY_FAIL']);
    AppModel::returnXml(['return_code' => 'SUCCESS', 'return_msg' => '支付失败']);
}





