<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 11:02
 */

require_once dirname(__DIR__) . '/../helper/LoadHelper.php';

class testRsaHelper
{
    protected $str = '12345';
    public function __construct()
    {
        $this->testPublic();
    }

    public function testPublic() {
        try {
            //        echo RsaHelper::getPublicKey();exit;
//            RsaHelper::setPrivateKey();
//            RsaHelper::setPublicKey();
            $strEn = RsaHelper::publicEncrypt($this->str);
//            echo $strEn;
            $str = RsaHelper::privDecrypt($strEn);
            echo $str === $this->str;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testRsa() {

    }
}

new testRsaHelper();