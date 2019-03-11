<?php
namespace Admin\Model;
use Think\Model;
//用户模型
class RobotModel extends Model{

    public $tableName = 'rewardsPool';
    public function getList($where, $page = 1, $limit = 10){
        return M()
        ->table('rewardsPool')
        ->where($where)
        ->field('*')
        ->page($page)
        ->limit($limit)
        ->order('id desc')
        ->select();
    }
}
