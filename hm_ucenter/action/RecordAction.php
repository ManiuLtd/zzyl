<?php
namespace action;

use config\ErrorConfig;
use config\GameRedisConfig;
use config\MysqlConfig;
use manager\DBManager;
use manager\RedisManager;
use model\AppModel;

// TODO 待优化 一些数据获取应该封装到mode里面
/**
 * 战绩业务
 * Class RecordAction
 */
class RecordAction extends AppAction
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
     * 战绩大结算简单信息列表
     * @param $param
     */
    public function simpleGradeList($param)
    {
        $userID = (int)$param['userID'];
        $gameID = (int)$param['gameID'];

        $data = RedisManager::getGameRedis()->zRevrange(GameRedisConfig::SSet_userGradeSet . '|' . $userID, 0, 49);

        if ($gameID) {
            $sql = "select roomID from " . MysqlConfig::Table_roombaseinfo . " where gameID = {$gameID}";
            $row = DBManager::getMysql()->queryAll($sql);
            $val = [];
            foreach ($row as $v) {
                $val[] = $v['roomID'];
            }
        }

        $gradeRecored = [];

        foreach ($data as $k => $v) {
            $find = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_gradeSimpleInfo . '|' . $v);

            $gradeRecoredInfo = [
                'id' => (int)$find['id'],
                'deskPasswd' => (int)$find['passwd'],
                'time' => (int)$find['time'],
                'gameCount' => (int)$find['gameCount'],
                'maxGameCount' => (int)$find['maxGameCount'],
                'score' => 0,
                'gameRules' => $find['gameRules'],
                'roomID' => (int)$find['roomID'],
            ];

            $score = explode('|', $find['userInfoList']);
            array_pop($score);
            foreach ($score as $v) {
                $findUser = explode(',', $v);
                if ($findUser[0] == $userID) {
                    $gradeRecoredInfo['score'] = (int)$findUser[1];
                }
            }

            $gradeRecored[] = $gradeRecoredInfo;
        }

        if (count($val) > 0) {
            foreach ($gradeRecored as $k => $v) {
                if (!in_array($v['roomID'], $val)) {
                    unset($gradeRecored[$k]);
                }
            }
        }
        $gradeRecored = array_values($gradeRecored);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $gradeRecored);

    }

    /**
     * 战绩小结算列表
     * @param $param
     */
    public function gradeList($param)
    {
        $gradeID = (int)$param['gradeID'];
        $userID = (int)$param['userID'];

        $data = RedisManager::getGameRedis()->sMembers(GameRedisConfig::Set_gradeSimpleSet . '|' . $gradeID);

        foreach ($data as $k => $v) {

            $find = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_gradeDetailInfo . '|' . $v);

            $oneGradeInfo = [
                'gradeID' => (int)$find['id'],
                'deskPasswd' => (int)$find['passwd'],
                'time' => (int)$find['time'],
                'gameCount' => (int)$find['gameCount'],
                'inning' => (int)$find['inning'],
                'score' => 0,
                'videoCode' => $find['videoCode'],
            ];

            $score = explode('|', $find['userInfoList']);
            array_pop($score);
            foreach ($score as $v) {
                $findUser = explode(',', $v);
                if ($findUser[0] == $userID) {
                    $oneGradeInfo['score'] = (int)$findUser[1];
                    break;
                }
            }

            $oneGrade[] = $oneGradeInfo;
        }

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $oneGrade);
    }

    /**
     * 大结算详情统计
     * @param $param
     */
    public function simpleGradeInfo($param)
    {
        $gradeID = (int)$param['gradeID'];

        $data = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_gradeSimpleInfo . '|' . $gradeID);

        $returnData = ['userInfoList' => $data['userInfoList'], 'gameData' => $data['gameData']];

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returnData);

    }

    /**
     * 小结算 详情统计
     * @param $param
     */
    public function gradeInfo($param)
    {
        $gradeID = (int)$param['gradeID'];

        $data = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_gradeDetailInfo . '|' . $gradeID);

        $returnData = ['userInfoList' => $data['userInfoList'], 'gameData' => $data['gameData']];

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $returnData);

    }
}
