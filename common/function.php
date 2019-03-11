<?php
/**
 * 获取客户端IP地址
 * @return string
 */
function get_client_ip() { 
    if(getenv('HTTP_CLIENT_IP')){ 
        $client_ip = getenv('HTTP_CLIENT_IP'); 
    } elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
        $client_ip = getenv('HTTP_X_FORWARDED_FOR'); 
    } elseif(getenv('REMOTE_ADDR')) {
        $client_ip = getenv('REMOTE_ADDR'); 
    } else {
        $client_ip = $_SERVER['REMOTE_ADDR'];
    } 
    return $client_ip; 
}   

/**
* 获取服务器端IP地址
 * @return string
 */
function get_server_ip() { 
    if (isset($_SERVER)) { 
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR']; 
        } else { 
            $server_ip = $_SERVER['LOCAL_ADDR']; 
        } 
    } else { 
        $server_ip = getenv('SERVER_ADDR');
    } 
    return $server_ip; 
}

/**
* json 中文转码
* @date: 2017年1月4日 下午3:10:54
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function decodeUnicode($str)
{
	return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
			create_function(
					'$matches',
					'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
			),
			$str);
}

/**
* 函数用途描述
* @date: 2017年1月4日 下午3:13:59
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function return_json($data){
	return decodeUnicode(json_encode($data,JSON_UNESCAPED_SLASHES));
}

/**
* 输出json
* @date: 2017年1月4日 下午3:17:21
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function print_json($data){
	exit(return_json($data));
}

/**
* 获取POST 数据
* @date: 2017年1月4日 下午3:18:25
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function m_post($key,$val=''){
	if(isset($_POST[$key]) && trim($_POST[$key])!='')
		return $_POST[$key];
	else
		return $val;
}

/**
* 获取GET 数据
* @date: 2017年1月4日 下午3:18:25
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function m_get($key,$val=''){
	if(isset($_GET[$key]) && trim($_GET[$key])!='')
		return $_GET[$key];
	else
		return $val;
}

/**
* 获取REQUEST 数据
* @date: 2017年1月4日 下午3:18:25
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function m_request($key,$val=''){
	if(isset($_REQUEST[$key]) && trim($_REQUEST[$key])!='')
		return $_REQUEST[$key];
	else
		return $val;
}


/**
* 通过id 获取图片
* @date: 2017年2月23日 下午6:23:59
* @author: CUI
* @param: $GLOBALS
* @return:
*/
function get_cover($id){
	global $db;
	$sql = "SELECT [id],[path] FROM Web_Files WHERE id={$id}";
	$res = $db->getRow($sql);
	if(isset($res['path'])){
		return $res['path'];
	}else{
		return '/Public/static/no.jpg';
	}
}




//生成32位随机数
function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}


//格式化参数成url参数
 function ToUrlParams($arr){
	$buff='';
	foreach($arr as $k=>$v){
		if($k != 'sign' && $v != '' && !is_array($v)){
			$buff.=$k.'='.$v.'&';
		}
	}
	$buff = trim($buff,'&');
	return $buff;
}
//
//生成签名
 function MakeSign($arr,$key){
	ksort($arr);
	$string = ToUrlParams($arr);
	$string = $string."&key=".$key;
	$string = md5($string);
	$result = strtoupper($string);
	return $result;
}


/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	function postXmlCurl($xml,$url,$second=30)
	{		
        //初始化curl        
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
        $data = curl_exec($ch);
		//返回结果
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else 
		{ 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}

//将数组转为xml
	function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }

//将xml转为数组
function xmlToArray($xml)
	{		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}



function write_log(){
	date_default_timezone_set('PRC');
	$log = '['.date("Y-m-d H:i:s",time()).'] RequestIp:'.get_client_ip().' RequestMethod: '.$_SERVER['REQUEST_METHOD']." Data:".json_encode($_REQUEST)."\r\n";
	file_put_contents('./log/haotian-'.date('Y-m-d').'.log', $log,FILE_APPEND);
}

function write_header_log(){
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '未知机型';
        $log = '['.date("Y-m-d H:i:s",time()).']'."ip:".get_client_ip()." ua:".$ua." Data:".json_encode($_REQUEST)."\r\n";
        file_put_contents('./log/ua-'.date('Y-m-d').'.log', $log,FILE_APPEND);
}


function pay_success($order_sn){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://47.106.144.97/index/API/update?order_sn={$order_sn}&merchant_id=100002&sign=c7cebd4a6e29cb59b0d679025d124702");
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
}

