<?php
namespace Admin\Model;
use config\MysqlConfig;
use Think\Model;

//用户模型
class StatisticsRewardspoolModel extends Model{

    public $tableName = 'statistics_rewardspool';//MysqlConfig::Table_web_recharge_commission;//'recharge_commission';
    public function getList($where, $page = 1, $limit = 10){
        // exit;
        return $this->alias('a')->where($where)
            ->join('left join ' . MysqlConfig::Table_userinfo . ' u on a.recharge_userid = u.userID')
            ->field('a.*, from_unixtime(a.time,"%Y-%m-%d %H:%i:%s") as day, u.name')
            ->page($page)
            ->limit($limit)
            ->order('a.id desc')
            ->select();
    }
}