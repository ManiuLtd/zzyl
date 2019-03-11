<?php
namespace Agent\Model;
use Think\Model;

//用户模型
class DataModel extends Model{
    //公共取数据
    public $tableName = 'agent_member';
    public function get_all_data($model='',$where='',$page='',$order=''){
        $m = M($model);
        $count = $m->where($where)->count();
        $page = new \Think\Page($count,$page);
        $data = $m->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
        $data = [
            '_page' =>  $page->show(),
            '_data'  =>  $data,
        ];
       // echo M($model)->_sql();
        return $data;
    }
		 
    
             public function get_table_data($map = [], $limit = 8)
    {
        $User  = M()->table('friendsGroupAccounts');
        $count = $User->where($map)->count();
        $Page  = new \Think\Page($count, $limit);
        $show  = $Page->show();
        $list  = M()
            ->table('friendsGroupAccounts')
            ->alias('ga')
            ->join('left join userInfo as u on u.userID=ga.userID')
            ->join('left join roomBaseInfo as b on b.roomID=ga.roomID')
            ->where($map)->order('time desc')
            ->field('ga.*,u.name as username,b.name')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        return ['data' => $list, 'page' => $show];
    }

    public function get_table_data2($map = [])
    {
        $User  = M()->table('friendsGroupAccounts');
        $count = $User->where($map)->count();
        $Page  = new \Think\Page($count, $limit);
        $show  = $Page->show();
        $list  = M()
            ->table('friendsGroupAccounts')
            ->alias('ga')
            ->join('left join userInfo as u on u.userID=ga.userID')
            ->join('left join roomBaseInfo as b on b.roomID=ga.roomID')
            ->where($map)->order('time desc')
            ->field('ga.roomtype,ga.time,u.name as username,ga.passwd,b.name,ga.playcount,ga.srctype,ga.userinfolist')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        return $list;
    }

    // 开房战绩列表 // 我的战绩
    public function get_table_data3($map = [],$limit=12,$userid='a')
    {
        $User  = M()->table('friendsGroupAccounts as ga');
        $count = $User->where($map)->count();
        $Page  = new \Think\Page($count, $limit);
        $show  = $Page->show();
        $list  = M()
            ->table('friendsGroupAccounts as ga')
            // ->join('left join userInfo as u on u.userID=ga.userID')
            ->join('left join friendsGroup as g on g.friendsGroupID=ga.friendsGroupID')
            ->join('left join roomBaseInfo as b on b.roomID=ga.roomID')
            ->where($map)->order('time desc')
            ->field('ga.id,ga.userid,ga.time,g.name as groupname,ga.passwd,b.name,ga.playcount,ga.userinfolist')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        if($userid != 'a'){
            // 输赢
            foreach ($list as $k=>$v) {
                $userinfolist = explode('|', $v['userinfolist']);
                array_pop($userinfolist);
                foreach($userinfolist as $vv){
                    $my = explode(',',$vv);
                    // dump($my[0] == $userid);
                    if($my[0] == $userid){
                        $list[$k]['score'] = $my[1] ? $my[1] : 0;
                    } else {
                        // unset($list[$k]);
                    }
                }
            }

            foreach($list as $k=>$v){
                if($v['score'] == 0){
                    unset($list[$k]);
                }
            }
        }

        if($userid == '') return false;
        return ['data' => $list, 'page' => $show];
    }
    //获取配置文件
    public function get_config($key=''){
        if($key){
            $config = M('agent_config')->where(array('key'=>$key))->find();
            if($config){
                return $config;
            }else{
                return false;
            }
        }
        $arr = M('agent_config')->select();
        $config = [];
        foreach($arr as $key => $value){
            $config[$arr[$key]['key']] = $arr[$key]['value'];
        }
        return $config;
    }
}
