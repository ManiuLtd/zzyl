<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29
 * Time: 16:39
 */

namespace model;


use config\MysqlConfig;
use manager\DBManager;

class ProductModel extends AppModel
{
    protected $tableName = MysqlConfig::Table_web_home_product;
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

//    public function getAll($where, $field = '*', $page = 1, $limit = 10, $order = null) {
//        $where = DBManager::getMysql()->makeWhere($where);
//        $pager = $this->makePager($page, $limit);
//        $order = $this->makeOrder($order);
//        return DBManager::getMysql()->queryAll('select ' . $field . ' from ' . $this->tableName . $where . $order . $pager);
//    }

    public function setRow() {

    }
}
