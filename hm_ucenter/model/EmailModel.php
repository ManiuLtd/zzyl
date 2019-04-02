<?php
namespace model;
use config\EnumConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use config\RedisConfig;
use manager\DBManager;
use manager\RedisManager;

/**
 * 邮件模块
 * Class EmailModel
 */
class EmailModel extends AppModel
{
    const USER_EMAIL_MAX_COUNT = 50; // 每个人邮件最大数量
    const MAIL_SEND_NAME = '至尊纸';
    const MAIL_SEND_URL = 'http://api.sendcloud.net/apiv2/mail/send';
    const MAIL_SEND_USER = 'Va_Dly_test_Fyumqj';
    const MAIL_SEND_KEY = 'bat22TFh4Y2yuSZA';
    const MAIL_SEND_FROM = 'sendcloud@sendcloud.org';
    //15天 不活跃时间
    const NOT_ACTIVE_TIME = 15 * 24 * 60 * 60;

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

    /**
     * 邮件是否存在
     * @param $emailID
     * @return bool
     */
    public function isEmailExists($emailID)
    {
        $exists = RedisManager::getRedis()->exists(RedisConfig::Hash_emailDetailInfo . '|' . $emailID);
        if ($exists == 1) {
            return true;
        }
        return false;
    }

    /**
     * 生成邮件附件
     * @param array $arrayData
     * @return array
     */
    public function makeEmailGoodsList($arrayData = [])
    {
        $goodsList = [];
        foreach ($arrayData as $goodsType => $goodsNums) {
            if ($goodsNums > 0 && in_array($goodsType, EnumConfig::E_ResourceType)) {
                $goods = array(
                    'goodsType' => (int)$goodsType,
                    'goodsNums' => (int)$goodsNums,
                );
                $goodsList[] = $goods;
            }
        }
        return $goodsList;
    }

    /**
     * 创建一封邮件
     * @param int $senderID 邮件发送人ID
     * @param $mailType 奖励类型
     * @param $title 邮件标题
     * @param $content 邮件内容
     * @param array $goodsList 邮件奖励
     * @return array 邮件信息
     */
    public function createEmail($senderID = 0, $mailType = EnumConfig::E_ResourceChangeReason['DEFAULT'], $title, $content, $goodsList = [])
    {
        //获取邮件唯一ID
        do {
            $emailID = $this->getRedisIncrementID(RedisConfig::String_emailInfoMaxIDSet);
            $exists = $this->isEmailExists($emailID);
        } while ($exists);

        //邮件结构
        $emailDetailInfo = array(
            'emailID' => $emailID, // 邮件ID
            'sendtime' => time(), // 发送时间
            'isHaveGoods' => count($goodsList) > 0 ? 1 : 0, // 是否包含附件
            'senderID' => $senderID, // 发送者ID
            'contentCount' => strlen($content), // 内容长度
            'content' => $content, // 内容
            'title' => $title, // 标题
            'mailType' => $mailType, // 奖励类型
            'goodsList' => json_encode($goodsList), //邮件奖励
        );
        // 增加邮件信息 emailDetailInfo
        RedisManager::getRedis()->hMset(RedisConfig::Hash_emailDetailInfo . '|' . $emailID, $emailDetailInfo);
        $result = $this->createEmailToDB($emailDetailInfo);
        if (empty($result)) {
            RedisManager::getRedis()->del(RedisConfig::Hash_emailDetailInfo . '|' . $emailID);
            return [];
        }
        return $emailDetailInfo;
    }

    /**
     * 创建一封邮件 To DB
     * @param $emailDetailInfo
     * @return mixed
     */
    public function createEmailToDB($emailDetailInfo)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_email, $emailDetailInfo);
    }

    /**
     * 获取发送的所有邮件
     * @return array
     */
    public function getAllEmailList()
    {
        $emailDetailInfoKey = RedisConfig::Hash_emailDetailInfo . '|';
        $keysList = RedisManager::getRedis()->keys($emailDetailInfoKey . '*');
        $emailList = [];
        foreach ($keysList as $key) {
            $emailID = str_replace($emailDetailInfoKey, '', $key);
            $emailAllInfo = $this->getEmailAllInfo($emailID);
            if (!empty($emailAllInfo)) {
                $emailList[] = $emailAllInfo;
            }
        }
        return $emailList;
    }

    /**
     * 获取发送的所有邮件 从DB获取
     * @return array
     */
    public function getAllEmailListToDB()
    {
        $emailList = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_email);
        foreach ($emailList as &$email) {
            $email['emailID'] = (int)$email['emailID'];
            $email['senderID'] = (int)$email['senderID'];
            $email['sendtime'] = (int)$email['sendtime'];
            $email['mailType'] = (int)$email['mailType'];
            $email['isHaveGoods'] = (int)$email['isHaveGoods'];
            $email['goodsList'] = json_decode($email['goodsList'], true);
            $email['contentCount'] = (int)$email['contentCount'];
        }
        return $emailList;
    }

    /**
     * 发送邮件给所有用户
     * @param $emailDetailInfo
     */
    public function addEmailToAllUser($emailDetailInfo)
    {
        $arrayKeyValue = ['userID'];
        $time = time() - self::NOT_ACTIVE_TIME;
        $where = "isVirtual = 0 and lastCrossDayTime > {$time}";
        $userList = DBManager::getMysql()->selectAll(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);
        foreach ($userList as $user) {
            $this->addEmailToUser($emailDetailInfo, $user['userID']);
        }
    }

    /**
     * 发送邮件给用户
     * @param $emailDetailInfo
     * @param $userID
     */
    public function addEmailToUser($emailDetailInfo, $userID)
    {
        $emailID = $emailDetailInfo['emailID'];
        $isHaveGoods = $emailDetailInfo['isHaveGoods'];

        // userEmailSet 玩家邮件集合 增加邮件ID
        RedisManager::getRedis()->zAdd(RedisConfig::SSet_userEmailSet . '|' . $userID, time(), $emailID);

        // 玩家邮件详情信息 userToEmailDetailInfo
        $userToEmailDetailInfo = array(
            'isReceived' => $isHaveGoods == 1 ? 0 : 1, // 有附件则未领 反之则已领
            'isRead' => 0, // 未读
        );
        RedisManager::getRedis()->hMset(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID, $userToEmailDetailInfo);

        //未读数量加1
        $this->changeNotReadCount($userID, 1);
        //未领取数量加1
        if ($isHaveGoods == 1) {
            $this->changeNotReceivedCount($userID, 1);
        }

        //邮件统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'getTotalEmailCount');
        if ($isHaveGoods) {
            UserModel::getInstance()->addWebUserInfoValue($userID, 'getGoodsEmailCount');
        }
    }

    /**
     * 玩家是否有这个邮件
     * @param $userID
     * @param $emailID
     * @return bool
     */
    public function isUserHaveEmail($userID, $emailID)
    {
        $exists = RedisManager::getRedis()->exists(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID);
        return $exists;
    }

    /**
     * 玩家邮件附件是否已经领取
     * @param $userID
     * @param $emailID
     * @return int
     */
    public function getEmailIsReceived($userID, $emailID)
    {
        $isReceived = RedisManager::getRedis()->hGet(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID, 'isReceived');
        return (int)$isReceived;
    }

    /**
     * 玩家邮件是否已读
     * @param $userID
     * @param $emailID
     * @return int
     */
    public function getEmailIsRead($userID, $emailID)
    {
        $isRead = RedisManager::getRedis()->hGet(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID, 'isRead');
        return (int)$isRead;
    }

    /**
     * 设置玩家邮件附件已经领取
     * @param $userID
     * @param $emailID
     */
    public function setEmailReceived($userID, $emailID)
    {
        RedisManager::getRedis()->hSet(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID, 'isReceived', 1);
    }

    /**
     * 设置玩家邮件已读
     * @param $userID
     * @param $emailID
     */
    public function setEmailRead($userID, $emailID)
    {
        RedisManager::getRedis()->hSet(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID, 'isRead', 1);
    }

    /**
     * 未读数量
     * @param $userID
     * @return int
     */
    public function getNotReadCount($userID)
    {
        $notReadCount = RedisManager::getGameRedis()->hGet(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'notEMRead');
        return (int)$notReadCount;
    }

    /**
     * 未领取数量
     * @param $userID
     * @return int
     */
    public function notReceivedCount($userID)
    {
        $notReadCount = RedisManager::getGameRedis()->hGet(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'notEMReceived');
        return (int)$notReadCount;
    }

    /**
     * 改变未领取数量
     * @param $userID
     * @param $count
     */
    public function changeNotReadCount($userID, $count)
    {
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'notEMRead', $count);
    }

    /**
     * 改变未领取数量
     * @param $userID
     * @param $count
     */
    public function changeNotReceivedCount($userID, $count)
    {
        RedisManager::getGameRedis()->hIncrBy(GameRedisConfig::Hash_userRedSpotCount . '|' . $userID, 'notEMReceived', $count);
    }

    /**
     * 获取邮件列表
     * @param $userID
     * @return array
     */
    public function getEmailList($userID)
    {
        $emailIDList = RedisManager::getRedis()->zRevRange(RedisConfig::SSet_userEmailSet . '|' . $userID, 0, self::USER_EMAIL_MAX_COUNT - 1);
        $emailList = [];
        foreach ($emailIDList as $key => $emailID) {
            $emailSimpleInfo = $this->getEmailSimpleInfo($emailID, $userID);
            if (!empty($emailSimpleInfo)) {
                $emailList[] = $emailSimpleInfo;
            }
        }
        return $emailList;
    }

    /**
     * 获取邮件全部信息
     * @param $emailID
     * @return array
     */
    public function getEmailAllInfo($emailID)
    {
        $tempArray = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_emailDetailInfo . '|' . $emailID);
        $emailAllInfo = [];
        if ($tempArray !== false) {
            $emailAllInfo['emailID'] = (int)$tempArray['emailID'];
            $emailAllInfo['senderID'] = (int)$tempArray['senderID'];
            $emailAllInfo['sendtime'] = (int)$tempArray['sendtime'];
            $emailAllInfo['mailType'] = (int)$tempArray['mailType'];
            $emailAllInfo['isHaveGoods'] = (int)$tempArray['isHaveGoods'];
            $emailAllInfo['goodsList'] = json_decode($tempArray['goodsList'], true);
            $emailAllInfo['contentCount'] = (int)$tempArray['contentCount'];
            $emailAllInfo['content'] = $tempArray['content'];
            $emailAllInfo['title'] = $tempArray['title'];
        }
        return $emailAllInfo;
    }

    /**
     * 获取邮件详细信息
     * @param $emailID
     * @return array
     */
    public function getEmailDetailInfo($emailID)
    {
        $tempArray = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_emailDetailInfo . '|' . $emailID);
        $emailDetailInfo = [];
        if ($tempArray !== false) {
            $emailDetailInfo['emailID'] = (int)$tempArray['emailID'];
            $emailDetailInfo['senderID'] = (int)$tempArray['senderID'];
            $emailDetailInfo['sendtime'] = (int)$tempArray['sendtime'];
            $emailDetailInfo['mailType'] = (int)$tempArray['mailType'];
            $emailDetailInfo['isHaveGoods'] = (int)$tempArray['isHaveGoods'];
            $emailDetailInfo['goodsList'] = json_decode($tempArray['goodsList'], true);
            $emailDetailInfo['contentCount'] = (int)$tempArray['contentCount'];
            $emailDetailInfo['content'] = $tempArray['content'];
        }
        return $emailDetailInfo;
    }

    /**
     * 获取邮件简单信息
     * @param $emailID
     * @param int $userID
     * @return array
     */
    public function getEmailSimpleInfo($emailID, $userID = 0)
    {
        $tempArray = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_emailDetailInfo . '|' . $emailID);
        $emailSimpleInfo = [];
        if ($tempArray !== false) {
            $emailSimpleInfo['emailID'] = (int)$tempArray['emailID'];
            $emailSimpleInfo['sendtime'] = (int)$tempArray['sendtime'];
            $emailSimpleInfo['mailType'] = (int)$tempArray['mailType'];
            $emailSimpleInfo['isHaveGoods'] = (int)$tempArray['isHaveGoods'];
            $emailSimpleInfo['title'] = $tempArray['title'];
            if ($userID != 0) {
                $emailSimpleInfo['isRead'] = $this->getEmailIsRead($userID, $emailID);
                $emailSimpleInfo['isReceived'] = $this->getEmailIsReceived($userID, $emailID);
            }
        }
        return $emailSimpleInfo;
    }

    /**
     * 删除邮件
     * @param $userID
     * @param $emailID
     * @return bool
     */
    public function delEmail($userID, $emailID)
    {
        $isCountChange = false;
        // 如果邮件未读 未读减1
        if ($this->getEmailIsRead($userID, $emailID) == 0) {
            $this->changeNotReadCount($userID, -1);
            $isCountChange = true;
        }
        // 如果邮件未领 未领减1
        if ($this->getEmailIsReceived($userID, $emailID) == 0) {
            $this->changeNotReceivedCount($userID, -1);
            $isCountChange = true;
        }
        // 从玩家集合移除邮件id
        RedisManager::getRedis()->zRem(RedisConfig::SSet_userEmailSet . '|' . $userID, $emailID);
        // 删除邮件
        RedisManager::getRedis()->del(RedisConfig::Hash_userToEmailDetailInfo . '|' . $userID . ',' . $emailID);
        return $isCountChange;
    }

    /**
     * 删除超出最大邮件数量的邮件
     * @param $userID
     * @return bool
     */
    public function checkEmailCount($userID)
    {
        $isCountChange = false;
        //逆序找出 self::USER_EMAIL_MAX_COUNT -1 的邮件ID 遍历删除
        $emailIDList = RedisManager::getRedis()->zRevRange(RedisConfig::SSet_userEmailSet . '|' . $userID, self::USER_EMAIL_MAX_COUNT, -1);
        foreach ($emailIDList as $key => $emailID) {
            $isChange = $this->delEmail($userID, $emailID);
            if (!$isCountChange && $isChange) {
                $isCountChange = true;
            }
        }
        return $isCountChange;
    }

    /**
     * 发送电子邮件
     * @param $to
     * @param $title
     * @param $content
     * @return string
     */
    public function sendEmail($to, $title, $content)
    {
        $param = array(
            'apiUser' => self::MAIL_SEND_USER, # 使用api_user和api_key进行验证
            'apiKey' => self::MAIL_SEND_KEY,
            'from' => self::MAIL_SEND_FROM, # 发信人，用正确邮件地址替代
            'fromName' => self::MAIL_SEND_NAME,
            'to' => $to,# 收件人地址, 用正确邮件地址替代, 多个地址用';'分隔
            'subject' => $title,
            'html' => $content,
            'respEmailId' => 'true'
        );
        $data = http_build_query($param);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ));
        $context = stream_context_create($options);
        $result = file_get_contents(self::MAIL_SEND_URL, FILE_TEXT, $context);
        return $result;
    }
}
