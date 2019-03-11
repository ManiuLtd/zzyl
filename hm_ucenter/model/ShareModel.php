<?php
namespace model;
use config\MysqlConfig;
use manager\DBManager;

/**
 * 分享模块
 * Class ShareModel
 */
class ShareModel extends AppModel
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

    //分享
    public function getShareRecord($userID, $share_address)
    {
        $sql = "select  *  from " . MysqlConfig::Table_web_share_record
            . " where send_money>0 AND share_address=" . $share_address
            . "  AND userid=" . $userID . " order by share_time desc";
        return DBManager::getMysql()->queryRow($sql);
    }

    //分享记录
    public function addShareRecord($shareData)
    {
        $userID = $shareData['userid'];
        $arrayData = array(
            "userid" => $userID,
            "name" => $shareData['name'],
            "share_address" => $shareData['share_address'],
            "share_time" => $shareData['share_time'],
            "send_money" => $shareData['send_money'],
            "send_jewels" => $shareData['send_jewels'],
            "total_count" => 0,
            "total_get_money" => 0,
            "total_get_jewels" => 0,
        );

        //统计分享信息
        $where = "userid={$userID} order by share_time desc";
        $arrayKeyValue = ['total_get_money', 'total_get_jewels', 'total_count'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_share_record, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_count'] = $data['total_count'];
            $arrayData['total_get_money'] = $data['total_get_money'];
            $arrayData['total_get_jewels'] = $data['total_get_jewels'];
        }
        $arrayData['total_count'] += 1;
        $arrayData['total_get_money'] += $shareData['send_money'];
        $arrayData['total_get_jewels'] += $shareData['send_jewels'];

        //分享统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'shareCount');
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_share_record, $arrayData);
    }

    public function getShareCode($unionID)
    {
        $where = "unionid = '{$unionID}'";
        return DBManager::getMysql()->selectRow(MysqlConfig::Table_web_share_code, [], $where);
    }

    public function addShareCode($arrayData)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_share_code, $arrayData);
    }

    public function updateShareCode($unionID, $arrayData)
    {
        $where = "unionid = '{$unionID}'";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_share_code, $arrayData, $where);
    }

    public function addCodeInvitation($arrayData)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_code_invitation, $arrayData);
    }

    public function getCodeInvitation($userID, $type, $needArray = []) {
        $where = " userid={$userID} and type={$type} ";
        return DBManager::getMysql()->selectRow(MysqlConfig::Table_web_code_invitation, $needArray, $where);
    }
}
