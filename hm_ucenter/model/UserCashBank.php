<?php
namespace model;
use config\MysqlConfig;
use manager\DBManager;
use manager\RedisManager;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/2
 * Time: 11:25
 */

/**
 * 添加银行卡
 * Class UserCashBank
 */
class UserCashBank extends AppModel
{
    const DEAD_TIME = 60;
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
     * 添加一张银行卡
     * @param $bankInfo
     * @return mixed
     */
    public function createBankDB($bankInfo)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_user_cash_bank, $bankInfo);
    }

    /**
     * 获取银行卡的数量
     * @param $userID
     * @return mixed
     */
    public function getCount($where)
    {
        return DBManager::getMysql()->getCount(MysqlConfig::Table_user_cash_bank, 'Id',$where);
    }

    /**
     * 更新账户信息
     * @param $userID
     * @return mixed
     */
    public function updateAccount($param, $where)
    {
        return DBManager::getMysql()->update(MysqlConfig::Table_user_cash_bank, $param, $where);
    }

    /*
     * 推送消息
     * $param int $userid  用户ID
     * $param int $type  类型
     * $param int $money  金额
     * $param int $changereason  变化类型
     * */
    public function sendMessage($userid, $type, $money, $changereason)
    {
        return $this->changeUserResource($userid, $type, $money, $changereason);
    }


}