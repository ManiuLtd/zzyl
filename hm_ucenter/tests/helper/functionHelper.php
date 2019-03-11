<?php

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';

function err($errno, $errstr, $errfile, $errline) {
	$res = (['errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline]);
	file_put_contents('err.log', var_export($res, true));
}
class testFunctionHelper {
	function __construct() {
		// ini_set('display_errors', 1);
		// error_reporting(1);
		// set_error_handler('err');
		$this->moneyOutput();
	}

	public function moneyOutput () {
		echo FunctionHelper::moneyOutput('1');
	}
}

new testFunctionHelper();