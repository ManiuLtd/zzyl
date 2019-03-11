<?php
namespace logic;

use config\EnumConfig;
use config\ErrorConfig;
use model\LobbyModel;
use model\NoticeModel;
use notify\CenterNotify;

/**
 * 代理模块
 * Class AgentModel
 */
class NotifyLogic extends BaseLogic
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function sendHorn($userID, $content) {
        $user = LobbyModel::getInstance()->getUserInfo($userID, ['jewels', 'name']);
        LobbyModel::getInstance()->addHorn($userID, $content, 0);

        CenterNotify::sendHorn($userID, $user['name'], iconv('UTF-8', 'GB2312', $content));
        return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => '广播成功'];
    }

    /**
     * 发送普通公告
     * @param $title
     * @param null $content
     * @return array
     */
    public function sendNormalNotify($content, $title = '普通公告') {
//        $content = null === $content ? $title : $content;
        $beginTime = time();
        $endTime = $beginTime + 3600;
        $interval = 0;
        $type = EnumConfig::E_NoticeType['BIG_EVENT'];
        return $this->sendNotify($type, $title, $content, $beginTime, $endTime, $interval, 1);
    }

    /**
     * 发送公告
     * @param $type
     * @param $title
     * @param $content
     * @param $beginTime
     * @param $endTime
     * @param $interval
     * @param $times
     * @return array
     */
    public function sendNotify($type, $title, $content, $beginTime, $endTime, $interval, $times) {
        $notice = NoticeModel::getInstance()->notice($type, $title, $content, $beginTime, $endTime, $interval, $times);
        $result = NoticeModel::getInstance()->addNotice($notice);
        if (empty($result)) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '发送公告失败'];
        }
        CenterNotify::sendNotice($notice);
        return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => '发送公告成功'];
    }

    private function __clone()
    {
    }
}