<?php
/*
 * 参数提交页面
 */
	
	define('PAY_URL', 'https://pay.heepay.com/Payment/Index.aspx'); //网银支付接口地址
	define('QUERY_URL', 'https://query.heepay.com/Payment/Query.aspx');
	define('AGENT_ID', '2112482');
	define('SIGN_KEY', 'C53514AFB85F443BAE134E90');
	define('PAY_TYPE',30);

class Pay{
	
	public $agent_bill_id,$agent_bill_time,$pay_amt,$goods_name,$notify_url;
	public $version=1,$return_url = "http://",$meta_option = '{"s":"WAP","n":"huomei","id":"http://ht.szhuomei.com"}';

	// $version = 1;
	// $agent_id = AGENT_ID;
	// 
	// $agent_bill_id = date('YmdHis', time()); // 订单号
	//$agent_bill_time = date('YmdHis', time());
    // $pay_type = 30; //微信支付代码,int型
	// $pay_code = ""; //char型，空字符串
	// $pay_amt = '0.12'; // 金额
	
	//微信支付不涉及同步返回，此处可填写任意URL，没有实际使用
	
	// $goods_num = 1;
	// $goods_note = '测试';
	// $remark = '测试'; // 备注
	// $wxpay_type = '测试'; // 支付说明
    // $sign_key = SIGN_KEY; //签名密钥，需要商户使用为自己的真实KEY


	//获取用户IP
	public function getIp(){
		$user_ip = "";
		if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else
		{
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}

		return $user_ip;
	}

    public function sign_str(){
		$sign_str = '';
		$sign_str  = $sign_str . 'version=' . $this->version;
		$sign_str  = $sign_str . '&agent_id=' . AGENT_ID;
		$sign_str  = $sign_str . '&agent_bill_id=' . $this->agent_bill_id;
		$sign_str  = $sign_str . '&agent_bill_time=' . $this->agent_bill_time;
		$sign_str  = $sign_str . '&pay_type=' . PAY_TYPE;
		$sign_str  = $sign_str . '&pay_amt=' . $this->pay_amt;
		$sign_str  = $sign_str .  '&notify_url=' . $this->notify_url;
		$sign_str  = $sign_str . '&return_url=' . $this->return_url;
		$sign_str  = $sign_str . '&user_ip=' . $this->getIp();
		$sign_str = $sign_str . '&key=' . SIGN_KEY;
		
		$sign = md5($sign_str); //签名值
		return $sign;
	}


	public function data(){

		$post_data = [
			'version' => $this->version,
			'agent_id' => AGENT_ID,
			'agent_bill_id' => $this->agent_bill_id,
			'agent_bill_time' => $this->agent_bill_time,
			'pay_type' => PAY_TYPE,
			'pay_code' => '',
			'pay_amt' => $this->pay_amt, // 金额
			'notify_url' => $this->notify_url,
			'return_url' => $this->return_url,
			'user_ip' => $this->getIp(),
			'goods_name' => $this->goods_name,
			'goods_num' => 1,
			'goods_note' => '至尊网络',
			'remark' => '至尊网络',
			'is_phone' => 1,
			'is_frame' => 0,
			'meta_option' => '{"s":"WAP","n":"huomei","id":"http://ht.szhuomei.com"}',
			'sign' => $this->sign_str(),
		];

		return $post_data;
	}

	public function curl(){
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, PAY_URL);
		//设置头文件的信息作为数据流输出
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//设置post方式提交
		curl_setopt($curl, CURLOPT_POST, 1);
		//设置post数据
		// {'jsonrpc':'2.0','method':'is_locked','params':[],'id':1}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data());
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		// preg_match('/{.*?}/ism', $data, $str);
		preg_match('/<html>.*?<a href=\"(.*?)\">.*?<\/a>.*?<\/html>/ism',$data,$str);
		return $str[1];
	}
}

// $pay = new Pay();
// $pay->agent_bill_id = date('YmdHis', time());
// $pay->agent_bill_time = date('YmdHis', time());
// $pay->pay_amt = '0.13';
// $pay->goods_name = '测试';
// $pay->notify_url = 'http://'.$_SERVER['HTTP_HOST']."/notify.php";
// echo $pay->curl();
// echo '<pre>';
// print_r($pay->data());
