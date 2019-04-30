<?php
namespace action;
use config\DynamicConfig;
use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use config\MysqlTableFieldConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use logic\ConfigLogic;
use logic\NewsLogic;
use manager\DBManager;
use model\AgentModel;
use model\AppModel;
use model\ConfigModel;
use model\UserModel;
use model\WelfareModel;
use model\NoticeModel;

/**
 * 福利业务 签到 转盘 救济金
 * Class WelfareAction
 */
class WelfareAction extends AppAction
{
    private static $_instance = null;

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

    /**
     * 获取签到配置
     * @param $params
     */
    public function signConfig($params)
    {
        $userID = (int)$params['userID'];
        $signConfig = WelfareModel::getInstance()->getUserSignConfig($userID);
        if (empty($signConfig)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_sign_config . "没有配置数据！");
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $signConfig);
    }

    /**
     * 获取转盘抽奖配置
     * @param $params
     */
    public function turntableConfig($params)
    {

        $turntableConfig = WelfareModel::getInstance()->getTurntableConfig();
//        var_export($turntableConfig);
        LogHelper::printDebug('-----------------2019' . json_encode($turntableConfig, JSON_UNESCAPED_SLASHES));
        if (empty($turntableConfig)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_turntable_config . "没有配置数据！");
        }
        foreach ($turntableConfig as $k => &$v) {
            // $turntableConfig[$k]['chance'] = $v;
            $v['num'] = FunctionHelper::MoneyOutput($v['num']);
            // echo FunctionHelper::MoneyOutput($v['chance']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $turntableConfig);
    }

    /**
     * 获取签到信息
     * @param $params
     */
    public function signInfo($params)
    {
        $userID = (int)$params['userID'];
        $signInfo['IsUseSign'] = WelfareModel::getInstance()->getIsUseSign($userID);;
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $signInfo);
    }

    /**
     * 获取转盘信息
     * @param $params
     */
    public function turntableInfo($params)
    {
        $userID = (int)$params['userID'];
        $turntableInfo['IsUseTurntable'] = WelfareModel::getInstance()->getIsUseTurntable($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $turntableInfo);
    }

    /**
     * 签到
     * @param $params
     */
    public function sign($params)
    {
        $userID = (int)$params['userID'];

        //是否签到过了
        if (!WelfareModel::getInstance()->getIsUseSign($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_SING_TOO);
        }

        //从第几天开始签到
        $signDay = WelfareModel::getInstance()->getSignDay($userID);
        $signReward = WelfareModel::getInstance()->getSignReward($signDay);
        if (empty($signReward)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "没有签到天数{$signDay}的奖励");
        }
        //获得用户信息
        $userInfo = WelfareModel::getInstance()->getUserInfo($userID, ['name']);
        $result = WelfareModel::getInstance()->addSignRecord($userID, $userInfo['name'], $signReward['prizeType'], $signReward['prize'], $signReward['num'], $signDay);
        if (empty($result)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "添加签到数据失败");
        }
        WelfareModel::getInstance()->changeUserResource($userID, $signReward['prizeType'], $signReward['num'], EnumConfig::E_ResourceChangeReason['SIGN']);
        
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 转盘抽奖
     * @param $params
     */
    public function turntable($params)
    {
        $userID = (int)$params['userID'];

        //是否抽过奖
        if (!WelfareModel::getInstance()->getIsUseTurntable($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TURNTABLE_TOO);
        }

        $turntableReward = WelfareModel::getInstance()->randomTurntableReward();
        if (empty($turntableReward)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "随机转盘奖励失败");
        }
        //获得用户信息
        $userInfo = WelfareModel::getInstance()->getUserInfo($userID, ['name']);
        $result = WelfareModel::getInstance()->addTurntableRecord($userID, $userInfo['name'], $turntableReward['prizetype'], $turntableReward['prize'], $turntableReward['num']);
        if (!$result) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "添加转盘数据失败");
        }
        WelfareModel::getInstance()->changeUserResource($userID, $turntableReward['prizetype'], $turntableReward['num'], EnumConfig::E_ResourceChangeReason['TURNTABLE']);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, ['turntableID' => $turntableReward['id']]);
    }

    /**
     * 获取补助信息
     * @param $param
     */
    public function supportInfo($param)
    {
        $userID = $param['userID'];
        $receiveCount = WelfareModel::getInstance()->getReceiveSupportCount($userID);
        $config = WelfareModel::getInstance()->getConfig();
        $remainCount = (int)$config['supportTimesEveryDay'] - $receiveCount;
        $data = array(
            'supportTimesEveryDay' => (int)$config['supportTimesEveryDay'],
            'supportMinLimitMoney' => (int)$config['supportMinLimitMoney'],
            'supportMoneyCount' => (int)$config['supportMoneyCount'],
            'leftSupportTimes' => $remainCount,
        );
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }

    /**
     * 领取救济金
     * @param $param
     */
    public function receiveSupport($param)
    {
        $userID = $param['userID'];
        $money = WelfareModel::getInstance()->getUserInfo($userID, 'money');

        $config = WelfareModel::getInstance()->getConfig();

        // 领取条件
        if ($money > $config['supportMinLimitMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "金币数低于{$config['supportMinLimitMoney']}才能领取救济金");
        }

        // 领取次数
        $receiveCount = WelfareModel::getInstance()->getReceiveSupportCount($userID);
        $remainCount = $config['supportTimesEveryDay'] - $receiveCount;
        if ($remainCount <= 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "每天只能领取{$config['supportTimesEveryDay']}次救济金");
        }
        // 记录日志
        $changeMoney = (int)$config['supportMoneyCount'];
        WelfareModel::getInstance()->writeReceiveSupport($userID, $receiveCount + 1, $changeMoney);
        WelfareModel::getInstance()->changeUserResource($userID, EnumConfig::E_ResourceType['MONEY'], $changeMoney, EnumConfig::E_ResourceChangeReason['SUPPORT']);

        $newMoney = WelfareModel::getInstance()->getUserInfo($userID, 'money');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, ['leftSupportTimes' => $remainCount - 1, 'newMoney' => (int)$newMoney]);
    }

    public function welfareList($params) {
        $resSignConfig = ConfigLogic::getInstance()->signConfig($params);
        $resTurnTableConfig = ConfigLogic::getInstance()->turntableConfig($params);
        $data = [
            [
                "content"  => "每日打卡签到可以获取丰厚的奖励哦！",
                "funKey"   => 2,
                "funName"  => "签  到",
                "funName3" => "已签到",
                "pic"      => "img_sign",
                "title"    => "每日签到",
            ],
            [
                "content"  => "每天转动轮盘，领取丰厚大奖",
                "funKey"   => 1,
                "funName"  => "抽  奖",
                "funName3" => "已抽奖",
                "pic"      => "img_zhuan",
                "title"    => "每日幸运轮盘",
            ],
            [
                "content" => "少于%s金币的时候可以领取，每次领取%s金币",
                "funKey"  => 3,
                "funName" => "领  取",
                "funName3" => "已领取",
                "pic"     => "img_gold",
                "title"   => "救济金",
            ],
            [
                "content"  => "绑定手机号使用手机号登录，绑定即送%s金币",
                "funKey"   => 4,
                "funName"  => "绑  定",
                "funName3" => "已绑定",
                "pic"      => "img_phone",
                "title"    => "绑定手机号",
            ]
        ];
        $data = DBManager::getMysql()->queryAll('select * from ' . MysqlConfig::Table_web_fun_config . ' where is_open = 1');
        array_walk($data, function(&$v, $k) {
            unset($v['is_open']);
            unset($v['id']);
            unset($v['sort']);
            $v['funKey'] = (int)$v['funKey'];
        });
        $needValue = [
//            'funkey' => 1,
//            'content' => 0,
        ];
//        FunctionHelper::arrayNeedValueToInt($data, $needValue);
//        var_export($data);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, 'get config success', $data);
    }

    /**
     * 初始化配置，代码待整理
     * @param $params
     */
    public function getInitInfo($params) {
        $userID = (int)$params['userID'];
        if (empty($userID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, 'userID 不能为空');
        }
        $agentUserID = AgentModel::getInstance()->getUpAgentInfoByUserID($userID, ['userid']);
        $where = ConfigModel::getInstance()->makeWhere(['keyConfig' => MysqlTableFieldConfig::OTHER_CONFIG_CONTACT_QRCODE]);
        $contactQrcode = FunctionHelper::getWebProtocal() . FunctionHelper::getServerHost() . '/download/contactQrcode.png';
        $arrContactQrcode = DBManager::getMysql()->queryRow('select * from ' . MysqlConfig::Table_otherconfig . $where . ' limit 1');
        if (!empty($arrContactQrcode)) {
            $configQrcode = htmlspecialchars_decode($arrContactQrcode['valueConfig']);
            $res = get_headers($configQrcode, 1);
            $contactQrcode = strstr($res[0], ' 200 OK') ? $configQrcode : $contactQrcode;
        }
        //获取系统公告
        $notice = NoticeModel::getInstance()->getNoticeList(EnumConfig::E_NoticeType['SPECIAL']);
        $notice = FunctionHelper::array_columns($notice, 'title,content');
        $arrAgentInfo = AgentModel::getInstance()->getAgentMemberByUserID($userID,['username', 'userid', 'agentid', 'superior_agentid', 'superior_username', 'is_franchisee']);
        if ($arrAgentInfo) {
            $arrAgentInfo['agentPhone'] = $arrAgentInfo['username'];
            unset($arrAgentInfo['username']);
        }
        $resNoticeInfo = NewsLogic::getInstance()->getAnnounce();
        //
        $data = DBManager::getMysql()->queryAll('select * from ' . MysqlConfig::Table_web_fun_config);
        $welfareListStatus = [];
        foreach ($data as $k => $v) {
            $welfareListStatus[] = ['funkey' => (int)$v['funKey'],'is_open' => (int)$v['is_open']];
        };

        $data = [
            'shareUrl' => 'http://zzyl.szbchm.com/download/fx.php',//分享链接
            'contactQrcode' => $contactQrcode,//客服二维码
            'agentUserID' => empty($agentUserID['userid']) ? '' : $agentUserID['userid'],//上级代理
            'sysNotice' => empty($notice) ? [] : $notice[0],//系统公告
            'agentInfo' => empty($arrAgentInfo) ? [''] : $arrAgentInfo,
//            'isAgent' => UserModel::getInstance()->isAgent($userID),//是否是代理
            'notifyInfo' => $resNoticeInfo['data'],

            'welfareListStatus' => $welfareListStatus,
//            'notifyInfo' => [
//                ['noticeID' => 1, 'noticeName' => '激情约战', 'noticeURL' => FunctionHelper::getWebProtocal() . FunctionHelper::getServerHost() . '/download/noticeInfo1.jpg'],
//            ],
        ];
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, 'get config success', $data);
    }
}
