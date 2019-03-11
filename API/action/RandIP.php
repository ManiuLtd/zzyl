<?php 
//随机生成ip   
function rand_ip(){
 $ip_long = array(
  array('607649792', '608174079'), //36.56.0.0-36.63.255.255
  array('975044608', '977272831'), //58.30.0.0-58.63.255.255
  array('999751680', '999784447'), //59.151.0.0-59.151.127.255
  array('1019346944', '1019478015'), //60.194.0.0-60.195.255.255
  array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
  array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
  array('1947009024', '1947074559'), //116.13.0.0-116.13.255.255
  array('1987051520', '1988034559'), //118.112.0.0-118.126.255.255
  array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
  array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
  array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
  array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
  array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
  array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
  array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
 );
 $rand_key = mt_rand(0, 14);
 $huoduan_ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
 return $huoduan_ip;
}
$ip = rand_ip();
//根据ip获取经纬度
$key = "N57BZ-ELKKW-IHTRW-RI6XA-PHELK-AQBI4";
$get_ll_url = "http://apis.map.qq.com/ws/location/v1/ip?ip=$ip&key=$key";
$ll = file_get_contents($get_ll_url);
$ll = json_decode($ll,true);
if($ll['status'] != 0){
	$arr = ['ip'=>'','address'=>''];
	exit(return_json($arr));
}
$latitude = $ll['result']['location']['lat'];
$longitude = $ll['result']['location']['lng'];
//获取详细地址
$get_address_url = "http://apis.map.qq.com/ws/geocoder/v1/?location=$latitude,$longitude&key=$key";
$address = json_decode(file_get_contents($get_address_url),true);
$address = $address['result']['address'];
$arr = ['ip'=>$ip,'address'=>$address];
exit(return_json($arr));
?>