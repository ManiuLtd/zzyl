<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\BankModel;
use model\EmailModel;
use model\UserModel;
use notify\CenterNotify;

/**
 * 银行业务
 * Class BankAction
 */
class BankAction extends AppAction
{
    const BANK_PASSWD_LEN = 6;
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        $this->_check_play_game = true;
        parent::__construct();
    }

    private function __clone()
    {
    }

    /**
     * 设置银行密码
     * @param $param
     */
    public function setBankPasswd($param)
    {
        $userID = (int)$param['userID'];
        $passwd = $param['passwd'];
        $email = $param['email'];

        // 检测长度
        if (strlen($passwd) != self::BANK_PASSWD_LEN) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }

        // 检测邮箱
        if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INCORRECT_MAILBOX_FORMAT);
        }

        BankModel::getInstance()->updateUserInfo($userID, ['bankPasswd' => $passwd, 'mail' => $email]);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 修改密码银行密码
     * @param $param
     */
    public function changeBankPasswd($param)
    {
        $userID = (int)$param['userID'];
        $oldPasswd = $param['oldPasswd'];
        $newPasswd = $param['newPasswd'];

        $bankPasswd = BankModel::getInstance()->getUserInfo($userID, 'bankPasswd');

        // 原密码不正确
        if ($bankPasswd != $oldPasswd) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_OLDPASSWD_YES);
        }

        // 检测长度
        if (strlen($newPasswd) != self::BANK_PASSWD_LEN) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }

        BankModel::getInstance()->updateUserInfo($userID, ['bankPasswd' => $newPasswd]);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 银行存钱
     * @param $param
     */
    public function bankSaveMoney($param)
    {
        $userID = (int)$param['userID']; // 用户ID
        $money = (int)$param['money']; // 金币

        $userInfo = BankModel::getInstance()->getUserInfo($userID, ['money', 'bankMoney', 'name']);

        // 获取配置
        $config = BankModel::getInstance()->getConfig();

        // 最低存款
        if ($money < $config['bankMinSaveMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "银行最低存款金币为" . $config['bankMinSaveMoney'] . "金币");
        }

        // 账户金币
        if ($userInfo['money'] < $config['bankMinSaveMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DEPOSIT_GOLD_COSIN_BEYOND_ITS_OWN_LIMITS);
        }

        $money = $money > $userInfo['money'] ? $userInfo['money'] : $money;

        // 存款必须是1000 的倍数
        $y = $money % $config['bankSaveMoneyMuti'];

        if ($y != 0) {
            $money = $money - $y;
        }

        // 剩余
        if ($money < 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DEPOSIT_GOLD_COSIN_BEYOND_ITS_OWN_LIMITS);
        }

        // 操作记录
        BankModel::getInstance()->addBankOperateRecord($userID, EnumConfig::E_BankOperateType['SAVE'], $money);
        BankModel::getInstance()->changeUserResource($userID,EnumConfig::E_ResourceType['MONEY'],-$money,EnumConfig::E_ResourceChangeReason['BANK_SAVE']);
        BankModel::getInstance()->changeUserResource($userID,EnumConfig::E_ResourceType['BANKMONEY'],$money,EnumConfig::E_ResourceChangeReason['BANK_SAVE']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 银行取钱
     * @param $param
     */
    public function bankTakeMoney($param)
    {
        $userID = (int)$param['userID']; // 用户ID
        $money = (int)$param['money']; // 金币

        $userInfo = BankModel::getInstance()->getUserInfo($userID, ['money', 'bankMoney', 'name']);

        // 获取配置
        $config = BankModel::getInstance()->getConfig();

        // 最低取款
        if ($money < $config['bankMinTakeMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "取款金额不能低于{$config['bankMinTakeMoney']}金币");
        }

        // 银行最低钱
        if ($userInfo['bankMoney'] < $config['bankMinTakeMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "取款金额不能低于{$config['bankMinTakeMoney']}金币");
        }

        $money = $money > $userInfo['bankMoney'] ? $userInfo['bankMoney'] : $money;

        $y = $money % $config['bankTakeMoneyMuti']; // 最低取钱数

        if ($y != 0) {
            $money = $money - $y;
        }

        // 操作记录
        BankModel::getInstance()->addBankOperateRecord($userID, EnumConfig::E_BankOperateType['TAKE'], $money);
        BankModel::getInstance()->changeUserResource($userID,EnumConfig::E_ResourceType['MONEY'],$money,EnumConfig::E_ResourceChangeReason['BANK_TAKE']);
        BankModel::getInstance()->changeUserResource($userID,EnumConfig::E_ResourceType['BANKMONEY'],-$money,EnumConfig::E_ResourceChangeReason['BANK_TAKE']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 银行转账
     * @param $param
     */
    public function bankTranMoney($param)
    {
        $userID = (int)$param['userID']; // 用户ID
        $money = (int)$param['money']; // 金币 如: 存款,取款,转账
        $targetUserID = (int)$param['targetUserID']; // 转账ID

        $userInfo = BankModel::getInstance()->getUserInfo($userID, ['money', 'bankMoney', 'name']);

        // 获取配置
        $config = BankModel::getInstance()->getConfig();

        if (!BankModel::getInstance()->isUserExists($targetUserID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_TRANSFER_USERS_DO_NOT_EXIST);
        }

        if ($money < $config['bankMinTransfer']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "转账金额不能低于{$config['bankMinTransfer']}金币");
        }

        // 转账金币超出自身
        if ($money > $userInfo['bankMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DEPOSIT_GOLD_BANK_BEYOND_ITS_OWN_LIMITS);
        }

        $y = $money % $config['bankTransferMuti']; // 转账倍数

        if ($y != 0) {
            $money = $money - $y;
        }
        // 操作记录
        BankModel::getInstance()->addBankOperateRecord($userID, EnumConfig::E_BankOperateType['TRAN'], $money, $targetUserID);
        BankModel::getInstance()->changeUserResource($userID,EnumConfig::E_ResourceType['BANKMONEY'],-$money,EnumConfig::E_ResourceChangeReason['BANK_TRAN']);
        BankModel::getInstance()->changeUserResource($targetUserID,EnumConfig::E_ResourceType['BANKMONEY'],$money,EnumConfig::E_ResourceChangeReason['BANK_TRAN']);

        // 创建邮件
        $title = "银行金币到账通知";
        $content = "{$userInfo['name']}通过银行给您转账{$money}金币";
        $emailDetailInfo = EmailModel::getInstance()->createEmail(0, EnumConfig::E_ResourceChangeReason['BANK_TRAN'], $title, $content);
        // 添加邮件
        EmailModel::getInstance()->addEmailToUser($emailDetailInfo, $targetUserID);
        // 小红点
        CenterNotify::sendRedSport($targetUserID, EnumConfig::E_RedSpotType['MAIL']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 银行操作记录
     * @param $param
     */
    public function bankOperateRecord($param)
    {
        $userID = (int)$param['userID']; // 用户ID
        $data = BankModel::getInstance()->getBankOperateRecord($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }

    /**
     * 发送密码到邮箱
     * @param $param
     */
    public function sendBankPasswd($param)
    {
        $userID = (int)$param['userID'];

        $userInfo = BankModel::getInstance()->getUserInfo($userID, ['mail', 'bankPasswd']);

        $result = EmailModel::getInstance()->sendEmail($userInfo['mail'], '找回密码', "您的银行密码是【{$userInfo['bankPasswd']}】");

        //银行密码统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID,'passwordBankCount');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }
}
