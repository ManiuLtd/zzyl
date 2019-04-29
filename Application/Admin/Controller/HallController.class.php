<?php
namespace Admin\Controller;
use config\EnumConfig;
use config\ErrorConfig;
use config\GeneralConfig;
use config\MysqlConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use manager\RedisManager;
use model\BankModel;
use model\ConfigModel;
use model\EmailModel;
use model\FeedbackModel;
use model\GiveModel;
use model\LobbyModel;
use model\NoticeModel;
use model\UserModel;
use notify\CenterNotify;


class HallController extends AdminController
{
    /*public function payAlert(){
        // 获取支付轮询数据
        $redis = RedisManager::getRedis();
        $res = $redis->lGetRange('orderNotify',0,-1); //取出key下所有值
        if($res){
            $redis->del('orderNotify');
            $this->success(1);//代表有新订单

        }
        $this->error();
    }*/

    public $moneyMapField = [3, 14, 19, 21, 23, 29];
    //抽奖管理
    public function turntable()
    {
        //获取抽奖记录
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['turntableTime'] = ['between', [$start, $stop]];

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['turntableTime'] = $res['data'];
        }        // }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['username'] = ['like', "%{$search}%"];
                    break;
            }
        }
        $data = D('Data')->get_all_data('turntable_record', $where, 15, 'turntableTime desc');
        FunctionHelper::moneyInArrayOutput($data['_data'], GeneralConfig::WEB_TURNTABLE_RECORD_MONEY);
//        dump($data);
        foreach ($data['_data'] as $k => &$turntableRecord) {
            $turntableRecord['reward_name'] = '未中奖';
            if ($turntableRecord['prizetype'] != 0) {
                $turntableRecord['num'] = FunctionHelper::moneyOutput($turntableRecord['num'], $turntableRecord['prizetype']);
                $turntableRecord['reward_name'] = EnumConfig::E_ResourceTypeName[$turntableRecord['prizetype']];
            }
        }

        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //抽奖配置
    public function turntable_config()
    {
        //获取抽奖配置
        $config = M('turntable_config')->select();
        ConfigModel::getInstance()->setTableCache(RedisConfig::Hash_web_turntable_config, $config);
        //金币倍数
        foreach ($config as $k => &$v) {
//            RedisManager::getRedis()->hSet(RedisConfig::Hash_web_turntable_config, $v['id'], json_encode($v));
//            if (in_array($v['prizetype'], GeneralConfig::TURNTABLE_CONFIG_MONEY)) {
//                $v['num'] = FunctionHelper::MoneyOutput($v['num']);
//            }

            if (in_array($v['prizetype'], GeneralConfig::SIGN_CONFIG_SOURCE_TYPE)) {
                $v['num'] = FunctionHelper::MoneyOutput($v['num'], $v['prizetype']);
            }
        }
        $all_money = M('turntable_config')->where(['prizeType' => 1])->sum('num');
        $all_jewels = M('turntable_config')->where(['prizeType' => 2])->sum('num');
        $all_chance = M('turntable_config')->sum('chance');
        $money = M('turntable_record')->where(['prizeType' => 1])->sum('num');
        $jewels = M('turntable_record')->where(['prizeType' => 2])->sum('num');

        $all_money = FunctionHelper::MoneyOutput($all_money, EnumConfig::E_ResourceType['MONEY']);
        $all_jewels = FunctionHelper::MoneyOutput($all_jewels, EnumConfig::E_ResourceType['JEWELS']);
        $money = FunctionHelper::MoneyOutput($money, EnumConfig::E_ResourceType['MONEY']);
        $jewels = FunctionHelper::MoneyOutput($jewels, EnumConfig::E_ResourceType['JEWELS']);


        $this->assign('all_money', $all_money);
        $this->assign('all_jewels', $all_jewels);
        $this->assign('all_chance', $all_chance);
        $this->assign('config', $config);
        $this->assign('money', $money);
        $this->assign('jewels', $jewels);
        $this->display();
    }

    //抽奖编辑
    public function turntable_config_edit()
    {
        if ($_POST) {
            $data['id'] = I('id');
            $data['prize'] = I('prize');
            $data['num'] = I('num');
            $data['prizeType'] = I('prizeType');
            if (I('prizeType') != 3) {
                $data['prizeType'] = I('prizeType');
            }

            // $prizeType = M('turntable_config')->where(['id' => $data['id']])->getField('prizeType');
            //金币倍数
            if (EnumConfig::E_ResourceType['MONEY'] == $data['prizeType']) {
                $data['num'] = FunctionHelper::MoneyInput($data['num']);
            }
//             var_export($data);exit;

            $data['chance'] = I('chance');
            if (!preg_match("/^[1-9][0-9]*$/", $data['num']) && $data['prizeType'] != 0) {
                $this->error('奖品数量数只能是正整数');
            }
            //

            $res = M('turntable_config')->save($data);
            if ($res) {
                ConfigModel::getInstance()->hSet(RedisConfig::Hash_web_turntable_config, $data['id'], $data);
                operation_record(UID, '编辑转盘数据');
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    //获取签到记录
    public function sign()
    {
        //获取抽奖记录
        $type = I('type');
        $search = I('search');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['signTime'] = ['between', [$start, $stop]];

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['signTime'] = $res['data'];
        }        // }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['username'] = ['like', "%{$search}%"];
                    break;
            }
        }
        $data = D('Data')->get_all_data('sign_record', $where, 15, 'signTime desc');
        //金币处理
        foreach ($data['_data'] as $k => &$v) {
//            if ($v['prizetype'] == EnumConfig::E_ResourceType['MONEY']) {
            if (in_array($v['prizetype'], GeneralConfig::SIGN_CONFIG_SOURCE_TYPE)) {
                $v['num'] = FunctionHelper::MoneyOutput($v['num'], $v['prizetype']);
                $v['total_get_money'] = FunctionHelper::MoneyOutput($v['total_get_money'], $v['prizetype']);
                // var_export($v);
            }
        }

        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //签到配置
    public function sign_config()
    {
        //获取签到配置
        $config = M('sign_config')->select();
        ConfigModel::getInstance()->setTableCache(RedisConfig::Hash_web_sign_config, $config);
        //金币倍数
        foreach ($config as $k => &$v) {
             if (in_array($v['prizetype'], GeneralConfig::SIGN_CONFIG_SOURCE_TYPE)) {
                $v['num'] = FunctionHelper::MoneyOutput($v['num'], $v['prizetype']);
             }
        }
        $all_money = M('sign_config')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY']])->sum('num');
        $all_jewels = M('sign_config')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS']])->sum('num');
        $money = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY']])->sum('num');
        $jewels = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS']])->sum('num');
        $all_money = FunctionHelper::MoneyOutput($all_money, EnumConfig::E_ResourceType['MONEY']);
        $all_jewels = FunctionHelper::MoneyOutput($all_jewels, EnumConfig::E_ResourceType['JEWELS']);
        $money = FunctionHelper::MoneyOutput($money, EnumConfig::E_ResourceType['MONEY']);
        $jewels = FunctionHelper::MoneyOutput($jewels, EnumConfig::E_ResourceType['JEWELS']);
        $this->assign('all_money', $all_money);
        $this->assign('all_jewels', $all_jewels);
        $this->assign('config', $config);
        $this->assign('money', $money);
        $this->assign('jewels', $jewels);
        $this->display();
    }

    //签到编辑
    public function sign_config_edit()
    {
        $data['picNum'] = I('picNum');
        $data['id'] = I('id');
        $data['prize'] = I('prize');
        $data['num'] = I('num');
        $data['prizeType'] = I('prizeType');
        // if (I('prizeType') != 3) {
        //     $data['prizeType'] = I('prizeType');
        // }
        //金币倍数
//        if ($data['prizeType'] == EnumConfig::E_ResourceType['MONEY']) {
        if (in_array($data['prizeType'], GeneralConfig::TURNTABLE_CONFIG_MONEY)) {
            $data['num'] = FunctionHelper::MoneyInput($data['num'], $data['prizeType']);
        }

        if (!preg_match("/^[1-9][0-9]*$/", $data['num']) && $data['prizeType'] != 0) {
            $this->error('奖品数量数只能是正整数');
        }
        $res = M('sign_config')->save($data);
        if ($res) {
            ConfigModel::getInstance()->hSet(RedisConfig::Hash_web_sign_config, $data['id'], $data);
            operation_record(UID, '编辑签到配置');
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
    }

    //反馈管理
    public function feedback()
    {
        //获取所有的反馈消息//并且是未结束的
        $where = [];
        $type = I('type');
        $search = I('search');

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['username'] = ['like', "%{$search}%"];
                    break;
            }
        } else {
            $search = '';
        }
        $order_by = "has_back desc,id desc";
        $read_type = I('read_type');
        if ($read_type) {
            $where['read_type'] = ['eq', EnumConfig::E_FeedbackReadType['CLOSE']];
        } else {
            $where['read_type'] = ['neq', EnumConfig::E_FeedbackReadType['CLOSE']];
        }

        $data = D('Data')->get_all_data('feedback', $where, 10, $order_by);
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //反馈详情
    public function feedback_msg()
    {
        $id = I('id');
        $feedback = FeedbackModel::getInstance()->getFeedbackByID($id);
        //获取所有的用户回复内容
        $feedbackReplyList = FeedbackModel::getInstance()->getFeedbackReplyList($id);
        $this->assign('f', $feedback);
        $this->assign('msg', $feedbackReplyList);
        $this->display();
    }

    //回复
    public function feedback_callback()
    {
        if (IS_POST) {
            $content = I('c_content');
            if (empty($content)) {
                $this->error('回复内容不能为空');
            }
            $id = I('id');
            $addResult = FeedbackModel::getInstance()->addFeedbackReply($id, EnumConfig::E_FeedbackReplyType['SYSTEM'], $content);
            if (empty($addResult)) {
                $this->error('添加回复数据失败');
            }
            //修改 is_back 为已回复
            FeedbackModel::getInstance()->updateFeedbackReplyStatus($id, EnumConfig::E_FeedbackReplyStatus['REPLAY']);
            //修改 read_type 为未读
            FeedbackModel::getInstance()->updateFeedbackReadType($id, EnumConfig::E_FeedbackReadType['NO_READ']);
            operation_record(UID, '回复反馈');
            $this->success('回复成功');
        } else {
            $id = I('id');
            //获取常用语
            $arr = [
                ['msg' => '感谢您的反馈，祝您游戏愉快！'],
                ['msg' => '一边去吧孩纸'],
                ['msg' => '不想理你呢'],
                ['msg' => '你想怎样，摔手机啊'],
            ];
            $this->assign('arr', $arr);
            $this->assign('id', $id);
            $this->display();
        }
    }

    //结束反馈
    public function feedback_over()
    {
        $id = array_unique((array)I('id', 0));
        if (empty($id) || empty($id[0])) {
            $this->error('请选择要操作的数据');
        }
        $data['read_type'] = EnumConfig::E_FeedbackReadType['CLOSE'];
        foreach ($id as $k => $v) {
            $result = FeedbackModel::getInstance()->updateFeedbackReadType($v, EnumConfig::E_FeedbackReadType['CLOSE']);
            if (empty($result)) {
                $this->error("结束id={$v}反馈失败");
            }
            operation_record(UID, "结束id={$v}的用户反馈");
        }
        $this->success('结束反馈成功');
    }

    //邮件
    public function email()
    {
        $where = [
            'mailType' => ['in', [EnumConfig::E_ResourceChangeReason['SYSTEM_MAIL'], EnumConfig::E_ResourceChangeReason['USER_MAIL']]],
        ];
        $count = M()->table('web_email')->where($where)->count();
        $Page = new \Think\Page($count, 20);
        $emailList = M()
            ->table('web_email')
            ->where($where)
            ->order('sendtime desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        foreach ($emailList as &$email) {
            if ($email['senderid'] == 0) {
                $email['senderName'] = '无';
            } else {
                if ($email['mailtype'] == EnumConfig::E_ResourceChangeReason['SYSTEM_MAIL'] || $email['mailtype'] == EnumConfig::E_ResourceChangeReason['USER_MAIL']) {
                    $email['senderName'] = M('admin_member')->where(['id' => $email['senderid']])->getfield('username');
                } else {
                    $email['senderName'] = EmailModel::getInstance()->getUserInfo($email['senderID'], 'name');
                }
            }
            $email['typeName'] = EnumConfig::E_ResourceChangeReasonName[$email['mailtype']];
            $email['goodslist'] = json_decode($email['goodslist'], true);
            if (empty($email['goodslist'])) {
                $email['goodsName'] = '无';
            } else {
                $goodsName = '';
                foreach ($email['goodslist'] as $goods) {
                    $goodsName .= EnumConfig::E_ResourceTypeName[$goods['goodsType']] . 'x' . $goods['goodsNums'] . ',';
                }
                $email['goodsName'] = rtrim($goodsName, ',');
            }
        }
        $this->assign('emailList', $emailList);
        $this->assign('page', $Page->show());
        $this->display();
    }

    //发布系统邮件
    public function send_email()
    {
        if (IS_POST) {
            $title = I('title');
            $content = I('content');
            if (empty($title) || empty($content)) {
                $this->error('标题或者内容不能为空');
            }
            //邮件附件
            $jewels_count = I('jewels_count', 0);
            $money_count = I('money_count', 0);
            if ($jewels_count < 0 || $money_count < 0) {
                $this->error('钻石和金币数不能小于0');
            }
            $goodsArray = [
                EnumConfig::E_ResourceType['JEWELS'] => $jewels_count,
                EnumConfig::E_ResourceType['MONEY'] => $money_count,
            ];
            $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
            $emailDetailInfo = EmailModel::getInstance()->createEmail(UID, EnumConfig::E_ResourceChangeReason['SYSTEM_MAIL'], $title, $content, $goodsList);
            if (empty($emailDetailInfo)) {
                $this->error('发布系统邮件失败');
            }
            operation_record(UID, "发布系统邮件，标题为{$title} 内容为{$content}");
            EmailModel::getInstance()->addEmailToAllUser($emailDetailInfo);
            CenterNotify::allMailRedSport();
            $this->success('发布系统邮件成功');
        } else {
            $this->display();
        }
    }

    //向个人发送邮件
    public function personal_send_email()
    {
        if (IS_POST) {
            $userID = I('userID');
            $isExists = EmailModel::getInstance()->isUserExists($userID);
            if (!$isExists) {
                $this->error('用户不存在');
            }
            $title = I('title');
            $content = I('content');
            if (empty($title) || empty($content)) {
                $this->error('标题或者内容不能为空');
            }
            //邮件附件
            $jewels_count = I('jewels_count', 0);
            $money_count = I('money_count', 0);
            //金币倍数
            $arrResource = [
                ['type' => EnumConfig::E_ResourceType['MONEY'], 'resourceNum' => &$money_count],
                ['type' => EnumConfig::E_ResourceType['JEWELS'], 'resourceNum' => &$jewels_count],
            ];
            foreach ($arrResource as $k => &$v) {
                $v['resourceNum'] = FunctionHelper::moneyInput($v['resourceNum'], $v['type']);
            }

            if ($jewels_count < 0 || $money_count < 0) {
                $this->error('钻石和金币数不能小于0');
            }
            $goodsArray = [
                EnumConfig::E_ResourceType['JEWELS'] => $jewels_count,
                EnumConfig::E_ResourceType['MONEY'] => $money_count,
            ];
            $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
            $emailDetailInfo = EmailModel::getInstance()->createEmail(UID, EnumConfig::E_ResourceChangeReason['USER_MAIL'], $title, $content, $goodsList);
            if (empty($emailDetailInfo)) {
                $this->error('发布用户邮件失败');
            }
            operation_record(UID, "发布用户邮件，用户ID为{$userID} 标题为{$title} 内容为{$content}");
            EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);
            // 小红点
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
            $this->success('发布用户邮件成功');
        } else {
            $this->display();
        }
    }

    //公告
    public function notice()
    {
        $count = M()->table('web_notice')->count();
        $Page = new \Think\Page($count, 15);
        $noticeList = M()
            ->table('web_notice')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        foreach ($noticeList as &$notice) {
            $notice['type_name'] = EnumConfig::E_NoticeTypeName[$notice['type']];
        }
        $this->assign('_page', $Page->show());
        $this->assign('_data', $noticeList);
        $this->display();
    }

    //删除公告
    public function notice_del()
    {
        $id = array_unique((array)I('id', 0));
        if (empty($id) || empty($id[0])) {
            $this->error('请选择要删除的公告');
        }

        //循环删除
        foreach ($id as $k => $v) {
            //删除数据库
            M('notice')->where(['id' => $id[$k]])->delete();
        }
        operation_record(UID, '删除公告');
        $this->success('删除成功');
    }

    //发布公告
    public function send_notice()
    {
        if (IS_POST) {
            $interval = I('interval');
            $times = I('times');
            $title = I('title', '');
            $type = I('type', 0);
            $content = I('content');
            $beginTime = time();
            $endTime = strtotime(I('endTime', 0));

            if ($beginTime > $endTime) {
                $this->error('公告结束时间必须大于现在时间');
            }

            if (!preg_match("/^[1-9][0-9]*$/", $interval) || !preg_match("/^[1-9][0-9]*$/", $times)) {
                $this->error('播放间隔和次数必须是正整数');
            }
            if (!$title) {
                $this->error('公告标题不能为空');
            }
            if (!$content) {
                $this->error('公告内容不能为空');
            }

            $notice = NoticeModel::getInstance()->notice($type, $title, $content, $beginTime, $endTime, $interval, $times);
            $result = NoticeModel::getInstance()->addNotice($notice);
            if (empty($result)) {
                $this->error('发送公告失败');
            }
            operation_record(UID, "发送公告  标题={$title}，内容={$content}");
            CenterNotify::sendNotice($notice);
            $this->success('发送公告成功');
        } else {
            $endTime = time() + 24 * 3600 + 59;
            $this->assign('endTime', $endTime);
            $this->display();
        }
    }

    //实物兑换
    public function convert()
    {
        //获取所有的实物兑换记录
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['realname'] = ['like', "%{$search}%"];
                    break;
                case 3:
                    $where['phone'] = $search;
                    break;
            }
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['create_time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['create_time'] = $res['data'];
        }
        $where['buyType'] = 4;
        $handle = I('handle');
        if ($handle != '') {
            $where['handle'] = $handle;
        }
        $data = D('Data')->get_all_data('pay_orders', $where, 15, 'handle asc,create_time desc');
        foreach ($data['_data'] as $k => $v) {
            if ($data['_data'][$k]['buyGoods'] == '房卡') {
                $data['_data'][$k]['handle'] = 1;
            }
            $user = M()->table('userInfo')->where(['userID' => $data['_data'][$k]['userid']])->find();
            $data['_data'][$k]['name'] = $user['name'];
            $data['_data'][$k]['consumenum_all'] = M('pay_orders')->where(['userID' => $data['_data'][$k]['userid'], 'buyType' => 4, 'consumeType' => 1])->sum('consumeNum');
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //进行兑换
    public function convert_handle()
    {
        $data['id'] = I('id');
        $data['handle'] = 1;
        if (M('pay_orders')->save($data)) {
            operation_record(UID, '处理实物兑换');
            $this->success('处理成功');
        } else {
            $this->error('处理失败');
        }
    }

    //大厅跳转和游戏参数配置
    public function web_page()
    {
        //获取所有常用参数
        $data1 = D('Data')->get_all_data('game_config', '', 25, '');
        foreach ($data1['_data'] as $k => &$v) {
            //处理金币倍数
            if (in_array($v['id'], $this->moneyMapField)) {
                $v['value'] = FunctionHelper::MoneyOutput($v['value']);
            }
        }
        // var_export($data1);
        $this->assign('_data1', $data1['_data']);
        $this->assign('_page', $data1['_page']);
        //获取已经生成文件
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/html';
        $dh = opendir($dir);
        $r = readdir($dh);
        $file_all = [];
        $i = 0;
        while (($file = readdir($dh)) != false) {
            if ($file != "." && $file != "..") {
                $create_time = filectime($dir . '/' . $file);
                $file_all[$i] = ['file_name' => $file, 'create_time' => $create_time, 'file_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/html/' . $file];
            }
            $i++;
        }
        closedir($dh);
        $this->assign('file_all', $file_all);
        $this->display();
    }

    //游戏参数编辑
    public function game_config_edit()
    {
        $data['id'] = I('id');
        $data['value'] = I('value');
        $data['desc'] = I('desc');
        if (!$data['desc']) {
            $this->error('描述必须填写');
        }
        //处理金币倍数
        if (in_array($data['id'], $this->moneyMapField)) {
            $data['value'] = FunctionHelper::MoneyInput($data['value']);
        }

        if (M('game_config')->save($data)) {
            operation_record(UID, '修改游戏参数配置');
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
    }

    //添加页面
    public function html_add()
    {
        if (IS_POST) {
            $file_name = I('file_name') . '.html';
            $content = html_entity_decode(I('editorValue'));
            $title = I('title');
            //判断文件是否存在
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/html/';
            if (file_exists($dir . $file_name)) {
                $this->error('该文件已经存在');
            }
            $html_head = '<!DOCTYPE html>
                            <html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                            <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
                            <title>' . $title . '</title>
                            <style>
                            body,div,p,ul,ol,li,h1,h2,h3,h4,h5{margin:0;padding:0}
                            body{font:12px/2 "\5FAE\8F6F\96C5\9ED1";}
                            li{list-style:none}
                            a{text-decoration:none}
                            img{border:none}
                            em{font-style:normal}
                            .wrap{padding:20px 30px}
                            .hl{color:#F00;font-weight:bold}
                            </style>
                            </head>
                            <body>
                            <div class="wrap">';
            $html_foot = "</div>
                            </body>
                            </html>";
            $file_content = $html_head . "\n" . $content . "\n" . $html_foot;
            //写入文件
            $fh = fopen($dir . $file_name, 'w');
            $res = fwrite($fh, $file_content);
            fclose($fh);
            if (!$res) {
                $this->error('文件生成失败' . $dir . $file_name);
            }
            operation_record(UID, '创建新的跳转页面' . $file_name);
            $this->success('文件创建成功，文件地址为http://' . $dir . $file_name);
        } else {
            $this->display();
        }
    }

    //文件预览
    public function html_view()
    {
        $file_name = I('file_name');
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/html/';
        $html = file_get_contents($dir . $file_name);
        echo $html;
    }

    //文件删除
    public function html_del()
    {

        $file_name = I('file_name');
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/html/' . $file_name;
        $res = unlink($dir);
        if ($res) {
            operation_record(UID, '删除了跳转html文件' . $file_name);
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //游戏常用参数配置
    public function game_config()
    {
        //
        // $fieldChange = ['registerGiveMoney', 'supportMinLimitMoney', 'useMagicExpressCostMoney', 'logonGiveMoneyEveryDay', 'friendRewardMoney', 'sendGiftMyLimitMoney', 'sendGiftMinMoney','bankMinSaveMoney', 'bankMinTakeMoney', 'bankMinTransfer'];

        if (IS_POST) {
            $data = I('post.');
            foreach ($data as $k => &$v) {
                //
                if (in_array($k, GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyInput($v);
                }
                M()->table('otherConfig')->where(['keyConfig' => $k])->save(['valueConfig' => $v]);
                ConfigModel::getInstance()->updateOtherConfigToRedis($k, $v);
            }
            $this->success('修改成功');
        } else {
            //获取所有的常用配置参数
            $config = M()->table('otherConfig')->select();
            //处理金币
            foreach ($config as $k => &$v) {
                if (in_array($v['keyconfig'], GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v['valueconfig'] = FunctionHelper::MoneyOutput($v['valueconfig']);
                }
                if (1 != C('IS_SHOW_JEWELS') && in_array($v['keyconfig'], getArrHideJewelsField())) {
                    unset($config[$k]);
                }
            }
//            echo MysqlTableFieldConfig::GAME_CONFIG_REGISTER_GIVE_JEWELS;
//            var_export($config);
            $this->assign('config', $config);
            $this->display();
        }
    }

    //分享管理
    public function share()
    {
        $type = I('type');
        $search = I('search');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['share_time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['share_time'] = $res['data'];
        }
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userid'] = $search;
                    break;
                case 2:
                    $where['name'] = ['like', "%{$search}%"];
                    break;
            }
        }
        $data = D('Data')->get_all_data('share_record', $where, 15, 'share_time desc');
        //WEB_SHARE_RECORD_MONEY
        FunctionHelper::moneyInArrayOutput($data['_data'], GeneralConfig::WEB_SHARE_RECORD_MONEY);
        //获取用户信息
        foreach ($data['_data'] as $k => &$v) {
            $v['name'] = UserModel::getInstance()->getUserInfo($v['userid'], 'name');;
            $v['share_type_name'] = EnumConfig::E_ShareTypeName[$v['share_address']];
        }
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //分享配置
    public function share_config()
    {
        if (IS_POST) {
            if ($_FILES['share_img']['size']) {
                //有文件上传
                $upload = new \Think\Upload(); // 实例化上传类
                $upload->maxSize = 3145728; // 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = 'home_img/'; // 设置附件上传（子）目录
                $upload->autoSub = false; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {
// 上传错误提示错误信息
                    $this->error($upload->getError());
                }
                $file_url = 'http://' . $_SERVER['HTTP_HOST'] . '/Uploads/' . $upload->savePath . $info['share_img']['savename'];
                M('game_config')->where(['key' => 'share_img'])->save(['value' => $file_url]);
            }
            //更新普通参数
            $data = $_POST;
            if (!$data['share_begin_time'] || !$data['share_end_time'] || !$data['share_url']) {
                $this->error('请选择活动时间');
            }
            if ($data['share_begin_time'] > $data['share_end_time']) {
                $this->error('活动时间错误');
            }
            $data['share_begin_time'] = strtotime($data['share_begin_time']);
            $data['share_end_time'] = strtotime($data['share_end_time']);
            foreach ($data as $k => &$v) {
                if (in_array($k, GeneralConfig::GAME_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyInput($v);
                }
                M('game_config')->where(['key' => $k])->save(['value' => $data[$k]]);
            }
            operation_record(UID, '编辑了分享配置');
            $this->success('发布成功');
        } else {
            $where = [
                'id' => ['between', [8, 28]],
            ];
            $config = M('game_config')->where($where)->select();
            ConfigModel::getInstance()->setTableCache(RedisConfig::Hash_web_game_config, $config);
            // var_export($config);
            $arr = [];
            foreach ($config as $k => &$v) {
                if (in_array($v['key'], GeneralConfig::GAME_CONFIG_MONEY)) {
                    $v['value'] = FunctionHelper::MoneyOutput($v['value']);
                }
                $arr[$config[$k]['key']] = $config[$k]['value'];
            }

            $this->assign('config', $arr);
            $this->display();
        }
    }

    // 同步上传
    public function uploadImg()
    {
        $upload = new \Think\Upload();
        $upload->maxSize = 314570000028;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = APP_ROOT_PATH . '/../Uploads/';
        if (!is_dir($upload->rootPath)) {
            @mkdir($upload->rootPath);
        }
        $upload->savePath = 'home_img/';
        $info = $upload->upload();
        if (!$info) {
            // $this->error($upload->getError());
            echo json_encode(['status' => 0, 'msg' => '上传失败:' . $upload->getError()]);
        } else {
            foreach ($info as $file) {
                $file_url = 'http://' . $_SERVER['HTTP_HOST'] . '/Uploads/' . $upload->savePath . $file['savename'];
                echo json_encode(['status' => 1, 'msg' => '上传成功', 'data' => $file_url]);
            }
        }
    }

    //转赠
    public function given()
    {
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop);
        //     $where['time'] = ['between', [$start, $stop]];
        // }

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }
        
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;
                case 2:
                    $where['targetID'] = $search;
                    break;
            }
        } else {
            $search = '';
            $type = '';
        }
        $count = M('give_record')->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $record = M('give_record')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('time desc')->select();
        foreach ($record as $k => &$v) {
            $v['name'] = UserModel::getInstance()->getUserInfo($v['userid'], 'name');
            $v['targetname'] = UserModel::getInstance()->getUserInfo($v['targetid'], 'name');
            $v['deduction'] = $v['value'] - $v['real_value'];
            $v['reward_name'] = EnumConfig::E_ResourceTypeName[$v['type']];
            $v['total_count'] = $v['total_money_count'] + $v['total_jewels_count'];
        }
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->display();
    }

    //转赠配置
    public function given_config()
    {
        if (IS_POST) {
            $data = I('post.');
            if (I('post.sendGiftRate') > 0.1 || I('post.sendGiftRate') < 0) {
                $this->error('转赠平台扣除税率必须在0 - 0.1区间');
            }
            foreach ($data as $k => $v) {
                //处理金币
                if (in_array($k, GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyInput($v);
                }
                M()->table('otherConfig')->where(['keyConfig' => $k])->save(['valueConfig' => $v]);
                ConfigModel::getInstance()->updateOtherConfigToRedis($k, $v);
            }
            $this->success('修改成功');
        } else {
            $config = GiveModel::getInstance()->getOtherConfig();
            //处理金币
            foreach ($config as $k => &$v) {
                if (in_array($k, GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyOutput($v);
                }
            }
            $this->assign('config', $config);
            $this->display();
        }
    }

    //救济金
    public function alms()
    {
        //获取领取救济金记录
        $where = [];
        $search = I('search');
        $type = I('type');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['time'] = ['between', [$start, $stop]];

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }        // }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userID'] = $search;
                    break;

                case 2:
                    $where['name'] = $search;
                    break;
            }
        }

        $count = M('support_record')->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $record = M('support_record')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('time desc')->select();
        // var_export($record);
        foreach ($record as $k => &$v) {
            $v['money'] = FunctionHelper::MoneyOutput($v['money']);
            $v['total_get_money'] = FunctionHelper::MoneyOutput($v['total_get_money']);
            $user = UserModel::getInstance()->getUserInfo($v['userid'], ['jewels', 'account', 'name']);
            $v = array_merge($v, $user);
        }
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //救济金配置
    public function alms_config()
    {
        if (IS_POST) {
            $data = I('post.');
            //修改数据库配置
            foreach ($data as $k => $v) {
                //处理金币
                if (in_array($k, GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyInput($v);
                }

                M()->table('otherConfig')->where(['keyConfig' => $k])->save(['valueConfig' => $v]);
                ConfigModel::getInstance()->updateOtherConfigToRedis($k, $v);
            }
            $this->success('修改成功');
        } else {
            $config = BankModel::getInstance()->getOtherConfig();
            //处理金币
            foreach ($config as $k => &$v) {
                if (in_array($k, GeneralConfig::OTHER_CONFIG_MONEY)) {
                    $v = FunctionHelper::MoneyOutput($v);
                }
            }

            $this->assign('config', $config);
            $this->display();
        }
    }

    //世界广播
    public function radio()
    {
        //获取所有的世界广播记录
        $where = [];
        $search = I('search');
        $type = I('type');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['time'] = ['between', [$start, $stop]];
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }
        if ($type && $search) {
            $where['userID'] = ['like', "%{$search}%"];
        }
        $count = M('horn')->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $record = M('horn')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($record as $k => &$v) {
            $v['name'] = UserModel::getInstance()->getUserInfo($v['userid'], 'name');
        }
        $this->assign('_data', $record);
        $this->assign('_page', $Page->show());
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //世界广播消耗配置
    public function radio_config()
    {
        if (IS_POST) {
            $data = I('post.');
            //修改数据库配置
            foreach ($data as $k => $v) {
                M()->table('otherConfig')->where(['keyConfig' => $k])->save(['valueConfig' => $v]);
                ConfigModel::getInstance()->updateOtherConfigToRedis($k, $v);
            }
            $this->success('修改成功');
        } else {
            $config = BankModel::getInstance()->getOtherConfig();
            $this->assign('config', $config);
            $this->display();
        }
    }


    //银行管理
    public function bank()
    {
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['b.userID'] = $search;
                    break;
                case 2:
                    $where['u.name'] = $search;
                    break;
            }
        }
        $start = I('start');
        $stop = I('stop');
//        if ($start && $stop) {
//            $start = strtotime($start);
//            $stop = strtotime($stop);
//            $where['time'] = ['between', [$start, $stop]];
//        }

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }

        $count = M()->table('web_bank_record as b ')
            ->join('left join ' . MysqlConfig::Table_userinfo . ' as u on u.userID=b.userID')
            ->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $res = M()->table('web_bank_record as b')
            ->join('left join ' . MysqlConfig::Table_userinfo . ' as u on u.userID=b.userID')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->where($where)
            ->field('b.*')
            ->order('time desc')->select();
        foreach ($res as $k => &$v) {
            $v['name'] = UserModel::getInstance()->getUserInfo($v['userid'], 'name');
            $v['operate_type_name'] = EnumConfig::E_BankOperateTypeName[$v['type']];
            if ($v['type'] == EnumConfig::E_BankOperateType['TRAN']) {
                //转账
                $v['targetname'] = UserModel::getInstance()->getUserInfo($v['targetid'], 'name');
            } else {
                $v['targetid'] = $v['userid'];
                $v['targetname'] = $v['name'];
            }
        }
        $bankmoney = M()->table('userInfo')->where(['isVirtual' => 0])->sum('bankmoney');
        $this->assign('bankmoney', $bankmoney);
        $this->assign('_data', $res);
        $this->assign('_page', $Page->show());
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    #银行转账配置
    public function bank_give_config()
    {
        if (IS_POST) {
            $data = I('post.');
            foreach ($data as $k => $v) {
                M()->table('otherConfig')->where(['keyConfig' => $k])->save(['valueConfig' => $v]);
                ConfigModel::getInstance()->updateOtherConfigToRedis($k, $v);
            }
            $this->success('修改成功');
        } else {
            $config = BankModel::getInstance()->getOtherConfig();
            $this->assign('config', $config);
            $this->display();
        }
    }

    #银行统计
    public function bank_count()
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

        $bank_data7 = [];
        $data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['SAVE'], 'time' => ['between', [$v, $end]]])->sum('money');
            $c_money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TAKE'], 'time' => ['between', [$v, $end]]])->sum('money');
            $z_money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TRAN'], 'time' => ['between', [$v, $end]]])->sum('money');
            $bank_data7['money'][] = (int)$money/100 ? $money/100 : 0;
            $bank_data7['c_money'][] = (int)$c_money/100 ? $c_money/100 : 0;
            $bank_data7['z_money'][] = (int)$z_money/100 ? $z_money/100 : 0;
            $data7[] = date("m-d", $v);
        }

        $this->assign('bank_data7', $bank_data7);
        $this->assign('data7', $data7);

        $bank_data12 = [];
        $data12 = [];
        foreach ($year as $k => $v) {
            $money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['SAVE'], 'time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('money');
            $c_money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TAKE'], 'time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('money');
            $z_money = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TRAN'], 'time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('money');
            $bank_data12['money'][] = (int)$money/100 ? $money/100 : 0;
            $bank_data12['c_money'][] = (int)$c_money/100 ? $c_money/100 : 0;
            $bank_data12['z_money'][] = (int)$z_money/100 ? $z_money/100 : 0;
            $data12[] = $year[$k]['month'];
        }

        $this->assign('bank_data12', ($bank_data12));
        $this->assign('data12', ($data12));

        //存钱总次数
        $Save_count = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['SAVE']])->count();
        $max_Save_count = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['SAVE']])->order('money desc')->getField('money') ? M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['SAVE']])->order('money desc')->getField('money') : 0;

        //取钱总次数
        $Withdraw_count = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TAKE']])->count();
        $max_Withdraw_count = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TAKE']])->order('money desc')->getField('money') ? M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TAKE']])->order('money desc')->getField('money') : 0;
        //转账总次数
        $Transfer_conut = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TRAN']])->count();
        $max_Transfer_count = M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TRAN']])->order('money desc')->getField('money') ? M('bank_record')->where(['type' => EnumConfig::E_BankOperateType['TRAN']])->order('money desc')->getField('money') : 0;

        //平台银行总数
        $total = M('')->table('userInfo')->sum('bankmoney');

        $this->assign('Save_count', $Save_count);
        $this->assign('Withdraw_count', $Withdraw_count);
        $this->assign('Transfer_conut', $Transfer_conut);
        $this->assign('max_Save_count', $max_Save_count/100);
        $this->assign('max_Withdraw_count', $max_Withdraw_count/100);
        $this->assign('max_Transfer_count', $max_Transfer_count);
        $this->assign('total', $total);
        $this->display();
    }

    // 登录管理
    public function login()
    {
        $type = I('type', 0);
        $search = trim(I('search', ''));

        $start = urldecode(I('start'));
        $end = urldecode(I('stop'));
        if (!empty($start) && !empty($end)) {
            if ($start > $end) {
                $this->error("结束时间不能大于开始时间");
            }

            $start = strtotime($start);
            $end = strtotime($end) + 24 * 3600 - 1;
            $map['time'] = ['between', [$start, $end]];
        } else {
            if (!$search && !$type) {
                $start = strtotime(date('Y-m-d', time()));
                $end = $start + 24 * 3600 - 1;
                $map['time'] = ['between', [$start, $end]];
            }
        }

        if ($search) {
            if ($type == 0) {
                $this->error("请选择搜索类型");
            }
            switch ($type) {
                case 1:
                    $map['l.userID'] = $search;
                    break;

                case 2:
                    $map['u.name'] = array('like', "%$search");
                    break;
            }
        }

        $User = M()->table('statistics_logonandlogout as l');
        $count = $User->join('left join userInfo as u on u.userID = l.userID')->where($map)->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $list = M()->table('statistics_logonandlogout as l')
            ->join('left join userInfo as u on u.userID = l.userID')
            ->order('time desc')
            ->field('l.*,u.name,u.account')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->where($map)
            ->select();
        $this->assign('login_type', ['1' => "登录", '2' => "退出"]);
        $this->assign('_data', $list);
        $this->assign('_page', $show);
        $this->assign('start', $start);
        $this->assign('stop', $end);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    public function rewardsPool()
    {
        if (IS_GET) {
            $type = I('type', 0);
            $search = trim(I('search', ''));
            $where['b.type'] = EnumConfig::E_RoomType['GOLD'];
            if ($search && $type > 0) {
                switch ($type) {
                    case 1:
                        $where['b.name'] = array('like', "%$search%");
                        break;
                }
            }
            $count = M()->table('roomBaseInfo as b')->join('inner join rewardsPool as r on r.roomID = b.roomID')->where($where)->count();
            $Page = new \Think\Page($count, 10);
            $dataLsit = M()->table('roomBaseInfo as b')
                ->join('inner join rewardsPool as r on r.roomID = b.roomID')
                ->where($where)
                ->field('b.name,r.roomID')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
            foreach ($dataLsit as &$data) {
                $name = $data['name'];
                $data = LobbyModel::getInstance()->getRewardsPoolToRedis($data['roomid']);
                $data['name'] = $name;
            }
            $this->assign('data', $dataLsit);
            $this->assign('page', $Page->show());
            $this->assign('type', $type);
            $this->assign('search', $search);
            $this->display();
        } else {
            $data = [
                'poolMoney' => (int)I('poolMoney'),
                'platformCtrlPercent' => (int)I('platformCtrlPercent'),
            ];

            $result = LobbyModel::getInstance()->updateRewardsPool(I('roomID'), $data);
            if (empty($result)) {
                $this->error('修改失败');
            }
            $this->success('修改成功');
        }
    }

    // 好友打赏
    public function reward()
    {
        $type = I('type', '');
        $search = I('search', '');

        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        // if ($start && $stop) {
        //     $start = strtotime($start);
        //     $stop = strtotime($stop) + 24 * 3600 - 1;
        //     $where['time'] = ['between', [$start, $stop]];
        // } else {
        //     if (!$type && !$search) {
        //         $start = strtotime(date('Y-m-d', time()));
        //         $stop = $start + 24 * 3600 - 1;
        //         $where['time'] = ['between', [$start, $stop]];
        //     }
        // }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['time'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['fk.userID'] = $search;
                    break;

                case 2:
                    $where['u.name'] = $search;
                    break;
            }
        }

        $User = M()->table('statistics_friendtakemoney')->table('statistics_friendtakemoney as fk')
            ->join('left join userInfo as u on u.userID = fk.userID');
        $count = $User->where($where)->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $list = M()
            ->table('statistics_friendtakemoney as fk')
            ->join('left join userInfo as u on u.userID = fk.userID')
            ->where($where)
            ->field('fk.*,u.name')
            ->order('time desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        foreach ($list as &$v) {
            $v['target_name'] = M()->table('userInfo')->where(['userID' => $v['userid']])->getfield('name');
        }

        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('search', $search);
        $this->assign('type', $type);
        $this->display();
    }


    //统计图表
    public function statistical_chart()
    {
        //7天
        $week = [];
        $begin = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'));
        for ($i = 0; $i < 7; $i++) {
            $begin = $begin - 24 * 3600;
            $week[] = $begin;
        }
        $week = array_reverse($week);
        //12个月
        $year = $this->getLastTimeArea(date('Y', time()), date('m', time()), 12);
        $year = array_reverse($year);

        $data7 = [];
        $data12 = [];

        //七天签到数据
        $sign_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY'], 'signTime' => ['between', [$v, $end]]])->sum('num');
            $jewels = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS'], 'signTime' => ['between', [$v, $end]]])->sum('num');
            $sign_data7['money'][] = (int)$money ? $money : 0;
            $sign_data7['jewels'][] = (int)$jewels ? $jewels : 0;
            $data7[] = date("m-d", $v);
        }
        $this->assign('sign_data7', ($sign_data7));
        $this->assign('data7', ($data7));

        $sign_data12 = [];
        foreach ($year as $k => $v) {
            $money = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY'], 'signTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('num');
            $jewels = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS'], 'signTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('num');
            $sign_data12['money'][] = (int)$money ? $money : 0;
            $sign_data12['jewels'][] = (int)$jewels ? $jewels : 0;
            $data12[] = $year[$k]['month'];
        }
        $this->assign('sign_data12', ($sign_data12));
        $this->assign('data12', ($data12));

        //签到总次数
        $sign_count = M('sign_record')->count();
        //签到累计金币
        $sign_all_money = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY']])->sum('num');
        //签到累计钻石
        $sign_all_jewels = M('sign_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS']])->sum('num');
        //用户签到累计获得最多金币数
        $sql = "select sum(num) as max from web_sign_record where prizeType=" . EnumConfig::E_ResourceType['MONEY'] . " group by userID order by max DESC limit 1";
        $sign_user_max_money = M()->query($sql)[0]['max'];
        //用户签到累计获得最多房卡数
        $sql = "select sum(num) as max from web_sign_record where prizeType=" . EnumConfig::E_ResourceType['JEWELS'] . " group by userID order by max DESC limit 1";
        $sign_user_max_jewels = M()->query($sql)[0]['max'];
        //用户签到累计次数最多的次数和玩家ID
        $sql = "select m.user_count as user_count ,m.userID as user_id from (select count(*) as user_count,userID from web_sign_record group by userID) as m order by user_count DESC limit 1";
        $sign_max_user_count = M()->query($sql)[0]['user_count'];
        $sign_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('sign_count', $sign_count);
        $this->assign('sign_all_jewels', $sign_all_jewels);
        $this->assign('sign_all_money', $sign_all_money);
        $this->assign('sign_user_max_jewels', $sign_user_max_jewels);
        $this->assign('sign_user_max_money', $sign_user_max_money);
        $this->assign('sign_max_user_count', $sign_max_user_count);
        $this->assign('sign_max_user_id', $sign_max_user_id);

        //分享七天统计
        $share_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('share_record')->where(['share_time' => ['between', [$v, $end]]])->count();
            $share_data7['count'][] = (int)$count ? $count : 0;
        }
        $this->assign('share_data7', ($share_data7));

        //分享最近12月数据统计
        $share_data12 = [];
        foreach ($year as $k => $v) {
            $count = M('share_record')->where(['share_time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->count();
            $share_data12['count'][] = (int)$count ? $count : 0;
        }
        $this->assign('share_data12', ($share_data12));

        //分享总次数
        $share_count = M('share_record')->count();
        //分享累计金币
        $share_all_money = M('share_record')->sum('send_money');
        //分享累计钻石
        $share_all_jewels = M('share_record')->sum('send_jewels');
        //用户分享累计获得最多金币数
        $sql = "select sum(send_money) as max from web_share_record group by userID order by max DESC limit 1";
        $share_user_max_money = M()->query($sql)[0]['max'];
        //用户分享累计获得最多房卡数
        $sql = "select sum(send_jewels) as max from web_share_record group by userID order by max DESC limit 1";
        $share_user_max_jewels = M()->query($sql)[0]['max'];
        //用户分享累计次数最多的次数和玩家ID
        $sql = "select m.user_count as user_count ,m.userid as user_id from (select count(*) as user_count,userid from web_share_record group by userid) as m order by user_count DESC LIMIT 1";
        $share_max_user_count = M()->query($sql)[0]['user_count'];
        $share_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('share_count', $share_count);
        $this->assign('share_all_money', $share_all_money);
        $this->assign('share_all_jewels', $share_all_jewels);
        $this->assign('share_user_max_money', $share_user_max_money);
        $this->assign('share_user_max_jewels', $share_user_max_jewels);
        $this->assign('share_max_user_count', $share_max_user_count);
        $this->assign('share_max_user_id', $share_max_user_id);

        //救济金七天统计
        $support_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('support_record')->where(['time' => ['between', [$v, $end]]])->sum('money');
            $support_data7['money'][] = (int)$money ? $money : 0;
        }
        $this->assign('support_data7', ($support_data7));

        //救济金最近12月数据统计
        $support_data12 = [];
        foreach ($year as $k => $v) {
            $money = M('support_record')->where(['time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('money');
            $support_data12['money'][] = (int)$money ? $money : 0;
        }
        $this->assign('support_data12', ($support_data12));

        //救济金总次数
        $support_count = M('support_record')->count();
        //救济金累计金币
        $support_all_money = M('support_record')->sum('money');
        //用户救济金累计获得最多金币数
        $sql = "select sum(money) as max from web_support_record group by userID order by max DESC limit 1";
        $support_user_max_money = M()->query($sql)[0]['max'];
        //用户救济金累计次数最多的次数和玩家ID
        $sql = "select m.user_count as user_count ,m.userID as user_id from (select count(*) as user_count,userID from web_support_record group by userID) as m order by user_count DESC limit 1";
        $support_max_user_count = M()->query($sql)[0]['user_count'];
        $support_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('support_count', $support_count);
        $this->assign('support_all_money', $support_all_money);
        $this->assign('support_user_max_money', $support_user_max_money);
        $this->assign('support_max_user_count', $support_max_user_count);
        $this->assign('support_max_user_id', $support_max_user_id);

        //转赠七天统计
        $give_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('give_record')->where(['time' => ['between', [$v, $end]]])->count();
            $money = M('give_record')->where(['type' => EnumConfig::E_ResourceType['MONEY'], 'time' => ['between', [$v, $end]]])->sum('value');
            $jewels = M('give_record')->where(['type' => EnumConfig::E_ResourceType['JEWELS'], 'time' => ['between', [$v, $end]]])->sum('value');
            $give_data7['count'][] = (int)$count ? $count : 0;
            $give_data7['money'][] = (int)$money ? $money : 0;
            $give_data7['jewels'][] = (int)$jewels ? $jewels : 0;
        }
        $this->assign('give_data7', ($give_data7));

        //转赠最近12月数据统计
        $give_data12 = [];
        foreach ($year as $k => $v) {
            $count = M('give_record')->where(['time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->count();
            $money = M('give_record')->where(['type' => EnumConfig::E_ResourceType['MONEY'], 'time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('value');
            $jewels = M('give_record')->where(['type' => EnumConfig::E_ResourceType['JEWELS'], 'time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('value');
            $give_data12['count'][] = (int)$count ? $count : 0;
            $give_data12['money'][] = (int)$money ? $money : 0;
            $give_data12['jewels'][] = (int)$jewels ? $jewels : 0;
        }
        $this->assign('give_data12', ($give_data12));

        //转赠总次数
        $give_count = M('give_record')->count();
        //转赠金累计金币
        $give_all_money = M('give_record')->where(['type' => EnumConfig::E_ResourceType['MONEY']])->sum('value');
        //转赠金累计钻石
        $give_all_jewels = M('give_record')->where(['type' => EnumConfig::E_ResourceType['JEWELS']])->sum('value');
        //用户转赠累计最多金币数
        $sql = "select sum(value) as max from web_give_record where type = 1 group by userID order by max DESC limit 1";
        $give_user_max_money = M()->query($sql)[0]['max'];
        //用户转赠累计最多钻石数
        $sql = "select sum(value) as max from web_give_record where type = 2 group by userID order by max DESC limit 1";
        $give_user_max_jewels = M()->query($sql)[0]['max'];

        //用户转赠累计次数最多的次数和玩家ID
        $sql = "select m.user_count as user_count ,m.userID as user_id from (select count(*) as user_count,userID from web_give_record group by userID) as m order by user_count DESC limit 1";
        $give_max_user_count = M()->query($sql)[0]['user_count'];
        $give_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('give_count', $give_count);
        $this->assign('give_all_money', $give_all_money);
        $this->assign('give_all_jewels', $give_all_jewels);
        $this->assign('give_user_max_money', $give_user_max_money);
        $this->assign('give_user_max_jewels', $give_user_max_jewels);
        $this->assign('give_max_user_count', $give_max_user_count);
        $this->assign('give_max_user_id', $give_max_user_id);

        //抽奖七天统计数据
        $turn_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $money = M('turntable_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY'], 'turntableTime' => ['between', [$v, $end]]])->sum('num');
            $jewels = M('turntable_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS'], 'turntableTime' => ['between', [$v, $end]]])->sum('num');
            $turn_data7['money'][] = (int)$money ? $money : 0;
            $turn_data7['jewels'][] = (int)$jewels ? $jewels : 0;
        }
        $this->assign('turn_data7', ($turn_data7));

        //抽奖最近12月统计
        $turn_data12 = [];
        foreach ($year as $k => $v) {
            $money = M('turntable_record')->where(['prizeType' => EnumConfig::E_ResourceType['MONEY'], 'turntableTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('num');
            $jewels = M('turntable_record')->where(['prizeType' => EnumConfig::E_ResourceType['JEWELS'], 'turntableTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('num');
            $turn_data12['money'][] = (int)$money ? $money : 0;
            $turn_data12['jewels'][] = (int)$jewels ? $jewels : 0;
        }
        $this->assign('turn_data12', ($turn_data12));
        //抽奖总次数
        $turn_count = M('turntable_record')->count();
        //抽奖累计金币数
        $turn_all_money = M('turntable_record')->where(['prizeType' => 1])->sum('num');
        //抽奖累计钻石数
        $turn_all_jewels = M('turntable_record')->where(['prizeType' => 2])->sum('num');
        //获取用户获得最大金币数
        $sql = "select max(m.num) as max_num from (select sum(num) as num from web_turntable_record where prizeType=" . EnumConfig::E_ResourceType['MONEY'] . " group by userID) as m ORDER BY max_num DESC limit 1";
        $turn_user_max_money = M()->query($sql)[0]['max_num'];
        //获取用户获得最大钻石数
        $sql = "select max(m.num) as max_num from (select sum(num) as num from web_turntable_record where prizeType=" . EnumConfig::E_ResourceType['JEWELS'] . " group by userID) as m ORDER BY max_num DESC limit 1";
        $turn_user_max_jewels = M()->query($sql)[0]['max_num'];
        //玩家抽奖最多次数的玩家ID和次数
        $sql = "select m.user_count as user_count, m.userID as user_id from (select count(*) as user_count,userID from web_turntable_record group by userID) as m ORDER BY user_count DESC limit 1";
        $turn_max_user_count = M()->query($sql)[0]['user_count'];
        $turn_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('turn_count', $turn_count);
        $this->assign('turn_all_jewels', $turn_all_jewels);
        $this->assign('turn_all_money', $turn_all_money);
        $this->assign('turn_user_max_jewels', $turn_user_max_jewels);
        $this->assign('turn_user_max_money', $turn_user_max_money);
        $this->assign('turn_max_user_count', $turn_max_user_count);
        $this->assign('turn_max_user_id', $turn_max_user_id);


        //广播七天统计数据
        $horn_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('horn')->where(['reqTime' => ['between', [$v, $end]]])->count();
            $cost = M('horn')->where(['reqTime' => ['between', [$v, $end]]])->sum('cost');
            $horn_data7['count'][] = (int)$count ? $count : 0;
            $horn_data7['cost'][] = (int)$cost ? $cost : 0;
        }
        $this->assign('horn_data7', ($horn_data7));

        //广播最近12月统计
        $horn_data12 = [];
        foreach ($year as $k => $v) {
            $count = M('horn')->where(['reqTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->count();
            $cost = M('horn')->where(['reqTime' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->sum('cost');
            $horn_data12['count'][] = (int)$count ? $count : 0;
            $horn_data12['cost'][] = (int)$cost ? $cost : 0;
        }
        $this->assign('horn_data12', ($horn_data12));
        //广播总次数
        $horn_count = M('horn')->count();
        //广播累计钻石数
        $horn_all_cost = M('horn')->sum('cost');
        //用户广播消耗数最多的数量
        $sql = "select max(m.num) as max_num from (select sum(cost) as num from web_horn group by userID) as m ORDER BY max_num DESC limit 1";
        $horn_user_max_cost = M()->query($sql)[0]['max_num'];
        //玩家广播最多次数的玩家ID和次数
        $sql = "select m.user_count as user_count, m.userID as user_id from (select count(*) as user_count,userID from web_horn group by userID) as m ORDER BY user_count DESC limit 1";
        $horn_max_user_count = M()->query($sql)[0]['user_count'];
        $horn_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('horn_count', $horn_count);
        $this->assign('horn_all_cost', $horn_all_cost);
        $this->assign('horn_user_max_cost', $horn_user_max_cost);
        $this->assign('horn_max_user_count', $horn_max_user_count);
        $this->assign('horn_max_user_id', $horn_max_user_id);


        //反馈七天统计数据
        $feed_data7 = [];
        foreach ($week as $k => $v) {
            $end = $v + 24 * 3600;
            $count = M('feedback')->where(['f_time' => ['between', [$v, $end]]])->count();
            $feed_data7['count'][] = (int)$count ? $count : 0;
        }
        $this->assign('feed_data7', ($feed_data7));

        //反馈最近12月统计
        $feed_data12 = [];
        foreach ($year as $k => $v) {
            $count = M('feedback')->where(['f_time' => ['between', [$year[$k]['begin2'], $year[$k]['end2']]]])->count();
            $feed_data12['count'][] = (int)$count ? $count : 0;
        }
        $this->assign('feed_data12', ($feed_data12));
        //反馈总次数
        $feed_count = M('feedback')->count();
        $feed_close_count = M('feedback')->where(['read_type' => EnumConfig::E_FeedbackReadType['CLOSE']])->count();
        $feed_no_close_count = M('feedback')->where(['read_type' => ['neq', EnumConfig::E_FeedbackReadType['CLOSE']]])->count();
        //玩家反馈最多次数的玩家ID和次数
        $sql = "select m.user_count as user_count, m.userID as user_id from (select count(*) as user_count,userID from web_feedback group by userID) as m ORDER BY user_count DESC limit 1";
        $feed_max_user_count = M()->query($sql)[0]['user_count'];
        $feed_max_user_id = M()->query($sql)[0]['user_id'];

        $this->assign('feed_count', $feed_count);
        $this->assign('feed_close_count', $feed_close_count);
        $this->assign('feed_no_close_count', $feed_no_close_count);
        $this->assign('feed_max_user_count', $feed_max_user_count);
        $this->assign('feed_max_user_id', $feed_max_user_id);

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


}
