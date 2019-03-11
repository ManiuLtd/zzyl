<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/11
 * Time: 9:42
 */

namespace action;


class ResourceAction extends AppAction
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

    public function getUserHeadUrlList($params) {
        $data = ['http://ht.szhuomei.com//head//man//1729.jpg', 'http://ht.szhuomei.com//head//man//1730.jpg', 'http://ht.szhuomei.com//head//man//1733.jpg', 'http://ht.szhuomei.com//head//man//1735.jpg', 'http://ht.szhuomei.com//head//man//1737.jpg', 'http://ht.szhuomei.com//head//man//1740.jpg', 'http://ht.szhuomei.com//head//man//1741.jpg', 'http://ht.szhuomei.com//head//man//1749.jpg', 'http://ht.szhuomei.com//head//man//1750.jpg', 'http://ht.szhuomei.com//head//man//1753.jpg', 'http://ht.szhuomei.com//head//man//1761.jpg', 'http://ht.szhuomei.com//head//man//1771.jpg', 'http://ht.szhuomei.com//head//man//1779.jpg', 'http://ht.szhuomei.com//head//man//1780.jpg', 'http://ht.szhuomei.com//head//man//1781.jpg'];
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }
}