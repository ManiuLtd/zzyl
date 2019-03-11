<?php
namespace action;
use config\EnumConfig;
use config\ErrorConfig;
use model\AgentModel;
use model\AppModel;
use model\EmailModel;
use notify\CenterNotify;

/**
 * 代理业务
 * Class AgentAction
 */
class AgentAction extends AppAction
{
    protected $whileAction = ['loopBattleRecord'];

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
     * 获取绑定邀请信息
     * @param $params
     */
    public function bindInviteInfo($params)
    {
        $userID = (int)$params['userID'];
        $agentBind = AgentModel::getInstance()->getAgentBindByUserID($userID);
        if (empty($agentBind)) {
            $result = ['isBind' => false, 'msg' => ErrorConfig::ERROR_MSG_BIND_NOT, 'agentID' => ''];
        } else {
            $result = ['isBind' => true, 'msg' => ErrorConfig::ERROR_MSG_BIND_TOO, 'agentID' => $agentBind['agentID']];
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 绑定邀请码
     * @param $params
     */
    public function bindInviteCode($params)
    {
        $userID = (int)$params['userID'];
        $agent_id = (int)$params['inviteCode'];

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

        //获得我的代理信息
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
        //添加记录
        $name = AgentModel::getInstance()->getUserInfo($userID, 'name');
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
            AgentModel::getInstance()->updateAgentMember($my_agent['agentid'], $my_agent);
        }

        $bindAgentReward = AgentModel::getInstance()->getBindAgentReward($userID);
        if (empty($bindAgentReward)) {
            //获得礼品
            $config = AgentModel::getInstance()->getConfig();
            $bind_agentid_send_jewels = (int)$config['bind_agentid_send_jewels'];
            $bind_agentid_send_money = (int)$config['bind_agentid_send_money'];

            //添加绑定代理奖励记录
            AgentModel::getInstance()->addBindAgentReward($userID, $bind_agentid_send_money, $bind_agentid_send_jewels);

            // 创建邮件
            $title = "绑定邀请码奖励";
            $content = "绑定邀请码{$addData['agentID']}成功，\n获得奖励：{$bind_agentid_send_jewels}钻石,{$bind_agentid_send_money}金币";
            $goodsArray = [
                EnumConfig::E_ResourceType['JEWELS'] => $bind_agentid_send_jewels,
                EnumConfig::E_ResourceType['MONEY'] => $bind_agentid_send_money,
            ];
            $goodsList = EmailModel::getInstance()->makeEmailGoodsList($goodsArray);
            $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BIND_AGENT'], $title, $content, $goodsList);
            // 添加邮件
            EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $userID);

            // 小红点
            CenterNotify::sendRedSport($userID, EnumConfig::E_RedSpotType['MAIL']); // 小红点
        }

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    public function loopBattleRecord() {
        ini_set('display_errors', 1);
        (new AgentModel())->loopBattleRecord();
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    public function getAgentInfo($params) {
        $userID = (int)$params['userID'];
        $arrAgentInfo = AgentModel::getInstance()->getAgentMemberByUserID($userID,['username', 'userid', 'agentid', 'superior_agentid', 'superior_username', 'is_franchisee']);
        $arrAgentInfo = $arrAgentInfo ? $arrAgentInfo : [];
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $arrAgentInfo);
    }
}
