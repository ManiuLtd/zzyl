<?php
namespace Admin\Model;
use EasyWeChat\Foundation\Application;
use Think\Model;
use Wechat\Wechat;

include  './vendor/autoload.php';

class WechatMenuModel extends Model{
	
    protected $app;
    public $tableName = 'admin_menu';
    public function _initialize(){
        parent::_initialize();
        $options = [
		'debug'   => true,
            	'app_id'  => 'wx0fd7ae64fc63bb91',
            	'secret'  => 'cacba2a2b006667bd00eccd30494f4ec',
            	'token'   => 'huomei',
            	'aes_key' => 'KonTCWjsdo4UGiLGCEnmIMClRZzfegzJx3kOqGSOfX0', // 可选
        ];
    
        $this->app = new Application($options); 
    }
     protected $_validate = array( 
         array('name','require','菜单名称不能为空！'),
         array('name','','菜单名称已经存在！',0,'unique',1),
    );


    // 更新 添加
    public function update(){
    	$data = $this->create();

    	if(empty($data)){
    		return false;
    	}


    	if(empty($data['id'])){

    		if($data['pid'] == 0){
                $cout = $this->where(['pid'=>$data['pid']])->count();
                if($count >= 3){
                    $this->error = '只能添加三个一级菜单';
                }
            } else {
                $cout = $this->where(['pid'=>$data['pid']])->count();
                if($count >= 5){
                    $this->error = '一个二级菜单只能添加5个子菜单';
            	}
            }

            $id = $this->add();

    		if(!$id) {
    			$this->error = '添加失败';
    			return false;
    		}
	
          // $this->updateMenu();

    	}else {

    		if($data['pid'] == 0){
                $cout = $this->where(['pid'=>$data['pid']])->count();
                if($count >= 3){
                    $this->error = '只能添加三个一级菜单';
                }
            } else {
                $cout = $this->where(['pid'=>$data['pid']])->count();
                if($count >= 5){
                    $this->error = '一个二级菜单只能添加5个子菜单';
            	}
            }



    		$res = $this->save();

    		if($res === false){
    			$this->error = '更新失败';
    			return false;
    		}
        //	$this->updateMenu();
    	}

    	return $data;
    }

    //菜单删除
    public function del($id){
        $children = $this->where(['pid'=>$id])->count();
        if($children){
            $this->error = '请先删除子类菜单';
            return false;
        }

        $res = $this->where(['id'=>$id])->delete();
        if($res){
           // $this->updateMenu();
	    return true;
        } else {
            $this->error = '删除失败';
        }
    }

    // 菜单更新
    public function updateMenu()
    {

        $parent = M('wechat_menu')->where(['pid' => 0])->select();
        $menu = [];
        foreach ($parent as $k => $v) {
            $menu[$k]['name'] = $v['name'];
            $child            = M('wechat_menu')->where(['pid' => $v['id']])->select();
            if ($child) {
                foreach ($child as $vv) {
                    $button         = [];
                    $button['name'] = $vv['name'];
                    $button['type'] = $vv['type'];

                    switch ($vv['type']) {
                        case 'view':
                            $button['url'] = $vv['url'];
                            break;

                        case 'click':
                            $button['key'] = $vv['key'];
                            break;

                        case 'view_limited':
                            $button['media_id'] = $vv['media_id'];
                            break;

                        case 'media_id':
                            $button['media_id'] = $vv['media_id'];
                            break;
                    }

                    $menu[$k]['sub_button'][] = $button;
                }
            } else {
                $menu[$k]['type'] = $v['type'];
                switch ($v['type']) {
                    case 'view':
                        $menu[$k]['url'] = $v['url'];
                        break;

                    case 'click':
                        $menu[$k]['key'] = $v['key'];
                        break;

                    case 'view_limited':
                        $menu[$k]['media_id'] = $vv['media_id'];
                        break;

                    case 'media_id':
                        $button['media_id'] = $vv['media_id'];
                        break;
                }
            }
        }

        // echo '<pre>';
        // print_r($menu);die;
        $this->app->menu->add($menu);
    }

}
