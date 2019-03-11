<?php
namespace Admin\Model;
use config\MysqlConfig;
use Think\Model;

//用户模型
class CommissionModel extends Model{

    public $tableName = 'recharge_commission';//MysqlConfig::Table_web_recharge_commission;//'recharge_commission';
    public function getList($where, $page = 1, $limit = 10){
        return $this->alias('a')->where($where)
            ->join('left join ' . MysqlConfig::Table_userinfo . ' u on a.recharge_userid = u.userID')
            ->field('a.*, from_unixtime(a.time,"%Y-%m-%d %H:%i:%s") as day, u.name')
            ->page($page, $limit)
//            ->limit($limit)
            ->order('a.id desc')
            ->select();
    }

    /**
     * 获取总抽水
     */
    public function getSumCommission() {
        $res = $this->query('select sum(changeMoney) as count from statistics_moneychange where reason = 8');
        return abs($res['count']);
    }
    //获取代理佣金总额
    public function getAgentCommission() {
        $res = $this->query('select sum(agent_commission) as count from ' . MysqlConfig::Table_web_recharge_commission);
        return $res['count'];
    }
        //获得平台抽水
    public function getOperatorCommission() {
        return $this->getSumCommission() - $this->getAgentCommission();
    }
}
