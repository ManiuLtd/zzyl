<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {
	/**
	 * 前台控制器初始化
	 */
	public function _initialize() {
		//获取栏目
		$menu = M('home_menu')->select();
		$linkurl = CONTROLLER_NAME.'/'.ACTION_NAME;
		$this->assign('linkurl',$linkurl);
		$this->assign('menu',$menu);
		//获取轮播图地址
		$banner = M('home_ad')->where(['type'=>1])->select();
		$this->assign('banner',$banner);
		//获取替他图片地址
		$i = M('home_ad')->where(['type'=>0])->select();
		$img = [];
		foreach($i as $k => $v){
			$img[$i[$k]['name']] = $i[$k]['img_url'];
		} 
		$this->assign('img',$img);
		//获取配置数据
		$c = M('home_config')->select();
		$config = [];
		foreach($c as $k => $v){
			$config[$c[$k]['key']] = $c[$k]['value'];
		} 
		$this->assign('config',$config);
	}
}