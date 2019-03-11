<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\EmailModel;
use model\UserModel;
use notify\CenterNotify;

/**
 * 邮件业务
 * Class EmailAction
 */
class EmailAction extends AppAction
{
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
     * 获取玩家邮件列表
     * @param $param
     */
    public function userEmailList($param)
    {
        $userID = (int)$param['userID'];

        $isCountChange = EmailModel::getInstance()->checkEmailCount($userID);

        $userEmailList = EmailModel::getInstance()->getEmailList($userID);

        if ($isCountChange) {
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        }

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $userEmailList);
    }

    /**
     * 获取邮件详情
     * @param $param
     */
    public function emailInfo($param)
    {
        $userID = (int)$param['userID'];
        $emailID = (int)$param['emailID'];

        if (!EmailModel::getInstance()->isEmailExists($emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件不存在或者已经过期");
        }

        if (!EmailModel::getInstance()->isUserHaveEmail($userID, $emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件已经被删除");
        }

        $emailDetailInfo = EmailModel::getInstance()->getEmailDetailInfo($emailID);

        // 如果邮件未读 未读减1
        if (EmailModel::getInstance()->getEmailIsRead($userID, $emailID) == 0) {
            //设置已读
            EmailModel::getInstance()->setEmailRead($userID, $emailID);
            EmailModel::getInstance()->changeNotReadCount($userID, -1);
            // 小红点
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $emailDetailInfo);
    }

    /**
     * 删除玩家邮件
     * @param $param
     */
    public function delUserEmail($param)
    {
        $userID = (int)$param['userID'];
        $emailID = (int)$param['emailID'];

        if (!EmailModel::getInstance()->isEmailExists($emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件不存在或者已经过期");
        }

        if (!EmailModel::getInstance()->isUserHaveEmail($userID, $emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件已经被删除");
        }

        $isCountChange = EmailModel::getInstance()->delEmail($userID, $emailID);

        if ($isCountChange) {
            // 小红点
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 领取邮件奖励
     * @param $param
     */
    public function receiveEmailReward($param)
    {
        $userID = (int)$param['userID'];
        $emailID = (int)$param['emailID'];

        if (!EmailModel::getInstance()->isEmailExists($emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件不存在或者已经过期");
        }

        if (!EmailModel::getInstance()->isUserHaveEmail($userID, $emailID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "邮件已经被删除");
        }

        if (EmailModel::getInstance()->getEmailIsReceived($userID, $emailID) == 1) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "已经领取过了");
        }

        $emailDetailInfo = EmailModel::getInstance()->getEmailDetailInfo($emailID);

        $goodsList = $emailDetailInfo['goodsList'];

        //设置已领取
        EmailModel::getInstance()->setEmailReceived($userID, $emailID);

        //未领取数量-1
        EmailModel::getInstance()->changeNotReceivedCount($userID, -1);
        foreach ($goodsList as $goods) {
            $goodsType = $goods['goodsType'];
            $goodsNums = $goods['goodsNums'];
            EmailModel::getInstance()->changeUserResource($userID, $goodsType, $goodsNums, $emailDetailInfo['mailType']);

            switch ($emailDetailInfo['mailType']) {
                case EnumConfig::E_ResourceChangeReason['SHARE']: //分享奖励统计
                    if ($goodsType == EnumConfig::E_ResourceType['MONEY']) {
                        UserModel::getInstance()->addWebUserInfoValue($userID, 'shareGetMoney', $goodsNums);
                    } elseif ($goodsType == EnumConfig::E_ResourceType['JEWELS']) {
                        UserModel::getInstance()->addWebUserInfoValue($userID, 'shareGetJewels', $goodsNums);
                    }
                    break;
                default:
                    break;
            }
        }
        // 小红点
        CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
}
