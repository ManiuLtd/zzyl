<?php

use config\MysqlConfig;
use model\AgentModel;
use model\ClubModel;

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';

function err($errno, $errstr, $errfile, $errline) {
	$res = (['errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline]);
	file_put_contents('err.log', var_export($res, true));
}
class agent {
	function __construct() {
//		set_error_handler('err');
		// $this->isFranchisee();
		echo 'heldf';
		 // $this->getAgentID();
//		$this->test();
//		 $this->getAgentById();
//        $this->getUpAgentList();
//        $this->getUpAgent();
        $this->updateLowerCommissionRate();
	}

	public function updateLowerCommissionRate() {
	    $res = AgentLogic::getInstance()->updateLowerCommissionRate(120010,120002, 22);
	    var_export($res);
    }
	public function isFranchisee () {
		$userID = 119006;
		$isFranchisee = ClubModel::getInstance()->isFranchisee($userID);
		var_dump($isFranchisee);
	}

	public function addAgent() {
		// ini_set('display_errors', 1);
		echo 'o';
		$res = AgentModel::getInstance()->setUser2Agent([
			'username' => '15523654236',
			'userid' => '119012',
			'commission_rate' => 50,

		], 119019);
		echo '---------';
		var_dump($res);
		echo 'o';
	}

	public function getAgentID() {
		$res = AgentModel::getInstance()->get_superior(119019);
		var_dump($res);
		echo 'get';
	}

	public function getAgentById() {
		$agentMember = AgentModel::getInstance()->getAgentMemberByUserID(119006, ['id']);
		var_dump($$agentMember);
	}

	public function test() {
		$userID = 119019;
		$agentID = DBManager::getMysql()->queryAll('select agentID from ' . MysqlConfig::Table_web_agent_bind . ' where userID = ' . $userID);
		var_dump($agentID);
	}

	public function getUpAgentList() {
	    $agentList = AgentModel::getInstance()->getUpAgentList(['superior_agentid' => 720183]);
	    var_dump($agentList);
    }


    public function getDownAgentInfo() {
        $agent = DBManager::getMysql()->queryRow('select * from ' . MysqlConfig::Table_web_agent_member . ' where superior_agentid > 0 limit 1');
//        var_export($agent);exit;
	    $info = AgentModel::getInstance()->getDownAgentInfo($agent['superior_agentid'],1);
	    var_export($info);
    }

    public  function getUpAgent() {
	    echo 'hlo';
        $agent = DBManager::getMysql()->queryRow('select a.* from ' . MysqlConfig::Table_web_agent_bind . ' a join ' . MysqlConfig::Table_web_agent_member . ' b on a.agentID = b.agentid where a.agentID > 0 limit 1');
//        var_export($agent);exit;
	    $res = AgentModel::getInstance()->getUpAgentInfoByUserID($agent['userID'], ['id', 'commission_rate']);
	    var_export($res);
    }
}

new agent();