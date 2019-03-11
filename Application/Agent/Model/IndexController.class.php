<?php
namespace Agent\Controller;

vendor('Common.Socket', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.GetRedis', '', '.class.php');
class IndexController extends Controller
{
    //我的信息
    public function index()
    {
        //获取我的信息
        $my                      = M('Agentmember')->find(UID);
        $my['balance']           = sprintf("%.2f", $my['balance'] / 100);
        $my['under_money']       = sprintf("%.2f", $my['under_money'] / 100);
        $my['not_under_money']   = sprintf("%.2f", $my['not_under_money'] / 100);
        $my['history_pos_money'] = sprintf("%.2f", $my['history_pos_money'] / 100);
        $this->assign('my', $my);
        $this->display();
    }


}