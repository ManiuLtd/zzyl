<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24
 * Time: 9:47
 */

require_once dirname(__DIR__) . '/helper/LoadHelper.php';
require_once dirname(__DIR__) . '/aliyun-dysms-php-sdk/api_demo/SmsDemo.php';
class testSms
{
    public function __construct()
    {        
        ini_set('display_errors', 1);
        error_reporting(1);
        echo 'hi';
        $this->send();
    }

    public function send() {
        $code = rand(111111, 999999);
        $phone = '15626519209';
        echo 'goods';
        header('Content-Type: text/plain; charset=utf-8');
        $response = SmsDemo::sendSms($phone, $code);
        echo 'hello';
        var_dump($response);
    }
}

new testSms();