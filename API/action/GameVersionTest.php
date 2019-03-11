<?php 
if(isset($_REQUEST['gameID']) && !empty($_REQUEST['gameID']) && isset($_REQUEST['RequestType']) && !empty($_REQUEST['RequestType'])){
	$gameID = $_REQUEST['gameID'];
	$RequestType = $_REQUEST['RequestType'];
	$json = file_get_contents('/usr/local/nginx/html/test_version.txt');
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
