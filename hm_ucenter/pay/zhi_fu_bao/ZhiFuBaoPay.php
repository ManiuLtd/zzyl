<?php
namespace pay\zhi_fu_bao;

use config\ErrorConfig;
use config\MysqlConfig;
use helper\LogHelper;
use manager\DBManager;
use model\AppModel;
use model\UserModel;
use pay\AppPay;
use config\EnumConfig;
use helper\FunctionHelper;

use pay\zhi_fu_bao\AlipayTradeAppPayRequest;
use pay\zhi_fu_bao\AopClient;

/**
 * 支付宝支付
 * Class WangShiFuPay
 */
class ZhiFuBaoPay extends AppPay
{
    private $orderInfo = [];//订单信息

    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['ZHI_FU_BAO'];

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

    const PAY_URL = 'https://openapi.alipay.com/gateway.do';

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
//        if ($payWay == EnumConfig::E_PayWay['WIN_XIN']) {
//            $desc = $desc . "-微信";
//            $data['pay_bankcode']="902";
//        } elseif ($payWay == EnumConfig::E_PayWay['ZHI_FU_BAO']) {
//            $desc = $desc . "-支付宝";
//            $data['pay_bankcode'] = "904";
//        }
        $desc = '-支付宝';
        $data['pay_bankcode']="904";
        $result = $this->createOrder($goods, $userID, $data['pay_orderid'], $desc);
        if (empty($result)) {
            return $this->payResultError();
        }
        $this->setOrderInfo($data['pay_orderid']);
        $payConfig=$this->initPayConfig();
        $data['pay_notifyurl']=$payConfig['notify_url'];
        $data['pay_callbackurl']=$payConfig['return_url'];

        //获取post数据
        $post_data = $this->post_data($data);
        //签名
        $orderStr = $this->getorderStrSign($post_data);
        //商品名称
//        $post_data['pay_productname'] = $goods['buyGoods'];
//
//        $post_data['pay_productnum'] = $goods['buyNum'];
//
//        $post_data['pay_productdesc'] = $goods['buyGoods'] . 'x' .$goods['buyNum'];

        $this->payLog($post_data);
        $this->payLog('-----------------------------zhifubao-----------------------------------------:' . $orderStr);
        return $this->payResultSuccess(['recharge_url' => '', 'orderStr' => base64_encode($orderStr)]);
    }

    protected function buildBizContent($data = []) {
        return json_encode([
            'subject' => $data['subject'],
            'out_trade_no' => $data['out_trade_no'],
            'total_amount' => sprintf('%.2f', $data['total_amount']),
            'product_code' => 'QUICK_MSECURITY_PAY',
        ], JSON_UNESCAPED_UNICODE);
    }

    protected function setOrderInfo($orderSn) {
        $where = UserModel::getInstance()->makeWhere(['order_sn' => $orderSn]);
        $orderInfo = DBManager::getMysql()->queryRow('select * from ' . MysqlConfig::Table_web_pay_orders . $where);
        if (EnumConfig::E_ResourceType['RMB'] != $orderInfo['consumeType']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '消费类型应为' . EnumConfig::E_ResourceTypeName[EnumConfig::E_ResourceType['RMB']]);
        }
        $this->orderInfo = [
            'subject' => '充值兑换',
            'out_trade_no' => $orderInfo['order_sn'],
            'total_amount' => sprintf('%.2f', FunctionHelper::moneyOutput($orderInfo['consumeNum'], EnumConfig::E_ResourceType['RMB'])),
        ];
    }

    //  订单签名
    protected function getorderStrSign($postData = [])
    {
        $request = new AlipayTradeAppPayRequest();

        $request->setNotifyUrl($this->_notify_url);
        $request->setBizContent ( $this->buildBizContent($this->orderInfo));


        $client = new AopClient();
        $client->appId = $this->getAppID();
        $client->rsaPrivateKey = $this->_private_key;
        $client->signType = 'RSA2';
        LogHelper::printDebug('AopClient----------------request:' . var_export($request, true));
        $res = $client->sdkExecute($request);
        return $res;
    }

    protected function getAppID() {
        return $this->_app_id;
    }

    public function checkAppID($appID) {
        if ($appID != $this->getAppID()) {
            return false;
        }
        return true;
    }

    public function checkParnetID($parnetID) {
        return $this->checkSellerID($parnetID);
    }

    public function checkSellerID($sellerID) {
        LogHelper::printDebug('seller_id:' . var_export($sellerID, true) . 'config_parnet_id:' . var_export($this->_parnetID, true));
        return $this->_parnetID == $sellerID;
    }

//    //  支付签名
//    public function pay_sign($data)
//    {
//        $returnArray=array( // 返回字段
//            "memberid" => $data["memberid"], // 商户ID
//            "orderid" =>  $data["orderid"], // 订单号
//            "amount" =>  $data["amount"], // 交易金额
//            "datetime" =>  $data["datetime"], // 交易时间
//            "returncode" => $data["returncode"],
//        );
//        $md5key =$this->_private_key;
//        ksort($returnArray);
//        reset($returnArray);
//        $md5str = "";
//        foreach ($returnArray as $key => $val) {
//            $md5str = $md5str . $key . "=" . $val . "&";
//        }
//        $sign = strtoupper(md5($md5str . "key=" . $md5key));
//        return $sign;
//    }


    public function post_data($data)
    {
        $post_data = array(
            "pay_memberid" => $this->_app_id,
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
