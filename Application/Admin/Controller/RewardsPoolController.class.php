<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

use config\ErrorConfig;
use config\MysqlConfig;
use config\EnumConfig;
use helper\FunctionHelper;
use model\LobbyModel;
use manager\RedisManager;

class RewardsPoolController extends AgentController
{
    protected $xiaji = '';
    const MODEL = 'Admin/RewardsPool';
    protected $tableName = MysqlConfig::Table_rewardspool;

    const ARR_PAGE = [1 => ['word' => '15条', 'val' => 15, ], 2 => ['word' => '50条', 'val' => 50, ],  3 => ['word' => '150条', 'val' => 150, ], ];

    public function index() {
        echo 'hello';
    }

    protected function getPage() {

    }

    public function rewardsPool() {
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
                 'word' => 'roomID',
                 'val' => 1,
             ],
             2 => [
                 'word' => 'gameID',
                 'val' => 2,
             ],
        ];

        $arrSearchUserType = [
            0 => [
                'word' => '请选择',
                'val' => 0,
            ],
            // 1 => [
            //     'word' => '代理',
            //     'val' => 1,
            // ],
            // 2 => [
            //     'word' => '运营商',
            //     'val' => 2,
            // ],
        ];

        $arrWhere = [];
        // if (!empty($starttime) && !empty($endtime)) {
        //     $starttime = strtotime($starttime);
        //     $endtime = strtotime($endtime);// + 24 * 3600 - 1;
        //     $where['a.time'] = ['between', [$starttime, $endtime]];
        // }
        
        $res = validSearchTimeRange($starttime, $endtime);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['a.time'] = $res['data'];
        }

        if (isset($arrSearchType[$searchType])) {
            switch ($arrSearchType[$searchType]['val']) {
                case 1:
                    $arrWhere['a.roomID']  = $search;
                    break;
                case 2:
                    $arrWhere['b.gameID'] = $search;
                    break;
                default:
                    break;
            }
        }
        // $arrWhere['gamewinmoney']  = ['neq',0];
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

    	$res = $this->getList($arrWhere, $page, $limit);
        $listCommission = $res['_data'];
//        var_export($listCommission);
//        $arrRoomID = array_column($listCommission, 'roomid');
//        var_export(implode(',', $arrRoomID));
//        $is_recommend = M()->table(MysqlConfig::Table_roombaseinfo)->where(['roomID' => ['in', implode(',', $arrRoomID)]])->select();
//         var_export($listCommission);
        foreach ($listCommission as $k => &$v) {
            $v['gamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['gamewinmoney']); //今日游戏输赢钱
            $v['allgamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allgamewinmoney']); //今日前累计游戏输赢钱
            $v['platformcompensate'] = FunctionHelper::MoneyOutput((int)$v['platformcompensate']); //平台补偿金币
            $v['percentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['percentagewinmoney']);
            $v['allpercentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allpercentagewinmoney']);
            $v['otherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['otherwinmoney']);
            $v['allotherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['allotherwinmoney']);
        }

        $count = $res['_count'];
        $pager = new \Think\Page($count, $limit);

        //前端显示的字段
        $show_list = [
            'roomid' => [
                'key' => 'roomid',
                'title' => 'roomid',
                'type' => ['type' => 'input', 'attribution' => 'style="width:100px;border:none;" readonly="readonly"', 'name' => 'roomID'],
            ],
            'name' => [
                'key' => 'name',
                'title' => '游戏名称',
                // 'href' => U('User/user_info',array('userid'=>'gamewinmoney')),
                // 'replace_href' => 'gamewinmoney',
            ],
            'otherwinmoney' => [
                'key' => 'otherwinmoney',
                'title' => '今日其它方式获得金币',
            ],
            'allotherwinmoney' => [
                'key' => 'allotherwinmoney',
                'title' => '今日前其它方式获得金币',
            ],
            'gamewinmoney' => [
                'key' => 'gamewinmoney',
                'title' => '今日游戏输赢钱',
            ],
            'allgamewinmoney' => [
                'key' => 'allgamewinmoney',
                'title' => '今日前累计游戏输赢钱',
            ],
            'platformcompensate' => [
                'key' => 'platformcompensate',
                'title' => '平台补偿金币',
            ],
            'percentagewinmoney' => [
                'key' => 'percentagewinmoney',
                'title' => '今日累计抽水',
            ],
            'allpercentagewinmoney' => [
                'key' => 'allpercentagewinmoney',
                'title' => '今日前累计抽水',
            ],
        ];
        $this->assign([
            'starttime' => $starttime,
            'endtime' => $endtime,
            'formRequest' => U('RewardsPool/rewardsPoolEdit'),
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
//    	 var_export($listCommission);
        $this->display('Commission/commissionChangeRecord');
    }

    public function getList($where, $page = 1, $limit = 10){
        $data = M()
        ->table($this->tableName)->alias('a')->join('left join ' . MysqlConfig::Table_roombaseinfo . ' b on a.roomID = b.roomID')
        ->where($where)
        ->field('a.*,b.name')
        ->page($page)
        ->limit($limit)
        ->order('a.roomID desc')
        ->select();
        $count = M()
        ->table($this->tableName)->alias('a')->join('left join ' . MysqlConfig::Table_roombaseinfo . ' b on a.roomID = b.roomID')
        ->where($where)
        ->count();
        return ['_data' => $data, '_count' => $count];
    }

    //奖池控制
    public function prizePoolControl() {
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
                'word' => 'roomID',
                'val' => 1,
            ],
            2 => [
                'word' => 'gameID',
                'val' => 2,
            ],
        ];

        $arrSearchUserType = [
            0 => [
                'word' => '请选择',
                'val' => 0,
            ],
        ];

        $arrWhere = [];

        $res = validSearchTimeRange($starttime, $endtime);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['a.time'] = $res['data'];
        }

        if (isset($arrSearchType[$searchType])) {
            switch ($arrSearchType[$searchType]['val']) {
                case 1:
                    $arrWhere['a.roomID']  = $search;
                    break;
                case 2:
                    $arrWhere['b.gameID'] = $search;
                    break;
                default:
                    break;
            }
        }
        // $arrWhere['gamewinmoney']  = ['neq',0];
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

        $arrWhere['a.type'] = 0;
        $res = $this->getList($arrWhere, $page, $limit);
        $listCommission = $res['_data'];

        foreach ($listCommission as $k => &$v) {
            $rewsinfo = RedisManager::getGameRedis()->hGetAll("rewardsPool|".$v['roomid']);
            $v['gamewinmoney'] = FunctionHelper::MoneyOutput((int)$rewsinfo['gameWinMoney']); //今日游戏输赢钱   实时获取
            $v['allgamewinmoney'] = FunctionHelper::MoneyOutput((int)$rewsinfo['allGameWinMoney']); //今日前累计游戏输赢钱  实时获取
            $v['platformcompensate'] = FunctionHelper::MoneyOutput((int)$rewsinfo['platformCompensate']); //平台补偿金币   实时获取
//            实时奖池 = 今日游戏输赢钱 + 今日前累计游戏输赢钱 + 平台补偿金币
//            平台盈利  =  实时奖池 + 平台银行储蓄 - 平台补偿金币
            $v['sumgamewinmoney'] = $v['gamewinmoney'] + $v['allgamewinmoney'] + $v['platformcompensate']; //实时奖池   实时获取
            $v['platformbankmoney'] = FunctionHelper::MoneyOutput((int)$rewsinfo['platformBankMoney']); //平台银行储蓄   实时获取
            $v['platformprofitability'] = $v['sumgamewinmoney'] + $v['platformbankmoney'] - $v['platformcompensate']; //平台盈利

            $v['poolmoney'] = FunctionHelper::MoneyOutput((int)$v['poolmoney']);
            $v['percentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['percentagewinmoney']);
            $v['allpercentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allpercentagewinmoney']);
            $v['otherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['otherwinmoney']);
            $v['allotherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['allotherwinmoney']);
            $v['platformctrlpercent'] = (int)$rewsinfo['platformCtrlPercent']; //单点控制千分比	redis获取
            $v['realpeoplefailpercent'] = (int)$v['realpeoplefailpercent'];
            $v['realpeoplewinpercent'] = (int)$v['realpeoplewinpercent'];
            $v['minpondmoney'] = (int)$v['minpondmoney'] /100;
            $v['maxpondmoney'] = (int)$v['maxpondmoney'] /100;
            $v['recoverypoint'] = FunctionHelper::MoneyOutput((int)$v['recoverypoint']);
            $v['incrementofgoldcoin'] = 0; //平台补偿金币增量
        }

        $count = $res['_count'];
        $pager = new \Think\Page($count, $limit);
        //前端显示的字段
        $show_list = [
            'roomid' => [
                'key' => 'roomid',
                'title' => 'roomid',
                'type' => ['type' => 'input', 'attribution' => 'style="width:100px;border:none;" readonly="readonly"', 'name' => 'roomID'],
            ],
            'name' => [
                'key' => 'name',
                'title' => '游戏名称',
            ],
            'platformprofitability' => [
                'key' => 'platformprofitability',
                'title' => '平台盈利',
            ],
            'sumgamewinmoney' => [
                'key' => 'sumgamewinmoney',
                'title' => '实时奖池',
            ],
            'platformbankmoney' => [
                'key' => 'platformbankmoney',
                'title' => '平台银行储蓄',
            ],
            'recoverypoint' => [
                'key' => 'recoverypoint',
                'title' => "平台回收金币的结点(0表示<br/>默认的800,必须大于0)",
                'type' => ['type' => 'input', 'name' => 'recoverypoint', 'attribution' => 'style="width:80px;"']
            ],
            'incrementofgoldcoin' => [
                'key' => 'incrementofgoldcoin',
                'title' => "实时奖池手动增减量<br/>(正数增加,负数减少)",
                'type' => ['type' => 'input', 'name' => 'incrementofgoldcoin', 'attribution' => 'style="width:80px;"']
            ],
            'platformctrlpercent' => [
                'key' => 'platformctrlpercent',
                'title' => '单点控制千分比',
                'type' => ['type' => 'input', 'name' => 'platformctrlpercent', 'attribution' => 'style="width:80px;"']
            ],
            'realPeopleFailPercent' => [
                'key' => 'realpeoplefailpercent',
                'title' => '真人玩家输概率',
                'type' => ['type' => 'input', 'name' => 'realpeoplefailpercent', 'attribution' => 'style="width:80px;"']
            ],
            'realPeopleWinPercent' => [
                'key' => 'realpeoplewinpercent',
                'title' => '真人玩家赢概率',
                'type' => ['type' => 'input', 'name' => 'realpeoplewinpercent', 'attribution' => 'style="width:80px;"']
            ],
            'minPondMoney' => [
                'key' => 'minpondmoney',
                'title'=> '奖池金额下限<br/>(0表示默认的800)',
                'type' => ['type' => 'input', 'name' => 'minPondMoney', 'attribution' => 'style="width:80px;"'],
            ],
            'maxPondMoney' => [
                'key' => 'maxpondmoney',
                'title'=> '奖池金额上限<br/>(0表示默认的1000)',
                'type' => ['type' => 'input', 'name' => 'maxPondMoney', 'attribution' => 'style="width:80px;"'],
            ],
        ];
        $this->assign([
            'starttime' => $starttime,
            'endtime' => $endtime,
            'formRequest' => U('RewardsPool/rewardsPoolEdit'),
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
//    	 var_export($listCommission);
        $this->display();
    }

    public function rewardsPoolEdit() {
        if ($_POST) {
            M()->startTrans();
            //根据roomid查询出当前房间的平台补偿金币
            $platformCompensate = M()->table(MysqlConfig::Table_rewardspool)->where(['roomID' => I('roomID')])->field('platformCompensate')->find();
            $pmoney = (int)I('incrementofgoldcoin') * 100 + $platformCompensate['platformcompensate'];
            $result = LobbyModel::getInstance()->updateRewardsPool((int)I('roomID'), [
                'poolMoney' => FunctionHelper::MoneyInput((int)I('poolmoney')),//奖池
                'platformCtrlPercent' => (int)I('platformctrlpercent'),//单点控制千分比
                'realPeopleWinPercent' => (int)I('realpeoplewinpercent'),
                'realPeopleFailPercent' => (int)I('realpeoplefailpercent'),
                'minPondMoney' => (int)I('minPondMoney') *100,
                'maxPondMoney' => (int)I('maxPondMoney') *100,
                'recoveryPoint' => (int)I('recoverypoint') * 100,
                'platformCompensate' => $pmoney,
                'updateTime' => time(),
            ]);
            if (!$result) {
                M()->rollback();
                $this->error('修改失败');
            }
            M()->commit();
            $this->success('修改成功');
        }
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

        ];
        $this->assign(
            $data
        );
//        var_export($data);
        $this->display();
    }


    //获取总抽水
    public function getSumCommission() {
        $res = M()->query('select sum(changeMoney) as count from statistics_moneychange where reason = 8');
        return FunctionHelper::MoneyOutput(abs($res[0]['count']));
    }
    //获取代理佣金总额
    public function getAgentCommission() {
        $res = M()->query('select sum(agent_commission) as count from web_recharge_commission');
        return FunctionHelper::MoneyOutput($res[0]['count']);
    }
    //获得平台抽水
    public function getOperatorCommission() {
        return $this->getSumCommission() - $this->getAgentCommission();
    }

}


