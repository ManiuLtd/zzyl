<?php
namespace Admin\Controller;

use config\EnumConfig;
use config\MysqlConfig;

class HomeController extends AdminController {
    //网站参数配置
    public function config(){
    	$data = D('Data')->get_all_data('home_config','',15,'');
        $this->assign('_page',$data['_page']);
        $this->assign('_data',$data['_data']);
        $this->display();
    }

    public function config_edit(){
    	$data['id'] = I('id');
        $data['value'] = I('value');
        $data['desc'] = I('desc');
        if(!$data['desc']){   
            $this->error('描述必须填写');
        }
        if(M('home_config')->save($data)){
	    operation_record(UID,'编辑了网站前台参数');
            $this->success('编辑成功');
        }else{
            $this->error('编辑失败');
        }
    }

    //图片位
    public function img(){
    	//获取所有的图片配置
       $data = D('Data')->get_all_data('home_ad','',10,'id desc');
       $this->assign('_page',$data['_page']);
       $this->assign('_data',$data['_data']);
       $this->display();
    }


    //重新上传
    public function img_upload(){
    	 if(IS_POST){
    	 	$id = I('id');
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
            $file_url = 'http://'.$_SERVER['HTTP_HOST'].'/Uploads/'.$upload->savePath.$info['file']['savename'];
            //插入数据库
            $data = ['img_url'=>$file_url,'id'=>$id];
            if(M('home_ad')->save($data)){
		operation_record(UID,'重新上传了网站图片');
                $this->success('上传成功');
            }else{
                $this->error('上传失败');
            }
        }else{
        	$id = I('id');
        	$this->assign('id',$id);
            $this->display();
        }
    }

    //产品
    public function product(){
        $type = (int)I('type');
        $where = [];
        if (!empty($type)) {
            $where['type'] = $type;
        }
    	$data = D('Data')->get_all_data('home_product',$where,10,'id desc');
    	array_walk($data['_data'], function(&$v, $k) {
    	    $v['typeName'] = EnumConfig::E_WebHomeProductTeypName[$v['type']];
        });
        $this->assign('_page',$data['_page']);
        $this->assign('_data',$data['_data']);
        $this->assign([
            'type' => $this->getProductType(),
        ]);
        $this->display();
    }

    //产品编辑
    public function product_edit(){
        if(IS_POST){
            $data = [
                'id'    => (int)I('id'),
                'name' =>  trim(I('name')),
                'type' => (int)I('type'),
                'desc'=>  trim(I('desc')),
                'link_url'  =>  trim(I('link_url')),
            ];
            $this->verifyProduct($data);
            if(M('home_product')->save($data)){
		operation_record(UID,'编辑了前台产品信息');
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $id = I('id');
            $product = M('home_product')->find($id);
            $this->assign('product',$product);
            $this->assign(['type' => $this->getProductType()]);
            $this->display();
        }
    }


    //产品图片上传
    public function product_img(){
    		$id = I('id');
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
            $data = array('id'=>$id,'img_url'=>$img_url);
            if(M('home_product')->save($data)){
            	$this->success('上传成功');
            }else{
            	$this->error('上传失败');
            }
    }

    //产品图片的添加
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

    public function del_product() {
//        if ($_POST) {
            $id = (int)I('id');
            $res = M()->table(MysqlConfig::Table_web_home_product)->where(['id' => $id])->delete();
            if (!$res) {
                $this->error('删除失败');
            }
            $this->success('删除成功');
//        }
    }

    //产品添加
    public function add_product(){
    	if(IS_POST){
    		$img_url = session('product_img');
    		if(!$img_url){
    			$this->error('请上传logo图片');
    		}
    		$data = [
                'name' =>  trim(I('name')),
                'type' => (int)I('type'),
                'desc'=>  trim(I('desc')),
                'link_url'  =>  trim(I('link_url')),
                'img_url' => trim($img_url),
                'create_time' => time(),
            ];
    		$this->verifyProduct($data);
            if(M('home_product')->add($data)){
		operation_record(UID,'添加了前台产品');
            	$this->success('添加成功');
            }else{
            	$this->error('添加失败');
            }
    	}else{
//            var_export($type);
    	    $this->assign([
    	        'type' => $this->getProductType(),
            ]);
    		$this->display();
    	}
    }

    protected function verifyProduct($data) {
        if(empty($data['name'])){
            $this->error('请填写名称');
        }
        if(empty($data['type'])){
            $this->error('请选择类型');
        }
        if(empty($data['desc'])){
            $this->error('请填写描述');
        }
        if ($data['type'] == EnumConfig::E_WebHomeProductTeyp['DOWNLOAD'] && empty($data['link_url'])) {
            $this->error('下载地址不能为空');
        }
        if(empty($data['name']) || empty($data['desc']) || empty($data['type'])){
            $this->error('请填写完整');
        }
    }
    protected function getProductType() {
        $type = [];
        foreach (EnumConfig::E_WebHomeProductTeyp as $k => $v) {
            $type[] = [
                'type' => $v,
                'typeName' => EnumConfig::E_WebHomeProductTeypName[$v],
            ];
        }
        return $type;
    }

    //新闻管理
    public function news(){
       $data = D('Data')->get_all_data('home_news','',10,'id desc');
       $this->assign('_page',$data['_page']);
       $this->assign('_data',$data['_data']);
       $this->display();
    }

    //新闻删除
    public function news_del(){
    	$id = I('id');
    	if(M('home_news')->delete($id)){
    		$this->success('删除成功');
    	}else{
    		$this->error('删除失败');
    	}
    }


    //新闻编辑
    public function news_edit(){
    	if(IS_POST){
            $data = [
                'id'    => I('id'),
                'title' =>  I('title'),
                'author'=>  I('author'),
                'type'  =>  I('type'),
                'content'=> html_entity_decode(I('editorValue')),
            ];
            if(empty($data['title']) || empty($data['author']) || empty($data['content'])){
                $this->error('请填写完整');
            }
            if(M('home_news')->save($data)){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $id = I('id');
            $news = M('home_news')->find($id);
            $this->assign('news',$news);
            $this->display();
        }
    }

    public function news_img(){
    		$id = I('id');
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
            $data = array('id'=>$id,'img_url'=>$img_url);
            if(M('home_news')->save($data)){
            	$this->success('上传成功');
            }else{
            	$this->error('上传失败');
            }
    }



    //产品图片的添加
    public function news_img_add(){
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
            session('news_img',$img_url);
            $this->success('上传成功');
    }

    //产品添加
    public function add_news(){
    	if(IS_POST){
    		$img_url = session('news_img');
    		if(!$img_url){
    			$this->error('请上传logo图片');
    		}
    		$data = [
                'title' =>  I('title'),
                'author'=>  I('author'),
                'type'  =>  I('type'),
                'create_time'  =>  time(),
                'content'=> html_entity_decode(I('editorValue')),
            ];
            if(empty($data['title']) || empty($data['author']) || empty($data['content'])){
                $this->error('请填写完整');
            }
            if(M('home_news')->add($data)){
            	$this->success('添加成功');
            }else{
            	$this->error('添加失败');
            }
    	}else{
    		$this->display();
    	}
    }

    //留言记录
    public function feedback(){

    }
}
