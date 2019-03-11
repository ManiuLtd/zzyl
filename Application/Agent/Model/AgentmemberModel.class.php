<?php
namespace Agent\Model;
use Think\Model;

//用户模型
class AgentmemberModel extends Model{
    //登录验证
    public $tableName = 'agent_member';

    protected function setSession($userInfo) {
        session('agentLoginInfo.agent_user_id',$userInfo['id']);
        session('agentLoginInfo.login_time',time());
    }
    public function check_login($data){
        $data['password'] = md5($data['password']);
        $user = $this->where(array('username'=>$data['username']))->find();
        if(!$user){$this->error='用户名不存在';return false;}
        if($user['disabled'] == 1){$this->error='账号被禁用';return false;}
        //判断该用户组是否被禁用
            $group = M('agent_group')->find($user['agent_level']);
            if($group['disabled'] == 1){
                $this->error = '您所在的分组被禁止登录';   return false;
            }
        if($user['password'] != $data['password']){$this->error='密码错误';return false;}
        //修改用户登录次数 IP 时间
        M('agent_member')->where(['id'=>$user['id']])->setInc('login_count');    //登陆次数+1
        if($data['openid'] != ''){
            $data['wx_token'] = md5('#ht.'.$data['openid'].'#/szhuomei.com#!');
        }
        $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();
        M('agent_member')->where(['id'=>$user['id']])->save($data);
        $this->setSession($user);
        return true;
    }

    public function getAgentInfoById($id, $needFieldArray = ['*']) {
        $needField = implode(',', $needFieldArray);
        return $this->where(['id' => $id])->field($needField)->find();
    }

    /**
     * 获取代理业绩
     * @param $userID
     * @param $begin
     * @param $end
     * @return float|int
     */
    public function getAgentAchievementByUserId($userID, $begin = 0, $end = 0) {
        $map = [];
        if (!empty($begin) && !empty($end)) {
            $map['make_time'] = ['between', [$begin, $end]];
        }
        $arr = array();
        $map['userid'] = $userID;
        $data = D('Data')->get_all_data('bill_detail', $map, '', 'make_time desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['handle_money'] = sprintf("%.2f", $data['_data'][$k]['handle_money'] / 100);

            $arr[] = $data['_data'][$k]['handle_money'];
        }

        if (empty($arr)) {
            $performance = 0;
        } else {
            $performance = array_sum($arr);
        }
        return $performance;
    }

    /**
     *免密登录
     *
     */
    public function setLogin($data) {
        $user = $this->where(array('username'=>$data['username']))->find();
        if(!$user){$this->error='用户名不存在';return false;}
        if($user['disabled'] == 1){$this->error='账号被禁用';return false;}
        //判断该用户组是否被禁用
            $group = M('agent_group')->find($user['agent_level']);
            if($group['disabled'] == 1){
                $this->error = '您所在的分组被禁止登录';   return false;
            }
        //修改用户登录次数 IP 时间
        M('agent_member')->where(['id'=>$user['id']])->setInc('login_count');    //登陆次数+1
        $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();
        M('agent_member')->where(['id'=>$user['id']])->save($data);
        $this->setSession($user);
        return true;
    }

}
