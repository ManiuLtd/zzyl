<?php
namespace Admin\Controller;
use Think\Controller;

/**公共操作控制器**/
class PublicController extends Controller {
    //用户登录
    public function login($username=null,$password=null){
        if(IS_POST){
            $member = D('Adminmember');
            $data = array('username'=>$username,'password'=>$password);
            if($member->check_login($data)){
		operation_record(session('admin_user_id'),'登录了管理后台');
                $this->success('登录成功',U('Index/index'));
            }else{
                $this->error($member->getError());
            }
        }else{
            $this->display();
        }
    }
    //用户退出
    public function logout(){
        session('admin_user_id',null);
        $this->success('退出成功',U('login'));
    }
    //输出404页面
    public function _404(){
        $this->display();
    }
    //清除缓存
    public function rm_cache(){
        drop_dir(TEMP_PATH);
        drop_dir(CACHE_PATH);
        $this->success('缓存清除成功！');
    }
   //修改密码
    public function change_password(){
            if(!I('oldpassword') || !I('password') || !I('repassword')){
                $this->error('请输入完整');
            }
            $id = session('admin_user_id');
            $oldpassword = md5(I('oldpassword'));
            $agent = M('admin_member')->find($id);
            if($oldpassword != $agent['password'])      $this->error('输入的当前密码不正确');
            $password = md5(trim(I('password')));
            $repassword = md5(trim(I('repassword')));
            if($password != $repassword)    $this->error('两次输入密码不一致！请重新输入！');
            if(strlen(I('password')) < 6)   $this->error('密码长度不能低于6位数');
            if(M('admin_member')->where(array('id'=>$id))->save(array('password'=>$password))){
                operation_record(session('admin_user_id'),'修改了密码');
		$this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
    }
    //公共删除
    public function del_record(){
        exit('非法操作');
        $model = I('model');
        if(D('Data')->data_del($model)){
            $this->success('删除成功');
        }else{
            $this->error('删除失败,'.D('Data')->getError());
        }
    }



    //公共禁用与解禁
    public function disabled(){
//        var_export($_SERVER);
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            exit('需要ajax操作');
        }
        $id = array_unique((array)I('id',0));
        if ( empty($id) || empty($id[0]) ) {
            $this->error('请选择要操作的数据');
        }
        $map = array('id' => array('in', $id));
        $model = I('model');
        if(M($model)->where($map)->save(array('disabled'=>intval(I('disabled'))))){
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 判断是否有代理兑换未审核的订单
     * */
    public function isExchangeAudit(){
        $where = [];
        // 查询提现中
        $where['U.cash_status'] =1;
        $id = M()->table('user_cash_application as U')->where(['cash_status' => 1])->getField('Id');
        echo $id;
    }

    

}
