<?php

namespace pay\hui_fu_bao;

use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

/**
 * 汇付宝支付
 * Class HuiFuBaoPay
 */
class HuiFuBaoPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['HUI_FU_BAO'];

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

    const PAY_URL = 'https://pay.heepay.com/Payment/Index.aspx'; //网银支付接口地址
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
        $sign_str = $this->sign_str($agent_bill_id, $agent_bill_time, $pay_amt);

        //获取post数据
        $post_data = $this->post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign_str);

        //请求第三方支付
        $ret = $this->curl_pay($post_data);
        if ($ret) {
            //生成订单
            $this->createOrder($goods, $userID, $agent_bill_id);
            return $this->payResultSuccess(['recharge_url' => $ret]);
        }
        return $this->payResultError();
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
        //显示获得的数据
        // preg_match('/{.*?}/ism', $data, $str);
        preg_match('/<html>.*?<a href=\"(.*?)\">.*?<\/a>.*?<\/html>/ism', $data, $str);
        $this->payLog(['preg_match $str=', $str]);
        return $str[1];
    }

    private function sign_str($agent_bill_id, $agent_bill_time, $pay_amt)
    {
        $join_data = array(
            'version' => $this->_version,
            'agent_id' => $this->_mch_id,
            'agent_bill_id' => $agent_bill_id,
            'agent_bill_time' => $agent_bill_time,
            'pay_type' => self::PAY_TYPE,
            'pay_amt' => $pay_amt,
            'notify_url' => $this->_notify_url,
            'return_url' => $this->_return_url,
            'user_ip' => $this->_user_ip,
            'key' => $this->_private_key,
        );
        $sign_str = md5(FunctionHelper::array_convert_post_params($join_data));
        return $sign_str;
    }

    private function post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign)
    {
        $post_data = [
            'version' => $this->_version,
            'agent_id' => $this->_mch_id,
            'agent_bill_id' => $agent_bill_id,
            'agent_bill_time' => $agent_bill_time,
            'pay_type' => self::PAY_TYPE,
            'pay_code' => '',
            'pay_amt' => $pay_amt, // 金额
            'notify_url' => $this->_notify_url,
            'return_url' => $this->_return_url,
            'user_ip' => $this->_user_ip,
            'goods_name' => $goods_name,
            'goods_num' => 1,
            'goods_note' => self::GOODS_NOTE,
            'remark' => self::REMARK,
            'is_phone' => 1,
            'is_frame' => 0,
            'meta_option' => self::META_OPTION,
            'sign' => $sign,
        ];
        return $post_data;
    }
}
