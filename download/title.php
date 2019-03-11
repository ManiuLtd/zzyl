<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//控制浏览器缓存
header("Cache-Control: no-cache, must-revalidate");
header('Content-Type: text/html; charset=utf-8');
header("Pragma: no-cache");

//设置默认时区
date_default_timezone_set('Asia/Shanghai');

//自动加载帮助类
require_once dirname(__DIR__) . '/hm_ucenter/helper/LoadHelper.php';

$gameConfig = \ShareModel::getInstance()->getGameConfig();
$getHomeConfig = \ShareModel::getInstance()->getHomeConfig();
var_export($getHomeConfig);
?>
