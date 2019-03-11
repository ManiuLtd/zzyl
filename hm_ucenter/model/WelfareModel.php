<?php
namespace model;
use config\EnumConfig;
use config\MysqlConfig;
use config\RedisConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 福利模块 签到 转盘 救济金
 * Class WelfareModel
 */
class WelfareModel extends AppModel
{
    const SIGN_BEGIN_DAY = 1;

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
     * @return array|bool
     */
    public function getSignConfig()
    {
//        $signConfig = ConfigModel::getInstance()->getTableCache(RedisConfig::Hash_web_sign_config);
//        if (!$signConfig) {
            $sql = "select * from " . MysqlConfig::Table_web_sign_config;
            $signConfig = DBManager::getMysql()->queryAll($sql);
//            $res = ConfigModel::getInstance()->setTableCache(RedisConfig::Hash_web_sign_config, $signConfig);
//            if (!$res) {
//                LogHelper::printError('set cache error');
//            }
//        }
        $intKeyArray = array(
            'id',
            'dateNum',
            'num',
            'prizeType',
            'picNum',
        );
        FunctionHelper::arrayValueToInt($signConfig, $intKeyArray);
        return $signConfig;
    }

    /**
     * 获取签到周期
     * @return int
     */
    public function getSignCycle()
    {
        $sql = "select count(*) count from " . MysqlConfig::Table_web_sign_config;
        $result = DBManager::getMysql()->queryRow($sql);
        return (int)$result['count'];
    }

    /**
     * 获取用户签到配置
     * @param $userID
     * @return array|bool
     */
    public function getUserSignConfig($userID)
    {
        //取一条记录
        $sql = "select * from " . MysqlConfig::Table_web_sign_record
            . " where userID={$userID} order by signTime desc";
        $record = DBManager::getMysql()->queryRow($sql);

        $signConfig = $this->getSignConfig();
        $dateNum = (int)$record['dateNum'];
        $signDay = $this->getSignDay($userID);
        $canSign = $this->getIsUseSign($userID);

        foreach ($signConfig as $k => $v) {
            //是否签到过了
            $signConfig[$k]['isSign'] = false;
            //能否签到
            $signConfig[$k]['canSign'] = false;
            if ($canSign) {
                if ($signDay == $signConfig[$k]['dateNum']) {
                    $signConfig[$k]['canSign'] = true;
                }
                if ($signConfig[$k]['dateNum'] < $signDay) {
                    $signConfig[$k]['isSign'] = true;
                }
            } else {
                if ($signConfig[$k]['dateNum'] <= $dateNum) {
                    $signConfig[$k]['isSign'] = true;
                }
            }
        }
        return $signConfig;
    }


    /**
     * 获取从第几天签到
     * @param $userID
     * @return int
     */
    public function getSignDay($userID)
    {
        /*
            //获取今日开始时间戳和结束时间戳
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            //获取昨日起始时间戳和结束时间戳
            $beginYesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $endYesterday = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            //获取上周起始时间戳和结束时间戳
            $beginLastweek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $endLastweek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            //获取本月起始时间戳和结束时间戳
            $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
            $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        */
        //查询上一次的签到记录
        $sql = "select * from " . MysqlConfig::Table_web_sign_record
            . " where userID=" . $userID . " order by signTime desc";
        $record = DBManager::getMysql()->queryRow($sql);

        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;

        $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;

        //没有签到记录 从第一天开始签到
        if (empty($record)) {
        } elseif ($record['signTime'] > $beginToday && $record['signTime'] < $endToday) {
            //今天签到过
            return $record['dateNum'];
        } elseif ($record['signTime'] > $beginYesterday && $record['signTime'] < $endYesterday) {
            //昨天签到过 +1
            $signDay = $record['dateNum'] + 1;
            //获取签到周期 超过从第一天开始签到
            $signCycle = $this->getSignCycle();
            $signDay = $signDay > $signCycle ? self::SIGN_BEGIN_DAY : $signDay;
            return $signDay;
        }
        return self::SIGN_BEGIN_DAY;
    }

    /**
     * 获取某天的签到奖励
     * @param $dataNum
     * @return array|mixed
     */
    public function getSignReward($dataNum)
    {
        $signConfig = $this->getSignConfig();
        foreach ($signConfig as $config) {
            if ($config['dateNum'] == $dataNum) {
                return $config;
            }
        }
        return [];
    }

    /**
     * 今天是否可以签到
     * @param $userID
     * @return bool
     */
    public function getIsUseSign($userID)
    {
        $sql = "SELECT * FROM " . MysqlConfig::Table_web_sign_record
            . " WHERE userID={$userID} order by signTime desc";
        $result = DBManager::getMysql()->queryRow($sql);
        //没有数据肯定可以签到
        if (empty($result)) {
            return true;
        }
        //如果上一次签到时间在今天内则不能再次签到
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));//今天开始时间戳
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;//今天结束时间戳
        //上一次抽奖时间在今天所以不能抽奖
        if ($result['signTime'] > $beginToday && $result['signTime'] < $endToday) {
            return false;
        }
        return true;
    }


    /**
     * 添加签到记录
     * @param $userID
     * @param $userName
     * @param $prizeType
     * @param $prize
     * @param $num
     * @param $dataNum
     * @return bool|mysqli_result
     */
    public function addSignRecord($userID, $userName, $prizeType, $prize, $num, $dataNum)
    {
        $arrayData = array(
            "prizeType" => $prizeType,
            "prize" => $prize,
            "num" => $num,
            "dateNum" => $dataNum,
            "signTime" => time(),
            "userID" => $userID,
            "username" => $userName,
            'total_get_jewels' => 0,
            'total_get_money' => 0,
            'total_count' => 0,
        );
        //统计次数
        $where = "userID={$userID} order by signTime desc";
        $arrayKeyValue = ['total_count', 'total_get_jewels', 'total_get_money'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_sign_record, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_get_jewels'] = $data['total_get_jewels'];
            $arrayData['total_get_money'] = $data['total_get_money'];
            $arrayData['total_count'] = $data['total_count'];
        }
        //签到统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'signCount');
        $arrayData['total_count'] += 1;
        if ($prizeType == EnumConfig::E_ResourceType['MONEY']) {
            $arrayData['total_get_money'] += $num;
            UserModel::getInstance()->addWebUserInfoValue($userID, 'signGetMoney', $num);
        } elseif ($prizeType == EnumConfig::E_ResourceType['JEWELS']) {
            UserModel::getInstance()->addWebUserInfoValue($userID, 'signGetJewels', $num);
            $arrayData['total_get_jewels'] += $num;
        }
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_sign_record, $arrayData);
    }

    /**
     * 获取转盘抽奖配置
     * @return array|bool
     */
    public function getTurntableConfig()
    {
        $turntableConfig = ConfigModel::getInstance()->getTableCache(RedisConfig::Hash_web_turntable_config);
        if (!$turntableConfig) {
            $sql = 'select * from ' . MysqlConfig::Table_web_turntable_config;
            $turntableConfig = DBManager::getMysql()->queryAll($sql);
            $res = ConfigModel::getInstance()->setTableCache(RedisConfig::Hash_web_turntable_config, $turntableConfig);
            if (!$res) {
                LogHelper::printError('set cache error');
            }
        }
        $intKeyArray = array(
            'id',
            'num',
            'prizeType',
        );
        FunctionHelper::arrayValueToInt($turntableConfig, $intKeyArray);
        return $turntableConfig;
    }

    /**
     * 获取是否可以转盘抽奖
     * @param $userID
     * @return bool
     */
    public function getIsUseTurntable($userID)
    {
        $sql = "SELECT * FROM " . MysqlConfig::Table_web_turntable_record
            . " WHERE userID={$userID} ORDER BY turntableTime DESC";
        $result = DBManager::getMysql()->queryRow($sql);
        //为空说明是第一次抽奖
        if (empty($result)) {
            return true;
        }
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));//今天开始时间戳
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;//今天结束时间戳
        //上一次抽奖时间在今天所以不能抽奖
        if ($result['turntableTime'] > $beginToday && $result['turntableTime'] < $endToday) {
            return false;
        }
        return true;
    }

    /**
     * 随机转盘奖励
     * @param int $userID
     * @return array|mixed
     */
    public function randomTurntableReward($userID = 0)
    {
        $turntableConfig = $this->getTurntableConfig();
        $randomArray = [];
        $rewardArray = [];
        foreach ($turntableConfig as $config) {

            $randomArray[$config['id']] = (float)$config['chance'] * 100;
            $rewardArray[$config['id']] = $config;
        }
        //机会从小到大排序
        asort($randomArray);
        $sum = array_sum($randomArray);
        $randNum = mt_rand(1, $sum);
        //默认奖励为最后一个 几率最大
        $randomKeys = array_keys($randomArray);
        $defaultRewardID = end($randomKeys);
        $reward = $rewardArray[$defaultRewardID];
        foreach ($randomArray as $id => $chance) {
            if ($randNum <= $chance) {
                $reward = $rewardArray[$id];
                break;
            }
        }
        return $reward;
    }

    /**
     * 添加转盘抽奖记录
     * @param $userID
     * @param $userName
     * @param $prizeType
     * @param $prize
     * @param $num
     * @return bool|mysqli_result
     */
    public function addTurntableRecord($userID, $userName, $prizeType, $prize, $num)
    {
        $arrayData = array(
            "userID" => $userID,
            "turntableTime" => time(),
            "num" => $num,
            "prizeType" => $prizeType,
            "prize" => $prize,
            "username" => $userName,
            'total_get_jewels' => 0,
            'total_get_money' => 0,
            'total_count' => 0,
            'total_reward_count' => 0,
        );

        //统计次数
        $where = "userID={$userID} order by turntableTime desc";
        $arrayKeyValue = ['total_count', 'total_get_jewels', 'total_get_money','total_reward_count'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_turntable_record, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_get_jewels'] = $data['total_get_jewels'];
            $arrayData['total_get_money'] = $data['total_get_money'];
            $arrayData['total_count'] = $data['total_count'];
            $arrayData['total_reward_count'] = $data['total_reward_count'];
        }
        //转盘抽奖统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID, 'turntableCount');
        $arrayData['total_count'] += 1;
        if ($num > 0) {
            if ($prizeType == EnumConfig::E_ResourceType['MONEY']) {
                $arrayData['total_get_money'] += $num;
                UserModel::getInstance()->addWebUserInfoValue($userID, 'turntableGetMoney', $num);
            } elseif ($prizeType == EnumConfig::E_ResourceType['JEWELS']) {
                $arrayData['total_get_jewels'] += $num;
                UserModel::getInstance()->addWebUserInfoValue($userID, 'turntableGetJewels', $num);
            }
            $arrayData['total_reward_count'] += 1;
        }
        return DBManager::getMysql()->insert(MysqlConfig::Table_web_turntable_record, $arrayData);
    }

    /**
     * 今天领取救济金记录
     * @param $userID
     * @return mixed
     */
    public function getReceiveSupportCount($userID)
    {
        //redis获取
        $today = date('Y-m-d', time());
        $userInfo = $this->getUserInfo($userID, ['reqSupportTimes', 'lastReqSupportDate']);
        FunctionHelper::arrayValueToInt($userInfo);

        if (date('Y-m-d', $userInfo['lastReqSupportDate']) != $today) {
            return 0;
        }
        return $userInfo['reqSupportTimes'];
    }

    /**
     * 领取救济金记录
     * @param $userID
     * @param $count
     * @param $money
     */
    public function writeReceiveSupport($userID, $count, $money)
    {
        //刷新reids数据
        $userInfo = $this->getUserInfo($userID, ['reqSupportTimes', 'lastReqSupportDate']);
        FunctionHelper::arrayValueToInt($userInfo);
        $data = ['reqSupportTimes' => $count, 'lastReqSupportDate' => time()];
        $this->updateUserInfo($userID, $data);

        //写记录
        $arrayData = array(
            "userID" => $userID,
            "time" => time(),
            "money" => $money,
            'total_get_money' => 0,
            'total_count' => 0,
        );
        //统计次数
        $where = "userID={$userID} order by time desc";
        $arrayKeyValue = ['total_count', 'total_get_money'];
        $data = DBManager::getMysql()->selectRow(MysqlConfig::Table_web_support_record, $arrayKeyValue, $where);
        if (!empty($data)) {
            $arrayData['total_get_money'] = $data['total_get_money'];
            $arrayData['total_count'] = $data['total_count'];
        }
        //救济金统计信息
        UserModel::getInstance()->addWebUserInfoValue($userID,'supportCount');
        UserModel::getInstance()->addWebUserInfoValue($userID,'supportGetMoney',$money);
        $arrayData['total_get_money'] += $money;
        $arrayData['total_count'] += 1;
        DBManager::getMysql()->insert(MysqlConfig::Table_web_support_record, $arrayData);
    }
}
