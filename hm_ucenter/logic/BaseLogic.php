<?php
namespace logic;
/**
 * 通用模块
 * Class AppModel
 */
abstract class BaseLogic
{
	public function __construct() {
		
	}

	public function returnData($code, $msg, $data = null) {
	    return [
	        'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
    }
}