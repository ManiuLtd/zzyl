<?php
namespace pay\wang_shi_fu;

use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

/**
 * 旺实富支付
 * Class WangShiFuPay
 */
class WangShiFuPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['WANG_SHI_FU'];

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

    const PAY_URL = 'http://order.paywap.cn/jh-web-order/order/receiveOrder';


    const PRODUCT_CODE_WX_ZZ_SM = 'WXZZWAPZZSM';
    const PRODUCT_CODE_ZFB_ZZ = 'ZFBZZWAP';

    const SIGN_TYPE = 1;

    public function initPayConfig()
    {
        //调用父类的方法 初始化配置
        $payConfig = $this->getPayConfig(self::_TYPE);
        // TODO 特殊的实现
    }

    public function doPayment($goods, $userID, array $params = [])
    {
        //生成订单
        $p6_ordertime = $this->makeOrderTime();
        $p2_ordernumber = $this->makeOrderID($userID);
        $p3_money = $goods['consumeNum'] / 100;
        $p25_terminal = 0;
        $p7_productcode = '';
        $deviceType = $params['deviceType'];
        $payWay = $params['payWay'];

        if ($deviceType == EnumConfig::E_DeviceType['WINDOWS']) {
            $p25_terminal = 1;
        } elseif ($deviceType == EnumConfig::E_DeviceType['IOS']) {
            $p25_terminal = 2;
        } elseif ($deviceType == EnumConfig::E_DeviceType['ANDROID']) {
            $p25_terminal = 3;
        }
        //必须写死3
        $p25_terminal = 3;
        $desc = $this->_name;
        if ($payWay == EnumConfig::E_PayWay['WIN_XIN']) {
            $p7_productcode = self::PRODUCT_CODE_WX_ZZ_SM;
            $desc = $desc . "-微信";
        } elseif ($payWay == EnumConfig::E_PayWay['ZHI_FU_BAO']) {
            $p7_productcode = self::PRODUCT_CODE_ZFB_ZZ;
            $desc = $desc . "-支付宝";
        }

        $result = $this->createOrder($goods, $userID, $p2_ordernumber, $desc);
        if (empty($result)) {
            return $this->payResultError();
        }

        //签名
        $p8_sign = $this->order_sign($p2_ordernumber, $p3_money, $p6_ordertime, $p7_productcode);

        //获取post数据
        $post_data = $this->post_data($p2_ordernumber, $p3_money, $p6_ordertime, $p7_productcode, $p8_sign, $p25_terminal);
        $url = self::PAY_URL . FunctionHelper::array_convert_get_params($post_data);
        return $this->payResultSuccess(['recharge_url' => $url]);
    }

    //  订单签名
    public function order_sign($p2_ordernumber, $p3_money, $p6_ordertime, $p7_productcode)
    {
        $p8_sign = md5($this->_app_id
            . '&' . $p2_ordernumber
            . '&' . $p3_money
            . '&' . $p6_ordertime
            . '&' . $p7_productcode
            . '&' . $this->_private_key);
        return $p8_sign;
    }

    //  支付签名
    public function pay_sign($data)
    {
        $p10_sign = strtoupper(md5($data['p1_yingyongnum'] . "&" .
            $data['p2_ordernumber'] . "&" .
            $data['p3_money'] . "&" .
            $data['p4_zfstate'] . "&" .
            $data['p5_orderid'] . "&" .
            $data['p6_productcode'] . "&" .
            $data['p7_bank_card_code'] . "&" .
            $data['p8_charset'] . "&" .
            $data['p9_signtype'] . "&" .
            $data['$p11_pdesc'] . "&" .
            $this->_private_key));
        return $p10_sign;
    }


    public function post_data($p2_ordernumber, $p3_money, $p6_ordertime, $p7_productcode, $p8_sign, $p25_terminal)
    {
        $post_data = [
            'p1_yingyongnum' => $this->_app_id,
            'p2_ordernumber' => $p2_ordernumber,
            'p3_money' => $p3_money,
            'p6_ordertime' => $p6_ordertime,
            'p7_productcode' => $p7_productcode,
            'p8_sign' => $p8_sign,
            'p9_signtype' => self::SIGN_TYPE,
            'p25_terminal' => $p25_terminal, //1 代表 pc     2 代表 ios    3 代表 android
        ];
        return $post_data;
    }


    public function curl_pay($data = array(), $type = 'POST')
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, self::PAY_URL);
        //设置头文件的信息作为数据流输出
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($type == "POST") {
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置post数据
            // {'jsonrpc':'2.0','method':'is_locked','params':[],'id':1}
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
}
