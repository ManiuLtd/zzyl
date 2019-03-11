<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 15:20
 */

namespace Admin\Model;
use config\EnumConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use Think\Model;

class StatisticsModel extends Model
{
    protected $tableName = 'Admin_menu';
    //所有后台充值金币
    public function allBackRechargeMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where changeMoney > 0 and reason = ' . EnumConfig::E_ResourceChangeReason['BACK_RECHARGE']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //所有后台提取金币
    public function allBackUnRechargeMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where changeMoney < 0 and reason = ' . EnumConfig::E_ResourceChangeReason['BACK_UNRECHARGE']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //商城支付充值金币总额
    public function allPayRechargeMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where reason = ' . EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //代理充值金币总额
    public function allAgentRechargeMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where reason = ' . EnumConfig::E_ResourceChangeReason['AGENT_RECHARGE']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //代理充值金币总额
    public function allSignMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where reason = ' . EnumConfig::E_ResourceChangeReason['SIGN']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //开房消耗金币总额
    public function allCreateRoomMoney() {
        $res = M()->query('select sum(changeMoney) as count from ' . MysqlConfig::Table_statistics_moneychange . ' where reason = ' . EnumConfig::E_ResourceChangeReason['CREATE_ROOM']);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
    //机器人统计
    public function allRobotMoney() {
        $res = M()->query('select sum(gameWinMoney) as count from ' . MysqlConfig::Table_rewardspool);
        return isset($res[0]['count']) ? FunctionHelper::MoneyOutput($res[0]['count']) : 0;
    }
}