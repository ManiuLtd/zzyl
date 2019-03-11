<?php
namespace Agents\Controller;

use Think\Controller;

class AgentController extends Controller
{
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
        //获取当前位置
        $host    = $_SERVER['HTTP_HOST'];
        $linkurl = CONTROLLER_NAME . '/' . ACTION_NAME;
        $this->assign('linkurl', $linkurl);
        //获取菜单
        $agent = M('Agentmember')->find(UID);
        $agent['balance']           = sprintf("%.2f", $agent['balance'] / 100);
        //今日业绩



        $my = M('Agentmember')->find(UID);
        //今天开始时间
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //今天结束时间
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        $map['make_time'] = ['between',[$begin,$end]];
        $arr=array();
         $map['username'] =$my['username'];
                    $data = D('Data')->get_all_data('Billdetail',$map,'','make_time desc');
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
         $maps['username'] =$my['username'];
                    $data = D('Data')->get_all_data('Billdetail',$maps,'','make_time desc');
                     foreach($data['_data'] as $k => $v){
                            $data['_data'][$k]['handle_money'] = sprintf("%.2f",$data['_data'][$k]['handle_money']/100);

                             $marr[]=$data['_data'][$k]['handle_money'];
                        }
        if(empty($marr)){
          $mperformance = 0;
        }else{
          $mperformance=array_sum($marr);
        }



        $this->assign('my',$my);
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
            $menus         = M('Agentmenu')->where($where)->select();
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
        $user  = M('Agentmember')->find(UID);
        $group = M('Agentgroup')->find($user['agent_level']);
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
