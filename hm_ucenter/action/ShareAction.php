<?php
namespace action;
use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\EmailModel;
use model\ShareModel;
use notify\CenterNotify;

/**
 * 分享业务
 * Class ShareAction
 */
class ShareAction extends AppAction
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

    //分享成功
    public function shareSuccess($params)
    {
        $userID = (int)$params['userID'];
        $share_address = (int)$params['shareType'];
        $gameConfig = ShareModel::getInstance()->getGameConfig();
        $beginTime = (int)$gameConfig['share_begin_time']; // 开始
        $endTime = (int)$gameConfig['share_end_time']; // 结束
        $share_send_jewels = (int)$gameConfig['share_send_jewels'];//分享获得钻石奖励
        $share_send_money = (int)$gameConfig['share_send_money'];//分享获得金币奖励

        $userInfo = ShareModel::getInstance()->getUserInfo($userID, ['name']);

        // 开始时间
        if (time() > $beginTime && time() < $endTime) {
            //分享记录数据
            $data = [];
            $data['userid'] = $userID;//用户id
            $data['name'] = $userInfo['name'];//用户名称
            $data['share_address'] = $share_address;//分享类型
            $data['share_time'] = time();//分享时间
            $data['send_money'] = 0;//分享获得 金币
            $data['send_jewels'] = 0;//分享获得 钻石
            if ($gameConfig['share_address'] == $share_address) {
                //分享有奖励
                $getSharegame = ShareModel::getInstance()->getShareRecord($userID, $share_address);
                if (empty($getSharegame)) {
                    //第一次分享
                    // 创建邮件
                    $title = "分享成功奖励";
                    $content = "分享成功，\n获得奖励：{$share_send_jewels}钻石,{$share_send_money}金币";
                    $goodsArray = [
                        EnumConfig::E_ResourceType['JEWELS'] => $share_send_jewels,
                        EnumConfig::E_ResourceType['MONEY'] => $share_send_money,
                    ];
                    $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
                    $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['SHARE'], $title, $content, $goodsList);
                    // 添加邮件
                    EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
                    // 小红点
                    CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
                    //添加签到记录
                    $data['send_money'] = $share_send_money;//分享获得 金币
                    $data['send_jewels'] = $share_send_jewels;//分享获得 钻石
                    ShareModel::getInstance()->addShareRecord($data);
                    AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_SHARE);
                } else {
                    //获得奖励间隔时间
                    // 用户时间 4 天后的时间
                    $user_share_time = $getSharegame['share_time'] + $gameConfig['share_interval'] * 24 * 3600;
                    if (time() > $user_share_time) {
                        // 创建邮件
                        $title = "分享成功奖励";
                        $content = "分享成功，\n获得奖励：{$share_send_jewels}钻石,{$share_send_money}金币";
                        $goodsArray = [
                            EnumConfig::E_ResourceType['JEWELS'] => $share_send_jewels,
                            EnumConfig::E_ResourceType['MONEY'] => $share_send_money,
                        ];
                        $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
                        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['SHARE'], $title, $content, $goodsList);
                        // 添加邮件
                        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
                        // 小红点
                        CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
                        //添加签到记录
                        $data['send_money'] = $share_send_money;//分享获得 金币
                        $data['send_jewels'] = $share_send_jewels;//分享获得 钻石
                        ShareModel::getInstance()->addShareRecord($data);
                        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_SHARE);
                    } else {
                        //还没有到奖励时间添加纪录
                        ShareModel::getInstance()->addShareRecord($data);
                        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_SHARE);
                    }
                }
            } else {
                //分享没有奖励添加纪录
                ShareModel::getInstance()->addShareRecord($data);
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_SHARE);
            }
        } else {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        }
    }
}
