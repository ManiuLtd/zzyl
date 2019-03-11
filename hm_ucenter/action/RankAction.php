<?php
namespace action;

use config\ErrorConfig;
use model\AppModel;
use model\RankModel;

/**
 * 排行榜业务
 * Class RankAction
 */
class RankAction extends AppAction {
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
	 * 胜局榜
	 * @param $params
	 */
	public function winRankList($params){
		$num = (int) $params['num']?(int) $params['num']:10;
		$winRankList = RankModel::getInstance()->getWinRankList($num);
		AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $winRankList);
	}

	/**
	 * 土豪榜
	 * @param $params
	 */
	public function richRankList($params){
		$richType = $params['richType']; //rankType：jewels钻石榜  money金钱榜
		$num = (int) $params['num']?(int) $params['num']:10;
		$richRankList = RankModel::getInstance()->getRichRankList($richType,$num);
		AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $richRankList);
	}
}
