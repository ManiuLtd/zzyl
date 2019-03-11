<?php 
if(isset($_REQUEST['Type']) && !empty($_REQUEST['Type'])){
	$Type = $_REQUEST['Type'];
	$RequestType = $_REQUEST['RequestType'];
	//Type为1表示请求苹果系统版本号 2为安卓
	if($Type == 1){
		$sql = "select * from web_version where packetType = 1 and platformType = 1";
	}elseif($Type == 2){
		$sql = "select * from web_version where packetType = 1 and platformType = 2";
	}elseif($Type == 3){
		$sql = "select * from web_version where packetType = 1 and platformType = 3";
	}
//	echo    1;die;
	$version = $db -> getRow($sql);
	if(!$version){
		$data = [
                        'version'       =>      '',
                        'packetaddress' =>      '',
                        'shenHeVersion'=>        '',
                ];
                exit(return_json($data));

	}
	if($RequestType == 1){
		if(!$version['version']){
echo '';exit();
}
		echo $version['version'];exit();
	}elseif($RequestType == 2){
	 if(!$version['packetaddress']){
echo '';exit();
	}
	echo $version['packetaddress'];exit();
	}elseif(!$RequestType){
	if(!$version['version']){
//echo $version['version'];
	$version['version'] = '';
}	

if(!$version['packetaddress']){
        $version['packetaddress'] = '';
} 

if(!$version['shenHeVersion']){
        $version['shenHeVersion'] = '';
}       
      

	$data = [
			'version'	=>	$version['version'],
			'packetaddress'	=>	$version['packetaddress'],
			'shenHeVersion'=>	$version['shenHeVersion'],
		];
		exit(return_json($data));
	}
	
}
echo '';exit();
 ?>
