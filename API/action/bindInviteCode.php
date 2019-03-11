<?php 
if(isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) && isset($_REQUEST['agentID']) && !empty($_REQUEST['agentID'])){
	//验证代理号和用户是否存在
	$userID = $_REQUEST['userID'];
	$agentID = $_REQUEST['agentID'];
	$sql = "select * from web_agentmember where agentid='$agentID'";
	$member = $db -> getRow($sql);
 	$redis = GetRedis::get();
 	if($redis->connect == false){
	    $arr = array('status'=>0,'msg'=>'缓存服务器未启动');
	    exit(return_json($arr));
  	}
  	$user =  $redis->redis->hgetall('userInfo|'.$userID);
  	if(!$user){
	    $arr = array('status'=>0,'msg'=>'用户不存在');
	    exit(return_json($arr));
  	}
  		//判断是否绑定过
	$sql ="select * from web_bindagentid where userID=$userID";
	$res = $db -> getRow($sql);
	if($res){
		$arr = array('status'=>0,'msg'=>'您已经绑定过了');
	    exit(return_json($arr));
	}
	if(!$member){
		$arr = array('status'=>0,'msg'=>'邀请码不存在');
	    exit(return_json($arr));
	}
	//判断他绑定的是不是自己
	if($member['userid'] == $userID){
		$arr = array('status'=>0,'msg'=>'您不能绑定您自己的邀请码');
	    exit(return_json($arr));
	}
  	//获取我的信息
	$sql = "select * from web_agentmember where userid=".$userID;
	$my = $db -> getRow($sql);
	function get_my_member($agentid,$db){
		$arr = [];
		//获取我的所有二级代理的agentid
		$sql = "select agentid from web_agentmember where superior_agentid='$agentid'";
		$l1_agnetid = $db->getAll($sql);
		//遍历获取我的所有三级代理
		foreach($l1_agnetid as $k => $v){
			$arr[] = $l1_agnetid[$k]['agentid'];	
			$sql = "select agentid from web_agentmember where superior_agentid='".$l1_agnetid[$k]['agentid']."'";
			$l2_agentid = $db -> getAll($sql);
			foreach($l2_agentid as $key => $value){
				$arr[] = $l2_agentid[$k]['agentid'];
			}
		}
		return $arr;
	}
	if($my){
		if($my['superior_agentid']){
			$arr = array('status'=>0,'msg'=>'您已经绑定过了');
	    	exit(return_json($arr));
		}
		//下两级代理不能绑定
		$arr = get_my_member($my['agentid'],$db);
		if(in_array($agentID,$arr)){
			$arr = array('status'=>0,'msg'=>'您不能绑定您自己的三级代理');
	    	exit(return_json($arr));
		}
	}
	$user['name'] = iconv('GB2312','UTF-8',$user['name']);
	//通过验证给玩家绑定代理号并且赠送礼品
  	$sql = "insert into web_bindagentid(userID,agentID,agentname,bind_time,username)values(".$userID.",".$agentID.",'".$member['username']."',".time().",'".$user['name']."')";
  	$res = $db -> add($sql);
  	if(!$res){
  		$arr = array('status'=>0,'msg'=>'绑定失败，插入数据时发送错误');
	    exit(return_json($arr));
  	}
	/*if($my){
		$sql = "update web_agentmember set superior_agentid='$agentID',superior_username='".$member['username']."' where id=".$my['id'];
		$db->add($sql);
	}*/
  	//获取礼品
  	$sql = "select * from web_gameconfig";
  	$config = $db -> getAll($sql);
	$c = array();
	foreach($config as $key => $value){
	    $c[$config[$key]['key']] = $config[$key]['value'];
	}
	$send_jewels = (int)$c['bind_agentid_send_jewels'];
	$send_money = (int)$c['bind_agentid_send_money'];
	//发送消息失败时回滚绑定
	function leave_agentid($userID,$db){
		$sql = "delete from web_bindagentid where userID=$userID";
		$db -> add($sql);
	}
	//给服务端发送消息
	$socket = Socket::get();
 	$send = new SendFunction();
 	if($socket->connet == false){
 		leave_agentid($userID,$db);
 		$arr = array('status'=>0,'msg'=>'请求失败，服务未启动');
	    exit(return_json($arr));
 	}
 	$packet1 = $send -> makeSendAgent($userID,2,$send_jewels);
 	$packet2 = $send -> makeSendAgent($userID,1,$send_money);
 	$res1 = $socket -> send($send::AgentID,1,0,$packet1);
 	$res2 = $socket -> send($send::AgentID,1,0,$packet2);
 	if(!$res1 || !$res2){
 		leave_agentid($userID,$db);
 		$arr = array('status'=>0,'msg'=>'请求失败，与服务器通信出现错误');
	    exit(return_json($arr));
 	}
 	$read = unpack('i*', $socket->read_data(1024));
 	if(!$read){
 		leave_agentid($userID,$db);
 		$arr = array('status'=>0,'msg'=>'请求失败，接收服务器消息失败');
	    exit(return_json($arr));
 	}
 	if($read[4] != 0){
 		leave_agentid($userID,$db);
 		$arr = array('status'=>0,'msg'=>'请求失败,错误码'.$read[4]);
	    exit(return_json($arr));
 	}
	//同时对代理进行绑定
 	if($my){
 		if(!$my['superior_agentid']){
            if($member['agentid'] != $agentID) {
                $sql = "update web_agentmember set superior_agentid='$agentID',superior_username='" . $member['username'] . "' where id=" . $my['id'];
                $db->add($sql);
            }
 		}
 	}
 	//返回客户端
 	$arr = array('status'=>1,'msg'=>'绑定成功');
	exit(return_json($arr));
}
$arr = [
	'status'	=> 0,
	'msg'		=> '传递参数不完整',
];
exit(return_json($arr));
?>
