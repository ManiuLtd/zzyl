<?php
namespace logic;
use model\AgentModel;
use config\ErrorConfig;
/**
 * 代理模块
 * Class AgentModel
 */
class AgentLogic extends BaseLogic
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

    public function updateLowerCommissionRate($superiorUserID, $lowerUserID, $commissionRate) {
        //判断上下级关系
        $superior= AgentModel::getInstance()->getAgentMemberByUserID($superiorUserID);
        $lower = AgentModel::getInstance()->getAgentMemberByUserID($lowerUserID);
        if ($lower['superior_agentid'] != $superior['agentid']) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '上下级关系不对应'];
        }
//        $lowRate = AgentConfig::BOTTOM_COMMISSION_RATE > 0 ? AgentConfig::BOTTOM_COMMISSION_RATE > $lower['commission_rate'] ? AgentConfig::BOTTOM_COMMISSION_RATE : $lower['commission_rate'] : 0;
        $lowRate = $lower['commission_rate'] > 0 ? $lower['commission_rate'] : 0;
        if ($commissionRate >= $superior['commission_rate'] || $commissionRate < $lowRate) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => "分成比率应该在{$superior['commission_rate']}%~{$lowRate}%"];
        }
        $res = AgentModel::getInstance()->updateLowerCommissionRate($lowerUserID, $commissionRate);
        if (!$res) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '更新失败'];
        }
        return ['code' => ErrorConfig::SUCCESS_CODE, 'msg' => '更新成功'];
    }

    private function __clone()
    {
    }
}
