<?php
namespace model;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 反馈模块
 * Class FeedbackModel
 */
class FeedbackModel extends AppModel
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
     * 获取反馈列表
     * @param $userID
     * @return array|bool
     */
    public function getFeedbackList($userID)
    {
        $sql = "select id,content,type,read_type,f_time from "
            . MysqlConfig::Table_web_feedback
            . " where userID= {$userID} order by f_time desc";
        $result = DBManager::getMysql()->queryAll($sql);
        $intKeyArray = array(
            'id',
            'type',
            'read_type',
            'f_time',
        );
        //转换int类型
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 获取反馈信息
     * @param $feedbackID
     * @return bool
     */
    public function getFeedbackByID($feedbackID)
    {
        $where = "id={$feedbackID}";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_feedback, [], $where);
        $intKeyArray = array(
            'id',
            'type',
            'f_time',
            'read_type',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 更新反馈已读状态
     * @param $feedbackID
     * @param $readType
     * @return bool|mysqli_result
     */
    public function updateFeedbackReadType($feedbackID, $readType)
    {
        $sql = "update " . MysqlConfig::Table_web_feedback
            . " set read_type={$readType} where id=" . $feedbackID;
        return DBManager::getMysql()->execSql($sql);
    }

    /**
     * 更新反馈回复状态
     * @param $feedbackID
     * @param $replyStatus
     * @return bool|mysqli_result
     */
    public function updateFeedbackReplyStatus($feedbackID, $replyStatus)
    {
        $sql = "update " . MysqlConfig::Table_web_feedback
            . " set has_back={$replyStatus} where id=" . $feedbackID;
        return DBManager::getMysql()->execSql($sql);
    }

    /**
     * 添加反馈回复
     * @param $feedbackID
     * @param $replayType
     * @param $content
     * @return mixed
     */
    public function addFeedbackReply($feedbackID, $replayType, $content)
    {
        $arrayData = array(
            "c_content" => $content,
            "c_id" => $feedbackID,
            "c_type" => $replayType,
            "c_time" => time(),
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_feedback_reply, $arrayData);
    }

    /**
     * 添加反馈
     * @param $userID
     * @param $userName
     * @param $content
     * @param $feedbackType
     * @param $reedType
     * @param $has_back
     * @return bool|mysqli_result
     */
    public function addFeedback($userID, $userName, $content, $feedbackType, $reedType, $has_back)
    {
        $arrayData = array(
            "userID" => $userID,
            "username" => $userName,
            "content" => $content,
            "type" => $feedbackType,
            "f_time" => time(),
            "read_type" => $reedType,
            "has_back" => $has_back,
        );
        //反馈统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'feedbackCount');
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_feedback, $arrayData);
    }

    /**
     * 获得最新一条反馈问题
     * @param $userID
     * @return bool
     */
    public function getNewFeedback($userID)
    {
        $where = "userID={$userID} order by id desc";
        $feedback = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_feedback, [], $where);
        $intKeyArray = array(
            'id',
            'type',
            'f_time',
            'read_type',
        );
        FunctionHelper::arrayValueToInt($feedback, $intKeyArray);
        return $feedback;
    }

    /**
     * 获取反馈回复列表
     * @param $feedbackID
     * @return array
     */
    public function getFeedbackReplyList($feedbackID)
    {
        $where = "c_id={$feedbackID} order by c_time asc";
        $result = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_feedback_reply, [], $where);
        $intKeyArray = array(
            'c_id',
            'c_type',
            'c_time',
            'id',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 删除反馈
     * @param $feedbackID
     * @return bool
     */
    public function delFeedback($feedbackID)
    {
        $sqlAyy = [];
        //删除反馈信息
        $sqlAyy['sql1'] = "delete from " . MysqlConfig::Table_web_feedback
            . " where id=" . $feedbackID;
        //删除反馈回复信息
        $sqlAyy['sql2'] = "delete from " . MysqlConfig::Table_web_feedback_reply
            . " where c_id=" . $feedbackID;
        return DBManager::getMysql()->execTransaction($sqlAyy);
    }
}
