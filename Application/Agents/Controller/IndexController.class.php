<?php
namespace Agents\Controller;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.GetRedis', '', '.class.php');
class IndexController extends AgentController
{
    //我的信息
    public function index()
    {

        //获取我的信息
        $my                      = M('Agentmember')->find(UID);
        $my['balance']           = sprintf("%.2f", $my['balance'] / 100);
        $my['under_money']       = sprintf("%.2f", $my['under_money'] / 100);
        $my['not_under_money']   = sprintf("%.2f", $my['not_under_money'] / 100);
        $my['history_pos_money'] = sprintf("%.2f", $my['history_pos_money'] / 100);

  $member = M('Agentmember')->select();      
 foreach ($member as $k => $v) {
        $where['username']  = $member[$k]['username'];
        $num = M('Billdetail')->where($where)->sum('handle_money');
        //echo M('Billdetail')->getlastsql();
        //echo "<hr/>";
        $member[$k]['num'] = sprintf("%.2f",((int)$num?$num:0)/100);

    }


         $date = array_column($member, 'num');
         array_multisort($date,SORT_DESC,$member);
         //dump($member);
        $ranks=array();
        foreach ($member as $key => $value) {
            if($member[$key]['username']==$my['username']){
               $ranks['sort'] = $key+1;
               $ranks['num'] = $value['num'];
            }
        }


        $this->assign('ranks',$ranks);


        //今日业绩
        //今天开始时间
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //今天结束时间
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        $map['make_time'] = ['between',[$begin,$end]];
        $arr=array();
         $map['username'] =$my['username'];
                    $data = D('Data')->get_all_data('Billdetail',$map,'','make_time desc');
                     foreach($data['_data'] as $k => $v){
                            $data['_data'][$k]['handle_money'] = sprintf("%.2f",$data['_data'][$k]['handle_money']/100);

                             $arr[]=$data['_data'][$k]['handle_money'];
                        }

        if(empty($arr)){
          $performance = 0;
        }else{
          $performance=array_sum($arr);
        }

        //本月开始时间
        $mbegin=mktime(0,0,0,date('m'),1,date('Y'));
         //本月结束时间
        $mend=mktime(23,59,59,date('m'),date('t'),date('Y'));

        $maps['make_time'] = ['between',[$mbegin,$mend]];

        $marr=array();
         $maps['username'] =$my['username'];
                    $data = D('Data')->get_all_data('Billdetail',$maps,'','make_time desc');
                     foreach($data['_data'] as $k => $v){
                            $data['_data'][$k]['handle_money'] = sprintf("%.2f",$data['_data'][$k]['handle_money']/100);

                             $marr[]=$data['_data'][$k]['handle_money'];
                        }
        if(empty($marr)){
          $mperformance = 0;
        }else{
          $mperformance=array_sum($marr);
        }
//echo $mperformance;
        $user_num = M('Bindagentid')->where(['agentID' => $my['agentid']])->count();
        $this->assign('performance',$performance);
        $this->assign('mperformance',$mperformance);
        $this->assign('ranks',$ranks);
        $this->assign("num",$user_num);
        $this->assign('my', $my);
        $this->display();
    }





//个人信息
    public function info(){
        $my  = M('Agentmember')->find(UID);
        $my['balance']   = sprintf("%.2f", $my['balance'] / 100);
        $my['under_money']       = sprintf("%.2f", $my['under_money'] / 100);
        $my['not_under_money']   = sprintf("%.2f", $my['not_under_money'] / 100);
        $this->assign('my', $my);
        $this->display();
    }


  
  //我的会员
    public function my_user()
    {

        $userid = I('userid');
        $username = I('username');
        if(!empty($userid)){
            $where['userID'] = I('userid');
         }
          if(!empty($username)){
            $where['username'] = I('username');
         }

        $my               = M('Agentmember')->where(['id' => UID])->find();
        $where['agentID'] = $my['agentid'];

        $data             = D('Data')->get_all_data('Bindagentid', $where,20, 'bind_time desc');
         //dump($data);
        //获取会员为我带来的收入以及他们充值的总数
        $user = $data['_data'];

        //print_r($user);die;
        foreach ($user as $k => $v) {
            $map['recharge_userid'] = $user[$k]['userid'];
            $map['bind_agentid']    = $user[$k]['agentid'];
            $user[$k]['recharge']   = sprintf("%.2f", M('Rechargecommission')->where($map)->sum('recharge_amount') / 100);
            //用户为我带来的收益
            //获取所有的用户充值分佣记录
            $c1            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'bind_username' => $my['username']])->sum('bind_member_commission') / 100);
            $c2            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'level2_username' => $my['username']])->sum('level2_member_commission') / 100);
            $c3            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'level3_member_username' => $my['username']])->sum('level3_member_commission') / 100);
            $user[$k]['c'] = $c1 + $c2 + $c3;
            $u             = M('Agentmember')->where(['userid' => $user[$k]['userid']])->find();
            //dump($u);
            if ($u) {
                unset($user[$k]);
            }
        }

        // dump($$user);die;
        // dump($user);
      
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $user);
        $this->display();
    }
   

//我的手下三级代理
    public function my_agent(){
        $my = M('Agentmember')->where(['id'=>UID])->find();

        $agentCount  = M('bindagentid')->where(['agentID' => $my['agentid']])->count();
        $qrcodeCount = M('codeinvitation')->where(['agent_id' => $my['agentid']])->count();
        //获取我的手下二级代理
        $arr = []; 
        $x = M('Agentmember')->where(['superior_agentid'=>$my['agentid']])->select();
        if($x){
            foreach($x as $k => $v){
                $x[$k]['type'] = 2;
                $arr[] = $x[$k]; 
                $z = M('Agentmember')->where(['superior_agentid'=>$x[$k]['agentid']])->select();
                if($z){
                    foreach($z as $i => $a){
                        $z[$i]['type'] = 3;
                        $arr[] = $z[$i];
                    }
                }
            } 
        }

        foreach($arr as $k => $v){
            //获取会员数 
            //下级代理数
            $arr[$k]['user_count'] = M('Bindagentid')->where(['agentID'=>$arr[$k]['agentid']])->count();
            $arr[$k]['agent_count'] = M('Agentmember')->where(['superior_agentid'=>$arr[$k]['agentid']])->count();
            $arr[$k]['balance'] = sprintf("%.2f",$arr[$k]['balance']/100);
            $arr[$k]['history_pos_money'] = sprintf("%.2f",$arr[$k]['history_pos_money']/100);

        }
        $this->assign('_data',$arr);
        $this->assign('qrcodeCount', $qrcodeCount);
        $this->assign('agentCount', $agentCount - $qrcodeCount);
        $this->display();
    }











    //申请提现
    public function apply_pos()
    {

  if(IS_POST){
     
        //验证用户申请数
        $apply_money = I('apply_money');
        $my          = M('Agentmember')->find(UID);
        if (!$my['wechat']) {
            $this->error('请填写完整您的微信或者银行卡账号');
        }
        if (sprintf("%.2f", $my['balance'] / 100) < 100) {
            $this->error('您的余额不足100，暂时不能申请提现');
        }
        if (!preg_match("/^[1-9][0-9]*$/", $apply_money)) {
            $this->error('申请提款数只能是正整数');
        }
        if ($apply_money > sprintf("%.2f", $my['balance'] / 100)) {
            $this->error('您只有' . sprintf("%.2f", $my['balance'] / 100) . '余额可以申请提现');
        }
        M()->startTrans();
        //可以申请，先冻结
        $res1 = M('Agentmember')->where(['id' => $my['id']])->setDec('balance', $apply_money * 100);
        $res3 = M('Agentmember')->where(['id' => $my['id']])->setInc('history_pos_money', $apply_money * 100);
        //记录
        $data = [
            'username'      => $my['username'],
            'userid'        => $my['userid'],
            'level_agent'   => $my['agent_level'],
            'bankcard'      => $my['bankcard'],
            'wechat'        => $my['wechat'],
            'apply_time'    => time(),
            'front_balance' => $my['balance'],
            'after_balance' => $my['balance'] - $apply_money * 100,
            'apply_amount'  => $apply_money * 100,
            'status'        => 0,
            'agentid'       => $my['agentid'],
        ];
        $res2 = M('Agentapplypos')->add($data);
        if ($res1 && $res2 && $res3) {
            M()->commit(); //都执行成功执行提交
            $redis     = \GetRedis::get();
            $make_name = iconv('ASCII', 'UTF-8', $redis->redis->hget('userInfo|' . $my['userid'], 'name'));
            //记录账单
            $data = [
                'username'         => $my['username'],
                'agent_level'      => $my['agent_level'],
                'front_balance'    => $my['balance'],
                'handle'           => (-$apply_money * 100),
                'after_balance'    => $my['balance'] - $apply_money * 100,
                '_desc'            => '提款',
                'make_time'        => time(),
                'make_userid'      => $my['userid'],
                'make_name'        => $make_name,
                'amount'           => 0,
                'commission'       => (-$apply_money * 100),
                'under_amount'     => 0,
                'under_commission' => 0,
            ];
            M('Billdetail')->add($data);
            $this->success('申请成功');
        } else {
            M()->rollback(); //又一个错误进行回滚
            $this->error('申请失败');
        }

  }else{
   
    $my  = M('Agentmember')->find(UID);
    $my['balance']   = sprintf("%.2f", $my['balance'] / 100);
    $my['history_pos_money'] = sprintf("%.2f", $my['history_pos_money'] / 100);
   $this->assign('my',$my);
   $this->display();

  }

    }


public function apply_pos_record(){

    $my = M('Agentmember')->find(UID);

        $type = I('type');
        $search = I('search');
        if($type && $search){
            switch($type){
                case 1:
                $where['username'] = $search;
                break;
                case 2:
                $where['userid'] = $search;
                break;
                case 3:
                $where['agentid'] = $search;
                break;
                case 4:
                $where['wechat'] = $search;
                break;
            }
        }
        $start = I('start');
        $stop = I('stop');
        if($start && $stop){
            $start = strtotime($start);
            $stop = strtotime($stop);
            $where['apply_time'] = ['between',[$start,$stop]];
        }

       $where['username'] =  $my['username'];

        $data = D('Data')->get_all_data('Agentapplypos',$where,10,'apply_time desc');
        foreach($data['_data'] as $k => $v){
            $data['_data'][$k]['front_balance'] = sprintf("%.2f",$data['_data'][$k]['front_balance']/100);
            $data['_data'][$k]['apply_amount'] = sprintf("%.2f",$data['_data'][$k]['apply_amount']/100);
            $data['_data'][$k]['after_balance'] = sprintf("%.2f",$data['_data'][$k]['after_balance']/100);
        }



        $this->assign('_data',$data['_data']);
        $this->assign('_page',$data['_page']);
        $this->display();

 
}






    //账单明细
    public function bill_detail()
    {
        $search = I('search', '');
        if ($search != '') {
            if ((int) $search == 0) {
                $where['make_name|username'] = array('like', $search . '%');
            } else {
                $where['make_userid'] = $search;
            }

        }

        $start = I('start', '');
        $end   = I('end', '');

        if ($start && $end) {

            if ($start > $end) {
                $this->error('开始时间不能大于结束时间');
            }

            $where['make_time'] = ['between', [strtotime($start), strtotime($end)]];
        }
        $my = M('Agentmember')->where(['id' => UID])->find();
        $where['username'] = $my['username'];
        $data = D('Data')->get_all_data('Billdetail', $where, 12, 'make_time desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['front_balance']    = sprintf("%.2f", $data['_data'][$k]['front_balance'] / 100);
            $data['_data'][$k]['handle_money']     = sprintf("%.2f", $data['_data'][$k]['handle_money'] / 100);
            $data['_data'][$k]['after_balance']    = sprintf("%.2f", $data['_data'][$k]['after_balance'] / 100);
            $data['_data'][$k]['amount']           = sprintf("%.2f", $data['_data'][$k]['amount'] / 100);
            $data['_data'][$k]['commission']       = sprintf("%.2f", $data['_data'][$k]['commission'] / 100);
            $data['_data'][$k]['under_amount']     = sprintf("%.2f", $data['_data'][$k]['under_amount'] / 100);
            $data['_data'][$k]['under_commission'] = sprintf("%.2f", $data['_data'][$k]['under_commission'] / 100);
        }

        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //修改个人信息
    public function change_info()
    {
        //检测微信和银行卡以及邮箱是否以及被人使用
        $where1 = [
            'id'     => ['neq', UID],
            'wechat' => I('wechat'),
        ];
        $where2 = [
            'id'       => ['neq', UID],
            'bankcard' => I('bankcard'),
        ];
        $where3 = [
            'id'    => ['neq', UID],
            'email' => I('email'),
        ];
        $agent1 = M('Agentmember')->where($where1)->find();
        $agent2 = M('Agentmember')->where($where2)->find();
        $agent3 = M('Agentmember')->where($where3)->find();
        if ($agent1) {
            $this->error('该微信已经被用了');
        }
        if ($agent2) {
            $this->error('该银行卡已经被用了');
        }
        if ($agent3) {
            $this->error('该邮箱已经被用了');
        }
        $data = [
            'id'       => UID,
            'wechat'   => I('wechat'),
            'bankcard' => I('bankcard'),
            'email'    => I('email'),
        ];
        if (M('Agentmember')->save($data)) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
    //vip房间
    public function vip_room()
    {
        $my = M('Agentmember')->where(['id' => UID])->find();
        //获取所有的房间列表
        $where = ['agentID' => $my['agentid']];
        $data  = D('Data')->get_all_data('viproom', $where, 15, 'createTime desc');
        foreach ($data['_data'] as $k => $v) {
            $game                      = M()->table('gameBaseInfo')->where(['gameID' => $data['_data'][$k]['gameid']])->find();
            $data['_data'][$k]['game'] = $game['name'];
        }
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //代理编辑房间密码
    public function change_room_password()
    {
        if (IS_POST) {
            $data = [
                'id'           => I('id'),
                'roomPassword' => I('roomPassword'),
            ];
            $room = M('viproom')->find(I('id'));
            if (!$room['canchangepassword']) {
                $this->error('没有修改房间密码的权限');
            }
            if (time() > $room['roomendtime']) {
                $this->error('该房间已失效');
            }
            if (time() > $room['bindendtime']) {
                $this->error('您的分配时效期已到，无法进行管理房间');
            }
            if (M('viproom')->save($data)) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败' . M('viproom')->getError());
            }
        } else {
            $id = I('id');
            $this->assign('id', $id);
            $this->display();
        }
    }

    // 用户详信息
    public function userinfo()
    {
        $userid = I('userid');
        //基本信息
        $user = M()->table('userInfo')->where(['userID' => $userid])->find();
        //获取绑定代理信息
        $bind_agentid = M('Bindagentid')->where(['userID' => $userid])->find();
        if ($bind_agentid) {
            $user['bind_agentid'] = $bind_agentid['agentid'];
            $bind_agent           = M('Agentmember')->where(['agentid' => $bind_agentid['agentid']])->find();
            if ($bind_agent) {
                $user['bind_userid'] = $bind_agent['userid'];
            }
        }
        //判断我的类型
        $my = M('Agentmember')->where(['userid' => $user['userid']])->find();
        if ($my) {
            $user['user_type'] = $my['agent_level'];
            //代理下级代理数
            $agent_count         = M('Agentmember')->where(['superior_username' => $my['username']])->count();
            $user['agent_count'] = $agent_count;
            //代理余额
            $user['agent_balance'] = sprintf("%.2f", $my['balance'] / 100);
            //提现
            $user['pos_all_money']  = sprintf("%.2f", $my['history_pos_money']);
            $user['last_pos_money'] = sprintf("%.2f", M('Agentapplypos')->where(['agentid' => $my['agentid']])->getField('apply_amount'));

            $user['agentid'] = $my['agentid'];
            //   $this->assign('agent',$my['superior_agentid']);
        } else {
            $user['user_type'] = 0;
        }

        $bindagentid = M('bindagentid')->where(['userID' => $userid])->find();
        $this->assign('agent', $bindagentid['agentid']);

        //最近登录时间
        $login                   = M()->table('statistics_logonAndLogout')->where(['userID' => $userid, 'type' => 1])->order('time desc')->find();
        $user['last_login_time'] = $login['time'];
        $user['last_login_ip']   = $login['ip'];
        //最近充值数
        $order                       = M('Orders')->where(['userID' => $userid, 'status' => 1, 'consumeType' => 0])->order('create_time desc')->find();
        $user['last_recharge_money'] = sprintf("%.2f", $order['consumenum'] / 100);
        //个人运营信息
        //商城统计
        $user['recharge_all_money'] = sprintf("%.2f", M('Orders')->where(['userID' => $userid, 'status' => 1, 'consumeType' => 0])->sum('consumeNum') / 100);
        $user['last_recharge_time'] = $order['create_time'];
        //大厅统计
        $user['share_count']     = M('sharegame')->where(['userid' => $userid])->count();
        $user['login_count']     = M()->table('statistics_logonandlogout')->where(['userID' => $userid, 'type' => 1])->count();
        $user['feedback_count']  = M('Adminfeedback')->where(['userID' => $userid])->count();
        $user['convert_count']   = M('Orders')->where(['userID' => $userid, 'buyType' => 4, 'status' => 1])->count();
        $user['turntable_count'] = M('Turntablerecord')->where(['userID' => $userid])->count();
        $user['sign_count']      = M('Signrecord')->where(['userID' => $userid])->count();
        //金币房卡统计
        $user['turntable_get_jewels'] = M('Turntablerecord')->where(['userID' => $userid, 'prizeType' => 2])->sum('num');
        $user['turntable_get_money']  = M('Turntablerecord')->where(['userID' => $userid, 'prizeType' => 1])->sum('num');
        $user['sign_get_jewels']      = M('Signrecord')->where(['userID' => $userid, 'prizeType' => 2])->sum('num');
        $user['sign_get_money']       = M('Signrecord')->where(['userID' => $userid, 'prizeType' => 1])->sum('num');
        // 转赠统计
        $user['zz_money']    = M()->table('statistics_sendGift')->where(['userID' => $userid, 'resourceType' => 1])->sum('resourceNumber'); //转赠金币
        $user['zz_jewels']   = M()->table('statistics_sendGift')->where(['userID' => $userid, 'resourceType' => 2])->sum('resourceNumber'); //转赠房卡
        $user['zzxh_money']  = M()->table('statistics_sendGift')->where(['userID' => $userid, 'resourceType' => 2])->sum('recievedNumber'); //累计接受金币
        $user['zzxh_jewels'] = M()->table('statistics_sendGift')->where(['userID' => $userid, 'resourceType' => 2])->sum('recievedNumber'); //累计接受房卡
        // 魔法表情
        $user['magicExpress']       = M()->table('statistics_magicExpress')->where(['userID' => $userid])->sum('costMoney');
        $user['magicExpress_count'] = M()->table('statistics_magicExpress')->where(['userID' => $userid])->count();
        //世界广播
        $user['horn_count']  = M()->table('statistics_horn')->where(['userID' => $userid])->count();
        $user['online_time'] = sprintf('%.2f', $this->online_time($userid) / 3600);

        $user['all_count'] = M()->table('statistics_moneyChange')->where(['userID' => $userid])->count();
        $user['wincount']  = M()->table('statistics_moneyChange')->where(['userID' => $userid, 'reason' => 14])->count();
        //判断我是不是在黑名单
        $str = file_get_contents('/usr/local/nginx/html/timedTask/black.txt');
        $arr = explode("|", $str);
        //dump($arr);
        if (in_array($user['logonip'], $arr)) {
            $user['inblack'] = 1;
        } else {
            $user['inblack'] = 0;
        }
        $str = file_get_contents('/usr/local/nginx/html/timedTask/white.txt');
        $arr = explode("|", $str);
        //dump($arr);
        if (in_array($user['logonip'], $arr)) {
            $user['inwhite'] = 1;
        } else {
            $user['inwhite'] = 0;
        }

        //dump($user);
        $this->assign('user', $user);
        $this->assign("userid",$userid);
        $this->display();
    }





    public function online_time($userID)
    {
        $online_time = 0;
        //查询我这个用户所有的记录
        $res = M()->table('statistics_logonandlogout')->order('time desc')->where(['userID' => $userID])->select();
        //如果为空或者记录条数小于2
        if (!$res || count($res) < 2) {
            return $online_time;
        } else {

            $arr   = [];
            $unset = [];
            foreach ($res as $k => $v) {
                if ($res[$k]['type'] == 2 && $res[$k + 1]['type'] == 1 || $res[$k]['type'] == 1 && $res[$k + 1]['type'] == 2) {
                    $arr[] = $res[$k];
                } else {
                    $unset[] = $res[$k];
                }

            }

            $count = count($arr);
            if ($arr[$count - 1]['type'] == 2) {
                unset($arr[$count - 1]);
            }

            $login     = 0;
            $login_out = 0;
            foreach ($arr as $v) {
                if ($v['type'] % 2 == 0) {
                    $login_out += $v['time'];
                } else {
                    $login += $v['time'];
                }
            }

            return $login_out - $login;
        }
    }

    // 添加代理
    public function addAgent()
    {
        if (IS_POST) {
            $data['username']    = I('username');
            $data['userid']      = I('userid');
            $data['agent_level'] = I('agent_level');
            $data['disabled']    = I('disabled');
            $password            = I('password');
            $repassword          = I('repassword');
            $data['wechat']      = I('wechat');
            $data['bankcard']    = I('bankcard');
            $data['real_name']   = I('real_name');
            $userid              = M()->table('userinfo')->where(['userID' => $data['userid']])->find();
            if (!$userid) {
                $this->error('游戏ID不存在');
            }

            if (!$data['wechat'] || !$data['bankcard'] || !$data['real_name']) {
                $this->error('微信和银行卡和姓名必须添加');
            }
            /* if(!preg_match('/^([1-9]{1})(\d{14}|\d{18})$/', $data['bankcard'])){
            $this->error('请输入正确的银行卡号');
            }*/
            if (!preg_match("/^1[34578]{1}\d{9}$/", $data['username'])) {$this->error('请输入正确的代理账号(手机号)');}
            $member = M('Agentmember')->where(['username' => $data['username']])->find();
            if ($member) {$this->error('该代理账号已经存在');}
            if (M('Agentmember')->where(['userid' => $data['userid']])->find()) {$this->error('该游戏ID已经被使用');}
            if (M('Agentmember')->where(['wechat' => $data['wechat']])->find()) {$this->error('该微信已经被使用');}
            if (M('Agentmember')->where(['userid' => $data['bankcard']])->find()) {$this->error('该银行卡账号已经被使用');}
            //验证
            $arr = [1, 2, 3];
            // 自己级别
            $level = M('agentmember')->where(['id' => session('agent_user_id')])->getfield('agent_level');
            if ($data['agent_level'] <= $level) {
                $this->error('权限不足!');
            }
            if (!in_array($data['agent_level'], $arr)) {$this->error('请选择代理级别');}
            // $redis = \GetRedis::get();
            // $user = $redis->redis->hgetall('userInfo|'.$data['userid']);
            /*if(!$user){$this->error('游戏ID不存在');}*/
            if ($password !== $repassword) {$this->error('两次密码不一致');}
            if (strlen($password) < 6) {$this->error('登录密码不能低于6位数');}
            //验证完成根据游戏ID获取游戏昵称以及上级代理的登录账号和邀请码
            $res  = $this->get_superior($data['userid']);
            $find = M('agentmember')->where(['id' => session('agent_user_id')])->find();
            //var_dump($find);die;
            $data['superior_agentid']  = $find['agentid'];
            $data['superior_username'] = $find['username'];
            $data['register_time']     = time();
            $data['password']          = md5($password);
            // $data['agentid'] = $this->get_max_agentid()+1;
            $data['agentid'] = $this->get_max_agentid() + 1;
            $data['status']  = 1;
            //获取最大的agentid
            if (M('agencyaudit')->add($data)) {
                // operation_record(UID,'添加代理'.$data['username']);
                // $url = 'http://'.$_SERVER['HTTP_HOST'].'/Home/Wechat/share/userID/'.$data['userid'].'/agentID/'.$data['agentid'];
                // $find = M()->table('userInfo')->where(['userID'=>$data['userid']])->find();
                // $this->personal_send_email($data['userid'],$data['agent_level'],$data['agentid']);
                // $this->download_file($find['headurl'],'./Uploads/qrcode/'.md5('Agent'.$find['userid']).'.jpg');
                // $this->scerweima1($url,$data['userid'],'./Uploads/qrcode/'.md5('Agent'.$find['userid']).'.jpg',$find['name'],$data['agentid']);
                $this->success('添加成功,请等待客服审核');
            } else {
                $this->error('添加失败');
            }
        } else {
            $level = M('agentmember')->where(['id' => session('agent_user_id')])->getfield('agent_level');

            if ($level != 3) {
                $map['id'] = ['gt', $level];
            } else {
                $map['id'] = 3;
            }


            $group = M('agentgroup')->where($map)->select();
           // echo M('agentgroup')->getlastsql();
            $this->assign('group', $group);
            $this->display();
        }
    }

    //获取agentid(邀请码)
    protected function get_max_agentid()
    {
        $agentid = rand(mt_rand(1, 9) . mt_rand(0, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9), mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(0, 9));
        return $agentid;
    }

    //获取上级代理的登录账号以及邀请码
    protected function get_superior($userid)
    {
        $agentID = M('Bindagentid')->where(['userID' => $userid])->getField('agentID');
        $res     = [];
        if (!$agentID) {
            $res['superior_agentid']  = '';
            $res['superior_username'] = '';
        } else {
            $res['superior_agentid']  = $agentID;
            $username                 = M('Agentmember')->where(['agentid' => $agentID])->getField('username');
            $res['superior_username'] = $username;
        }
        return $res;
    }

    // 审核列表
    public function agent_list()
    {
        $phone = I('phone', '');
        if ($phone) {
            $where['username'] = $phone;
        }
        $agentid                   = M('agentmember')->where(['id' => session('agent_user_id')])->getfield('agentid');
        $where['superior_agentid'] = $agentid;
        $data                      = D('Data')->get_all_data('agencyaudit', $where, 10, 'id desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['gamename'] = M()->table('userInfo')->where(['userid' => $v['userid']])->getField('name');
            $data['_data'][$k]['money']    = M()->table('userInfo')->where(['userid' => $v['userid']])->getField('money');
            $data['_data'][$k]['jewels']   = M()->table('userInfo')->where(['userid' => $v['userid']])->getField('jewels');
        }

        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    // 代理充值提取
    public function recharge()
    {
        $map = [];

        $start  = I('start');
        $stop   = I('stop');
        $search = (int) I('search');

        if ($start && $stop) {
            $start             = strtotime($start);
            $stop              = strtotime($stop);
            $map['actionTime'] = ['between', [$start, $stop]];
        }

        if ($search) {
            $map['userID'] = $search;
        }

        $my = M('Agentmember')->find(UID);
        $map['username'] = $my['username'];
        $mod   = M('adminaction');
        $count = $mod->where($map)->count();
        $Page  = new \Think\Page($count, 15);
        $show  = $Page->show();
        $list  = $mod->order('actionTime desc')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('recharge', M('adminaction')->where(['actionType' => 1])->sum('resourceNum'));
        $this->assign('pos', M('adminaction')->where(['actionType' => 2])->sum('resourceNum'));
        $this->display();
    }

    // 充值
    public function userRecharge()
    {
        if (IS_POST) {
            $userid = (int) I('userID', 0);
            $amount = (int) I('num', 0);
            $type   = I('type', 0);

            if (!$userid) {
                $this->error('无效的用户ID');
            }

            if ($amount < 1) {
                $this->error('充值数量最少大于等于1');
            }

            $redis = \GetRedis::get();
            if (false == $redis->connect) {
                $this->error('充值失败');
            }

            $res = $redis->redis->hgetall('userInfo|' . $userid);
            if (!$res) {
                $this->error('用户不存在');
            }

            //发送消息
            $socket = \Socket::get();
            $send   = new \SendFunction();
            if (false == $socket->connet) {
                $this->error('充值失败，原因：服务器连接失败');
            }
            $rechargePacket = $send->makeRechargePacket($userid, $amount, $type);
            $res            = $socket->send($send::RechargeID, 1, 0, $rechargePacket);
            if (!$res) {
                $this->error('充值失败 原因：向服务器发送请求失败');
            }
            $read = unpack('i*', $socket->read_data(1024));
            if (!$read) {
                $this->error('充值失败 原因：接收服务器消息失败');
            }
            if (0 != $read[4]) {
                $this->error('充值失败 原因:接收失败，服务器返回状态码为' . $read[4]);
            }
            if (1 == $type) {
                $goods = '金币';
            } elseif (2 == $type) {
                $goods = '钻石';
            }

            $my = M('Agentmember')->find(UID);

            $data = [
                'adminuser'    => $my['username'],
                'actionType'   => 1,
                'resourceType' => $type,
                'resourceNum'  => $amount,
                'actionTime'   => time(),
                'userID'       => $userid,
                'actionDesc'   => '代理用户:' . $my['username'] . '为玩家:' . $userid . '充值' . $goods . $amount,
            ];

            M('adminaction')->add($data);
            $this->success('充值成功');
        }

        $this->display();
    }

    // / 提取
    public function userPos()
    {
        if (IS_POST) {
            $userid = I('userID');
            $amount = I('num');
            $type   = I('type');

            if (!$userid) {
                $this->error('无效的用户ID');
            }

            if ($amount < 1) {
                $this->error('充值数量最少大于等于1');
            }

            $redis = \GetRedis::get();
            if (false == $redis->connect) {
                $this->error('充值失败');
            }

            $res = $redis->redis->hgetall('userInfo|' . $userid);
            if (!$res) {
                $this->error('用户不存在');
            }

            //发送消息
            $socket = \Socket::get();
            $send   = new \SendFunction();
            if (false == $socket->connet) {
                $this->error('提取失败，原因：服务连接不上');
            }
            $rechargePacket = $send->makeRechargePacket($userid, $amount, $type);
            $res            = $socket->send($send::PosID, 1, 0, $rechargePacket);
            if (!$res) {
                $this->error('提取失败 原因：向服务器发送请求失败');
            }
            $read = unpack('i*', $socket->read_data(1024));
            if (!$read) {
                $this->error('提取失败 原因：接收服务器消息失败');
            }
            if (0 != $read[4]) {
                $this->error('提取失败 原因:接收失败，服务器返回状态码为' . $read[4]);
            }
            if (1 == $type) {
                $goods = '金币';
            } elseif (2 == $type) {
                $goods = '钻石';
            }

            $my   = M('Agentmember')->find(UID);
            $data = [
                'adminuser'    => $my['username'],
                'actionType'   => 2,
                'resourceType' => $type,
                'resourceNum'  => $amount,
                'actionTime'   => time(),
                'userID'       => $userid,
                'actionDesc'   => '代理用户:' . $my['username'] . '为玩家:' . $userid . '提取' . $goods . $amount,
            ];

            M('adminaction')->add($data);
            $this->success('提取成功');
        }

        $this->display();
    }



/**二维码下载**/
       public function download()
    {
        $my = M('Agentmember')->find(UID);

        if (file_exists('./Uploads/qrcode/' . md5($my['userid']) . '.png')) {
            header("Content-type:application/octet-stream");
            header("Content-Disposition:attachment;filename =二维码.png");

            header("Accept-ranges:bytes");
            header("Accept-length:" . filesize('./Uploads/qrcode/' . md5($my['userid']) . '.png'));
            readfile('./Uploads/qrcode/' . md5($my['userid']) . '.png');
        } else {
            echo "<script>alert('下载失败或文件不存在');history.go(-1)</script>";
        }
    }



/*我的业绩排名*/
    public function my_ranking(){
    $my = M('Agentmember')->find(UID);  
	$member = M('Agentmember')->select();
    $type= I('time_type');

    if($type){
       if($type=='day'){
              //今天开始时间
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //今天结束时间
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

   }else if($type=='week'){
		$begin = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y"));
		$end  = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));

   }elseif($type=='month'){

	$begin  = mktime(0, 0, 0, date('m'), 1, date('Y'));

    $end  = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

   }
      $where['make_time'] = ['between',[$begin,$end]];
    }
 
    
 


	foreach ($member as $k => $v) {
		$where['username']  = $member[$k]['username'];
		$num = M('Billdetail')->where($where)->sum('handle_money');
		//echo M('Billdetail')->getlastsql();
		//echo "<hr/>";
		$member[$k]['num'] = sprintf("%.2f",((int)$num?$num:0)/100);

	}


		 $date = array_column($member, 'num');
		 array_multisort($date,SORT_DESC,$member);
		 //dump($member);
		$ranks=array();
		foreach ($member as $key => $value) {
			if($member[$key]['username']==$my['username']){
		       $ranks['sort'] = $key+1;
		       $ranks['num'] = $value['num'];
			}
		}


            $this->assign('ranks',$ranks);
            $this->display();
    }






    //业绩分析
    public function rank_analysis(){
        $my = M('Agentmember')->where(['id' => UID])->find();
        $begin = mktime(0,0,0,date('m'),date('d'),date('Y'));//今天开始时间戳
        for($i=0;$i<7;$i++){
                $begin = $begin - 24*3600;
                $week[] = $begin;
        }
        $i = 1;
        foreach($week as $k => $v){
                $end = $v + 24*3600;
                $num = M('Billdetail')->where(['make_time'=>['between',[$v,$end]],'username'=>$my['username']])->sum('handle_money');
                $data7[$i]['num'] = sprintf("%.2f",((int)$num?$num:0)/100);
                $data7[$i]['date'] = date("m-d",$v);
                $i++;
        }
        $data7 = array_reverse($data7);
        $this->assign('data7',$data7);


            //最近十二个月充值数据
            $year = date('Y',time());
            $month = date('m',time());
            $time12 = $this->getLastTimeArea($year,$month,10);
            $i = 1;
            foreach($time12 as $k=>$v){
                $num = M('Billdetail')->where(['make_time'=>['between',[$time12[$k]['begin2'],$time12[$k]['end2']]],'username'=>$my['username']])->sum('handle_money');
                $data12[$i]['num'] = sprintf("%.2f",((int)$num?$num:0)/100);
                $data12[$i]['date'] = $time12[$k]['month'];
                $i++;
            }

        $data12 = array_reverse($data12);
        $this->assign('data12',$data12);
        $this->display();
    }

/**
        * @param $year 给定的年份
        * @param $month 给定的月份
        * @param $legth 筛选的区间长度 取前六个月就输入6
        * @param int $page 分页
        * @return array
        */
    public function getLastTimeArea($year,$month,$legth,$page=1)
        {
            if (!$page) {
            $page = 1;
            }
            $monthNum = $month + $legth - $page*$legth;
            $num = 1;
            if ($monthNum < -12) {
            $num = ceil($monthNum/(-12));
            }
            $timeAreaList = [];
            for($i=0;$i<$legth;$i++) {
            $temMonth = $monthNum - $i;
            $temYear = $year;
            if ($temMonth <= 0) {
            $temYear = $year - $num;
            $temMonth = $temMonth + 12*$num;
            if ($temMonth <= 0) {
            $temMonth += 12;
            $temYear -= 1;
            }
            }
            $startMonth = strtotime($temYear.'/'.$temMonth.'/01');//该月的月初时间戳
            $endMonth = strtotime($temYear.'/'.($temMonth + 1).'/01') - 86400;//该月的月末时间戳
            $res['month'] = $temYear.'/'.$temMonth; //该月的月初格式化时间
            $res['begin2'] = $startMonth;
            $res['end2'] = $endMonth;
            $timeAreaList[] = $res;
            }
            return $timeAreaList;
    }


     public function paysearch()
     {

        $search = I('search');
        $start = I('start');
        $end   = I('end');

        if ($start && $end) {

            if ($start > $end) {
                $this->error('开始时间不能大于结束时间');
            }
            $where['make_time'] = ['between', [strtotime($start), strtotime($end)]];
        }

        if($search){
                $where['make_userid'] = $search;
        }



        //dump($where);
        $my = M('Agentmember')->where(['id' => UID])->find();
        $where['username'] = $my['username'];
        $data = D('Data')->get_all_data('Billdetail', $where, 12, 'make_time desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['front_balance']    = sprintf("%.2f", $data['_data'][$k]['front_balance'] / 100);
            $data['_data'][$k]['handle_money']     = sprintf("%.2f", $data['_data'][$k]['handle_money'] / 100);
            $data['_data'][$k]['after_balance']    = sprintf("%.2f", $data['_data'][$k]['after_balance'] / 100);
            $data['_data'][$k]['amount']           = sprintf("%.2f", $data['_data'][$k]['amount'] / 100);
            $data['_data'][$k]['commission']       = sprintf("%.2f", $data['_data'][$k]['commission'] / 100);
            $data['_data'][$k]['under_amount']     = sprintf("%.2f", $data['_data'][$k]['under_amount'] / 100);
            $data['_data'][$k]['under_commission'] = sprintf("%.2f", $data['_data'][$k]['under_commission'] / 100);
        }

        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);


        $this->display();
     }



    /*充值查询*/
    public function ordersearch(){
        $my = M('Agentmember')->where(['id' => UID])->find();
        $where['bind_username'] = $my['username'];

        $search =I('search');
        $start = I('start');
        $stop = I('stop');
        if($search){
         $where['recharge_userid'] = $search;
        }
        if($start && $stop){
        $start = strtotime($start);
        $stop = strtotime($stop);
        $where['recharge_time'] = ['between',[$start,$stop]];
        }

         $data = D('Data')->get_all_data('Rechargecommission',$where,20,'recharge_time desc');
// dump($where);
// die;


                 $arr1 = array();
        foreach($data['_data'] as $k => $v){
            $data['_data'][$k]['recharge_amount'] = sprintf("%.2f",$data['_data'][$k]['recharge_amount']/100);
            $data['_data'][$k]['bind_member_commission'] = sprintf("%.2f",$data['_data'][$k]['bind_member_commission']/100);
            $data['_data'][$k]['level2_member_commission'] = sprintf("%.2f",$data['_data'][$k]['level2_member_commission']/100);
            $data['_data'][$k]['level3_member_commission'] = sprintf("%.2f",$data['_data'][$k]['level3_member_commission']/100);
            $data['_data'][$k]['plat_income'] = $data['_data'][$k]['recharge_amount'] - ($data['_data'][$k]['bind_member_commission']+$data['_data'][$k]['level2_member_commission']+$data['_data'][$k]['level3_member_commission']);
            //$arr1[] = $data['_data'][$k]['bind_member_commission'];
        }

                //dump(array_sum($arr1));
              $this->assign("_data",$data['_data']);
              $this->assign('page',$data['_page']);
              $this->display();
    }





        /*我的会员会员消耗查询*/
    public function consume_usersearch()
    {
                if(!empty(I('userid'))){
                    $where['userID'] = I('userid');
                 }
                  if(!empty(I('username'))){
                    $where['username'] = I('username');
                 }
                $my               = M('Agentmember')->where(['id' => UID])->find();
                $where['agentID'] = $my['agentid'];

                $data             = D('Data')->get_all_data('Bindagentid', $where,20, 'bind_time desc');
                 //dump($data);
                //获取会员为我带来的收入以及他们充值的总数
                $user = $data['_data'];
                //print_r($user);die;
                foreach ($user as $k => $v) {
                    $map['recharge_userid'] = $user[$k]['userid'];
                    $map['bind_agentid']    = $user[$k]['agentid'];
                    $user[$k]['recharge']   = sprintf("%.2f", M('Rechargecommission')->where($map)->sum('recharge_amount') / 100);
                    //用户为我带来的收益
                    //获取所有的用户充值分佣记录
                    $c1            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'bind_username' => $my['username']])->sum('bind_member_commission') / 100);
                    $c2            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'level2_username' => $my['username']])->sum('level2_member_commission') / 100);
                    $c3            = sprintf("%.2f", M('Rechargecommission')->where(['recharge_userid' => $user[$k]['userid'], 'level3_member_username' => $my['username']])->sum('level3_member_commission') / 100);
                    $user[$k]['c'] = $c1 + $c2 + $c3;
                    $u             = M('Agentmember')->where(['userid' => $user[$k]['userid']])->find();
                    //dump($u);
                    if ($u) {
                        unset($user[$k]);
                    }
                }
                // dump($$user);die;
                // dump($user);
                $this->assign('_page', $data['_page']);
                $this->assign('_data', $user);

            $this->display();
    }


    public function consume_agentsearch()
    {
        $my = M('Agentmember')->where(['id'=>UID])->find();
            //获取我的手下二级代理
            $arr = []; 
            $x = M('Agentmember')->where(['superior_agentid'=>$my['agentid']])->select();
            if($x){
                foreach($x as $k => $v){
                    $x[$k]['type'] = 2;
                    $arr[] = $x[$k]; 
                    $z = M('Agentmember')->where(['superior_agentid'=>$x[$k]['agentid']])->select();
                    if($z){
                        foreach($z as $i => $a){
                            $z[$i]['type'] = 3;
                            $arr[] = $z[$i];
                        }
                    }
                } 
            }
            foreach($arr as $k => $v){
                //获取会员数 
                //下级代理数
                $arr[$k]['user_count'] = M('Bindagentid')->where(['agentID'=>$arr[$k]['agentid']])->count();
                $arr[$k]['agent_count'] = M('Agentmember')->where(['superior_agentid'=>$arr[$k]['agentid']])->count();
                $arr[$k]['balance'] = sprintf("%.2f",$arr[$k]['balance']/100);
                $arr[$k]['history_pos_money'] = sprintf("%.2f",$arr[$k]['history_pos_money']/100);

            }
        
        $this->assign('_data',$arr);
        $this->display();
    }

    /** 金币消耗**/
    public function money_consume()
    {
            $userID = I('userID');
            $where             = [];
            $where['S.userID'] = $userID;
            $where['S.changemoney']  = array('lt',0);
            $count = M()->table('statistics_moneyChange as S')->where($where)->count();
            $Page = new \Think\Page($count, 15);
            $res  = M()->table('statistics_moneyChange as S')
                 ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
                 ->where($where)
                 ->limit($Page->firstRow . ',' . $Page->listRows)
                 ->order('time desc')
                 ->field('S.time,S.money,S.changeMoney,S.reason,R.name as rname')
                 ->select();
            foreach ($res as $k => $v) {
                 if((int)$v['changemoney'] < 0){
                    $res[$k]['changemoney']=$v['changemoney'];
                 }else{
                    unset($res[$k]);
                 }
            }
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->display();
      
    }


    /**房卡消耗**/
    public function jewels_consume()
    {
             $userID = I('userID');
            $where             = [];
            $where['S.userID'] = $userID;
            $reason ='1,2,6,11,12,14';
            $where['S.reason'] =['in',$reason];
            $count             = M()->table('statistics_jewelsChange as S')
                ->where($where)
                ->count();
            $Page = new \Think\Page($count, 15);
            $res  = M()->table('statistics_jewelsChange as S')
                ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
                ->where($where)
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('time desc')
                ->field('S.time,S.jewels,S.changeJewels,S.reason,R.name as rname')
                ->select();
            foreach ($res as $k => $v) {
                if ($res[$k]['reason'] == 1 || $res[$k]['reason'] == 2 || $res[$k]['reason'] == 6 || $res[$k]['reason'] == 11 || $res[$k]['reason'] == 12 || $res[$k]['reason'] == 14) {
                    $res[$k]['changejewels'] = '-' . abs($res[$k]['changejewels']);
                }else{
                    unset($res[$k]);
                }       
            }
            $this->assign('_data', $res);
            $this->assign('_page', $Page->show());
            $this->display();
    }



        /**游戏二维码**/
    public  function inviteplayer()
    {

            $this->display();
    }








/**代理二维码**/
public function agent_inviteplayer(){
    $my = M('Agentmember')->find(UID);

    $invite='/Uploads/qrcode/' . md5($my['userid']) . '.png';
    $this->assign('invite',$invite);
    $this->display();
}



    /*举报/反馈*/
    public function question(){
       if(IS_POST){
                $img_url = session('product_img');
                if(!$img_url){
                    $this->error('请上传logo图片');
                }

               $data = I('post.');
               $data['img_url'] = $img_url;
               $data['q_userid'] = UID;
               $data['add_time']  = time();

                if(M('feedback')->add($data)){

                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
       }else{
        $my               = M('Agentmember')->where(['id' => UID])->find();
        $where['agentID'] = $my['agentid'];
        $data             = D('Data')->get_all_data('Bindagentid', $where,20, 'bind_time desc');
        $user = $data['_data'];

        $my = M('Agentmember')->where(['id'=>UID])->find();

        $agentCount  = M('bindagentid')->where(['agentID' => $my['agentid']])->count();
        $qrcodeCount = M('codeinvitation')->where(['agent_id' => $my['agentid']])->count();
        //获取我的手下二级代理
        $arr = []; 
        $x = M('Agentmember')->where(['superior_agentid'=>$my['agentid']])->select();
        if($x){
            foreach($x as $k => $v){
                $x[$k]['type'] = 2;
                $arr[] = $x[$k]; 
                $z = M('Agentmember')->where(['superior_agentid'=>$x[$k]['agentid']])->select();
                if($z){
                    foreach($z as $i => $a){
                        $z[$i]['type'] = 3;
                        $arr[] = $z[$i];
                    }
                }
            } 
        }




        $this->assign('arr',$arr);
        $this->assign('user',$user);
        $this->display();
       }
    }


    public function question_list(){
        $where['q_userid'] = UID;
        $data = D('Data')->get_all_data('feedback', $where,20, 'add_time desc');
    //dump($data);
        $res = $data['_data'];
        $this->assign('res',$res);
        $this->assign('page',$_page); 
        $this->display();
    }


    //图片的添加
    public function product_img_add(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     'home_img/'; // 设置附件上传（子）目录
        $upload->autoSub   =      false; // 设置附件上传（子）目录
                // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
                }
        $img_url = 'http://'.$_SERVER['HTTP_HOST'].'/Uploads/'.$upload->savePath.$info['file']['savename'];
        session('product_img',$img_url);
        $this->success('上传成功');
    }



    /*代理规则*/

    public function dlgz()
    {
        $this->display();
    }

    public function dlgz_c()
    {
        $this->display();
    }

    public function dlgz_help1()
    {
        $this->display();
    }

    public function dlgz_help2()
    {
        $this->display();
    }

    public function dlgz_help3()
    {
        $this->display();
    }

    public function dlgz_help4()
    {
        $this->display();
    }


    public function dlgz_help5()
    {
        $this->display();
    }


    
    #货币兑换
    public function CurrencyExchange(){
        #获取我的金币数
        $my = M('Agentmember')->find(UID);

        $userid = $my['userid'];

        $info = M()->table('userInfo')->where(['userid'=>$userid])->find();
     
        #获取我的钻石数
        $this->assign('money',$info['money']);
        $this->assign('jewels',$info['jewels']);
        $this->display();
    }
    

    #兑换订单
    public function exchange_order(){
        



        $this->display();
    }


     #金币/佣金提现
    public function pos(){
       

    $this->display();
    }




    #提现记录
    public function pos_record(){
        $start = I('start');
        $end   = I('end');

        if ($start && $end) {

            if ($start > $end) {
                $this->error('开始时间不能大于结束时间');
            }
            $where['make_time'] = ['between', [strtotime($start), strtotime($end)]];
        }

        if($search){
                $where['make_userid'] = $search;
        }


        $this->display();
    }


   
}
