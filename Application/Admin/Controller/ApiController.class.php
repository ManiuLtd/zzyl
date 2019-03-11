<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 20:58
 */

namespace Admin\Controller;

use Think\Controller;
use Think\Upload;

class ApiController extends Controller
{
    //添加图片公共接接口
    public function upload_img(){
        $path = I('get.path');
        $path = $path?$path.'/':'';
        $upload = new Upload();
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     $path; // 设置附件上传（子）目录
        $info   =   $upload->upload();
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/Public/Uploads/'.$upload->savePath.$info['file']['savename'];
        if ($path=='goods/'){
            //修改商城图片
            $id = I('get.id');
           if (!empty($id)){
                $data= ['goods_img'=>$url,'id'=>$id];
              M('pay_goods')->save($data);
           }
        }
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            echo json_encode(['code'=>1,'msg'=>'上传成功','url'=>$url]);
        }
    }
}