<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/11
 * Time: 16:35
 */

namespace logic;
use config\EnumConfig;
use config\ErrorConfig;
use helper\LogHelper;
use model\PhoneModel;

class PhoneLogic extends BaseLogic
{
    //每个手机每天发送验证码次数
    const DAY_SEND_CODE_COUNT = 300;

    //验证码有效时间 10 分钟
    const CODE_INVALID_TIME = 10 * 60;
    const DOWN_TIME = 90;//多久后可以重发
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    protected function verifySendCode($type, $phone, $userID) {
        switch ($type) {
            case EnumConfig::E_PhoneCodeType['BIND']:
                if (empty($userID)) {
                    return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_USERID_REQUIRED);
                }

                //用户是否已经绑定手机号
                if (PhoneModel::getInstance()->isUserBindPhone($userID)) {
                    return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_TOO);
                }
                //该手机号是否已经被绑定
                if (PhoneModel::getInstance()->isPhoneBind($phone)) {
                    return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NOT);
                }
                break;
            case EnumConfig::E_PhoneCodeType['PASSWORD']:
                //该手机号是否没被绑定
                if (!PhoneModel::getInstance()->isPhoneBind($phone)) {
                    return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PHONE_NOT_BIND);
                }
                break;
            case EnumConfig::E_PhoneCodeType['REGISTER']:
                //该手机号是否已经被绑定
                if (PhoneModel::getInstance()->isPhoneBind($phone)) {
                    return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NOT);
                }
                break;
            default:

                break;
        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE, '验证通过');
    }

    public function sendCode($phone, $type = EnumConfig::E_PhoneCodeType['BIND'], $userID = 0) {

        //手机号码是否正确
        $myreg = "/^1[345678]{1}\d{9}$/";
        $res = preg_match($myreg, $phone);
        if (!$res) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => ErrorConfig::ERROR_MSG_BIND_PHONE_NO];
        }
        $res = $this->verifySendCode($type, $phone, $userID);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            return $this->returnData(ErrorConfig::ERROR_CODE, $res['msg']);
        }

        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($phone, $type);
        $count = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['count'];
        //发送上限
        if ($count >= self::DAY_SEND_CODE_COUNT) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => "每个手机号每天只能发送{$count}次"];
        }
        // 绑定
        include dirname(dirname(__FILE__)) . '/aliyun-dysms-php-sdk/api_demo/SmsDemo.php';
        $code = rand(111111, 999999);
        $response = \SmsDemo::sendSms($phone, $code, $type);
        //发送成功
        if ($response->Code == 'OK') {
            PhoneModel::getInstance()->setPhoneCodeInfo($phone, $type, $count + 1, $code);
            return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => 'ok', 'data' => ['code' => $code, 'count' => $count, 'downTime' => self::DOWN_TIME]];
        } else {
            LogHelper::printError('aliCodeResponse:' . serialize($response));
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => ErrorConfig::ERROR_MSG_BIND_PHONE];
        }
    }
}