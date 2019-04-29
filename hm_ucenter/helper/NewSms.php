<?php
namespace helper;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * 日志 帮助类
 * Class LogHelper
 */
class NewSms
{
    const SENDURL = 'http://api.qirui.com:7891/mt';

    private $apiKey = '2266940010';
    private $apiSecret = '9cf76dbbe88ebfc687bf';



    /**
     * 短信发送
     * @param string $phone   	手机号码
     * @param string $content 	短信内容
     * @param integer $isreport	是否需要状态报告
     * @return void
     */
    //public function send($phone, $content, $isreport = 1)
    public function send($phone, $code, $type, $isreport = 1)
    {
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        if($type == 0){
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在绑定手机号，感谢您的支持！'; //绑定手机号
            //$content = '【启瑞云】您的验证码是:'.$code;
        }elseif ($type == 1){
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在找回密码，感谢您的支持！';
            //$content = '【启瑞云】您的验证码是:'.$code;
        }elseif ($type == 2){
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在注册成为会员，感谢您的支持！';
            //$content = '【启瑞云】您的验证码是:'.$code;
        }elseif ($type == 3){
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在绑定收款账户，感谢您的支持！';//绑定支付宝银行卡收款账户
            //$content = '【启瑞云】您的验证码是:'.$code;
        }elseif ($type == 4){
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在设置个人游戏银行密码，感谢您的支持！';
            //$content = '【启瑞云】您的验证码是:'.$code;
        }else{
            $content = '【金尊娱乐】您的校验码：'.$code.'，您正在注册成为会员，感谢您的支持！';//默认注册成为会员
            //$content = '【启瑞云】您的验证码是:'.$code;
        }

        $requestData = array(
            'un' => $this->apiKey,
            'pw' => $this->apiSecret,
            'sm' => $content,
            'da' => $phone,
            'rd' => $isreport,
            'dc' => 15,
            'rf' => 2,
            'tf' => 3,
        );

        $url = self::SENDURL . '?' . http_build_query($requestData);
        return $this->request($url);
    }

    /**
     * 请求发送
     * @return string 返回发送状态
     */
    private function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
