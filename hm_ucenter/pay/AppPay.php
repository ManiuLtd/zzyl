<?php
namespace pay;
use config\ErrorConfig;
use helper\FunctionHelper;
use helper\LogHelper;
use model\PayModel;

/**
 * 通用支付类
 * Class AppPay
 */
abstract class AppPay
{
    //支付中心地址
    const PAY_CENTER_URL = 'http://47.106.144.97/index/API/add';
    //每个项目唯一ID
    const MERCHANT_ID = 100002;
    //每个项目唯一KEY
    const MERCHANT_KEY = 'c7cebd4a6e29cb59b0d679025d124702';

    //日志标签名字
    const LOG_TAG_NAME = 'PAY';

    protected $_user_ip = 'unknown';

    //支付类型
    protected $_type = 0;
    //支付名字
    protected $_name = '';
    //应用id
    protected $_app_id = '';
    //商户id
    protected $_mch_id = '';
    //秘钥
    protected $_private_key = '';
    //状态
    protected $_status = 0;
    //公钥
    protected $_public_key = '';
    //支付回调地址
    protected $_notify_url = '';
    //成功返回页面
    protected $_return_url = '';
    //版本号
    protected $_version = '1';
    //回调服务器公钥,用于回调时签名校验
    protected $_callback_public_key = '';
    //合作伙伴身份
    protected $_parnetID = '';

    protected function __construct()
    {
        $this->_user_ip = FunctionHelper::get_client_ip();
        //初始化支付配置
        $this->initPayConfig();
    }

    /**
     * 初始化子类
     * @return mixed
     */
    abstract protected function initPayConfig();

    /**
     * 开始支付
     * @param $goods
     * @param $userID
     * @param array $params
     * @return payResult
     */
    abstract protected function doPayment($goods, $userID, array $params = []);

    /**
     * 支付返回结果统一这种形式
     * @param int $status
     * @param string $msg
     * @param array $data
     * @return array
     */
    private function payResult($status, $msg = '', $data = [])
    {
        $pay_result = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
        ];
        return $pay_result;
    }

    /**
     * 成功返回
     * @param array $data
     * @return array
     */
    protected function payResultSuccess($data = [])
    {
        return $this->payResult(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $data);
    }

    /**
     * 失败返回
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function payResultError($msg = '订单创建失败', $data = [])
    {
        return $this->payResult(ErrorConfig::ERROR_CODE, $msg, $data);
    }

    /**
     * 根据支付的类型得到支付配置
     * @param $pay_type
     * @return array|mixed
     */
    protected function getPayConfig($pay_type)
    {
        $payConfig = PayModel::getInstance()->getPayConfig($pay_type);
        if (empty($payConfig)) {
            LogHelper::printError("获取类型为{$pay_type}的配置失败");
            return [];
        }
        $this->_type = $payConfig['type'];
        $this->_name = $payConfig['name'];
        $this->_app_id = $payConfig['app_id'];
        $this->_mch_id = $payConfig['mch_id'];
        $this->_private_key = $payConfig['private_key'];
        $this->_status = $payConfig['status'];
        $this->_public_key = $payConfig['public_key'];
        $this->_notify_url = $payConfig['notify_url'];
        $this->_return_url = $payConfig['return_url'];
        $this->_version = $payConfig['version'];
        $this->_callback_public_key = $payConfig['callback_server_PK'];
        $this->_parnetID = $payConfig['parnetID'];
        return $payConfig;
    }

    /**
     * 生成订单时间
     * @return bool|string
     */
    protected function makeOrderTime()
    {
        $order_time = date("YmdHis", time());
        return $order_time;
    }

    /**
     * 生成订单ID
     * @param $userID
     * @return string
     */
    protected function makeOrderID($userID)
    {
        //毫秒
        $order_time = FunctionHelper::u_date('YmdHisu');
        $order_sn = $order_time . $userID;
        return $order_sn;
    }

    /**
     * 创建订单
     * @param $goods
     * @param $userID
     * @param string $order_sn
     * @param string $desc
     * @return mixed
     */
    protected function createOrder($goods, $userID, $order_sn = '', $desc = '')
    {
        if ($order_sn == '') {
            $order_sn = $this->makeOrderID($userID);
        }

        if ($desc == '') {
            $desc = $this->_name;
        }
        
        return PayModel::getInstance()->addOrder($goods, $userID, $order_sn, $desc);
    }

    /**
     * 获取订单信息
     * @param $order_sn
     * @return array|mixed
     */
    protected function getOrder($order_sn)
    {
        return PayModel::getInstance()->getPayOrder($order_sn);
    }

    /**
     * 发送信息到支付中心
     * @param $order_sn
     * @param $consume_num
     */
    public function sendOrderToPayCenter($order_sn, $consume_num)
    {
        $arrayData = array(
            'order_sn' => $order_sn,
            'merchant_id' => self::MERCHANT_ID,
            'sign' => self::MERCHANT_KEY,
            'buy_type' => 1,
            'consume_num' => $consume_num,
            'status' => 0,
        );
        $url = self::PAY_CENTER_URL . FunctionHelper::array_convert_get_params($arrayData);
        $this->payLog($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $this->payLog($result);
    }

    /**
     * 支付日志
     * @param $msg
     */
    protected function payLog($msg)
    {
        LogHelper::printLog(self::LOG_TAG_NAME, $msg);
    }
}
