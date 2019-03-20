<?php
namespace Admin\Controller;
use config\EnumConfig;
use config\ErrorConfig;
use config\GeneralConfig;
use config\MysqlConfig;
use function foo\func;
use model\AgentModel;
use model\UserModel;
use helper\FunctionHelper;

class MallController extends AdminController
{
    //充值记录
    public function recharge_record()
    {
        //获取所有的充值记录
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['p.handleTime'] = ['between', [$start, $stop]];
        // } else {
        //     if (!$search && !$type) {
        //         $start = strtotime(date('Y-m-d', time()));
        //         $stop = $start + 24 * 3600 - 1;
        //         $where['p.handleTime'] = ['between', [$start, $stop]];
        //     }
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['p.handleTime'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['p.userID'] = ['like', "%{$search}%"];
                    break;
                case 2:
                    $where['u.name'] = ['like', "%{$search}%"];
                    break;
            }
        } else {
            $search = '';
        }
        $where['p.status'] = EnumConfig::E_OrderStatus['GIVE'];
        $count = M()->table('web_pay_orders as p')
            ->join('left join userInfo as u on u.userID=p.userID')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 15);

        $res = M()->table('web_pay_orders as p')
            ->join('left join userInfo as u on u.userID=p.userID')
            ->where($where)
            ->field('p.*,u.name')
            ->order('p.handleTime desc')
            ->select();

        foreach ($res as $k => &$v) {
            //玩家信息
            $userID = $v['userid'];
            $user = UserModel::getInstance()->getUserInfo($userID);
            //判断我的类型
            $agentMember = AgentModel::getInstance()->getAgentMemberByUserID($userID);
            $user['user_type_name'] = '普通用户';
            $user['bind_agent_id'] = '无';
            $user['bind_agent_userid'] = '无';
            $user['bind_agent_username'] = '无';
            $user['bind_agent_type_name'] = '无';
            if ($user['isVirtual'] == EnumConfig::E_GameUserType['ROBOT']) {
                $user['user_type_name'] = '机器人';
            } elseif (!empty($agentMember)) {
                $user['user_type_name'] = EnumConfig::E_AgentLevelName[$agentMember['agent_level']];;
            }

            //获取绑定代理信息
            $agentBind = AgentModel::getInstance()->getAgentBindByUserID($userID);
            if (!empty($agentBind) && !empty($agentBind['agentID'])) {
                $user['bind_agent_id'] = $agentBind['agentID'];
                $bind_agent = AgentModel::getInstance()->getAgentMemberByAgentID($user['bind_agent_id']);
                if (!empty($bind_agent)) {
                    $user['bind_agent_userid'] = $bind_agent['userid'];
                    $user['bind_agent_username'] = $bind_agent['username'];
                    $user['bind_agent_type_name'] = EnumConfig::E_AgentLevelName[$bind_agent['agent_level']];
                }
            }
            $v['user'] = $user;
            $v['buy_type_name'] = EnumConfig::E_ResourceTypeName[$v['buytype']];
            $v['recharge_amount'] = sprintf('%.2f', $v['consumenum'] / 100);

            $v['all_agent_commission'] = 0;
            $v['bind_agent_commission'] = 0;
            //所有代理收益
            if (!empty($agentMember) || !empty($agentBind)) {
                $v['all_agent_commission'] = sprintf('%.2f', M()->table('web_recharge_commission')->where(['recharge_order_sn' => $v['order_sn']])->getfield('sum(agent_commission)') / 100);
            }
            //绑定代理收益
            if (!empty($agentBind)) {
                $v['bind_agent_commission'] = sprintf('%.2f', M()->table('web_recharge_commission')->where(['recharge_order_sn' => $v['order_sn'], 'agent_userid' => $user['bind_agent_userid']])->getfield('agent_commission') / 100);
            }
            //平台收益
            $v['plat_commission'] = ($v['recharge_amount'] - $v['all_agent_commission']);
        }

        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //数据统计
    public function data_count()
    {
        //七天的数据
        $week = [];
        $begin = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //今天开始时间戳
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $week = array_reverse($week);

        //最近十二个月数据
        $year = $this->getLastTimeArea(date('Y', time()), date('m', time()), 12);
        $year = array_reverse($year);

        $recharge_data7 = [];
        $data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600 - 1;
            $num = M('pay_orders')->where(['create_time' => ['between', [$v, $end]], 'status' => EnumConfig::E_OrderStatus['GIVE']])->sum('consumeNum');
            $recharge_data7['num'][] = sprintf("%.2f", ((int)$num ? $num : 0) / 100);
            $data7[] = date("m-d", $v);
        }
        $this->assign('recharge_data7', $recharge_data7);
        $this->assign('data7', $data7);

        $recharge_data12 = [];
        $data12 = [];
        foreach ($year as $k => $v) {
            $num = M('pay_orders')->where(['create_time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]], 'status' => 1])->sum('consumeNum');
            $recharge_data12['num'][] = sprintf("%.2f", ((int)$num ? $num : 0) / 100);
            $data12[] = $year[$k]['month'];
        }
        $this->assign('recharge_data12', $recharge_data12);
        $this->assign('data12', $data12);


        //获取平台充值总额，平台净收入，单次充值最大值，当月充值总额，当月平台收入总额，
        //充值总额
        $recharge_all = sprintf("%.2f", M('pay_orders')->where(['status' => EnumConfig::E_OrderStatus['GIVE']])->sum('consumeNum') / 100);
        //历史充值额度最大的金额
        $recharge_max = sprintf("%.2f", M('pay_orders')->where(['status' => EnumConfig::E_OrderStatus['GIVE']])->max('consumeNum') / 100);
        $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        //本月充值总额
        $month_recharge_all = sprintf("%.2f", M('pay_orders')->where(['create_time' => ['between', [$beginThismonth, $endThismonth]], 'status' => EnumConfig::E_OrderStatus['GIVE']])->sum('consumeNum') / 100);

        //用户累计充值最多的充值金额
        $user_recharge_max = M()->query('select max(r.s) as m from (select sum(consumeNum) as s from web_pay_orders as r WHERE status = ' . EnumConfig::E_OrderStatus['GIVE'] . ' group by userID) as r');
        $user_recharge_max = sprintf("%.2f", $user_recharge_max[0]['m'] / 100);
        //代理总收益
        $commission_all = sprintf("%.2f", (M('recharge_commission')->sum('agent_commission'))) / 100;
        //平台总收益
        $plat_all = sprintf("%.2f", $recharge_all - $commission_all);
        //本月代理收益
        $month_commission_all = sprintf("%.2f", (M('recharge_commission')->where(['time' => ['between', [$beginThismonth, $endThismonth]]])->sum('agent_commission'))) / 100;
        //本月平台收益
        $month_plat_all = sprintf("%.2f", ($month_recharge_all - $month_commission_all));
        $this->assign('recharge_all', $recharge_all);
        $this->assign('recharge_max', $recharge_max);
        $this->assign('month_recharge_all', $month_recharge_all);
        $this->assign('user_recharge_max', $user_recharge_max);
        $this->assign('plat_all', $plat_all);
        $this->assign('month_plat_all', $month_plat_all);
        $begin = mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天开始时间戳
        //今日充值金额
        $today_recharge_all = sprintf("%.2f", M('pay_orders')->where(['create_time' => ['between', [$begin, $begin + 24 * 3600]], 'status' => EnumConfig::E_OrderStatus['GIVE']])->sum('consumeNum') / 100);
        $today_recharge_max = sprintf("%.2f", M('pay_orders')->where(['create_time' => ['between', [$begin, $begin + 24 * 3600]], 'status' => EnumConfig::E_OrderStatus['GIVE']])->max('consumeNum') / 100);
        $this->assign('today_recharge_all', $today_recharge_all);
        $this->assign('today_recharge_max', $today_recharge_max);

        //充值金额数分布图
        $level1_recharge = sprintf("%.2f", M('pay_orders')
                ->alias('p')
                ->join('left join web_agent_member as a on p.userID=a.userid')
                ->where(['p.status' => EnumConfig::E_OrderStatus['GIVE'], 'a.agent_level' => 1])
                ->sum('p.consumeNum') / 100);
        $level2_recharge = sprintf("%.2f", M('pay_orders')
                ->alias('p')
                ->join('left join web_agent_member as a on p.userID=a.userid')
                ->where(['p.status' => EnumConfig::E_OrderStatus['GIVE'], 'a.agent_level' => 2])
                ->sum('p.consumeNum') / 100);
        $level3_recharge = sprintf("%.2f", M('pay_orders')
                ->alias('p')
                ->join('left join web_agent_member as a on p.userID=a.userid')
                ->where(['p.status' => EnumConfig::E_OrderStatus['GIVE'], 'a.agent_level' => 3])
                ->sum('p.consumeNum') / 100);
        $user_recharge = sprintf("%.2f", $recharge_all - ($level1_recharge + $level2_recharge + $level3_recharge));
        //充值人数
        $user_count = M('pay_orders')->where(['create_time' => ['between', [$begin, $begin + 24 * 3600]], 'status' => EnumConfig::E_OrderStatus['GIVE']])->group('userID')->select();
        $user_count = $user_count ? count($user_count) : 0;
        $this->assign('user_count', $user_count);
        $this->assign('user_recharge', $user_recharge);
        $this->assign('level1_recharge', $level1_recharge);
        $this->assign('level2_recharge', $level2_recharge);
        $this->assign('level3_recharge', $level3_recharge);
        $this->display();
    }

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
            //$endMonth = strtotime($temYear.'/'.($temMonth + 1).'/01') - 86400;//该月的月末时间戳
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


    //商品管理
    public function goods()
    {
        //获取所有商品信息
        $buyType = I('buyType');
        if ($buyType != '') {
            $where = [
                'buyType' => $buyType,
            ];
        }
        $data = D('Data')->get_all_data('pay_goods', $where, 8, '');
//        FunctionHelper::moneyInArrayOutput($data, GeneralConfig::TIMES_RESOURCE_TYPE);
//        dump($data);
        foreach ($data['_data'] as $k => &$v) {
            //金币倍数
            if ($v['buytype'] == EnumConfig::E_PayGoodsBuyType['MONEY']) {
                $v['buynum'] = FunctionHelper::MoneyOutput($v['buynum']);
            }
            //金币倍数
            if ($v['consumetype'] == EnumConfig::E_PayGoodsBuyType['MONEY']) {
                $v['consumenum'] = FunctionHelper::MoneyOutput($v['consumenum']);
            }
            //金币倍数
            if ($v['buytype'] == EnumConfig::E_ResourceType['RMB']) {
                $v['buynum'] = FunctionHelper::MoneyOutput($v['buynum'], EnumConfig::E_ResourceType['RMB']);
            }
            //金币倍数
            if ($v['consumetype'] == EnumConfig::E_ResourceType['RMB']) {
                $v['consumenum'] = FunctionHelper::MoneyOutput($v['consumenum'], EnumConfig::E_ResourceType['RMB']);
            }
        }
//         var_export($data);
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //添加商品
    public function add_goods()
    {
        if (IS_POST) {
            $post = I('post.');

            if ($post['buyType'] == $post['consumeType']) {
                $this->error('购买类型和出售类型不能相同');
            }
            if (!is_numeric($post['buyNum']) || $post['buyNum'] < 0 || !is_numeric($post['consumeNum']) || $post['consumeNum'] < 0) {
                $this->error('商品或消耗数量格式错误');
            }
            //金币倍数
            if ($post['buyType'] == EnumConfig::E_PayGoodsBuyType['MONEY']) {
                $post['buyNum'] = FunctionHelper::MoneyInput($post['buyNum'], $post['buyType']);
            }
            //金币倍数
            if ($post['consumetype'] == EnumConfig::E_PayGoodsBuyType['MONEY']) {
                $post['consumeNum'] = FunctionHelper::MoneyOutput($post['consumeNum'], $post['consumeType']);
            }
            if (M('pay_goods')->add($post)) {
                $this->success('添加成功');
                exit();
            } else {
                $this->error('添加失败');
                exit();
            }
        }
        $this->display();
    }

    //删除商品
    public function goods_del()
    {
        $id = I('post.id');
        if (empty($id)) {
            $this->error('参数错误');
        }
        if (M('pay_goods')->where(['id' => $id])->delete()) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //商品编辑
    public function pay_goods_config_edit()
    {
        $data = I('post.');
//        $data['id'] = I('id');
//        $data['buyGoods'] = I('buyGoods');
        if (in_array(I('buyType'), [1, 2, 3, 4])) {
            $data['buyType'] = I('buyType');
        }
//        $data['buyNum'] = I('buyNum');
//        $data['buyType'] = I('buyType');
//        $data['consumeNum'] = I();

        if (!isset($data['consumeNum']) || !isset($data['consumeType'])) {

        }

        if ($data['buyType'] == $data['consumeType']) {
            $this->error('购买类型和出售类型不能相同');
        }
//        var_export($data);

        //金币倍数
        $data['buyNum'] = FunctionHelper::MoneyInput($data['buyNum'], $data['buyType']);

        //金币倍数
        $data['consumeNum'] = FunctionHelper::MoneyInput($data['consumeNum'], $data['consumeType']);
//        var_export($data);
        $data['consumeGoods'] = I('consumeGoods');
        if (in_array(I('consumeType'), [0, 1, 2])) {
            $data['consumeType'] = I('consumeType');
        }
        if (!in_array($data['status'], [0, 1])) {
            $this->error('status format error');
        }
        if (!in_array($data['is_New'], [0, 1])) {
            $this->error('is_New format error');
        }
        if (!in_array($data['is_Hot'], [0, 1])) {
            $this->error('is_Hot format error');
        }
        $data['appleID'] = I('appleID');

        if (!preg_match("/^[1-9][0-9]*$/", $data['buyNum']) || !preg_match("/^[1-9][0-9]*$/", $data['consumeNum'])) {
            $this->error('商量数量和消耗资源数量必须为正整数,您的输入为' . $data['buyNum'] . '--' . $data['consumeNum']);
        }
//        if ($data['consumeType'] == 0) {
//            $data['consumeNum'] = $data['consumeNum'] * 100;
//        }
        if (empty($data['buyGoods']) || empty($data['consumeGoods'])) {
            $this->error('商品名称和消耗资源名称不能为空');
        }
        $res = M('pay_goods')->save($data);
        if ($res) {
            operation_record(UID, '编辑了商城商品');
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
    }

    /**
     * 订单管理
     * @DateTime 2017-12-08
     */
    public function orders()
    {
        $where = [];
        $type = I('type');
        $search = I('search');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['create_time'] = ['between', [$start, $stop]];
        // } else {
        //     if (!$search && !$type) {
        //         $start = strtotime(date('Y-m-d', time()));
        //         $stop = $start + 24 * 3600 - 1;
        //         $where['create_time'] = ['between', [$start, $stop]];
        //     }
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['create_time'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['name'] = $search;
                    break;
                case 3:
                    $where['order_sn'] = $search;
                    break;
            }
        }

        $status = I('status', 1);
        #获取所有的订单记录
        switch ($status) {
            case 1:
                $where['status'] = 1;
                break;
            case 2:
                $where['status'] = 0;
                break;
            case 3:
                $where['status'] = 3;
                break;
            case 4:
                $where['status'] = 4;
                break;
        }
        $where['consumeType'] = 0;
        var_dump($where);exit;
        $data = D('Data')->get_all_data('pay_orders', $where, 10, 'id desc');
        foreach ($data['_data'] as &$v) {
            $v['buynum'] = FunctionHelper::moneyOutput($v['buynum'], $v['buytype']);
            $v['consumenum'] = FunctionHelper::moneyOutput($v['consumenum'], $v['consumetype']);
            $v['name'] = M()->table('userInfo')->where(['userID' => $v['userid']])->getfield('name');
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->assign('status', $status);
        $this->display();
    }

    public function replenishment()
    {
        $data['id'] = I('id');
        $data['status'] = 1;
        $data['pay_desc'] = '支付成功,数据处理成功';
        $order = M('pay_orders')->find($data['id']);
        //发送消息
        $socket = \Socket::get();
        $send = new \SendFunction();
        if ($socket->connet == false) {
            $this->error('充值失败，原因：服务连接不上');
        }
        $rechargePacket = $send->makeRechargePacket($order['userid'], $order['buynum'], $order['buytype']);
        $res = $socket->send($send::RechargeID, 1, 0, $rechargePacket);
        if (!$res) {
            $this->error('充值失败 原因：向服务器发送请求失败');
        }
        $read = unpack('i*', $socket->read_data(1024));
        if (!$read) {
            $this->error('充值失败 原因：接收服务器消息失败');
        }
        if ($read[4] != 0) {
            $this->error('充值失败 原因:接收失败，服务器返回状态码为' . $read[4]);
        }
        $res = M('pay_orders')->save($data);
        $data = [
            'recharge_userid' => $order['userid'],
            'recharge_name' => $order['name'],
            'user_type' => 3,
            'recharge_amount' => $order['consumenum'],
            'status' => 1,
            'buytype' => $order['buytype'],
            'buy_num' => $order['buy_num'],
            'recharge_time' => time(),
        ];
        $res = M('recharge_commission')->add($data);
        if ($res) {
            $this->success('补货成功');
        } else {
            $this->error('补货失败');
        }
    }

    public function del_pay_config()
    {
        $type = I('post.id', '', 'int');
        empty($type) && $this->error('参数错误');
        if (M('pay_config')->where(['type' => $type])->delete()) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //支付配置信息
    public function payConfig()
    {
        $dataList = M('pay_config')->select();
        $this->assign('dataList', $dataList);
        array_walk($dataList, function(&$v, $k) {
            $v['payTypeName'] = EnumConfig::E_PayTypeName[$v['type']];
        });
        $payType = EnumConfig::E_PayType;
        $payTypeList = [];
        foreach ($payType as $k => $v) {
            $payTypeList[] = [
                'type' => $v,
                'typeName' => EnumConfig::E_PayTypeName[$v],
            ];
        }
        $this->assign([
           'payTypeList' => $payTypeList,
        ]);
//        var_export($dataList);
        $this->display();
    }

    //修改或者添加配置信息
    public function edit_pay_config()
    {
        if (IS_POST) {
            $post = I('post.');
            $cation = I('get.cation');
            if (empty($post['type'])) {
                $this->error('支付类型不能为空');
            }
            if (empty($post['name'])) {
                $this->error('支付名字不能为空');
            }
            if (empty($post['app_id'])) {
                $this->error('支付应用ID不能为空');
            }
            if (empty($post['mch_id'])) {
                $this->error('支付商户ID不能为空');
            }
            $resPayConfig = M()->table(MysqlConfig::Table_web_pay_config)->where(['type' => $post['type'], 'status' => EnumConfig::E_PayTypeStatus['OPEN']])->find();
            if ($resPayConfig && $post['id'] != $resPayConfig['id'] && $post['status'] == EnumConfig::E_PayTypeStatus['OPEN']) {
                $this->error('同一类型支付，只能有一个启用');
            }
            //添加商品
            if ($cation == 'add') {

                if (M('pay_config')->add($post)) {
                    $this->success('添加成功');
                } else {
                    $this->error('添加失败');
                }
            } else {
                //修改商品
                if (M('pay_config')->save($post)) {
                    $this->success('修改成功');
                } else {
                    $this->error('修改失败');
                }
            }

        }

        $payType = EnumConfig::E_PayType;
        $payTypeList = [];
        foreach ($payType as $k => $v) {
            $payTypeList[] = [
                'type' => $v,
                'typeName' => EnumConfig::E_PayTypeName[$v],
            ];
        }
        $this->assign([
            'payTypeList' => $payTypeList,
        ]);

        $this->display();
    }
}
