<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use config\MysqlConfig;
use helper\LogHelper;
use model\AppModel;
use model\PayModel;
use model\UserModel;
use pay\hui_fu_bao\HuiFuBaoPay;
use pay\hui_fu_bao_zl\HuiFuBaoZLPay;
use pay\jian_fu\JianFuPay;
use pay\ping_guo\PingGuoPay;
use pay\wang_shi_fu\WangShiFuPay;
use pay\wei_xin\WeiXinPay;
use pay\xin_bao\XinBaoPay;
use pay\ma_fu\MaFuPay;
use pay\zhi_fu_bao\ZhiFuBaoPay;
use pay\hui_tong\HuiTongPay;
use pay\hui_tong\Notify;

/**
 * 支付业务
 * Class PayAction
 */
class PayAction extends AppAction
{
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

    /**
     * 获取商城商品信息
     * @param $params
     */
    public function goodsList($params)
    {
        $goodsType = (int)$params['goodsType'];//1.金币 2.钻石 3.道具 4.实物
        $goodsList = PayModel::getInstance()->getGoodsList($goodsType);
        if (empty($goodsList)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_pay_goods . "没有配置类型为[{$goodsType}]的数据！ ");
        }
        foreach ($goodsList as &$goods) {
            $goods['consumeNum'] = $goods['consumeNum'] / 100;
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $goodsList);
    }

    /**
     * 获得订单列表
     * @param $params
     */
    public function ordersList($params)
    {
        $userID = (int)$params['userID'];
        $ordersList = PayModel::getInstance()->getPayOrdersList($userID);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $ordersList);
    }

    public function checkPingGuoOrder($params)
    {
        $receipt = (int)$params['status'];    //苹果支付状态
        $order_sn = $params['order_sn'];        //订单号

        $order = PayModel::getInstance()->getPayOrder($order_sn);
        if (empty($order)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "订单不存在");
        }

        if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "订单已经处理");
        }

        if ($receipt == 1) {
            $ret = PayModel::getInstance()->changeUserResource($order['userID'], $order['buyType'], $order['buyNum'], EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
            if ($ret) {
                PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
            } else {
                PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
            }
        } else {
            PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['PAY_FAIL']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    protected function verifyThirdPay($params) {
        $userID = (int)$params['userID'];
//        if (empty($userID)) {
//                return ['code' => ErrorConfig::ERROR_CODE, 'msg' => 'userID 不能为空'];
//            }
        if (!UserModel::getInstance()->isUserExists($userID)) {
            return ['code' => ErrorConfig::ERROR_CODE, 'msg' => '用户不存在'];
        }
    }

    /**
     * 汇通支付
     * @param $params
     */
    public function huitongPay($params)
    {
        if (empty($params['userID']) || empty($params['goodsID'])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_NOT_PARAMETER);
        }
        $res = $this->verifyThirdPay($params);
        if (ErrorConfig::ERROR_CODE == $res['code']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, $res['msg']);
        }

        $pay_result = $this->doPayPrepare(10, $params);
        LogHelper::printDebug(['payresult' => $pay_result, 'type' => 10, 'params' => $params]);
        if ($pay_result['status'] == ErrorConfig::SUCCESS_CODE) {
            $pay_result['data']['pay_type'] = 10;
            $this->returnPayResult($pay_result);
        }

        AppModel::returnJson(ErrorConfig::ERROR_CODE, "支付失败");
        $this->returnPayResult($pay_result);
    }

    /**
     * 汇通支付  回调通知
     * @param $params
     */
    public function huitong_callback()
    {
        $result = file_get_contents('php://input');
        LogHelper::printLog('PAY', '回调返回参数111'.$result);
        LogHelper::printLog('PAY', '回调返回参数222'.json_encode($result));
        $callbackObj = Notify::getInstance();
        $callbackObj->notify_callback();
        /*$callbackObj = Notify::getInstance();
        $callbackObj->updata_order('20190417134701811605122005');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);*/

    }

    /**
     * 第三方支付
     * @param $params
     */
        public function thirdPay($params)
    {
        $res = $this->verifyThirdPay($params);
        if (ErrorConfig::ERROR_CODE == $res['code']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, $res['msg']);
        }
        $payTypeList = PayModel::getInstance()->getPayTypeList(EnumConfig::E_PayTypeStatus['OPEN'], EnumConfig::E_PayThird['IS']);
        if (empty($payTypeList)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "没有可用的支付方式");
        }
        foreach ($payTypeList as $pay_type) {
            $pay_result = $this->doPayPrepare($pay_type['type'], $params);
            LogHelper::printDebug(['payresult' => $pay_result, 'type' => $pay_type, 'params' => $params]);
            if ($pay_result['status'] == ErrorConfig::SUCCESS_CODE) {
                $pay_result['data']['pay_type'] = $pay_type['type'];
                $this->returnPayResult($pay_result);
            }
        }
        AppModel::returnJson(ErrorConfig::ERROR_CODE, "支付失败");
        $this->returnPayResult($pay_result);
    }

    public function weiXinPay($params)
    {
        $pay_result = $this->weiXinPayResult($params);
        $this->returnPayResult($pay_result);
    }

    public function huiFuBaoPay($params)
    {
        $pay_result = $this->huiFuBaoPayResult($params);
        $this->returnPayResult($pay_result);
    }

    public function pingGuoPay($params)
    {
        $pay_result = $this->pingGuoPayResult($params);
        $this->returnPayResult($pay_result);
    }

    public function wangShiFuWXPay($params)
    {
        $pay_result = $this->wangShiFuWXPayResult($params);
        $this->returnPayResult($pay_result);
    }

    public function wangShiFuZFBPay($params)
    {
        $pay_result = $this->wangShiFuZFBPayResult($params);
        $this->returnPayResult($pay_result);
    }

    private function returnPayResult($pay_result)
    {
        AppModel::returnJson($pay_result['status'], $pay_result['msg'], $pay_result['data']);
    }

    private function doPayPrepare($pay_type, $params)
    {
        $pay_result = [
            'status' => ErrorConfig::ERROR_CODE,
            'msg' => '',
            'data' => [],
        ];
        switch ($pay_type) {
            case EnumConfig::E_PayType['WEI_XIN']:
                $pay_result = $this->weiXinPayResult($params);
                break;
            case EnumConfig::E_PayType['HUI_FU_BAO']:
                $pay_result = $this->huiFuBaoPayResult($params);
                break;
            case EnumConfig::E_PayType['PING_GUO']:
                $pay_result = $this->pingGuoPayResult($params);
                break;
            case EnumConfig::E_PayType['WANG_SHI_FU']:
                $pay_result = $this->wangShiFuZFBPayResult($params);
                break;
            case EnumConfig::E_PayType['JIAN_FU']:
                $pay_result = $this->jainFuPayResult($params);
                break;
            case EnumConfig::E_PayType['HUI_FU_BAO_ZL']:
                $pay_result = $this->huiFuBaoZLPayResult($params);
                break;
            case EnumConfig::E_PayType['XIN_BAO']:
                $pay_result = $this->xinBaoResult($params);
                break;
            case EnumConfig::E_PayType['MA_FU']:
                $pay_result = $this->maFuResult($params);
                break;
            case EnumConfig::E_PayType['ZHI_FU_BAO']:
                $pay_result = $this->doPay($params, ZhiFuBaoPay::getInstance());
                break;
            case EnumConfig::E_PayType['HUI_TONG']:
                $pay_result = $this->doPay($params, HuiTongPay::getInstance());
                break;
            default:
                AppModel::returnJson(ErrorConfig::ERROR_CODE, "sdk不存在");
                break;

        }
        return $pay_result;
    }

    private function doPay($params, $payClass)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['WIN_XIN']];
        $pay_result = $payClass->doPayment($goods, $userID, $array);
        return $pay_result;
    }

    private function wangShiFuWXPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['WIN_XIN']];
        $pay_result = WangShiFuPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }
    private function jainFuPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['JIAN_FU']];
        $pay_result = JianFuPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }

    private function huiFuBaoZLPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['HUI_FU_BAO_ZL']];
        $pay_result = HuiFuBaoZLPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }
    private function xinBaoResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['XIN_BAO']];
        $pay_result = XinBaoPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }
    private function maFuResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['MA_FU']];
        $pay_result = MaFuPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }

    private function wangShiFuZFBPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        $deviceType = (int)$params['deviceType'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $array = ['deviceType' => $deviceType, 'payWay' => EnumConfig::E_PayWay['ZHI_FU_BAO']];
        $pay_result = WangShiFuPay::getInstance()->doPayment($goods, $userID, $array);
        return $pay_result;
    }

    private function pingGuoPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $pay_result = PingGuoPay::getInstance()->doPayment($goods, $userID);
        return $pay_result;
    }

    private function weiXinPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $pay_result = WeiXinPay::getInstance()->doPayment($goods, $userID);
        return $pay_result;
    }

    private function huiFuBaoPayResult($params)
    {
        $goodsID = (int)$params['goodsID'];
        $userID = (int)$params['userID'];
        //获取购买商品信息
        $goods = PayModel::getInstance()->getGoodsId($goodsID);
        if (empty($goods)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_ORDER);
        }
        $pay_result = HuiFuBaoPay::getInstance()->doPayment($goods, $userID);
        return $pay_result;
    }
}
