<?php
namespace pay\hui_tong;

use pay\AppPay;
use config\HuitongPayConfig;

class HuiTongPay extends AppPay
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
        //生成订单号
        $order_sn = $this->makeOrderID($userID);
        $pay_amount = $goods['consumeNum'] / 100;  //支付金额元
        $desc = '-汇通支付';
        $result = $this->createOrder($goods, $userID, $order_sn, $desc);
        if (empty($result)) {
            return $this->payResultError();
        }

        $payUrl = $this->initpaycs($order_sn, $pay_amount * 100);

        $this->payLog('-----------------------------huitong跳转链接-----------------------------------------:' . $payUrl);
        return $this->payResultSuccess(['recharge_url' => '', 'orderStr' => base64_encode($payUrl)]);
    }

    /*
     *$param numbert $order_sn  订单号
     * $param int $pay_amount_branch  支付金额分
     * */
    public function initpaycs($order_sn, $pay_amount_branch)
    {
        $merconfig = HuitongPayConfig::MERCONFIG;
        $removeKeys = HuitongPayConfig::REMOVEKEYS;
        $base64Keys = HuitongPayConfig::BASE64KEYS;
        $util = new Util();
        $util->writelog("==========收银台置单=============");
        //==设置请求参数
        $req["Version"] = $merconfig["Version"];
        $req["SignMethod"] = $merconfig["SignMethod"];
        //交易码
        $req["TxCode"] = "setupord";
        $req["MerNo"] = $merconfig["MerNo"];
        //商户交易流水号(唯一)
        $req["TxSN"] = $order_sn;
        //金额:单位:分
        $req["Amount"] = $pay_amount_branch;
        //商品名称
        $req["PdtName"] = "虚拟金币";
        //备注
        $req["Remark"] = "测试";
        //同步通知URL
        //$req["ReturnUrl"] =$merconfig["ReturnUrl"];
        //异步通知URL
        $req["NotifyUrl"] = $merconfig["NotifyUrl"];
        //请求时间 格式:YmdHis
        $req["ReqTime"] = date('YmdHis', time());
        //订单有效时间，单位分钟，默认24小时,值可不传
        //$req["TimeoutExpress"] ="1440";

        //==设置请求签名
        $util->setSignature($req, $merconfig["Url_Param_Connect_Flag"]
            , $removeKeys, $merconfig["Md5Key"]);

        //==得到请求数据
        $post_data = $util->getWebForm($req, $base64Keys, $merconfig["Charset"]
            , $merconfig["Url_Param_Connect_Flag"]);

        //==提交数据
        $respMsg = $util->postData($merconfig["ReqUrl"], $post_data);
        $this->payLog('-----------------------------huitong-----------------------------------------:返回数据' . json_encode($respMsg));
        $util->writelog("返回数据:" . $respMsg);

        //==解析返回数据为数组
        $respAr = $util->parseResponse($respMsg, $base64Keys
            , $merconfig["Url_Param_Connect_Flag"], $merconfig["Charset"]);
        $this->payLog('-----------------------------huitong-----------------------------------------:解析返回数据为数组' . json_encode($respAr));
        //==验签数据
        if ($util->verifySign($respAr, $merconfig["Url_Param_Connect_Flag"], $removeKeys
            , $merconfig["Md5Key"])) {
            $util->writelog("验证签名成功:");
            if (strcmp($respAr['RspCod'], "00000") == 0
                && strcmp($respAr['Status'], "1") == 0
                && isset($respAr['Token'])) {
                $util->writelog("置单成功 获取token成功");
                //收银台URL 拼接
                $payUrl = $this->buildPayUrl($merconfig, $removeKeys, $base64Keys, $respAr['Token'], null, null);
                if (empty($payUrl)) {
                    $this->payLog('-----------------------------huitong-----------------拼接收银台URL 失败!!!------------------------');
                    $util->writelog("拼接收银台URL 失败");
                } else {
                    $util->writelog("支付链接：" . $payUrl);
                    return $payUrl;
                    //header("Location:" . $payUrl);//需要把$util->writelog中print_r屏蔽
                }
            } else {
                //失败
                $this->payLog('-----------------------------huitong-----------------受理失败!!!------------------------');
                $util->writelog("受理失败!!!");
            }
        } else {
            $this->payLog('-----------------------------huitong-----------------验证签名失败!!!------------------------');
            $util->writelog("验证签名失败!!!");
        }

        $util->writelog("==========收银台置单  处理结束=============");
    }


    /**
     * ====收银台URL 拼接====
     * 商户自己定制收银台,用户选择支付后，直接跳转入支付工具，在不同的支付环境支持如下
     * ProductId 说明如下
     * 手机端
     * 0601 微信扫码
     * 0602 支付宝扫码
     * 0614 H5快捷支付
     * 0615 H5银联在线
     * 0621 H5微信APP支付
     * 0622 H5支付宝APP支付
     * PC端
     * 0601 微信扫码
     * 0602 支付宝扫码
     * 0614 H5快捷支付
     * 0615 H5银联在线
     * 0611 B2C网银跳银行   还需传入DirectBankId 参考文档列表
     * 0612 B2C网银跳收银台
     */
    public function buildPayUrl($merconfig, $removeKeys, $base64Keys, $token, $productId, $directBankId)
    {
        $util = new Util();
        if (empty($token)) {
            $util->writelog("支付token为空");
            return null;
        }
        $build["Version"] = $merconfig["Version"];
        $build["SignMethod"] = $merconfig["SignMethod"];
        $build["MerNo"] = $merconfig["MerNo"];
        $build["Token"] = $token;
        //操作收银台的有效时间，到期后不可操作  格式:yyyyMMddHHmmss 不传：默认:请求支付链接10分钟后关闭
        //加5分钟
        $build["ExpireTime"] = date('YmdHis', time() + 5 * 60);
        //用户ID  请填写系统平台真实用户ID,如果没有用随机数代替
        $build["UserId"] = "00001";
        //用户标识类型:USEID:用户 IDPHONE:用户手机号 ID_CARD:用户身份证号
        $build["UserIdType"] = "USEID";
        if (!empty($productId)) {
            $build["ProductId"] = $productId;
            if($productId == "0611"){
                if (empty($directBankId)) {
                    $util->writelog("网银跳银行必须传直连银行编码");
                    return null;
                } else {
                    $build["DirectBankId"] = $directBankId;
                }
            }
            /*if (strcmp($productId, "0611")) {
                if (empty($directBankId)) {
                    $util->writelog("网银跳银行必须传直连银行编码");
                    var_dump($build);exit;
                    return null;
                } else {
                    $build["DirectBankId"] = $directBankId;
                }
            }*/
        }

        //==设置请求签名
        $util->setSignature($build, $merconfig["Url_Param_Connect_Flag"]
            , $removeKeys, $merconfig["Md5Key"]);
        //组合支付数据
        $get_data = $util->getWebForm($build, $base64Keys, $merconfig["Charset"]
            , $merconfig["Url_Param_Connect_Flag"]);
        //生成支付URL
        $url = $merconfig["ReqUrl_Show"] . "?" . $get_data;

        $util->writelog("支付Url:" . $url);
        return $url;
    }
}
