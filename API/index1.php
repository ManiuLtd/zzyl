<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting();
include '../common/config.php';
include '../common/db.php';
include '../common/function.php';
include './SendFunction.class.php';
include './Socket.class.php';
include './GetRedis.class.php';
//session_start();
//判断seesion
//if(isset($_SESSION['db']) && !empty($_SESSION['db'])){			
//	$db = json_decode($_SESSION['db']);
//}else{
//	$db = new db();
//}

//$db = new db();
//$sql = "select * from web_adminConfig";
//$config = $db -> getAll($sql);
//$c = array();
//foreach($config as $key => $value){
//    $c[$config[$key]['key']] = $config[$key]['value'];
//}
$act = m_request('action');
switch ($act){
	//请求测试文件
    	case 'test_request':
    		include './action/test_request.php';
    	break;
	//发送测试文件
	case 'test':
		include './action/test.php';
		break;
	//获取闲时公告
	case 'GetNotice':
		include './action/GetNotice.php';
	break;
	//获取游戏信息   //传递参数：gameID  返回该条数据信息
	case 'GetGameInfo':
		include './action/GetGameInfo.php';
		break;

		//获取房间信息   //传递参数：roomID  返回该条数据信息
	case 'GetRoomInfo':
		include './action/GetRoomInfo.php';
		break;
	//获取游戏配置 pribateDeskConfig  传递参数：gameID 返回所有匹配的信息
	case 'privateDeskConfig':
		include './action/privateDeskConfig.php';
		break;

	//获取玩家基本信息 userinfo  传递参数：userID 返回该条数据
	case 'GetUserInfo':
		include './action/GetUserInfo.php';
		break;

//反馈   接收客户端的参数  UserID  反馈信息
  case 'Feedback':
		include './action/Feedback.php';
		break;
//胜局榜   返回前n名
	case 'Victory':
		include './action/Victory.php';
		break;
//土豪榜  接收参数type：金币还是房卡
	case 'Vulgar':
		include './action/Vulgar.php';
		break;
//获取大转盘配置
  case 'GetTurntable':
		include './action/GetTurntable.php';
		break;
//大转盘
	case 'Turntable':
		include './action/Turntable.php';
		break;
//判断是否可以使用大转盘
	case 'IsUseTurntable':
		include './action/IsUseTurntable.php';
		break;
//获取签到配置
	case 'GetSignConfig':
		include './action/GetSignConfig.php';
		break;
//签到
	case 'Sign':
		include './action/Sign.php';
		break;
		//签到
	case 'IsUseSign':
		include './action/IsUseSign.php';
		break;

		//获取系统邮件
	case 'GetSystemEmail':
		include './action/GetSystemEmail.php';
		break;

		//获取转换汇率
	case 'GetRate':
		include './action/GetRate.php';
		break;	

		//获取游戏当前平台版本号
	case 'PlatformVersion':
		include './action/PlatformVersion.php';
		break;	

		//获取游戏当前版本号
	case 'GameVersion':
		include './action/GameVersion.php';
		break;

		//获取商品信息
	case 'GetGoods':
		include './action/GetGoods.php';
		break;	
		 //生成订单
    case 'Order':
        include './action/Order.php';
        break;
		 //生成订单
    case 'notify_url':
        include './action/notify_url.php';
        break;
   case 'egg':
	include './action/egg.php';
	break;
   //获取最新的一条特殊公告
    case 'get_new_special_notice':
    	include './action/get_new_special_notice.php';
    	break;
	//绑定代理号
   	case 'bindInviteCode':
   		include './action/bindInviteCode.php';
   		break;
   	//解绑代理号
   	case 'leave_agentid':
   		include './action/leave_agentid.php';
   		break;
   	//判断是否绑定代理号
   	case 'getInviteCodeInfo':
   		include './action/getInviteCodeInfo.php';
   		break;
	//随机生成ip
   	case 'RandIP':
   		include './action/RandIP.php';
   		break;
	//用户反馈回复
   	case 'FeedbackCallback':
   		include './action/FeedbackCallback.php';
   		break;
   	//获取我的反馈记录
   	case 'getMyFeedback':
   		include './action/getMyFeedback.php';
   		break;
   	//反馈详情
   	case 'getFeedbackDetails':
   		include './action/getFeedbackDetails.php';
   		break;
	//删除反馈
   	case 'DelMyFeedback':
		include './action/DelMyFeedback.php';
		break;
	//分享送房卡以及记录
	case 'ShareGame':
		include './action/ShareGame.php';
		break;
	//获取游戏配置
    case 'GetConfig':
    	include './action/GetConfig.php';
    break;
	//找回银行密码
	case 'ForgotPassword':
		include './action/ForgotPassword.php';
		break;

	//验证黑名单
	case 'CheckLogin':
		include './action/CheckLogin.php';
		break;
	//测试
	case 'VersionTest':
		include './action/VersionTest.php';
		break;
	case 'PlatformTest':
		include './action/PlatformTest.php';
		break;
	default:
		$data['status'] = 0;
		$data['msg'] = '请求方法错误';
		print_json($data);
		break;
}
