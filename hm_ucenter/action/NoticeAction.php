<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use helper\FunctionHelper;
use model\AppModel;
use model\NoticeModel;

/**
 * 公告业务
 * Class NoticeAction
 */
class NoticeAction extends AppAction
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

    const NORMAL_NOTICE_NUM = 10;
    const SPECIAL_NOTICE_NUM = 1;

    /**
     * 公告列表
     * @param $param
     */
    public function normalNoticeList($param)
    {
        $num = empty($param['num']) ? self::NORMAL_NOTICE_NUM : (int)$param['num'];
        $notice = NoticeModel::getInstance()->getNoticeList(EnumConfig::E_NoticeType['NORMAL'], $num);
        $content = FunctionHelper::array_columns($notice, 'content');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $content);
    }

    /**
     * 特殊公告
     * @param $param
     */
    public function specialNoticeList($param)
    {
        $page = max((int)$param['page'], 1);
        $num = empty($param['num']) ? self::SPECIAL_NOTICE_NUM : (int)$param['num'];
        $notice = NoticeModel::getInstance()->getNoticeList(EnumConfig::E_NoticeType['SPECIAL'], $num, $page);
        $content = FunctionHelper::array_columns($notice, 'title,content');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $content);
    }
}
