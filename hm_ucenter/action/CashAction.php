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
use config\EnumConfig;
use helper\FunctionHelper;
use model\UserModel;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/2
 * Time: 10:50
 */

/**
 * 客户端申请提现业务
 * Class CashAction
 */
class CashAction extends AppAction
{
    private static $_instance = null;
    //验证码有效时间 10 分钟
    const CODE_INVALID_TIME = 10 * 60;
    //日志标签名字
    const LOG_TAG_NAME = 'CASH';

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
     * 查询出用户的金币数，和是否绑定过银行卡 以及是否绑定了手机号，没有绑定不能提现
     * @param $param
     */
    public function showUserInfo($param)
    {
        $userID = (int)$param['userID'];
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //获取金币
        $arrayKeyValue = ['phone'];
        $where = "userID = {$userID}";
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);

        //获取绑定银行卡数量
        $res = UserCashBank::getInstance()->getCount('userID ='.$userID.' and account_type = 1');

        //获取绑定支付宝数量
        $zfbres = UserCashBank::getInstance()->getCount('userID ='.$userID.' and account_type = 2');

        $resinfo['is_bind_card'] = empty($res)? 0 : 1; //0没有绑定卡  1已经绑定银行卡
        $resinfo['is_bind_zfb'] = empty($zfbres)? 0 : 1; //0没有绑定支付宝账号  1已经绑定支付宝账号
        $where = "userID = {$userID}";
        $resinfo['account_info'] = DBManager::getMysql()->selectAll(MysqlConfig::Table_user_cash_bank, ['bank_number','real_name','account_type'],$where);
        $moneyInfo = UserModel::getInstance()->getUserInfo($userID,['money']);
        $resinfo['money'] = $moneyInfo['money'];

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT,$resinfo);

    }

    /**
     * 提现绑定银行卡或支付宝
     * @param $param
     */
    public function addCashBank($param)
    {
        $param['create_time'] = time();
        $code = $param['code'];
        unset($param['api']);
        unset($param['action']);
        if (empty($param['userID']) || empty($param['bank_number']) || empty($param['real_name']) || empty($param['code']) || empty($param['account_type'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }
        unset($param['code']);

        //获取绑定的手机号
        $arrayKeyValue = ['phone'];
        $where = "userID = {$param['userID']}";
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);

        //获取验证码信息
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($resinfo['phone'], 3);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];
        if ($phoneCode !== $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        //添加该账号之前先判定该账号是否绑定过
        $zfbres = UserCashBank::getInstance()->getCount('bank_number = "'.$param['bank_number'].'" and account_type = '.$param['account_type']);
        if(!empty($zfbres)) AppModel::returnJson(ErrorConfig::ERROR_CODE, '该账号已经被绑定过,请勿重复绑定!');

        LogHelper::printLog(self::LOG_TAG_NAME, '参数111'.json_encode($param));
        $adddate['userID'] = $param['userID'];
        $adddate['bank_number'] = $param['bank_number'];
        $adddate['opening_bank'] = !empty($param['opening_bank']) ? $param['opening_bank'] : '';
        $adddate['real_name'] = $param['real_name'];
        $adddate['create_time'] = $param['create_time'];
        $adddate['account_type'] = $param['account_type'];
        $res = UserCashBank::getInstance()->createBankDB($adddate);
        $message = $param['account_type'] == 1 ? '绑定银行卡失败' : '绑定支付宝失败';
        if(empty($res)) AppModel::returnJson(ErrorConfig::ERROR_CODE, $message.$res);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);

    }

    /**
     * 修改绑定银行卡或支付宝账户信息
     * @param $param
     */
    public function updateCashAccount($param)
    {
        $code = $param['code'];
        unset($param['api']);
        unset($param['action']);
        if (empty($param['userID']) || empty($param['bank_number']) || empty($param['real_name']) || empty($param['code'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }
        unset($param['code']);

        //获取绑定的手机号
        $arrayKeyValue = ['phone'];
        $where = "userID = {$param['userID']}";
        $resinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);

        //获取验证码信息
        $phoneCodeInfo = PhoneModel::getInstance()->getPhoneCodeInfo($resinfo['phone'], 3);
        $phoneCode = empty($phoneCodeInfo) ? '' : $phoneCodeInfo['code'];
        $phoneTime = empty($phoneCodeInfo) ? 0 : (int)$phoneCodeInfo['time'];
        if ($phoneCode !== $code) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE);
        }
        // 验证验证码时间
        if (time() - $phoneTime > self::CODE_INVALID_TIME) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BIND_PHONE_CODE_TOO);
        }

        LogHelper::printLog(self::LOG_TAG_NAME, '参数222'.json_encode($param));
        $adddate['userID'] = $param['userID'];
        $adddate['bank_number'] = $param['bank_number'];
        $adddate['opening_bank'] = !empty($param['opening_bank']) ? $param['opening_bank'] : '';
        $adddate['real_name'] = $param['real_name'];
        $adddate['account_type'] = $param['account_type'];
        $where = "userID = {$param['userID']} and account_type = {$param['account_type']}";
        $res = UserCashBank::getInstance()->updateAccount($adddate, $where);
        $message = $param['account_type'] == 1 ? '修改银行卡失败' : '修改支付宝失败';
        if(empty($res)) AppModel::returnJson(ErrorConfig::ERROR_CODE, $message);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);

    }

    /**
     * 申请提现
     * @param $param
     */
    public function applyCash($param)
    {
        if (empty($param['userID']) || empty($param['cash_account_type']) || empty($param['cash_money'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }
        //开启事务
        DBManager::getMysql()->beginTransaction();
        try{
            //判断是否已经添加过对应的账户
            if($param['cash_account_type'] == 1){  //提现到银行卡
                $arrayKeyValue = ['bank_number','opening_bank','real_name'];
                $where = "userID = {$param['userID']} and account_type = 1";
                $accountInfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_user_cash_bank, $arrayKeyValue, $where);
                if(empty($accountInfo)) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_KEEP_ADD_BANK);
                $adddata['cash_remarks'] = $accountInfo['real_name'].' -- '.$accountInfo['bank_number'].'['.$accountInfo['opening_bank'].']';
                //实际应转账金额
                $adddata['transferable_amount'] = $param['cash_money'];
                $adddata['remarks'] = '提现到银行卡';
            }elseif ($param['cash_account_type'] == 2){  //提现到支付宝
                $arrayKeyValue = ['bank_number','opening_bank','real_name'];
                $where = "userID = {$param['userID']} and account_type = 2";
                $accountInfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_user_cash_bank, $arrayKeyValue, $where);
                if(empty($accountInfo)) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_KEEP_ADD_ZFB);
                $adddata['cash_remarks'] = $accountInfo['real_name'].' -- '.$accountInfo['bank_number'];
                //查询出用户今天是否有提现到支付宝
                $start_time = strtotime(date('Y-m-d', time()));
                $is_cash_id = DBManager::getMysql()->selectRow(MysqlConfig::Table_user_cash_application, ['Id'], "create_time > {$start_time}");

                if(empty($is_cash_id)){ //今天没有提现过
                    //实际应转账金额
                    $adddata['transferable_amount'] = $param['cash_money'];
                    $adddata['remarks'] = '今天首次提现到支付宝,免手续费';
                }else{
                    //实际应转账金额
                    $adddata['transferable_amount'] = $param['cash_money'] * 0.98;
                    $adddata['remarks'] = '今天非首次提现到支付宝,需收取手续费';
                }
            }

            //获取用户名称
            /*$arrayKeyValue = ['name','money'];
            $where = "userID = {$param['userID']}";
            $userinfo = DBManager::getMysql()->selectRow(MysqlConfig::Table_userinfo, $arrayKeyValue, $where);*/

            $userID = $param['userID'];
            $needData = ['name', 'money'];
            $userinfo = UserModel::getInstance()->getUserInfo($userID, $needData);

            //判断兑换金额有没有超出携带金额
            LogHelper::printLog(self::LOG_TAG_NAME, '用户信息'.$userinfo);
            LogHelper::printLog(self::LOG_TAG_NAME, '提现金额'.$param['cash_money']);
            if(($param['cash_money'] * 100) > $userinfo['money']) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_BEYOND_MONEY);
            //兑换金额100起兑换
            if($param['cash_money'] < 100) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_LOWER_THAN_MONEY);
            //兑换账户必须留底3元
            if($userinfo['money']/100 - $param['cash_money'] < 3) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_KEEP_BOTTOM_MONEY);
            //提现金额必须是10的整数倍
            $keyword = $param['cash_money']/10;
            if(!preg_match("/^[1-9][0-9]*$/",$keyword)) AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_KEEP_BOTTOM_TEN);

            $adddata['userID'] = $param['userID'];
            $adddata['cash_account_type'] = $param['cash_account_type'];
            $adddata['cash_money'] = $param['cash_money'];
            $adddata['create_time'] = time();
            $adddata['nickname'] = $userinfo['name'];
            $adddata['cash_withdrawal'] = $param['cash_money'];
            $adddata['cash_rate'] = 0.02;

            //记录日志
            LogHelper::printLog(self::LOG_TAG_NAME, $adddata);
            //添加提现申请记录
            $res = UserCashApplication::getInstance()->addInsert($adddata);
            if(empty($res)){
                DBManager::getMysql()->rollback();
                AppModel::returnJson(ErrorConfig::ERROR_CODE, '申请提现失败');
            }

            //添加用户金币变化表的记录
            /*$moneychangeInfo['userID'] = $param['userID'];
            $moneychangeInfo['time'] = time();
            $moneychangeInfo['money'] = $userinfo['money'] - $param['cash_money']*100;
            $moneychangeInfo['changeMoney'] = -100 * $param['cash_money'];
            $moneychangeInfo['reason'] = '1022';
            $changeres = DBManager::getMysql()->insert(MysqlConfig::Table_statistics_moneychange, $moneychangeInfo);
            if (empty($changeres)) {
                DBManager::getMysql()->rollback();
                AppModel::returnJson(ErrorConfig::ERROR_CODE, '申请提现失败');
            }

            //更改用户的金币数
            $userres = DBManager::getMysql()->update(MysqlConfig::Table_userinfo, ['money' => $userinfo['money'] - $param['cash_money']*100], "userID = {$param['userID']}");
            if (empty($userres)) {
                DBManager::getMysql()->rollback();
                AppModel::returnJson(ErrorConfig::ERROR_CODE, '申请提现失败');
            }*/
            $changeFireCoin = FunctionHelper::MoneyInput($param['cash_money'], 1);

            $res = UserCashBank::getInstance()->sendMessage($param['userID'], EnumConfig::E_ResourceType['MONEY'], -$changeFireCoin, EnumConfig::E_ResourceChangeReason['GOLD_EXCHANGE']);
            if (empty($res)) {
                DBManager::getMysql()->rollback();
                AppModel::returnJson(ErrorConfig::ERROR_CODE, '申请提现失败');
            }

            DBManager::getMysql()->commit();
            AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
        }catch (Exception $e){
            LogHelper::printLog(self::LOG_TAG_NAME, '提现申请错误信息'.$e->getMessage());
            DBManager::getMysql()->rollback();
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '申请提现失败'.$e->getMessage());
        }


    }

    /**
     * 查询出用户的提现信息
     * @param $param
     */
    public function showCashInfo($param)
    {
        $userID = (int)$param['userID'];
        if (empty($param['userID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }

        //获取金币
        $arrayKeyValue = ['userID','create_time','cash_money','cash_account_type','cash_status'];
        $where = "userID = {$userID}";
        $resinfo = DBManager::getMysql()->selectAll(MysqlConfig::Table_user_cash_application, $arrayKeyValue, $where);

        foreach ($resinfo as &$value){
            $value['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            $value['cash_account_type_text'] = $value['cash_account_type'] == 1 ? '支付宝兑换' : '银行卡兑换';
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT,$resinfo);

    }

}