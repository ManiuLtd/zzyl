<?php
namespace pay\hui_tong;

use pay\AppPay;
use config\EnumConfig;
use model\PayModel;
use config\HuitongPayConfig;

/*
 * 230000-机构支付异步通知回调
 */
class Notify extends AppPay
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

    public function initPayConfig()
    {
    }

    public function doPayment($goods, $userID, array $params = [])
    {
    }

    public function notify_callback(){
        $merconfig = HuitongPayConfig::MERCONFIG;
        $removeKeys = HuitongPayConfig::REMOVEKEYS;

        $util = new Util ();
        $util->writelog("===========异步通知========");
        $Params = null;
        foreach ( $_REQUEST as $key => $val ) {
            $Params [$key] = urldecode ( $val );
        }
        $util->writelog ("异步通知参数:" .$util->getURLParam($Params,$merconfig["Url_Param_Connect_Flag"],true,null));
        $this->payLog('-----------------------------huitong----------------:异步通知参数' . $util->getURLParam($Params,$merconfig["Url_Param_Connect_Flag"],true,null));
        //==验签数据
        if ($util->verifySign($Params, $merconfig["Url_Param_Connect_Flag"], $removeKeys
            , $merconfig["Md5Key"])){
            $util->writelog("异步通知验证签名成功!");
            $this->payLog('-----------------------------huitong----------------:异步通知验证签名成功!');
            if (strcmp($Params['Status'],"1") == 0){
                //成功
                $util->writelog("支付成功,业务处理!!!订单号:".$Params['TxSN']
                    .'支付状态:'.$Params['Status']
                    .'支付金额:'.$Params['Amount']
                    .'元潮支付流水号:'.$Params['PlatTxSN']
                );
                $this->payLog("------------------huitong-------支付成功,业务处理!!!订单号:".$Params['TxSN']
                    .'支付状态:'.$Params['Status']
                    .'支付金额:'.$Params['Amount']
                    .'元潮支付流水号:'.$Params['PlatTxSN']);
                //更改订单状态添加相关数据
                $resupdatepay = $this->updata_order($Params['TxSN']);
                if($resupdatepay['code'] != 200){
                    $this->payLog('----------------huitong----------:'.$resupdatepay['msg']);
                    echo $resupdatepay['msg'];
                }else{
                    $this->payLog('----------------huitong----------:'.$resupdatepay['msg']);
                    echo "success";
                }
            }
            else {
                //失败
                $util->writelog("支付失败,业务处理!!!");
                $this->payLog('-----------------------------huitong----------------:支付失败,业务处理!!!');
            }
        }
        else {
            $util->writelog("异步通知验证失败:");
            $this->payLog('-----------------------------huitong----------------:异步通知验证失败');

        }
        //处理完成后一定返回,不然定时再次发送异步通知
    }

    public function updata_order($order_sn){
        if (!isset($order_sn)) {
            return ['code' => -1, 'msg' => '订单号不能为空'];
        }

        $order = PayModel::getInstance()->getPayOrder($order_sn);
        if (empty($order)) {
            return ['code' => -1, 'msg' => '不存在该订单'];
        }

        //订单状态是否NEW
        if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
            return ['code' => -1, 'msg' => '订单状态异常'];
        }

        //先写订单状态 再发货
        $updateStatus = PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
        if (!empty($updateStatus)) {
            //充值增加玩家资源
            $userID = $order['userID'];
            $resourceType = $order['buyType'];
            $change = $order['buyNum'];
            $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
            //如果发货失败需要补单
            if (!$ret) {
                \helper\LogHelper::printError('充值成功，但订单缺货，需补发' . var_export($_POST, true));
                $this->payLog('-----------------------------huitong----------------:充值成功，但订单缺货，需补发');
                PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
            }
            //插入redis通知消息
            /*$redis = \manager\RedisManager::getRedis();
            $redis->lPush("orderNotify",$order_sn);*/
            \helper\LogHelper::pushSpeech();
            return ['code' => 200, 'msg' => '充值成功'];
        } else {
            \helper\LogHelper::printError('充值成功，但订单更新失败' . var_export($_POST, true));
            return ['code' => -1, 'msg' => '充值成功，但订单更新失败'];
        }
    }


}


?>