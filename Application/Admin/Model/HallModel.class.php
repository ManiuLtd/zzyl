<?php
namespace Admin\Model;

use Think\Model;

vendor('Common.GetRedis', '', '.class.php');

class HallModel
{
    protected $redis;

    public function __construct()
    {
        $this->redis = $redis = \GetRedis::get()->redis;
    }

    // 发送邮件
    public function send_email($userID, $data)
    {
        $emailID = $this->getEmailMaxID();
        // 写入集合
        $this->userEmailSet($userID, $emailID);
        // // 写入邮件列表
        $this->emailList($userID, $emailID, $data);
        // 写入邮件详情
        $this->emailInfo($emailID, $data);
        // 增加未读数量
        $this->userEmailCount($userID, $data);
    }

    // 获取邮件最大值ID
    public function getEmailMaxID()
    {
        $emailID = $this->redis->get('emailInfoMaxIDSet');
        if ($emailID == false) {
            $this->redis->set('emailInfoMaxIDSet', 1);
            return 1;
        }

        return $this->redis->incr('emailInfoMaxIDSet');
    }

    // 邮件玩家集合
    public function userEmailSet($userID, $emailID)
    {
        $res = $this->redis->zadd('userEmailSet|' . $userID, time(), $emailID);
        if ($res == false) {
            $this->error('写入失败');
        }
    }

    // 邮件详情
    public function emailInfo($emailID, $data)
    {
        $emailInfo = [
            'emailID'       => $emailID, // 邮件ID
            'sendtime'      => time(), // 发送时间
            'isHaveGoods'   => $data['GoodsTypeNum1'] > 0 ? 1 : 0, // 是否包含附件
            'senderID'      => $data['senderID'], // 发送者
            'contentCount'  => $data['contentCount'], // 内容长度
            'content'       => $data['content'], // 内容
            'isReceived'    => 0, // 是否领取
            'GoodsType1'    => $data['GoodsType1'], // 附件类型
            'GoodsType2'    => $data['GoodsType2'],
            'GoodsType3'    => $data['GoodsType3'],
            'GoodsType4'    => $data['GoodsType4'],
            'GoodsTypeNum1' => $data['GoodsTypeNum1'], // 附件类型数量
            'GoodsTypeNum2' => $data['GoodsTypeNum2'],
            'GoodsTypeNum3' => $data['GoodsTypeNum3'],
            'GoodsTypeNum4' => $data['GoodsTypeNum4'],
        ];

        $this->redis->hmset('emailDetailInfo|' . $emailID, $emailInfo);
    }

    // 玩家邮件列表
    public function emailList($userID, $emailID, $data)
    {
        $list = [
            'userID'      => $userID,
            'sendtime'    => time(),
            'emailID'     => $emailID,
            'emailType'   => $data['emailType'],
            'title'       => $data['title'],
            'isReceived'  => 0,
            'isRead'      => 0,
            'isHaveGoods' => $data['GoodsTypeNum1'] > 0 ? 1 : 0,
        ];

        $this->redis->hmset('userToEmailDetailInfo|' . $userID . ',' . $emailID, $list);
    }

    // 邮件未读 已读 数量 notReadCount 未读 notReceivedCount 已读
    public function userEmailCount($userID, $data)
    {
        $emailCount = $this->redis->hgetall('userEmailCount|' . $userID);
        if ($emailCount == false) {
            $res = $this->redis->hmset('userEmailCount|' . $userID, ['notReadCount' => 1, 'notReceivedCount' => $data['GoodsTypeNum1'] > 0 ? 1 : 0]);
            return;
        }

        // 增加未读数量
        $this->redis->hincrby('userEmailCount|' . $userID, 'notReadCount', 1);
        // 增加领取数量
        $this->redis->hincrby('userEmailCount|' . $userID, 'notReadCount', $data['GoodsTypeNum1'] > 0 ? 1 : 0);
    }
}
