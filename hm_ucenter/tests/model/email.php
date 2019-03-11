<?php

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\EmailModel;
use model\PhoneModel;
use notify\CenterNotify;

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';
class email {
    function __construct() {
        $this->sendEmail();
    }

    public function sendEmail () {
        $phone = '15626519209';
        // $userID = '119012';
        $params = $_GET;
        $userID = isset($params['userid']) ? $params['userid'] : 0;
        if (empty($userID))
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, 'param userid can be not null');
        $config = PhoneModel::getInstance()->getconfig();
        $sendMoney = $config['BindPhoneSendMoney'];
        // 创建邮件
        $title = "绑定手机号码成功奖励";
        $content = "绑定手机号码：{$phone} 成功，\n获得奖励{$sendMoney}金币";
        $goodsArray = [
            EnumConfig::E_ResourceType['MONEY'] => $config['BindPhoneSendMoney'],
        ];
        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BIND_PHONE'], $title, $content, $goodsList);
        // 添加邮件
        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
        // 小红点
        CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
}

new email();
