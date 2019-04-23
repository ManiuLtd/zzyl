<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;


class CommissionController extends AgentController
{
    protected $xiaji = '';
    const MODEL = 'Admin';

    const ARR_PAGE = [1 => ['word' => '15条', 'val' => 15, ], 2 => ['word' => '50条', 'val' => 50, ],  3 => ['word' => '150条', 'val' => 150, ], ];

    public function index() {
        echo 'hello';
    }

    protected function getPage() {

    }

    public function moneyChangeRecord() {
    	$page = I('p', 1);
    	$limit = I("limit", self::ARR_PAGE[1]['val']);
        $searchType = I("searchType");
        $searchUserType = I("searchUserType");
        $search = trim(I("search"));

        $starttime = urldecode(I('starttime'));
        $endtime = urldecode(I('endtime'));

        $arrSearchType = [
            0 => [
                'word' => '请选择',
                'val' => 0,
            ],
            1 => [
                'word' => '赢家id',
                'val' => 1,
            ],
            2 => [
                'word' => '代理id',
                'val' => 2,
            ],
            3 => [
                'word' => '抽水记录id',
                'val' => 3,
            ],
        ];

        $arrSearchUserType = [
            0 => [
                'word' => '请选择',
                'val' => 0,
            ],
            1 => [
                'word' => '代理',
                'val' => 1,
            ],
            2 => [
                'word' => '运营商',
                'val' => 2,
            ],
        ];

        $arrWhere = [];
        $res = validSearchTimeRange($starttime, $endtime);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['a.time'] = $res['data'];
        }
//        if (!empty($starttime) && !empty($endtime)) {
//            $starttime = strtotime($starttime);
//            $endtime = strtotime($endtime);// + 24 * 3600 - 1;
//            $where['a.time'] = ['between', [$starttime, $endtime]];
//        } elseif(!empty($starttime)) {
//            $this->error('请选择结束时间');
//        } elseif (!empty($endtime)) {
//            $this->error('请选择开始时间');
//        }

        if (isset($arrSearchType[$searchType])) {
            switch ($arrSearchType[$searchType]['val']) {
                case 1:
                    $arrWhere['a.recharge_userid'] = $search;
                    break;
                case 2:
                    $arrWhere['a.agent_userid'] = $search;
                    break;
                case 3:
                    $arrWhere['a.foreign_key_id'] = $search;
                    break;
                default:
                    break;
            }
        }
        //代理或运营商
        if (isset($arrSearchUserType[$searchUserType])) {
            switch ($arrSearchUserType[$searchUserType]['val']) {
                case 1:
                    $arrWhere['a.get_amount_user_type'] = $searchUserType;
                    break;
                case 2:
                    $arrWhere['a.get_amount_user_type'] = $searchUserType;
                    break;
                default:
                    break;
            }
        }

    	$listCommission = D(self::MODEL . "/Commission")->getList($arrWhere, $page, $limit);
        foreach ($listCommission as $k => &$v) {
            $v['recharge_amount'] = FunctionHelper::MoneyOutput($v['recharge_amount']);
            $v['agent_commission'] = FunctionHelper::MoneyOutput($v['agent_commission']);
        }
        // implode(glue, pieces)
        $count = D(self::MODEL . "/Commission")->alias('a')->where($arrWhere)->count();
        $pager = new \Think\Page($count, $limit);
        // var_export( $pager);exit;
        //前端显示的字段
        $show_list = [
            'id' => [
                'key' => 'id',
                'title' => 'id',
            ],
            'recharge_userid' => [
                'key' => 'recharge_userid',
                'title' => '赢家id',
                'href' => U('User/user_info',array('userid'=>'recharge_userid')),
                'replace_href' => 'recharge_userid',
            ],
            'name' => [
                'key' => 'name',
                'title' => '赢家昵称',
            ],
            'recharge_amount' => [
                'key' => 'recharge_amount',
                'title' => '抽水总额',
            ],
            'agent_commission' => [
                'key' => 'agent_commission',
                'title' => '代理佣金',
            ],
            'agent_username' => [
                'key' => 'agent_username',
                'title' => '代理用户',
            ],
            'agent_userid' => [
                'key' => 'agent_userid',
                'title' => '代理id',
                'href' => U('User/user_info',array('userid'=>'agent_userid')),
                'replace_href' => 'agent_userid',
            ],
            'day' => [
                'key' => 'day',
                'title' => '日期',
            ],
            // 'commission_type' => [
            //     'key' => 'commission_type',
            //     'title' => '抽水类型',
            // ]
        ];
        $this->assign([
            'isHideNavbar' => I("isHideNavbar", 0),//是否隐藏navbar 0否 1是
            'starttime' => $starttime,
            'endtime' => $endtime,
            'show_list' => $show_list,
            'show_data' => $listCommission,
            '_page' => $pager->show(),
            'searchType' => $searchType,
            'arrSearchType' => $arrSearchType,
            'search' => $search,
            'limit' => $limit,
            'searchUserType' => $searchUserType,
            'arrSearchUserType' => $arrSearchUserType,
            'page_select' => self::ARR_PAGE,
        ]);
    	// var_export($listCommission);
        $this->display('Commission/commissionChangeRecord');
    }

    public function data() {
        $data =             [
            'sum' => $this->getSumCommission(),
            'agentSum' => $this->getAgentCommission(),
            'operatorSum' => $this->getOperatorCommission(),
            'allBackRechargeMoney' => D('Statistics')->allBackRechargeMoney(),
            'allBackUnRechargeMoney' => D('Statistics')->allBackUnRechargeMoney(),
            'allPayRechargeMoney' => D('Statistics')->allPayRechargeMoney(),
//            'allAgentRechargeMoney' => D('Statistics')->allAgentRechargeMoney(),
            'allSignMoney' => D('Statistics')->allSignMoney(),
            'allCreateRoomMoney' => D('Statistics')->allCreateRoomMoney(),
            'allRobotMoney' => D('Statistics')->allRobotMoney(),
            'getSumNotOnlyCommission' => $this->getSumNotOnlyCommission(),
            'configLeftBar' => EnumConfig::E_AdminPageHideType['LEFT_BAR'],
            'configRecourceSign' => EnumConfig::E_ResourceChangeReason['SIGN'],
            'configRecourceBackRecharge' => EnumConfig::E_ResourceChangeReason['BACK_RECHARGE'],
            'configRecourceBackUnRecharge' => EnumConfig::E_ResourceChangeReason['BACK_UNRECHARGE'],
            'configRecourceChangeReasonCreateRoom' => EnumConfig::E_ResourceChangeReason['CREATE_ROOM'],
            'configPayRecharge' => EnumConfig::E_ResourceChangeReason['PAY_RECHARGE'],
        ];
        $this->assign(
            $data
        );
//        var_export($data);
        $this->display();
    }


    //获取总抽水(包括未分佣)
    public function getSumNotOnlyCommission() {
        $res = M()->query('select sum(cash_withdrawal) as count from user_cash_application where cash_status = 2');
        return $res[0]['count'];
    }
    //获取总抽水
    /*public function getSumCommission() {
        $res = M()->query('select sum(changeMoney) as count from statistics_moneychange where reason = ' . EnumConfig::E_ResourceChangeReason['ROOM_PUMP_CONSUME'] . ' and status = 1');
        return FunctionHelper::MoneyOutput(abs($res[0]['count']));
    }*/
    /*public function getSumCommission() {
        $res = M()->query('select sum(changeMoney) as count from statistics_moneychange where reason = ' . EnumConfig::E_ResourceChangeReason['ROOM_PUMP_CONSUME'] . ' and status = 1');
        return FunctionHelper::MoneyOutput(abs($res[0]['count']));
    }*/

    //获取金币兑换总额
    public function getSumCommission() {
        $res = M()->query('select sum(cash_withdrawal) as count from user_cash_application where cash_status = 2');
        return $res[0]['count'];
    }

    //获取代理佣金总额
    /*public function getAgentCommission() {
        $res = M()->query('select sum(agent_commission) as count from ' . MysqlConfig::Table_web_recharge_commission . ' where get_amount_user_type = ' . EnumConfig::E_CommissionUserType['AGENT']);
        return FunctionHelper::MoneyOutput($res[0]['count']);
    }*/

    //代理奖励兑换金币总额
    public function getAgentCommission() {
        $res = M()->query('select sum(apply_amount) as count from ' . MysqlConfig::Table_web_agent_apply_pos . ' where status = 1 and withdrawals = 1');
        return $res[0]['count']/100;
    }

    //代理奖励兑换账户总额
    public function getOperatorCommission() {
        $res = M()->query('select sum(apply_amount) as count from ' . MysqlConfig::Table_web_agent_apply_pos . ' where status = 1 and withdrawals = 2');
        return $res[0]['count']/100;
    }

}


