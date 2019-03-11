<?php
namespace action;

use config\ErrorConfig;
use model\AppModel;
use model\AudioModel;
use model\UserModel;

/**
 * 语音业务
 * Class AudioAction
 */
class AudioAction extends AppAction
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
     * 保存录音
     * @param $params
     */
    public function saveAudio($params)
    {
        $userID = (int)$params['userID'];
        $audio = $params['audio'];
        if ($audio == '') {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        }

        $audioID = AudioModel::getInstance()->saveAudio($audio);
        //录音统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID,'sendAudioCount');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, ['audioID' => $audioID]);
    }

    /**
     * 获取录音
     * @param $params
     */
    public function getAudio($params)
    {
        $audioID = (int)$params['audioID'];
        $audio = AudioModel::getInstance()->getAudio($audioID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, ['audio' => $audio]);
    }
}
