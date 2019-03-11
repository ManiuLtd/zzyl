<?php

class api
{
	private $pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCmE4aXk7kFMAJL1Yzg9TofAECi
WoYNfKiDUOkl4JqBavQibiSgeyhn1IcbcnGJqnLCnDz1QECO8JpucaI6rznyEw1J
YErS4fwUvQYezD4TvmsviGR1cgNLYrOX2JK/3lfm8u2f0AavMBW8j0tzd5WCFBfa
XY7AwftgwSSxOM4alQIDAQAB
-----END PUBLIC KEY-----';

private $pri = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCmE4aXk7kFMAJL1Yzg9TofAECiWoYNfKiDUOkl4JqBavQibiSg
eyhn1IcbcnGJqnLCnDz1QECO8JpucaI6rznyEw1JYErS4fwUvQYezD4TvmsviGR1
cgNLYrOX2JK/3lfm8u2f0AavMBW8j0tzd5WCFBfaXY7AwftgwSSxOM4alQIDAQAB
AoGAG4491MSj/GJc3yxNtb26qQ4nq0iN2YsHgtJX/PrpZ/PREi1oUmBc8e1nhXxV
rlvjsrqtupuwmob9eLnOCyKr4GQk5iw70x6aIEB6Ci0I0op/jCehE5P92MnwbqZL
rgSGSlfN3U4allCpFvRYMUsfq3C0rAiniZ85vWU29ZDxK0ECQQDVthhi7lgpFi+G
fEvKSl3UjINz/U3iaP7j5ujtMXG0Z9sH/wSW//S+nFZjjCLL731aKeh8FOvmCOUk
rprYyKp5AkEAxvBl1tiQIsMsfDeNP/BstnN96K3lAUdpROdRxMNICbl0cZb0lya8
cEOXGvVIZlEojIl+3lTQ+vGq9LymLKJp/QJBAMUx2R6wPrjqt7+oQWbPZ/UQEtuc
B5m6uDcighsbXIfSrNCcB2gtlW+sYipIzHLQ8SGZOeQEigcgfg9y7X7K6kECQDPG
HLrtYZWqdcleK8SYLbLOG9aSaycKDrt/+CfdKsJdIZDuWHl9+0y8SncZnt4CASuS
HRJ/wGOOwY8wCY9UNYECQGNybzyemk6QiMo6+zj2Poegfqk6LDFG3ZePHmA2L7bD
JBbX4DTHr82flmNDjivLm8TEIT+sbfqKfDhilK5ppF4=
-----END RSA PRIVATE KEY-----';

	// sign
    public function data($data)
    {
        $uuid   = $data['uuid'];
        $action = $data['action'];
        unset($data['uuid'], $data['action'], $data['sign']);

        $order = [];
        foreach ($data as $k => $v) {
            $order[] = ord($k{0});
        }

        array_multisort($order, SORT_ASC, $data);
        $action = http_build_query($data) . '&uuid=' . $uuid . '&action=' . $action;
       // echo $action;die;
        $sign = $this->rsa_encode($action);

        if ($sign) {
            return $sign;
        }

        return false;
    }

    // rsa 签名
    public function rsa_encode($pwd)
    {
        //$publicstr = file_get_contents('prikey.pem');
        $publicstr =$this->pri;
        $publickey = openssl_pkey_get_private($publicstr); // 读取私钥

        $r = openssl_sign($pwd, $sign, $publickey, OPENSSL_ALGO_SHA1);
        if ($r) {
			//return $sign;
            return base64_encode($sign);
        }
        return false;
    }

    // rsa 验签
    public function rsa_decode($str,$sign)
    {
        //$publicstr = file_get_contents('pubkey.pem');
        $publicstr = $this->pubkey;
        $publickey = openssl_pkey_get_public($publicstr); // 读取私钥

        $r = openssl_verify($str, base64_decode($sign), $publickey, OPENSSL_ALGO_SHA1);
        if ($r) {
            return true;
        }
        return false;
    }

	    // 参数拼接
    public function splicingAction($data)
    {
        $uuid   = $data['uuid'];
        $action = $data['action'];
        unset($data['uuid'], $data['action'], $data['sign']);

        $sort = [];
        foreach ($data as $k => $v) {
            $sort[] = ord($k{0});
        }

        array_multisort($sort, SORT_ASC, $data);
        $action = http_build_query($data) . '&uuid=' . $uuid . '&action=' . $action;
        return $action;
    }

}
//http://192.168.1.100/hm_ucenter/web/index.php?r=hall&action=emailInfo&emailID=1234&uuid=123456&timestamp=123456&sign=123456
$data = ['gameID'=>30100006,'r'=>'hall','timestamp'=>1528979397,'userID'=>118097,'uuid'=>'A1D62-DAB28-80Z59-ACW87-1ETD9','action'=>'simpleGradeList'];
$api  = new api();
$key = $api->data($data);
echo $key;die;
var_dump($api->rsa_encode($d));die;
var_dump($api->rsa_decode($d, $key));
