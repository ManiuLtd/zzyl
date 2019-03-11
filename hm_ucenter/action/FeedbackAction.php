<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\FeedbackModel;

/**
 * 反馈业务
 * Class FeedbackAction
 */
class FeedbackAction extends AppAction
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
     * @param $params
     */
    public function feedbackList($params)
    {
        $userID = (int)$params['userID'];
        $feedbackList = FeedbackModel::getInstance()->getFeedbackList($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $feedbackList);
    }

    /**
     * 回复反馈
     * @param $params
     */
    public function replyFeedback($params)
    {
        $feedbackID = (int)$params['feedbackID'];
        $content = $params['content'];
        if (empty($content)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        }
        $feedbackInfo = FeedbackModel::getInstance()->getFeedbackByID($feedbackID);
        //不存在
        if (empty($feedbackInfo)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_FEEDBACK_ID);
        }
        //已经结束
        if ($feedbackInfo['read_type'] == EnumConfig::E_FeedbackReadType['CLOSE']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_FEEDBACK_END);
        }
        //增加一条用户反馈
        FeedbackModel::getInstance()->addFeedbackReply($feedbackID,EnumConfig::E_FeedbackReplyType['USER'] , $content);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 提交反馈
     * @param $params
     */
    public function commitFeedback($params)
    {
        $userID = (int)$params['userID'];
        $content = $params['content'];
        $feedbackType = (int)$params['feedbackType'];//1.游戏问题 2.登陆问题 3.支付问题 4.举报 5.其他
        //参数检查
        if (empty($content) || !in_array($feedbackType, EnumConfig::E_FeedbackType)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        }

        //用户名字
        $userName = FeedbackModel::getInstance()->getUserInfo($userID, 'name');

        //添加问题反馈
        $addResult = FeedbackModel::getInstance()->addFeedback($userID, $userName, $content, $feedbackType, EnumConfig::E_FeedbackReadType['NONE'],EnumConfig::E_FeedbackReplyStatus['NONE']);
        if (!$addResult) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_FEEDBACK);
        }
        $feedback = FeedbackModel::getInstance()->getNewFeedback($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_FEEDBACK, $feedback);
    }

    /**
     * 获取反馈详情信息
     * @param $params
     */
    public function feedbackInfo($params)
    {
        $feedbackID = (int)$params['feedbackID'];
        //不存在
        $feedbackInfo = FeedbackModel::getInstance()->getFeedbackByID($feedbackID);
        if (empty($feedbackInfo)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_FEEDBACK_ID);
        }
        //回复成功状态改为已读
        if ($feedbackInfo['read_type'] != EnumConfig::E_FeedbackReadType['CLOSE']) {
            FeedbackModel::getInstance()->updateFeedbackReadType($feedbackID, EnumConfig::E_FeedbackReadType['READ']);
        }
        $feedbackReplyList = FeedbackModel::getInstance()->getFeedbackReplyList($feedbackID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_FEEDBACK, $feedbackReplyList);
    }

    /**
     * 删除反馈
     * @param $params
     */
    public function delFeedback($params)
    {
        $feedbackID = (int)$params['feedbackID'];
        //不存在
        $feedbackInfo = FeedbackModel::getInstance()->getFeedbackByID($feedbackID);
        if (empty($feedbackInfo)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_FEEDBACK_ID);
        }
        $delFeedback = FeedbackModel::getInstance()->delFeedback($feedbackID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $delFeedback);
    }
}
