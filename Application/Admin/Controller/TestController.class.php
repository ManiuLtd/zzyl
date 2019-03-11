<?php
namespace Admin\Controller;

vendor('Common.GetRedis', '', '.class.php');
vendor('Common.SendFunction', '', '.class.php');
vendor('Common.Socket', '', '.class.php');

class TestController extends AdminController
{
    protected $xiaji = '';

    public function index() {
        echo 'hello';
    }

    public function session() {
    	session(array('expire'=>10));
    	session('mytest', 'testtt00000');
    }

    public function getsession() {
    	dump(session());

        $loginInfo = session('agentLoginInfo');
        dump($loginInfo);
        return $loginInfo['admin_user_id'];
    }
}


