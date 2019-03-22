<?php
namespace action;
use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\UpdateModel;

/**
 * 更新业务
 * Class UpdateAction
 */
class UpdateAction extends AppAction
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
//        parent::__construct();
    }

    private function __clone()
    {
    }

    /**
     * 获取平台版本信息信息
     * @param $params
     */
    public function platformVersion($params)
    {
        $platformType = (int)$params['platformType'];
        $returnType = (int)$params['returnType'];
        if (!in_array($platformType, EnumConfig::E_PlatformType)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        }
        $packet_type = EnumConfig::E_VersionPacketType['PLATFORM'];
        $packetVersionInfo = UpdateModel::getInstance()->getPacketVersion($packet_type, $platformType);
        if (empty($packetVersionInfo)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "获取包版本信息失败");
        }
        if ($returnType == EnumConfig::E_VersionReturnType['STRING_ADDRESS']) {
            $address = $packetVersionInfo['address'];
            AppModel::returnString($address);
        } else if ($returnType == EnumConfig::E_VersionReturnType['STRING_VERSION']) {
            $version = $packetVersionInfo['version'];
            AppModel::returnString($version);
        }
        LogHelper::printLog('PROMOTION', '参数qwert12344'.json_encode($params));
        LogHelper::printLog('PROMOTION', '参数qwert'.json_encode($packetVersionInfo));
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $packetVersionInfo);
    }

    /**
     * 获取游戏版本信息
     * @param $params
     */
    public function gameVersion($params)
    {
        $gameID = (int)$params['gameID'];
        $returnType = (int)$params['returnType'];
        $packet_type = EnumConfig::E_VersionPacketType['GAME'];
        $packetVersionInfo = UpdateModel::getInstance()->getPacketVersion($packet_type, $gameID);
        if (empty($packetVersionInfo)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "获取包版本信息失败");
        }
        if ($returnType == EnumConfig::E_VersionReturnType['STRING_ADDRESS']) {
            $address = $packetVersionInfo['address'];
            AppModel::returnString($address);
        } else if ($returnType == EnumConfig::E_VersionReturnType['STRING_VERSION']) {
            $version = $packetVersionInfo['version'];
            AppModel::returnString($version);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $packetVersionInfo);
    }


    /**
     * 获取测试服游戏版本信息
     * @param $params
     */
    public function gameVersionTest($params)
    {
        $gameID = (int)$params['gameID'];
        $returnType = (int)$params['returnType'];
        $packet_type = EnumConfig::E_VersionPacketType['GAME'];
        $packetVersionInfo = UpdateModel::getInstance()->getPacketVersionTest($packet_type, $gameID);
        if (empty($packetVersionInfo)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "获取包版本信息失败");
        }
        if ($returnType == EnumConfig::E_VersionReturnType['STRING_ADDRESS']) {
            $address = $packetVersionInfo['address'];
            AppModel::returnString($address);
        } else if ($returnType == EnumConfig::E_VersionReturnType['STRING_VERSION']) {
            $version = $packetVersionInfo['version'];
            AppModel::returnString($version);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $packetVersionInfo);
    }
}
