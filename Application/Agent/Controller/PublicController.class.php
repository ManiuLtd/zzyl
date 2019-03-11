<?php
namespace Agent\Controller;

include './vendor/autoload.php';

use EasyWeChat\Foundation\Application;
use Think\Controller;

/**公共操作控制器**/
class PublicController extends Controller
{

    public function verifyToken() {
        $data = I();
        exit($data['echostr']);
        $timestamp = $data['timestamp'];
        $nonce = $data['nonce'];
        $token = "n1G5F101uhuMXw9sZD8dGgNS19YFsOgg";
        $signature = $data['signature'];
        $array = array($timestamp,$nonce,$token);
        sort($array);

//2.将排序后的三个参数拼接后用sha1加密
        $tmpstr = implode('',$array);
        $tmpstr = sha1($tmpstr);

//3. 将加密后的字符串与 signature 进行对比, 判断该请求是否来自微信
        if($tmpstr == $signature)
        {
            echo $data['echostr'];
            exit;
        }
    }

    public function _initialize()
    {
        $options = [
            'debug'   => true,
            'app_id'  => 'wx3a5aac7161b28013',
            'secret'  => 'd4624c36b6795d1d99dcf0547af5443d',
            'token'   => 'n1G5F101uhuMXw9sZD8dGgNS19YFsOgg',
//            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选

            'oauth'   => [
                'scopes'   => ['snsapi_base'],
                'callback' => 'http://47.105.147.29/index.php/Agent/Public/wxloginTest.html',
            ],
        ];

        $this->app = new Application($options);
    }
    public function wxloginTest() {
        echo 'login test';
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
        exit('只能从app自动登录');
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

    /**
     *免密登录
     */
    public function autoLogin() {
        $data = I('');
        if (false === $username = $this->verifySignExpired($data)) {
            $this->error('签名错误或过期');
        }

        if (D('Agentmember')->setLogin(['username' => $username])) {
                // $this->success('登录成功', U('Index/index'));
                header('location:' . U('Index/index'));
            } else {
                $this->error(D('Agentmember')->getError());
            }
    }

    protected function verifySignExpired($data) {
        $cipher = 'ECeRk3plOLPjhgfdBYHNUisaVTGJmvcxKz125o6uD8nb9QAZWSXF4ytr0w7q_MI';
        $plaintext = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-';
        $data['uuuu'] = urldecode($data['uuuu']);
        $strLen = strlen($data['uuuu']);
        $strDec = '';
        for ($i=0; $i < $strLen; $i++) { 
            $index = strpos($cipher, $data['uuuu'][$i]);
            // echo $cipher[$index];
            $strDec .= $plaintext[$index];
        }
        $data['uuuu'] = $strDec;
        $arrStr = explode('-', $data['uuuu']);
//        var_export($arrStr);exit;

        $str = $arrStr[0] . '-' . $arrStr[1];
        $sign = $arrStr[2];
        if ($arrStr[1] + 12 < time()) {
            return false;
        }

        if ($sign == md5($str . 'dfjire895u8t5fu85utjtiste3')) {
            return $arrStr[0];
        }
        return false;
    }

    public function testLogin() {
        $cipher =    'ECeRk3plOLPjhgfdBYHNUisaVTGJmvcxKz125o6uD8nb9QAZWSXF4ytr0w7q_MI';
        // echo $cipher[6];exit;
        $plaintext = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-';
        $time = time();
        $username = '15666565656';
        $newStr = $username . '-' . $time;
        $sign = md5($newStr . 'dfjire895u8t5fu85utjtiste3');
        $newStr .= '-' . $sign;
        $strLen = strlen($newStr);
        $strEnc = '';
        for ($i=0; $i < $strLen; $i++) {
            $index = strpos($plaintext, $newStr[$i]);
//            echo $index . "<br>";
            // echo $cipher[$index];
            $strEnc .= $cipher[$index];
        }
        $newStr = urlencode($strEnc);
//         echo $strEnc;exit;
        $url = 'http://huo.qq/agent.php/public/autologin.html?uuuu=' . $newStr;// . '&sign=' . $sign;
//         exit($url);
        header('location:' . $url);

    }

}
