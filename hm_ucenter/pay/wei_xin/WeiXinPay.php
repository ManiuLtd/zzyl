<?php 
namespace pay\wei_xin;

use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;
use helper\LogHelper;

/**
 * 微信支付
 * Class WeiXinPay
 */
class WeiXinPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['WEI_XIN'];

    private static $_instance = null;

    const TRADE_TYPE = 'APP';

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    const PAY_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder'; //网银支付接口地址

    public function initPayConfig()
    {
        //调用父类的方法 初始化配置
        $payConfig = $this->getPayConfig(self::_TYPE);
        // TODO 特殊的实现

    }

    public function doPayment($goods, $userID, array $params = [])
    {
        $nonce_str = FunctionHelper::randomString();
        $body = '您确定要用' . sprintf("%.2f", $goods['consumeNum'] / 100) . '元购买' . $goods['buyNum'] . $goods['buyGoods'] . '吗?';
        $total_fee = (float)$goods['consumeNum'] / 100 * 100;

        $out_trade_no = $this->makeOrderID($userID);

        $array = $this->join_data($nonce_str, $body, $out_trade_no, $total_fee);
        $param = $array;

        $sign = $this->make_sign($array);

        $param['sign'] = $sign;

        $xml = FunctionHelper::arrayToXml($param);

        $response = $this->curl_pay($xml);
        $response = FunctionHelper::xmlToArray($response);

        if (!$response || $response['return_code'] != 'SUCCESS') {
            return $this->payResultError();
        }
        $this->createOrder($goods, $userID, $out_trade_no);
        $arr = array(
            'appid' => $response['appid'],
            'partnerid' => $this->_mch_id,
            'noncestr' => $response['nonce_str'],
            'timestamp' => time(),
            'package' => 'Sign=WXPay',
            'prepayid' => $response['prepay_id']
        );

        $result = array(
            'appId' => $response['appid'],
            'partnerId' => $this->_mch_id,
            'nonceStr' => $response['nonce_str'],
            'timeStamp' => (string)time(),
            'packageValue' => 'Sign=WXPay',
            'prepayId' => $response['prepay_id']
        );
        $result['order_sn'] = $out_trade_no;

        $appsign = $this->make_sign($arr);
        $result['sign'] = $appsign;
        return $this->payResultSuccess($result);
    }


    public function make_sign($arr)
    {
        ksort($arr);
        $arr['key'] = $this->_private_key;
        $string = FunctionHelper::array_convert_post_params($arr);
        $md5_string = md5($string);
        $sign = strtoupper($md5_string);
        return $sign;
    }

    /**
     * @param $nonce_str
     * @param $body
     * @param $out_trade_no
     * @param $total_fee
     * @return array
     */
    private function join_data($nonce_str, $body, $out_trade_no, $total_fee)
    {
        $array = array(
            'appid' => $this->_app_id,
            'mch_id' => $this->_mch_id,
            'nonce_str' => $nonce_str,
            'body' => $body,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'spbill_create_ip' => $this->_user_ip,
            'notify_url' => $this->_notify_url,
            'trade_type' => self::TRADE_TYPE,
        );
        return $array;
    }


    private function curl_pay($xml, $second = 30)
    {
        $this->payLog($xml);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, self::PAY_URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            LogHelper::printError([$this->_type, 'curl_pay', $error]);
            curl_close($ch);
            return false;
        }
    }
}
