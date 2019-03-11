<?php
namespace pay\xin_bao;

use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

/**
 * 旺实富支付
 * Class WangShiFuPay
 */
class XinBaoPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['XIN_BAO'];

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

    const PAY_URL = 'http://47.75.113.83/Pay_Index.html';

    const PRODUCT_CODE_WX_ZZ_SM = 'WXZZWAPZZSM';
    const PRODUCT_CODE_ZFB_ZZ = 'ZFBZZWAP';

    const SIGN_TYPE = 1;

    public function initPayConfig()
    {
        //调用父类的方法 初始化配置
        return $this->getPayConfig(self::_TYPE);
        // TODO 特殊的实现
    }

    public function doPayment($goods, $userID, array $params = [])
    {
        //生成订单
//        $data['pay_applydate'] = "2018-09-21 17:22:09";
        $data['pay_applydate'] = date("Y-m-d H:i:s");
//        $data['pay_orderid'] = "20180921172209397262";
        $data['pay_orderid'] = $this->makeOrderID($userID);
        $pay_amount=$goods['consumeNum'] / 100;
        $data['pay_amount'] = $pay_amount;
//        $data['pay_amount'] = "0.1";

        $payWay = $params['payWay'];


        $desc = $this->_name;
        if ($payWay == EnumConfig::E_PayWay['WIN_XIN']) {
            $desc = $desc . "-微信";
            $data['pay_bankcode']="902";
        } elseif ($payWay == EnumConfig::E_PayWay['ZHI_FU_BAO']) {
            $desc = $desc . "-支付宝";
            $data['pay_bankcode'] = "904";
        }
        $data['pay_bankcode']="904";
        $result = $this->createOrder($goods, $userID, $data['pay_orderid'], $desc);
        if (empty($result)) {
            return $this->payResultError();
        }
        $payConfig=$this->initPayConfig();
        $data['pay_notifyurl']=$payConfig['notify_url'];
        $data['pay_callbackurl']=$payConfig['return_url'];

        //获取post数据
        $post_data = $this->post_data($data);
        //签名
        $post_data['pay_md5sign'] = $this->order_sign($post_data);
        //商品名称
        $post_data['pay_productname'] = $goods['buyGoods'];

        $post_data['pay_productnum'] = $goods['buyNum'];

        $post_data['pay_productdesc'] = $goods['buyGoods'] . 'x' .$goods['buyNum'];

        $this->payLog($post_data);
        $url="http://".$_SERVER["HTTP_HOST"]."/hm_ucenter/pay/xin_bao/index.php".FunctionHelper::array_convert_get_params($post_data);
        $this->payLog('-----------------------------xinbao-----------------------------------------:' . $url);
        return $this->payResultSuccess(['recharge_url' => $url]);
    }

    //  订单签名
    public function order_sign($native)
    {
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $this->_private_key));
        return $sign;
    }

    //  支付签名
    public function pay_sign($data)
    {
        $returnArray=array( // 返回字段
            "memberid" => $data["memberid"], // 商户ID
            "orderid" =>  $data["orderid"], // 订单号
            "amount" =>  $data["amount"], // 交易金额
            "datetime" =>  $data["datetime"], // 交易时间
            "returncode" => $data["returncode"],
        );
        $md5key =$this->_private_key;
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        return $sign;
    }


    public function post_data($data)
    {
        $post_data = array(
            "pay_memberid" => $this->_mch_id,
            "pay_orderid" => $data['pay_orderid'],
            "pay_amount" => $data['pay_amount'],
            "pay_applydate" => $data['pay_applydate'],
            "pay_bankcode" => $data['pay_bankcode'],
            "pay_notifyurl" => $data['pay_notifyurl'],
            "pay_callbackurl" => $data['pay_callbackurl'],
        );

        ksort($post_data);
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
