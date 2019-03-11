<?php
namespace model;
use config\MysqlConfig;
use manager\DBManager;
use manager\RedisManager;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/4
 * Time: 9:45
 */

/**
 * 添加银行卡
 * Class UserCashBank
 */
class UserCashApplication extends AppModel
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
     * 添加提现申请信息
     * @param $userID
     * @return mixed
     */
    public function addInsert($arrayDataValue)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_user_cash_application, $arrayDataValue);
    }



}