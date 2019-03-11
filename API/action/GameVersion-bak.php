<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID']) && isset($_REQUEST['RequestType']) && !empty($_REQUEST['RequestType'])){
	$gameID = $_REQUEST['gameID'];
	$RequestType = $_REQUEST['RequestType'];
	$redis = GetRedis::get();

	if($RequestType == 1){
		$v = $redis->redis->get('game_version_'.$gameID);
	}elseif($RequestType == 2){
		$v = $redis->redis->get('game_version_packetaddress'.$gameID);
	}

	if(!$v){
		$sql = "select version,packetaddress from web_version where packetType = 2 and gameid = $gameID";
		$version = $db -> getRow($sql);
		if($RequestType == 1){
			$redis->redis->set('game_version_'.$gameID,$version['version']);
			echo $version['version'];
		}elseif($RequestType == 2){
			$redis->redis->set('game_version_packetaddress'.$gameID,$version['packetaddress']);
			echo $version['packetaddress'];
		}


		
	} else {
	/*
		$sql = "select version,packetaddress from web_version where packetType = 2 and gameid = $gameID";
		$version = $db -> getRow($sql);
		if(!$version){
			echo '';exit();
		}

		if($RequestType == 1){			
			echo $version['version'];exit();
		}elseif($RequestType == 2){
			echo $version['packetaddress'];exit();
		}
		*/
	
		if($RequestType == 1){
			echo $redis->redis->get('game_version_'.$gameID);
		}elseif($RequestType == 2){
			echo $redis->redis->get('game_version_packetaddress'.$gameID);
		}
	}
	
	
}
echo '';
exit();
 ?>