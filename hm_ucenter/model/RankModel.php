<?php 
namespace model;
use config\MysqlConfig;
use helper\FunctionHelper;
use manager\DBManager;

/**
 * 排行榜模块
 * Class RankModel
 */
class RankModel extends AppModel
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
     * 获取玩家胜局榜列表
     * @param int $num
     * @return array|bool
     */
    public function getWinRankList($num)
    {
        $sql = "select userID,name,headURL,wincount from "
            . MysqlConfig::Table_userinfo
            . " where isVirtual=0 order by wincount DESC,userID limit {$num}";
        $intKeyArray = array(
            'userID',
            'wincount',
        );
        $result = DBManager::getMysql()->queryAll($sql);
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }

    /**
     * 获取玩家土豪榜列表
     * @param string $richType
     * @param int $num
     * @return array|bool
     */
    public function getRichRankList($richType, $num)
    {
        if ($richType == 'jewels') {
            $intKeyArray = array(
                'userID',
                'jewels',
            );
            $sql = "select userID,account,name,headURL,jewels from "
                . MysqlConfig::Table_userinfo
                . " where isVirtual=0 order by jewels DESC,userID desc limit {$num}";
        } elseif ($richType == 'money') {
            $intKeyArray = array(
                'userID',
                'money',
            );
            $sql = "select userID,account,name,headURL,money from "
                . MysqlConfig::Table_userinfo
                . " where isVirtual=0 order by money DESC,userID desc limit {$num}";
        }
        $result = DBManager::getMysql()->queryAll($sql);
        FunctionHelper::arrayValueToInt($result, $intKeyArray);
        return $result;
    }
}
