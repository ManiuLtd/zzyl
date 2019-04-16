<?php
namespace action;

use config\ErrorConfig;
use model\UserCashBank;
use model\UserCashApplication;
use model\AppModel;
use manager\DBManager;
use config\MysqlConfig;
use model\UserModel;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/2
 * Time: 10:50
 */

/**
 * 获取收款账户列表
 * Class CashAction
 */
class PersonalAction extends AppAction
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
     * 获取收款账户列表
     * @param $param
     */
    public function showAccountList()
    {
        $resinfo['account_weixin'] = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_collection_account, ['account_name','wx_qq_name'], 'type = 1 and status = 1');
        $resinfo['account_qq'] = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_collection_account, ['account_name','wx_qq_name'], 'type = 2 and status = 1');

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT,$resinfo);

    }


}