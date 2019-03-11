<?php


use config\ConnectConfig;
use config\MysqlConfig;
use manager\DBManager;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 11:14
 */
// 定义应用目录
//define('APP_PATH','./Application/');

// 引入ThinkPHP入口文件
//require_once dirname(__DIR__) . './../ThinkPHP/ThinkPHP.php';
require_once dirname(__DIR__) . '/helper/LoadHelper.php';
class testConfig
{
    /**
     * testConfig constructor.
     */
    public function __construct()
    {
//        $this->setTableConfig();
        $this->initOtherConfig();
    }

    /**
     * 检查mysql配置一致性
     */
    public function consistence() {
        var_export(ConnectConfig::MYSQL_CONFIG_MASTER);
    }

    public function setTableConfig() {
        $id = $_GET['id'];
        $where = \model\UserModel::getInstance()->makeWhere(['id' => $id]);
        var_export($where);
//        $sql = 'select * from ' . MysqlConfig::Table_web_turntable_config . ' where id = ' . $id;
        $sql = 'select * from ' . MysqlConfig::Table_web_turntable_config . $where;
//        $turntableConfig = DBManager::getMysql()->queryAll($sql);
        $pdo = DBManager::getMysql()->getPDO()->query($sql);
        $pdo->setFetchMode(\PDO::FETCH_ASSOC);
        $turntableConfig = $pdo->fetchAll();
        var_export($turntableConfig);
//        ConfigModel::getInstance()->setTableCache(MysqlConfig::Table_web_turntable_config, $turntableConfig);
    }

    public function initOtherConfig() {
//        \config\MysqlTableFieldConfig::initConfigData();
        \config\EnumFunConfig::initConfigData();
    }


}

new testConfig();