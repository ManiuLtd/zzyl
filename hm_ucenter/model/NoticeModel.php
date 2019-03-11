<?php 
namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use manager\DBManager;

/**
 * 公告模块
 * Class NoticeModel
 */
class NoticeModel extends AppModel
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
        $this->checkUser();
    }

    private function __clone()
    {
    }

    public static function notice($type, $title, $content, $begin_time, $end_time, $interval = 0, $times = 1)
    {
        $notice = array(
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'time' => time(),
            'interval' => $interval,
            'times' => $times,
        );
        return $notice;
    }

    /**
     * 添加公告
     * @param $notice
     * @return mixed
     */
    public function addNotice($notice)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_notice, $notice);
    }

    /**
     * 删除公告
     * @param $noticeID
     * @return mixed
     */
    public function delNotice($noticeID)
    {
        $where = "id={$noticeID}";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_notice, $where);
    }


    /**
     * 获取公告列表
     * @param $type
     * @param $num
     * @return mixed
     */
    public function getNoticeList($type, $num = 1, $page = 1)
    {
        $limit = $this->makePager($page, $num);
        $where = "type = {$type} order by id desc {$limit}";
        return DBManager::getMysql()->selectAll(MysqlConfig::Table_web_notice, [], $where);
    }

    /**
     * 获取最新的一条特殊公告
     * @return bool
     */
    public function getSpecialNotice()
    {
        $sql = "select content from " . MysqlConfig::Table_web_notice
            . " where special_types = " . EnumConfig::E_NoticeType['SPECIAL'] . " order by id desc";
        return DBManager::getMysql()->queryRow($sql);
    }
}
