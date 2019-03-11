<?php
namespace Home\Model;

use EasyWeChat\Foundation\Application;
include './vendor/autoload.php';
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Voice;
use Qrcode\QRcode;
use Think\Model;

class MessageModel extends Model
{

    public function _initialize()
    {
        $options = [
            'debug'   => true,
            'app_id'  => 'wx0fd7ae64fc63bb91',
            'secret'  => 'cacba2a2b006667bd00eccd30494f4ec',
            'token'   => 'huomei',
            'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
        ];

        $this->app = new Application($options);
    }

    public function addUser($openid)
    {
        $user = $this->app->user->get($openid);
        // $media_id = $this->myQrcode($openid);
        $data = [
            'nickname'       => $user->nickname,
            'openid'         => $user->openid,
            'sex'            => $user->sex,
            'city'           => $user->city,
            'province'       => $user->province,
            'headimgurl'     => $user->headimgurl,
            'unionid'        => $user->unionid,
            'remark'         => $user->remark,
            'groupid'        => $user->groupid,
            'subscribe_time' => $user->subscribe_time,
            'subscribe'      => $user->subscribe,
            'media_id'       => '',
            // 'media_id_time'  => time(),
        ];

        $userInfo = M('wechat_user')->where(['openid' => $openid])->find();
        if (!$userInfo) {
            $res = M('wechat_user')->data($data)->add();
        } else {
            $data['id']          = $userInfo['id'];
            $data['update_time'] = time();
            $res                 = M('wechat_user')->save($data);
        }

    }

    // 更新状态
    public function updateUser($openid)
    {
        M('wechat_user')->where(['openid' => $openid])->save(['subscribe' => 0,'media_id'=>'','headimgurl_file'=>0]);
    }

    // 关键字回复
    public function keywords($str)
    {
        $map['keywords'] = ['like', "%{$str}%"];

        $data = M('wechat_keywords')->where($map)->find();
        if ($data) {
            switch ($data['type']) {
                case 'text':
                    $result          = new Text(['content' => $data['content']]);
                    $result->content = html_entity_decode($result->content);
                    break;

                case 'news':
                    $find = M('wechat_article')->where(['id' => $data['mid']])->find();
                    $news = $this->app->material->get($find['media_id']);
                    if (empty($news)) {
                        return false;
                    }

                    $dataArray = [];
                    foreach ($news['news_item'] as $v) {
                        $dataArray[] = new News([
                            'title'       => $v['title'],
                            'description' => $v['digest'],
                            'url'         => $v['url'],
                            'image'       => $v['thumb_url'],
                        ]);
                    }
                    $result = $dataArray;
                    break;

                case 'music':
                    $find   = M('wechat_material')->where(['id' => $data['mid']])->find();
                    $result = new Voice(['media_id' => $find['media_id']]);
                    break;

                case 'image':
                    $find   = M('wechat_material')->where(['id' => $data['mid']])->find();
                    $result = new Image(['media_id' => $find['media_id']]);
                    break;
            }

        } else {
            //$result = "亲，您好，请问有什么问题可以帮您？请直接描述问题哦~ ";
            //return new \EasyWeChat\Message\Transfer();
            $result = '';
        }

        return $result;
    }

    // 关注自动回复
    public function subscribe()
    {
        $data = M('wechat_subscribe')->where(['status' => 1])->find();
        if ($data['status']) {
            switch ($data['type']) {
                case 'text':
                    $result          = new Text(['content' => $data['content']]);
                    $result->content = html_entity_decode($result->content);
                    break;

                case 'news':
                    $find = M('wechat_article')->where(['id' => $data['news_mediaid']])->find();
                    $news = $this->app->material->get($find['media_id']);
                    if (empty($news)) {
                        return false;
                    }

                    $dataArray = [];
                    foreach ($news['news_item'] as $v) {
                        $dataArray[] = new News([
                            'title'       => $v['title'],
                            'description' => $v['digest'],
                            'url'         => $v['url'],
                            'image'       => $v['thumb_url'],
                        ]);
                    }
                    $result = $dataArray;
                    break;

                case 'music':
                    $find   = M('wechat_material')->where(['id' => $data['music_mediaid']])->find();
                    $result = new Voice(['media_id' => $find['media_id']]);
                    break;

                case 'image':
                    $find   = M('wechat_material')->where(['id' => $data['image_mediaid']])->find();
                    $result = new Image(['media_id' => $find['media_id']]);
                    break;
            }

        } else {
            $result = "感谢您的关注!";
        }

        return $result;
    }

    // 判断是否是代理 返回邀请码
    public function isAgent($openid)
    {
        $user   = $this->getUserInfo($openid);
        $userId = M('share_code')->where(['unionid' => $user['unionid']])->getfield('userid');
        $agent  = M('agentmember')->where(['userid' => $userId])->find();
        if ($agent) {
            $qrcode = $agent['media_id'];
            return ['status'=>1 ,'data'=>$qrcode];
        } else {
            $qrcode = D('Message')->myQrcode($openid);
            return $qrcode;
        }
    }

    // 生成二维码
    public function myQrcode($openid)
    {
        $user        = M('wechat_user')->where(['openid' => $openid])->find();
        $wechat_user = $this->getUserInfo($openid);
        // 是否存在 
        $userid = M('share_code')->where(['unionid' => $wechat_user['unionid']])->getfield('userid');
	    if (!$userid) {
            return ['status'=> 0, 'msg'=>'您还没有登陆过游戏'];
        }
        if ($user['media_id']) {
            $media_id = $user['media_id'];
            return ['status'=> 1, 'msg'=>'获取成功','data'=>$media_id];
        } else {
 /*           if (!$user['headimgurl_file']) {
                $this->download_file($user['headimgurl'], './Uploads/WechatHeader/' . $openid . '.png');
                $head    = './Uploads/WechatHeader/' . $openid . '.png';
                $url     = 'http://ht.szhuomei.com/Home/Wechat/share/userID/' . $userid . '/agentID/0';
                $imgfile = $this->scerweima1($url, $userid, './Uploads/qrcode/img.png', $head);
                M('wechat_user')->where(['openid' => $openid])->save(['headimgurl_file' => $imgfile]);
            } else {*/
		$this->handleCode($openid,$userid);
                return ['status'=> 2, 'msg'=>'正在生成请稍后...'];
          /*  }*/
        }
    }

    // 下载头像
    public function download_file($file_url, $save_to)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);

        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }

    // 生成二维码
    public function scerweima1($url = '', $id = '', $img = '', $head = '')
    {
        $value                = $url; //二维码内容
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize      = 7; //生成图片大小
        //生成二维码图片
        $filename = './Uploads/qrcode/'.$id.'_qrcode.png';
        QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $logo = './Uploads/qrcode/qrcode.png'; //准备好的logo图片
        $QR   = $filename; //已经生成的原始二维码图

        // 生成二维码
        if (file_exists($logo)) {
            $QR             = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
            $logo           = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
            $QR_width       = imagesx($QR); //二维码图片宽度
            $QR_height      = imagesy($QR); //二维码图片高度
            $logo_width     = imagesx($logo); //logo图片宽度
            $logo_height    = imagesy($logo); //logo图片高度
            $logo_qr_width  = $QR_width / 4; //组合之后logo的宽度(占二维码的1/5)
            $scale          = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
            $from_width     = ($QR_width - $logo_qr_width) / 2; //组合之后logo左上角所在坐标点

            //重新组合图片并调整大小
            /*
             *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        //输出图片
        $png = './Uploads/qrcode/'.$id.'share_qrcode.png';
        $res = imagepng($QR, $png);
        // imagedestroy($logo);

        // 合成二维码
        if ($res) {
            $logo = $png; //logo
            $QR   = $img; //底图
            if (file_exists($logo)) {
                $QR             = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
                $logo           = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
                $QR_width       = imagesx($QR); //二维码图片宽度
                $QR_height      = imagesy($QR); //二维码图片高度
                $logo_width     = imagesx($logo); //logo图片宽度
                $logo_height    = imagesy($logo); //logo图片高度
                $logo_qr_width  = 180; //组合之后logo的宽度(占二维码的1/5)
                $scale          = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
                $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
                $from_width     = 900; //组合之后logo左上角所在坐标点
                $right          = 400;
                // var_dump($from_width);die;
                //重新组合图片并调整大小
                /*
                 *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
                 */
                imagecopyresampled($QR, $logo, $right, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }

            //输出图片
            $res1 = imagepng($QR, './Uploads/qrcode/qrcode_img.png');
            // $res2 = imagedestroy($QR);
            if ($res1) {
                $logo = $head;
                $QR   = './Uploads/qrcode/qrcode_img.png';
                if (file_exists($logo)) {
                    $logo           = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
                    $QR             = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
                    $QR_width       = imagesx($QR); //二维码图片宽度
                    $QR_height      = imagesy($QR); //二维码图片高度
                    $logo_width     = imagesx($logo); //logo图片宽度
                    $logo_height    = imagesy($logo); //logo图片高度
                    $logo_qr_width  = 77; //组合之后logo的宽度(占二维码的1/5)
                    $scale          = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
                    $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
                    $from_width     = 538; //组合之后logo左上角所在坐标点
                    $right          = 100;
                    // var_dump($from_width);die;
                    //重新组合图片并调整大小
                    /*
                     *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
                     */
                    imagecopyresampled($QR, $logo, $right, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
                }

                $png = './Uploads/qrcode/' . md5($id) . '.png';
                $res = imagepng($QR, $png);

                if ($res) {
                    // $result = $this->app->material->uploadImage($png);
                    return $png;
                } else {
                    return "";
                }
            }

        }
    }

    // 代理二维码
    public function agentQrcode($id)
    {
        $data             = M('agentmember')->find($id);
        $result           = $this->app->material->uploadImage('./Uploads/qrcode/' . md5($data['userid']) . '.png');
        $data['media_id'] = $result->media_id;
        $res              = M('agentmember')->save($data);
       //($result->media_id);
	   return $res;
	//dump(md5($data['userid']));
    }


    //统计代理人数
    public function agentCount($openid)
    {
        $user = $this->getUserInfo($openid);
        if ($user) {
            $userid = M('share_code')->where(['unionid' => $user['unionid']])->getfield('userid');
            if ($userid) {
                $agent = M('agentmember')->where(['userid' => $userid])->find();
                if ($agent) {
                    // 代理
                    $money  = M('codeinvitation')->where(['type' => 1, 'userid' => $userid])->sum('money');
                    $count  = M('codeinvitation')->where(['type' => 1, 'agent_id' => $userid])->count();
                    $result = [(int) $count, (int) $money];
                } else {
                    // 普通用户
                    $money  = M('codeinvitation')->where(['type' => 0, 'userid' => $userid])->sum('money');
                    $count  = M('codeinvitation')->where(['type' => 0, 'agent_id' => $userid])->count();
                    $result = [(int) $count, (int) $money];
                }
            } else {
                $result = [0, 0];

            }
        } else {
            $result = [0, 0];
        }

        return $result;
    }

    // 发送末模板消息
    public function tplMessage($type, $openid, $user)
    {
        $url = 'http://ht.szhuomei.com/download/fx.php';
        switch ($type) {
            case 0: // 提交提醒
                $templateId = 'v4T53dCnm7nlHsQqzzK_MlLwZz5umopb3zQI6pp7J1Q';
                $data       = array(
                    "first"    => ["代理申请提交成功！", '#CC3333'],
                    "keyword1" => [$user['username'], '#5599FF'],
                    "keyword2" => [$user['user_id'], '#5599FF'],
                    "keyword3" => [date('Y-m-d H:i:s', time()), '#5599FF'],
                    "remark"   => ['你的代理申请已提交,请耐心等待客服审核！', "#5599FF"],
                );

                break;

            case 1: // 审核失败提醒
                $templateId = 'wuqzHRKQlkHIb36g6kzPdOg8TkugDlUN7hTIMdXOff4';
                $data       = array(
                    "first"    => ["非常抱歉您不符合申请条件", "#CC3333"],
                    "keyword1" => ["审核未通过", "#CC3333"],
                    "keyword2" => ["至尊娱乐棋牌代理申请", "#CC3333"],
                    "keyword3" => [date('Y-m-d H:i:s', $user['addtime']), "#CC3333"],
                    "keyword4" => [date('Y-m-d H:i:s', time()), "#CC3333"],
                    "remark"   => ["你的申请审核未通过！感谢您对至尊娱乐棋牌的支持,如果疑问可直接咨询公众号。", "#CC3333"],
                );
                break;

            case 2: // 审核成功提醒
                $templateId = 'wuqzHRKQlkHIb36g6kzPdOg8TkugDlUN7hTIMdXOff4';
                $data       = array(
                    "first"    => ["恭喜您成功为至尊娱乐棋牌代理", "#CC3333"],
                    "keyword1" => ["审核通过", "#CC3333"],
                    "keyword2" => ["至尊娱乐棋牌代理申请", "#CC3333"],
                    "keyword3" => [date('Y-m-d H:i:s', $user['addtime']), "#CC3333"],
                    "keyword4" => [date('Y-m-d H:i:s', time()), "#CC3333"],
                    "remark"   => ["你的申请审核已通过！客服将会在7个工作联系你到你,请保持电话的畅通,如果疑问可直接咨询公众号。", "#CC3333"],
                );
                break;
        }

        $result = $this->app->notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($openid)->send();
        return $result;
    }

    // 获取用户信息
    public function getUserInfo($openid)
    {
        $user = session($openid);
        if (!$user) {
            $userInfo = $this->app->user->get($openid);
            session($openid, $userInfo->toArray());
        }

        return session($openid);
    }

    //
    public function handleCode($openid = '', $userid='')
    {
        if($openid == '' || $userid == '') return false;
        $data = S('createImgList');

        if (empty($data)) {
            $user = [
                'openid' => $openid,
                'userid' => $userid,
            ];

            $data[]                    = $user;
            S('createImgList',$data);
        }

        foreach ($data as $v) {
            if ($v['openid'] != $openid) {
                $user = [
                    'openid' => $openid,
                    'userid' => $userid,
                ];

                $data[] = $user;

                S('createImgList',$data);
            }
        }

    }
}
