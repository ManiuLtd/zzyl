<?php
namespace Home\Controller;

include './vendor/autoload.php';

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use Think\Controller;

class WechatRunController extends Controller
{

    protected $app;

    public function _initialize()
    {
        $options = [
            'debug'   => false,
            'app_id'  => 'wx0fd7ae64fc63bb91',
            'secret'  => 'cacba2a2b006667bd00eccd30494f4ec',
            'token'   => 'huomei',
            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
            //...
        ];

        $this->app = new Application($options);
        // $this->message();
    }
    // 处理上传二维码
    public function createQrcode()
    {
        $map['media_id']  = ['eq', ''];
        $map['subscribe'] = ['eq', 1];
        $data             = M('wechat_user')->where($map)->select();
        if ($data) {
            foreach ($data as $v) {
                if (empty($v['headimgurl_file'])) {
                    $this->createHeadUrl($v);
                    continue;
                }
                $img = $this->app->material->uploadImage($v['headimgurl_file']);
                //    dump($img);
                $res = M('wechat_user')->where(['id' => $v['id']])->save(['media_id' => $img->media_id]);
                if ($res) {
                    echo 'ok';
                }
            }
        }
    }

    // 重新处理
    public function createHeadUrl($user)
    {
        D('Home/Message')->download_file($user['headimgurl'], './Uploads/WechatHeader/' . $user['openid'] . '.png');
        if (file_exists('./Uploads/WechatHeader/' . $user['openid'] . '.png')) {
            $head    = './Uploads/WechatHeader/' . $user['openid'] . '.png';
            $userid  = M('share_code')->where(['unionid' => $user['unionid'], 'status' => 1])->getfield('userid');
            $url     = 'http://ht.szhuomei.com/Home/Wechat/share/userID/' . $userid . '/agentID/0';
            $imgfile = D('Home/Message')->scerweima1($url, $userid, './Uploads/qrcode/img.png', $head);
            M('wechat_user')->where(['openid' => $user['openid']])->save(['headimgurl_file' => $imgfile]);
            $img = $this->app->material->uploadImage($imgfile);
            $res = M('wechat_user')->where(['id' => $user['id']])->save(['media_id' => $img->media_id]);

        }
    }

    public function run()
    {
        $data = S('createImgList');
        if ($data) {
            foreach($data as $k=>$v){
                $user = M('wechat_user')->where(['openid'=>$v['openid']])->find();
                D('Home/Message')->download_file($user['headimgurl'], './Uploads/WechatHeader/' . $user['openid'] . '.png');
                if (file_exists('./Uploads/WechatHeader/' . $user['openid'] . '.png')) {
                    $head    = './Uploads/WechatHeader/' . $user['openid'] . '.png';
                    $url     = 'http://ht.szhuomei.com/Home/Wechat/share/userID/' . $v['userid'] . '/agentID/0';
                    $imgfile = D('Home/Message')->scerweima1($url, $v['userid'], './Uploads/qrcode/img.png', $head);
                    M('wechat_user')->where(['openid' => $user['openid']])->save(['headimgurl_file' => $imgfile]);
                    $img = $this->app->material->uploadImage($imgfile);
                    $res = M('wechat_user')->where(['id' => $user['id']])->save(['media_id' => $img->media_id]);
                    if($res){
                        $message = new Image(['media_id' => $img->media_id]);
			$result = $this->app->staff->message($message)->to($v['openid'])->send();
                        unset($data[$k]);
                        S('createImgList',$data);
                    }

                }
            }
        }
    }
}
