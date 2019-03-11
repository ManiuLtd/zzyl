<?php
namespace pay\hui_fu_bao_zl;

use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

/**
 * 汇付宝直连支付
 * Class HuiFuBaoZLPay
 */
class HuiFuBaoZLPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['HUI_FU_BAO_ZL'];

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

    const ZL_PAY_URL = 'https://Pay.Heepay.com/DirectPay/applypay.aspx';
    const ZL_QUERY_URL = 'https://jhpay.heemoney.com/DirectPay/query.aspx';
    const SCENE = 'h5';

    const PAY_TYPE_WX = 30;
    const PAY_TYPE_ZFB = 22;
    const REMARK = 'hfb';
    //TODO 每个项目需要修改参数 n和id
    const META_OPTION = '{"s":"WAP","n":"baidu","id":"https://www.baidu.com"}';
    const BANK_CARD_TYPE = -1;

    private $pay_type = self::PAY_TYPE_WX;

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

        $deviceType = $params['deviceType'];
        $payWay = $params['payWay'];
        $desc = $this->_name;
        if ($payWay == EnumConfig::E_PayWay['WIN_XIN']) {
            $this->pay_type = self::PAY_TYPE_WX;
            $desc = $desc . "-微信";
        } elseif ($payWay == EnumConfig::E_PayWay['ZHI_FU_BAO']) {
            $this->pay_type = self::PAY_TYPE_ZFB;
            $desc = $desc . "-支付宝";
        }

        //签名
        $sign_str = $this->sign_str($agent_bill_id, $agent_bill_time, $pay_amt);

        //获取post数据
        $post_data = $this->post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign_str);
        //请求第三方支付
        $ret = $this->curl_pay($post_data);
        if ($ret) {
            //生成订单
            $this->createOrder($goods, $userID, $agent_bill_id, $desc);
            return $this->payResultSuccess(['recharge_url' => $ret]);
        }
        return $this->payResultError();
    }

    private function curl_pay($post_data)
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, self::ZL_PAY_URL);
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
        //将xml格式转为数组
        $result = FunctionHelper::xmlToArray($data);
        $this->payLog(['huifubao curl_pay $result=', $result]);
        return empty($result) ? null : $result['redirectUrl'];
    }

    private function sign_str($agent_bill_id, $agent_bill_time, $pay_amt)
    {
        $join_data = array(
            'version' => $this->_version,
            'agent_id' => $this->_mch_id,
            'agent_bill_id' => $agent_bill_id,
            'agent_bill_time' => $agent_bill_time,
            'pay_type' => $this->pay_type,
            'pay_amt' => $pay_amt,
            'notify_url' => $this->_notify_url,
            'return_url' => $this->_return_url,
            'user_ip' => $this->_user_ip,
            'bank_card_type' => self::BANK_CARD_TYPE,
            'remark' => self::REMARK,
            'key' => $this->_private_key,
        );
        $sign_str = strtolower(md5(FunctionHelper::array_convert_post_params($join_data)));
        return $sign_str;
    }

    private function post_data($agent_bill_id, $agent_bill_time, $pay_amt, $goods_name, $sign)
    {
        $post_data1 = [
            'version' => $this->_version,
            'scene' => self::SCENE,
            'pay_type' => $this->pay_type,
            'agent_id' => $this->_mch_id,
            'agent_bill_id' => $agent_bill_id,
            'pay_amt' => $pay_amt, // 金额
            'notify_url' => $this->_notify_url,
            'return_url' => $this->_return_url,
            'user_ip' => $this->_user_ip,
            'agent_bill_time' => $agent_bill_time,
            'goods_name' => $goods_name,
            'remark' => self::REMARK,
            'meta_option' => base64_encode(self::META_OPTION)  ,
            'bank_card_type' => self::BANK_CARD_TYPE,
            'sign' => $sign,
        ];
        return $post_data1;
    }

    public function pay_sign($payData)
    {
        $join_data = array(
            'result' => $payData['result'],
            'agent_id' => $this->_mch_id,
            'jnet_bill_no' => $payData['jnet_bill_no'],
            'agent_bill_id' => $payData['agent_bill_id'],
            'pay_type' => $payData['pay_type'],
            'pay_amt' => $payData['pay_amt'],
            'remark' => self::REMARK,
            'key' => $this->_private_key,
        );
        $sign_str = strtolower(md5(FunctionHelper::array_convert_post_params($join_data)));
        return $sign_str;
    }
}
