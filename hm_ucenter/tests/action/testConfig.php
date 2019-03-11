<?php

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';

class testConfig {
	public $testUrl = '';
	public function __construct() {
		$this->testUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/hm_ucenter/web/index.php?api=config&action=config';

		$this->config();
	}

	public function config() {
		// echo $this->testUrl;
		// exit;
		$res = FunctionHelper::curlRequest($this->testUrl);
		var_dump($res);
	}
}

new testConfig();