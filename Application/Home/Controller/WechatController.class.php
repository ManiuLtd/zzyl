<?php
namespace Home\Controller;

vendor('Common.GetRedis', '', '.class.php');
include './vendor/autoload.php';

use config\DynamicConfig;
use config\EnumConfig;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use helper\LogHelper;
use Think\Controller;
use Wechat\Wechat;

class WechatController extends Controller
{
//    const WEIXIN_DOMAIN = 'zzyl.szhmkeji.com';
    protected $app;
    protected $wechat; // 申请代理用
    protected $invite_userid; // 用户id
    protected $agentid; // 代理id
    protected $domain = '';

    public function _initialize()
    {
        $this->domain = !empty(C('AGENT_CODE_DOMIAN')) ? C('AGENT_CODE_DOMIAN') : $_SERVER['HTTP_HOST'];
//        parent::_initialize();

        // $this->message();
    }

    protected function getOauthApp($arrRedirect = []) {

//        $params = [
//            'debug'   => true,
//            'app_id'  => 'wx3a5aac7161b28013',
//            'secret'  => 'd4624c36b6795d1d99dcf0547af5443d',
//            'token'   => 'n1G5F101uhuMXw9sZD8dGgNS19YFsOgg',
////            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
//            'log'     => [
//                'level' => 'debug',
//                'file'  => APP_ROOT_PATH . '/../logs/Home_easywechat.log', // XXX: 绝对路径！！！！
//            ],
//
//            'oauth'   => [
//                'scopes'   => ['snsapi_userinfo'],
//                // 'callback' => 'http://' . $this->domain . '/Home/Wechat/callback',
//                'callback' => U('Home/Wechat/callback', $arrRedirect),
//            ],
//            //...
//        ];

        $params = [
            'debug'   => true,
            'app_id'  => DynamicConfig::WECHAT_APPID,
            'secret'  => DynamicConfig::WECHAT_APPSECREDIT,
            'token'   => DynamicConfig::WECHAT_TOKEN,
//            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
            'log'     => [
                'level' => 'debug',
                'file'  => APP_ROOT_PATH . '/../logs/Home_easywechat.log', // XXX: 绝对路径！！！！
            ],

            'oauth'   => [
                'scopes'   => ['snsapi_userinfo'],
                // 'callback' => 'http://' . $this->domain . '/Home/Wechat/callback',
                'callback' => U('Home/Wechat/callback', $arrRedirect),
            ],
            //...
        ];

        return new Application($params);
    }
    // public function index()
    // {
    //     exit();
    //     $user = $this->app->user;
    //     $this->app->server->setMessageHandler(function ($message) use ($user) {
    //         switch ($message->MsgType) {
    //             case 'event':
    //                 switch ($message->Event) {
    //                     case 'subscribe':
    //                         $result = D('Message')->subscribe();
    //                         $this->app->staff->message($result)->to($message->FromUserName)->send();
    //                         D('Message')->addUser($message->FromUserName);
    //                       //  D('Message')->myQrcode($message->FromUserName);
    //                         return '';
    //                         break;

    //                     case 'unsubscribe':
    //                         D('Message')->updateUser($message->FromUserName);
    //                         return "";
    //                         break;
    //                 }

    //                 // 点击事件
    //                 if ($message->Event == 'CLICK') {

    //                     // 自定义事件
    //                     switch ($message->EventKey) {
    //                         case 'MY_SHARE_CODE':

    //                             $qrcode = D('Message')->isAgent($message->FromUserName);
    //                             switch($qrcode['status']){
    //                                 case 0: // 没有登录过游戏
    //                                 $res = new Text(['content' => '你还没有登录过游戏']);
    //                                 break;

    //                                 case 1: // 返回二维码
    //                                 $res = new Image(['media_id' => $qrcode['data']]);
    //                                 break;

    //                                 case 2: // 正在生成
    //                                 $res = new Text(['content' => '正在生成,请稍后...']);
    //                                 break;
    //                             }

    //                             $this->app->staff->message($res)->to($message->FromUserName)->send();
    //                             return "";
    //                             break;

    //                         case 'My_Reward':
    //                             $count = D('Message')->agentCount($message->FromUserName);
    //                             $text  = new Text(['content' => '邀请注册' . $count[0] . '人，获得奖励' . (int) $count[1] . '金币']);
    //                             $this->app->staff->message($text)->to($message->FromUserName)->send();
    //                             break;

    //                         case 'KF':
    //                             $text = "hi，亲爱哒：客服的工作时间是：每日上午9:00-晚上24:00，请回复公众号咨询您需要问的问题，客服接入后会帮您解答的哦！（当前咨询人数较多，人工客服需逐一接入，请您耐心等待一会哦~）";
    //                             $this->app->staff->message($text)->to($message->FromUserName)->send();
    //                             return new \EasyWeChat\Message\Transfer();
    //                             break;
    //                     }
    //                 }

    //                 break;
    //             case 'text':
    //                 $str = trim($message->Content);

    //                 // 转入客服
    //                 if (strstr($str, "你好") || strstr($str, "您好") || strstr($str, "在吗") || strstr($str, "有人吗")) {
    //                     $text = "hi，亲爱哒：客服的工作时间是：每日上午9:00-晚上24:00，请回复公众号咨询您需要问的问题，客服接入后会帮您解答的哦！（当前咨询人数较多，人工客服需逐一接入，请您耐心等待一会哦~）";
    //                     $this->app->staff->message($text)->to($message->FromUserName)->send();
    //                     return new \EasyWeChat\Message\Transfer();
    //                 } else {
    //                     $data = D('Message')->keywords($str);
    //                     if ($data) {
    //                         $this->app->staff->message($text)->to($message->FromUserName)->send();
    //                     }
    //                     return new \EasyWeChat\Message\Transfer();
    //                 }
    //                 break;
    //             // ... 其它消息
    //             default:
    //                 return '';
    //                 break;
    //         }
    //         // ..
    //     });

    //     $response = $this->app->server->serve();
    //     $response->send();
    // }

    public function share()
    {
        $userid = I('userID', '');

        // session('userid', $userid);
        // session('agentid', I('agentID', ''));
        $this->invite_userid  = I('userID', '');
//        $this->agentid = I('agentID', '');
        $url           = $this->getOauthApp(['userID' => I('userID', 0)])->oauth->redirect();
        $url->send();
    }

    public function callback()
    {
//        $invite_userid  = session('userid');
        $invite_userid = I('userID');
//        $agentid = session('agentid');
        $time = date('YmdHis');
        LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
        $user    = $this->getOauthApp()->oauth->user();
        $agent   = M('agentMember')->where(['userid' => $invite_userid])->find();
        LogHelper::printDebug($time . 'agentBangDebug' . __LINE__ . M()->getLastSql());
        if (!$agent) {
            //邀请人不是代理
            LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
            $agentid = 0;
        }

        // 存入数据库
        date_default_timezone_set('PRC');
        $arr                  = $user->toArray();
//        $data['unionid']      = $arr['original']['unionid'];
//        $data['agent_userid'] = $invite_userid;
//        $data['agentid']      = $agentid;
//        $data['expiry_time']  = 7 * 24 * 60 * 60;
//        $data['login']        = 0;
        $data['unionid']      = $arr['original']['unionid'];
        $data['invite_userid'] = $invite_userid;
        $data['userid']      = 0;
        $data['status'] = EnumConfig::E_ShareCodeRewardStatus['NONE'];
        $data['time'] = time();

        // // 查询是否登录过游戏
        // $user = M('share_code')->where(['unionid' => $data['unionid']])->find();
        // // 自己邀请自己
        // if ($user['userid'] == $invite_userid) {
        //     header('location:http://' . $this->domain . '/download/fx.php');
        // }

        // 邀请人用户id 是否存在（是否存在邀请人）
        $invite_user = M()->table('userInfo')->where(['userID' => $invite_userid])->find();
        LogHelper::printDebug($time . 'agentBangDebug' . __LINE__ . M()->getLastSql());
        if (!$invite_user) {
            //不存在邀请人
            LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
            header('location:http://' . $this->domain . '/download/fx.php');
            exit;
        }

        // 代理id
        $isagentid = M('agentMember')->where(['userid' => $invite_userid])->find();
        LogHelper::printDebug($time . 'agentBangDebug' . __LINE__ . M()->getLastSql());
        if (true || $isagentid) {
            //邀请人是代理
            LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
            // 是否存在数据
            $dataExists = M('share_code')->where(['unionid' => $data['unionid']])->find();

            if (!$dataExists) {
                //不存在被邀请人的分享记录数据
                LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                M('share_code')->add($data);
                header('location:http://' . $this->domain . '/download/fx.php');
                exit;
            } else {
                LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                if (EnumConfig::E_ShareCodeRewardStatus['SEND'] == $dataExists['status']) {
                    LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                    // 绑定过
                    header('location:http://' . $this->domain . '/download/fx.php');
                    exit;
                } elseif (EnumConfig::E_ShareCodeRewardStatus['NONE'] == $dataExists['status'] || EnumConfig::E_ShareCodeRewardStatus['NOT'] == $dataExists['status']) {
                    LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                    // 更新
                    M('share_code')->where(['id' => $dataExists['id']])->save($data);
                    header('location:http://' . $this->domain . '/download/fx.php');
                    exit;
                }

            }

        } else {
            //邀请人不是代理
            LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);

            // 判断是否新用户
            if ($user) {
                LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                header('location:http://' . $this->domain . '/download/fx.php');
                exit;
            }

            $find = M('share_code')->where(['invite_userid' => $invite_userid])->find();
            if ($find) {
                //
                LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                M('share_code')->where(['id' => $find['id']])->save($data);
            } else {
                LogHelper::printDebug($time . 'agentBangDebug' . __LINE__);
                M('share_code')->add($data);
            }
            header('location:http://' . $this->domain . '/download/fx.php');
            exit;
        }

        header('location:http://' . $this->domain . '/download/fx.php');
        exit;
    }

    // 发送宣传海报
    public function image($openid)
    {
        $qrcode = D('Message')->myQrcode($openid);
        if ($qrcode) {
            $image = new Image(['media_id' => $qrcode]);
            $this->getOauthApp()->staff->message($image)->to($openid)->send();
        }
    }

    public function apply()
    {
        if (IS_POST) {
            $Agent = M('agent_apply');
            $data  = I('post.');
            $rules = array(
                array('username', 'require', '请输入您真实姓名！'),
                array('weixin', 'require', '请输入您的微信号！'),
                array('phone', 'require', '请输入您的手机号方便联系您！'),
                array('phone', '/^1[3|4|5|7|8][0-9]{9}$/', '手机格式不正确！'),
                array('user_id', 'require', '请输入游戏ID！'),
                array('username', 'require', '请输入真实姓名！'),
                array('confirm', array(1), '请勾选用户协议！', 1, 'in'),
            );

            if (!$Agent->validate($rules)->create()) {
                $this->error($Agent->getError());
            } else {
                $id = (int) $data['user_id'];
                if ($id == 0) {
                    $this->error('游戏ID必须是一个数字');
                }
                $userID = M()->table('userinfo')->where(['userID' => $data['user_id']])->find();
                if (!$userID) {
                    $this->error('游戏ID不存在,请重新输入');
                }

                if ($data['agent_id'] != '') {
                    $agent_id = M()->table('userinfo')->where(['userID' => $data['agent_id']])->find();
                    if (!$agent_id) {
                        $this->error('上级ID不存在请重新填写');
                    }
                }

                $Agent->addtime  = time();
                $Agent->apply_ip = get_client_ip();
		if($data['openid'] == ''){
			$this->error('提交出错!请稍后再试...');
		}

		$find = M('agent_apply')->where(['openid'=>$data['openid']])->find();
		if($find && $find['status'] == 0){
			$this->error('你已提交过代理申请,请耐心等待客服审核.');
		}
                if ($Agent->add()) {
                    D('Message')->tplMessage(0, $data['openid'], $data);
                    $this->success('提交成功');
                } else {
                    $this->error('提交失败');
                }
            }

        } else {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
                $this->error('非法访问');
            }

            if (!empty(session('openid'))) {
                $openid = session('openid');
            } else {
                $user   = $this->app->oauth->user();
                $openid = session('openid', $user->id);
            }

            $this->assign('openid', $openid);
            $this->display();
        }
    }

    public function applyOauth()
    {
        $config = [
            'debug'   => false,
            'app_id'  => 'wx0fd7ae64fc63bb91',
            'secret'  => 'cacba2a2b006667bd00eccd30494f4ec',
            'token'   => 'huomei',
            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
            'oauth'   => [
                'scopes'   => ['snsapi_base'],
                'callback' => 'http://' . $this->domain . '/Home/Wechat/apply',
            ],
        ];

        $this->wechat = new Application($config);
        $url          = $this->wechat->oauth->redirect();
        $url->send();
    }


   
    // 意见反馈
    public function feedback()
    {
        if (IS_POST) {
            $data['title']   = I('title', '');
            $data['content'] = I('content', '');

            if ($data['title'] == '') {
                $this->error('请输入反馈标题');
            }

            if ($data['content'] == '') {
                $this->error('请输入内容');
            }
	
  	    $start = strtotime(date('Y-m-d', time()));
            $stop  = $start + 24 * 3600 - 1;
            $ip    = get_client_ip();
            $count = (int) M('wechat_feedback')->where(['addtime' => ['between', [$start, $stop]], 'add_ip' => $ip])->count();
            if ($count > 4) {
                $this->error('今天反馈次数已达上线,请明天再试!');
            }

            $data['addtime'] = time();
            $data['add_ip']  = $ip;

            $res = M('wechat_feedback')->add($data);

            if ($res) {
                $this->success('感谢你的反馈!');
            } else {
                $this->error('提交失败!请稍后重试!');
            }

        }

        return $this->display();
    }

}
