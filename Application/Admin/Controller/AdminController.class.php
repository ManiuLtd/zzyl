<?php
namespace Admin\Controller;

use Think\Controller;


class AdminController extends Controller
{
    const ARR_PAGE = [1 => ['word' => '15条', 'val' => 15, ], 2 => ['word' => '50条', 'val' => 50, ],  3 => ['word' => '150条', 'val' => 150, ], ];
    const PRE_CACHE_MENU = 'cache_menu_';//菜单缓存前缀
    protected $selfInfo = null;//管理员信息
    protected $menu = array();
    public $start;
    public $stop;

    /* 空操作，用于输出404页面 */
    public function _empty()
    {
        $this->display("Public/404");
    }
    protected function backXReferer() {
        $referer = base64_decode(I('x_referer'));
        if (empty($referer)) {
            return true;
        }
        $where   = [
            'link_url' => $referer,
        ];
        $menu_name = D('Data')->get_row_data('Admin_menu', $where);
        if ($menu_name) {
            $param = json_decode(base64_decode($_GET['x_param']), true);
//            var_export($param);exit;
            unset($param['x_param']);
            unset($param['x_token']);
            unset($param['x_referer']);
            $this->success('正在跳转至' . $menu_name['menu_name'], U($referer, $param));
            exit;
        }
    }
    /**
     * 后台控制器初始化
     */
    public function _initialize()
    {
//        var_export($_GET);
        $this->start = strtotime(date('Y-m-d', time()));
        $this->stop  = $this->start + 24 * 3600 - 1;
        
        // 获取当前用户ID
        define('UID',is_login());
        // define('UID', 1);

        if (!UID) {
            // 还没登录 跳转到登录页面
            $this->redirect('Public/login');
        }

        $linkurl = CONTROLLER_NAME . '/' . ACTION_NAME;

        $x_token = isset($_POST['x_token']) ? $_POST['x_token'] : isset($_GET['x_token']) ? $_GET['x_token'] : 0;
        if (!verifyToken($x_token)) {
            $this->assign('waitSecond','1');
            $this->error('token已失效,正在刷新token', U('index/index', ['x_referer' => base64_encode($linkurl), 'x_param' => base64_encode(json_encode($_GET))]));
        }
        $this->backXReferer();

        $this->selfInfo = getLoginInfo();
        /*if(session_id() != S(UID)){
        session('admin_user_id','');
        $this->error('您的账号在异地登录，被迫下线',U('Public/logout'));
        }*/
        //获取当前位置
        $host    = $_SERVER['HTTP_HOST'];
        $action  = ACTION_NAME;
        $where   = [
            'link_url' => $linkurl,
        ];
        $this->assign('_url', $linkurl);
        $menu_name = D('Data')->get_row_data('Admin_menu', $where);
//        dump($menu_name);
        $parent    = $menu_name['pid'];//M('Admin_menu')->where(['link_url' => $linkurl])->getfield('pid');
        //获取菜单
        $menu            = $this->getMenus();

        $GLOBALS['menu'] = $menu;
        $admin_user      = M('Admin_member')->find(UID);
        $uid             = UID;
        $this->assign('menu', $menu);
        $this->assign('uid', $uid);
        $this->assign('admin_user', $admin_user);
        $this->assign('parent', $parent);
        $this->assign('menu_name', $menu_name);
        //获取代理提现申请 反馈  实物兑换申请
        $apply_pos_new = M('agent_apply_pos')->where(['status' => 0])->count();
        //$feedback_new  = M('Adminfeedback')->where(['read_type' => ['neq', 3]])->count();
        $convert_new   = M('pay_orders')->where(['buyType' => 4, 'buyGoods' => ['neq', '房卡'], 'handle' => 0])->count();
        $this->assign('apply_pos_new', $apply_pos_new);
       // $this->assign('feedback_new', $feedback_new);
        $this->assign('convert_new', $convert_new);
        if (!$apply_pos_new &&  !$convert_new) {
            $news_type = 0;
        } else {
            $news_type = 1;
        }
        $this->assign('news_type', $news_type);
        $this->assign('waitSecond','1');
    }
    protected function makeCacheMenuKey() {
        return self::PRE_CACHE_MENU . $this->selfInfo['id'] . '_' . $this->selfInfo['group_id'] . '_' . strval($this->selfInfo['disabled']) . '_' . md5($this->selfInfo['rules']);
    }
    //获取菜单
    final public function getMenus()
    {
        $menuKey = $this->makeCacheMenuKey();
        $menus = S($menuKey);
        if (empty($menus)) {
            // 获取主菜单
            $where['pid']  = 0;
            $where['hide'] = 0;
            $menus         = M('Admin_menu')->where($where)->order('level')->select();
            
            foreach ($menus as $key => $val) {
                // 判断主菜单权限
                if (!$this->checkRule(intval($val['id']))) {
                    unset($menus[$key]);
                    continue; //继续循环
                }
                $map['pid']  = $val['id'];
                $map['hide'] = 0;
                $child       = M('Admin_menu')->order('level')->where($map)->select();
                foreach ($child as $k => $v) {
                    // 判断子菜单权限
                    if (!$this->checkRule(intval($v['id']))) {
                        unset($child[$k]);
                        continue; //继续循环
                    }

                    // 三级菜单
                    $map['pid']  = $v['id'];
                    $map['hide'] = 0;
                    $sub         = M('Admin_menu')->order('level')->where($map)->select();
                    // dump($sub);
                    foreach ($sub as $kk => $vv) {
                        // 判断子菜单权限
                        if (!$this->checkRule(intval($vv['id']))) {
                            unset($sub[$kk]);
                            continue; //继续循环
                        }
                    }

                    $child[$k]['_child'] = $sub;
                    if (!empty($sub)) {
                        $this->menu[] = $sub;
                    }

                }

                $menus[$key]['child'] = $child;
            }
            S($menuKey, $menus);
        }
        return $menus;
    }
    //检测权限
    public function checkRule($id)
    {
        if (UID == 1) {
            return true;
        }
        //获取当前用户的权限规则
        $user  = M('Admin_member')->find(UID);
        $group = M('Admin_group')->find($user['group_id']);
        $rules = $group['rules'];
        if (empty($rules)) {
            return false; //没有规则则没有任何权限
        }
        //如果是一个以上的权限规则就得拆分为数组
        if (strpos($rules, ',') !== false) {
            $arr = explode(',', $rules);
            //dump($arr);exit();
            if (in_array($id, $arr)) {
                return true; //用户规则存在则返回真
            } else {
                return false;
            }
        } else {
            //说明规则只有一个
            if ($id == $rules) {
                return true;
            } else {
                return false;
            }
        }
    }
}
