<?php
ini_set('display_errors', 1);
require_once dirname(__DIR__) . '/hm_ucenter/helper/LoadHelper.php';

$userid = \manager\DBManager::getMysql()->queryAll('select userID from ' . \config\MysqlConfig::Table_web_agent_member);
var_dump($userid);
$userid = is_array($userid) ? $userid : [];
foreach ($userid as $k =>$v){
    var_dump($v);
    $result = \manager\RedisManager::getGameRedis()->sIsMember(\config\GameRedisConfig::Set_web_agentmember,$v['userID']);
    if($result){
        continue;
    }else{
        $res = \manager\RedisManager::getGameRedis()->sAdd(\config\GameRedisConfig::Set_web_agentmember,$v['userID']);
        var_dump($res);
    }
}