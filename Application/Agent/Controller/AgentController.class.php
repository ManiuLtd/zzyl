<?php
namespace Agent\Controller;

use config\ErrorConfig;
use logic\AgentLogic;
use Think\Controller;

class AgentController extends Controller
{
    protected $agentInfoOfMime = null;
    /* 空操作，用于输出404页面 */
    public function _empty()
    {
        $this->display("Public/404");
    }
    /**
     * 后台控制器初始化
     */
    public function _initialize()
    {
        // 获取当前用户ID
        define('UID', is_login());
        if (!UID) {
            // 还没登录 跳转到登录页面
            $this->redirect('Public/login');
        }

        if (!verifyToken($_REQUEST['x_token'])) {
            $this->error('token已失效,正在刷新token', U('index/index'));
        }

        $disabled = D('Common/Agent')->isDisabled(UID);
        if ($disabled) {
            $this->error('该代理已被禁用');
        }
        //获取当前位置
        $host    = $_SERVER['HTTP_HOST'];
        $linkurl = CONTROLLER_NAME . '/' . ACTION_NAME;
        $this->assign('linkurl', $linkurl);
        //获取菜单
        $agent = M('agent_member')->find(UID);
        $this->agentInfoOfMime = $agent;
        define('userID', $agent['userid']);
        $agent['balance']           = sprintf("%.2f", $agent['balance'] / 100);
        //今日业绩

        $my = M('agent_member')->find(UID);
        //今天开始时间
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //今天结束时间
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        $map['make_time'] = ['between',[$begin,$end]];
        $arr=array();

         $map['userid'] =$my['userid'];

                    $data = D('Data')->get_all_data('bill_detail',$map,'','make_time desc');
                     foreach($data['_data'] as $k => $v){
                            $data['_data'][$k]['handle_money'] = sprintf("%.2f",$data['_data'][$k]['handle_money']/100);

                             $arr[]=$data['_data'][$k]['handle_money'];
                        }

        if(empty($arr)){
          $performance = 0;
        }else{
          $performance=array_sum($arr);
        }
        
        //本月开始时间
        $mbegin=mktime(0,0,0,date('m'),1,date('Y'));
         //本月结束时间
        $mend=mktime(23,59,59,date('m'),date('t'),date('Y'));

        $maps['make_time'] = ['between',[$mbegin,$mend]];

         $marr=array();
         $maps['userid'] =$my['userid'];

                    $data = D('Data')->get_all_data('bill_detail',$maps,'','make_time desc');

                     foreach($data['_data'] as $k => $v){
                            $data['_data'][$k]['handle_money'] = sprintf("%.2f",$data['_data'][$k]['handle_money']/100);
                             $marr[]=$data['_data'][$k]['handle_money'];
                        }

        if(empty($marr)){
          $mperformance = 0;
        }else{
          $mperformance=array_sum($marr);
        }
        
        $agent_notice = M('agent_config')->where(['key'=>'agent_notice'])->find()['value'];
        $this->assign('agent_notice',$agent_notice);
        $this->assign('performance',$performance);
        $this->assign('mperformance',$mperformance);

        $this->assign('agent', $agent);
        $menus = $this->getMenus();
        $this->assign('menus', $menus);
    }
    //获取菜单
    final public function getMenus()
    {
        if (empty($menus)) {
            $menus = array();
            //获取主菜单
            $where['hide'] = 0;
            $menus         = M('agent_menu')->where($where)->select();
            //判断主菜单权限
            foreach ($menus as $key => $value) {
                if (!$this->checkRule(intval($value['id']))) {
                    unset($menus[$key]);
                    continue; //继续循环
                }
            }
        }
        return $menus;
    }
    //检测权限
    public function checkRule($id)
    {
        //获取当前用户的权限规则
        $user  = M('agent_member')->find(UID);
        $group = M('agent_group')->find($user['agent_level']);
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

    public function edit() {
        if ($_POST) {
            $lowerUserID = I("lowerUserID");
            $commissionRate = trim(I("commissionRate"));

            $res = AgentLogic::getInstance()->updateLowerCommissionRate(userID,$lowerUserID, $commissionRate);
            if (ErrorConfig::ERROR_CODE === $res['code']) {
                $this->error('修改失败,' . $res['msg']);
            }
            $this->success('修改成功');
        }
    }

    //修改密码
    public function change_password()
    {
        if (!I('oldpassword') || !I('password') || !I('repassword')) {
            $this->error('请输入完整');
        }
        $id          = UID;
        $oldpassword = md5($_POST['oldpassword']);
        $agent       = M('agent_member')->find($id);
        if ($oldpassword != $agent['password']) {
            $this->error('输入的当前密码不正确');
        }

        $password   = md5($_POST['password']);
        $repassword = md5($_POST['repassword']);
        if ($password != $repassword) {
            $this->error('两次输入密码不一致！请重新输入！');
        }

        if (strlen($_POST['password']) < 6) {
            $this->error('密码长度不能低于6位数');
        }

        if (M('agent_member')->where(array('id' => $id))->save(array('password' => $password))) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }
}
