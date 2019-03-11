<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 14:00
 */

namespace logic;


use config\EnumConfig;
use config\ErrorConfig;
use helper\FunctionHelper;
use model\EmailModel;
use notify\CenterNotify;

class MailLogic extends BaseLogic
{
    private static $_instance = null;

    public static function getInstance() {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 发送奖励
     * @param $title 邮件标题
     * @param $subTitle 副标题
     * @param $receiveUserID 接收邮件用户id
     * @param $diamonds 奖励砖石数
     * @param $money 奖励金币数
     */
    public function sendRewards($title, $subTitle, $receiveUserID, $money, $diamonds) {

//        $title = "首次进入游戏奖励";
        $arrReward = [];
        if (!empty($money)) {
            $arrReward[] = FunctionHelper::moneyOutput($money) . "金币";
        } elseif (!empty($diamonds)) {
            $arrReward[] = FunctionHelper::moneyOutput($diamonds, EnumConfig::E_ResourceType['JEWELS']) . "钻石";
        } else {
//            throw new \Exception("奖励数需大于0");
            return $this->returnData(ErrorConfig::ERROR_CODE, '奖励数需大于0');
        }

        $content = $subTitle . "。\n您获得奖励：" . implode(',', $arrReward);
        $goodsArray = [
            EnumConfig::E_ResourceType['JEWELS'] => $diamonds,
            EnumConfig::E_ResourceType['MONEY'] => $money,
        ];
        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['INVITE_ENTER'], $title, $content, $goodsList);
        // 添加邮件
        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $receiveUserID);
        // 小红点
        CenterNotify::sendRedSport($receiveUserID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        return $this->returnData(ErrorConfig::SUCCESS_CODE, '发送成功');
    }
}