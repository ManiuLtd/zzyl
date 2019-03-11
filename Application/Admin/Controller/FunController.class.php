<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30
 * Time: 21:19
 */

namespace Admin\Controller;
use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;

class FunController extends AgentController
{
    protected $tableName = MysqlConfig::Table_web_fun_config;
    public function getList() {
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
        // 1 => [
        //     'word' => '有输赢',
        //     'val' => 1,
        // ],
        // 2 => [
        //     'word' => '代理id',
        //     'val' => 2,
        // ],
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
                $arrWhere['a.gamewinmoney']  = ['neq',0];
                break;
            case 2:
                $arrWhere['a.agent_userid'] = $search;
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

//        $listCommission = $this->getList($arrWhere, $page, $limit);
    $listCommission = M()->table($this->tableName)->page($page, $limit)->select();
    $arrid = array_column($listCommission, 'id');
    $arrid = $arrid ? $arrid : ['0'];
//        dump(M()->getLastSql());
//         var_export($listCommission);exit;
    foreach ($listCommission as $k => &$v) {
        $v['gamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['gamewinmoney']);
        $v['allgamewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allgamewinmoney']);
        $v['poolmoney'] = FunctionHelper::MoneyOutput((int)$v['poolmoney']);
        $v['percentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['percentagewinmoney']);
        $v['allpercentagewinmoney'] = FunctionHelper::MoneyOutput((int)$v['allpercentagewinmoney']);
        $v['otherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['otherwinmoney']);
        $v['allotherwinmoney'] = FunctionHelper::MoneyOutput((int)$v['allotherwinmoney']);
        $v['platformctrlpercent'] = (int)$v['platformctrlpercent'];

//            var_export($v);
    }
    // implode(glue, pieces)
    $count = M()
        ->table($this->tableName)->alias('a')->where($arrWhere)->count();
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
//        'id' => '10000900',
//    'name' => '豹子王',
//    'dllname' => '10000900.DLL',
//    'deskpeople' => '180',
//    'watchercount' => '180',
//    'canwatch' => '0',
//    'is_recommend' => '1',
    //前端显示的字段
    $show_list = [
        'id' => [
            'key' => 'id',
            'title' => 'id',
            'href' => U('RewardsPool/rewardsPool',array('search'=>'id', 'searchType' => 2, 'isHideNavbar' => 1)),
            'replace_href' => 'id',
            'type' => ['type' => 'input', 'attribution' => 'style="border:none;" readonly="readonly"', 'name' => 'id'],
        ],
        'content' => [
            'key' => 'content',
            'title' => '内容',
            'type' => ['type' => 'input', 'name' => 'content', 'attribution' => 'style="width:300px;"']
            // 'href' => U('User/user_info',array('userid'=>'gamewinmoney')),
            // 'replace_href' => 'gamewinmoney',
        ],
//            'dllname' => [
//                'key' => 'dllname',
//                'title' => '动态库',
//            ],
        'funkey' => [
            'key' => 'funkey',
            'title' => 'funkey',
        ],
        'funname' => [
            'key' => 'funname',
            'title' => 'funname',
        ],
        'funname3' => [
            'key' => 'funname3',
            'title' => 'funname3',
        ],
        'is_open' => [
            'key' => 'is_open',
            'title' => '是否开启',
            'type' => ['type' => 'option', 'name' => 'is_open', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
        ],
//        'option' => [
//            'key' => 'is_recommend',
//            'title' => '是否推荐',
//            'type' => ['type' => 'option', 'name' => 'is_Recommend', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
//        ],
        'pic' => [
            'key' => 'pic',
            'title' => 'pic',
        ],
    ];
    $this->assign([
        'isHideNavbar' => I("isHideNavbar", 0),//是否隐藏navbar 0否 1是
        'starttime' => $starttime,
        'endtime' => $endtime,
        'formRequest' => U('Fun/Edit'),
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

    public function Edit() {
        if ($_POST) {
            $id = (int)I('id');
            $is_open = (int)I('is_open');
            $sort = (int)I('sort');
            $res = M()->table($this->tableName)->where(['id' => $id])->save(['sort' => $sort, 'is_open' => $is_open]);
            if (empty($res)) {
                $this->error('修改失败');
            }
            $this->success('修改成功');
        }
    }

}


