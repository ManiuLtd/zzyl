<?php

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlTableFieldConfig;

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 */
function check_verify($code, $id = 1){
    ob_clean();
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}
/**
 * [删除目录]
 * @param  string $path [description]
 * @return [boolean]       [description]
 */
function drop_dir($path = ''){
    if (is_file($path)) {
        unlink($path);
    } else if (is_dir($path)) {
        if (($dir = opendir($path)) !== false) {
            while (($file = readdir($dir)) !== false) {
                if ($file != '.' && $file != '..') {
                    drop_dir($path . '/' . $file);
                }
            }
            rmdir($path);
        }
    }
}
/**
 * 阿里大鱼短信注册
 * @param  string 	$phone 	手机号
 * @param  string	$tpl 	短信模板
 * @return boolean 			发送成功返回true;失败返回false
 */
function sendSms($phone='',$tpl='',$code=''){
    Vendor('Alidayu.TopSdk');
    $c = new \TopClient;
    $c->appkey = C('Alidayu.appkey');
    $c->secretKey = C('Alidayu.secretkey');
    $req = new \AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("邹金顺");
    $req->setSmsParam("{\"code\":\"$code\"}");
    $req->setRecNum($phone);
    $req->setSmsTemplateCode($tpl);
    $resp = $c->execute($req);
    if($resp->success){
        return true;
    } else {
        return false;
    }
}
/**
 * 邮件发送
 * @param  string  $to  	 收件者
 * @param  string  $title    标题
 * @param  string  $content  内容
 * @return boolean			 成功返回 true 失败 返回 false
 */
function sendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail= new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->IsSMTP();
    $mail->Host = C('Email.smtp');
    $mail->Port = 25;
    $mail->From = C('Email.username');
    $mail->FromName = C('webname');
    $mail->SMTPAuth = true;
    $mail->Username = C('Email.username');
    $mail->Password = C('Email.password');
    $mail->Subject = $title;
    $mail->AltBody = "text/html";
    $mail->Body = $content;
    $mail->IsHTML(true);
    $mail->AddAddress(trim($to));
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}
/**
 * 支付宝扫码支付
 * @param array $param 商品参数
 * @return boolean     表单参数
 */
function alipay($param=array()){
    Vendor('Alipay.pagepay.buildermodel.AlipayTradePagePayContentBuilder');
    Vendor('Alipay.pagepay.service.AlipayTradeService');
    $config = C('Alipay');
    $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
    $payRequestBuilder->setBody($param['body']); //描述
    $payRequestBuilder->setSubject($param['subject']); //订单名称
    $payRequestBuilder->setTotalAmount($param['total_amount']); //金额
    $payRequestBuilder->setOutTradeNo($param['out_trade_no']); //订单号
    $aop = new \AlipayTradeService($config);
    $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    //输出表单
    return $response;
}
/**
 * 微信扫码支付
 * @param  array   $param   参数封装
 * @return string           成功返回 url 失败 返回false
 */
function wxpay($param=array()){
    Vendor('Wxpay.WxPay#Api');
    Vendor('Wxpay.WxPay#NativePay');
    $notify = new \NativePay();
    $input = new \WxPayUnifiedOrder();
    $input->SetBody($param['body']); //描述
    $input->SetAttach($param['attach']); //附加数据
    $input->SetOut_trade_no($param['order_sn']);
    $input->SetTotal_fee($param['fee']); //总金额 单位分
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 6000));
    $input->SetGoods_tag($param['goods_tag']);
    $input->SetNotify_url($param['url']); //回调
    $input->SetTrade_type("NATIVE");
    $input->SetProduct_id($param['product']);
    $result = $notify->GetPayUrl($input);
    $url = $result["code_url"];
    if($url){
        return $url;
    } else {
        return false;
    }
}

function ChangeReasonList(){
    
    $ChangeReason =   EnumConfig::E_ResourceChangeReason;
    $changeType=[];
    foreach ($ChangeReason as $k=>$v){
		$changeType[$k]=getChangeType($v);
    }
    return $changeType;
}

function getChangeType($v){
    $changeType=[];
    switch ($v)
    {
        case 1:
            $changeType=['name'=>'创建房间消耗','val'=>$v];
            break;
        case 2:
            $changeType=['name'=>'游戏开始','val'=>$v];
            break;
        case 3:
            $changeType=['name'=>'游戏结束','val'=>$v];
            break;
        case 4:
            $changeType=['name'=>'大结算没有生成战绩返还','val'=>$v];
            break;
        case 5:
            $changeType=['name'=>'大结算普通支付','val'=>$v];
            break;
        case 6:
            $changeType=['name'=>'游戏大结算AA支付','val'=>$v];
            break;
        case 7:
            $changeType=['name'=>'金币房抽水返还','val'=>$v];
            break;
        case 8:
            $changeType=['name'=>'房间抽水扣除','val'=>$v];
            break;
        case 9:
            $changeType=['name'=>'系统补贴金币','val'=>$v];
            break;
        case 10:
            $changeType=['name'=>'注册','val'=>$v];
            break;
        case 11:
            $changeType=['name'=>'魔法表情','val'=>$v];
            break;
        case 1000:
            $changeType=['name'=>'银行存钱','val'=>$v];
            break;
        case 1001:
            $changeType=['name'=>'银行取钱','val'=>$v];
            break;
        case 1002:
            $changeType=['name'=>'银行转账','val'=>$v];
            break;
        case 1003:
            $changeType=['name'=>'转赠','val'=>$v];
            break;
        case 1004:
            $changeType=['name'=>'救济金','val'=>$v];
            break;
        case 1005:
            $changeType=['name'=>'签到','val'=>$v];
            break;
        case 1006:
            $changeType=['name'=>'后台充值','val'=>$v];
            break;
        case 1007:
            $changeType=['name'=>'商城支付充值','val'=>$v];
            break;
        case 1008:
            $changeType=['name'=>'代理充值','val'=>$v];
            break;
        case 1009:
            $changeType=['name'=>'转盘','val'=>$v];
            break;
        case 1010:
            $changeType=['name'=>'分享','val'=>$v];
            break;
        case 1011:
            $changeType=['name'=>'好友打赏','val'=>$v];
            break;
        case 1012:
            $changeType=['name'=>'绑定手机','val'=>$v];
            break;
        case 1013:
            $changeType=['name'=>'世界广播','val'=>$v];
            break;
        case 1014:
            $changeType=['name'=>'邮件奖励','val'=>$v];
            break;
        case 1015:
            $changeType=['name'=>'绑定代理','val'=>$v];
            break;
        case 1016:
            $changeType=['name'=>'邀请进入游戏','val'=>$v];
            break;
        case 1017:
            $changeType=['name'=>'代理转赠','val'=>$v];
            break;
        default:
            $changeType=['name'=>'其他','val'=>$v];
    }
	return  $changeType;
}

function clubRoomType($t)
{
    $arr = [
        '金币场',
        '积分房',
        '金币房',
        'VIP房',
    ];

    return $arr[$t];
}

// 火币变化
function firCoinChange($c)
{
    $arr = [
        1 => '火币充值',
        '火币提现',
        '游戏结束',
        '退出俱乐部',
    ];

    return $arr[$c];
}

// 金币变化
function moneyChange($m)
{
    $arr = [
        1 => '银行存钱',
        '银行取钱',
        '转赠失去',
        '转赠获得',
        '领取补助',
        '平台签到',
        '平台充值',
        '平台提现',
        '平台转盘',
        '平台分享',
        '平台砸金蛋(消耗)',
        '平台砸金蛋',
        '平台代理',
        '游戏结束',
        '魔法表情(消耗)',
        '开局扣除(消耗)',
        '领取好友打赏',
        '创建房间消耗',
        '大结算没有生成战绩返还',
        '绑定手机号',
        '游戏大结算AA支付',
        '金币房抽水',
    ];

    return $arr[$m];
}


// 钻石变化
function jewelsChange($j)
{
    $arr = [
        1 => '发送世界广播',
        '转赠扣除',
        '转赠增加',
        '平台签到',
        '平台充值',
        '平台提现',
        '提现返还',
        '平台转盘',
        '平台分享',
        '平台砸蛋',
        '大结算普通支付',
        '大结算AA支付',
        '平台代理',
        '创建房间消耗',
        '大结算没有生成战绩返还',
        '绑定手机号',
        '游戏大结算',
        '游戏大结算AA支付',
    ];

    return $arr[$j];
}

/**
 * 获取时间范围
 * @param $timeType
 * @return array
 * @throws Exception
 */
function getRangeOfTime($timeType) {
    $arrTimeType = [
        'today' => 'today',
        'thisMouth' => 'thisMouth',
    ];
    switch ($arrTimeType[$timeType]) {
        case 'today':
            //今天开始时间
            $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            //今天结束时间
            $end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
            break;

        case 'thisMouth':
            //本月开始时间
            $begin = mktime(0, 0, 0, date('m'), 1, date('Y'));
            //本月结束时间
            $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
            break;
        default:
            throw new \Exception("时间范围类型不存在", 1);
            break;
    }
    return ['begin' => $begin, 'end' => $end];
}

function getArrHideJewelsField() {
    return [
        MysqlTableFieldConfig::GAME_CONFIG_REGISTER_GIVE_JEWELS,
        MysqlTableFieldConfig::GAME_CONFIG_SEND_HORN_COST_JEWELS,
        MysqlTableFieldConfig::GAME_CONFIG_BUYING_DESK_COUNT,
        MysqlTableFieldConfig::GAME_CONFIG_SEND_GIFT_MIN_JEWELS,
        MysqlTableFieldConfig::GAME_CONFIG_SEND_GIFT_MY_LIMIT_JEWELS,

    ];
}

function validSearchTimeRange(&$starttime, &$endtime) {
    $timeRange = ['neq', 0];
    if (!empty($starttime) && !empty($endtime)) {
        $starttime = strtotime($starttime);
        $endtime = strtotime($endtime);// + 24 * 3600 - 1;
        if ($starttime > $endtime) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '开始时间不能大于结束时间'];
        }
        $timeRange = ['between', [$starttime, $endtime]];
    } elseif(!empty($starttime)) {
//        $this->error('请选择结束时间');
        return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '请选择结束时间'];
    } elseif (!empty($endtime)) {
//        $this->error('请选择开始时间');
        return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '请选择开始时间'];
    }
    return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => 'success', 'data' => $timeRange];
}

?>
