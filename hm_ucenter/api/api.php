<?php
use action\AppAction;

$api = "\\action\\" . ucwords($_REQUEST['api']);
$action = $_REQUEST['action'];

//工厂模式根据不同的名字实例化Action
$common = AppAction::factory($api);
if (!is_object($common)){
    AppModel::returnJson(ErrorConfig::ERROR_CODE, " no {$api} Action");
}
//反射Action类
$ref = new ReflectionClass($common);
if (!$ref->hasMethod($action)){
    AppModel::returnJson(ErrorConfig::ERROR_CODE, "in {$api} Action no {$action} Method");
}
//通过方法名字调用方法
$method = $ref->getMethod($action);
if (!$method->isPublic()){
    AppModel::returnJson(ErrorConfig::ERROR_CODE, "{$action} Method not public");
}
//执行方法
$method->invoke($common,$_REQUEST);