<?php
namespace Admin\Controller;

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use model\ServerModel;
use notify\CenterNotify;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.GetRedis', '', '.class.php');

class AdminsystemController extends AdminController
{
    public function _initialize() {
        parent::_initialize();
        if (in_array(ACTION_NAME, ['menu_add', 'menu_edit', 'menu_rm']) && $_POST) {
            S($this->makeCacheMenuKey(), null);
        }
    }

    //删除菜单
    public function menu_rm(){
        if ($_POST) {
            if (D('admin_menu')->where(['id' => I('id')])->delete()) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败,');
            }
        }
    }
    //系统菜单管理
    public function menu_config()
    {
        $typeWhere = I("isHide", -1);
        $arrWhere = [];
        $arrTypeWhere = [
            'onlyShow' => ['value' => 0, 'key' => '显示菜单'],
            'onlyHide' => ['value' => 1, 'key' => '隐藏菜单'],

        ];
        switch ($typeWhere) {
            case $arrTypeWhere['onlyShow']['value']:
                $arrWhere['hide'] = 0;
                break;
            case $arrTypeWhere['onlyHide']['value']:
                $arrWhere['hide'] = 1;
                break;
            default:
        }
        $data = D('Data')->get_all_data('admin_menu', $arrWhere, 15, 'pid asc,level asc');
        //         echo '<pre>';
        // print_r($data['_data']);die;
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //菜单添加
    public function menu_add()
    {
        if (IS_POST) {
            if (D('admin_menu')->create()) {
                if (D('admin_menu')->add()) {
                    operation_record(UID, '添加了菜单' . I('menu_name'));
                    $this->success('添加成功');
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error(D('admin_menu')->getError());
            }
        } else {
            //获取所有顶级菜单
            $where = [
                'pid' => 0,
            ];
            $data = D('Data')->get_all_data('admin_menu', $where);
            $menu = M('admin_menu')->where(['hide' => 0])->select();
            $tree = \Tree\Data::tree($menu, 'menu_name', 'id', 'pid');
            // echo '<pre>';
            // print_r($tree);die;
            $this->assign('data', $tree);
            $this->display();
        }
    }

    //菜单编辑
    public function menu_edit()
    {
        if (IS_POST) {
            if (D('admin_menu')->create()) {
                if (D('admin_menu')->save()) {
                    operation_record(UID, '编辑了菜单');
                    $this->success('编辑成功');
                } else {
                    $this->error('编辑失败');
                }
            } else {
                $this->error(D('admin_menu')->getError());
            }
        } else {
            //获取之前的数据
            $where = [
                'id' => I('id'),
            ];
            $now = D('Data')->get_row_data('admin_menu', $where);
            //获取所有的顶级菜单
            $data = D('Data')->get_all_data('admin_menu', array('pid' => 0));
            $this->assign('now', $now);
            $menu = M('admin_menu')->where(['hide' => 0])->select();
            $tree = \Tree\Data::tree($menu, 'menu_name', 'id', 'pid');
            $this->assign('data', $tree);
            $this->display();
        }
    }

    //用户组
    public function user_group()
    {
        $data = D('Data')->get_all_data('admin_group', '', 15, 'id desc');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //添加分组
    public function group_add()
    {
        if (IS_POST) {
            //分组名不能重复
            $group = M('admin_group')->where(array('group_name' => I('group_name')))->select();
            if ($group) {
                $this->error('该分组名已经存在，不可使用');
            }
            $rules = I('rules');
            if (isset($rules)) {
                $rules = (array)I('rules');
                sort($rules);
                $str = '';
                foreach ($rules as $k => $v) {
                    $str .= $v . ',';
                }
                $rules = rtrim($str, ',');
                $data['rules'] = $rules;
            }
            $data['group_name'] = I('group_name');
            $data['disabled'] = intval(I('disabled'));
            $data['desc'] = I('desc');
            if (M('admin_group')->add($data)) {
                operation_record(UID, '添加用户组' . $data['group_name']);
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        } else {
            $this->assign('menu', $GLOBALS['menu']);
            $this->display();
        }
    }

    //编辑分组
    public function group_edit()
    {
        if (IS_POST) {
            $rules = I('rules');
            if (isset($rules)) {
                $rules = (array)I('rules');
                sort($rules);
                $str = '';
                foreach ($rules as $k => $v) {
                    $str .= $v . ',';
                }
                $rules = rtrim($str, ',');
                $data['rules'] = $rules;
            }
            $data['group_name'] = I('group_name');
            $data['disabled'] = intval(I('disabled'));
            $data['id'] = intval(I('id'));
            if (M('admin_group')->save($data)) {
                operation_record(UID, '编辑后台用户分组' . $data['group_name']);
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $group = M('admin_group')->find(I('get.id'));
            $str = $group['rules'];
            $rules = array();
            $rules = explode(',', $str);
            $rules = json_encode($rules);
            $this->assign('rules', $rules);
            $this->assign('group', $group);
            $this->display();
        }
    }

    //用户分组删除
    public function group_del()
    {
        if (D('admin_group')->data_del()) {
            operation_record(UID, '删除后台用户分组');
            $this->success('删除成功');
        } else {
            $this->error('删除失败,' . D('admin_group')->getError());
        }
    }

    //管理用户列表
    public function member_list()
    {
        $where['id'] = ['neq', 1];
        $data = D('Data')->get_all_data('admin_member', $where, 15, '');
        $member = $data['_data'];
        foreach ($member as $k => $v) {
            $group_name = M('admin_group')->where(array('id' => $member[$k]['group_id']))->getField('group_name');
            $member[$k]['group_name'] = $group_name;
        }

        $this->assign('_data', $member);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //禁用与解禁
    public function disabled()
    {
        $id = array_unique((array)I('id', 0));
        if (empty($id) || empty($id[0])) {
            $this->error('请选择要操作的数据');
        }
        $map = array('id' => array('in', $id));
        if (M('admin_member')->where($map)->save(array('disabled' => intval(I('disabled'))))) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    //删除用户
    public function user_del()
    {
        $id = I('id');
        if (M('admin_member')->delete($id)) {
            $this->success('删除成功');
        } else {
            $this->display('删除失败');
        }
    }

    //添加用户
    public function user_add()
    {
        if (IS_POST) {
            if ($data = D('admin_member')->create()) {
                $data['register_time'] = time();
                if (D('admin_member')->add($data)) {
                    $this->success('添加成功');
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error('添加失败，' . D('admin_member')->getError());
            }
        } else {
            //获取分组
            $group = M('admin_group')->select();
            $this->assign('group', $group);
            $this->display();
        }
    }

    //用户编辑
    public function user_edit()
    {
        if (IS_POST) {
            $where = [
                'username' => trim(I('username')),
                'id' => ['neq', I('id')],
            ];
            $member = M('admin_member')->where($where)->find();
            if ($member) {
                $this->error('用户名已经存在');
            }
            if (I('password')) {
                if (trim(I('password')) != trim(I('repassword'))) {
                    $this->error('两次输入密码不一致');
                }

                $_POST['password'] = md5(I('password'));
            } else {
                unset($_POST['password']);
            }
            if (M('admin_member')->save($_POST)) {
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败');
            }
        } else {
            $id = I('id');
            //获取用户信息
            $member = M('admin_member')->find($id);
            //获取分组
            $group = M('admin_group')->select();
            $this->assign('member', $member);
            $this->assign('group', $group);
            $this->display();
        }
    }

    //操作日志
    public function operation()
    {
        $type = I('type');
        $search = I('search');

        $where = [];
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['l._time'] = ['between', [$start, $stop]];
        // } else {
        //     $start = strtotime(date('Y-m-d', time()));
        //     $stop = $start + 24 * 3600 - 1;
        //     $where['l._time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['l._time'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['m.username'] = ['like', "%{$search}%"];
                    break;
                case 2:
                    $where['l._action'] = ['like', "%{$search}%"];
                    break;
            }

        }else{
            $search = '';
        }
        $count = M('admin_log as l')
            ->join('left join web_admin_member as m on m.id=l.uid')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 15);

        $res = M('admin_log as l')
            ->join('left join web_admin_member as m on m.id=l.uid')
            ->where($where)
            ->field('l.*,m.username')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('l._time desc')
            ->select();

        $this->assign('_page', $Page->show());
        $this->assign('_data', $res);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('search', $search);
        $this->assign('type', $type);
        $this->display();
    }

    //清除所有操作日志
    public function operation_del()
    {
        if (M('admin_log')->where(1)->delete()) {
            $this->success('清除成功');
        } else {
            $this->error('清除失败' . M('admin_log')->getError());
        }
    }

    //平台状态管理
    public function plat_status()
    {
        $cur_server_status = M('server_info')->where(['server_id' => ServerModel::SERVER_ID])->getfield('server_status');
        if (IS_POST) {
            $status = I('status');
            $server_status = EnumConfig::E_ServerStatus['NORMAL'];
            if ($status == 'none') {
                $this->error('请选择服务器状态');
            } elseif ($status == 'test') {
                $server_status = EnumConfig::E_ServerStatus['TEST'];
            } elseif ($status == 'stop') {
                $server_status = EnumConfig::E_ServerStatus['STOP'];
            } elseif ($status == 'normal') {
                $server_status = EnumConfig::E_ServerStatus['NORMAL'];
            }
            if (false !== M('server_info')->where(['server_id' => ServerModel::SERVER_ID])->setfield('server_status', $server_status)) {
                //通知中心服
                if ($server_status == EnumConfig::E_ServerStatus['NORMAL']) {
                    CenterNotify::openServer();
                } else {
                    CenterNotify::closeServer($server_status);
                }
                $this->saveServerStatusToTxt($status);
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            if ($cur_server_status == EnumConfig::E_ServerStatus['TEST']) {
                $status = 'test';
            } elseif ($cur_server_status == EnumConfig::E_ServerStatus['STOP']) {
                $status = 'stop';
            } elseif ($cur_server_status == EnumConfig::E_ServerStatus['NORMAL']) {
                $status = 'normal';
            }
            $this->assign('status', $status);
            $this->display();
        }
    }

    //添加白名单
    public function white()
    {
        if (IS_POST) {
            $ip = I('loginIP');
            $res = ServerModel::getInstance()->addServerWhite($ip);
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        } else {
            $this->display();
        }
    }

    // 白名单列表
    public function whitelist()
    {
        $serverWhiteList = ServerModel::getInstance()->getServerWhiteList();
        $ipList = array_column($serverWhiteList, 'ip');
        $this->assign('data', $ipList);
        $this->display();
    }

    // 白名单删除
    public function white_del()
    {
        $ip = I('id');
        $res = ServerModel::getInstance()->delServerWhite($ip);
        if ($res !== false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function saveServerStatusToTxt($status)
    {
        unlink('/usr/local/nginx/html/timedTask/server_status.txt');
        file_put_contents('/usr/local/nginx/html/timedTask/server_status.txt', $status);
    }
}
