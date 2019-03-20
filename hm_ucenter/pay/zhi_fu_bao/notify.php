<?php

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;
use model\AppModel;
use model\PayModel;
use model\UserModel;
use pay\AppPay;
use pay\zhi_fu_bao\AlipayTradeAppPayRequest;
use pay\zhi_fu_bao\AopClient;
use helper\LogHelper;
use pay\zhi_fu_bao\ZhiFuBaoPay;

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

$data = $_POST;
file_put_contents('paydata.txt', $data);
//订单是否存在
if (!isset($data['out_trade_no'])) {
    AppModel::returnString('parameter error');
}
$order_sn = $data['out_trade_no'];

$order = PayModel::getInstance()->getPayOrder($order_sn);
if (empty($order)) {
    AppModel::returnString('order is empty');
}

//订单状态是否NEW
if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
    AppModel::returnString('order is not NEW');
}

//$arr=$_POST;

$config = ZhiFuBaoPay::getInstance()->initPayConfig();

$client = new AopClient();
$client->alipayrsaPublicKey = $config['callback_server_PK'];//'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsL31HY2zkk+urNAHJuga5eYe1zgdEPEic3cpvoEBaKbIv8OJMsfgTQIXDxhMs2hu3xwKr+CCq5cYVfvgKVSrn7CT6jjXrbL+4D0r9p8wSChrEsljbIZrznHUWQ+HkNxrwDjDPclK1AvV9yYmsuDor1w9S7EUHyzpApXChzk/5FwTjMwv13KpodOrKWEbHyR825s7qyE/4Y1lV/PIyI7UTh5X9wOhxifCb2XOwdtuGUm+gjgZZU/0s7CkR6ww8zYztBgxfB+Z2Rm/g6k5IYr/ojQo+2rye6wwacHTwnR5O6qEFfNr6zJdAMIMx8Y2ORRGLgnJgnxVvvpJE1dp6bYXSQIDAQAB';//$config['alipay_public_key'];

$signVerifyData = $_POST;
$signVerify = $client->rsaCheckV1($signVerifyData, null, 'RSA2');
//$signVerify = $client->rsaSign($signVerifyData, $client->signType);
//$sign = XinBaoPay::getInstance()->pay_sign($data);
if (!$signVerify) {
    AppModel::returnString('fail');
}
$consumeNum = FunctionHelper::moneyOutput($order['consumeNum'], $order['consumeType']);
if ($_POST['total_amount'] != $consumeNum) {
    AppModel::returnString('fail');
}
if (!ZhiFuBaoPay::getInstance()->checkAppID($_POST['app_id'])) {
    LogHelper::printError('校验失败' . __LINE__);
    AppModel::returnString('fail');
}
if (!ZhiFuBaoPay::getInstance()->checkParnetID($_POST['seller_id']) && !ZhiFuBaoPay::getInstance()->checkParnetID($_POST['seller_email'])) {
    LogHelper::printError('校验失败' . __LINE__);
    AppModel::returnString('fail');
}
//1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
//2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
//3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email），
//4、验证app_id是否为该商户本身。上述1、2、3、4有任何一个验证不通过，则表明本次通知是异常通知，务必忽略。
//在上述验证通过后商户必须根据支付宝不同类型的业务通知，正确的进行不同的业务处理，并且过滤重复的通知结果数据。
//在支付宝的业务通知中，只有交易通知状态为TRADE_SUCCESS或TRADE_FINISHED时，支付宝才会认定为买家付款成功。
//支付成功
if ($data['trade_status'] == 'TRADE_SUCCESS' || 'TRADE_FINISHED' == $data['trade_status']) {
    //先写订单状态 再发货
    $updateStatus = PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
    if (!empty($updateStatus)) {
        //充值增加玩家资源
        $userID = $order['userID'];
        $resourceType = $order['buyType'];
        $change = $order['buyNum'] + $order['sendNum'];
        $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
        //如果发货失败需要补单
        if (!$ret) {
            \helper\LogHelper::printError('充值成功，但订单缺货，需补发' . var_export($_POST, true));
            PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
        }
        //插入redis通知消息
        /*$redis = \manager\RedisManager::getRedis();
        $redis->lPush("orderNotify",$order_sn);*/

        \helper\LogHelper::pushSpeech();
        AppModel::returnString('success');
    } else {
        \helper\LogHelper::printError('充值成功，但订单更新失败' . var_export($_POST, true));
        AppModel::returnString('fail');
    }
} else {
    //支付失败
    \helper\LogHelper::printDebug('logPayDebug:----------------------sign fail22222' . var_export($_POST, true));
    \helper\LogHelper::printDebug('logPayDebug:----------------------sign fail222223' . var_export($data, true));
    PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['PAY_FAIL']);
    AppModel::returnString('success');
}

