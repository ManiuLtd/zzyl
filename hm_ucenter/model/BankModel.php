<?php

namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 银行模块
 * Class BankModel
 */
class BankModel extends AppModel
{
    const BANK_OPERATE_RECORD_MAX_NUM = 100;

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
     * 添加银行操作记录
     * @param $userID
     * @param $type
     * @param $money
     * @param int $targetID
     */
    public function addBankOperateRecord($userID, $type, $money, $targetID = 0)
    {
        $arrayData = array(
            'userID' => $userID,
            'type' => $type,
            'targetID' => $targetID,
            'money' => $money,
            'time' => time(),
        );

        if ($type == EnumConfig::E_BankOperateType['TRAN']) {
            //统计次数
            $where = "userID={$userID} and type ={$type} order by time desc";
            $arrayKeyValue = ['total_count', 'total_cost_bank_money'];
            $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_bank_record, $arrayKeyValue, $where);
            $arrayData['total_cost_bank_money'] = 0;
            $arrayData['total_count'] = 0;
            if (!empty($data)) {
                $arrayData['total_cost_bank_money'] = $data['total_cost_bank_money'];
                $arrayData['total_count'] = $data['total_count'];
            }
            $arrayData['total_count'] += 1;
            $arrayData['total_cost_bank_money'] += $money;
            //银行转账统计信息
            UserModel::getInstance()->addWebUserInfoValue($userID, 'bankCount');
            UserModel::getInstance()->addWebUserInfoValue($userID, 'bankCostMoney', $money);
            UserModel::getInstance()->addWebUserInfoValue($targetID, 'bankGetMoney', $money);
        }
        DBManager::getMysql()->insert(MysqlConfig::Table_web_bank_record, $arrayData);
    }

    /**
     * 获取银行操作记录
     * @param $userID
     * @param int $num
     * @return mixed|null
     */
    public function getBankOperateRecord($userID, $num = self::BANK_OPERATE_RECORD_MAX_NUM)
    {
        $sql = "select * from " . MysqlConfig::Table_web_bank_record
            . " where userID={$userID} order by time desc limit {$num}";
        $result = DBManager::getMysql()->queryAll($sql);
        FunctionHelper::arrayValueToInt($result);
        return $result;
    }
}
