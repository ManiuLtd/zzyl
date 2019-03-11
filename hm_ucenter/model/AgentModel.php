<?php
namespace model;
use config\AgentConfig;
use config\EnumConfig;
use config\MysqlConfig;
use config\GameRedisConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use manager\DBManager;
use manager\RedisManager;

/**
 * 代理模块
 * Class AgentModel
 */
class AgentModel extends AppModel
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

    private function __clone()
    {
    }

    /**
     * 获取绑定代理奖励数据
     * @param $userID
     * @param array $keyArray
     * @return mixed
     */
    public function getBindAgentReward($userID, $keyArray = [])
    {
        $where = "userID='{$userID}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_bind_reward, $keyArray, $where);
        $intKeyArray = [
            'userID',
            'money',
            'jewels',
            'time',
        ];
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 添加奖励记录
     * @param $userID
     * @param int $money
     * @param int $jewels
     * @return mixed
     */
    public function addBindAgentReward($userID, $money = 0, $jewels = 0)
    {
        $arrayDataValue = [
            'userID' => $userID,
            'money' => $money,
            'jewels' => $jewels,
            'time' => time(),
        ];
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_agent_bind_reward, $arrayDataValue);
    }

    /**
     * 获取代理成员信息
     * @param $agentID
     * @param array $keyArray
     * @return mixed
     */
    public function getAgentMemberByAgentID($agentID, $keyArray = [])
    {
        $where = "agentid='{$agentID}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $keyArray, $where);
        $intKeyArray = array(
            'id',
            'userid',
            'agent_level',
            'register_time',
            'login_count',
            'last_login_time',
            'balance',
            'disabled',
            'under_money',
            'not_under_money',
            'history_pos_money',
            'status',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 获取代理绑定信息
     * @param $userID
     * @param $keyArray
     * @return mixed
     */
    public function getAgentBindByUserID($userID, $keyArray = [])
    {
        $where = "userID='{$userID}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_bind, $keyArray, $where);
        $intKeyArray = array(
            'id',
            'userID',
            'bind_time',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 解除绑定 不是代理 是代理
     * @param $userID
     * @return bool|mixed
     */
    public function delAgentBindByUserID($userID)
    {
        $agentMember = $this->getAgentMemberByUserID($userID);
        if (!empty($agentMember)) {
            //移除代理表的上级代理信息
            $where = "userid={$userID}";
            $changeData = [
                'superior_username' => '',
                'superior_agentid' => '',
            ];
            $result = DBManager::getMysql()->update(MysqlConfig::Table_web_agent_member, $changeData, $where);
            if (empty($result)) {
                return false;
            }
        }
        $where = "userID={$userID}";
        return DBManager::getMysql()->delete(MysqlConfig::Table_web_agent_bind, $where);
    }

    /**
     * 获得的我的代理信息
     * @param $userID
     * @param array $keyArray
     * @return mixed
     */
    public function getAgentMemberByUserID($userID, $keyArray = [])
    {
        $where = "userid='{$userID}'";
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_agent_member, $keyArray, $where);
        $intKeyArray = array(
            'id',
            'userid',
            'agent_level',
            'register_time',
            'login_count',
            'last_login_time',
            'balance',
            'disabled',
            'under_money',
            'not_under_money',
            'history_pos_money',
            'commission_rate',
            'status',
        );
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 获取下级代理成员信息列表
     * @param $agentID
     * @param array $keyArray
     * @return mixed
     */
    public function getAgentInfoList($agentID, $keyArray = [])
    {
        $where = "superior_agentid='{$agentID}'";
        return DBManager::getMysql()->selectAll(MysqlConfig::Table_web_agent_member, $keyArray, $where);
    }

    /**
     * 获取我手下所有代理ID
     * @param $agentID
     * @param int $level_end 默认0取全部 | 大于0 表示取到多少级
     * @param array $memberList
     * @param int $level_start 默认0从第1级开始取
     * @return array
     */
    public function getDownAgentID($agentID, $level_end = 0, &$memberList = [], $level_start = 0)
    {
        $agent_list = $this->getAgentInfoList($agentID, ['agentid']);
        $level_start++;
        foreach ($agent_list as $key => $agent) {
            $agent_id = $agent['agentid'];
            $memberList[] = $agent_id;
            if ($level_end == 0 || $level_start < $level_end) {
                $this->getDownAgentID($agent_id, $level_end, $memberList, $level_start);
            }
        }
        return $memberList;
    }

    /**
     * 获取我手下所有代理信息
     * @param $agentID
     * @param int $level_end 默认0取全部 | 大于0 表示取到多少级
     * @param array $memberList
     * @param int $level_start 默认0从第1级开始取
     * @return array
     */
    public function getDownAgentInfo($agentID, $level_end = 0, &$memberList = [], $level_start = 0)
    {
        $agent_list = $this->getAgentInfoList($agentID);
        $level_start++;
        foreach ($agent_list as $key => $agent) {
            $agent_id = $agent['agentid'];
            $agent['level'] = $level_start;
            $memberList[] = $agent;
            if ($level_end == 0 || $level_start < $level_end) {
                $this->getDownAgentInfo($agent_id, $level_end, $memberList, $level_start);
            }
        }
        return $memberList;
    }

    /**
     * 获取我手下所有代理ID 按等级分
     * @param $agentID
     * @param int $level_end 默认0取全部 | 大于0 表示取到多少级
     * @param array $memberList
     * @param int $level_start 默认0从第1级开始取
     * @return array
     */
    public function getDownAgentIDByLevel($agentID, $level_end = 0, &$memberList = [], $level_start = 0)
    {
        $agent_list = $this->getAgentInfoList($agentID, ['agentid']);
        $level_start++;
        foreach ($agent_list as $key => $agent) {
            $agent_id = $agent['agentid'];
            $memberList[$level_start][] = $agent_id;
            if ($level_end == 0 || $level_start < $level_end) {
                $this->getDownAgentIDByLevel($agent_id, $level_end, $memberList, $level_start);
            }
        }
        return $memberList;
    }

    /**
     * 获取我手下所有代理信息 按等级分
     * @param $agentID
     * @param int $level_end 默认0取全部 | 大于0 表示取到多少级
     * @param array $memberList
     * @param int $level_start 默认0从第1级开始取
     * @return array
     */
    public function getDownAgentInfoByLevel($agentID, $level_end = 0, &$memberList = [], $level_start = 0)
    {
        $agent_list = $this->getAgentInfoList($agentID);
        $level_start++;
        foreach ($agent_list as $key => $agent) {
            $agent_id = $agent['agentid'];
            $agent['level'] = $level_start;
            $memberList[$level_start][] = $agent;
            if ($level_end == 0 || $level_start < $level_end) {
                $this->getDownAgentInfoByLevel($agent_id, $level_end, $memberList, $level_start);
            }
        }
        return $memberList;
    }

    /**
     * 获取上级代理信息 包括自己
     * @param $agent
     * @param int $level_end 默认0取全部 | 大于0 表示取到多少级
     * @param array $memberList
     * @param int $level_start
     * @return array
     */
    public function getUpAgentList($agent, $level_end = 0, &$memberList = [], $level_start = 0)
    {
        if (empty($agent)) {
            return $memberList;
        }
        //第一级是自己
        if ($level_start == 0) {
            $level_start++;
            $memberList[$level_start] = $agent;
        }
        if (!empty($agent['superior_agentid'])) {
            $super_agent = $this->getAgentMemberByAgentID($agent['superior_agentid']);
            if (!empty($super_agent)) {
                $level_start++;
                $memberList[$level_start] = $super_agent;
                if ($level_end == 0 || $level_start <= $level_end) {
                    $this->getUpAgentList($super_agent, $level_end, $memberList, $level_start);
                }
            }
        }
        return $memberList;
    }

    /**
     * 更新代理信息
     * @param $agentID
     * @param $agentMember
     * @return mixed
     */
    public function updateAgentMember($agentID, $agentMember)
    {
        $where = "agentid = '{$agentID}'";
        return DBManager::getMysql()->update(MysqlConfig::Table_web_agent_member, $agentMember, $where);
    }

    /**
     * 绑定代理
     * @param $addData
     * @return bool|mysqli_result
     */
    public function addBindAgent($addData)
    {
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_agent_bind, $addData);
    }

    /**
     * 获取代理分成配置
     * @return array
     */
    public function getAgentConfig()
    {
        $sql = "select * from " . MysqlConfig::Table_web_agent_config;
        $result = DBManager::getMysql()->queryAll($sql);
//        DBManager::getMysql()->
        $config = [];
        foreach ($result as $k => $v) {
            $config[$v['key']] = $v['value'];
        }
        return $config;
    }

    /**
     * 获取用户上级代理
     * @param $userID
     * @param int $level
     * @return array
     */
    public function getUserUpAgentList($userID, $level = 0)
    {
        $agent_list = [];
        //我是否代理
        $agentInfo = $this->getAgentMemberByUserID($userID);
        if (empty($agentInfo)) {
            //是否绑定
            $bindInfo = $this->getAgentBindByUserID($userID);
            if (!empty($bindInfo)) {
                $agentInfo = $this->getAgentMemberByAgentID($bindInfo['agentID']);
                if (!empty($agentInfo)) {
                    //获取上级代理信息
                    $agent_list = $this->getUpAgentList($agentInfo, $level, $agent_list);
                }
            }
        } else {
            //获取上级代理信息
            $agent_list = $this->getUpAgentList($agentInfo, $level, $agent_list);
        }
        return $agent_list;
    }

    /**
     * 根据订单信息计算代理分佣
     * @param $order
     */
    public function rechargeCommission($order)
    {
        return true;
        $order_sn = $order['order_sn'];
        $foreign_key_id = $order['id'];
        //查询订单号是否存在分佣
        $where = "recharge_order_sn = {$order_sn} and commission_type = " . AgentConfig::COMMISSION_TYPE['recharge'];
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_recharge_commission, [], $where);
        if (!empty($result)) {
            LogHelper::printWarning("订单号order_sn={$order_sn}已经存在分佣记录");
            return;
        }
        $config = $this->getAgentConfig();
        $commission_level = $config['recharge_commission_level'];
        $userID = $order['userID'];
        $name = $order['name'];
        $resourceType = $order['buyType'];
        $amount = $order['consumeNum'];
        $buyNum = $order['buyNum'];

        if ($commission_level == 0) {
            $_desc = '无限级代理返佣';
        } else {
            $_desc = $commission_level . '级代理返佣';
        }

        //充值统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeCount');
        UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeMoney', $amount);
        if ($resourceType == EnumConfig::E_ResourceType['MONEY']) {
            UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeGetMoney', $buyNum);
        } elseif ($resourceType == EnumConfig::E_ResourceType['JEWELS']) {
            UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeGetJewels', $buyNum);
        }

        $agent_list = $this->getUserUpAgentList($userID, $commission_level);

        foreach ($agent_list as $level => $agent) {
            //金额小于等于0后终止循环
            if ($amount <= 0) {
                break;
            }
            $ratio = $config['agent_level_ratio_' . $agent['agent_level']];
            //没有配置分成 跳过
            if (empty($ratio)) {
                continue;
            }
            $add_amount = $amount * $ratio / 100;
            if ($level == 1) {
                $bill_amount = $amount;
                $bill_commission = $add_amount;
                $under_amount = 0;
                $under_commission = 0;
                $changeData = array(
                    'balance' => $agent['balance'] + $add_amount,
                    'under_money' => $agent['under_money'] + $add_amount,
                );
            } else {
                $bill_amount = 0;
                $bill_commission = 0;
                $under_amount = $amount;
                $under_commission = $add_amount;
                $changeData = array(
                    'balance' => $agent['balance'] + $add_amount,
                    'not_under_money' => $agent['not_under_money'] + $add_amount,
                );
            }
            //更新代理收益
            $this->updateAgentMember($agent['agentid'], $changeData);
            //添加账单
            $this->addBillDetail($agent['username'], $agent['agentid'], $agent['userid'], $agent['agent_level'], $agent['balance'], $add_amount, $changeData['balance'], $_desc, $userID, $name, $bill_amount, $bill_commission, $under_amount, $under_commission);
            //添加分佣记录
            $this->addCommissionRecord($foreign_key_id, $userID, $name, $order_sn, $amount, $agent['agent_level'], $agent['username'], $add_amount, $agent['userid'], $level, $resourceType, $buyNum, AgentConfig::COMMISSION_TYPE['recharge']);
            //在剩下的钱继续返
//            $amount -= $add_amount;
        }
    }

    protected function beforeLoopBattleRecord($recordList) {
        return true;
    }

    protected function afterLoopBattleRecord($recordList) {
//        $this->setBattleRecordStatus($recordList);
    }

//    /**
//     * @param $recordList修改抽水记录状态
//     */
//    protected function setBattleRecordStatus($recordList) {
//        foreach ($recordList as $k => $v) {
//            $strWhere = 'id = ' . $v['id'];
//            $res_update = DBManager::getMysql()->update(MysqlConfig::Table_statistics_moneychange, ['status' => 1], $strWhere);
//        }
//        return true;
//    }
    /**
     * @param $recordList修改抽水记录状态
     */
    protected function setBattleRecordStatusOk($recordID) {
//            $strWhere = $this->makeWhere(['id' => $recordID]);
        $strWhere = " id = $recordID ";
        return DBManager::getMysql()->update(MysqlConfig::Table_statistics_moneychange, ['status' => 1], $strWhere);
    }

    /**
     * 抽水记录映射订单
     */
    public function mapBattleRecord2Order() {
        $battleRecordList = DBManager::getMysql()->queryAll('select * from ' . MysqlConfig::Table_statistics_moneychange . ' where reason = 8 and status = 0 limit 200');
        $mapOrderList = [];
        foreach ($battleRecordList as $k => $v) {
            $mapOrderList[] = [
                'order_sn' => '',
                'id' => $v['id'],
                'userID' => $v['userID'],//赢家id
                'name' => 'test name',
                'buyType' => 100,
                'consumeNum' => abs($v['changeMoney']),
                'buyNum' => abs($v['changeMoney']) * 1,
            ];
        }
        return $mapOrderList;
    }

    public function isExistsUpAgent($userID) {
        $where = $this->makeWhere(['userID' => $userID]);
        $res = DBManager::getMysql()->queryRow('select userID from ' . MysqlConfig::Table_web_agent_bind . $where . ' limit 1');
        LogHelper::printDebug(var_export($res, true));
        return $res;
    }

    /**
     * 遍历抽水记录
     */
    public function loopBattleRecord() {
        //获取抽水记录集
        $recordList = $this->mapBattleRecord2Order();
        $this->beforeLoopBattleRecord($recordList);
        foreach ($recordList as $k => $v) {
            DBManager::getMysql()->beginTransaction();
            $res = $this->battleCommission($v);
            if (!$res) {
                DBManager::getMysql()->rollback();
            }
            $res = $this->setBattleRecordStatusOk($v['id']);
            if (!$res) {
                DBManager::getMysql()->rollback();
            }
            DBManager::getMysql()->commit();
        }
//        $this->afterLoopBattleRecord($recordList);
    }

    /**
     * 根据订单信息计算代理分佣
     * @param $order
     */
    public function battleCommission($order)
    {
        $foreign_key_id = $order['id'];
        $order_sn = $order['order_sn'];
        //查询分佣记录是否存在分佣
        $where = "foreign_key_id = {$foreign_key_id} and commission_type = " . AgentConfig::COMMISSION_TYPE['battle'];
        $result = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_recharge_commission, [], $where);
        if (!empty($result)) {
            LogHelper::printWarning("抽水记录id={$foreign_key_id}已经存在分佣记录");
            return;
        }
        $config = $this->getAgentConfig();
        $commission_level = $config['recharge_commission_level'];
        $userID = $order['userID'];
        $name = $order['name'];
        $resourceType = $order['buyType'];
        $amount = $order['consumeNum'];
        $buyNum = $order['buyNum'];
//        echo "<br>\n";
//        var_dump($order);
//        echo "<br>\n";

        if ($commission_level == 0) {
            $_desc = '无限级代理返佣';
        } else {
            $_desc = $commission_level . '级代理返佣';
        }

        //充值统计信息
//        UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeCount');
//        UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeMoney', $amount);
//        if ($resourceType == EnumConfig::E_ResourceType['MONEY']) {
//            UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeGetMoney', $buyNum);
//        } elseif ($resourceType == EnumConfig::E_ResourceType['JEWELS']) {
//            UserModel::getInstance()->addWebUserInfoValue($userID, 'rechargeGetJewels', $buyNum);
//        }

        $agent_list = $this->getUserUpAgentList($userID, $commission_level);
        array_push($agent_list, $this->getMapAgent2Operator());
        echo '------------';
        var_export($agent_list);
//        $i = 0;//计数器
        $amountOfAgents = 0;//代理累计分佣
        foreach ($agent_list as $level => $agent) {
            //金额小于等于0后终止循环
            if ($amount <= 0) {
                break;
            }
//            $ratio = $config['agent_level_ratio_' . $agent['agent_level']];
            //如果是最近一层级，也就是赢家本身，采用本身的分成比率，否则采用比率差
            if (1 == $level) {
                $ratio = $agent['commission_rate'];
            } else {
                //当前用户 - 下级用户,佣差
//                echo '---____';
                echo $level;
                $ratio = $agent['commission_rate'] - $agent_list[$level - 1]['commission_rate'];
            }

            // $ratio = $config['agent_level_ratio_' . $agent['agent_level']];
            //没有配置分成 跳过
            if (empty($ratio)) {
                continue;
            }
            $add_amount = floor($amount * $ratio / 100);
            if ($level == 1) {
                $bill_amount = $amount;
                $bill_commission = $add_amount;
                $under_amount = 0;
                $under_commission = 0;
                $changeData = array(
                    'balance' => $agent['balance'] + $add_amount,
                    'under_money' => $agent['under_money'] + $add_amount,
                );
            } else {
                $bill_amount = 0;
                $bill_commission = 0;
                $under_amount = $amount;
                $under_commission = $add_amount;
                $changeData = array(
                    'balance' => $agent['balance'] + $add_amount,
                    'not_under_money' => $agent['not_under_money'] + $add_amount,
                );
            }
            if (!isset($agent['get_amount_user_type']) && EnumConfig::E_CommissionUserType['OPERATOR'] != $agent['get_amount_user_type']) {
                $amountOfAgents += $add_amount;
                //更新代理收益
                $res = $this->updateAgentMember($agent['agentid'], $changeData);
                if (!$res) {
                    return false;
                }
                //添加账单
//                $this->addBillDetail($agent['username'], $agent['agentid'], $agent['userid'], $agent['agent_level'], $agent['balance'], $add_amount, $changeData['balance'], $_desc, $userID, $name, $bill_amount, $bill_commission, $under_amount, $under_commission);
                //添加分佣记录
            } else {
                $add_amount = $amount - $amountOfAgents;
            }
            $res = $this->addCommissionRecord($foreign_key_id, $userID, $name, $order_sn, $amount, $agent['agent_level'], $agent['username'], $add_amount, $agent['userid'], $level, $resourceType, $buyNum, AgentConfig::COMMISSION_TYPE['battle'], isset($agent['get_amount_user_type']) ? $agent['get_amount_user_type'] : EnumConfig::E_CommissionUserType['AGENT']);
            if (!$res) {
                return false;
            }
            //在剩下的钱继续返
//            $amount -= $add_amount;
        }
        return true;
    }

    /**
     * 虚拟平台代理用户
     * @return array
     */
    public function getMapAgent2Operator() {
        //要修改为mysql动态获取字段
        return array (
            'id' => 6,
            'username' => '平台运营商',
            'userid' => 0,
            'password' => '',
            'agent_level' => 0,
            'superior_agentid' => '',
            'agentid' => '953718',
            'superior_username' => '',
            'register_time' => 1543639387,
            'login_count' => 0,
            'last_login_time' => 0,
            'last_login_ip' => '',
            'last_login_address' => '',
            'wechat' => '',
            'bankcard' => '',
            'balance' => 0,
            'disabled' => 0,
            'under_money' => 0,
            'not_under_money' => 0,
            'email' => '',
            'history_pos_money' => 0,
            'status' => 0,
            'wx_token' => '',
            'rel_name' => '',
            'media_id' => '',
            'is_franchisee' => '1',
            'commission_rate' => '100',
            'get_amount_user_type' => 2,
        );
    }

    /**
     * 添加账单
     * @param $username
     * @param $agent_level
     * @param $front_balance
     * @param $handle_money
     * @param $after_balance
     * @param $_desc
     * @param $make_userid
     * @param $make_name
     * @param $amount
     * @param $commission
     * @param $under_amount
     * @param $under_commission
     * @return mixed
     */
    function addBillDetail($username, $agentid, $userid, $agent_level, $front_balance, $handle_money, $after_balance, $_desc, $make_userid, $make_name, $amount, $commission, $under_amount, $under_commission)
    {
        $arrayDataValue = array(
            'username' => $username,
            'agentid' => $agentid,
            'userid' => $userid,
            'agent_level' => $agent_level,
            'front_balance' => $front_balance,
            'handle_money' => $handle_money,
            'after_balance' => $after_balance,
            '_desc' => $_desc,
            'make_time' => time(),
            'make_userid' => $make_userid,
            'make_name' => $make_name,
            'amount' => $amount,
            'commission' => $commission,
            'under_amount' => $under_amount,
            'under_commission' => $under_commission,
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_bill_detail, $arrayDataValue);
    }

    /**
     * 添加分佣记录
     * @param $recharge_userid
     * @param $recharge_name
     * @param $recharge_order_sn
     * @param $recharge_amount
     * @param $agent_level
     * @param $agent_username
     * @param $agent_commission
     * @param $agent_userid
     * @param $level
     * @param $buy_type
     * @param $buy_num
     * @return mixed
     */
    function addCommissionRecord($foreign_key_id, $recharge_userid, $recharge_name, $recharge_order_sn, $recharge_amount, $agent_level, $agent_username, $agent_commission, $agent_userid, $level, $buy_type, $buy_num, $commission_type, $get_amount_user_type = EnumConfig::E_CommissionUserType['AGENT'])
    {
        $arrayDataValue = array(
            'foreign_key_id' => $foreign_key_id,
            'recharge_userid' => $recharge_userid,
            'recharge_name' => $recharge_name,
            'recharge_order_sn' => $recharge_order_sn,
            'recharge_amount' => $recharge_amount,
            'agent_level' => $agent_level,
            'agent_username' => $agent_username,
            'agent_commission' => $agent_commission,
            'agent_userid' => $agent_userid,
            'level' => $level,
            'buy_type' => $buy_type,
            'buy_num' => $buy_num,
            'commission_type' => $commission_type,
            'get_amount_user_type' => $get_amount_user_type,
            'time' => time(),
        );
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_recharge_commission, $arrayDataValue);
    }

    /**
     *玩家成为代理，玩家就是绑定了代理，但自身还不是代理的那个
     * @return boolean
     */
    function setUser2Agent($arrUserInfo,$agentMemberID) {
        $data['username'] = $arrUserInfo['username'];//phone
        $data['userid'] = $arrUserInfo['userid'];//
        $data['commission_rate'] = $arrUserInfo['commission_rate'];
        $data['agent_level'] = 1;
        $data['is_franchisee'] = 0;
        $data['disabled'] = 0;
        $password = '123456';
        $data['wechat'] = '';
        $data['bankcard'] = '';
        $data['rel_name'] = '';
        // if (!preg_match("/^1[34578]{1}\d{9}$/", $data['username'])) {
        //     $this->error('请输入正确的代理账号(手机号)');
        // }
        // if (!is_numeric($data['commission_rate']) || 0 >= $data['commission_rate'] || $data['commission_rate'] >= 100) {
        //     $this->error('比率必须为数字，且0到100之间');
        // }


        // if (!$data['wechat'] || !$data['bankcard'] || !$data['real_name']) {
        //     $this->error('微信和银行卡和姓名必须添加');
        // }
        // if (M('agent_member')->where(['wechat' => $data['wechat']])->find()) {
        //     $this->error('该微信已经被使用');
        // }
        // if (M('agent_member')->where(['userid' => $data['bankcard']])->find()) {
        //     $this->error('该银行卡账号已经被使用');
        // }
        $data['agent_level'] = 1;
        //验证
        // $arr = [1, 2, 3];
        // if (!in_array($data['agent_level'], $arr)) {
        //     $this->error('请选择代理级别');
        // }
        // $user = UserModel::getInstance()->getUserInfo($agentMemberID);
        //验证完成根据游戏ID获取游戏昵称以及上级代理的登录账号和邀请码
        $res = $this->getAgentMemberByUserID($agentMemberID, ['agentid', 'username']);
//        var_dump($res);exit;
        if($data['userid'] != $res['agentid']){
            $data['superior_agentid'] = $res['agentid'];
            $data['superior_username'] = $res['username'];
        }
        $data['register_time'] = time();
        $data['password'] = md5($password);
        // $data['agentid'] = $this->get_max_agentid()+1;
        $data['agentid'] = $data['userid'];//$this->get_max_agentid() + 1;
        //获取最大的agentid
        //
        $res = DBManager::getMysql()->insert(MysqlConfig::Table_web_agent_member, $data);
        if ($res) {
            //redis 添加集合
            RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_web_agentmember, $data['userid']);
//            operation_record(UID, '添加用户名为' . $data['username'] . '的代理');
        }
        return true;
    }

    function get_max_agentid()
    {
        // $max_agentid = M('agent_member')->max('agentid');
        // if(!$max_agentid){
        //     $max_agentid = 10000;
        // }
        $agentid = rand(mt_rand(1, 9) . mt_rand(0, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9), mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(0, 9));
        return $agentid;
    }

    //获取上级代理的登录账号以及邀请码
    function get_superior($userID)
    {
        // $agentID = M('agent_bind')->where(['userID' => $userid])->getField('agentID');
        $agentID = DBManager::getMysql()->queryRow('select agentID from ' . MysqlConfig::Table_web_agent_bind . ' where userID = ' . $userID . ' limit 1');
        $res = [];
        if (!$agentID) {
            $res['superior_agentid'] = '';
            $res['superior_username'] = '';
        } else {
            $res['superior_agentid'] = $agentID['agentID'];
            // $username = M('agent_member')->where(['agentid' => $agentID])->getField('username');
            $username = DBManager::getMysql()->queryRow('select username from ' . MysqlConfig::Table_web_agent_bind . ' where 11111 and userID = ' . $userID . ' limit 1');
            $res['superior_username'] = $username['username'];
        }
        return $res;
    }

    /**
     * 根据userid获取上级信息
     */
    function getUpAgentInfoByUserID($userID, $needKeyArray = ['*']) {
        $fields = implode(',', $needKeyArray);
        $arrSuperiorAgentid = DBManager::getMysql()->queryRow('select agentID from ' . MysqlConfig::Table_web_agent_bind . ' where userID = ' . $userID . ' and 11111 ' . ' limit 1');
        $upAgent = null;
        if (isset($arrSuperiorAgentid['agentID']) && $arrSuperiorAgentid['agentID']) {
            $upAgent = DBManager::getMysql()->queryAll('select ' . $fields . ' from ' . MysqlConfig::Table_web_agent_member . ' where agentid = ' . $arrSuperiorAgentid['agentID'] . ' limit 1');
        }
        return $upAgent ? $upAgent[0] : $upAgent;
    }

    /**
     * 修改下级代理分佣比率
     */
    function updateLowerCommissionRate($targetUserID, $commissionRate) {
        return DBManager::getMysql()->update(MysqlConfig::Table_web_agent_member, ['commission_rate' => $commissionRate], 'userID=' . $targetUserID);
    }
}
