<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;

class RewardsPoolDetailController extends AgentController
{
    protected $xiaji = '';
//    protected $tableName = 'statisticsRewardspool';
    const MODEL = 'Admin/statisticsRewardspool';

    const ARR_PAGE = [1 => ['word' => '15条', 'val' => 15, ], 2 => ['word' => '50条', 'val' => 50, ],  3 => ['word' => '150条', 'val' => 150, ], ];

    public function index() {
        echo 'hello';
    }

    protected function getPage() {

    }

    public function moneyChangeRecord() {
        $page = I('page', 1);
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
//            1 => [
//                'word' => '赢家id',
//                'val' => 1,
//            ],
//            2 => [
//                'word' => '代理id',
//                'val' => 2,
//            ],
//            3 => [
//                'word' => '抽水记录id',
//                'val' => 3,
//            ],
        ];

        $arrSearchUserType = [
            0 => [
                'word' => '请选择',
                'val' => 0,
            ],
//            1 => [
//                'word' => '代理',
//                'val' => 1,
//            ],
//            2 => [
//                'word' => '运营商',
//                'val' => 2,
//            ],
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

//        $listCommission = D(self::MODEL)->getList($arrWhere, $page, $limit);
        $listCommission = $this->getList($arrWhere, $page, $limit);
//        dump($listCommission);
        foreach ($listCommission as $k => &$v) {
            $v['gamewinmoney'] = FunctionHelper::MoneyOutput($v['gamewinmoney']);
            $v['otherwinmoney'] = FunctionHelper::MoneyOutput($v['otherwinmoney']);
        }
        // implode(glue, pieces)
//        $count = D(self::MODEL)->alias('a')->where($arrWhere)->count();
        $count = M()->table(MysqlConfig::Table_statistics_rewardspool)->alias('a')->where($arrWhere)->count();
        $pager = new \Think\Page($count, $limit);
        // var_export( $pager);exit;
        //前端显示的字段
        $show_list = [
            'id' => [
                'key' => 'id',
                'title' => 'id',
            ],
            'gamewinmoney' => [
                'key' => 'gamewinmoney',
                'title' => '游戏输赢',
            ],
            'otherwinmoney' => [
                'key' => 'otherwinmoney',
                'title' => '其他方式获得金币',
//                'href' => U('User/user_info',array('userid'=>'recharge_userid')),
                'replace_href' => 'recharge_userid',
            ],
            'percentagewinmoney' => [
                'key' => 'percentagewinmoney',
                'title' => '千分位控制点',
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
    protected function getList($where, $page = 1, $limit = 10){
        // exit;
        return M()->table('statistics_rewardspool')->alias('a')->where($where)
//            ->join('left join ' . MysqlConfig::Table_userinfo . ' u on a.recharge_userid = u.userID')
            ->field('a.*, from_unixtime(a.time,"%Y-%m-%d %H:%i:%s") as day')
            ->page($page)
            ->limit($limit)
            ->order('a.id desc')
            ->select();
    }

}


