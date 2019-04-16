<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\BankModel;
use model\EmailModel;
use model\UserModel;
use notify\CenterNotify;
use manager\DBManager;
use config\MysqlConfig;
use model\PhoneModel;

/**
 * 银行业务
 * Class BankAction
 */
class BankAction extends AppAction
{
    const BANK_PASSWD_LEN = 6;
    private static $_instance = null;

    //每个手机每天发送验证码次数
    const DAY_SEND_CODE_COUNT = 300;

    //验证码有效时间 10 分钟
    const CODE_INVALID_TIME = 10 * 60;

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
        $inputpasswd = $param['inputpasswd'];
        $confirmpasswd = $param['confirmpasswd'];
        //$email = $param['email'];
        $code = $param['code'];

        //判断参数不能为空
        if (empty($param['userID']) || empty($param['inputpasswd']) || empty($param['code']) || empty($param['confirmpasswd'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //验证密码格式 包含字母、数字以及下划线，且至少包含2种
        $myreg = "/^(?![0-9]+$)(?![_]+$)(?![a-zA-Z]+$)[A-Za-z_0-9]{1,}$/";
        $res1 = preg_match($myreg, $inputpasswd);
        if (!$res1){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER_GESHI);
        }

        //两次密码
        if($inputpasswd != $confirmpasswd){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_ATYPISM);
        }

        // 检测长度
        if (strlen($inputpasswd) < 8 || strlen($inputpasswd) > 16) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }

        // 检测邮箱
        /*if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INCORRECT_MAILBOX_FORMAT);
        }*/

        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['phone','passwd'], "userID = {$userID}");

        //验证密码不能等于登录密码
        if(md5($inputpasswd) == $resinfo['passwd']){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_LOGIN);
        }

        //获取验证码信息
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($resinfo['phone'], 4);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];

        if ($phoneCode !== $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        BankModel::getInstance()->updateUserInfo($userID, 'bankPasswd',"'".$inputpasswd."'");
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 修改密码银行密码
     * @param $param
     */
    public function changeBankPasswd($param)
    {
        $userID = (int)$param['userID'];
        $oldPasswd = $param['oldPasswd']; //原始密码
        $newPasswd = $param['newPasswd']; //新密码
        $confirmpasswd = $param['confirmpasswd']; //再次输入新密码
        //$code = $param['code']; //验证码

        //判断参数不能为空
        if (empty($param['userID']) || empty($param['oldPasswd']) || empty($param['confirmpasswd']) || empty($param['newPasswd'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //$bankPasswd = BankModel::getInstance()->getUserInfo($userID, 'bankPasswd');
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['phone', 'phonePasswd', 'bankpasswd'], "userID = {$userID}");

        // 原密码不正确
        if ($resinfo['bankpasswd'] != $oldPasswd) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_OLDPASSWD_YES);
        }

        //新密码和原始密码不能一致
        if($resinfo['bankpasswd'] == $newPasswd){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_OLDPASSWD_OLD_NEW);
        }


        //验证密码格式 包含字母、数字以及下划线，且至少包含2种
        $myreg = "/^(?![0-9]+$)(?![_]+$)(?![a-zA-Z]+$)[A-Za-z_0-9]{1,}$/";
        $res1 = preg_match($myreg, $newPasswd);
        if (!$res1){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER_GESHI);
        }

        //两次密码
        if($newPasswd != $confirmpasswd){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_ATYPISM);
        }

        // 检测长度
        if (strlen($newPasswd) < 8 || strlen($newPasswd) > 16) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }

        // 检测邮箱
        /*if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INCORRECT_MAILBOX_FORMAT);
        }*/

        //验证密码不能等于登录密码
        if(md5($newPasswd) == $resinfo['phonePasswd']){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_LOGIN);
        }

        //获取验证码信息
        /*$phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($resinfo['phone'], 4);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];

        if ($phoneCode !== $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }*/

        // 检测长度
        /*if (strlen($newPasswd) != self::BANK_PASSWD_LEN) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }*/

        BankModel::getInstance()->updateUserInfo($userID, 'bankPasswd',"'".$newPasswd."'");
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
        if ($money/100 < $config['bankMinSaveMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "银行最低存款金币为" . $config['bankMinSaveMoney'] . "金币");
        }

        // 账户金币
        if ($userInfo['money'] < $money) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DEPOSIT_GOLD_COSIN_BEYOND_ITS_OWN_LIMITS);
        }

        //存钱金额必须是 $config['bankSaveMoneyMuti'] 的整数倍
        $keyword = $money/(100 * $config['bankSaveMoneyMuti']);
        if(!preg_match("/^[1-9][0-9]*$/",$keyword)) AppModel::returnJson(ErrorConfig::ERROR_CODE, '存钱必须是'.$config['bankSaveMoneyMuti'].'的整数倍');


        //$money = $money > $userInfo['money'] ? $userInfo['money'] : $money;

        // 存款必须是1000 的倍数
        /*$y = $money % $config['bankSaveMoneyMuti'];

        if ($y != 0) {
            $money = $money - $y;
        }

        // 剩余
        if ($money < 0) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DEPOSIT_GOLD_COSIN_BEYOND_ITS_OWN_LIMITS);
        }*/


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
        if ($money/100 < $config['bankMinTakeMoney']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "取款金额不能低于{$config['bankMinTakeMoney']}金币");
        }

        // 银行最低钱
        if ($userInfo['bankMoney'] < $money) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "取款金币超出银行存款金币");
        }

        //取钱金额必须是 $config['bankTakeMoneyMuti'] 的整数倍
        $keyword = $money/(100 * $config['bankTakeMoneyMuti']);
        if(!preg_match("/^[1-9][0-9]*$/",$keyword)) AppModel::returnJson(ErrorConfig::ERROR_CODE, '取钱必须是'.$config['bankTakeMoneyMuti'].'的整数倍');

        /*$money = $money > $userInfo['bankMoney'] ? $userInfo['bankMoney'] : $money;

        $y = $money % $config['bankTakeMoneyMuti']; // 最低取钱数

        if ($y != 0) {
            $money = $money - $y;
        }*/

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
     * 设置银行密码
     * @param $param
     */
    public function sendBankPasswd($param)
    {
        $userID = (int)$param['userID'];
        $newPasswd = $param['newPasswd']; //新密码
        $confirmpasswd = $param['confirmpasswd']; //再次输入新密码
        $code = $param['code']; //验证码

        //判断参数不能为空
        if (empty($param['userID']) || empty($param['code']) || empty($param['confirmpasswd']) || empty($param['newPasswd'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //$bankPasswd = BankModel::getInstance()->getUserInfo($userID, 'bankPasswd');
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['phone', 'phonePasswd'], "userID = {$userID}");

        //验证密码格式 包含字母、数字以及下划线，且至少包含2种
        $myreg = "/^(?![0-9]+$)(?![_]+$)(?![a-zA-Z]+$)[A-Za-z_0-9]{1,}$/";
        $res1 = preg_match($myreg, $newPasswd);
        if (!$res1){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER_GESHI);
        }

        //两次密码
        if($newPasswd != $confirmpasswd){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_ATYPISM);
        }

        // 检测长度
        if (strlen($newPasswd) < 8 || strlen($newPasswd) > 16) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }

        // 检测邮箱
        /*if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_INCORRECT_MAILBOX_FORMAT);
        }*/

        //验证密码不能等于登录密码
        if(md5($newPasswd) == $resinfo['phonePasswd']){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_REPASSWD_LOGIN);
        }

        //获取验证码信息
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($resinfo['phone'], 4);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];

        if ($phoneCode !== $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        // 检测长度
        /*if (strlen($newPasswd) != self::BANK_PASSWD_LEN) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_ISNUMBER);
        }*/

        //银行密码统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID,'passwordBankCount');

        BankModel::getInstance()->updateUserInfo($userID, 'bankPasswd',"'".$newPasswd."'");
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 银行登录
     * @param $param
     */
    public function banklogin($param)
    {
        $userID = (int)$param['userID']; // 用户ID
        $password = $param['password']; //密码
        if(empty($userID) || empty($password)) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, ['bankpasswd'], "userID = {$userID}");
        if($password != $resinfo['bankpasswd']) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BANK_PASSWD_YES);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
}
