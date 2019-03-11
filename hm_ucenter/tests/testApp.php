<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 11:06
 */

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//控制浏览器缓存
//header("Cache-Control: no-cache, must-revalidate");
//header('Content-Type: text/html; charset=utf-8');
//header("Pragma: no-cache");

//设置默认时区
date_default_timezone_set('Asia/Shanghai');

// app通信唯一ID
defined('PLAT_UUID') or define('PLAT_UUID', 'A1D62-DAB28-80Z59-ACW87-1ETD9');

//自动加载帮助类
require_once dirname(__DIR__) . '/helper/LoadHelper.php';