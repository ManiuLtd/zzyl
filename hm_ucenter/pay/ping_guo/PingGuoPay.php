<?php 
namespace pay\ping_guo;

use pay\AppPay;
use config\EnumConfig;

/**
 * 苹果支付
 * Class PingGuoPay
 */
class PingGuoPay extends AppPay
{
    //支付类型 每个支付都不一样
    const _TYPE = EnumConfig::E_PayType['PING_GUO'];

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

    public function initPayConfig()
    {
        //调用父类的方法 初始化配置
        $payConfig = $this->getPayConfig(self::_TYPE);
        // TODO 特殊的实现
    }

    public function doPayment($goods, $userID, array $params = [])
    {
        $out_trade_no = $this->makeOrderID($userID);
        $result = [
            'order_sn' => $out_trade_no,
            'appleID' => $goods['appleID'],
        ];
        return $this->payResultSuccess($result);
    }
}
