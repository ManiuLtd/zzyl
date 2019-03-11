<?php
// 大厅
namespace API\Controller;

use Think\Controller;

class HallController extends CommonController
{
    // 邮件
    public function getEmail()
    {

    }

    // 好友
    public function friends()
    {

    }

    // 战绩列表 大计算
    public function gradeRecored()
    {
        $userID = (int) I('post.userID', 11997);
        // $findUser = $this->checkUser($userID);
        // if (!$findUser) {
        //     $this->returnJson(self::ERROR_CODE, self::ERROR_MSG_DOES_NOT_EXIST);
        // }

        $data = $this->redis->ZREVRANGE('userGradeSet|' . $userID, 0, 49, 'WITHSCORES');

        $gradeRecored = [];

        foreach ($data as $k => $v) {
            $find = $this->redis->hgetall('gradeSimpleInfo|' . $k);

            $gradeRecoredInfo = [
                'id'             => $find['id'],
                'desdeskPasswdk' => $find['passwd'],
                'time'           => $find['time'],
                'gameCount'      => $find['gameCount'],
                'maxGameCount'   => $find['maxGameCount'],
                'score'          => 0,
                'gameRules'      => $find['gameRules'],
            ];

            $score = explode('|', $find['userInfoList']);
            array_pop($score);
            foreach ($score as $v) {
                $findUser = explode(',', $v);
                if ($findUser[0] == $userID) {
                    $gradeRecoredInfo['score'] = $findUser[1];
                }
            }

            $gradeRecored[] = $gradeRecoredInfo;
        }

        $this->returnJson(self::SUCCESS_CODE, self::SUCCESS_MSG_DEFAULT, $gradeRecored);
    }

    // 小结算集合
    public function gradeSimple()
    {
        $gameID = (int) I('post.gameID', '');
        $data   = $this->redis->ssmembers('gradeSimpleSet|' . $gameID);

        $oneGrade = [];

        foreach ($data as $v) {
            $find = $this->redis->hgetall('gradeDetailInfo|' . $v);

            $oneGradeInfo = [
                'id'             => $find['id'],
                'desdeskPasswdk' => $find['passwd'],
                'time'           => $find['time'],
                'gameCount'      => $find['gameCount'],
                'maxGameCount'   => $find['maxGameCount'],
                'score'          => 0,
                'gameRules'      => $find['gameRules'],
            ];

            $score = explode('|', $find['userInfoList']);
            array_pop($score);
            foreach ($score as $v) {
                $findUser = explode(',', $v);
                if ($findUser[0] == $userID) {
                    $gradeRecoredInfo['score'] = $findUser[1];
                }
            }

            $oneGrade[] = $oneGradeInfo;
        }

        $this->returnJson(self::SUCCESS_CODE, self::SUCCESS_MSG_DEFAULT, $oneGrade);
    }

    // 单个战绩列表
    public function LogonRequestGradeList()
    {
        $gradeID = (int) I('gradeID');

        $data = $this->redis->hgetall('gradeDetailInfo|' . $gradeID);

        $gameRecord = [];

		$userlist = explode('|',$data['userInfoList']);
       	
       	array_pop($userlist);

       	foreach($userlist as $v){

       	}
    }
}
