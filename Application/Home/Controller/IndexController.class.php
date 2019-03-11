<?php
namespace Home\Controller;

use config\EnumConfig;

vendor('Common.GetRedis', '', '.class.php');
class IndexController extends HomeController {
    //首页
    public function index(){
        if (C('CANNOT_ACCESS_INDEX_PAGE')) {
            header("HTTP/1.1 404 Not Found");exit;
        }
    	//获取两个产品
        $product = M('home_product')->limit(2)->select();
    	//获取用户排行榜
        $ranking = M()->table('userInfo')->limit(10)->order('winCount desc')->where(['isVirtual'=>0])->select();
        $this->assign('product',$product);
        $this->assign('ranking',$ranking);
    	$this->display();   
    }

    //下载中心
    public function download(){
    	//获取所有产品
        $product = M('home_product')->where(['type' => EnumConfig::E_WebHomeProductTeyp['DOWNLOAD']])->select();
//        var_export(M()->getLastSql());
        $this->assign('product',$product);
    	$this->display();
    }

    //新闻中心
    public function news(){
    	$type = I('type',0);
    	$where['type'] = $type;
    	//根据类型获取新闻数据
        $news = M('home_news')->where($where)->select();
        $this->assign('news',$news);
    	$this->display();
    }

    //联系我们
    public function lianxi(){
    	//获取数据
    	$this->display();
    }

    //在线留言
    public function online_msg(){
    	$name = trim(I('name'));
    	$phone = trim(I('phone'));
    	$msg = trim(I('content'));
    	//插入数据库
    	if(!$name){$this->error('请输入您的姓名');}
    	if(!$phone){$this->error('请输入您的联系方式');}
    	if(!$msg){$this->error('请输入留言内容');}
        $data = [
            'name'  =>  $name,
            'phone' =>  $phone,
            'content'   =>  $msg,
            'create_time'   =>  time(),
        ];
        if(M('home_feedback')->add($data)){
    	    $this->success('留言成功');
        }else{
            $this->error('留言失败');
        }
    }

    public function test(){
     $redis = \GetRedis::get();
        if (!$redis || $redis->connect == false) {
            $this->error('redis连接失败');
        }

        $user   = $redis->redis->keys('userInfo|*');
	foreach($user as $v){
	   $find = $redis->redis->hgetall($v);
	   var_dump($find);die;
        }
   }
}
