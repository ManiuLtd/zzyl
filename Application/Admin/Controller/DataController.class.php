<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

class DataController extends AdminController
{
    protected $xiaji = '';

    public function index() {
        echo 'hello';
    }

    public function commission() {
    	$this->display();
    }


}


