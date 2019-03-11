<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 11:58
 */

namespace logic;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use model\WelfareModel;


class ConfigLogic extends BaseLogic
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

    public function getGameConfig($field = '*') {
    }

    private function __clone()
    {
    }


    /**
     * 获取签到配置
     * @param $params
     */
    public function signConfig($params)
    {
        $userID = (int)$params['userID'];
        $signConfig = WelfareModel::getInstance()->getUserSignConfig($userID);
        if (empty($signConfig)) {
            return $this->returnData(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_sign_config . "没有配置数据！");
        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $signConfig);
    }

    /**
     * 获取转盘抽奖配置
     * @param $params
     */
    public function turntableConfig($params)
    {

        $turntableConfig = WelfareModel::getInstance()->getTurntableConfig();
//        var_export($turntableConfig);
        LogHelper::printDebug('-----------------2019' . json_encode($turntableConfig, JSON_UNESCAPED_SLASHES));
        if (empty($turntableConfig)) {
            return $this->returnData(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_turntable_config . "没有配置数据！");
        }
        foreach ($turntableConfig as $k => &$v) {
            // $turntableConfig[$k]['chance'] = $v;
            $v['num'] = FunctionHelper::MoneyOutput($v['num']);
            // echo FunctionHelper::MoneyOutput($v['chance']);
        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $turntableConfig);
    }


}