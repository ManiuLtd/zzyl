<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID']) && isset($_REQUEST['RequestType']) && !empty($_REQUEST['RequestType'])){
	$gameID = $_REQUEST['gameID'];
	$RequestType = $_REQUEST['RequestType'];
       
       /*	$redis = GetRedis::get();
	$sql = "select version,packetaddress from web_version where packetType = 2 and gameid = $gameID";
	if($RequestType == 1){
		$version = $redis->redis->get('game_version_'.$gameID);
		if(!$version){
			$v = $db -> getRow($sql);
			$version = $version['version'];
		}

		echo $version;
	} else {
		$version = $redis->redis->get('game_version_packetaddress'.$gameID);
		if(!$version){
			$v = $db -> getRow($sql);
			$version = $version['packetaddress'];
		}

		echo $version;
	}*/
	$json = file_get_contents('/usr/local/nginx/html/version.txt');
	$arr = json_decode($json,true);
	foreach($arr as $v){
 		if($v['gameid'] == $gameID){
 			$version = $RequestType == 1 ? $v['version'] : $v['packetaddress'];
 		}
 	}

       echo $version;
}
echo '';
exit();
 ?>
