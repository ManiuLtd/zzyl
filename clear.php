<?php 
header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time', '0');
/**
*清除数据，统计数据	
**/	
#引入需要类
include './API/GetRedis.class.php';
include './common/config.php';
include './common/db.php';
$db = new db();
$redis = GetRedis::get();
class Clear{
	private $redis;
	private $db;
	public function __construct($redis,$db){
		$this->redis = $redis;
		$this->db = $db;
	}
	/**
	 * 清除数据
	 * @DateTime 2017-11-15
	 * @author dly;
	 * @return   
	 */
	private function clear(){
		$clearArr = $this->get_clear_data();
		//遍历清除数据
		foreach($clearArr as $v){
			$this->clear_data($v[0],$v[1]);
		}
	}
	/**
	 * 执行清除sql
	 * @DateTime 2017-11-15
	 * @return   [type]
	 */
	private function clear_data($tableName,$dayCount){
		$time = time()-$dayCount*86400;
		$where = "";
		switch($tableName){
			case 'statistics_gameRecordInfo':	#游戏记录
				$where = "finishedTime < $time";
			break;
			case 'statistics_horn':				#世界广播
			case 'statistics_support':			#救济金
				$where = "reqTime < $time";
			break;
			case 'statistics_magicExpress':		#魔术表情
			case 'statistics_moneyChange':		#金币变化
			case 'statistics_sendGift':			#转赠记录
				$where = "time < $time";
			break;
			case 'web_sharegame':				#分享游戏记录
				$where = "share_time < $time";
			break;
			case 'web_signRecord':				#签到记录
				$where = "signTime < $time";
			break;
			case 'web_turntableRecord':			#抽奖记录
				$where = "turntableTime < $time";
			break;							
		}
		if(!$where){
			echo '无where条件！！！！！！';die;
		}
		$sql = "delete from $tableName where $where";
		$res = $this->db->add($sql);
		/*if(!$res){
			echo '删除失败，方法：clear_data';
		}*/
	}
	/**
	 * 获取清除数据配置
	 * @DateTime 2017-11-15
	 * @return   [type]
	 */
	private function get_clear_data(){
		$sql = "select * from web_gameconfig where id=17";
		$res = $this->db->getRow($sql);	
		$arr = explode('|',$res['value']);
		$clearArr = [];
		foreach($arr as $k=>&$v){
			$i = explode(',',$v);
			$clearArr[$k][] = $i[0];
			$clearArr[$k][] = $i[1];
		}
		return $clearArr;
	}
	/**
	 * 更新
	 * @DateTime 2017-11-15
	 * @return   [type]
	 */
	private function update_data(){
		$redisUser = $this->get_redis_user();
		$sqlserverUser = $this->get_sqlserver_user();
		$this->update_user($redisUser,$sqlserverUser);
		foreach($sqlserverUser as $v){
			$this->update_user_data($v);
		}
	}
	/**
	 * 更新web_users表用户
	 * @DateTime 2017-11-16
	 * @param    [type]     $redisUser     [description]
	 * @param    [type]     $sqlserverUser [description]
	 * @return   [type]                    [description]
	 */
	private function update_user($redisUser,$sqlserverUser){
		foreach($redisUser as $v){
			if(!in_array($v,$sqlserverUser)){
				$sql = "insert into web_users(userID)values($v)";
				$this->db->add($sql);
			}
		}
	}
	/**
	 * 更新用户数据
	 * @DateTime 2017-11-16
	 * @param    [type]     $userID [用户ID]
	 * @return   [null]             [无返回]
	 */
	private function update_user_data($userID){
		# [分享次数,反馈次数,世界广播次数,实物兑换次数,发送魔法表情次数,
		# 抽奖次数,签到次数,转赠金币,接收金币，转赠房卡，接收房卡，抽奖获取金币
		# 抽奖获取房卡，签到获取金币，签到获取房卡，魔法表情消耗金币，对局数
		# 转赠消耗房卡，转赠消耗金币，发送世界广播消耗房卡，实物兑换消耗金币
		# 实物兑换获取的房卡，领取救济金数，消耗金币]
		if(!$userID) return '';
		$beginTime = mktime(0,0,0,date('m'),date('d'),date('Y')) - (200*24*3600);	//今天开始时间戳
		$shareCount = $this->db->getRow("select count(*) as c from web_sharegame where userID=$userID and share_time > $beginTime");
		$shareCount['c'] = $shareCount['c']?$shareCount['c']:0;
		$feedbackCount = $this->db->getRow("select count(*) as c from web_adminfeedback where userID=$userID and f_time > $beginTime");
		$feedbackCount['c'] = $feedbackCount['c']?$feedbackCount['c']:0;
		$radio = $this->db->getRow("select count(*) as c,sum(cost) as cost from statistics_horn where userID=$userID and reqTime > $beginTime");
		$radio['c'] = $radio['c']?$radio['c']:0;
		$radio['cost'] = $radio['cost']?$radio['cost']:0;
		$convert = $this->db->getRow("select count(*) as c,sum(consumeNum) as num from web_orders where userID=$userID and buyType=4 and create_time > $beginTime");
		$convert['c'] = $convert['c']?$convert['c']:0;
		$convert['num'] = $convert['num']?$convert['num']:0;
		$convertGetJewels = $this->db->getRow("select sum(buyNum) as num from web_orders where buyGoods='房卡' and buyType=4 and userID=$userID and create_time > $beginTime");
		$convertGetJewels['num'] = $convertGetJewels['num']?$convertGetJewels['num']:0;
		$magic = $this->db->getRow("select count(*) as c,sum(costMoney) as cost from statistics_magicExpress where userID=$userID and time > $beginTime");
		$magic['c'] = $magic['c']?$magic['c']:0;
		$magic['cost'] = $magic['cost']?$magic['cost']:0;
		$turntableCount = $this->db->getRow("select count(*) as c from web_turntableRecord where userID=$userID and turntableTime > $beginTime");
		$turntableCount['c'] = $turntableCount['c']?$turntableCount['c']:0;
		$turntableJewels = $this->db->getRow("select sum(num) as jewels from web_turntableRecord where userID=$userID and  prizeType=2 and turntableTime > $beginTime");
		$turntableJewels['jewels'] = $turntableJewels['jewels']?$turntableJewels['jewels']:0;
		$turntableMoney = $this->db->getRow("select sum(num) as money from web_turntableRecord where userID=$userID and  prizeType=1 and turntableTime>$beginTime");
		$turntableMoney['money'] = $turntableMoney['money']?$turntableMoney['money']:0;
		$signCount = $this->db->getRow("select count(*) as c from web_signRecord where userID=$userID and signTime > $beginTime");
		$signCount['c'] = $signCount['c']?$signCount['c']:0;
		$signJewels = $this->db->getRow("select sum(num) as jewels from web_signRecord where userID=$userID and prizeType=2 and signTime > $beginTime");
		$signJewels['jewels'] = $signJewels['jewels']?$signJewels['jewels']:0;
		$signMoney = $this->db->getRow("select sum(num) as money from web_signRecord where userID=$userID and  prizeType=1 and signTime>$beginTime");
		$signMoney['money'] = $signMoney['money']?$signMoney['money']:0;
		$sendGetMoney = $this->db->getRow("select sum(recievedNumber) as num from statistics_sendGift where targetID=$userID and resourceType=1 and time > $beginTime");
		$sendGetMoney['num'] = $sendGetMoney['num']?$sendGetMoney['num']:0;
		$sendLostMoney = $this->db->getRow("select sum(resourceNumber) as num from statistics_sendGift where userID=$userID and resourceType=1 and time > $beginTime");
		$sendLostMoney['num'] = $sendLostMoney['num']?$sendLostMoney['num']:0;
		$sendCostMoney = $this->db->getRow("select sum(deduceNumber) as num from statistics_sendGift where userID=$userID and resourceType=1 and time > $beginTime");
		$sendCostMoney['num'] = $sendCostMoney['num']?$sendCostMoney['num']:0;
		$sendGetJewels = $this->db->getRow("select sum(recievedNumber) as num from statistics_sendGift where targetID=$userID and resourceType=2 and time > $beginTime");
		$sendGetJewels['num'] = $sendGetJewels['num']?$sendGetJewels['num']:0;
		$sendLostJewels = $this->db->getRow("select sum(resourceNumber) as num from statistics_sendGift where userID=$userID and resourceType=2 and time > $beginTime");
		$sendLostJewels['num'] = $sendLostJewels['num']?$sendLostJewels['num']:0;
		$sendCostJewels = $this->db->getRow("select sum(deduceNumber) as num from statistics_sendGift where userID=$userID and resourceType=2 and time > $beginTime");
		$sendCostJewels['num'] = $sendCostJewels['num']?$sendCostJewels['num']:0;
		$support = $this->db->getRow("select sum(reqSupportMoney) as money from statistics_support where userID=$userID and reqTime > $beginTime");
		$support['money'] = $support['money']?$support['money']:0;
		$openGameCost = $this->db->getRow("select sum(changeMoney) as money from statistics_moneyChange where userID=$userID and reason=16 and time > $beginTime");
		$openGameCost['money'] = $openGameCost['money']?$openGameCost['money']:0;
		$costMoney = $magic['cost']+$openGameCost['money']+$sendCostMoney['num'];
		#更新到web_users表中去
		$sql = "update web_users set shareCount=shareCount+".$shareCount['c'].",feedbackCount=feedbackCount+".$feedbackCount['c'].",radioCount=radioCount+".$radio['c'].",convertCount=convertCount+".$convert['c'].",magicCount=magicCount+".$magic['c'].",turntableCount=turntableCount+".$turntableCount['c'].",signCount=signCount+".$signCount['c'].",sendMoney=sendMoney+".$sendCostMoney['num'].",sendJewels=sendJewels+".$sendCostJewels['num'].",receiveMoney=receiveMoney+".$sendGetMoney['num'].",receiveJewels=receiveJewels+".$sendGetJewels['num'].",turntableGetMoney=turntableGetMoney+".$turntableMoney['money'].",turntableGetJewels=turntableGetJewels+".$turntableJewels['jewels'].",signGetMoney=signGetMoney+".$signMoney['money'].",signGetJewels=signGetJewels+".$signJewels['jewels'].",magicCostMoney=magicCostMoney+".$magic['cost']." where userID=$userID";
		$res = $this->db->add($sql);	
		if($res){
			echo '成功'.$userID.'<br>';
		}else{
			echo '失败'.$userID.'<br>';
		}	
	}
	/**
	 * 每个游戏的输赢以及对局数
	 * @DateTime 2017-11-16
	 * @return   [null]     [无返回]
	 */
	private function update_game_info($room){
		//对局数以及游戏输赢取金币变化表
		if(!$room) exit();
		foreach($room as $v){
			if($v['type'] == 0){
				$count = count($this->db->getAll("select id from statistics_gameRecordInfo where roomID=".$v['roomID']));
				$bunko = $this->db->getRow("select sum(changeMoney) as m from statistics_moneyChange where roomID=".$v['roomID']." and reason=14");
				$bunko = $bunko['m']?$bunko['m']:0;
				if($bunko > 0){
					$bunko = '-'.$bunko;
				}else{
					$bunko = abs($bunko);
				}
				$this->db->add("update web_room set count=".$count.",bunko=".$bunko." where roomID=".$v['roomID']);
			}
		}
	}
	#更新所有的房间
	private function update_room(){
		$sql = "select roomID,gameID,name,type from roomBaseInfo";
		$room = $this->db->getAll($sql);
		foreach($room as $v){
			$res = $this->db->getRow("select * from web_room where roomID=".$v['roomID']);
			if(!$res){
				$this->db->add("insert into web_room(roomID,gameID,name,type,bunko,count)values(".$v['roomID'].",".$v['gameID'].",'".$v['name']."',".$v['type'].",0,0)");
			}
		}
		$sql = "select * from web_room";
		$room = $this->db->getAll($sql);
		return $room;
	}
	/**
	 * 获取redis用户
	 * @DateTime 2017-11-15
	 * @return   [redisUser] [redis里面的用户]
	 */
	private function get_redis_user(){
		$prefix = 'userInfo|';
		$redisUser = $this->redis->redis->keys($prefix.'*');
		if(!$redisUser){
			echo 'redis里面无数据';die;
		}
		$arr = [];
		foreach($redisUser as $v){
			$userID = substr($v,9);
			$arr[] = $userID;
		}
		return $arr;
	}
	/**
	 * 获取数据库用户 
	 * @DateTime 2017-11-16
	 * @return   [type]     [description]
	 */
	private function get_sqlserver_user(){
		$sql = "select userID from web_users";
		$user = $this->db->getAll($sql);
		$arr = [];
		foreach($user as $v){
			$arr[] = $v['userID'];
		}
		return $arr;
	}

	public function run(){
		//$this->clear();
		//$this->update_data();
		//$room = $this->update_room();
		//$this->update_game_info($room);
		//echo "执行完毕!";
		$this->update_data();

	}
}

$run = new Clear($redis,$db);
$run->run();
?>
