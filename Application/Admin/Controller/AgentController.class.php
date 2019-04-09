<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

use config\ErrorConfig;
use config\GameRedisConfig;
use helper\FunctionHelper;
use manager\RedisManager;
use model\AgentModel;
use model\AppModel;
use model\UserModel;
use Qrcode\QRcode;
use config\RedisConfig;

class AgentController extends AdminController
{
    protected $xiaji = '';

    //代理列表
    public function member_list()
    {
        //获取所有代理信息
        $type = I('type');
        $search = I('search');
        $searchType = (int)I("searchType");
       
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
//        if ($start && $stop) {
//            $start = strtotime($start);
//            $stop = strtotime($stop) + 24 * 3600 - 1;
//            $where['register_time'] = ['between', [$start, $stop]];
//        }

        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['register_time'] = $res['data'];
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['username'] = $search;
                    break;
                case 2:
                    $where['a.userid'] = $search;
                    break;
                case 3:
                    $where['name'] = $search;
                    break;
                case 4:
                    $where['agentid'] = $search;
                    break;
            }
        }

        $arrSearchType = [
            0 => [
                'word' => '请选择',
//                'type' => '2',
                'val' => 0,
            ],
//            1 => [
//                'word' => '代理链查找',
////                'type' => '1',
//                'val' => 1,
//            ],
            2 => [
                'word' => '上级代理链查找',
//                'type' => '2',
                'val' => 2,
            ],
            3 => [
                'word' => '下级代理链查找',
//                'type' => '3',
                'val' => 3,
            ],
            4 => [
                'word' => '直属会员查找',
//                'type' => '3',
                'val' => 4,
            ],
            5 => [
                'word' => '直属下级查找',
//                'type' => '3',
                'val' => 5,
            ],
        ];


        //$data = D('Data')->get_all_data('agent_member', $where, 10, '');

        $count = M('agent_member as a')->join('userInfo as u ON a.userid = u.userid')->where($where)->count();
        $page = new \Think\Page($count,10);
        $data = M('agent_member as a')
            ->join('userInfo as u ON a.userid = u.userid')
            ->join('left join web_pay_orders as wpo on wpo.userID=u.userID')
            ->join('left join user_cash_application as uca on uca.userID=u.userID')
            ->join('left join web_admin_action as waaa on waaa.userID=u.userID and waaa.actionType = 1')
            ->join('left join web_admin_action as wbbb on wbbb.userID=u.userID and wbbb.actionType = 2')
            ->field('a.*,u.name, wpo.id as wpoid, waaa.id as waaaid, uca.Id as ucaid, wbbb.id as wbbbid')
            ->group('u.userID')
            ->where($where)->limit($page->firstRow.','.$page->listRows)->order('a.id desc')->select();
        $data = [
            '_page' =>  $page->show(),
            '_data'  =>  $data,
        ];

        $member = $data['_data'];

        $redis = RedisManager::getGameRedis();
        //获取金币数和房卡数，以及统计下级玩家数和代理数
        foreach ($member as $k => &$v) {
            $userInfo = UserModel::getInstance()->getUserInfo($member[$k]['userid'],['jewels','money','name']);
            $member[$k]['jewels'] = $userInfo['jewels'];
            $member[$k]['money'] = $userInfo['money'];
            $member[$k]['money'] = FunctionHelper::MoneyOutput((int)$member[$k]['money']);
           // $encode = mb_detect_encoding($userInfo['name'], array("ASCII", 'UTF-8', 'GB2312', "GBK", 'BIG5'));
            //$member[$k]['gamename'] = iconv('GB2312', 'UTF-8', $userInfo['name']);
            $member[$k]['member_count'] = M('agent_member')->where(['superior_agentid' => $member[$k]['agentid']])->count();
            $member[$k]['user_count'] = M('agent_bind')->where(['agentID' => $member[$k]['agentid']])->count();
            $v['user_count'] -= $v['member_count'];
            $member[$k]['balance'] = sprintf("%.2f", $member[$k]['balance'] / 100);
            $member[$k]['under_money'] = sprintf("%.2f", $member[$k]['under_money'] / 100);
            $member[$k]['not_under_money'] = sprintf("%.2f", $member[$k]['not_under_money'] / 100);
        }
//        var_export($member);exit;
//        var_export($arrSearchType[$searchType]);exit;
        if (isset($arrSearchType[$searchType]) && 1 === count($member)) {
            switch ($arrSearchType[$searchType]['val']) {
                case 1:

                    break;

                case 2:
                    $upAgentList = AgentModel::getInstance()->getUpAgentList($member[0]);
                    array_shift($upAgentList);
//                    var_export($upAgentList);exit;
//                    $member = array_merge($upAgentList);
                    $member = $upAgentList;
                    break;

                case 3:
                    //getDownAgentInfo
                    $upAgentList = AgentModel::getInstance()->getDownAgentInfo($member[0]['agentid']);
//                    var_export($upAgentList);exit;
//                    $member = array_merge($upAgentList);
                    $member = $upAgentList;
                    break;

                case 4:
                    //getDownAgentInfo
                    $upAgentList = D('Common/agent')->getMyUserByAgentID($member[0]['agentid']);
                    $member = $upAgentList['userList'];
                    foreach ($member as $k => &$v) {
                        $v['superior_agentid'] = $v['agentid'];
                        unset($v['agentid']);
                    }
//                    var_export($upAgentList);exit;
//                    $member = array_merge($upAgentList);
                    break;

                case 5:
                    //getDownAgentInfo
                    $member = D('Common/agent')->getUnderlingByAgentID($member[0]['agentid']);
                    break;

                default:
                    break;
            }
        }

        $this->assign([
            'isHideNavbar' => I("isHideNavbar", 0),//是否隐藏navbar 0否 1是
            'arrSearchType' => $arrSearchType,
            'searchType' => $searchType,
        ]);
        $this->assign('_data', $member);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //代理充值房卡金币
    public function member_recharge()
    {
        if (IS_POST) {
            $my = M('adminmember')->find(UID);
            $userid = (int)I('userid');
            $type = (int)I('type');
            $amount = (int)I('amount');
            if ($amount < 1) {
                $this->error('充值数不能小于1');
            }
            //发送消息
            $socket = \Socket::get();
            $send = new \SendFunction();
            if ($socket->connet == false) {
                $this->error('充值失败，原因：服务连接不上');
            }
            $rechargePacket = $send->makeRechargePacket($userid, $amount, $type);
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
            if ($type == 1) {
                $goods = '金币';
            } elseif ($type == 2) {
                $goods = '钻石';
            }
            //记录
            $data = [
                'userid' => $userid,
                'goodstype' => $type,
                'num' => $amount,
                'sendtype' => 1,
                '_desc' => '管理用户' . $my['username'] . '为游戏ID为' . $userid . '的代理用户充值' . $amount . $goods,
                'time' => time(),
            ];
            M('agentrecharge')->add($data);
            $this->success('充值成功');
        } else {
            $userid = I('userid');
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    //代理分等级
    public function member_rules()
    {
        $data = D('Data')->get_all_data('agent_group', '', 10, 'id desc');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //编辑各级代理权限
    public function group_edit()
    {
        if (IS_POST) {
            $rules = I('rules');
            if (isset($rules)) {
                $rules = (array)I('rules');
                sort($rules);
                $str = '';
                foreach ($rules as $k => $v) {
                    $str .= $v . ',';
                }
                $rules = rtrim($str, ',');
                $data['rules'] = $rules;
            }
            $data['group_name'] = I('group_name');
            $data['disabled'] = intval(I('disabled'));
            $data['id'] = intval(I('id'));
            if (M('agent_group')->save($data)) {
                operation_record(UID, '编辑名为' . $data['group_name'] . '代理分组');
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            //获取所有的菜单
            $where['hide'] = 0;
            $menus = M('Agentmenu')->where($where)->select();
            $this->assign('_menus', $menus);
            $group = M('agent_group')->find(I('get.id'));
            $str = $group['rules'];
            $rules = array();
            $rules = explode(',', $str);
            $rules = json_encode($rules);
            $this->assign('rules', $rules);
            $this->assign('group', $group);
            $this->display();
        }
    }

    //删除分组
    public function group_del()
    {
        $id = I('id');
        //判断分组下有没有用户
        $member = M('agent_member')->where(['agent_level' => $id])->find();
        if ($member) {
            $this->error('该分组下有用户不能直接删除');
        }
        if (M('agent_group')->delete($id)) {
            operation_record(UID, '删除代理分组');
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //解除绑定
    public function remove_bind()
    {
        $data['id'] = I('id');
        $data['superior_agentid'] = '';
        $data['superior_username'] = '';
        if (M('agent_member')->save($data)) {
            //解除绑定的同时删掉绑定表的数据
            $userid = M('agent_member')->where(['id' => $data['id']])->getField('userid');
            M('agent_bind')->where(['userID' => $userid])->delete();
            operation_record(UID, "解除玩家ID为{$userid}的代理绑定");
            $this->success('解绑成功');
        } else {
            $this->error('解绑失败');
        }
    }

    //删除代理
    public function member_del()
    {
        $id = I('id');
        $member = M('agent_member')->find($id);
        $under_member = M('agent_member')->where(['superior_agentid' => $member['agentid']])->find();
        if ($under_member) {
            $this->error('该代理下面有下级代理不能直接删除');
        }


        $under_user = M('agent_bind')->where(['agentID' => $member['agentid']])->find();
        if ($under_user) {
            $this->error('该代理下面有玩家不能直接删除');
        }
        //移除redis 代理集合
        if (M('agent_member')->delete($id)) {
            RedisManager::getGameRedis()->sRem(GameRedisConfig::Set_web_agentmember, $member['userid']);
            operation_record(UID, '删除用户名为' . $member['username'] . '的代理');
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    //添加代理
    public function member_add()
    {
        if (IS_POST) {
            $data['username'] = I('username');
            $data['userid'] = I('userid');
            $data['agent_level'] = I('agent_level');
            $data['on_trial_day'] = I('on_trial_day');
            $data['is_franchisee'] = I('is_franchisee');
            $data['disabled'] = I('disabled');
            $password = I('password');
            $repassword = I('repassword');
            $data['wechat'] = I('wechat');
            $data['bankcard'] = I('bankcard');
            $data['real_name'] = I('real_name');
            $data['new_agent_leval_money'] = I('new_agent_leval_money');
            if (!$data['wechat'] || !$data['bankcard'] || !$data['real_name']) {
                $this->error('微信和银行卡和姓名必须添加');
            }
            if (!preg_match("/^1[34578]{1}\d{9}$/", $data['username'])) {
                $this->error('请输入正确的代理账号(手机号)');
            }
            $member = M('agent_member')->where(['username' => $data['username']])->find();
            if ($member) {
                $this->error('该代理账号已经存在');
            }
            if (M('agent_member')->where(['userid' => $data['userid']])->find()) {
                $this->error('该游戏ID已经被使用');
            }
            if (M('agent_member')->where(['wechat' => $data['wechat']])->find()) {
                $this->error('该微信已经被使用');
            }
            //是否有上级代理，如果有，验证分成比率
            $upAgent = AgentModel::getInstance()->getUpAgentInfoByUserID($data['userid'], ['id', 'commission_rate']);
            $commissionRateUpAgent = $upAgent ? $upAgent['commission_rate'] : 100;

            if (!is_numeric($data['new_agent_leval_money']) || 60 > $data['new_agent_leval_money'] || $data['new_agent_leval_money'] > 350) {
                $this->error('保底金额必须为数字，且在60到350之间');
            }
            if (M('agent_member')->where(['userid' => $data['bankcard']])->find()) {
                $this->error('该银行卡账号已经被使用');
            }
            $data['agent_level'] = 1;
            //验证
            // $arr = [1, 2, 3];
            // if (!in_array($data['agent_level'], $arr)) {
            //     $this->error('请选择代理级别');
            // }
            $user = UserModel::getInstance()->getUserInfo($data['userid']);
            if ($user) {

                if ($password !== $repassword) {
                    $this->error('两次密码不一致');
                }
                if (strlen($password) < 6) {
                    $this->error('登录密码不能低于6位数');
                }

                //验证完成根据游戏ID获取游戏昵称以及上级代理的登录账号和邀请码
                $res = $this->get_superior($data['userid']);
                $data['superior_agentid'] = $res['superior_agentid'];
                $data['superior_username'] = $res['superior_username'];
                $data['register_time'] = time();
                $data['password'] = md5($password);
                // $data['agentid'] = $this->get_max_agentid()+1;
                $data['agentid'] = $data['userid'];//$this->get_max_agentid() + 1;
                //获取最大的agentid
                $id = M('agent_member')->add($data);
                if ($id) {
                    /*if(!empty($data['superior_agentid'])){
                        //修改该代理上级的下级代理统计
                        $this->updateAgent($data['superior_agentid'], $id);
                    }*/
                    //redis 添加集合
                    RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_web_agentmember, $data['userid']);
                    operation_record(UID, '添加用户名为' . $member['username'] . '的代理');
                    $this->success('添加成功');

                    /*operation_record(UID, '添加代理' . $data['username']);
                    $url = C('WEBSITE_URL').'/Home/Wechat/share/userID/' . $data['userid'] . '/agentID/' . $data['agentid'];
                    $redis = \GetRedis::get();
                    $find = $redis->redis->hgetall("userInfo|" . $data['userid']);

                    $this->download_file($find['headURL'], '/usr/local/nginx/html/Uploads/qrcode_new/' . md5('Agent' . $find['userID']) . '.jpg');
                    $res = $this->scerweima1($url, $find['userID'], '/usr/local/nginx/html/Uploads/qrcode_new/' . md5('Agent' . $find['userID']) . '.jpg', $find['name'], $data['agentid']);

                    $res = D('Home/Message')->agentQrcode($id);
                     if (!$res) {
                       $this->error('生成二维码失败!');
                    }*/

                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error('游戏ID不存在');
            }
        } else {
            //查询代理等级
            $group = M('agent_group')->select();
            $this->assign(['arrVerifyType' => $this->getVerifyType()]);
            $this->assign('group', $group);
            $this->display();
        }
    }

    /*
     * 修改redis代理关系信息
     * $paranm int $superior_agentid 上级代理id
     * $param int $id   添加的代理id
     * */
    public function updateAgent($superior_agentid, $id){
        //获取该代理的所有下级代理
        $xj_agent_id = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $superior_agentid);
        //该自己添加下级代理
        if(empty($xj_agent_id)){
            RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $superior_agentid, [$id]);
        }else{
            array_push($xj_agent_id, $id);
            RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $superior_agentid, $xj_agent_id);
        }

        $res = RedisManager::getRedis()->exists(RedisConfig::Hash_superiorAgentSet . '|' . $superior_agentid);
        if(!empty($res)){ //存在上级代理,就得给自己的上级代理添加该代理
            //获取该上级代理的所有上级代理
            $sj_agent_id = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_superiorAgentSet . '|' . $superior_agentid);
            foreach ($sj_agent_id as $k => $v){
                $xj_agent_ids = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $v);
                array_push($xj_agent_ids, $id);
                RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $v, $xj_agent_ids);
            }
            array_push($sj_agent_id, $superior_agentid);
            //给添加的代理成员添加上级代理信息
            RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, $sj_agent_id);
        }else{
            //给添加的代理成员添加上级代理信息
            RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $id, [$superior_agentid]);
        }
    }

// 下载头像
    public
    function download_file($file_url, $save_to)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);

        $downloaded_file = fopen($save_to, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);
    }

    // 生成二维码
    public
    function scerweima1($url = '', $id = '', $head = '', $nickname = '', $agentid = '', $img = './Uploads/qrcode/agent_img.jpg')
    {
        //echo $url;
        $value = $url; //二维码内容
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize = 7; //生成图片大小
        //生成二维码图片
        $filename = './Uploads/qrcode/' . $id . '_agent_qrcode.png';
        //include '/ThinkPHP/library/Vendor/Wxpay/example/phpqrcode/phpqrcode.php';
        QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        $logo = './Uploads/qrcode/qrcode.png'; //准备好的logo图片
        $QR = $filename; //已经生成的原始二维码图

        // 生成二维码
        if (file_exists($logo)) {
            $QR = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
            $QR_width = imagesx($QR); //二维码图片宽度
            $QR_height = imagesy($QR); //二维码图片高度
            $logo_width = imagesx($logo); //logo图片宽度
            $logo_height = imagesy($logo); //logo图片高度
            $logo_qr_width = $QR_width / 3; //组合之后logo的宽度(占二维码的1/5)
            $scale = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2; //组合之后logo左上角所在坐标点

            //重新组合图片并调整大小
            /*
             *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        //输出图片
        $png = './Uploads/qrcode/' . md5($id) . '.png';
        $res = imagepng($QR, $png);
        imagedestroy($QR);
        imagedestroy($logo);

        // 合成二维码
        if ($res) {
            $logo = $png; //准备好的logo图片
            $QR = $img; //已经生成的原始二维码图

            if (file_exists($logo)) {
                $QR = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
                $logo = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
                $QR_width = imagesx($QR); //二维码图片宽度
                $QR_height = imagesy($QR); //二维码图片高度
                $logo_width = imagesx($logo); //logo图片宽度
                $logo_height = imagesy($logo); //logo图片高度
                $logo_qr_width = 320; //组合之后logo的宽度(占二维码的1/5)
                $scale = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
                $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
                $from_width = 1800; //组合之后logo左上角所在坐标点
                $right = 110;
                // var_dump($from_width);die;
                //重新组合图片并调整大小
                /*
                 *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
                 */
                imagecopyresampled($QR, $logo, $right, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }

            //输出图片
            $res1 = imagepng($QR, './Uploads/qrcode/' . md5($id) . '.png');
            // $res2 = imagedestroy($QR);
            imagedestroy($logo);
        }

        // 合成头像
        if ($res1) {
            $logo = $head; //准备好的logo图片
            $QR = './Uploads/qrcode/' . md5($id) . '.png'; //已经生成的原始二维码图

            if (file_exists($logo)) {
                $QR = imagecreatefromstring(file_get_contents($QR)); //目标图象连接资源。
                $logo = imagecreatefromstring(file_get_contents($logo)); //源图象连接资源。
                $QR_width = imagesx($QR); //二维码图片宽度
                $QR_height = imagesy($QR); //二维码图片高度
                $logo_width = imagesx($logo); //logo图片宽度
                $logo_height = imagesy($logo); //logo图片高度
                $logo_qr_width = 127; //组合之后logo的宽度(占二维码的1/5)
                $scale = $logo_width / $logo_qr_width; //logo的宽度缩放比(本身宽度/组合后的宽度)
                $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
                $from_width = 815; //组合之后logo左上角所在坐标点
                $right = 50;
                // var_dump($from_width);die;
                //重新组合图片并调整大小
                /*
                 *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
                 */
                imagecopyresampled($QR, $logo, $right, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }

            //输出图片
            $head = imagepng($QR, './Uploads/qrcode/' . md5($id) . '.png');
            // $res2 = imagedestroy($QR);
            imagedestroy($logo);

            if ($head) {
                $src = './Uploads/qrcode/' . md5($id) . '.png';
                //2.获取图片信息
                $info = getimagesize($src);
                //3.通过编号获取图像类型
                $type = image_type_to_extension($info[2], false);
                //4.在内存中创建和图像类型一样的图像
                $fun = "imagecreatefrom" . $type;
                //5.图片复制到内存
                $image = $fun($src);

                /*操作图片*/
                //1.设置字体的路径
                $font = "./Uploads/qrcode/fzlt.ttf";
                //2.填写水印内容
                //3.设置字体颜色和透明度
                $color1 = imagecolorallocatealpha($image, 255, 255, 255, 0); // 昵称
                $color2 = imagecolorallocatealpha($image, 255, 255, 255, 0); // ID
                $color3 = imagecolorallocatealpha($image, 251, 216, 137, 0); // 邀请码
                //4.写入文字
                imagettftext($image, 40, 0, 450, 810, $color1, $font, $nickname); // 昵称
                imagettftext($image, 40, 0, 450, 897, $color2, $font, $id); // ID
                imagettftext($image, 40, 0, 450, 988, $color3, $font, $agentid); //  邀请码
                /*输出图片*/
                //浏览器输出
                header("Content-type:" . $info['mime']);
                $fun = "image" . $type;
                // $fun($image);
                //保存图片
                // $fun($image,'./Uploads/qrcode/'.md5($id).'.'.$type);
                imagepng($image, './Uploads/qrcode/' . md5($id) . '.' . $type);
                /*销毁图片*/
                imagedestroy($image);
            }
        }
    }

    //向个人发送邮件
    public
    function personal_send_email($userid, $l, $agentid = '')
    {
        // $userid = (int)I('userid');
        $level = [1 => '一', '二', '三'];
        $title = "恭喜成为饶城窝龙{$level[$l]}级代理";
        $content = "恭喜成为饶城窝龙{$level[$l]}级代理，您的邀请码为{$agentid}. 祝您生活愉快！！！";
        $send_name = '系统';
        $socket = \Socket::get();
        $send = new \SendFunction();
        $emailPacket = $send->makeEmailPacket(101, $send_name, $title, '', $content, $userid);
        if ($socket->connet == false) {
            $this->error('添加失败,连接服务器失败');
        }

        $res = $socket->send($send::EmailID, 1, 0, $emailPacket);
        if (!$res) {
            $this->error('发布失败,给服务器发送消息失败');
        }
        $read = unpack('i*', $socket->read_data(1024));
        if (!$read) {
            $this->error('发布失败，服务器未响应');
        }
        if ($read[4] != 0) {
            $this->error('发布失败，服务器响应错误码为' . $read[4]);
        }
    }

    //验证银行卡合法性
    protected
    function check_bankcard($no = '')
    {
        $arr_no = str_split($no);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        if ($last_n == ($total % 10)) {
            echo '符合Luhn算法';
        }
    }

    //获取上级代理的登录账号以及邀请码
    protected
    function get_superior($userid)
    {
        $agentID = M('agent_bind')->where(['userID' => $userid])->getField('agentID');
        $res = [];
        if (!$agentID) {
            $res['superior_agentid'] = '';
            $res['superior_username'] = '';
        } else {
            $res['superior_agentid'] = $agentID;
            $username = M('agent_member')->where(['agentid' => $agentID])->getField('username');
            $res['superior_username'] = $username;
        }
        return $res;
    }

    //获取agentid
    protected
    function get_max_agentid()
    {
        // $max_agentid = M('agent_member')->max('agentid');
        // if(!$max_agentid){
        //     $max_agentid = 10000;
        // }
        $agentid = rand(mt_rand(1, 9) . mt_rand(0, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9), mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(0, 9));
        return $agentid;
    }

    //绑定推荐码
    public
    function agentid_bind()
    {
        if (IS_POST) {
            $agent_id = (int)I('superior_agentid');
            $id =(int) I('id') ;
            $commission_rate = (int)I("commission_rate");
            $userID = M('agent_member')->where(['id'=>$id])->find()['userid'];
            //查看是否绑定
            $agent_bind = AgentModel::getInstance()->getAgentBindByUserID($userID);
            if (!empty($agent_bind)) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_TOO);
            }

            //验证代理号和用户是否存在
            $agent_member = AgentModel::getInstance()->getAgentMemberByAgentID($agent_id);
            //代理是否存在
            if (empty($agent_member)) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INVITE_NOT);
            }
            //不能绑定自己的邀请码
            if ($agent_member['userid'] == $userID) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INVITE_OWN);
            }


            //获得当前代理信息
            $my_agent = AgentModel::getInstance()->getAgentMemberByUserID($userID);
            if (!empty($my_agent)) {
                if ($my_agent['superior_agentid']) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_TOO);
                }
                $allAgentID = AgentModel::getInstance()->getDownAgentID($my_agent['agentid']);
                if (in_array($agent_id, $allAgentID)) {
                    AppModel::returnJson(ErrorConfig::ERROR_CODE, "不能绑定手下代理");
                }
            }

            //获取绑定的上级用户
            $officer = M('agent_member')->where(['agentid'=>$agent_id])->find();
            //比率不能小于下级用户
            $subordinate = M('agent_member')->where(['superior_agentid' => $my_agent['agentid']])->order('commission_rate desc')->find();
            $subordinate['commission_rate'] = isset($subordinate['commission_rate']) ? $subordinate['commission_rate'] : 0;
            if ($subordinate['commission_rate'] + 1 >= $officer['commission_rate']) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "无法绑定当前代理，当前代理的下级比率" . $subordinate['commission_rate'] . "%，绑定代理用户比率" . $officer['commission_rate'] . '%，当前代理比率已没有调整空间');
            }
            //设置分成比率,正整数，{1%,99%},<上级比率, >下级比率
            if ($subordinate['commission_rate'] >= $commission_rate || $commission_rate >= $officer['commission_rate']) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "比率需要大于" . $subordinate['commission_rate'] . "%且小于" . $officer['commission_rate'] . '%');
            }


            //添加记录
            $name = AgentModel::getInstance()->getUserInfo($userID,'name');
            $addData = [];
            $addData['userID'] = $userID;
            $addData['agentID'] = $agent_id;
            $addData['agentname'] = $agent_member['username'];
            $addData['username'] = $name;
            $addData['bind_time'] = time();
            $addResult = AgentModel::getInstance()->addBindAgent($addData);

            if (empty($addResult)) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_FAIL);
            }

                //更新我的代理信息的绑定信息
            if (!empty($my_agent)) {
                $my_agent['superior_agentid'] = $agent_id;
                $my_agent['superior_username'] = $agent_member['username'];
                $my_agent['commission_rate'] = $commission_rate;
                AgentModel::getInstance()->updateAgentMember($my_agent['agentid'], $my_agent);
            }
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, '绑定成功');
          /*  $member = M('agent_member')->where(['agentid' => $data['superior_agentid']])->find();
            if (!$member) {
                $this->error('该邀请码不存在');
            }
            $my = M('agent_member')->find($data['id']);
            if ($data['superior_agentid'] == $my['agentid']) {
                $this->error('不能绑定自己的邀请码');
            }
            $this->xiaji($my['agentid']);
            if (in_array($data['superior_agentid'], $this->xiaji)) {
                $this->error('不能绑定自己的下级代理邀请码');
            }
            $data['superior_username'] = $member['username'];
            if (M('agent_member')->save($data)) {
                $redis = \GetRedis::get();
                $username = iconv('ASCII', 'UTF-8', $redis->redis->hget('userInfo|' . $member[$k]['userid'], 'name'));
                //绑定成功后在绑定中生成数据
                $data = [
                    'userID' => $my['userid'],
                    'agentID' => $data['superior_agentid'],
                    'username' => $username,
                    'agentname' => $member['username'],
                    'bind_time' => time(),
                ];
                M('agent_bind')->add($data);
                operation_record(UID, '为代理用户名为' . $my['username'] . '绑定邀请码' . $data['superior_agentid']);
                $this->success('绑定成功');
            } else {
                $this->error('绑定失败');
            }*/
        } else {
            $id = I('id');
            $this->assign('id', $id);
            $this->display();
        }
    }

    // 代理链
    public
    function agent_list()
    {
        $where['superior_agentid'] = '';
        $agent = M('agent_member')->where($where)->select();

        // 自己充值
        foreach ($agent as &$v) {
            $v['money'] = (int)M('recharge_commission')->where(['recharge_userid' => $v['userid']])->sum('recharge_amount');
        }

        // 下面会员
        foreach ($agent as &$v) {
            $data = self::agentTree($v['agentid']);
            $v['count'] = $data['count'];
            $v['money'] += $data['balance'];
        }

        // 排序
        $ages = array();
        foreach ($agent as $value) {
            $m[] = $value['money'];
        }

        array_multisort($m, SORT_DESC, $agent);

        $this->assign('_data', $agent);
        //$this->assign('_page',$agent['_page']);
        $this->display();
    }

    // 统计 金额 人数
    protected
    static function agentTree($agentid)
    {
        $agent = M('agent_bind')->where(['agentID' => $agentid])->select();
        $count = 0;
        $balance = 0;
        if ($agent) {
            foreach ($agent as $k => $v) {
                $map['recharge_userid'] = $v['userid'];
                $map['agent_bind'] = array('neq', '');
                //$balance += (int)M('recharge_commission')->where(['recharge_userid'=>$v['userid'],'agent_bind'=>['neq'=>'']])->sum('recharge_amount');
                $balance += (int)M('recharge_commission')->where($map)->sum('recharge_amount');
                $count++;
            }

        }

        return ['balance' => $balance, 'count' => $count];
    }

    public
    function xiaji($agentid)
    {
        $x = M('agent_member')->where(['superior_agentid' => $agentid])->select();
        if ($x) {
            foreach ($x as $k => $v) {
                $this->xiaji[] = $x[$k]['agentid'];
                $this->xiaji($x[$k]['agentid']);
            }
        }
    }

    //账单充值明细
    public function bill_detail()
    {
        $type = I('type');
        $search = I('search');
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop) + 24 * 3600 - 1;
            $where['recharge_time'] = ['between', [$start, $stop]];
        } else {
            if (!$search && !$type) {
                $start = strtotime(date('Y-m-d', time()));
                $stop = $start + 24 * 3600 - 1;
                $where['recharge_time'] = ['between', [$start, $stop]];
            }
        }

        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['recharge_userid'] = $search;
                    break;
                case 2:
                    $where['recharge_name'] = $search;
                    break;
                case 3:
                    $where['agent_bind'] = $search;
                    break;
                case 4:
                    $where['bind_username'] = $search;
                    break;
                case 5:
                    $where['bind_userid'] = $search;
                    break;
            }
        }

        $data = D('Data')->get_all_data('recharge_commission', $where, 10, 'recharge_time desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['recharge_name'] = M()->table('userInfo')->where(['userID' => $v['recharge_userid']])->getfield('name');
            $data['_data'][$k]['recharge_amount'] = sprintf("%.2f", $data['_data'][$k]['recharge_amount'] / 100);
            $data['_data'][$k]['bind_member_commission'] = sprintf("%.2f", $data['_data'][$k]['bind_member_commission'] / 100);
            $data['_data'][$k]['level2_member_commission'] = sprintf("%.2f", $data['_data'][$k]['level2_member_commission'] / 100);
            $data['_data'][$k]['level3_member_commission'] = sprintf("%.2f", $data['_data'][$k]['level3_member_commission'] / 100);
        }

        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->assign('start', $start);
        $this->assign('stop', $stop);
        $this->assign('type', $type);
        $this->assign('search', $search);
        $this->display();
    }

    //代理信息统计
    public function info_count()
    {
        //获取各级代理的人数
        $level1_count = M('agent_member')->where(['agent_level' => 1])->count();
        $level2_count = M('agent_member')->where(['agent_level' => 2])->count();
        $level3_count = M('agent_member')->where(['agent_level' => 3])->count();
        $level_count = $level1_count + $level2_count + $level3_count;
        //用户充值总额
        $where = [
            'consumeType' => 0,
            'status' => 1,
        ];
        $user_recharge_sum = sprintf("%.2f", M('pay_orders')->where($where)->sum('consumeNum') / 100);
        //所有代理余额
        $member_balance_sum = sprintf("%.2f", M('agent_member')->sum('balance') / 100);
        //代理提现总额
        $member_pos_sum = sprintf("%.2f", M('agent_member')->sum('history_pos_money') / 100);
        $this->assign('level1_count', $level1_count);
        $this->assign('level2_count', $level2_count);
        $this->assign('level3_count', $level3_count);
        $this->assign('level_count', $level_count);
        $this->assign('user_recharge_sum', $user_recharge_sum);
        $this->assign('member_balance_sum', $member_balance_sum);
        $this->assign('member_pos_sum', $member_pos_sum);
        $this->display();
    }

    //代理提现申请记录
    public function apply_pos()
    {
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['username'] = $search;
                    break;
                case 2:
                    $where['userid'] = $search;
                    break;
                case 3:
                    $where['agentid'] = $search;
                    break;
                case 4:
                    $where['wechat'] = $search;
                    break;
            }
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
//        if ($start && $stop) {
//            $start = strtotime($start);
//            $stop = strtotime($stop);
//            $where['apply_time'] = ['between', [$start, $stop]];
//        }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['apply_time'] = $res['data'];
        }
        $status = I('status', 0);
        if ($status==1){
            $where['status'] = ['neq',0];
        }else{
            $where['status'] = $status;
        }

        $data = D('Data')->get_all_data('agent_apply_pos', $where, 10, 'apply_time desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['front_balance'] = sprintf("%.2f", $data['_data'][$k]['front_balance'] / 100);
            $data['_data'][$k]['apply_amount'] = sprintf("%.2f", $data['_data'][$k]['apply_amount'] / 100);
            $data['_data'][$k]['after_balance'] = sprintf("%.2f", $data['_data'][$k]['after_balance'] / 100);
        }
        $this->assign('status', $status);
        $this->assign('_data', $data['_data']);
        $this->assign('_page', $data['_page']);
        $this->display();
    }

    //审核通过
    public function examine_pass()
    {
        $id = array_unique((array)I('id', 0));
        if (empty($id) || empty($id[0])) {
            $this->error('请选择要操作的数据');
        }
        $map = array('id' => array('in', $id));
        if (M('agent_apply_pos')->where($map)->save(array('status' => 1, 'handle_time' => time()))) {
            //发送邮件
            operation_record(UID, '通过代理提现审核');
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
    //审核不通过
    public function no_examine_pass(){
        $id = I('id');
        if (empty($id) || empty($id[0])) {
            $this->error('请选择要操作的数据');
        }


        M()->startTrans();
        $data = M('agent_apply_pos')->where(['id' => $id])->find();
        $userInfo = M('agent_member')->where(['userid' => $data['userid']])->find();
        $res1 = M('agent_apply_pos')->where(['id' => $id])->save(array('status' => 2, 'handle_time' => time()));
        $res2 = M('agent_member')->where(['userid' => $data['userid']])->setDec('history_pos_money', $data['apply_amount'] );
        $res3 = M('agent_member')->where(['userid' => $data['userid']])->setInc('balance', $data['apply_amount']);

        //记录账单
        $billdata = [
            'username' => $userInfo['username'],
            'agent_level' => $userInfo['agent_level'],
            'front_balance' => $userInfo['balance'],  //总的可提现金额
            'handle_money' => $data['apply_amount'],  //提现金额
            'after_balance' => $userInfo['balance'] + $data['apply_amount'], //剩余可提现金额
            '_desc' => '代理提现审批驳回',
            'make_time' => time(),
            //'make_name' => $redisuserInfo['name'],
            'make_userid' => $userInfo['userid'],
            'amount' => 0,
            'commission' => ($data['apply_amount']),
            'under_amount' => 0,
            'under_commission' => 0,
        ];
        $res4 = M('bill_detail')->add($billdata);

        if ($res1 && $res2 && $res3 && $res4) {
            M()->commit();
            //发送邮件
            operation_record(UID, '通过代理提现审核');
            $this->success('操作成功');
        } else {
            M()->rollback();
            $this->error('操作失败');
        }
    }

    protected function getVerifyType() {
        return $arrVerifyYype = [
            'username' => 1,
            'userid' => 2,
            'bankcard' => 3,
            'wechat' => 4,
            'commission_rate' => 5,
        ];
    }
    //验证合法性
    public function ajax_check($v, $type)
    {
        $arrVerifyYype = $this->getVerifyType();

        switch ($type) {
            //验证用户名
            case $arrVerifyYype['username']:
                if (M('agent_member')->where(['username' => $v])->find()) {
                    $this->error('用户名已经存在');
                }
                break;
            //验证游戏ID
            case $arrVerifyYype['userid']:
                if (M('agent_member')->where(['userid' => $v])->find()) {
                    $this->error('游戏ID已经存在');
                }
                break;
            //验证银行卡
            case $arrVerifyYype['bankcard']:
                if (M('agent_member')->where(['bankcard' => $v])->find()) {
                    $this->error('银行卡号已经存在');
                }
                break;
            //验证微信
            case $arrVerifyYype['wechat']:
                if (M('agent_member')->where(['wechat' => $v])->find()) {
                    $this->error('微信账号已经存在');
                }
                break;
            //验证分佣比率
            case $arrVerifyYype['new_agent_leval_money']:

                if (!is_numeric($v) || $v < 60 || $v > 350) {
                    $this->error('保底金额必须为数字，且在60到350之间');
                }
                break;
        }
        $this->success('可使用');
    }

    //代理参数配置
    public
    function agent_config()
    {
        if (IS_POST) {
            $config = $_POST;
            foreach ($config as $k => &$v) {
                if ($k=='agent_notice'){
                    M('agent_config')->where(['key' => $k])->save(['value' =>$v]);
                }else{
                    M('agent_config')->where(['key' => $k])->save(['value' => (int)$v]);
                }

            }
            $this->success('配置修改成功');
        } else {
            $config = M('agent_config')->select();
            $this->assign('config', $config);
            $this->display();
        }
    }

    //代理后台充值账单
    public
    function agent_recharge_detail()
    {
        $where = [];
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userid'] = $search;
                    break;
            }
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
        if ($start && $stop) {
            $start = strtotime($start);
            $stop = strtotime($stop);
            $where['time'] = ['between', [$start, $stop]];
        }
        $data = D('Data')->get_all_data('agentrecharge', $where, 15, 'id id');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    //代理操作
    public
    function agent_edit()
    {
        if (IS_POST) {
            $data = [
                // 'id'            => I('id'),
                'username' => I('username'),
                'userid' => I('userid'),
                'agent_level' => I('agent_level'),
                'disabled' => I('disabled'),
                'agentid' => I('agentid'),
                'wechat' => I('wechat'),
                'on_trial_day' => I('on_trial_day'),
                'is_franchisee' => I('is_franchisee'),
                'new_agent_leval_money' => I('new_agent_leval_money'),
            ];
            $agent_old_info = $this->get_agent_info(I('id'));

            if(I('new_agent_leval_money') < 60 || I('new_agent_leval_money') > 350) $this->error('保底金额只能在60到350之间!');

            //不能低于自己之前的保底金额
            if(I('new_agent_leval_money') < $agent_old_info['new_agent_leval_money']) $this->error('修改后的保底金额不能低于该玩家之前的保底金额!');

            //判断是否存在上级代理
            if(!empty($agent_old_info['superior_agentid'])){
                $new_agent_leval_money = M('agent_member')->where(['userid' => $agent_old_info['superior_agentid']])->getField('new_agent_leval_money');
                if(I('new_agent_leval_money') >= $new_agent_leval_money) $this->error('不能超过上级代理的保底金额!');
            }


            /*
             *以下字段发生变更后，分佣表  绑定邀请码表  账单详情表都需要同步
             */
            //手机号变更
            if ($data['username'] != $agent_old_info['username']) {
                $this->synchro_change_username($data['username'], $agent_old_info['username']);
            }
            // 游戏ID变更
            if ($data['userid'] != $agent_old_info['userid']) {
                $this->synchro_change_userid($data['userid'], $agent_old_info['userid']);
            }

            // 邀请码变更
            if ($data['agentid'] != $agent_old_info['agentid']) {
                $this->synchro_change_agentid($data['agentid'], $agent_old_info['agentid']);
            }

            $password = I('password', '');
            $repassword = I('repassword', '');
            if (!empty($password)) {
                if ($password != $repassword) {
                    $this->error('两次输入密码不一致!');
                }

                $data['password'] = md5($password);
            }
            unset($data['agent_level']);
            $res = M('agent_member')->where(['id' => I('id')])->save($data);
            if ($res) {
                $url = 'http://ht.szhuomei.com/Home/Wechat/share/userID/' . $data['userid'] . '/agentID/' . $data['agentid'];
                $find = M()->table('userInfo')->where(['userID' => $data['userid']])->find();
                $this->download_file($find['headurl'], './Uploads/qrcode/' . md5('Agent' . $find['userid']) . '.jpg');
                $this->scerweima1($url, $data['userid'], './Uploads/qrcode/' . md5('Agent' . $find['userid']) . '.jpg', $find['name'], $data['agentid']);
               // $res = D('Home/Message')->agentQrcode(I('id'));
                if (!$res) {
                    $this->error('生成二维码失败!');
                }

                $this->success('修改成功');
            } else {
                $this->success('修改失败');
            }

        } else {
            $id = I('id');
            //获取该代理的信息
            $agent = $this->get_agent_info($id);
            $this->assign('agent', $agent);
            $level = $this->get_agent_level();
            $this->assign('level', $level);
            $this->display();
        }
    }

    public
    function test()
    {
        $data = M('agent_member')->select();
        foreach ($data as $k => $v) {
            $url = 'http://ht.szhuomei.com/Home/Wechat/share/userID/' . $v['userid'] . '/agentID/' . $v['agentid'];
            $find = M()->table('userInfo')->where(['userID' => $v['userid']])->find();
            $this->download_file($find['headurl'], './Uploads/qrcode/' . md5('Agent' . $find['userid']) . '.jpg');
            $this->scerweima1($url, $find['userid'], './Uploads/qrcode/' . md5('Agent' . $find['userid']) . '.jpg', $find['name'], $v['agentid']);
            echo $k;
        }

    }

    //同步变更用户名
    protected
    function synchro_change_username($username, $oldusername)
    {
        //验证
        if (!preg_match("/^1[34578]\d{9}$/", $username)) {
            $this->error('请输入正确的用户名(手机号)');
        }
        $res = M('agent_member')->where(['username' => $username])->find();
        if ($res) {
            $this->error('该用户名已被使用');
        }
        //账单详情
        $billdetail = M('billdetail')->field('id,username')->select();
        foreach ($billdetail as $k => $v) {
            if ($billdetail[$k]['username'] == $oldusername) {
                M('billdetail')->save(['id' => $billdetail[$k]['id'], 'username' => $username]);
            }
        }
        //绑定代理
        $bindagentid = M('agent_bind')->field('id,agentname')->select();
        foreach ($bindagentid as $k => $v) {
            if ($bindagentid[$k]['agentname'] == $oldusername) {
                M('agent_bind')->save(['id' => $bindagentid[$k]['id'], 'agentname' => $username]);
            }
        }
        //分佣
        $commission = M('recharge_commission')->field('id,bind_username,level2_username,level3_member_username')->select();
        foreach ($commission as $k => $v) {
            if ($commission[$k]['bind_username'] == $oldusername) {
                M('recharge_commission')->save(['id' => $commission[$k]['id'], 'bind_username' => $username]);
            }
            if ($commission[$k]['level2_username'] == $oldusername) {
                M('recharge_commission')->save(['id' => $commission[$k]['id'], 'level2_username' => $username]);
            }
            if ($commission[$k]['level3_member_username'] == $oldusername) {
                M('recharge_commission')->save(['id' => $commission[$k]['id'], 'level3_member_username' => $username]);
            }
        }
        //代理表
        $agent_member = M('agent_member')->field('id,username,superior_username')->select();
        foreach ($agent_member as $k => $v) {
            if ($agent_member[$k]['username'] == $oldusername) {
                M('agent_member')->save(['id' => $agent_member[$k]['id'], 'username' => $username]);
            }
            if ($agent_member[$k]['superior_username'] == $oldusername) {
                M('agent_member')->save(['id' => $agent_member[$k]['id'], 'superior_username' => $username]);
            }
        }
        //代理申请提现表
        $apply = M('agent_apply_pos')->field('id,username')->select();
        foreach ($apply as $k => $v) {
            if ($apply[$k]['username'] == $oldusername) {
                M('agent_apply_pos')->save(['id' => $apply[$k]['id'], 'username' => $username]);
            }
        }
    }

    //同步变更游戏ID
    protected
    function synchro_change_userid($userid, $olduserid)
    {
        if ((int)$userid == 0) {
            $this->error('游戏ID必须是一个数字');
        }

        //检测用户是否存在
        $isUserid = M('agent_member')->where(['userid' => $userid])->find();
        if ($isUserid) {
            $this->error('游戏ID已经存在');
        }

        // 更改代理表
        $agentid = M('agent_member')->where(['userid' => $olduserid])->getfield('id');
        $res = M('agent_member')->save(['id' => $agentid, 'userid' => $userid]);
        // 更改绑定代理表
        $bind = M('agent_bind')->where(['userid' => $olduserid])->getfield('id');
        // M('agent_bind')->save(['userid'=>$olduserid, 'userID'=>$userid]);
        M('agent_bind')->save(['id' => $bind, 'userID' => $userid]);
        // 更改分佣表
        $commissionData = M('recharge_commission')->where(['recharge_userid' => $olduserid])->field('id,recharge_userid')->select();
        if ($commissionData) {
            foreach ($commissionData as $v) {
                if ($v['recharge_userid'] == $olduserid) {
                    M('recharge_commission')->where(['id' => $v['id']])->save(['recharge_userid' => $userid]);
                }
            }
        }

        // 更改提现表
        $applyData = M('agent_apply_pos')->field('id,userid')->select();
        if ($applyData) {
            foreach ($applyData as $v) {
                if ($v['userID'] == $olduserid || $v['userid'] == $olduserid) {
                    M('agent_apply_pos')->where(['id' => $v['id']])->save(['userID' => $userid]);
                }

            }
        }

        // 抽奖记录
        $turntableData = M('turntable_record')->field('id,userID')->select();
        if ($turntableData) {
            foreach ($turntableData as $v) {
                if ($v['userid'] == $olduserid) {
                    M('turntable_record')->where(['id' => $v['id']])->save(['userID' => $userid]);
                }

            }
        }

    }

    //同步变更邀请码
    protected
    function synchro_change_agentid($agentid, $oldagentid)
    {
        //验证
        if (!is_numeric($agentid) || strlen($agentid) != 6) {
            $this->error('邀请码格式错误');
        }

        $res = M('agent_member')->where(['agentid' => $agentid])->find();
        if ($res) {
            $this->error('邀请码已被使用');
        }

        //绑定代理表
        $bindagentid = M('agent_bind')->field('id,agentid')->select();
        foreach ($bindagentid as $k => $v) {
            if ($bindagentid[$k]['agentid'] == $oldagentid) {
                M('agent_bind')->where(['id' => $bindagentid[$k]['id']])->save(['agentID' => $agentid]);
            }
        }

        //分佣表
        $commission = M('recharge_commission')->field('id,agent_bind')->select();
        foreach ($commission as $k => $v) {
            if ($commission[$k]['agent_bind'] == $oldagentid) {
                M('recharge_commission')->save(['id' => $commission[$k]['id'], 'agent_bind' => $agentid]);
            }
        }

        //代理表
        $agent_member = M('agent_member')->field('id,agentid,superior_agentid')->select();
        foreach ($agent_member as $k => $v) {
            if ($agent_member[$k]['agentid'] == $oldagentid) {
                // M('agent_member')->save(['id'=>$agent_member[$k]['id'],'aegntid'=>$oldagentid]);
                M('agent_member')->where(['id' => $agent_member[$k]['id']])->save(['aegntid' => $oldagentid]);
            }

            if ($agent_member[$k]['superior_agentid'] == $oldagentid) {
                // M('agent_member')->save(['id'=>$agent_member[$k]['id'],'superior_agentid'=>$agentid]);
                M('agent_member')->where(['id' => $agent_member[$k]['id']])->save(['aegntid' => $agentid]);
            }
        }

    }

    //获取代理信息
    protected function get_agent_info($id)
    {
        $agent = M('agent_member')->find($id);
        return $agent;
    }

    //获取代理等级
    protected function get_agent_level()
    {
        $level = M('agent_group')->select();
        return $level;
    }

    // 代理审核
    public function examine()
    {
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['username'] = $search;
                    break;
                case 2:
                    $where['userid'] = $search;
                    break;
                case 3:
                    $where['gamename'] = $search;
                    break;
                case 4:
                    $where['agentid'] = $search;
                    break;
                case 5:
                    $where['real_name'] = $search;
                    break;
            }
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
//        if ($start && $stop) {
//            $start = strtotime($start);
//            $stop = strtotime($stop);
//            $where['register_time'] = ['between', [$start, $stop]];
//        }
        $res = validSearchTimeRange($start, $stop);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            $this->error($res['msg']);
        } else {
            $where['register_time'] = $res['data'];
        }
        $data = D('Data')->get_all_data('agent_audit', $where, 10, 'id desc');
        foreach ($data['_data'] as $k => $v) {
            $data['_data'][$k]['gamename'] = M()->table('userInfo')->where(['userID' => $v['userid']])->getField('name');
            $data['_data'][$k]['money'] = M()->table('userInfo')->where(['userID' => $v['userid']])->getField('money');
            $data['_data'][$k]['jewels'] = M()->table('userInfo')->where(['userID' => $v['userid']])->getField('jewels');
        }
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);
        $this->display();
    }

    // 代理审核
    public function examine_edit()
    {
        $data = I('get.');
        if ($data['status'] == 0) {
            // 审核
            $agentData = M('agent_audit')->where(['id' => $data['id']])->find();
            $newData = [
                'username' => $agentData['username'],
                'userid' => $agentData['userid'],
                'password' => $agentData['password'],
                'agent_level' => $agentData['agent_level'],
                'superior_agentid' => $agentData['superior_agentid'],
                'agentid' => $agentData['agentid'],
                'superior_username' => $agentData['superior_username'],
                'register_time' => time(),
                'wechat' => $agentData['wechat'],
                'bankcard' => $agentData['bankcard'],
                'balance' => $agentData['balance'],
                'disabled' => $agentData['disabled'],
                'not_under_money' => $agentData['not_under_money'],
                'under_money' => $agentData['under_money'],
                'email' => $agentData['email'],
            ];

            if (M('agent_member')->data($newData)->add()) {
                //redis 添加集合
                RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_web_agentmember,$agentData['userid']);
                M('agent_audit')->where(['id' => $data['id']])->save(['status' => 0]);
                operation_record(UID, '代理审核' . $data['username']);

                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }

        } else {
            // 未审核
            $res = M('agent_audit')->where(['id' => $data['id']])->save(['status' => 2]);
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        }
    }

    //代理申请页面
    public function register()
    {
        if (IS_POST) {
            //接收post数据
            $data['username'] = I('username');//手机
            $password = I('password');//密码
            $repassword = I('repassword');
            $data['wechat'] = I('wechat');//微信号
            $data['bankcard'] = I('bankcard');//银行卡
            $data['real_name'] = I('real_name');//姓名
            $data['userid'] = I('userid');//游戏id

            if (!$data['wechat'] || !$data['bankcard'] || !$data['real_name']) {
                echo json_encode(['code' => 1, 'msg' => '微信和银行卡和姓名必须添加']);
                die;
            }

            if (!preg_match("/^1[34578]{1}\d{9}$/", $data['username'])) {
                echo json_encode(['code' => 1, 'msg' => '请输入正确的代理账号(手机号)']);
                die;
            }
            $member = M('agent_member')->where(['username' => $data['username']])->find();
            if ($member) {
                echo json_encode(['code' => 1, 'msg' => '该代理账号已经存在']);
                die;
            }
            if (M('agent_member')->where(['userid' => $data['userid']])->find()) {
                echo json_encode(['code' => 1, 'msg' => '该游戏ID已经被使用']);
                die;
            }
            if (M('agent_member')->where(['wechat' => $data['wechat']])->find()) {
                echo json_encode(['code' => 1, 'msg' => '该微信已经被使用']);
                die;
            }
            if (M('agent_member')->where(['userid' => $data['bankcard']])->find()) {
                echo json_encode(['code' => 1, 'msg' => '该银行卡账号已经被使用']);
                die;
            }

            if ($password !== $repassword) {
                echo json_encode(['code' => 1, 'msg' => '两次密码不一致']);
                die;
            }
            if (strlen($password) < 6) {
                echo json_encode(['code' => 1, 'msg' => '登录密码不能低于6位数']);
                die;
            }

            //用id去查上一级
            $res = $this->get_superior($data['userid']);
            $data['superior_agentid'] = $res['superior_agentid'];
            $data['superior_username'] = $res['superior_username'];
            $data['register_time'] = time();
            $data['password'] = md5($password);
            //获得邀请码
            $data['agentid'] = $this->get_max_agentid() + 1;
            $data['status'] = 1;
            //获取最大的agentid
            if (M('agent_audit')->add($data)) {
                echo json_encode(['code' => 0, 'msg' => '添加成功,请等待客服审核']);
                die;
            } else {
                $this->error('添加失败');
            }
        } else {

            $userid = I('get.userid') ? I('get.userid') : 118003;
            $this->assign('userid', $userid);
            $this->display();
        }
    }

    // 代理申请

    function apply()
    {
        $Agent = M('agent_apply');
        $count = $Agent->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $list = $Agent
            ->alias('a')
            ->join('left join userInfo as u on u.userID=a.user_id')
            ->join('left join web_agent_bind as b on b.userID=a.user_id')
            ->join('left join web_agent_member as m on m.agentid=b.agentID')
            ->field('a.*,b.agentID,m.agent_level')
            ->order('addtime desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    // 代理审核
    public function apply_edit()
    {
        if (IS_POST) {
            $data = I('post.');
            $res = M('agent_apply')->save($data);
            $user = M('agent_apply')->where(['id' => $data['id']])->find();
            D('Home/Message')->tplMessage($data['status'], $user['openid'], $user);
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        } else {
            $id = I('id', 0);
            $this->assign('id', $id);
            $this->display();
        }
    }
    //代理反馈问题
    public function feedback(){
        $type = I('type');
        $search = I('search');
        if ($type && $search) {
            switch ($type) {
                case 1:
                    $where['userid'] = $search;
                    break;
            }
        }
        $start = urldecode(I('start'));
        $stop = urldecode(I('stop'));
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

        $where['status'] = I('get.status','1','int');

        $data = D('Data')->get_all_data('agent_feedback', $where, 10, 'id desc');
        $this->assign('_page', $data['_page']);
        $this->assign('_data', $data['_data']);

        $this->display();
    }
    //回复
    public function reply(){
        if (IS_POST){
            $post = I('post.');
            if (M('agent_feedback')->where(['id'=>$post['id']])->save(['status'=>2,'reply'=>$post['pass']])){
                echo json_encode(['code'=>1,'msg'=>'回复成功']);exit();
            }else{
                echo json_encode(['code'=>1,'msg'=>'回复失败']);exit();
            }
        }
    }
}


