<?php
namespace pay\ma_fu;

use helper\LogHelper;
use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

/**
 * 碼付宝支付
 * Class HuiFuBaoPay
 */
class MaFuPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['MA_FU'];

    private static $_instance = null;

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

    const PAY_URL = 'http://www.mafupay.cn/index/pay/make'; //网银支付接口地址
    const QUERY_URL = 'https://query.heepay.com/Payment/Query.aspx';

    const PAY_TYPE = 30;
    const GOODS_NOTE = '至尊网络';
    const REMARK = '至尊网络';
    const META_OPTION = '{"s":"WAP","n":"huomei","id":"http://ht.szhuomei.com"}';

    public function initPayConfig()
    {
        //调用父类的方法 初始化配置
        $payConfig = $this->getPayConfig(self::_TYPE);
        // TODO 特殊的实现
    }

    public function doPayment($goods, $userID, array $params = [])
    {
        //订单信息
        $agent_bill_time = $this->makeOrderTime();
        $agent_bill_id = $this->makeOrderID($userID);
        $pay_amt = $goods['consumeNum'] / 100;
        $goods_name = iconv('UTF-8', 'GB2312', $goods['buyGoods'] . '购买');

        //签名
        $sign_str = $this->sign_str($agent_bill_id, $pay_amt);

        //获取post数据
        $post_data = $this->post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign_str);
        LogHelper::printDebug('postData:' . json_encode($post_data, JSON_UNESCAPED_UNICODE));

        //请求第三方支付
//        $ret = $this->curl_pay($post_data);
        $ret = $this->phpPost(self::PAY_URL, $post_data);
        LogHelper::printDebug('curlResult:' . json_encode($ret, JSON_UNESCAPED_UNICODE));
        LogHelper::printDebug(var_export($ret, true));
        $ret = json_decode($ret, true);
        if ('success' === $ret['msg']) {
            //生成订单
            $this->createOrder($goods, $userID, $agent_bill_id);
            return $this->payResultSuccess(['recharge_url' => $ret['data']['qrcode']]);
        }
        return $this->payResultError();
    }
    private function phpPost($url, $data = array()) {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
//        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded')
        );
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;

    }
    private function curl_pay($post_data)
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, self::PAY_URL);
        //设置头文件的信息作为数据流输出
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        // {'jsonrpc':'2.0','method':'is_locked','params':[],'id':1}
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        LogHelper::printDebug('curlResult:' . json_encode($data, JSON_UNESCAPED_UNICODE));
        //显示获得的数据
        // preg_match('/{.*?}/ism', $data, $str);
        preg_match('/<html>.*?<a href=\"(.*?)\">.*?<\/a>.*?<\/html>/ism', $data, $str);
        $this->payLog(['preg_match $str=', $str]);
        return $str[1];
    }

    private function sign_str($agent_bill_id, $money, $pay_way = 1, $format = 1)
    {
        $join_data = array(
//            'version' => $this->_version,
//            'way_id' => $this->_user_ip,
            'key' => $this->_private_key,
        );
//        $join_data = array_merge($join_data, $this->getCommonData($agent_bill_id, $money, $format));
//        $sign_str = md5(FunctionHelper::array_convert_post_params($join_data));
        $join_data = $this->getCommonData($agent_bill_id, $money, $format);
        LogHelper::printDebug(var_export($join_data, true));
        $sign_str = $this->createSign($join_data, $this->_private_key);
        return $sign_str;
    }
    private function createSign($parameters, $key) {
        $signPars = '';
        ksort($parameters);
        foreach ($parameters as $k => $v) {
            if ('' != $v && 'sign' != $k) {
                $signPars .= $k . '=' . $v . '&';
            }
        }
        $signPars .= 'key=' . $key;
        $sign = md5($signPars);
        return $sign;
    }
    private function post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign)
    {
        $post_data = [
            'key' => $sign,
        ];
        $post_data = array_merge($this->getCommonData($agent_bill_id, $pay_amt), $post_data);
        return $post_data;
    }
    private function getCommonData($agent_bill_id, $money, $format = 1) {
        $data = [
            'uid' => $this->_mch_id,
            'money' => sprintf('%.2f', $money),
            'pay_way' => (string)1,
            'format' => (string)$format,
            'notify_url' => stripslashes($this->_notify_url),
            'return_url' => stripslashes($this->_return_url),
            'order_id' => $agent_bill_id,
            'way_id' => (string)0,
            'goodsname' => (string)'test goods name',
            'remark' => 'remark',
        ];
//        $data = array (
//            'uid' => '10548',
//            'money' => '0.01',
//            'notify_url' => 'http://zzyl.szhmkeji.com/logs/pay/notify.php',
//            'return_url' => 'http://zzyl.szhmkeji.com/logs/pay/result.php',
//            'order_id' => '2018849454',
//            'way_id' => '0',
//            'goodsname' => 'test_goods',
//            'pay_way' => '2',
//            'format' => '1',
////            'key' => '9cd54f9d023391ade26ec58a0a24a5c0',
//        );
//        ksort($data);
        return $data;
    }
}
