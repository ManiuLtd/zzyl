<?php
namespace Admin\Controller;
use config\EnumConfig;
use config\ErrorConfig;
use config\GeneralConfig;
use config\MysqlConfig;
use helper\FunctionHelper;

vendor('Common.GetRedis', '', '.class.php');

class OperateController extends AdminController
{
    /**
     * @param $year 给定的年份
     * @param $month 给定的月份
     * @param $legth 筛选的区间长度 取前六个月就输入6
     * @param int $page 分页
     * @return array
     */
    public function getLastTimeArea($year, $month, $legth, $page = 1)
    {
        if (!$page) {
            $page = 1;
        }
        $monthNum = $month + $legth - $page * $legth;
        $num = 1;
        if ($monthNum < -12) {
            $num = ceil($monthNum / (-12));
        }
        $timeAreaList = [];
        for ($i = 0; $i < $legth; $i++) {
            $temMonth = $monthNum - $i;
            $temYear = $year;
            if ($temMonth <= 0) {
                $temYear = $year - $num;
                $temMonth = $temMonth + 12 * $num;
                if ($temMonth <= 0) {
                    $temMonth += 12;
                    $temYear -= 1;
                }
            }
            $startMonth = strtotime($temYear . '/' . $temMonth . '/01'); //该月的月初时间戳
            //    $endMonth = strtotime($temYear.'/'.($temMonth + 1).'/01') - 86400;//该月的月末时间戳
            if ($temMonth == '12') {
                $endMonth = strtotime($temYear . '/12/31'); //该月的月末时间戳
            } else {
                $endMonth = strtotime($temYear . '/' . ($temMonth + 1) . '/01') - 86400; //该月的月末时间戳
            }

            $res['month'] = $temYear . '/' . $temMonth; //该月的月初格式化时间
            $res['begin2'] = $startMonth;
            $res['end2'] = $endMonth;
            $timeAreaList[] = $res;
        }
        return $timeAreaList;
    }

    //抽奖统计
    public function turntable_count()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('turntable_record')->where(['prizeType' => 1, 'turntableTime' => ['between', [$v, $end]]])->sum('num');
            $jewels = M('turntable_record')->where(['prizeType' => 2, 'turntableTime' => ['between', [$v, $end]]])->sum('num');
            $data7[$i]['money'] = (int)$money ? $money : 0;
            $data7[$i]['jewels'] = (int)$jewels ? $jewels : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        //dump($data7);exit();
        $this->assign('data7', $data7);
        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $money = M('turntable_record')->where(['prizeType' => 1, 'turntableTime' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('num');
            $jewels = M('turntable_record')->where(['prizeType' => 2, 'turntableTime' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('num');
            $data12[$i]['money'] = (int)$money ? $money : 0;
            $data12[$i]['jewels'] = (int)$jewels ? $jewels : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //抽奖数据
        $turntable_count = M('turntable_record')->count();
        $all_money = M('turntable_record')->where(['prizeType' => 1])->sum('num');
        $all_jewels = M('turntable_record')->where(['prizeType' => 2])->sum('num');
        $max = M()->query('select count(*) as c,userID from web_turntable_record as a group by a.userID');
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => 'c', //排序字段
        );
        $arrSort = array();
        foreach ($max as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $max);
        }
        $max = $max[0];
        //获取用户获得最大金币数
        $sql = "select max(m.num) as max_num from (select sum(num) as num from web_turntable_record where prizeType=1 group by userID) as m";
        $user_max_money = M()->query($sql);
        $user_max_money = $user_max_money[0]['max_num'];
        //获取用户获得最大金币数
        $sql = "select max(m.num) as max_num from (select sum(num) as num from web_turntable_record where prizeType=2 group by userID) as m";
        $user_max_jewels = M()->query($sql);
        $user_max_jewels = $user_max_jewels[0]['max_num'];
        $sql = "select max(m.user_count) as user_count from (select count(*) as user_count from web_turntable_record group by userID) as m";
        $user_count = M()->query($sql);
        $user_count = $user_count[0]['user_count'];
        $this->assign('turntable_count', $turntable_count);
        $this->assign('all_jewels', $all_jewels);
        $this->assign('all_money', $all_money);
        $this->assign('max', $max);
        $this->assign('user_max_jewels', $user_max_jewels);
        $this->assign('user_max_money', $user_max_money);
        $this->assign('user_count', $user_count);
        $this->display();
    }

    //反馈统计
    public function feedback_count()
    {
        //获取七天的反馈次数
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('Adminfeedback')->where(['f_time' => ['between', [$v, $end]]])->count();
            $data7[$i]['count'] = (int)$count ? $count : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);

        $this->assign('data7', $data7);
        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $count = M('Adminfeedback')->where(['f_time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->count();
            $data12[$i]['count'] = (int)$count ? $count : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //获取总次数
        $count = M('Adminfeedback')->count();
        $handle_count = M('Adminfeedback')->where(['read_type' => 3])->count();
        $nhandle_count = M('Adminfeedback')->where(['read_type' => ['neq', 3]])->count();
        $max = M()->query('select count(*) as c,userID from web_adminfeedback as a group by a.userID');
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => 'c', //排序字段
        );
        $arrSort = array();
        foreach ($max as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $max);
        }
        $max = $max[0];
        $this->assign('count', $count);
        $this->assign('handle_count', $handle_count);
        $this->assign('nhandle_count', $nhandle_count);
        $this->assign('max', $max);
        $this->display();
    }


    //分享统计
    public function share_count()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('share_record')->where(['share_time' => ['between', [$v, $end]]])->count();
            $data7[$i]['count'] = (int)$count ? $count : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        //dump($data7);exit();
        $this->assign('data7', $data7);
        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $count = M('share_record')->where(['share_time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->count();
            $data12[$i]['count'] = (int)$count ? $count : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        $count = M('share_record')->count();
        $this->assign('count', $count);
        $max = M()->query('select count(*) as c,userid from web_share_record as a group by a.userid');
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => 'c', //排序字段
        );
        $arrSort = array();
        foreach ($max as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $max);
        }
        $max = $max[0];
        $this->assign('max', $max);
        $this->display();
    }

    //签到统计
    public function sign_count()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('sign_record')->where(['prizeType' => 1, 'signTime' => ['between', [$v, $end]]])->sum('num');
            $jewels = M('sign_record')->where(['prizeType' => 2, 'signTime' => ['between', [$v, $end]]])->sum('num');
            $data7[$i]['money'] = (int)$money ? $money : 0;
            $data7[$i]['jewels'] = (int)$jewels ? $jewels : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);

        $this->assign('data7', $data7);
        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $money = M('sign_record')->where(['prizeType' => 1, 'signTime' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('num');
            $jewels = M('sign_record')->where(['prizeType' => 2, 'signTime' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('num');
            $data12[$i]['money'] = (int)$money ? $money : 0;
            $data12[$i]['jewels'] = (int)$jewels ? $jewels : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //抽奖数据
        $sign_count = M('sign_record')->count();
        $all_money = M('sign_record')->where(['prizeType' => 1])->sum('num');
        $all_jewels = M('sign_record')->where(['prizeType' => 2])->sum('num');
        $max = M()->query('select count(*) as c,userID from web_sign_record as a group by a.userID');
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => 'c', //排序字段
        );
        $arrSort = array();
        foreach ($max as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $max);
        }
        $max = $max[0];
        //获取用户获得最大金币数
        $sql = "select sum(num) as max from web_sign_record where prizeType=1 group by userID";
        $user_max_money = max(M()->query($sql));
        $user_max_money = $user_max_money['max'];
        //获取用户获得最大房卡数
        $sql = "select sum(num) as max from web_sign_record where prizeType=2 group by userID";
        $user_max_jewels = max(M()->query($sql));
        $user_max_jewels = $user_max_jewels['max'];
        $sql = "select max(m.user_count) as user_count from (select count(*) as user_count from web_sign_record group by userID) as m";
        $user_count = M()->query($sql);
        $user_count = $user_count[0]['user_count'];
        $this->assign('sign_count', $sign_count);
        $this->assign('all_jewels', $all_jewels);
        $this->assign('all_money', $all_money);
        $this->assign('max', $max);
        $this->assign('user_max_jewels', $user_max_jewels);
        $this->assign('user_max_money', $user_max_money);
        $this->assign('user_count', $user_count);
        $this->display();
    }

    //实物兑换统计
    public function convert_record()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('pay_orders')->where(['buyType' => 4, 'create_time' => ['between', [$v, $end]]])->sum('consumeNum');
            $count = M('pay_orders')->where(['buyType' => 4, 'create_time' => ['between', [$v, $end]]])->count();
            $data7[$i]['money'] = (int)$money ? $money : 0;
            $data7[$i]['count'] = (int)$count ? $count : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        //dump($data7);exit();
        $this->assign('data7', $data7);
        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $money = M('pay_orders')->where(['buyType' => 4, 'create_time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('consumeNum');
            $count = M('pay_orders')->where(['buyType' => 4, 'create_time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->count();
            $data12[$i]['money'] = (int)$money ? $money : 0;
            $data12[$i]['count'] = (int)$count ? $count : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //抽奖数据
        $count = M('pay_orders')->where(['buyType' => 4])->count();
        $money = M('pay_orders')->where(['buyType' => 4])->sum('consumeNum');
        $jewels = M('pay_orders')->where(['buyType' => 4, 'buyGoods' => '房卡'])->sum('buyNum');
        $max = M()->query('select count(*) as c,userID from web_pay_orders as a group by a.userID');
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field' => 'c', //排序字段
        );
        $arrSort = array();
        foreach ($max as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $max);
        }
        $maxc = $max[0];
        //获取用户消耗金币数
        $sql = "select max(m.num) as max_num from (select max(consumeNum) as num from web_pay_orders where buyType=4 group by userID) as m";
        $user_max_money = M()->query($sql);
        $user_max_money = $user_max_money[0]['max_num'];
        $this->assign('count', $count);
        $this->assign('money', $money);
        $this->assign('jewels', $jewels);
        $this->assign('maxc', $maxc);
        $this->assign('user_max_money', $user_max_money);
        $this->display();
    }

    //充值统计
    public function user_recharge_count()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $num = M('recharge_commission')->where(['recharge_time' => ['between', [$v, $end]]])->sum('recharge_amount');
            $data7[$i]['num'] = sprintf("%.2f", ((int)$num ? $num : 0) / 100);
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        $this->assign('data7', $data7);

        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $num = M('recharge_commission')->where(['recharge_time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->sum('recharge_amount');
            $data12[$i]['num'] = sprintf("%.2f", ((int)$num ? $num : 0) / 100);
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //获取平台充值总额，平台净收入，单次充值最大值，当月充值总额，当月平台收入总额，单人总计充值最多
        $recharge_all = sprintf("%.2f", M('recharge_commission')->sum('recharge_amount')) / 100;
        $recharge_max = sprintf("%.2f", M('recharge_commission')->max('recharge_amount')) / 100;
        $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        $month_recharge_all = sprintf("%.2f", M('recharge_commission')->where(['recharge_time' => ['between', [$beginThismonth, $endThismonth]]])->sum('recharge_amount')) / 100;
        //$user_recharge_max = sprintf("%.2f",M('recharge_commission')->group('recharge_userid')->sum('recharge_amount'))/100;
        $user_recharge_max = M()->query('select max(r.s) as m from (select sum(recharge_amount) as s from web_recharge_commission as r group by recharge_userid) as r');
        $user_recharge_max = sprintf("%.2f", $user_recharge_max[0]['m'] / 100);
        $commission_all = sprintf("%.2f", (M('recharge_commission')->sum('bind_member_commission')) + (M('recharge_commission')->sum('level2_member_commission')) + (M('recharge_commission')->sum('level3_member_commission'))) / 100;
        $plat_all = $recharge_all - $commission_all;
        $month_commission_all = sprintf("%.2f", (M('recharge_commission')->where(['recharge_time' => ['between', [$beginThismonth, $endThismonth]]])->sum('bind_member_commission')) + (M('recharge_commission')->where(['recharge_time' => ['between', [$beginThismonth, $endThismonth]]])->sum('level2_member_commission')) + (M('recharge_commission')->where(['recharge_time' => ['between', [$beginThismonth, $endThismonth]]])->sum('level3_member_commission'))) / 100;
        $month_plat_all = $month_recharge_all - $month_commission_all;
        $this->assign('recharge_all', $recharge_all);
        $this->assign('recharge_max', $recharge_max);
        $this->assign('month_recharge_all', $month_recharge_all);
        $this->assign('user_recharge_max', $user_recharge_max);
        $this->assign('plat_all', $plat_all);
        $this->assign('month_plat_all', $month_plat_all);
        //充值金额数分布图
        $recharge_all = M('recharge_commission')->sum('recharge_amount');
        $user_recharge = sprintf("%.4f", M('recharge_commission')->where(['user_type' => 0])->sum('recharge_amount') / $recharge_all) * 100;
        $level1_recharge = sprintf("%.4f", M('recharge_commission')->where(['user_type' => 1])->sum('recharge_amount') / $recharge_all) * 100;
        $level2_recharge = sprintf("%.4f", M('recharge_commission')->where(['user_type' => 2])->sum('recharge_amount') / $recharge_all) * 100;
        $level3_recharge = sprintf("%.4f", M('recharge_commission')->where(['user_type' => 3])->sum('recharge_amount') / $recharge_all) * 100;
        $this->assign('user_recharge', $user_recharge);
        $this->assign('level1_recharge', $level1_recharge);
        $this->assign('level2_recharge', $level2_recharge);
        $this->assign('level3_recharge', $level3_recharge);
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        //今日充值金额
        $today_recharge_all = sprintf("%.2f", M('recharge_commission')->where(['recharge_time' => ['between', [$begin, $begin + 24 * 3600]]])->sum('recharge_amount') / 100);
        $today_recharge_max = sprintf("%.2f", M('recharge_commission')->where(['recharge_time' => ['between', [$begin, $begin + 24 * 3600]]])->max('recharge_amount') / 100);
        $this->assign('today_recharge_all', $today_recharge_all);
        $this->assign('today_recharge_max', $today_recharge_max);
        $this->display();
    }

    //下载统计
    //下载统计
    public function register_download_record()
    {
        $model = M();
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $week[] = $begin;
            $begin = $begin - 24 * 3600;
        }
        $i = 1;
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $num = $model->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0, 'registerTime' => ['between', [$v, $end]]])->count();
            $data7[$i]['num'] = (int)$num ? $num : 0;
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        $this->assign('data7', $data7);

        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $num = $model->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0, 'registerTime' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]]])->count();
            $data12[$i]['num'] = (int)$num ? $num : 0;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //分析时间段
        $data = $model->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0])->select();
//        dump($data);
        $arr = $this->data24($data);
//        var_export($arr);
        $this->assign('data24', $arr);
        //dump($arr);exit();
        //平台下载量总量
        $all = $model->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0])->count();
        $this->assign('all', $all);
        $this->display();
    }

    protected function data24($data)
    {
        $arr = [];
        for ($i = 0; $i < 24; $i++) {
            $a = $i;
            if ($a < 10) {
                $a = '0' . $a;
            }
            $arr[$a] = 0;
        }
        foreach ($data as $k => $v) {
            $h = date('H', $data[$k]['registertime']);
            //echo $data[$k]['registertime'];
            $arr[$h]++;
        }
        //dump($arr);
        return $arr;
    }


    //平台数据统计
    public function plat_data_record()
    {
        $start = I('start');
        $stop = I('stop');

        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop);
            $time = [$start, $stop];
        } else {
            $start = strtotime(date('Y-m-d', time()));
            $stop = $start + 24 * 3600 - 1;
            $time = [$start, $stop];
        }

        #获取充值数据
        $recharge_data = $this->get_recharge($time);
        #获取代理信息
        $agent_data = $this->get_agent($time);
        #获取玩家数据
        $user_data = $this->get_user($time);
        #获取平台数据
        $plat_data = $this->get_plat($time);
        #获取金币统计
        $money_data = $this->get_money($time);
        #获取房卡统计
        $jewels_data = $this->get_jewels($time);
        #获取事件统计
        $count_data = $this->get_count($time);
        $this->assign('recharge_data', $recharge_data);
        $this->assign('agent_data', $agent_data);
        $this->assign('user_data', $user_data);
        $this->assign('plat_data', $plat_data);
        $this->assign('money_data', $money_data);
        $this->assign('jewels_data', $jewels_data);
        $this->assign('count_data', $count_data);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->display();
    }

    #获取充值数据
    # @return [在线充值金额，代理分成金额,平台净收入,在线充值金币数，在线充值房卡数，后台充值金币数，后台充值房卡数，后台提取金币数，后台提取房卡数]
    public function get_recharge($time = [])
    {
        if (!$time) {
            $where1 = [];
            $where2 = [];
            $where3 = [];
        } else {
            $where1 = ['recharge_time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['create_time' => ['between', [$time[0], $time[1]]], 'status' => 1, 'consumeType' => 0];
            $where3 = ['actionTime' => ['between', [$time[0], $time[1]]]];
        }
        #在线充值总额
        $recharge_all = M('recharge_commission')->where($where1)->sum('recharge_amount');
        #代理分成总额
        $commission_all = M('recharge_commission')->where($where1)->sum('bind_member_commission')
            + M('recharge_commission')->where($where1)->sum('level2_member_commission')
            + M('recharge_commission')->where($where1)->sum('level3_member_commission');
        #平台净收入总额
        $income = sprintf('%.2f', ($recharge_all - $commission_all) / 100);
        #在线充值金币
        $where2['buyType'] = 1;
        $where2['status'] = 1;
        $recharge_money = M('pay_orders')->where($where2)->sum('buyNum');
        #在线充值房卡
        $where2['buyType'] = 2;
        $recharge_jewels = M('pay_orders')->where($where2)->sum('buyNum');
        #后台充值金币
        $where3['actionType'] = 1;
        $where3['type'] = 1;
        $admin_recharge_money = M('admin_action')->where($where3)->sum('resourceNum');
        #后台充值房卡
        $where3['type'] = 2;
        $admin_recharge_jewels = M('admin_action')->where($where3)->sum('resourceNum');
        #后台提取金币
        $where3['actionType'] = 2;
        $where3['type'] = 1;
        $admin_pos_money = M('admin_action')->where($where3)->sum('resourceNum');
        #后台提取房卡
        $where3['type'] = 2;
        $admin_pos_jewels = M('admin_action')->where($where3)->sum('resourceNum');
        $data = [sprintf('%.2f', $recharge_all / 100), $commission_all, $income, $recharge_money, $recharge_jewels, $admin_recharge_money, $admin_recharge_jewels, $admin_pos_money, $admin_pos_jewels];
        return $data;
    }


    #获取代理信息
    #@return [代理总人数，代理可提现金额，代理已提现金额，抽水金币代理分成总额，已提现抽水金币总额，提现抽水金币折合人民币]
    public function get_agent($time = [])
    {
        $where1 = [];
        $where2 = [];

        if ($time) {
            $where1 = ['register_time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['create_time' => ['between', [$time[0], $time[1]]]];
        }
        #代理总人数
        $agent_count = M('agent_member')->where($where1)->count();
        #代理可提现金额
        $agent_can_pos = sprintf('%.2f', (M('agent_member')->sum('balance')) / 100);
        #代理已提现金额
        $agent_history_pos = sprintf('%.2f', (M('agent_member')->sum('history_pos_money')) / 100);
        #代理抽水金币分成总额
        //$agent_pump_money = M('agentpumprecord')->where($where2)->sum('into1') + M('agentpumprecord')->where($where2)->sum('into2') + M('agentpumprecord')->where($where2)->sum('into3');
        $agent_pump_money = 0;
        #已提现金币抽水总额
        //$pump_pos_money = M('agent_member')->sum('pump_pos_money');
        $pump_pos_money = 0;
        #已提现抽水金币折合人民币
        //$pump_rmb = sprintf('%.2f',(M('agent_member')->sum('pump_rmb')));
        $pump_rmb = '0.00';
        $data = [$agent_count, $agent_can_pos, $agent_history_pos, $agent_pump_money, $pump_pos_money, $pump_rmb];
        return $data;
    }



    #获取玩家数据
    #@return [平台玩家数，平台机器人数，当前在线玩家数，玩家房卡总数，玩家身上金币数，玩家银行金币数，机器人身上金币数，机器人银行金币数]
    public function get_user()
    {
        $data = [$user_count, $vbot_count, $online_count, $user_jewels, $user_money, $user_bank_money, $vbot_money, $vbot_bank_money, $all_money, $all_jewels];
        return $data;
    }

    #获取平台数据
    #@return [平台消耗金币总数，平台消耗房卡总数，已开房卡场总数，游戏输赢金币总数，目前活跃玩家总数]
    public function get_plat($time = '')
    {
        if (!$time) {
            $where1 = [];
            $where2 = [];
        } else {
            $where1 = ['time' => ['between', [$time[0], $time[1]]]];
            $where3 = ['time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['createTime' => ['between', [$time[0], $time[1]]]];
        }
        #金币消耗:转赠消耗，平台提取，魔法表情消耗，开局消耗
        $where1['reason'] = ['in', '3,11,15,16'];
        $cost_money = abs(M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney'));
        #房卡消耗:世界广播消耗，转赠扣除，大结算普通支付，大结算aa支付
        $where1['reason'] = ['in', '1,2,6,11,12,14'];
        $fh_jewels = M()->table('statistics_jewelschange')->where($where3)->where(['reason' => 15])->sum('changeJewels');
        $cost_jewels = abs(M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels')) - $fh_jewels;
        #已开房卡场数
        $where2['passwd'] = ['neq', 'aa'];
        $jewels_game_count = M()->table('statistics_gameRecordInfo')->where($where2)->count();
        #游戏输赢金币总数
        $where1['reason'] = 14;
        $bunko_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        #当前活跃玩家
        $now_active_user = $this->now_active_user();
        $data = [$cost_money, $cost_jewels, $jewels_game_count, $bunko_money, $now_active_user];
        return $data;
    }

    #金币统计
    #@return [玩家签到获得金币，玩家抽奖获得金币,领取救济金获得金币，#玩家注册获得金币#，魔法表情消耗金币，玩家转赠消耗金币，实物兑换消耗金币，游戏开局消耗金币]
    public function get_money($time = [])
    {
        if (!$time) {
            $where1 = [];
            $where2 = [];
        } else {
            $where1 = ['time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['create_time' => ['between', [$time[0], $time[1]]]];
            $where3 = ['time' => ['between', [$time[0], $time[1]]]];
        }
        #签到获得金币
        $where1['reason'] = 6;
        $sign_get_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        #抽奖获得金币
        $where1['reason'] = 9;
        $turntable_get_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        #领取救济金获得金币
        $where1['reason'] = 5;
        $support_get_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        #玩家注册赠送金币
        $reg_money = M('reg_money')->where($where3)->sum('money');
        #魔法表情消耗金币
        $where1['reason'] = 15;
        $magic_cost_money = abs(M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney'));
        #转赠消耗金币
        $where1['reason'] = 3;
        $send_cost_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        #实物兑换消耗金币
        $where2['status'] = 1;
        $where2['consumeType'] = 1;
        $convert_cost_money = M('pay_orders')->where($where2)->sum('consumeNum');
        $where1['reason'] = 16;
        $begin_cost_money = abs(M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney'));
        #分享获得金币
        $where1['reason'] = 10;
        $share_get_money = M()->table('statistics_moneyChange')->where($where1)->sum('changeMoney');
        $data = [$sign_get_money, $turntable_get_money, $support_get_money, $reg_money, $magic_cost_money, $send_cost_money, $convert_cost_money, $begin_cost_money, $share_get_money];
        return $data;
    }

    #房卡统计
    #@return [玩家签到获得房卡，玩家抽奖获得房卡,实物兑换获得房卡，玩家注册获得房卡，发送广播消耗房卡，游戏开局消耗房卡，]
    public function get_jewels($time = [])
    {
        if (!$time) {
            $where1 = [];
            $where2 = [];
        } else {
            $where1 = ['time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['create_time' => ['between', [$time[0], $time[1]]]];
        }
        #玩家签到获得房卡
        $where1['reason'] = 4;
        $sign_get_jewels = M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels');
        #玩家抽奖获得房卡
        $where1['reason'] = 8;
        $turntable_get_jewels = M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels');
        #玩家实物兑换获得房卡
        $where2['status'] = 1;
        $where2['consumeType'] = 1;
        $where2['buyGoods'] = '房卡';
        $convert_cost_jewels = M('pay_orders')->where($where2)->sum('buyNum');
        #玩家注册获得房卡
        $diamonds = M('reg_money')->where($where1)->sum('diamonds');
        #玩家发送世界广播消耗房卡
        $where1['reason'] = 1;
        $radio_cost_jewels = M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels');
        #游戏开局消耗房卡
        $where1['reason'] = ['in', '11,12,14'];
        $fh_jewels = abs(M()->table('statistics_jewelschange')->where(['reason' => 15])->sum('changeJewels'));
        $begin_cost_jewels = abs(M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels')) - $fh_jewels;
        #分享获得房卡
        $where1['reason'] = 9;
        $share_get_jewels = M()->table('statistics_jewelschange')->where($where1)->sum('changeJewels');
        $data = [$sign_get_jewels, $turntable_get_jewels, $convert_cost_jewels, $diamonds, $fh_jewels, $begin_cost_jewels, $share_get_jewels];
        return $data;
    }
    #事件统计
    #@return [玩家登录总次数,玩家反馈总次数,玩家分享总次数,玩家签到总次数,玩家转赠总次数，玩家抽奖总次数,发送广播总次数,魔法表情总次数，实物兑换总次数，绑定代理用户数，领取救济金总次数]
    public function get_count($time = '')
    {
        if (!$time) {
            $where1 = [];
            $where2 = [];
            $where3 = [];
            $where4 = [];
            $where5 = [];
            $where6 = [];
            $where7 = [];
            $where8 = [];
        } else {
            $where1 = ['time' => ['between', [$time[0], $time[1]]]];
            $where2 = ['f_time' => ['between', [$time[0], $time[1]]]];
            $where3 = ['share_time' => ['between', [$time[0], $time[1]]]];
            $where4 = ['signTime' => ['between', [$time[0], $time[1]]]];
            $where5 = ['turntableTime' => ['between', [$time[0], $time[1]]]];
            $where6 = ['reqTime' => ['between', [$time[0], $time[1]]]];
            $where7 = ['create_time' => ['between', [$time[0], $time[1]]]];
            $where8 = ['bind_time' => ['between', [$time[0], $time[1]]]];
        }
        #玩家登录总次数
        $where1['type'] = 1;
        $user_login_count = M()->table(MysqlConfig::Table_statistics_logonandlogout)->where($where1)->count();
        #玩家反馈总次数
        //$user_feedback_count = M('adminfeedback')->where($where2)->count();
        #玩家分享总次数
        $user_share_count = M('share_record')->where($where3)->count();
        #玩家签到总次数
        $user_sign_count = M('sign_record')->where($where4)->count();
        #玩家转赠次数
        unset($where1['type']);
        $user_send_count = M('give_record')->where($where1)->count();
        #玩家抽奖次数
        $user_turntable_count = M('turntable_record')->where($where5)->count();
        #发送广播次数
        $user_radio_count = M()->table('web_horn')->where($where6)->count();
        #魔法表情次数
        $user_magic_count = M()->table('statistics_moneyChange')->where($where1)->where(['reason' => 15])->count();
        #实物兑换次数
        $where7['status'] = 1;
        $where7['consumeType'] = 1;
        $user_convert_count = M('pay_orders')->where($where7)->count();
        #绑定代理用户数
        $user_bind_count = M('agent_bind')->where($where8)->count();
        #领取救济金次数
        $user_support_count = M()->table('web_horn')->where($where6)->count();
        $data = [$user_login_count, $user_feedback_count, $user_share_count, $user_sign_count, $user_send_count, $user_turntable_count, $user_radio_count, $user_magic_count, $user_convert_count, $user_bind_count, $user_support_count];
        return $data;
    }

    // 平台数据统计
    public function getRedisData()
    {
        $redis = \GetRedis::get();
        $prefix = 'userInfo|';
        $user = $redis->redis->keys($prefix . '*');
        $arr = [];
        foreach ($user as $k => &$v) {
            $arr[$k] = $redis->redis->hgetall($v);
        }

        $user_count = 0; // 用户数量
        $online_count = 0; // 在线玩家
        $user_jewels = 0; // 用户携带钻石
        $user_money = 0;  // 用户携带金币
        $user_bank_money = 0; // 银行金币


        foreach ($arr as $k => $v) {
            if ($arr[$k]['isVirtual'] == 0) {

                $user_count++;
                $user_jewels = $user_jewels + $arr[$k]['jewels'];
                $user_money = $user_money + $arr[$k]['money'];
                $user_bank_money = $user_bank_money + $arr[$k]['bankMoney'];
                if ($arr[$k]['isOnline'] == 1) {
                    $online_count++;
                }
            }
        }

        $all_money = $user_money + $user_bank_money;
        echo json_encode(['user_count' => $user_count, 'all_money' => $all_money, 'online_count' => $online_count, 'user_money' => $user_money, 'user_jewels' => $user_jewels, 'user_bank_money' => $user_bank_money]);
    }

    //在线用户统计
    public function online_count()
    {
        //七天的数据
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $week[] = $begin;
            $begin = $begin - 24 * 3600;
        }

        $i = 1;

        foreach ($week as $k => &$v) {
            $end = $v + 24 * 3600;
            //echo $v.'|'.$end;die();
            $res = M()->table(MysqlConfig::Table_statistics_logonandlogout)->where(['time' => ['between', [& $v, $end]], 'type' => 1])->select();
            $arr = [];
            foreach ($res as $z => $j) {
                $arr[] = $res[$z]['userid'];
            }
            //数组去重
            $arr = array_unique($arr);
            $data7[$i]['num'] = count($arr);
            $data7[$i]['date'] = date("m-d", $v);
            $i++;
        }
        $data7 = array_reverse($data7);
        $this->assign('data7', $data7);

        //最近十二个月数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $res = M()->table(MysqlConfig::Table_statistics_logonandlogout)->where(['time' => ['between', [$time12[$k]['begin2'], $time12[$k]['end2']]], 'type' => 1])->select();
            $arr = [];
            foreach ($res as $z => $j) {
                $arr[] = $res[$z]['userid'];
            }
            //数组去重
            $arr = array_unique($arr);
            $data12[$i]['num'] = count($arr);
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);
        $this->assign('data12', $data12);
        //获取最多在线人数以及日期
        $file = '/usr/local/nginx/html/timedTask/data.txt';
        $str = file_get_contents($file);
        $new = explode('|', $str);
        $this->assign('max_online_count', $new[0]);
        $this->assign('max_online_date', $new[1]);
        //获取目前在线人数
        $this->display();
    }

    //获取目前在线
    public function online_now()
    {
        $user = [];
        $time = [];
        //获取所有的用户ID
        $arr = M()->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0])->select();
        $userID = [];
        foreach ($arr as $k => $z) {
            $userID[] = $arr[$k]['userid'];
        }
        foreach ($userID as $k => &$v) {
            $res = M()->table(MysqlConfig::Table_statistics_logonandlogout)->where(['userID' => $v])->select();
            $count = count($res);
            if (!$res || $res[$count - 1]['type'] != 1) {
                continue;
            } else {
                $user[] = $res[$count - 1]['userid'];
                $time[] = time() - $res[$count - 1]['time'];
            }
        }
        //获取最大的在线时间以及对应的用户
        $key = array_search(max($time), $time);
        $arr = [
            'time' => max($time),
        ];
        //获取用户昵称
        $u = M()->table(MysqlConfig::Table_userinfo)->where(['userID' => $user[$key]])->find();
        $arr['username'] = $u['name'];
        $arr['count'] = count($user);
        return $arr;
        // echo json_encode($arr);
    }

    //在线时长分布图
    public function online_time()
    {
        $c = [
            't1' => 0,
            't2' => 0,
            't3' => 0,
            't4' => 0,
        ];
        //获取所有的用户ID
        $arr = M()->table(MysqlConfig::Table_userinfo)->where(['isVirtual' => 0])->select();
        $userID = [];
        foreach ($arr as $k => $v) {
            $userID[] = $arr[$k]['userid'];
        }
        //取每个用户的在线时间1-15,15-30,30-45,45分钟以上的次数
        $begintime1 = time();
        foreach ($userID as $k => &$v) {
            //如果没有记录或者记录小于2条则跳过循环
            $res = M()->table(MysqlConfig::Table_statistics_logonandlogout)->where(['userID' => $v])->select();
            if (!$res || count($res) < 2) {
                continue;
            } else {
                if ($res[0]['type'] == 2) {
                    unset($res[0]);
                    //键值从0开始
                    foreach ($res as $z => &$j) {
                        $res[$z - 1] = $j;
                    }
                }

                $last = count($res) - 1;
                if ($res[$last]['type'] == 1) {
                    unset($res[$last]);
                }
                $login = [];
                $logout = [];
                foreach ($res as $a => $z) {
                    if ($a % 2 != 0) {
                        $logout[] = $res[$a]['time'];
                    } else {
                        $login[] = $res[$a]['time'];
                    }
                }
                $count = count($login);
                for ($i = 0; $i < $count; $i++) {
                    $online_time = ($logout[$i] - $login[$i]) / 60;
                    if ($online_time <= 15) {
                        $c['t1']++;
                    } elseif ($online_time > 15 && $online_time < 30) {
                        $c['t2']++;
                    } elseif ($online_time > 30 && $online_time < 45) {
                        $c['t3']++;
                    } else {
                        $c['t4']++;
                    }
                }
            }

        }
        return $c;
    }

    //活跃玩家以及流失玩家
    public function active_loss_user()
    {
        //取出所有的记录
        $str = file_get_contents(APP_ROOT_PATH . '/../timedTask/active_loss_record.txt');
        $record = explode(',', $str);
        foreach ($record as $k => &$v) {
            $record[$k] = explode('|', $v);
        }
        //dump($record);exit();
        $count = count($record);
        //最近七天
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        for ($i = 1; $i <= 7; $i++) {
            $begin = $begin - 24 * 3600;
            $data7[$i]['date'] = date("m-d", $begin);
            $data7[$i]['active_count'] = $record[$count - $i][0];
            $data7[$i]['loss_count'] = $record[$count - $i][1];
        }
        $data7 = array_reverse($data7);

        $this->assign('data7', $data7);

        //最近十二个月充值数据
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        $i = 1;
        foreach ($time12 as $k => $v) {
            $active_count = 0;
            $loss_count = 0;
            foreach ($record as $z => &$j) {
                if ($j[2] > $time12[$k]['begin2'] && $j[2] < $time12[$k]['end2']) {
                    $active_count += $j[0];
                    $loss_count += $j[1];
                }
            }
            $data12[$i]['active_count'] = $active_count;
            $data12[$i]['loss_count'] = $loss_count;
            $data12[$i]['date'] = $time12[$k]['month'];
            $i++;
        }
        $data12 = array_reverse($data12);

        $this->assign('data12', $data12);
        $this->display();

    }

    // 领取救济金
    public function alms_count()
    {
        $begin = strtotime(date('Y-m-d'));
        $end = $begin + 86399;
        for ($i = 1; $i <= 7; $i++) {
            $begin = $begin - 24 * 3600;
            $time[] = $begin;
        }

        // 近七天的情况
        foreach ($time as $k => $v) {
            $begin = $v;
            $end = $v + 24 * 3600 - 1;
            $res[] = M('support_record')->where(['reqTime' => ['between', [$begin, $end]]])->count();
            $d[] = $v;
        }

        //近一年的情况
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        foreach ($time12 as $k => $v) {
            $time12[$k]['yearCount'] = M('support_record')->where(['reqTime' => ['between', [$v['begin2'], $v['end2']]]])->count();
        }

        //单个用户最多次数
        $sql = "select count(userID) as usercount from web_support_record group by userID";
        $data = M()->query($sql);

        foreach ($data as $k => $v) {
            $_data[] = $v['usercount'];
        }
        $pos = array_search(max($_data), $_data);
        $max = $_data[$pos];

        $this->assign('d', $d);
        $this->assign('year', $time12); // 年
        $this->assign('day', $res); // 天
        $this->assign('count', M('support_record')->count()); // 领取总次数
        $this->assign('JbCount', M('support_record')->sum('Money')); // 领取救济金增加金币数
        $this->assign('max', $max);
        $this->display();
    }

    //世界广播
    public function radio_count()
    {
        $begin = strtotime(date('Y-m-d'));
        $end = $begin + 86399;
        for ($i = 1; $i <= 7; $i++) {
            $begin = $begin - 24 * 3600;
            $time[] = $begin;
        }

        // 近七天的情况
        foreach ($time as $k => $v) {
            $begin = $v;
            $end = $v + 24 * 3600 - 1;
            $res[] = M()->table('web_horn')->where(['reqTime' => ['between', [$begin, $end]]])->count();
            $jewels[] = M()->table('web_horn')->where(['reqTime' => ['between', [$begin, $end]]])->sum('cost');
            $d[] = $v;
        }

        //近一年的情况
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        foreach ($time12 as $k => $v) {
            $time12[$k]['yearCount'] = M()->table('web_horn')->where(['reqTime' => ['between', [$v['begin2'], $v['end2']]]])->count();
            $jewelsYear[] = M()->table('web_horn')->where(['reqTime' => ['between', [$v['begin2'], $v['end2']]]])->sum('cost');
        }

        //单个用户最多次数
        $sql = "select count(userID) as usercount from web_horn group by userID";
        $data = M()->query($sql);

        foreach ($data as $k => $v) {
            $_data[] = $v['usercount'];
        }
        $pos = array_search(max($_data), $_data);
        $max = $_data[$pos];

        //dump($jewels);die;
        $this->assign('d', $d);
        $this->assign('year', $time12); // 年
        $this->assign('day', $res); // 天
        $this->assign('count', M()->table('web_horn')->count()); // 领取总次数
        // $this->assign('JbCount',M()->table('web_horn')->sum('reqSupportMoney')); // 领取救济金增加金币数
        $this->assign('max', $max);
        $this->assign('jewels', $jewels);
        $this->assign('jewelsYear', $jewelsYear);
        $this->display();
    }

    //用户转赠
    public function given_count()
    {
        $begin = strtotime(date('Y-m-d'));
        $end = $begin + 86399;
        for ($i = 1; $i <= 7; $i++) {
            $begin = $begin - 24 * 3600;
            $time[] = $begin;
        }

        // 近七天的情况
        foreach ($time as $k => $v) {
            $begin = $v;
            $end = $v + 24 * 3600 - 1;
            $money[] = M('give_record')->where(['time' => ['between', [$begin, $end]], 'type' => 1])->sum('value') - M('give_record')->where(['time' => ['between', [$begin, $end]], 'type' => 1])->sum('real_value'); //金币
            $fk[] = M('give_record')->where(['time' => ['between', [$begin, $end]], 'type' => 2])->sum('value') - M('give_record')->where(['time' => ['between', [$begin, $end]], 'type' => 2])->sum('real_value'); //房卡
            $d[] = $v;
        }

        //近一年的情况
        $year = date('Y', time());
        $month = date('m', time());
        $time12 = $this->getLastTimeArea($year, $month, 12);
        foreach ($time12 as $k => $v) {
            $time12[$k]['yearCountMoney'] = M('give_record')->where(['time' => ['between', [$v['begin2'], $v['end2']]], 'type' => 1])->sum('value') - M('give_record')->where(['time' => ['between', [$v['begin2'], $v['end2']]], 'type' => 1])->sum('real_value');
            $time12[$k]['yearCountJb'] = M('give_record')->where(['time' => ['between', [$v['begin2'], $v['end2']]], 'type' => 2])->sum('value') - M('give_record')->where(['time' => ['between', [$v['begin2'], $v['end2']]], 'type' => 2])->sum('real_value');
            $y[] = $v['month'];
        }

        //单个用户最多次数
        $sql = "select count(userID) as usercount from web_give_record group by userID";

        $data = M()->query($sql);

        foreach ($data as $k => $v) {
            $_data[] = $v['usercount'];
        }
        $pos = array_search(max($_data), $_data);
        $maxMoney = $_data[$pos];

        //单个用户转赠最多金币数,房卡数
        $userMoneyCount = M('give_record')->order('real_value desc')->where(['type' => 1])->getfield('value');
        $userFkCount = M('give_record')->order('real_value desc')->where(['type' => 2])->getfield('value');
        $this->assign('d', $d); // 天
        $this->assign('y', $y); // 年
        $this->assign('time12j', $time12); // 最近一年金币
        $this->assign('time12f', $time12); // 最近一年房卡
        $this->assign('money', $money); // 最近七天 金币
        $this->assign('fk', $fk); // 最近七天房卡
        $this->assign('zzCount', M('give_record')->count()); // 转赠总次数
        $xhMoney = M('give_record')->where(['type' => 1])->sum('value') - M('give_record')->where(['type' => 1])->sum('real_value');
        $this->assign('zzMoneyCount', $xhMoney); // 转赠金币消耗数
        $xhJewel = M('give_record')->where(['type' => 1])->sum('value') - M('give_record')->where(['type' => 2])->sum('real_value');
        $this->assign('zzFkCount', $xhJewel); // 转赠砖石消耗数
        $this->assign('maxMoney', $maxMoney); // 单个用户转赠最多次数
        $this->assign('userMoneyCount', $userMoneyCount);
        $this->assign('userFkCount', $userFkCount);
        $this->display();
    }

    //金币变化日志
    public function money_change_record()
    {
        $where = [];
        $type = I('type');
        $search = I('search');
        $starttime = urldecode(I('starttime'));
        $endtime = urldecode(I('endtime'));

        // if (!empty($starttime) && !empty($endtime)) {
        //     $starttime = strtotime($starttime);
        //     $endtime = strtotime($endtime);// + 24 * 3600 - 1;
        //     $where['S.time'] = ['between', [$starttime, $endtime]];
        // }

        $res = validSearchTimeRange($starttime, $endtime);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['S.time'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['S.userID'] = $search;
                    break;
                case 2:
                    $where['U.account'] = $search;
                    break;
                case 3:
                    $where['U.name'] = $search;
                    break;
            }
        }
        $reason = I('reason', '-1');
        if ($reason != -1) {
            $where['S.reason'] = $reason;
        }
        // $start = I('start');
        // $stop = I('stop');

        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['S.time'] = ['between', [$start, $stop]];
        // } else {
        //     $start = strtotime(date('Y-m-d', time()));
        //     $stop = $start + 24 * 3600 - 1;
        //     $where['S.time'] = ['between', [$start, $stop]];
        // }

        $count = M()->table('statistics_moneychange as S')->join('left join userInfo as U on U.userID=S.userID')->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $res = M()->table('statistics_moneychange as S')
            ->join('left join userInfo as U on U.userID = S.userID')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.id,S.userID,U.name,U.account,S.time,S.money,S.changeMoney,S.reason,R.name as rname')
            ->select();
            // $arrMoney = GeneralConfig::STATISTICS_MONEYCHANGE_MONEY;
            $res = FunctionHelper::moneyInArrayOutput($res, GeneralConfig::STATISTICS_MONEYCHANGE_MONEY);
            // var_export($arrMoney);
            // array_walk($arrMoney,function(&$v,$k){$v = strtolower($v);});
            // var_export($arrMoney);
        foreach ($res as $k => &$v) {
            // foreach ($v as $kk => &$vv) {
            //         // var_export($kk);
            //     if (in_array($kk, $arrMoney)) {
            //         // echo 'okkk';
            //         // var_export($kk);
            //         $vv = FunctionHelper::MoneyOutput($vv);
            //     }
            // }
            // $v['money'] = round(FunctionHelper::MoneyOutput($v['money']), 2);
            // $v['changemoney'] = round(FunctionHelper::MoneyOutput($v['changemoney']), 2);
            $v['reason_name'] = EnumConfig::E_ResourceChangeReasonName[$v['reason']];
        }

        // var_export($res);exit;
        $this->assign([
            'starttime' => $starttime,
            'endtime' => $endtime,
            'isHideNavbar' => I('isHideNavbar'),
        ]);
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);

        $reason_array = [];
        foreach (EnumConfig::E_ResourceChangeReason as $changeReason) {
            if ($changeReason != EnumConfig::E_ResourceChangeReason['DEFAULT']){
                $reason_array[$changeReason] = EnumConfig::E_ResourceChangeReasonName[$changeReason];
            }
        }

        $this->assign('reason_array', $reason_array);
        $this->assign('reason', $reason);
        $this->display();
    }



    // 游戏记录
    public function game_record_info()
    {
        $start = I('start');
        $stop = I('stop');
        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop) + 24 * 3600 - 1;
            $where['S.time'] = ['between', [$start, $stop]];
        } else {
            if (!$type) {
                $start = strtotime(date('Y-m-d', time()));
                $stop = $start + 24 * 3600 - 1;
                $where['S.time'] = ['between', [$start, $stop]];
            }
        }

        $game = M()->table('gameBaseInfo')->field('gameID,name')->select();
        $this->assign('game', $game);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->display();
    }


    //游戏记录
    public function game_record_info2()
    {
        //获取所有的游戏类型
        $game = M()->table('gameBaseInfo')->field('gameID,name')->select();

        foreach ($game as $k => $v) {
            //获取每个游戏的金币场房间信息
            $room = M()->table('roomBaseInfo')->where(['gameID' => $game[$k]['gameid']])->field('roomID,name')->select();
            $arr = [];
            //获取每个房间的数据
            foreach ($room as $z => $j) {
                $where1 = [
                    'roomID' => $room[$z]['roomid'],
                ];
                $where2 = [
                    'roomID' => $room[$z]['roomid'],
                    'reason' => 14,
                ];

                $start = I('start');
                $stop = I('stop');

                if ($start && $stop) {
                    $start = strtotime($start);
                    $stop = strtotime($stop);
                    $where1['beginTime'] = ['between', [$start, $stop]];
                    $where2['time'] = ['between', [$start, $stop]];
                }
                $count = M()->table('statistics_gameRecordInfo')->where($where1)->count();
                $count = $count ? $count : 0;
                //获取每个场的金币输赢
                $bunko = M()->table('statistics_moneyChange')->where($where2)->sum('changemoney');
                $bunko = $bunko ? $bunko : 0;
                if ($bunko < 0) {
                    $bunko = abs($bunko);
                } elseif ($bunko > 0) {
                    $bunko = '-' . $bunko;
                }
                if (strpos($room[$z]['name'], '初级场')) {
                    $game[$k]['chuji'] = ['count' => $count, 'bunko' => $bunko];
                } elseif (strpos($room[$z]['name'], '中级场')) {
                    $game[$k]['zhongji'] = ['count' => $count, 'bunko' => $bunko];
                } elseif (strpos($room[$z]['name'], '高级场')) {
                    $game[$k]['gaoji'] = ['count' => $count, 'bunko' => $bunko];
                } elseif (strpos($room[$z]['name'], '专家场')) {
                    $game[$k]['zhuanjia'] = ['count' => $count, 'bunko' => $bunko];
                }
            }
            $game[$k]['all_count'] = $game[$k]['chuji']['count'] + $game[$k]['zhongji']['count'] + $game[$k]['gaoji']['count'] + $game[$k]['zhuanjia']['count'];
            $game[$k]['all_bunko'] = $game[$k]['chuji']['bunko'] + $game[$k]['zhongji']['bunko'] + $game[$k]['gaoji']['bunko'] + $game[$k]['zhuanjia']['bunko'];
        }


        foreach ($game as $k => $v) {
            if (!isset($game[$k]['chuji'])) {
                $game[$k]['chuji'] = ['count' => 0, 'bunko' => 0];
            }

            if (!isset($game[$k]['zhongji'])) {
                $game[$k]['zhongji'] = ['count' => 0, 'bunko' => 0];
            }

            if (!isset($game[$k]['gaoji'])) {
                $game[$k]['gaoji'] = ['count' => 0, 'bunko' => 0];
            }
        }

        echo json_encode($game);
    }

    //获取今日金币场总输赢
    public function today_bunko()
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1; //今天结束时间戳
        $where = [
            'R.type' => 0,
            'S.reason' => 14,
            'S.time' => ['between', [$beginToday, $endToday]],
        ];
        $today_bunko = M()
            ->table('statistics_moneyChange as S')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->sum('changeMoney');
        $today_bunko = $today_bunko ? $today_bunko : 0;
        if ($today_bunko < 0) {
            $today_bunko = abs($today_bunko);
        } elseif ($today_bunko > 0) {
            $today_bunko = '-' . $today_bunko;
        }
        //echo $today_bunko;
        return $today_bunko;
    }

    //获取金币消耗
    public function money_cost()
    {
        //消耗原因：转赠手续费  开局消耗  魔法表情消耗
        $send_gift = M()
            ->table('give_record')
            ->sum('recievedNumber-deduceNumber');
        $opening_cost = M()
            ->table('statistics_moneyChange')
            ->where(['reason' => 16])
            ->sum('changeMoney');
        $magic_cost = M()
            ->table('statistics_magicExpress')
            ->sum('costMoney');
        return $send_gift + $opening_cost + $magic_cost;
    }

    //当前活跃玩家数
    public function now_active_user()
    {
        $time = time();
        $beginTime = $time - 7 * 24 * 3600;
        $active = M()
            ->table(MysqlConfig::Table_statistics_logonandlogout)
            ->where(['time' => ['between', [$beginTime, $time]], 'type' => 1])
            ->select();
        $user = [];
        foreach ($active as $k => $v) {
            $user[] = $active[$k]['userid'];
        }
        $user = array_unique($user);
        return count($user);
    }

    //平台玩家总数
    public function plat_user_count()
    {
        $plat_user_count = M()
            ->table(MysqlConfig::Table_userinfo)
            ->where(['isVirtual' => 0])
            ->count();
        return $plat_user_count;
    }

    //房卡变化日志
    public function jewels_change_record()
    {
        $where = [];
        $type = I('type');
        $search = I('search');

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['S.userID'] = $search;
                    break;
                case 2:
                    $where['U.account'] = $search;
                    break;
                case 3:
                    $where['U.name'] = $search;
                    break;
            }
        }

        $reason = I('reason', '-1');
        if ($reason != -1) {
            $where['S.reason'] = $reason;
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
//        if ($start && $stop) {
//            $start = strtotime($start);
//            $stop = strtotime($stop);
//            $where['S.time'] = ['between', [$start, $stop]];
//        } else {
//            $start = strtotime(date('Y-m-d', time()));
//            $stop = $start + 24 * 3600 - 1;
//            $where['S.time'] = ['between', [$start, $stop]];
//        }

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['S.time'] = $res['data'];
        }

        $count = M()->table('statistics_jewelschange as S')->join('left join userInfo as U on U.UserID = S.UserID')->where($where)->count();
        //echo $count;exit();
        $Page = new \Think\Page($count, 15);
        $res = M()->table('statistics_jewelschange as S')
            ->join('left join userInfo as U on U.userID = S.userID')
            ->join('left join roomBaseInfo as R on R.roomID = S.roomID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('time desc')
            ->field('S.userID,U.name,U.account,S.time,S.jewels,S.changeJewels,S.reason,R.name as rname')
            ->select();

        foreach ($res as $k => &$v) {
            $v['reason_name'] = EnumConfig::E_ResourceChangeReasonName[$v['reason']];
        }
        $changeType = ChangeReasonList();
        $this->assign('changeType', $changeType);
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $reason_array = [];
        foreach (EnumConfig::E_ResourceChangeReason as $changeReason) {
            if ($changeReason != EnumConfig::E_ResourceChangeReason['DEFAULT']){
                $reason_array[$changeReason] = EnumConfig::E_ResourceChangeReasonName[$changeReason];
            }
        }

        $this->assign('reason_array', $reason_array);
        $this->assign('reason', $reason);
        $this->display();
    }

    // 导出
    public function plat_data_record_csrv_data()
    {
        #获取充值数据
        $recharge_data = $this->get_recharge();
        #获取代理信息
        $agent_data = $this->get_agent();
        #获取玩家数据
        $user_data = $this->get_user();
        #获取平台数据
        $plat_data = $this->get_plat();
        #获取金币统计
        $money_data = $this->get_money();
        #获取房卡统计
        $jewels_data = $this->get_jewels();
        #获取事件统计
        $count_data = $this->get_count();

        unset($money_data[8], $jewels_data[6]);

        $data = array_merge($recharge_data, $agent_data, $user_data, $plat_data, $money_data, $jewels_data, $count_data);

        // echo '<pre>';
        // print_r($data);die;

        $filename = "战绩数据.csv";
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename='$filename'");
        header('Cache-Control: max-age=0');
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息

        $head = array(
            // 玩家数据
            '玩家在线充值总金额',
            '代理分成总金额',
            '平台充值净收入金额',
            '玩家在线充值金币数',
            '玩家在线充值钻石数',
            '系统后台充值金币数',
            '系统后台充值钻石数',
            '系统后台提取金币数',
            '系统后台提取钻石数',

            // 代理数据
            '平台代理总人数',
            '代理可提现金额',
            '代理已提现金额',
            '抽水金币代理分成总额',
            '已提现抽水金币总额',
            '提现抽水折合人民币',

            // 玩家数据
            '平台真实玩家数',
            '平台机器人数量',
            '当前在线玩家数',
            '玩家钻石总数',
            '玩家身上金币总数',
            '玩家银行金币总数',
            '机器人身上金币数',
            '机器人银行金币数',
            '平台金币总数',
            '平台钻石总数',

            // 平台数据
            '平台消耗金币总数',
            '平台消耗钻石总数',
            '已开钻石场总数',
            '游戏输赢金币总数',
            '目前活跃玩家总数',

            // 金币统计
            '玩家签到获得金币',
            '玩家抽奖获得金币',
            '领取救济金金币数',
            '玩家注册赠送金币',
            '魔法表情消耗金币',
            '玩家转赠消耗金币',
            '实物兑换消耗金币',
            '游戏开局消耗金币',

            // 钻石统计
            '玩家签到获得钻石',
            '玩家抽奖获得钻石',
            '实物兑换获得钻石',
            '玩家注册赠送钻石',
            '发送广播消耗钻石',
            '游戏开局消耗钻石',

            // 事件统计
            '玩家登录总次数',
            '玩家反馈总次数',
            '玩家分享总次数',
            '玩家签到总次数',
            '玩家转赠总次数',
            '玩家抽奖总次数',
            '发送广播总次数',
            '魔法表情总次数',
            '实物兑换总次数',
            '绑定代理用户数',
            '领取救济金总次数',
        );

        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }

        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);
        fputcsv($fp, $data);

        /*foreach ($data as $k => $v) {
    foreach ($data[$k] as $z => $i) {
    $data[$k][$z] = iconv('utf-8', 'gbk', $i);
    }
    }

    foreach ($data as $k => $v) {
    fputcsv($fp, $data[$k]);
    }*/
    }

}
