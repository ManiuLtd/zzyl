<?php
namespace Admin\Controller;

use Think\Controller;

/*
 * 定时任务统计业务
 * */

class CronController extends Controller
{
    protected $time = '';//管理员信息
    protected $idArr = []; //所有的下级代理id和会员id

    public function _initialize(){
        /*if(empty(IS_CLI)){
            echo '只能在命令行模式下面运行!';
            exit;
        }*/
    }

    //实时统计客户每天的业绩，没十分钟执行一次
    public function realtimeStatisticalPerformance()
    {
        $this->time = time();
        $todayDate = date('Y-m-d', $this->time);
        $startTime = strtotime(date('Y-m-d', $this->time));
        $endTime = strtotime(date('Y-m-d', $this->time)) + 86399;

        $Model = new \Think\Model();
        M()->startTrans();
        try{
            $adddata = [];
            //查询出所有的代理
            $agentMemberInfo = M()->table('web_agent_member')->field('username,userid,agentid')->select();
            foreach ($agentMemberInfo as $k1 => $v1){
                //查询出每个代理的所有下级代理ID
                self::diGui($v1['userid']);
                $subordinate_agent_id = $this->zsdata();
                //var_dump($subordinate_agent_id);
                //查询出所有下级代理的玩家以及自己的玩家
                $forarr = array_merge($subordinate_agent_id, [$v1['agentid']]);
                $memberidarr = [];
                foreach ($forarr as $k9 => $v9){
                    $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $subordinate_agent_id));
                }
                //$subordinate_agent_id  所有的下级代理id
                //$memberidarr  自己以及自己所有下级代理的玩家
                //我的团队成员id
                $team_userid_arr = array_merge($subordinate_agent_id, $memberidarr);
                //var_dump($team_userid_arr);
                //查询出该团队今天到现在的业绩
                if(!empty($team_userid_arr)){
                    $inuserid = implode(',', $team_userid_arr);
                    $sql = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and reason = 3 and userID in ({$inuserid})";
                    //var_dump($sql);
                    //本日团队贡献
                    $todayTeamAmount = $Model->query($sql);
                    //var_dump($todayTeamAmount);exit;
                }else{
                    $todayTeamAmount[0]['summoney'] = 0;
                }

                //本日个人贡献
                $sql2 = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and reason = 3 and userID = {$v1['userid']}";
                //var_dump($sql2);exit;
                $todayPersonalAmount = $Model->query($sql2);
                /*var_dump($todayTeamAmount);
                var_dump($todayPersonalAmount);*/

                //组装插入的数据
                $adddata[$k1]['userid'] = $v1['userid'];
                $adddata[$k1]['name'] = $v1['username'];
                $adddata[$k1]['day_performance'] = $todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'];
                $adddata[$k1]['day_team_performance'] = $todayTeamAmount[0]['summoney'];
                $adddata[$k1]['day_personal_performance'] = $todayPersonalAmount[0]['summoney'];
                $adddata[$k1]['create_date'] = $todayDate;
                $adddata[$k1]['create_time'] = $this->time;
                var_dump($adddata);exit;
                $this->idArr = [];
            }
            //var_dump($adddata);
            //删除今天的所有数据
            M()->table('statistics_day_performance')->where(['create_date' => $todayDate])->delete();
            //批量导入刚才组装好的数据
            $res = M()->table('statistics_day_performance')->addAll($adddata);
            if(empty($res)){
                M()->rollback();
                echo '数据插入失败';
            }

            M()->commit();
            echo '执行成功';exit;
        }catch (Exception $e){
            \Think\Log::write('定时任务错误信息111111:'.$e->getMessage());
            M()->rollback();
            echo $e->getMessage();exit;
        }


    }


    /*
     * 获取所有会员id
     * */
    protected function getmemberid($agentid, $subordinate_agent_id){
        $where['b.agentID'] = $agentid;
        //找到不是代理的用户
        if(!empty($subordinate_agent_id)){
            $where['b.userID'] = ['NOT IN', $subordinate_agent_id];
        }
        $data = M('agent_bind as b')->field('userID')->where($where)->select();
        return array_column($data, 'userid');
    }

    /*
     * 组装数据
     * */
    protected function zsdata(){
        $returndata = [];
        foreach ($this->idArr as $k => $v) {
            if(!in_array($v['userid'], $returndata)){
                $returndata[] = $v['userid'];
            }
            if(!empty($v['totalid'])){
                foreach ($v['totalid'] as $k2 => $v2){
                    if(!in_array($v2['userid'], $returndata)){
                        $returndata[] = $v2['userid'];
                    }
                }
            }

        }
        return $returndata;
    }

    /*
     * 递归函数查询下级代理id
     * */
    protected function diGui($userid){
        $mapWhere['superior_agentid'] = $userid;
        $dataInfo = M('agent_member')->field('userid')->where($mapWhere)->select();
        foreach ($dataInfo as $k => $v) {
            $map1Where['superior_agentid'] = $v['userid'];
            $v['totalid'] = M('agent_member')->field('userid')->where($map1Where)->select();
            $this->idArr[] = $v;
            self::diGui($v['userid']);
        }
    }


    //每天凌晨三十分统计客户每天的奖励
    public function statisticsDailyRewards()
    {
        $this->time = (time() - 7200);  //前一天的时间错
        $todayDate = date('Y-m-d', $this->time); // 前一天年月日
        $startTime = strtotime(date('Y-m-d', $this->time));
        $endTime = strtotime(date('Y-m-d', $this->time)) + 86399;

        $Model = new \Think\Model();
        M()->startTrans();
        try{
            $adddata = [];
            //查询出所有的代理
            $agentMemberInfo = M()->table('web_agent_member')->field('username,userid,agentid')->select();
            foreach ($agentMemberInfo as $k1 => $v1){
                //查询出每个代理的所有下级代理ID
                self::diGui($v1['userid']);
                $subordinate_agent_id = $this->zsdata();
                //var_dump($subordinate_agent_id);
                //查询出所有下级代理的玩家以及自己的玩家
                $forarr = array_merge($subordinate_agent_id, [$v1['agentid']]);
                $memberidarr = [];
                foreach ($forarr as $k9 => $v9){
                    $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $subordinate_agent_id));
                }

                //$subordinate_agent_id  所有的下级代理id
                //$memberidarr  自己以及自己所有下级代理的玩家
                //我的团队成员id
                $team_userid_arr = array_merge($subordinate_agent_id, $memberidarr);
                //var_dump($team_userid_arr);exit;
                //var_dump($team_userid_arr);
                //查询出该团队今天到现在的业绩
                if(!empty($team_userid_arr)){
                    $inuserid = implode(',', $team_userid_arr);
                    $sql = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and reason = 3 and userID in ({$inuserid})";
                    //var_dump($sql);
                    //本日团队贡献
                    $todayTeamAmount = $Model->query($sql);
                    //var_dump($todayTeamAmount);exit;
                }else{
                    $todayTeamAmount[0]['summoney'] = 0;
                }

                //本日个人贡献
                $sql2 = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and reason = 3 and userID = {$v1['userid']}";
                //var_dump($sql2);exit;
                $todayPersonalAmount = $Model->query($sql2);
                //var_dump($todayPersonalAmount);exit;

                //组装插入的数据
                $adddata[$k1]['userid'] = $v1['userid'];
                $adddata[$k1]['name'] = $v1['username'];
                $adddata[$k1]['day_performance'] = $todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'];
                $adddata[$k1]['day_team_performance'] = $todayTeamAmount[0]['summoney'];
                $adddata[$k1]['day_personal_performance'] = $todayPersonalAmount[0]['summoney'];
                $adddata[$k1]['create_date'] = $todayDate;
                $adddata[$k1]['create_time'] = time();
                $this->idArr = [];
            }

            //计算用户的奖励
            $resadddate = $this->getReward($adddata);
            //var_dump($resadddate);exit;


            //删除今天的所有数据
            M()->table('statistics_day_performance')->where(['create_date' => $todayDate])->delete();
            //批量导入刚才组装好的数据
            $res = M()->table('statistics_day_performance')->addAll($resadddate);
            if(empty($res)){
                M()->rollback();
                echo '数据插入失败';
            }

            M()->commit();
            echo '执行成功';exit;
        }catch (Exception $e){
            \Think\Log::write('定时任务错误信息111111:'.$e->getMessage());
            M()->rollback();
            echo $e->getMessage();exit;
        }

    }

    /*
     * 后去每个用户的奖励
     * $param arr  $adddata  统计的数据
     * */
    public function getReward($adddata)
    {
        //var_dump($adddata);exit;
        //查询出抽层比例信息
        $ratioInfo = M()->table('pumping_ratio')->field('agent_start_value,agent_end_value,pump_money')->select();
        foreach ($adddata as $key1 => $val1){
            //获取该用户的所有下一级代理
            $agent_id_arr = M('agent_member')->field('userid')->where(['superior_agentid' => $val1['userid']])->select();

            if(!empty($agent_id_arr)){  //如果存在下级代理减去下级代理的奖励就是自己的奖励
                $subordinaterewardMoney = '';  //下级奖励金额总和
                foreach ($agent_id_arr as $key2 => $val2){
                    foreach ($adddata as $key3 => $val3){
                        if($val2['userid'] === $val3['userid']){
                            $subordinaterewardMoney += $this->getJlmongey($val3['day_performance']/100, $ratioInfo);
                            /*if($val1['userid'] == 122021){
                                var_dump($agent_id_arr);
                                var_dump($val3['day_performance']);
                                var_dump($subordinaterewardMoney);exit;
                            }*/
                        }
                    }
                }
                /*if($val1['userid'] == 122021){
                    $myRewardMoney = $this->getJlmongey($val1['day_performance']/100, $ratioInfo);
                    $adddata[$key1]['reward'] = $myRewardMoney - $subordinaterewardMoney;
                }*/
                //计算自己奖励的金额的总和
                $myRewardMoney = $this->getJlmongey($val1['day_performance']/100, $ratioInfo);
                $adddata[$key1]['reward'] = $myRewardMoney - $subordinaterewardMoney;
            }else{   //如果没有下级代理根据自己的总业绩计算奖励
                $adddata[$key1]['reward'] = $this->getJlmongey($val1['day_performance']/100, $ratioInfo);
            }
            //更改该用户的可提现金额
            if(!empty($adddata[$key1]['reward'])){
                M()->table('web_agent_member')->where(['userid' => $val1['userid']])->setInc('balance',$adddata[$key1]['reward'] * 100);
                //记录账单
                $this->addApplyPos($adddata[$key1]['userid'], $adddata[$key1]['reward']);
            }

        }
        return $adddata;
    }

    /*
     * 添加账单
     * $userid  用户id
     * $handle_money 提现金额
     * */
    protected function addApplyPos($userid, $handle_money){
        //查询出该代理的信息
        $userInfo = M('agent_member')->field('userid,username,agent_level,balance')->where(['userid' => $userid])->find();
        //记录账单
        $billdata = [
            'username' => $userInfo['username'],
            'agent_level' => $userInfo['agent_level'],
            'front_balance' => $userInfo['balance'],  //总的可提现金额
            'handle_money' => ($handle_money * 100),  //奖励金额
            'after_balance' => $userInfo['balance'] + $handle_money * 100, //剩余可提现金额
            '_desc' => '代理奖励',
            'make_time' => time(),
            'make_userid' => $userInfo['userid'],
            'amount' => 0,
            'commission' => ($handle_money * 100),
            'under_amount' => 0,
            'under_commission' => 0,
        ];
        M('bill_detail')->add($billdata);
    }

    /*
     * 根据每个人的业绩计算奖励
     * $param int $performance 用户的业绩
     * $param arr $ratioInfo   抽层比例信息
     * */
    public function getJlmongey($performance, $ratioInfo)
    {
        foreach ($ratioInfo as $key => $value){
            if($performance >= $value['agent_start_value'] && $performance < $value['agent_end_value']){
                return floor($performance/10000) * $value['pump_money'];
            }
        }

    }

    /*
     * 根据每个人的业绩计算奖励
     * $param int $performance 用户的业绩
     * $param arr $ratioInfo   抽层比例信息
     * */
    public function testCheShi()
    {
        $userid = 122043;
        $handle_money = 200;
        //查询出该代理的信息
        $userInfo = M('agent_member')->field('userid,username,agent_level,balance')->where(['userid' => $userid])->find();
        //记录账单
        $billdata = [
            'username' => $userInfo['username'],
            'agent_level' => $userInfo['agent_level'],
            'front_balance' => $userInfo['balance'],  //总的可提现金额
            'handle_money' => ($handle_money * 100),  //奖励金额
            'after_balance' => $userInfo['balance'] + $handle_money * 100, //剩余可提现金额
            '_desc' => '代理奖励',
            'make_time' => time(),
            'make_userid' => $userInfo['userid'],
            'amount' => 0,
            'commission' => ($handle_money * 100),
            'under_amount' => 0,
            'under_commission' => 0,
        ];
        $res = M('bill_detail')->add($billdata);
        var_dump($res);exit;
    }

}
