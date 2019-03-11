<?php

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';
//echo 'ooo';
function err($errno, $errstr, $errfile, $errline) {
	$res = (['errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline]);
	file_put_contents('err.log', var_export($res, true));
}
class Notify {
	function __construct() {
	    set_time_limit(0);
//		set_error_handler('err');
		// $this->isFranchisee();
//		echo 'heldf';exit;
		 // $this->getAgentID();
//		$this->test();
//		 $this->getAgentById();
//        $this->getUpAgentList();
//        $this->getUpAgent();
//        while (true) {
//            sleep(10);
            $this->sendNotify();
//        }
	}

	public function sendNotify() {
//	    echo 'helllll';exit;
        $title = isset($_REQUEST['title']) ? $_REQUEST['title'] : time();
//        $title = time();
        $title = '{"name":"testName","money":1000}';
		$res = NotifyLogic::getInstance()->sendNormalNotify($title);
//        $res = NotifyLogic::getInstance()->sendNormalNotify('玩家土豪金一掷千金，充值1000金币，壕气冲天！');
		var_dump($res);
	}

}

new Notify();