<?php
namespace Admin\Controller;

include './vendor/autoload.php';

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Video;
use EasyWeChat\Message\Voice;

class WechatController extends AdminController
{

    protected $app;

    public function _initialize()
    {
        parent::_initialize();
        $options = [
            'debug'   => true,
            'app_id'  => 'wxd8d438f534a0eda3',
            'secret'  => '1bb54942a9b3276a82cc0aa08eb15a4d',
            'token'   => 'huomei',
            'aes_key' => 'P70JzMKOr6SovsQohyHbx6OSVn5rp56UoDc08Uq0JU8', // 可选
            'log'     => [
                'level' => 'debug',
                'file'  => './Public/easywechat.log', // XXX: 绝对路径！！！！
            ],

            'oauth'   => [
                'scopes' => ['snsapi_base'],
                // 'callback' => 'http://wx.chenteng.net/Home/Wechat/callback',
            ],
            //...
        ];

        $this->app = new Application($options);
    }

    //微信管理
    public function index()
    {
        $menu = M('wechat_menu')->select();
        $menu = \Tree\Data::tree($menu, 'name', 'id', 'pid');

        $this->assign('_menu', $menu);
        $this->display();
    }

    // 菜单添加
    public function menu_add()
    {
        $this->assign('_menu', M('wechat_menu')->where(['pid' => 0])->select());
        $this->display();
    }

    // 菜单添加
    public function menu_update()
    {
        if (IS_POST) {
            $data = D('WechatMenu')->update();
            if (!$data) {
                $this->error(D('WechatMenu')->getError());
            } else {
                $this->success($data['id'] ? '更新成功' : '添加成功');
            }

        } else {
            $id = I('id', 0);
            $this->assign('data', M('wechat_menu')->find($id));
            $this->assign('_menu', M('wechat_menu')->where(['pid' => 0])->select());
            $this->display();
        }
    }

     // 菜单删除
    public function menu_del(){
        $id = I('id');
        $res = D('WechatMenu')->del($id);
        if($res){
            $this->success('删除成功');
        } else {
            $this->error($res->getError());
        }
    }
	 
   // 微信菜单更新
   public function updateWxMenu(){
	D('WechatMenu')->updateMenu();
	$this->success('更新成功');
   }


    // 关键字列表
    public function keywords()
    {
        $keys  = M('wechat_keywords');
        $count = $keys->where('status=1')->count();
        $Page  = new \Think\Page($count, 25);
        $show  = $Page->show();
        $list  = $keys->order('addtime')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    // 关键字添加
    public function keywordsAdd()
    {
        $this->assign('news', M('wechat_article')->select());
        $this->assign('image', M('wechat_material')->where(['type' => 'image'])->select());
        $this->assign('music', M('wechat_material')->where(['type' => 'music'])->select());
        $this->assign('video', M('wechat_material')->where(['type' => 'video'])->select());
        $this->display();
    }

    // 关键字 编辑 更新
    public function keywordsUpdate()
    {
        if (IS_POST) {
            $data = D('Keywords')->update();
            if (!$data) {
                $this->error(D('Keywords')->getError());
            } else {
                $this->success($data['id'] ? '更新成功' : '添加成功');
            }
        } else {
            $id = I('id', 0);
            $this->assign('news', M('wechat_article')->select());
            $this->assign('image', M('wechat_material')->where(['type' => 'image'])->select());
            $this->assign('music', M('wechat_material')->where(['type' => 'music'])->select());
            $this->assign('video', M('wechat_material')->where(['type' => 'video'])->select());
            $this->assign('data', M('wechat_keywords')->find($id));
            $this->display();
        }
    }

    // 关键字 删除
    public function keywordsDel()
    {
        $id  = I('id', 0);
        $res = M('wechat_keywords')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    // 关键字 禁用 启用
    public function KeywordsStatus()
    {
        $id     = I('id', 0);
        $status = M('wechat_keywords')->where(['id' => $id])->getfield('status');
        if ($status) {
            $res = M('wechat_keywords')->where(['id' => $id])->setfield('status', 0);
        } else {
            $res = M('wechat_keywords')->where(['id' => $id])->setfield('status', 1);
        }

        if ($res) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    // 关注默认回复
    public function subscribe()
    {
        if (IS_POST) {
            $s    = M('wechat_subscribe');
            $data = $s->create();
            if ($s->save()) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        } else {
            $this->assign('news', M('wechat_article')->select());
            $this->assign('image', M('wechat_material')->where(['type' => 'image'])->select());
            $this->assign('music', M('wechat_material')->where(['type' => 'music'])->select());
            $this->assign('video', M('wechat_material')->where(['type' => 'video'])->select());
            $this->assign('data', M('wechat_subscribe')->find());
            $this->display();
        }
    }

    // 用户管理
    public function user()
    {
        $User  = M('wechat_user');
        $count = $User->count();
        $Page  = new \Think\Page($count, 25);
        $show  = $Page->show();
        $list  = $User->order('subscribe_time')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    // 向用户发送消息
    public function toUser()
    {
        if (IS_POST) {
            $data = I('post.');
            switch ($data['type']) {
                case 'text':
                    if ($data['content'] == '') {
                        $this->error('请填写要发送的内容');
                    }
                    $message = new Text(['content' => $data['content']]);
                    $message->content = html_entity_decode($message->content);
		    $result  = $this->app->staff->message($message)->to($data['openid'])->send();
                    break;

                case 'image':
                    if ($data['image_url'] == '') {
                        $this->error('请上传要发送的图片');
                    }
                    $img    = new Image(['media_id' => $data['image_url']]);
                    $result = $this->app->staff->message($img)->to($data['openid'])->send();
                    break;

                case 'news':
                    $media_id  = I('media_id', '');
                    $news      = $this->app->material->get($media_id);
                    $dataArray = [];
                    foreach ($news['news_item'] as $v) {
                        $dataArray[] = new News([
                            'title'       => $v['title'],
                            'description' => $v['digest'],
                            'url'         => $v['url'],
                            'image'       => $v['thumb_url'],
                        ]);
                    }

                    $result = $this->app->staff->message($dataArray)->to($data['openid'])->send();
                    break;

                case 'music':
                    if ($data['music_url'] == '') {
                        $this->error('请发送要上传的音乐');
                    }
                    $music  = new Voice(['media_id' => $data['music_url']]);
                    $result = $this->app->staff->message($music)->to($data['openid'])->send();
                    break;

                case 'video':
                    if ($data['title'] == '') {
                        $this->error('请填写视频标题');
                    }
                    if ($data['video_url'] == '') {
                        $this->error('请先上传视频');
                    }
                    if ($data['video_desc'] == '') {
                        $this->error('请填写视频描述');
                    }
                    if ($data['video_thumb'] == '') {
                        $this->error('请上传视频缩略图');
                    }

                    $video = new Video([
                        'title'          => $data['title'],
                        'media_id'       => $data['video_url'],
                        'description'    => $data['video_desc'],
                        'thumb_media_id' => $data['video_thumb'],
                    ]);
                    $result = $this->app->staff->message($video)->to($data['openid'])->send();
                    break;
            }

            if ($result->errmsg == 'ok') {
                $this->success('发送成功');
            } else {
                $this->error('发送失败');
            }
        } else {
            $openid = I('openid');
            $this->assign('openid', $openid);
            $this->assign('news', M('wechat_article')->select());
            $this->display();
        }
    }

    // 拉黑用户
    public function batchblock()
    {
        $openid = I('openid');
        $result = $this->app->user->batchBlock([$openid]);
        if ($result->errmsg == 'ok') {
            M('wechat_user')->where(['openid' => $openid])->setfield('batchblock', 1);
            $this->success('操作成功');
        } else {
            $this->success('操作失败');
        }
    }

    // 取消拉黑
    public function batchunblock()
    {
        $openid = I('openid');
        $result = $this->app->user->batchUnblock([$openid]);
        if ($result->errmsg == 'ok') {
            M('wechat_user')->where(['openid' => $openid])->setfield('batchblock', 0);
            $this->success('操作成功');
        } else {
            $this->success('操作失败');
        }
    }

    /**
     * 素材管理
     * @param   type    接收类型
     */
    public function material()
    {
        $type = I('type', 'image');
        if ($type == 'news') {
            $map['group_id'] = 0;
            $map['type']     = $type;
            $User            = M('wechat_article');
            $count           = $User->where(['type' => $type])->count();
            $Page            = new \Think\Page($count, 25);
            $show            = $Page->show();
            $list            = $User->order('addtime')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $this->assign($type, $list);
            $this->assign('page', $show);
        } else {
            $map['type'] = $type;
            $User        = M('wechat_material');
            $count       = $User->where(['type' => $type])->count();
            $Page        = new \Think\Page($count, 25);
            $show        = $Page->show();
            $list        = $User->order('addtime')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $this->assign($type, $list);

            $this->assign('page', $show);
        }

        $this->display();
    }

    // 素材添加 图片
    public function imgAdd()
    {
        $this->display();
    }

    // 素材添加 语音
    public function musicAdd()
    {
        $this->display();
    }

    // 素材添加 视频
    public function videoAdd()
    {
        $this->display();
    }

    // 上传素材 并存入数据库
    public function materialAdd()
    {
        $material         = M('wechat_material');
        $data             = I('post.');
        $data['addtime']  = time();
        $data['media_id'] = $data['type'] == 'video' ? $this->materialUplaods(1, $data['type'], '.' . $data['local'], $data['title'], $data['video_desc']) : $this->materialUplaods(1, $data['type'], '.' . $data['local']);
        if ($material->data($data)->add()) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
    }

    // 群发
    public function sendMessage()
    {
        $data = I('post.');
        switch ($data['type']) {
            case 'image':
                $result = $this->app->broadcast->sendImage($data['mediaid']);
                break;

            case 'music':
                $result = $this->app->broadcast->sendVoice($data['mediaid']);
                break;

            case 'video':
                $result = $this->app->broadcast->sendVideo($data['mediaid']);
                break;

            case 'news':
                $result = $this->app->broadcast->sendNews($data['mediaid']);
                break;
        }

        if ($result->errmsg == 'ok') {
            $this->success('发送成功');
        } else {
            $this->error('发送失败');
        }
    }

    // 图文列表
    public function news()
    {
        $article = M('wechat_article');
        $count   = $article->count();
        $Page    = new \Think\Page($count, 15);
        $show    = $Page->show();
        $list    = $article->order('addtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    // 图文添加 更新
    public function newsAdd()
    {
        if (IS_POST) {

            $data = $this->toArray($_POST['data']);

            if (!$data) {
                $this->error('没有数据被提交');
            }

            $num = 1;
            foreach ($data as $k => $v) {
                if (empty($v['title'])) {
                    $this->error("第{$num}篇图文不能为空");
                }

                if (empty($v['thumb_media_id'])) {
                    $this->error("请上传第{$num}篇封面图片");
                }

                if (count($data) > 1) {

                    $mediaid = $this->app->material->uploadArticle($data);

                    if ($mediaid->media_id == '') {
                        $this->error = '添加图文失败';
                    }

                    if ($k == 0) {
                        $v['media_id'] = $mediaid->media_id;
                        $v['group_id'] = 0;
                        $v['addtime']  = time();
                        $v['multiple'] = 1;
                        $id            = M('wechat_article')->data($v)->add();
                    } else {
                        $v['media_id'] = $mediaid->media_id;
                        $v['group_id'] = $id;
                        $v['multiple'] = 1;
                        $v['addtime']  = time();
                        $res           = M('wechat_article')->data($v)->add();
                    }
                } else {
                    $mediaid = $this->app->material->uploadArticle($data);

                    if ($mediaid->media_id == '') {
                        $this->error = '添加图文失败';
                    }
                    $v['addtime']  = time();
                    $v['group_id'] = 0;
                    $v['multiple'] = 0;
                    $v['media_id'] = $mediaid->media_id;
                    $res           = M('wechat_article')->data($v)->add();
                }

                $num++;
            }

            if ($res) {
                $this->success('添加成功', U('material', array('type' => 'news')));
            } else {
                $this->error('添加失败');
            }

        } else {
            $this->display();
        }
    }

    // 图文更新
    public function newsUpdate()
    {
        if (IS_POST) {
            $data = $this->toArray($_POST['data']);

            if (!$data) {
                $this->error('没有数据被提交');
            }

            $num = 1;
            foreach ($data as $k => $v) {
                if (empty($v['title'])) {
                    $this->error("第{$num}篇图文不能为空");
                }

                if (empty($v['thumb_media_id'])) {
                    $this->error("请上传第{$num}篇封面图片");
                }

                if ($k == 0) {
                    $mediaId = M('wechat_article')->where(['id' => $v['id']])->getfield('media_id');
                }

                if (count($data) > 1) {
                    // 多图文
                    $result = $this->app->material->updateArticle($mediaId, new Article(
                        $v
                    ), $num);

                    if ($result->errmsg != 'ok') {
                        $this->error('第' . $num . '篇图文更新失败');
                    }

                    // 更新数据库
                    $res = M('wechat_article')->save($v);
                    if ($res) {
                        $this->success('更新成功', U('material', array('type' => 'news')));
                    } else {
                        $this->error('更新失败');
                    }

                } else {
                    $result = $this->app->material->updateArticle($mediaId, [
                        $v,
                    ]);

                    if ($result->errmsg != 'ok') {
                        $this->error('第' . $num . '篇图文更新失败');
                    }

                    // 更新数据库
                    $res = M('wechat_article')->save($v);
                    if ($res) {
                        $this->success('更新成功', U('material', array('type' => 'news')));
                    } else {
                        $this->error('更新失败');
                    }
                }

                $num++;
            }

        } else {
            $id     = I('id', 0);
            $data[] = M('wechat_article')->where(['id' => $id])->find();
            if ($data[0]['multiple']) {
                // 多图文
                $childrenData = M('wechat_article')->where(['group_id' => $data[0]['id']])->select();
                foreach ($childrenData as $v) {
                    $data[] = $v;
                }
            }

            $this->assign('tab', $data);
            $this->assign('data', $data);
            $this->assign('len', count($data));
            $this->display();
        }
    }

    // 图文删除
    public function newsDel()
    {
        $mediaid = I('mediaid');
        if ($mediaid == '') {
            $this->error('参数错误');
        }
        $result = $this->app->material->delete($mediaid);
        if ($result->errmsg == 'ok') {
            M('wechat_article')->where(['media_id' => $mediaid])->delete();
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    // 客服列表
    public function kf()
    {
        $this->assign('list', M('wechat_kf')->select());
        $this->display();
    }

    // 添加客服
    public function addKf()
    {
        $this->display();
    }

    // 更新
    public function updateKf()
    {
        if (IS_POST) {
            $data = I('post.');
            if($data['username'] == '' || $data['nickname'] == '' || $data['invite_wx'] == ''){
                $this->error('信息不完整,请重新输入');
            }
            if ($data['id']) {
                $userinfo = $this->app->staff->update($data['username'], $data['nickname']);
                $userImg  = $this->app->staff->avatar($data['username'], "." . $data['file']);
                if ($userinfo->errmsg == 'ok' && $userImg->errmsg == 'ok') {
                    M('wechat_kf')->save();
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            } else {
                // dump($data);die;
                $userinfo = $this->app->staff->create($data['username'], $data['nickname']);
                if ($userinfo->errmsg == 'ok') {
                    $userImg = $this->app->staff->avatar($data['username'], "." . $data['file']);
                    M('wechat_kf')->data($data)->add();
                    $this->success('添加成功');
                } else {
                    $this->error('添加失败');
                }

            }
        } else {
            $this->assign('data', M('wechat_kf')->find($id));
            $this->display();
        }
    }

    // 删除客服
    public function delKf()
    {
        $username = I('username');
        $res      = $this->app->staff->delete($username);
        if ($res->errmsg == 'ok') {
            M('wechat_kf')->where(['username' => $username])->delete();
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    // 发送绑定邀请
    public function invite_wx(){
        $username = I('username');
        $weixin = I('weixin');
        $res = $this->app->staff->invite($username, $weixin);
        if($res->errmsg == 'ok'){
            M('wechat_kf')->where(['username'=>$username, 'invite_wx'=>$weixin])->save(['invite_status'=>1]);
            $this->success('发邀请成功');
        } else {
            $this->error('发送邀请失败');
        }
    }

    /**
     * 素材上传
     * @param    $uploadType 素材类型 1 永久 0 临时
     * @param    $type       素材类型
     * @param    $path       素材路径
     * @param    $title      视频标题
     * @param    $desc       视频描述
     * @return   $media_id   上传成功的素材id
     */
    public function materialUplaods($uploadType = false, $type, $path, $title = '', $desc = '')
    {
        $upload = $uploadType ? $this->app->material : $this->app->material_temporary;
        switch ($type) {
            // 图片
            case 'image':
                $result   = $upload->uploadImage($path);
                $media_id = $result->media_id;
                break;

            // 缩略图
            case 'thumb':
                $result   = $upload->uploadThumb($path);
                $media_id = $result->thumb_media_id == '' ? $result->media_id : $result->thumb_media_id; // 临时 和 永久返回的不一样
                break;

            // 正文图片
            case 'articleimage':
                $result   = $this->app->material->uploadArticleImage($path);
                $media_id = $result->url;
                break;

            // 声音
            case 'music':
                $result   = $upload->uploadVoice($path);
                $media_id = $result->media_id;
                break;

            // 视频
            case 'video':
                $result   = $upload->uploadVideo($path, $title, $desc);
                $media_id = $result->media_id;
                break;
        }

        return $media_id;
    }

    //素材删除
    public function materialDel()
    {
        $mediaid = I('mediaid');
        if ($mediaid == '') {
            $this->error('参数错误');
        }
        $result = $this->app->material->delete($mediaid);
        if ($result->errmsg == 'ok') {
            M('wechat_material')->where(['media_id' => $mediaid])->delete();
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    // json 转数组
    public function toArray($data = [])
    {
        $data = json_decode($data, true);

        if (empty($data)) {
            return false;
        }

        $newArr = [];

        foreach ($data as $k => $v) {

            $arr = array();

            foreach ($data[$k] as $kk => $item) {
                // if ($kk > 8) {
                //     break;
                // }

                $arr[$item['name']] = $item['value'];
            }

            $newArr[] = $arr;
        }

        return $newArr;
    }

        // 意见反馈
    public function feedback()
    {
        $mod   = M('wechat_feedback');
        $count = $mod->count();
        $Page  = new \Think\Page($count, 25);
        $show  = $Page->show();
        $list  = $mod->order('addtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        foreach ($list as &$v) {
            $v['city'] = $this->getCity($v['add_ip']);
        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    // 归属地查询
    public function getCity($ip)
    {
        $ipData = S($ip);
        if (empty($ipData)) {
            $ipData = json_decode(file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip), true);
            S($ip, $ipData['province'] . $ipData['city']);
            return $ipData['province'] . $ipData['city'];
        } else {
            return $ipData;
        }

    }

    // 状态
    public function feedbackStatus()
    {
        $data['status'] = 1;
        $data['id']     = I('id');

        $res = M('wechat_feedback')->save($data);
        if ($res) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    // 文件上传
    public function uploads()
    {
        $upload           = new \Think\Upload(); // 实例化上传类
        $upload->maxSize  = 31457280000000000000000000000000000000000; // 设置附件上传大小
        $upload->exts     = array('jpg', 'gif', 'png', 'jpeg', 'mp3', 'mp4'); // 设置附件上传类型
        $upload->rootPath = './Uploads/Wechat/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录// 上传文件
        $uoload->autoSub  = false;
        $info             = $upload->upload();
        if (!$info) {
            // 上传错误提示错误信息
            echo json_encode(['status' => 0, 'msg' => '上传失败']);
        } else {
            // 上传成功 获取上传文件信息
            foreach ($info as $file) {
                echo json_encode(['status' => 1, 'msg' => '上传成功', 'data' => "http://" . $_SERVER['HTTP_HOST'] . "/Uploads/Wechat/" . $file['rootPath'] . $file['savename']]);
            }

        }
    }

    // 文件上传2
    public function uploads2($materialUplaods = false, $type = "", $t = 0)
    {
        $upload           = new \Think\Upload(); // 实例化上传类
        $upload->maxSize  = 31457280000000000000000000000000000000000; // 设置附件上传大小
        $upload->exts     = array('jpg', 'gif', 'png', 'jpeg', 'mp3', 'mp4'); // 设置附件上传类型
        $upload->rootPath = './Uploads/Wechat/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录// 上传文件
        $uoload->autoSub  = false;
        $info             = $upload->upload();
        if (!$info) {
            dump($upload->getError());die;
            // 上传错误提示错误信息
            echo json_encode(['status' => 0, 'msg' => '上传失败']);
        } else {
            // 上传成功 获取上传文件信息
            foreach ($info as $file) {
                if ($materialUplaods) {
                    $data = $this->materialUplaods($t, $type, "./Uploads/Wechat/" . $file['savename']);
                    echo json_encode(['status' => 1, 'msg' => '上传成功', 'data' => $data]);
                } else {
                    echo json_encode(['status' => 1, 'msg' => '上传成功', 'data' => "/Uploads/Wechat/" . $file['savename']]);
                }

            }

        }
    }

}
