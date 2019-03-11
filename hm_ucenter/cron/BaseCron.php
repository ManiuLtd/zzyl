<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 11:22
 */
namespace cron;

abstract class BaseCron
{
    protected $isCronTab = null;
    public function __construct()
    {
        if (null == $this->isCronTab || true != $this->isCronTab) {
//            throw new \Exception("不能继承本类");
            exit('不能继承本类');
        }
    }
}