<?php

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';
//echo 'ooo';
function err($errno, $errstr, $errfile, $errline) {
    $res = (['errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline]);
    file_put_contents('err.log', var_export($res, true));
}
class Mail {
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
        $this->sendRewards();
//        }
    }

    public function sendRewards() {
        $res = \logic\MailLogic::getInstance()->sendRewards('rewards', 'rewards', 118010, 0, 1);
        var_export($res);
    }

}

new Mail();