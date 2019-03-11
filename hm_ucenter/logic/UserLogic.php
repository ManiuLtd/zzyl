<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
<<<<<<< HEAD
 * Time: 19:40
=======
 * Time: 17:46
>>>>>>> master
 */

namespace logic;

use config\ErrorConfig;
use config\StrategyConfig;
use helper\LogHelper;
use model\AgentModel;

use config\EnumConfig;
use model\PhoneModel;
use model\UserModel;

class UserLogic extends BaseLogic
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    protected function verifyBindAgent($userID, $inviteUserID, $bindMode) {
        $res = UserModel::getInstance()->isUserExists($userID);
        if (!$res) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '该用户不存在');
        }
        $res = UserModel::getInstance()->isUserExists($inviteUserID);
        if (!$res) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '上级用户不存在');
        }
        //上级是否为代理
        $res = UserModel::getInstance()->isAgent($inviteUserID);
        if (!$res) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '邀请用户不为代理');
        }
        if ($userID == $inviteUserID) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '不能绑定自己的邀请码');
        }
        //登录用户未绑定上级
        if (AgentModel::getInstance()->isExistsUpAgent($userID)) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '该用户已存在上级');
        }
        //
        return $this->returnData(ErrorConfig::SUCCESS_CODE, '验证通过');
    }
    /**
     * @param $userID
     * @param $inviteUserID
     * @return array
     */
    public function bindAgent($userID, $inviteUserID, $bindMode = StrategyConfig::M_BIND_AGENT_MODE['BIND2USER'])
    {
        //
        $res = $this->verifyBindAgent($userID, $inviteUserID, $bindMode);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            return $res;
        }
        $userInfo = UserModel::getInstance()->getUserInfo($userID, ['name']);
        $agentInfo = UserModel::getInstance()->getUserInfo($inviteUserID, ['name']);
        //绑定成为玩家
        $res = AgentModel::getInstance()->addBindAgent([
            'userID' => $userID,
            'agentID' => $inviteUserID,
            'username' => $userInfo['name'],
            'agentname' => $agentInfo['name'],
            'bind_time' => time(),
        ]);
        if (!$res) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '绑定失败');
        }
        $isAgent = UserModel::getInstance()->isAgent($userID);

        if($isAgent){
            $agent = AgentModel::getInstance()->getAgentMemberByUserID($userID,['agentid']);
            $res = AgentModel::getInstance()->updateAgentMember($agent['agentid'],['superior_agentid'=>$inviteUserID,'superior_username'=>$agentInfo['name']]);
            if(!$res){
                return $this->returnData(ErrorConfig::ERROR_CODE, '绑定失败');
            }
        }
        if (StrategyConfig::M_BIND_AGENT_MODE['BIND2AGENT'] == $bindMode) {
            //绑定成为代理
            //如果登录用户已是代理，跳过，否则成为代理
        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE, '绑定成功');
    }

    protected function verifyResetPassword($data = [], $type = 1) {
        if (!in_array($type, EnumConfig::E_ResetPasswdVerifyType)) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '重置验证类型不正确');
        }
        $resData = [];
        if (EnumConfig::E_ResetPasswdVerifyType['PHONE_CODE'] == $type) {
            //验证手机号码
            $myreg = "/^1[345678]{1}\d{9}$/";
            $res = preg_match($myreg, $data['phone']);
            if (!$res) {
                return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_NO);
            }

            //该手机号是否没被绑定
            if (!PhoneModel::getInstance()->isPhoneBind($data['phone'])) {
                return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_PHONE_NOT_BIND);
            }

            // 验证验证码
            $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($data['phone'], EnumConfig::E_PhoneCodeType['PASSWORD']);
            $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
            $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];
            if ($phoneCode != $data['code']) {
                return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
            }
            // 验证验证码时间
            if (time() - $phoneTime > self::CODE_INVALID_TIME) {
                return $this->returnData(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
            }
        } elseif (EnumConfig::E_ResetPasswdVerifyType['OLD_PASSWORD'] == $type) {

        }
        return $this->returnData(ErrorConfig::SUCCESS_CODE,  '验证通过', $resData);
    }
    public function resetPassword($userID, $password, $oldPassword = 0, $code = 0, $type = EnumConfig::E_ResetPasswdVerifyType['PHONE_CODE']) {
//        $phone = $params['phone'];
//        $code = $params['code'];
//        $password = $params['password'];
        $res = $this->verifyResetPassword([
            'code' => $code,
            'password' => $password,
            'oldPassword' => $oldPassword,
            'userID' => $userID,
        ],$type);
        if (ErrorConfig::ERROR_CODE === $res['code']) {
            return $res;
        }

        PhoneModel::getInstance()->updatePhonePassword($phone, $password);
        return $this->returnData(ErrorConfig::SUCCESS_CODE, ErrorConfig::ERROR_MSG_BIND_SUCCESS);
    }

    protected function verifyChangeResource($targetUserID, $resourceType, $num, $changeReason, $roomID, $friendsGroupID, $rateMoney, $isAllowInGame) {
        if (0 == $isAllowInGame) {
            $isInGame = UserModel::getInstance()->isUserInGame($targetUserID, ['roomID']);
            if ($isInGame) {
                return $this->returnData(ErrorConfig::ERROR_CODE, '用户正在游戏中，无法修改' . EnumConfig::E_ResourceTypeName[$resourceType]);
            }
        }

    }
    public function changeResource($targetUserID, $resourceType, $num, $changeReason, $roomID = 0, $friendsGroupID = 0, $rateMoney = 0, $isAllowInGame = 1) {
        $res = $this->verifyChangeResource($targetUserID, $resourceType, $num, $changeReason, $roomID, $friendsGroupID, $rateMoney, $isAllowInGame);
        if (ErrorConfig::ERROR_CODE == $res['code']) {
            return $res;
        }
        $result = UserModel::getInstance()->changeUserResource($targetUserID, $resourceType, $num, $changeReason, $roomID, $friendsGroupID, $rateMoney, $isAllowInGame);
        if (!$result) {
            return $this->returnData(ErrorConfig::ERROR_CODE, '修改' . EnumConfig::E_ResourceTypeName[$resourceType] . '失败');
        }

        return $this->returnData(ErrorConfig::SUCCESS_CODE, '修改' . EnumConfig::E_ResourceTypeName[$resourceType] . '成功');
    }

    private function __clone()
    {
    }
}