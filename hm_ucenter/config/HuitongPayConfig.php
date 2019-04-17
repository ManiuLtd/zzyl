<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 11:20
 */
namespace config;

/**
 * 汇通支付相关配置
 */
final class HuitongPayConfig
{

    /**
     * 商户配置文件项
     */
    const MERCONFIG = array(
        "Version"     => "1.0",                                    //版本号
        "MerNo"     => "23049043",                                    //测试商户id
        "Md5Key"     => "6a3d9dea4c5febef329e7e4847296c11",    		  //测试商户密钥
        //请求地址
        "ReqUrl"      => "http://apipay.juheb2b.com/mer/api",
        //交易成功异步通知地；商户设置自己异步URL地址 商户设置自己异步URL地址 商户设置自己异步URL地址
        "NotifyUrl"      => "http://zzuls.szbchm.com/hm_ucenter/web/index.php?api=Pay&action=huitong_callback",
        "ReturnUrl" =>"",//同步通知URL
        "Charset"   => "UTF-8", //字符编码
        "SignMethod"=> "MD5",//签名类型
        "Url_Param_Connect_Flag"=>"&",//参数分隔符
        "ReqUrl_Show"  => "http://apipay.juheb2b.com/payshow/index", //收银台显示URL
    );

    //需做Base64加密的参数
    const BASE64KEYS = array("CodeUrl", "ImgUrl", "Token_Id","PayInfo","sPayUrl","PayUrl"
    ,"NotifyUrl","ReturnUrl");

    const REMOVEKEYS = array("SignMethod","Signature");



}
