<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 11:20
 */
namespace cron;

use model\AgentModel;

final class CommissionCron extends \cron\BaseCron
{
    protected $isCronTab = true;
    public function __construct()
    {
        parent::__construct();
    }

    public function run() {
        ini_set('display_errors', 1);
        (new AgentModel())->loopBattleRecord();

    }
}