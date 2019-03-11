<?php
namespace Agents\Controller;

include './vendor/autoload.php';
use EasyWeChat\Foundation\Application;
use Think\Controller;

/**公共操作控制器**/
class PublicController extends Controller
{

    public function _initialize()
    {
        $options = [
            'debug'   => false,
            'app_id'  => 'wx0fd7ae64fc63bb91',
            'secret'  => 'cacba2a2b006667bd00eccd30494f4ec',
            'token'   => 'huomei',
            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选

            'oauth'   => [
                'scopes'   => ['snsapi_base'],
                'callback' => 'http://ht.szhuomei.com/index.php/Agent/Public/wxlogin',
            ],
        ];

        $this->app = new Application($options);
    }

    // 微信登录
    public function wxlogin()
    {
        if(empty(session('wx_openid'))) {
            $user = $this->app->oauth->user();
            session('wx_openid',$user->id);
        }

        $wxToken = md5('#ht.'.session('wx_openid').'#/szhuomei.com#!');
        $find = M('agentmember')->where(['wx_token'=>$wxToken])->find();
	if($find){	
            session('agent_user_id',$find['id']);
            $this->redirect('Index/index');
        }

        $this->assign('openid', session('wx_openid'));
        $this->display();
    }


    //用户登录
    public function login($username = null, $password = null, $openid = '')
    {
        if (IS_POST) {
            $member = D('Agentmember');
            $data   = array('username' => $username, 'password' => $password,'openid'=>$openid);
            if ($member->check_login($data)) {
                $this->success('登录成功', U('Index/index'));
            } else {
                $this->error($member->getError());
            }
        } else {
         /*  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                $url = $this->app->oauth->redirect();
                $url->send();
            } else {*/
                $this->display();
           /* }*/

        }
    }
    //用户退出
    public function logout()
    {
        session('agent_user_id', null);
        $this->success('退出成功', U('login'));
    }
    //输出404页面
    public function _404()
    {
        $this->display();
    }
    //清除缓存
    public function rm_cache()
    {
        drop_dir(TEMP_PATH);
        drop_dir(CACHE_PATH);
        $this->success('缓存清除成功！');
    }
    //修改密码
    public function change_password()
    {
        if (!I('oldpassword') || !I('password') || !I('repassword')) {
            $this->error('请输入完整');
        }
        $id          = session('agent_user_id');
        $oldpassword = md5(I('oldpassword'));
        $agent       = M('Agentmember')->find($id);
        if ($oldpassword != $agent['password']) {
            $this->error('输入的当前密码不正确');
        }

        $password   = md5(trim(I('password')));
        $repassword = md5(trim(I('repassword')));
        if ($password != $repassword) {
            $this->error('两次输入密码不一致！请重新输入！');
        }

        if (strlen(I('password')) < 6) {
            $this->error('密码长度不能低于6位数');
        }

        if (M('Agentmember')->where(array('id' => $id))->save(array('password' => $password))) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

}
