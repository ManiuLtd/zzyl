<?php 
namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use manager\DBManager;

/**
 * 赠送模块
 * Class GiveModel
 */
class GiveModel extends AppModel
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
     * 添加转赠记录
     * @param $userID
     * @param $targetID
     * @param $type
     * @param $value
     * @param $real_value
     * @return mixed
     */
    public function addGiveRecord($userID, $targetID, $type, $value, $real_value)
    {
        $arrayData = array(
            "userID" => $userID,
            "targetID" => $targetID,
            "type" => $type,
            "value" => $value,
            "real_value" => $real_value,
            "time" => time(),
            'total_cost_money' => 0,
            'total_cost_jewels' => 0,
            'total_money_count' => 0,
            'total_jewels_count' => 0,
        );
        //统计次数
        $where = "userID={$userID} order by time desc";
        $arrayKeyValue = ['total_cost_money', 'total_cost_jewels', 'total_money_count','total_jewels_count'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_give_record, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_cost_jewels'] = $data['total_cost_jewels'];
            $arrayData['total_cost_money'] = $data['total_cost_money'];
            $arrayData['total_money_count'] = $data['total_money_count'];
            $arrayData['total_jewels_count'] = $data['total_jewels_count'];
        }
        if ($type == EnumConfig::E_ResourceType['MONEY']){
            //金币转赠统计信息
            $arrayData['total_money_count'] += 1;
            $arrayData['total_cost_money'] += $value;
            UserModel::getInstance()->addWebUserInfoValue($userID, 'giveMoneyCount');
            UserModel::getInstance()->addWebUserInfoValue($userID, 'giveCostMoney', $value);
            UserModel::getInstance()->addWebUserInfoValue($targetID, 'giveGetMoney', $real_value);
        }elseif ($type == EnumConfig::E_ResourceType['JEWELS']){
            //钻石转赠统计信息
            $arrayData['total_jewels_count'] += 1;
            $arrayData['total_cost_jewels'] += $value;
            UserModel::getInstance()->addWebUserInfoValue($userID, 'giveJewelsCount');
            UserModel::getInstance()->addWebUserInfoValue($userID, 'giveCostJewels', $value);
            UserModel::getInstance()->addWebUserInfoValue($targetID, 'giveGetJewels', $real_value);
        }
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_give_record, $arrayData);
    }
}
