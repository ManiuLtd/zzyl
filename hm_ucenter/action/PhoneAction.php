<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use helper\LogHelper;
use logic\MailLogic;
use model\AppModel;
use model\EmailModel;
use model\PhoneModel;
use notify\CenterNotify;
use logic\PhoneLogic;
use model\UserModel;
use config\MysqlConfig;
use manager\DBManager;

/**
 * 手机业务
 * Class PhoneAction
 */
class PhoneAction extends AppAction
{
    //每个手机每天发送验证码次数
    const DAY_SEND_CODE_COUNT = 300;

    //验证码有效时间 10 分钟
    const CODE_INVALID_TIME = 10 * 60;

    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    /**
     * 绑定手机信息
     * @param $params
     */
    public function bindPhoneInfo($params)
    {
        $userID = (int)$params['userID'];
        //获取手机号码
        $phone = PhoneModel::getInstance()->getUserBindPhone($userID);
        if (empty($phone['phone'])) {
            $data = ['msg' => ErrorConfig::ERROR_MSG_BIND_NOT, 'isBind' => false];
        } else {
            $data = ['msg' => ErrorConfig::ERROR_MSG_BIND_TOO, 'isBind' => true, 'phone' => $phone['phone'], 'name' => $phone['name']];
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }

    //绑定手机验证码
    public function bindPhoneCode($params)
    {
        $userID = (int)$params['userID'];
        $phone = $params['phone'];
//        $type = (int)$params['type'];

        //用户是否已经绑定手机号
        if (PhoneModel::getInstance()->isUserBindPhone($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_TOO);
        }

        //手机号码是否正确
        $myreg = "/^1[345678]{1}\d{9}$/";
        $res = preg_match($myreg, $phone);
        if (!$res) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NO);
        }

        //该手机号是否已经被绑定
        if (PhoneModel::getInstance()->isPhoneBind($phone)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NOT);
        }

        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['BIND']);
        $count = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['count'];
        //发送上限
        if ($count >= self::DAY_SEND_CODE_COUNT) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "每个手机号每天只能发送{$count}次");
        }
        // 绑定
        include dirname(dirname(__FILE__)) . '/aliyun-dysms-php-sdk/api_demo/SmsDemo.php';
        $code = rand(111111, 999999);
        $response = \SmsDemo::sendSms($phone, $code);
        //发送成功
        if ($response->Code == 'OK') {
            PhoneModel::getInstance()->setPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['BIND'], $count + 1, $code);
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_BIND_PHONE, ['code' => $code, 'count' => $count]);
        } else {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE);
        }
    }

    /**
     * 发送验证码(绑定手机号)
     * @param $params
     */
    public function sendBindCode($params) {
        $userID = (int)$params['userID'];
        $phone = $params['phone'];
//        $type = (int)$params['type'];
        if (empty($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
        }

        //用户是否已经绑定手机号
        if (PhoneModel::getInstance()->isUserBindPhone($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_TOO);
        }
        //该手机号是否已经被绑定
        if (PhoneModel::getInstance()->isPhoneBind($phone)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NOT);
        }
        $res = PhoneLogic::getInstance()->sendCode($phone);
        //发送成功
        if ($res['code'] == ErrorConfig::SUCCESS_CODE) {
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_BIND_PHONE, ['count' => $res['data']['count'], 'downTime' => $res['data']['downTime']]);
        } else {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, $res['msg']);
    }

    /**
     * 发送验证码(最新接口)
     * @param $params
     */
    public function sendCode($params) {
        $userID = (int)$params['userID'];
        $phone = $params['phone'];
        $type = (int)$params['type'];
        //如果是提现获取验证码，则判断是否绑定手机号
        if($type == 3 || $type == 4){
            //获取绑定的手机号
            $arrayKeyValue = ['phone'];
            $where = "userID = {$userID}";
            $res = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);
            $phone = $res['phone'];
            if(empty($phone)) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_CODE_PERSONAL_CENTER);
        }
        if (!in_array($type, EnumConfig::E_PhoneCodeType)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_CODE_TYPE_NO);
        }

//        $type = (int)$params['type'];
//        $res = PhoneLogic::getInstance()->sendCode($phone, EnumConfig::E_PhoneCodeType['PASSWORD']);
        $res = PhoneLogic::getInstance()->sendCode($phone, $type, $userID);
        //发送成功
        if ($res['code'] == ErrorConfig::SUCCESS_CODE) {
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_BIND_PHONE, ['count' => $res['data']['count'], 'downTime' => $res['data']['downTime']]);
        } else {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, $res['msg']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, $res['msg']);
    }

    //绑定手机
    public function bindPhone($params)
    {
        $userID = (int)$params['userID'];//用户id
        $phone = $params['phone'];//手机号码
        $wechat = $params['wechat'];//微信号
        $code = $params['code'];//验证码码
        $password = $params['password'];//密码

        //用户是否已经绑定手机号
        if (PhoneModel::getInstance()->isUserBindPhone($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_TOO);
        }

        //验证手机号码
        $myreg = "/^1[345678]{1}\d{9}$/";
        $res = preg_match($myreg, $phone);
        if (!$res) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NO);
        }

        //该手机号是否已经被绑定
        if (PhoneModel::getInstance()->isPhoneBind($phone)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NOT);
        }

        // 验证验证码
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['BIND']);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];
        if ($phoneCode != $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        $resName = PhoneModel::getInstance()->userBindPhone($userID, $phone, $password, $wechat);

        $config = PhoneModel::getInstance()->getconfig();
        $sendMoney = $config['BindPhoneSendMoney'];
        // 创建邮件
        $title = "绑定手机号码成功奖励";
//        $content = "绑定手机号码：{$phone} 成功，\n获得奖励{$sendMoney}金币";
//        $goodsArray = [
//            EnumConfig::E_ResourceType['MONEY'] => $config['BindPhoneSendMoney'],
//        ];
//        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
//        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BIND_PHONE'], $title, $content, $goodsList);
//        // 添加邮件
//        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
//        // 小红点
//        CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        //-------------------------------------rewards------------------------------------
        $res = MailLogic::getInstance()->sendRewards($title, "绑定手机号码：{$phone} 成功", $userID, $sendMoney, 0);
        if (ErrorConfig::ERROR_CODE == $res['code']) {
            LogHelper::printError('sendRewards error' . __CLASS__ . '-' . __FUNCTION__);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, ['rename' => $resName]);
    }

    //重设手机密码验证码
    public function resetPhoneCode($params)
    {
        $phone = $params['phone'];

        //验证手机号码
        $myreg = "/^1[345678]{1}\d{9}$/";
        $res = preg_match($myreg, $phone);
        if (!$res) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NO);
        }
        //该手机号是否没被绑定
        if (!PhoneModel::getInstance()->isPhoneBind($phone)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PHONE_NOT_BIND);
        }
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['PASSWORD']);
        $count = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['count'];
        //发送上限
        if ($count >= self::DAY_SEND_CODE_COUNT) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "每个手机号每天只能发送{$count}次");
        }

        include dirname(dirname(__FILE__)) . '/aliyun-dysms/SmsDemo.php';
        $code = rand(111111, 999999);
        $response = \SmsDemo::sendSms($phone, $code);
        //发送成功
        if ($response->Code == 'OK') {
            PhoneModel::getInstance()->setPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['PASSWORD'], $count + 1, $code);
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_BIND_PHONE, ['code' => $code]);
        } else {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE);
        }
    }

    //重设手机密码
    public function resetPhonePassword($params)
    {
        $phone = $params['phone'];
        $code = $params['code'];
        $password = $params['password'];

        //验证手机号码
        $myreg = "/^1[345678]{1}\d{9}$/";
        $res = preg_match($myreg, $phone);
        if (!$res) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NO);
        }

        //该手机号是否没被绑定
        if (!PhoneModel::getInstance()->isPhoneBind($phone)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PHONE_NOT_BIND);
        }

        // 验证验证码
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($phone, EnumConfig::E_PhoneCodeType['PASSWORD']);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];
        if ($phoneCode != $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        //新密码不能与银行密码相同
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['bankpasswd'], "phone = {$phone}");
        if($password == md5($resinfo['bankpasswd'])){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '新密码不能与银行密码相同');
        }
        
        PhoneModel::getInstance()->updatePhonePassword($phone, $password);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::ERROR_MSG_BIND_SUCCESS);
    }
}
