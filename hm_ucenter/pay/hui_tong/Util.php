<?php
namespace pay\hui_tong;
/**
 *接口所需类
 *
 */
class Util{

	/**
	 * 设置请求签名
	 *
	 * @param paramMap
	 */
	 public function  setSignature(&$params,$urlParamConnectFlag,$removeKeys,$md5key) {
		$signMethod = $params["SignMethod"];
	
		$signMsg = $this->getSignMsg($params,$urlParamConnectFlag,$removeKeys);
		$this->writelog("需签名报文末尾未加密钥串:".$signMsg);
		$signature = md5($signMsg.$md5key);
		$this->writelog("生成的签名:".$signature);
		$params["Signature"] = $signature;
	}
	
	/**
	 * 验证返回签名
	 *
	 * @param paramMap
	 * @return
	 */
	public function verifySign($params,$urlParamConnectFlag,$removeKeys,$md5key) {

		$signMsg = $this->getSignMsg($params,$urlParamConnectFlag,$removeKeys);
		$signature = $params["Signature"];
		$this->writelog("签名字符串未加加密串:".$signMsg);
		$signature_compute = md5($signMsg.$md5key);
		$this->writelog("生成的签名:".$signature_compute);
		if (strcmp($signature,$signature_compute) == 0){
			return true;
		}
		return false;
	}
	/**
	 * 对请求数据进行特殊处理 
	 */
	public function getWebForm($params,$base64Keys,$charset,$urlParamConnectFlag){

		//转换请求数据中特殊字段的值为Base64	
		$this->convertReqData($params,$base64Keys,$charset);
		$this->writelog("转换请求数据中特殊字段的值为Base64的报文:".$this->getURLParam($params,$urlParamConnectFlag,true,null));
		$sign_str = "";
		foreach ($params as $key =>$val){
			$sign_str .= sprintf ( "%s=%s&", $key, urlencode($val));
		}
		$reqMsg = substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );
		$this->writelog("Url编码后的请求报文:".$reqMsg);
		return $reqMsg;
	}
	
	public function parseResponse($respData,$base64Keys
			,$urlParamConnectFlag,$charset){
		$str = explode($urlParamConnectFlag,$respData);
		$ar = array();
		foreach ($str as $param){
			$param1 = explode("=",$param);
			$ar[$param1[0]] = urldecode($param1[1]);
		}
	
		$this->writelog("URL解码后返回数据:".$this->getURLParam($ar,$urlParamConnectFlag,true,null));
		
		$this->convertRespData($ar,$base64Keys);
		$this->writelog("转换返回数据中Base64的值:".$this->getURLParam($ar,$urlParamConnectFlag,true,null));
		return $ar;
	}
	

	/**
	 * 转换特殊请求字段
	 * @param paramMap
	 */
	public function convertReqData(&$params,$base64Keys,$charset) {
		foreach ($base64Keys as $bs64Key){
			if(isset($params[$bs64Key])){
				$params[$bs64Key] = base64_encode($params[$bs64Key]);
				$params[$bs64Key] = str_replace("+", "%2b",$params[$bs64Key]);
			}
		}
	}

	/**
	 * 转换特殊返回字段
	 * @param paramMap
	 */
	public function convertRespData(&$params,$base64Keys) {
		foreach ($base64Keys as $bs64Key){
			if(isset($params[$bs64Key])){
				$params[$bs64Key] = base64_decode($params[$bs64Key]);
				$params[$bs64Key] = str_replace("%2b", "+",$params[$bs64Key]);
			}
		}
	}
	/*
	 * 得到签名字符串
	 */
	public function getSignMsg($params,$urlParamConnectFlag,$removeKeys) {
		return $this->getURLParam($params,$urlParamConnectFlag, true, $removeKeys);
	}
	
	/*
	 * 
	 */
	public function getURLParam($params,$urlParamConnectFlag,$isSort,$removeKeys) {
		$useParams = null;
		foreach ($params as $key =>$val){
			if ($removeKeys != null && in_array($key,$removeKeys)){
				continue;
			}
			$useParams[$key] = $val;
		}
		//排序
		if ($isSort == true) {
			ksort($useParams);
		}
		//$util->writelog("排序:".'<br/>');
		//$util->writelog($useParams);
		$sign_str="";
		foreach ($useParams as $key =>$val){
			$sign_str .= sprintf ( "%s=%s&", $key, $val );
		}
		return substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );
	}

	/**
	
	 抛送数据方法
	*/
	
	public function postData($url, $data){     
	        $ch = curl_init();     
	        $timeout = 300;
			curl_setopt($ch,CURLOPT_HTTPHEADER,array(
	"content-type: application/x-www-form-urlencoded; 
	charset=UTF-8"
	));
	        curl_setopt($ch, CURLOPT_URL, $url);    
	        curl_setopt($ch, CURLOPT_REFERER, "http://localhost");   //站点  
	        curl_setopt($ch, CURLOPT_POST, true);     
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);     
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     
	        $handles = curl_exec($ch);     
	        curl_close($ch);     
	        return $handles;     
	    }

	    
	    public function writelog($str){
	    	$file = fopen(dirname(__FILE__)."/log.txt","a");
	    	fwrite($file,date('Y-m-d H:i:s')."   ".$str."\r\n");
	    	fclose($file);
	    	//print_r($str.'<br/><br/>');
	    }
	}

?>