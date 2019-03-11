<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting();
include '../../common/config.php';
include '../../common/db.php';
include '../../common/function.php';
include '../GetRedis.class.php';
include '../SendFunction.class.php';
include '../Socket.class.php';
include './wxpay.php';
$db = new db();
$act = m_request('action');
switch ($act){
	//获取商品信息
	case 'GetGoods':
		include './action/GetGoods.php';
		break;
	//下订单
	case 'Order':
		include './action/Order.php';
		break;
	//苹果支付验证
    case 'apple_pay_check':
    	include './action/apple_pay_check.php';
    	break;
    //苹果支付下订单
    case 'apple_order':
    	include './action/apple_order.php';
    	break;
	default:
	
   //获取订单记录
    case 'get_orders_record':
    	include './action/get_orders_record.php';
    	break;
	//15173支付
   case 'Order_15173':
	include './action/Order_15173.php';
	break;
//处理兑换
    case 'convert':
    	include './action/convert.php';
    	break;
	$data['status'] = 0;
		$data['msg'] = '请求方法错误';
		print_json($data);
		break;
}
