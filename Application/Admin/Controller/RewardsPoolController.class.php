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

            $v['gamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['gamewinmoney']);
            $v['allgamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allgamewinmoney']);
            $v['sumgamewinmoney'] = $v['gamewinmoney'] + $v['allgamewinmoney'];
            $v['poolmoney'] = FunctionHelper::MoneyOutput((int)$v['poolmoney']);
            $v['percentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['percentagewinmoney']);
            $v['allpercentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allpercentagewinmoney']);
            $v['otherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['otherwinmoney']);
            $v['allotherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['allotherwinmoney']);
            $v['platformctrlpercent'] = (int)$v['platformctrlpercent'];
            $v['realpeoplefailpercent'] = (int)$v['realpeoplefailpercent'];
            $v['realpeoplewinpercent'] = (int)$v['realpeoplewinpercent'];
            $v['minpondmoney'] = (int)$v['minpondmoney'] /100;
            $v['maxpondmoney'] = (int)$v['maxpondmoney'] /100;
            $v['platformbankmoney'] = FunctionHelper::MoneyOutput((int)$v['platformbankmoney']);
            $v['recoverypoint'] = FunctionHelper::MoneyOutput((int)$v['recoverypoint']);
//            var_export($v);
        }
//        var_dump($listCommission);
        // implode(glue, pieces)
        $count = $res['_count'];
        $pager = new \Think\Page($count, $limit);
        // var_export( $pager);exit;
        //type = ['text', 'option', 'input']
        // $show_list = [
        //     'input' => [
        //         'key' => 'input',
        //         'title' => '输入测试',
        //         'type' => ['type' => 'input', 'name' => 'testName']
        //     ],
//        'option' => [
//            'key' => 'option',
//            'title' => '选项测试',
//            'type' => ['type' => 'option', 'name' => 'testName', 'options' => [['value' => '1', 'text' => '选项1'],['value' => 2, 'text' => '选项2']]],
//        ],
        // ];
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
            'sumgamewinmoney' => [
                'key' => 'sumgamewinmoney',
                'title' => '实时奖池',
            ],
            'platformbankmoney' => [
                'key' => 'platformbankmoney',
                'title' => '平台银行储蓄',
            ],
            'percentagewinmoney' => [
                'key' => 'percentagewinmoney',
                'title' => '今日累计抽水',
            ],
            'allpercentagewinmoney' => [
                'key' => 'allpercentagewinmoney',
                'title' => '今日前累计抽水',
            ],
            'recoverypoint' => [
                'key' => 'recoverypoint',
                'title' => '平台回收金币的结点',
                'type' => ['type' => 'input', 'name' => 'recoverypoint', 'attribution' => 'style="width:80px;"']
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
                'title'=> '奖池金额下限',
                'type' => ['type' => 'input', 'name' => 'minPondMoney', 'attribution' => 'style="width:80px;"'],
            ],
            'maxPondMoney' => [
                'key' => 'maxpondmoney',
                'title'=> '奖池金额上限',
                'type' => ['type' => 'input', 'name' => 'maxPondMoney', 'attribution' => 'style="width:80px;"'],
            ],
//            'poolmoney' => [
//                'key' => 'poolmoney',
//                'title' => '奖池',
//                'type' => ['type' => 'input', 'name' => 'poolmoney', 'attribution' => 'style="width:100px;"'],
//            ],
//            'realpeoplefailpercent' => [
//                'key' => 'realpeoplefailpercent',
//                'title' => '真人玩家输概率',
//            ],
            'is_hide' => [
                'key' => 'is_hide',
                'title' => '是否隐藏',
                'type' => ['type' => 'option', 'name' => 'is_hide', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
            ],
             'option' => [
                 'key' => 'is_recommend',
                 'title' => '是否推荐',
                 'type' => ['type' => 'option', 'attribution' => 'style="width:100px;border:none;" readonly="readonly"',  'name' => 'is_Recommend', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
             ],
//            'realpeoplewinpercent' => [
//                'key' => 'realpeoplewinpercent',
//                'title' => '真人玩家赢概率',
//            ],
            // 'agent_userid' => [
            //     'key' => 'agent_userid',
            //     'title' => '代理id',
            //     'href' => U('User/user_info',array('userid'=>'agent_userid')),
            //     'replace_href' => 'agent_userid',
            // ],
//            'spotcontrolinfo' => [
//                'key' => 'spotcontrolinfo',
//                'title' => '多点控制',
//            ],
//            'detailinfo' => [
//                'key' => 'detailinfo',
//                'title' => '房间控制详情',
//            ]
            // 'commission_type' => [
            //     'key' => 'commission_type',
            //     'title' => '抽水类型',
            // ]
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
        ->field('a.*,b.name,b.is_Recommend,b.is_hide')
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

    public function rewardsPoolEdit() {
        if ($_POST) {
            $isRecommend = (int)I('is_Recommend');
            $roomID = (int)I('roomID');
            $is_hide = (int)I('is_hide');
            $gameID = M()->query('select gameID from ' . MysqlConfig::Table_roombaseinfo . ' where roomID =' . $roomID . ' limit 1');
//            dump($gameID);exit;
            $resCount = M()->query('select count(*) as cnt, gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and gameID =' . $gameID[0]['gameid']);
            $res = M()->query('select gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and roomID =' . $roomID);
            if (EnumConfig::E_GameRecommend['NO'] == $isRecommend && $resCount[0]['cnt'] <= 1 && $res) {
                $this->error('房间推荐数不能小于1');
            }
            M()->startTrans();
            $result = LobbyModel::getInstance()->updateRewardsPool((int)I('roomID'), [
                'poolMoney' => FunctionHelper::MoneyInput((int)I('poolmoney')),//奖池
                'platformCtrlPercent' => (int)I('platformctrlpercent'),//单点控制千分比
                'realPeopleWinPercent' => (int)I('realpeoplewinpercent'),
                'realPeopleFailPercent' => (int)I('realpeoplefailpercent'),
                'minPondMoney' => (int)I('minPondMoney') *100,
                'maxPondMoney' => (int)I('maxPondMoney') *100,
                'recoveryPoint' => (int)I('recoverypoint') * 100,
                'updateTime' => time(),
            ]);
            $res = M()->table(MysqlConfig::Table_roombaseinfo)->where(['roomID' => $roomID])->save(['is_Recommend' => $isRecommend, 'is_hide' => $is_hide, 'updateTime' => time()]);
            if (empty($res) || !$result) {
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


