<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 17:56
 */
namespace Admin\Controller;

use config\EnumConfig;
use config\ErrorConfig;
use model\ServerModel;
use notify\CenterNotify;

class PersonalController extends AdminController
{
    //系统菜单管理
    public function index()
    {
        $data = D('Data')->get_all_data('collection_account', '', 15, 'id desc');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
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
        if(M($model)->where($map)->save(array('status'=>intval(I('disabled'))))){
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    //添加账户
    public function account_add()
    {
        if (IS_POST) {
            if(empty(I('account_name')) || empty(I('type'))) $this->error('参数不能为空');
            //分组名不能重复
            $group = M('collection_account')->where(array('account_name' => I('account_name')))->getField('id');
            if ($group) {
                $this->error('该收款账户已经存在，不可添加');
            }

            $data['account_name'] = I('account_name');
            $data['type'] = I('type');
            $data['create_time'] = time();
            $data['create_id'] = session('admin_user_info.id');
            if (M('collection_account')->add($data)) {
                operation_record(UID, '添加收款账户' . $data['account_name']);
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        } else {
            $this->display();
        }
    }

    //编辑账户
    public function account_edit()
    {
        if (IS_POST) {
            if(empty(I('account_name')) || empty(I('type'))) $this->error('参数不能为空');
            //分组名不能重复
            $group = M('collection_account')->where(array('account_name' => I('account_name')))->getField('id');
            if ($group) {
                $this->error('该收款账户已经存在，不能编辑为该账户名称');
            }

            $data['account_name'] = I('account_name');
            $data['type'] = I('type');
            $data['id'] = intval(I('id'));
            if (M('collection_account')->save($data)) {
                operation_record(UID, '编辑收款账户' . $data['account_name']);
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $group = M('collection_account')->find(I('get.id'));
            $this->assign('group', $group);
            $this->display();
        }
    }
}