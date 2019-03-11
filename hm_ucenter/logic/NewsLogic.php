<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29
 * Time: 17:15
 */

namespace logic;


use config\EnumConfig;
use config\ErrorConfig;
use helper\LogHelper;
use model\ProductModel;

class NewsLogic extends BaseLogic
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getAnnounce() {
        $resData = ProductModel::getInstance()->select(
            ['type' => EnumConfig::E_WebHomeProductTeyp['ANNOUNCE']],
            '*',
            1,
            10,
            'create_time desc'
        );
        $data = [];
        foreach ($resData as $k => $v) {
            $data[] =
                ['noticeID' => $v['id'],
                'noticeName' => $v['name'],
                'noticeURL' => $v['img_url']];
        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE, 'ok', $data);
    }

    private function __clone()
    {
    }
}