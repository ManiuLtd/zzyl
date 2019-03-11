<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 11:31
 * 调用方法：~/php ~/hm_ucenter/cron/Run.php \\cron\\CommissionCron , /usr/local/bin/php /usr/share/nginx/vhost/zzyl.env/hm_ucenter/cron/Run.php \\cron\\CommissionCron >> ~/CommissionCron.log
 */
ini_set('display_errors', 0);
require_once dirname(__DIR__) . '/helper/LoadHelper.php';
$cron = isset($argv[1]) ? $argv[1] : '';
if (!class_exists($cron)) {
    echo "class not exists:{$cron}\n";
    exit;
}

$class = new $cron();
if (!($class instanceof \cron\BaseCron)) {
    echo 'class instanceof \cron\BaseCron is false';
    exit;
}
$class->run();
