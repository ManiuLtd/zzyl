<?php
namespace action;

use config\ErrorConfig;
use model\UserCashBank;
use model\UserCashApplication;
use model\AppModel;
use model\PhoneModel;
use manager\DBManager;
use config\MysqlConfig;
use helper\LogHelper;
//use Qrcode\QRcode;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/2
 * Time: 10:50
 */

/**
 * 客户端新的分享业务
 * Class CashAction
 */
class   PromotionAction extends AppAction
{
    private static $_instance = null;
    //每页条数
    const PAGE_SIZE = 10;
    //访问域名
    const ACCESS_DOMAIN = 'http://zzyl.szbchm.com';
    protected $idArr = [];

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    //获取上级代理的登录账号以及邀请码
    protected function get_superior($userid)
    {
        $arrayKeyValue = ['agentID'];
        $where = "userID = {$userid}";
        $bindinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_bind, $arrayKeyValue, $where);
        $res = [];
        if (empty($bindinfo)) {
            $res['superior_agentid'] = '';
            $res['superior_username'] = '';
        } else {
            $res['superior_agentid'] = $bindinfo['agentID'];
            $arrayKeyValue1 = ['username'];
            $where1 = "agentid = {$bindinfo['agentID']}";
            $memberinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $arrayKeyValue1, $where1);
            $res['superior_username'] = $memberinfo['username'];
        }
        return $res;
    }

    /**
     * 生成分享的二维码，以及生成的二维码
     * @param $param
     */
    public function showImgInfo($param)
    {
        $id = (int)$param['userID'];
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //判断是否是代理，如果是不是代理就将该用户生成顶级代理
        $arrayKeyValue = ['id'];
        $where = "userid = {$id}";
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $arrayKeyValue, $where);
        //查询出用户信息
        $arrayKeyValue = ['phone','name'];
        $where = "userID = {$id}";
        $userinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);
        if (empty($resinfo)) {  //不是代理
            $res = $this->get_superior($id);
            $data['superior_agentid'] = $res['superior_agentid'];
            $data['superior_username'] = $res['superior_username'];
            $data['username'] = $userinfo['name'];
            $data['userid'] = $id;
            $data['agentid'] = $id;
            $data['register_time'] = time();
            $res = DBManager::getMysql()->insert(MysqlConfig::Table_web_agent_member, $data);
            if(empty($res)) AppModel::returnJson(ErrorConfig::ERROR_CODE, '代理添加失败');
        }

        if(empty($userinfo['phone'])) AppModel::returnJson(ErrorConfig::ERROR_CODE, '请先绑定手机号');

        $url = self::ACCESS_DOMAIN.'/home/wechat/share.html?userID='.$id;
        $img = '';

        $value = $url;  //二维码内容;
        $errorCorrectionLevel = 'H'; //容错级别
        $matrixPointSize = 7; //生成图片大小
        //生成二维码图片
        if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
            require_once dirname(__DIR__) . '\phpqrcode\phpqrcode.php';
            $Path = dirname(__DIR__) . '\synthesis\\'.date('Ymd');
            $filename = $Path.'\\'.$id.'_agent_qrcode.png';
            $QR = dirname(__DIR__) .'\synthesis\logo\20190312200148.png'; //准备好的logo图片
            $png = $Path.'\\' . md5($id) . '.png';
            $qrimg = 'hm_ucenter\synthesis\\'.date('Ymd').'\\'.$id.'_agent_qrcode.png';
            $extension = 'hm_ucenter\synthesis\\'.date('Ymd').'\\' . md5($id) . '.png';
        }else{
            require_once dirname(__DIR__) . '/phpqrcode/phpqrcode.php';
            $Path = dirname(__DIR__) . '/synthesis/'.date('Ymd');
            $filename = $Path.'/'.$id.'_agent_qrcode.png';
            $QR = dirname(__DIR__) .'/synthesis/logo/20190312200148.png'; //准备好的logo图片
            $png = $Path.'/' . md5($id) . '.png';
            $qrimg = '/hm_ucenter/synthesis/'.date('Ymd').'/'.$id.'_agent_qrcode.png';
            $extension = '/hm_ucenter/synthesis/'.date('Ymd').'/' . md5($id) . '.png';
        }
        $imgopj = new \QRcode();
        if(!file_exists($Path))
        {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($Path, 0700);
        }

        $imgopj->png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = $filename; //已经生成的原始二维码图

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
            $logo_qr_height = $logo_height / ($scale); //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2; //组合之后logo左上角所在x坐标点
            $from_height = ($QR_width - $logo_qr_width + 220) / 2; //组合之后logo左上角所在y坐标点

            //重新组合图片并调整大小
            /*
             *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        //输出图片
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

        }
        $returnarr['qrimg'] = $qrimg;
        $returnarr['extension'] = $extension;
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returnarr);
    }


    /*
     * 获取我的推广信息
     * @param $param
     * */
    public function showExtensionInfo($param)
    {
        $userID = (int)$param['userID'];
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //获取用户的上级代理id
        $arrayKeyValue1 = ['superior_agentid','agentid'];
        $where1 = "userid = {$userID}";
        $memberinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $arrayKeyValue1, $where1);

        //获取团队人数
        //查询出所有的下级代理id
        $this->gettemcount($userID);
        $subordinate_agent_id = $this->zsdata();
        //查询出所有的会员ID
        $memberidarr = $this->getmemberid($memberinfo['agentid'], $subordinate_agent_id);
        /*var_dump($subordinate_agent_id);
        var_dump($memberidarr);*/
        //查询出所有下级代理的玩家以及自己的玩家
        $forarr = array_merge($subordinate_agent_id, [$userID]);
        $memberidarr = [];
        foreach ($forarr as $k9 => $v9){
            $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $subordinate_agent_id));
        }
        //$subordinate_agent_id  所有的下级代理id
        //$memberidarr  自己以及自己所有下级代理的玩家
        $sum_arr = array_merge($subordinate_agent_id, $memberidarr);

        $this->idArr = [];

        //获取今日新增下级代理人数
        $starttime = strtotime(date('Y-m-d', time()));
        $endtime = $starttime + 86400;
        $this->gettemcount($userID, "register_time > {$starttime} and register_time < $endtime and ");
        $addsubordinate_agent_id = $this->zsdata();
        //查询出今日新增所有的下级代理的玩家以及自己的玩家
        $forarr = array_merge($addsubordinate_agent_id, [$memberinfo['agentid']]);
        $addmemberidarr = [];
        foreach ($forarr as $k9 => $v9){
            $addmemberidarr = array_merge($addmemberidarr, $this->getmemberid($v9, $subordinate_agent_id, "bind_time > {$starttime} and bind_time < $endtime and "));
        }

        //查询出今日活跃人数，只要今日完了游戏就算活跃人数
        $sumagentarr = implode(',', $sum_arr);
        $where = "time > {$starttime} and time < $endtime and userID IN ({$sumagentarr}) and reason = 3 group by  userID";
        $arrayKeyValue1 = ['id'];
        $active_number = DBManager::getMysql()->selectAll(MysqlConfig::Table_statistics_moneychange, $arrayKeyValue1, $where);

        $returndata['parentid'] = $memberinfo['superior_agentid'];//上级id
        $returndata['id'] = $userID;//用户id
        $returndata['team_num'] = count($sum_arr);//团队人数
        $returndata['direct_player_num'] = count($this->getmemberid($userID, $subordinate_agent_id));//直属玩家人数
        $returndata['add_today_num'] = count(array_merge($addsubordinate_agent_id, $addmemberidarr));//今日新增人数
        $returndata['active_number'] = count($active_number);//今日活跃人数

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returndata);
    }

    /*
     * 获取所有会员id
     * */
    protected function getmemberid($agentid, $subordinate_agent_id, $where = ''){
        $where1 = $where."agentID = ".$agentid;
        //找到不是代理的用户
        if(!empty($subordinate_agent_id)){
            $agentarr = implode(',', $subordinate_agent_id);
            $where1 .= " and userID NOT IN ({$agentarr})";
        }
        $arrayKeyValue1 = ['userID'];
        $dataInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_bind, $arrayKeyValue1, $where1);
        return array_column($dataInfo, 'userID');
    }

    /*
     * 组装数据
     * */
    protected function zsdata(){
        $returndata = [];
        foreach ($this->idArr as $k => $v) {
            if(!in_array($v['userid'], $returndata)){
                $returndata[] = $v['userid'];
            }
            if(!empty($v['totalid'])){
                foreach ($v['totalid'] as $k2 => $v2){
                    if(!in_array($v2['userid'], $returndata)){
                        $returndata[] = $v2['userid'];
                    }
                }
            }

        }
        return $returndata;
    }

    //获取团队人数
    /*
     * @param int $userid  用户id
     * */

    protected function gettemcount($userID, $where = ''){
        $arrayKeyValue1 = ['userid'];
        $where1 = $where."superior_agentid = {$userID}";
        $dataInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_member, $arrayKeyValue1, $where1);

        foreach ($dataInfo as $k => $v) {
            $arrayKeyValue2 = ['userid'];
            $where2 = $where."superior_agentid = {$v['userid']}";
            $v['totalid'] = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_member, $arrayKeyValue2, $where2);
            $this->idArr[] = $v;
            self::gettemcount($v['userid'], $where);
        }
    }

    /**
     * 获取我的玩家信息(直属下级和玩家)
     * @param $param
     */
    public function showPlayerInfo($param)
    {
        $userID = (int)$param['userID'];
        $searchid = (int)$param['searchid'];
        $page = empty((int)$param['page']) ? 1 : (int)$param['page'];
        $pagesize = self::PAGE_SIZE;
        $startnum = ($page * $pagesize) - $pagesize;
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //查询出直属下级用户
        $arrayKeyValue1 = ['userid'];
        $where1 = empty($searchid) ? "superior_agentid = {$userID}" : "superior_agentid = {$userID} and userid = {$searchid}";
        $dataInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_member, $arrayKeyValue1, $where1);
        //var_dump($dataInfo);

        //查询出我的玩家用户
        $where2 = "agentID = {$userID}";
        if(!empty($dataInfo)){
            $notUserID = implode(',', array_column($dataInfo, 'userid'));
            $where2 = empty($searchid) ? "agentID = {$userID} and userID NOT IN ({$notUserID})" : "agentID = {$userID} and userID NOT IN ({$notUserID}) and userID = {$searchid}";
        }
        $arrayKeyValue2 = ['userid'];
        $bindInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_bind, $arrayKeyValue2, $where2);
        //var_dump($bindInfo);
        $list_id_arr = array_merge($dataInfo, $bindInfo);
        $performanceInfo = [];
        //var_dump($list_id_arr);exit;
        if(!empty($list_id_arr)){
            $inUserID = implode(',', array_column($list_id_arr, 'userid'));
            $create_date = date('Y-m-d', time());
            $where3 = "create_date = '{$create_date}' and userID IN ({$inUserID}) LIMIT {$startnum},{$pagesize}";
            $arrayKeyValue3 = ['userid','name','day_team_performance','day_personal_performance'];
            $performanceInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_statistics_day_performance, $arrayKeyValue3, $where3);

            foreach ($performanceInfo as $k1 => $v1){
                //获取团队人数
                $this->gettemcount($v1['userid']);
                $subordinate_agent_id = $this->zsdata();
                //查询出所有的会员ID
                $memberidarr = $this->getmemberid($v1['userid'], $subordinate_agent_id);
                //查询出所有下级代理的玩家以及自己的玩家
                $forarr = array_merge($subordinate_agent_id, [$v1['userid']]);
                $memberidarr = [];
                foreach ($forarr as $k9 => $v9){
                    $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $subordinate_agent_id));
                }
                //团队人数
                $performanceInfo[$k1]['team_num'] = count(array_merge($subordinate_agent_id, $memberidarr));
                //直属玩家人数
                $performanceInfo[$k1]['direct_player_num'] = count($this->getmemberid($v1['userid'], $subordinate_agent_id));
                $this->idArr = [];
            }
        }

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $performanceInfo);

    }


    /**
     * 获取我的业绩信息
     * @param $param
     */
    public function showAchievementsInfo($param)
    {
        $userID = (int)$param['userID'];
        $page = empty((int)$param['page']) ? 1 : (int)$param['page'];
        $pagesize = self::PAGE_SIZE;
        $startnum = ($page * $pagesize) - $pagesize;
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        $create_date = date('Y-m-d', time());
        $where3 = "create_date = '{$create_date}' and userID = {$userID}";
        $arrayKeyValue3 = ['day_performance','day_team_performance','day_personal_performance'];
        $returnInfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_statistics_day_performance, $arrayKeyValue3, $where3);
        //var_dump($dayperformanceInfo);

        //计算预估收益
        $this->gettemcount($userID);
        $subordinate_agent_id = $this->zsdata();
        //var_dump($subordinate_agent_id);exit;
        $inUserID = implode(',', $subordinate_agent_id);
        //var_dump($inUserID);exit;
        $create_date = date('Y-m-d', time());
        $where4 = "create_date = '{$create_date}' and userID IN ({$inUserID})";
        $arrayKeyValue4 = ['day_performance'];
        $dayperformanceInfoArr = DBManager::getMysql()->selectAll(MysqlConfig::Table_statistics_day_performance, $arrayKeyValue4, $where4);
        //var_dump($dayperformanceInfoArr);
        //计算本人今天到现在的收益
        $myTimeBenefit = $this->getJlmongey($returnInfo['day_performance']);
        //计算下级每个人的收益，相加
        $sumBenefit = '';
        foreach ($dayperformanceInfoArr as $k => $v){
            $sumBenefit += $this->getJlmongey($v['day_performance']);
        }

        $returnInfo['estimated_revenue'] = $myTimeBenefit - $sumBenefit;
        //查询出业绩列表
        $where8 = "userID = {$userID} LIMIT {$startnum},{$pagesize}";
        $arrayKeyValue8 = ['create_date','day_performance','day_team_performance','day_personal_performance'];
        $listInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_statistics_day_performance, $arrayKeyValue8, $where8);
        $returnInfo['performance_list'] = $listInfo;
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returnInfo);

    }


    /*
     * 根据每个人的业绩计算奖励
     * $param int $performance 用户的业绩
     * return  返回该用户的收益
     * */
    public function getJlmongey($performance)
    {
        //查询出抽层比例信息
        $arrayKeyValue4 = ['agent_start_value','agent_end_value','pump_money'];
        $ratioInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_pumping_ratio, $arrayKeyValue4);
        foreach ($ratioInfo as $key => $value){
            if($performance >= $value['agent_start_value'] && $performance < $value['agent_end_value']){
                return floor($performance/10000) * $value['pump_money'];
            }
        }

    }

    /*
     * 获取我的奖励信息
     * */
    public function showRewardInformation($param)
    {
        //var_dump(self::PAGE_SIZE);exit;
        $userID = (int)$param['userID'];
        $page = empty((int)$param['page']) ? 1 : (int)$param['page'];
        $pagesize = self::PAGE_SIZE;
        $startnum = ($page * $pagesize) - $pagesize;
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }
        $date = date('Y-m-d', time());
        $where4 = "userID = {$userID} and create_date <> '{$date}' LIMIT {$startnum},{$pagesize}";
        $arrayKeyValue4 = ['create_date','day_performance','day_team_performance','day_personal_performance','reward'];
        $returnInfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_statistics_day_performance, $arrayKeyValue4, $where4);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returnInfo);
    }







}