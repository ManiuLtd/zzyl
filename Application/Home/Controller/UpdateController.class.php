<?php
namespace Home\Controller;
use Think\Controller;
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
        $i = 0;
	foreach ($user as $v) {
            $user = $redis->redis->hgetall($v);
            /*if ($user['isVirtual'] == 1) {
                continue;
            }*/

            $find = M()->table('userInfo')->where(['userID'=>$user['userID']])->find();
             if(!$find){
		unset($user['extendMode']);
             	$encode    = mb_detect_encoding($user['name'], array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
                $user['name'] = mb_convert_encoding($user['name'], "UTF-8", $encode);
		$user['name'] = str_replace("'","?",$user['name']);
		//$res = M()->table('userInfo')->data($user)->add();
		//echo $res;
             	echo '<pre>';
		print_r($user);
            }
		$i ++;
        }
	var_dump($i);
    }
}
