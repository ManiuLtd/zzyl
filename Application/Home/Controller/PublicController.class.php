<?php
namespace Home\Controller;
use Think\Controller;

class PublicController  extends Controller{
    //首页
    public $config;
    public function _initialize()
    {
        $config = [];
        $c = M('home_config')->select();
        foreach($c as $k => $v){
            $config[$c[$k]['key']] = $c[$k]['value'];
        }
        $this->assign('config',$config);
    }

    public function privacy(){
        $this->display();
    }
    public function service(){
        $this->display();
    }
    public function test(){
        $this->display();
    }
    public function user(){
        $this->display();
    }
}
