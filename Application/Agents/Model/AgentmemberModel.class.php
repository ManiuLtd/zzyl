<?php
namespace Agents\Model;
use Think\Model;
//用户模型
class AgentmemberModel extends Model{
    //登录验证
    public function check_login($data){
        $data['password'] = md5($data['password']);
        $user = $this->where(array('username'=>$data['username']))->find();
        if(!$user){$this->error='用户名不存在';return false;}
        if($user['disabled'] == 1){$this->error='账号被禁用';return false;}
        //判断该用户组是否被禁用
            $group = M('Agentgroup')->find($user['agent_level']);
            if($group['disabled'] == 1){
                $this->error = '您所在的分组被禁止登录';   return false;
            }
        if($user['password'] != $data['password']){$this->error='密码错误';return false;}
        //修改用户登录次数 IP 时间
        M('Agentmember')->where(['id'=>$user['id']])->setInc('login_count');    //登陆次数+1
        if($data['openid'] != ''){
            $data['wx_token'] = md5('#ht.'.$data['openid'].'#/szhuomei.com#!');
        }
        $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();
        M('Agentmember')->where(['id'=>$user['id']])->save($data);
        session('agent_user_id',$user['id']);
        return true;
    }
}
