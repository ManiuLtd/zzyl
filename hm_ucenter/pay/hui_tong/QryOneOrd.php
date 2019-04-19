<?php
namespace pay\hui_tong;

use config\HuitongPayConfig;

/*
 * 交易订单查询
 * 交易码 210101-机构支付查询交易
 */
class QryOneOrd
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    private function __clone()
    {
    }

    public function getOrderInfo($order_sn){
        $merconfig = HuitongPayConfig::MERCONFIG;
        $removeKeys = HuitongPayConfig::REMOVEKEYS;
        $base64Keys = HuitongPayConfig::BASE64KEYS;

        $util = new Util();
        $util->writelog("==========支付查询交易=============");
        //==设置请求参数
        $req["Version"] =$merconfig["Version"];
        $req["SignMethod"] =$merconfig["SignMethod"];
        //交易码 支付查询交易
        $req["TxCode"] ="qrypayord";
        $req["MerNo"] =$merconfig["MerNo"];
        //商户交易流水号(唯一)
        $req["TxSN"] = $order_sn;

        //==设置请求签名
        $util->setSignature($req,$merconfig["Url_Param_Connect_Flag"]
            ,$removeKeys,$merconfig["Md5Key"]);

        //==得到请求数据
        $post_data = $util->getWebForm($req, $base64Keys, $merconfig["Charset"]
            ,$merconfig["Url_Param_Connect_Flag"]);

        //==提交数据
        $respMsg = $util->postData($merconfig["ReqUrl"],$post_data);
        $util->writelog("返回数据:".$respMsg);

        //==解析返回数据为数组
        $respAr = $util->parseResponse($respMsg,$base64Keys
            ,$merconfig["Url_Param_Connect_Flag"], $merconfig["Charset"]);
        //==验签数据
        if ($util->verifySign($respAr, $merconfig["Url_Param_Connect_Flag"], $removeKeys
            , $merconfig["Md5Key"])){
            $util->writelog("验证签名成功:");
            $util->writelog("查询成功");
            $util->writelog("==========支付查询交易   结束=============");
            return $respAr;
        }
        else{
            $util->writelog("验证签名失败!!!");
            return ['RspCod' => -10000, 'RspMsg' => '验证签名失败!!!'];
        }
    }

}
?>