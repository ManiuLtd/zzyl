<?php
namespace Home\Controller;
vendor('Common.GetRedis', '', '.class.php');

class UpdateController extends Controller
{
    public function index()
    {
        $redis = \GetRedis::get();
        if (!$redis || $redis->connect == false) {
            $this->error('redis连接失败');
        }

        $user   = $redis->redis->keys('userInfo|*');
        foreach ($user as $v) {
            $user = $redis->redis->hgetall($v);
            if ($user['isVirtual'] == 1) {
                continue;
            }

            $find = M()->table('userInfo')->where(['userID'=>$user['userID']])->find();
            dump($find);
            // if(!$find){
            // 	unset($data['userID']);
            // 	$res = M()->data($data)->add();
            // 	dump($res);
            // }
        }
    }
}
