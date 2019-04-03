<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use model\ServerModel;

class GameController extends AdminController
{
    protected $tableName = MysqlConfig::Table_gamebaseinfo;
    const ARR_PAGE = [1 => ['word' => '15条', 'val' => 15, ], 2 => ['word' => '50条', 'val' => 50, ],  3 => ['word' => '150条', 'val' => 150, ], ];

    //版本控制
    public function version()
    {
        $type = I('type', 'formal');
        //获取所有的平台版本 正式服
        $where1 = ['packet_type' => EnumConfig::E_VersionPacketType["PLATFORM"]];
        $plat = D('Data')->get_all_data('packet_version', $where1, 3, '');
        //获取所有的游戏版本 正式服
        $where2 = ['packet_type' => EnumConfig::E_VersionPacketType["GAME"]];
        $game = D('Data')->get_all_data('packet_version', $where2, 10, '');
        $this->assign('plat', $plat['_data']);
        $this->assign('page1', $plat['_page']);
        $this->assign('game', $game['_data']);
        $this->assign('page2', $game['_page']);

        //获取所有的游戏版本 测试服
        $where2 = ['packet_type' => EnumConfig::E_VersionPacketType["GAME"]];
        $test_game = D('Data')->get_all_data('packet_version_test', $where2, 10, '');

        $this->assign('test_game', $test_game['_data']);
        $this->assign('test_page2', $test_game['_page']);
        $this->assign('status', M('server_info')->where(['server_id' => ServerModel::SERVER_ID])->getfield('server_test_status'));
        $this->assign('type', $type);
        $this->display();
    }

    //平台包版本修改
    public function version_plat_edit()
    {
        $data['id'] = I('id');
        $data['name'] = I('name');
        $data['version'] = I('version');
        $data['desc'] = I('desc');
        $data['address'] = I('address');
        $data['check_version'] = I('check_version');
        $data['packet_type'] = EnumConfig::E_VersionPacketType["PLATFORM"];

        if (M('packet_version')->save($data)) {
            operation_record(UID, '修改' . $data['name'] . '版本信息');

            $this->updatePacketVersionToTxt($data['packet_type']);
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    //游戏版本修改
    public function version_game_edit()
    {
        $data = array();
        $data['id'] = I('id', 0, 'int');
        $data['name'] = I('name');
        $data['packet_id'] = I('packet_id');
        $data['version'] = I('version');
        $data['desc'] = I('desc');
        $data['address'] = I('address');
        $data['check_version'] = I('check_version');
        $data['packet_type'] = EnumConfig::E_VersionPacketType["GAME"];

        $gameType = I('gameType');

        if ($gameType == 0) {
            if (M('packet_version')->save($data)) {
                operation_record(UID, '修改' . $data['name'] . '版本信息');
                $this->updatePacketVersionToTxt($data['packet_type']);

                // 更新测试服
                M('packet_version_test')->where(['packet_id' => I('packet_id')])->save(['version' => $data['version'], 'address' => $data['address']]);
                $this->updatePacketVersionToTxt($data['packet_type'], true);
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            if (M('packet_version_test')->save($data)) {
                operation_record(UID, '修改' . $data['name'] . '版本信息');
                $this->updatePacketVersionToTxt($data['packet_type'], true);
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        }
    }

    //添加游戏版本
    public function game_version_add()
    {
        if (IS_POST) {
            $data = array();
            $data['packet_type'] = EnumConfig::E_VersionPacketType["GAME"];
            $data['packet_id'] = I('packet_id', 0, 'int');
            $data['name'] = I('name', '', 'trim');
            $data['version'] = I('version', '', 'trim');
            $data['address'] = I('address', '', 'trim');
            $data['check_version'] = I('check_version', '', 'trim');
            $data['desc'] = I('desc', '', 'trim');
            //判断名称和游戏ID是否有相同
            if (M('packet_version')->where(['name' => $data['name']])->select()) {
                $this->error('该名称已经存在，为了有利于区分请换个名称吧');
            }
            if (M('packet_version')->where(['packet_id' => $data['packet_id']])->select()) {
                $this->error('该游戏ID已经存在');
            }
            if (!$data['desc']) {
                $this->error('描述必须填写');
            }

            if (M('packet_version')->add($data)) {
                operation_record(UID, '添加游戏id为' . $data['packet_id'] . '版本信息');
                $this->updatePacketVersionToTxt($data['packet_type']);

                M('packet_version_test')->add($data);
                $this->updatePacketVersionToTxt($data['packet_type'], true);

                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }

        } else {
            $this->display();
        }
    }

    // 测试服状态更改
    public function test_status()
    {
        if (IS_POST) {
            $status = I('status');
            if (false !== M('server_info')->where(['server_id' => ServerModel::SERVER_ID])->setfield('server_test_status', $status)) {
                $this->success('更改成功');
            } else {
                $this->error('更改失败');
            }
        } else {
            $this->assign('status', M('server_info')->where(['server_id' => ServerModel::SERVER_ID])->getfield('server_test_status'));
            $this->display();
        }
    }

    //游戏底包上传
    public function upload_view()
    {
        $this->display();
    }

    public function upload_file()
    {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 553145728; // 设置附件上传大小
        $upload->exts = array('apk'); // 设置附件上传类型
        $upload->rootPath = APP_ROOT_PATH . '/../download/'; // 设置附件上传根目录
        if (!is_dir($upload->rootPath)) {
            mkdir($upload->rootPath);
        }
//        $upload->savePath = ''; // 设置附件上传（子）目录
//        $upload->autoSub = false; // 设置附件上传（子）目录
        //$file_name = iconv('UTF-8','GB2312','安卓');
        $file_name = 'anzhuo';
        $upload->saveName = $file_name;
        $upload->replace = true;
        // 上传文件
        $info = $upload->upload();
        if (!$info) {
            // 上传错误提示错误信息
            $this->error($upload->getError());
        } else {
            // 上传成功
            operation_record(UID, '上传游戏底包');
            $this->success('上传成功！');
        }
    }

    //子游戏列表
    public function subgameList()
    {
        $page = I('p', 1);
        $limit = I("limit", self::ARR_PAGE[1]['val']);
        $searchType = I("searchType");
        $search = trim(I("search"));

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

        $arrWhere = [];


        if (isset($arrSearchType[$searchType])) {
            switch ($arrSearchType[$searchType]['val']) {
                case 1:
                    $arrWhere['b.roomID']  = $search;
                    break;
                case 2:
                    $arrWhere['b.gameID'] = $search;
                    break;
                default:
                    break;
            }
        }
        // $arrWhere['gamewinmoney']  = ['neq',0];

        $res = $this->getList($arrWhere, $page, $limit);
        $listCommission = $res['_data'];


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
        ];
        $this->assign([
            'formRequest' => U('Game/rewardsPoolEdit'),
            'show_list' => $show_list,
            'show_data' => $listCommission,
            '_page' => $pager->show(),
            'searchType' => $searchType,
            'arrSearchType' => $arrSearchType,
            'search' => $search,
            'limit' => $limit,
            'page_select' => self::ARR_PAGE,
        ]);
//    	 var_export($listCommission);
        $this->display();
    }

    public function rewardsPoolEdit() {
        if ($_POST) {
            $isRecommend = (int)I('is_Recommend');
            $roomID = (int)I('roomID');
            $is_hide = (int)I('is_hide');
            $gameID = M()->query('select gameID from ' . MysqlConfig::Table_roombaseinfo . ' where roomID =' . $roomID . ' limit 1');
            $resCount = M()->query('select count(*) as cnt, gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and gameID =' . $gameID[0]['gameid']);
            $res = M()->query('select gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and roomID =' . $roomID);
            if (EnumConfig::E_GameRecommend['NO'] == $isRecommend && $resCount[0]['cnt'] <= 1 && $res) {
                $this->error('房间推荐数不能小于1');
            }
            M()->startTrans();
            $res = M()->table(MysqlConfig::Table_roombaseinfo)->where(['roomID' => $roomID])->save(['is_Recommend' => $isRecommend, 'is_hide' => $is_hide, 'updateTime' => time()]);
            if (empty($res)) {
                M()->rollback();
                $this->error('修改失败');
            }
            M()->commit();
            $this->success('修改成功');
        }
    }

    public function getList($where, $page = 1, $limit = 10){
        $data = M()
            ->table(MysqlConfig::Table_roombaseinfo)->alias('b')
            ->where($where)
            ->field('b.roomID,b.name,b.is_Recommend,b.is_hide')
            ->page($page)
            ->limit($limit)
            ->order('b.roomID desc')
            ->select();
        $count = M()
            ->table(MysqlConfig::Table_roombaseinfo)->alias('b')
            ->where($where)
            ->count();
        return ['_data' => $data, '_count' => $count];
    }

    //游戏列表
    public function game_list()
    {
        $this->game_list1();
    }

    public function game_list0() {
        $model = M();
        $count = $model->table('gameBaseInfo')->count();
        $page = new \Think\Page($count, 15);
        $data = $model->table('gameBaseInfo')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('_data', $data);
        $this->assign('_page', $page->show());
        $this->display();
    }
    public function game_list1() {
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
        $listCommission = M()->table(MysqlConfig::Table_gamebaseinfo)->page($page, $limit)->order('sort desc, gameID')->select();
        $arrGameID = array_column($listCommission, 'gameid');
        $arrGameID = $arrGameID ? $arrGameID : ['0'];
        $resCount = M()->query('select count(*) as cnt, gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and gameID in (' . implode(',', $arrGameID) . ') group by gameID' );
        $resCount = array_column($resCount, 'cnt', 'gameid');
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

            $v['subRoomRecommendCount'] = (int)$resCount[$v['gameid']]['cnt'];
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
//        'gameid' => '10000900',
//    'name' => '豹子王',
//    'dllname' => '10000900.DLL',
//    'deskpeople' => '180',
//    'watchercount' => '180',
//    'canwatch' => '0',
//    'is_recommend' => '1',
        //前端显示的字段
        $show_list = [
            'gameid' => [
                'key' => 'gameid',
                'title' => 'gameid',
                 'href' => U('RewardsPool/rewardsPool',array('search'=>'gameid', 'searchType' => 2, 'isHideNavbar' => 1)),
                 'replace_href' => 'gameid',
                'type' => ['type' => 'input', 'attribution' => 'style="width:100px;border:none;" readonly="readonly"', 'name' => 'gameID'],
            ],
            'name' => [
                'key' => 'name',
                'title' => '游戏名称',
                'type' => ['type' => 'input', 'attribution' => '', 'name' => 'name'],
                // 'href' => U('User/user_info',array('userid'=>'gamewinmoney')),
                // 'replace_href' => 'gamewinmoney',
            ],
//            'dllname' => [
//                'key' => 'dllname',
//                'title' => '动态库',
//            ],
            'deskpeople' => [
                'key' => 'deskpeople',
                'title' => '桌子最大人数',
            ],
            'watchercount' => [
                'key' => 'watchercount',
                'title' => '旁观人数',
            ],
            'canwatch' => [
                'key' => 'canwatch',
                'title' => '是否可以旁观',
            ],
            'is_hide' => [
                'key' => 'is_hide',
                'title' => '是否隐藏',
                'type' => ['type' => 'option', 'name' => 'is_hide', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
            ],
            'option' => [
                'key' => 'is_recommend',
                'title' => '是否推荐',
                'type' => ['type' => 'option', 'name' => 'is_Recommend', 'options' => [['value' => '0', 'text' => '否'],['value' => 1, 'text' => '是']]],
            ],
            'subRoomRecommendCount' => [
                'key' => 'subRoomRecommendCount',
                'title' => '房间推荐数',
            ],
            'sort' => [
                'key' => 'sort',
                'title' => '排序',
                'type' => ['type' => 'input', 'attribution' => 'style="width:100px;"', 'name' => 'sort'],
            ],
        ];
        $this->assign([
            'isHideNavbar' => I("isHideNavbar", 0),//是否隐藏navbar 0否 1是
            'starttime' => $starttime,
            'endtime' => $endtime,
            'formRequest' => U('Game/gameBaseInfoEdit'),
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

    public function gameBaseInfoEdit() {
        if ($_POST) {
            $gameID = (int)I('gameID');
            $isRecommend = (int)I('is_Recommend');
            $is_hide = (int)I('is_hide');
            $name = I('name');
            $sort = (int)I('sort');
            $resCount = M()->query('select count(*) as cnt, gameID from ' . MysqlConfig::Table_roombaseinfo . ' where is_Recommend = 1 and gameID =' . $gameID);
            if (EnumConfig::E_GameRecommend['YES'] == $isRecommend && $resCount[0]['cnt'] <= 0) {
                $this->error('房间推荐数不能小于0');
            }
            $res = M()->table(MysqlConfig::Table_gamebaseinfo)->where(['gameID' => $gameID])->save(['is_Recommend' => $isRecommend, 'sort' => $sort, 'is_hide' => $is_hide, 'name' => $name]);
            if (empty($res)) {
                $this->error('修改失败');
            }
            $this->success('修改成功');
        }
    }
    //版本删除
    public function version_del()
    {
        $id = I('id', 0, 'int');
        if (M('packet_version')->delete($id)) {
            operation_record(UID, '删除版本信息');
            $this->updatePacketVersionToTxt(EnumConfig::E_VersionPacketType['GAME']);

            M('packet_version_test')->delete($id);
            $this->updatePacketVersionToTxt(EnumConfig::E_VersionPacketType['GAME'], true);

            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //vip房间管理,获取已经设置的vip房间
    public function vipRoom()
    {
        $data = D('Data')->get_all_data('vip_room', '', 10, '');
        foreach ($data['_data'] as $k => $v) {
            $game = M()->table('gameBaseInfo')->where(['gameID' => $data['_data'][$k]['gameid']])->find();
            $data['_data'][$k]['game'] = $game['name'];
        }
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();

    }

    //添加vip房间
    public function vipRoomAdd()
    {
        if (IS_POST) {
            $data = [
                'roomID' => I('roomID'),
                'roomPassword' => I('roomPassword'),
                'roomBeginTime' => strtotime(I('roomBeginTime')),
                'roomEndTime' => strtotime(I('roomEndTime')),
                'createTime' => time(),
                'gameID' => I('gameID'),
            ];
            //如果有分配给代理
            if (I('agentID')) {
                $data['agentID'] = I('agentID');
                $data['canChangePassword'] = (int)I('canChangePassword');
                $data['bindBeginTime'] = strtotime(I('bindBeginTime'));
                $data['bindEndTime'] = strtotime(I('bindEndTime'));
                if ($data['bindEndTime'] <= $data['bindBeginTime']) {
                    $this->error('分配代理有效期格式错误');
                }
                if ($data['bindEndTime'] > $data['roomEndTime']) {
                    $this->error('分配代理有效期超过房间号有效期');
                }
            }
            $test = '/^\d{6}$/';
            if (!preg_match($test, $data['roomID']) || !preg_match($test, $data['roomPassword'])) {
                $this->error('房间号和密码必须是6位数');
            }
            if ($data['roomBeginTime'] >= $data['roomEndTime']) {
                $this->error('房间有效期格式不正确');
            }
            //检测是否存在房间号
            $res = M('vip_room')->where(['roomID' => $data['roomID']])->find();
            if ($res) {
                $this->error('该房间号已存在');
            }
            $res = M('vip_room')->add($data);
            if ($res) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败' . M('vip_room')->getError());
            }
        } else {
            //获取代理
            $agent = M('agent_member')->field('username,agentid')->select();
            $this->assign('agent', $agent);
            //获取游戏
            $game = M()->table('gameBaseInfo')->select();
            $this->assign('game', $game);
            $this->display();
        }
    }

    //编辑vip房间
    public function vipRoomEdit()
    {
        //编辑房间只需要编辑房间号和房间密码有效期
        if (IS_POST) {
            $data = [
                'id' => I('id'),
                'roomPassword' => I('roomPassword'),
                'roomBeginTime' => strtotime(I('roomBeginTime')),
                'roomEndTime' => strtotime(I('roomEndTime')),
            ];
            if (strlen($data['roomPassword']) != 6) {
                $this->error('密码格式错误');
            }
            if ($data['roomBeginTime'] >= $data['roomEndTime']) {
                $this->error('房间有效期格式不正确');
            }
            if (M('vip_room')->save($data)) {
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败' . M('vip_room')->getError());
            }
        } else {
            $id = I('id');
            $room = M('vip_room')->find($id);
            $this->assign('room', $room);
            $this->display();
        }
    }

    //删除vip房间
    public function vipRoomDel()
    {
        $id = I('id');
        $room = M('vip_room')->find($id);
        //如果分配给了代理并且未过有效期则不能直接删除
        if ($room['agentID'] && time() < $room['bindEndTime']) {
            $this->error('该房间正在分配给代理号为' . $room['agentID'] . '的代理，不能直接删除');
        }
        if (M('vip_room')->delete($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败' . M('vip_room')->getError());
        }
    }

    //分配房间给代理管理
    public function roomBind()
    {
        if (IS_POST) {
            $data = [
                'id' => I('id'),
                'agentID' => I('agentID'),
                'canChangePassword' => I('canChangePassword'),
                'bindBeginTime' => strtotime(I('bindBeginTime')),
                'bindEndTime' => strtotime(I('bindEndTime')),
            ];
            if (!$data['agentID']) {
                $this->error('请选择分配代理');
            }
            if ($data['bindEndTime'] <= $data['bindBeginTime']) {
                $this->error('分配代理有效期格式错误');
            }
            $room = M('vip_room')->find($data['id']);
            if ($data['bindEndTime'] > $room['roomendtime']) {
                $this->error('分配代理有效期超过房间号有效期');
            }
            if (M('vip_room')->save($data)) {
                $this->success('分配成功');
            } else {
                $this->error('分配失败' . M('vip_room')->getError());
            }
        } else {
            $id = I('id');
            $room = M('vip_room')->find($id);
            $this->assign('room', $room);
            //获取所有代理
            $agent = M('agent_member')->field('username,agentid')->select();
            $this->assign('agent', $agent);
            $this->display();
        }
    }

    //解除分配代理
    public function relieveBind()
    {
        $id = I('id');
        $data = [
            'id' => $id,
            'agentID' => '',
            'canChangePassword' => '',
            'bindBeginTime' => '',
            'bindEndTime' => '',
        ];
        if (M('vip_room')->save($data)) {
            $this->success('解除成功');
        } else {
            $this->error('解除失败' . M('vip_room')->getError());
        }
    }

    //魔法表情
    public function magic()
    {
        $type = I('type');
        $search = I('search');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop);
            $where['time'] = ['between', [$start, $stop]];
        } else {
            if (!$type && !$search) {
                $start = strtotime(date('Y-m-d', time()));
                $stop = $start + 24 * 3600 - 1;
                $where['time'] = ['between', [$start, $stop]];
            }
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['m.userID'] = $search;
                    break;

                case 2:
                    $where['u.name'] = $search;
                    break;
            }
        }

        $isvirtual = I('isvirtual', 0);
        if ($isvirtual) {
            $where['u.isvirtual'] = 1;
        } else {
            $where['u.isvirtual'] = 0;

        }
        $where['reason'] = 11;
        // $count = M()->table('statistics_moneyChange')->where($where)->count();
        $count = M()
            ->table('statistics_moneychange as m')
            ->join('left join userinfo as u on u.userID = m.userID')
            ->where($where)
            ->field('m.*')
            ->count();
        $Page = new \Think\Page($count, 15);
        // $record = M()->table('statistics_moneyChange')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $record = M()
            ->table('statistics_moneychange as m')
            ->join('left join userinfo as u on u.userID = m.userID')
            ->order('time desc')
            ->where($where)
            ->field('m.*,u.isvirtual')
            ->where($where)->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($record as $k => $v) {
            $user = M()->table('userInfo')->where(['userID' => $record[$k]['userid']])->find();
            $record[$k]['name'] = $user['name'];
            $record[$k]['account'] = $user['account'];
        }
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //魔法表情配置
    public function magic_config()
    {
        if (IS_POST) {
            $cost = I('cost', 0, 'int');
            //修改数据库配置
            $res = M()->table('otherConfig')->where(['keyConfig' => 'useMagicExpressCostMoney'])->save(['valueconfig' => $cost]);
            if ($res) {
                $this->success('配置成功');
            } else {
                $this->error('配置失败' . M()->table('otherConfig')->getError());
            }
        } else {
            $value = M()->table('otherConfig')->where(['keyConfig' => 'useMagicExpressCostMoney'])->find();
            $this->assign('value', $value['valueconfig']);
            $this->display();
        }
    }

    public function room()
    {
        $where = [];
        $roomid = I('roomid');
        if ($roomid) {
            $where['GRI.roomID'] = $roomid;
        }
        $passwd = I('passwd');
        if ($passwd) {
            $where['GRI.passwd'] = $passwd;
        }

        $type2 = I('type2');
        if ($type2) {
            if ($type2 == 'jewels') {
                $where['GRI.passwd'] = ['neq', 'aa'];
            } elseif ($type2 == 'money') {
                $where['GRI.passwd'] = ['eq', 'aa'];
            }
        }
        $count = M()
            ->table('statistics_gamerecordinfo as GRI')
            ->where($where)
            ->count();
        $Page = new \Think\Page($count, 15);
        $res = M()
            ->table('statistics_gamerecordinfo as GRI')
            ->join('left join roombaseinfo as RBI on GRI.roomID = RBI.roomID')
            ->join('left join gameBaseInfo as GBI on RBI.gameID = GBI.gameID')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->field('GRI.passwd,GRI.createTime,GRI.beginTime,GRI.finishedTime,GRI.userIDList,RBI.name as rname,GBI.name as gname')
            ->order('beginTime desc')
            ->select();
        foreach ($res as $k => $v) {
            if (!$res[$k]['useridlist']) {
                $res[$k]['useridlist'] = '';
            } else {
                //解析用户
                $str = substr($res[$k]['useridlist'], 0, strlen($res[$k]['useridlist']) - 1);
                $user = explode(',', $str);
                //过滤机器人
                foreach ($user as $z => $j) {
                    $u = M()->table('userInfo')->where(['userID' => (int)$user[$z]])->field('userID,name,isVirtual')->find();
                    if ($u['isvirtual'] == 0) {
                        $res[$k]['user'] .= $u['name'] . '&emsp;';
                    }
                }
            }
        }
        //获取所有的房间
        $room = M()->table('roombaseinfo')->select();
        $this->assign('room', $room);
        $this->assign('_page', $Page->show());
        $this->assign('_data', $res);
        $this->display();
    }

    public function del_private_room()
    {
        $redis = \GetRedis::get();
        if ($redis->connect == false) {
            $this->error('缓存服务器未启动');
        }

        //获取所有真实玩家用户id列表
        $user_list = M()->table('userInfo')->where(['isVirtual' => 0])->field('userID')->select();
        $prefix = 'privateRoomOpenedList|';
        foreach ($user_list as $k => $v) {
            $redis->redis->del($prefix . $user_list[$k]['userid']);
        }
        $this->success('清除成功');
    }

    //清除用户已经购买的桌子
    public function del_user_desk()
    {
        $redis = \GetRedis::get();
        if ($redis->connect == false) {
            $this->error('缓存服务器未启动');
        }

        $prefix = 'userinfo|*';
        $user = $redis->redis->keys($prefix);
        foreach ($user as $k => &$v) {
            //修改buyingDeskCount
            $redis->redis->hset($v, 'buyingDeskCount', 0);
        }
        echo '清除成功';
    }

    #游戏奖池数据
    public function jackpotconfig()
    {

        $where = [];
        $serviceName = I('serviceName', '');
        if ($serviceName) {
            $where['RBI.serviceName'] = I('serviceName');
            $where['RBI.type'] = I('type');
        }

        $count = M()->table('rewardsPool AS RP')
            ->join('LEFT JOIN roombaseinfo AS RBI ON RBI.roomID = RP.roomID')->where($where)->count();
        $Page = new \Think\Page($count, 10);
        $jackpotConfig = M()
            ->table('rewardsPool AS RP')
            ->join('LEFT JOIN roombaseinfo AS RBI ON RBI.roomID = RP.roomID')
            ->field('RP.roomID,RP.poolMoney,RP.gameWinMoney,RP.percentageWinMoney,
                     RP.otherWinMoney,RP.allGameWinMoney,RP.allPercentageWinMoney,
                     RP.allOtherWinMoney,RP.platformCtrlType,RP.platformCtrlValue,
                     RP.platformCtrlPercent,RP.realPeopleFailPercent,RP.realPeopleWinPercent,
                     RP.rewardsCount,RP.rewards1,RP.rewards2,RP.rewards3,RP.rewards4,
                     RP.rewards5,RBI.name')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        $this->assign('_page', $Page->show());
        $this->assign('_data', $jackpotConfig);
        $this->display();
    }

    #编辑奖池数据
    public function jackpotedit()
    {
        if (IS_POST) {
            #编辑后的数据
            $roomID = (int)I('roomID');
            $data = [
                'platformCtrlPercent' => (int)I('platformCtrlPercent'),
            ];
            //更新数据库
            M()->table('rewardsPool')->where(['roomID' => $roomID])->save($data);
            //更新reids
            $redis = \GetRedis::get();
            if ($redis->connect == false) {
                $this->error('更新失败,缓存服务器未启动');
            }

            $redis->redis->hmset('rewardsPool|' . $roomID, $data);
            $this->success('更新成功');
        } else {
            $roomid = (int)I('roomid');
            //$jackpot = M()->table('rewardsPool')->where(['roomID'=>$roomid])->find();
            $redis = \GetRedis::get();
            if ($redis->connect == false) {
                $this->error('更新失败,缓存服务器未启动');
            }

            $data = $redis->redis->hgetall('rewardsPool|' . $roomid);
            $this->assign('jackpot', $data);
            //dump($data);die;
            $this->assign('roomid', $roomid);
            $this->display();
        }
    }

    public function updatePacketVersionToTxt($packet_type, $isTest = false)
    {
        //转为json存入txt  兼容之前的
        if ($packet_type == EnumConfig::E_VersionPacketType['PLATFORM']) {
            $info = M('packet_version')->where(['packet_type' => $packet_type])
                ->field('packet_id as platformType,version,address as packetaddress,check_version as shenHeVersion')->select();
            unlink('/usr/local/nginx/html/plat_version.txt');
            file_put_contents('/usr/local/nginx/html/plat_version.txt', json_encode($info));
        } elseif ($packet_type == EnumConfig::E_VersionPacketType['GAME']) {
            if ($isTest) {
                $info = M('packet_version_test')->where(['packet_type' => $packet_type])
                    ->field('packet_id as gameid,version,address as packetaddress,check_version as shenHeVersion')->select();
                unlink('/usr/local/nginx/html/test_version.txt');
                file_put_contents('/usr/local/nginx/html/test_version.txt', json_encode($info));
            } else {
                $info = M('packet_version')->where(['packet_type' => $packet_type])
                    ->field('packet_id as gameid,version,address as packetaddress,check_version as shenHeVersion')->select();
                unlink('/usr/local/nginx/html/version.txt');
                file_put_contents('/usr/local/nginx/html/version.txt', json_encode($info));
            }
        }
    }
}
