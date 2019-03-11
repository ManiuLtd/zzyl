<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\EmailModel;
use model\GiveModel;
use notify\CenterNotify;

/**
 * 转赠业务
 * Class GiveAction
 */
class GiveAction extends AppAction
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        $this->_check_play_game = true;
        parent::__construct();
    }

    private function __clone()
    {
    }

    /**
     * 赠送资源
     * @param $param
     */
    public function giveResources($param)
    {
        $userID = (int)$param['userID'];
        $resourcesType = (int)$param['resourcesType'];
        $targetUserID = (int)$param['targetUserID'];
        $num = (int)$param['num'];

        // 检测用户
        if (!GiveModel::getInstance()->isUserExists($targetUserID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DOES_NOT_EXIST);
        }

        $user = GiveModel::getInstance()->getUserInfo($userID, ['money', 'jewels', 'name']);
        $config = GiveModel::getInstance()->getConfig();
        switch ($resourcesType) {
            case EnumConfig::E_ResourceType['MONEY']: // 金币
                // 最少拥有金币数
                if ($user['money'] < $config['sendGiftMyLimitMoney']) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, "转赠要求自身拥有最低金币数:{$config['sendGiftMyLimitMoney']}");
                }

                // 赠送最低金币数
                if ($num < $config['sendGiftMinMoney']) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, "转赠最低金币数:{$config['sendGiftMinMoney']}");
                }

                $num = $num > $user['money'] ? $user['money'] : $num;

                // 扣除手续费
                $actualNum = $num - ceil($num * $config['sendGiftRate']);

                GiveModel::getInstance()->addGiveRecord($userID, $targetUserID, $resourcesType, $num, $actualNum);
                GiveModel::getInstance()->changeUserResource($userID, $resourcesType, -$num, EnumConfig::E_ResourceChangeReason['GIVE']);
                GiveModel::getInstance()->changeUserResource($targetUserID, $resourcesType, $actualNum, EnumConfig::E_ResourceChangeReason['GIVE'],
                    0, 0, ceil($num * $config['sendGiftRate']));

                // 创建邮件
                $title = "金币转赠到账通知";
                $content = "{$user['name']}转赠了{$num}金币,实际到账{$actualNum}";
                $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['GIVE'], $title, $content);
                // 添加邮件
                EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $targetUserID);
                // 小红点
                CenterNotify::sendRedSport($targetUserID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;

            case EnumConfig::E_ResourceType['JEWELS']: // 钻石
                // 最少拥有钻石数
                if ($user['jewels'] < $config['sendGiftMyLimitJewels']) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, "转赠要求自身拥有最低钻石数:{$config['sendGiftMyLimitJewels']}");
                }

                // 赠送最低钻石数
                if ($num < $config['sendGiftMinJewels']) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, "转赠最低钻石数:{$config['sendGiftMinMoney']}");
                }

                $num = $num > $user['jewels'] ? $user['jewels'] : $num;

                // 扣除手续费
                $actualNum = $num - ceil($num * $config['sendGiftRate']);

                GiveModel::getInstance()->addGiveRecord($userID, $targetUserID, $resourcesType, $num, $actualNum);
                GiveModel::getInstance()->changeUserResource($userID, $resourcesType, -$num, EnumConfig::E_ResourceChangeReason['GIVE']);
                GiveModel::getInstance()->changeUserResource($targetUserID, $resourcesType, $actualNum, EnumConfig::E_ResourceChangeReason['GIVE'],
                    0, 0, ceil($num * $config['sendGiftRate']));

                // 创建邮件
                $title = "钻石转赠到账通知";
                $content = "{$user['name']}给您转赠了{$num}钻石,实际到账{$actualNum}";
                $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['GIVE'], $title, $content);
                // 添加邮件
                EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $targetUserID);
                // 小红点
                CenterNotify::sendRedSport($targetUserID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
                AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
                break;
            default:
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PARAMETER);
                break;
        }
    }
}
