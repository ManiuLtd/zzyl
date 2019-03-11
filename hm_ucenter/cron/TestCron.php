<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 13:34
 */
namespace cron;

class TestCron extends \cron\BaseCron
{
    protected $isCronTab = true;
    public function __construct()
    {
        parent::__construct();
    }

    public function run() {
        echo 'I am running';
    }
}