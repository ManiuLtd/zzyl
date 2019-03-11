<?php
namespace helper;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 10:33
 */

final class RsaHelper {
    private static $PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXwIBAAKBgQDI2vGfLpd4I4Tc2bZ49H6slp2TCcLoycbXgqLmsG2WePMbr97I
XQr1f4Qla+VBHwgZe+qCvtG7awkZhniz6tskk4dClOn00beaTJfLvsTzg7gaAVfE
2kcfF5GwJADUyJi7oAgyttY8up6aPsY3HyAjBsNXtpa64w1vsgb4reaK/wIDAQAB
AoGBAIZwVe3wJ55YV+PjLYnETM2EerAihn1+IUO6wwKZ8KAZEM/lZb7C2ET+Nxs9
HtP5y1gTYyJkSsmhmROmuD/k4QxopJ8XfKwaz/pg8EHhdJqCj0x3pwO1wGgk3nRw
8z3+p8/1c2YxUXZ4G6orKgg7FdstayqziMI7ztaX2L6ioLhxAkEA6h9AUW9H7hfj
xRSJds8XI+fXi+n0tjbQRIX7VHLcdsWTgXjJTzQV4Rk1QPTJqt4I2Z2OZulTVssE
9WSjHHRhywJBANuf30VEMXkv75OH9TmN7zG74tk6EEMKTyTN0G4Sl6dJpXfjmnIs
1rCEUa5ps+wvw58oJLW6X5KaSttbw1ibhR0CQQC7QoHMYOi0nLEW2mVU/TEyC+dQ
xrFv6QnONSgglLqX2VIxNa42xp9Vazc4HFMNcwi7HpVe5PM+saXmrTb1tHTfAkEA
owwHZ5b+26dYx0AyL+5NW7DO8tIGbJ7krsAVMwKej9U/AMh6SrU7FYTGVyOmLXww
UKIuzrN0bJQbasqvNcMPiQJBALTGCR7pXUGWAFDWLFbVtxsLqM39i/qXha5fR+hh
/M5x7qSwCGANsFNhUSkcqAM9ZiOSKzSdot2eV0x1FFinMNw=
-----END RSA PRIVATE KEY-----
';
    private static $PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDI2vGfLpd4I4Tc2bZ49H6slp2T
CcLoycbXgqLmsG2WePMbr97IXQr1f4Qla+VBHwgZe+qCvtG7awkZhniz6tskk4dC
lOn00beaTJfLvsTzg7gaAVfE2kcfF5GwJADUyJi7oAgyttY8up6aPsY3HyAjBsNX
tpa64w1vsgb4reaK/wIDAQAB
-----END PUBLIC KEY-----
';
    private static $private_key_path = '';
    private static $public_key_path = '';


    public static function setPrivateKey($filePath = APP_ROOT_PATH . '\..\download\rsa_private_key.pem') {
        self::$private_key_path =  $filePath;
    }
    public static function setPublicKey($filePath = APP_ROOT_PATH . '\..\download\rsa_public_key.pem') {
        self::$public_key_path = $filePath;
    }
    /**
     * 获取私钥
     * @return bool|resource
     */
    private static function getPrivateKey()
    {
        if (!file_exists(self::$private_key_path) && empty(self::$PRIVATE_KEY)) {
            throw new Exception(self::$private_key_path . 'file not exists', 1);
        }
        self::$PRIVATE_KEY = file_exists(self::$private_key_path) ?file_get_contents(self::$private_key_path) :self::$PRIVATE_KEY;
        return openssl_pkey_get_private(self::$PRIVATE_KEY);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private static function getPublicKey()
    {
        if (!file_exists(self::$public_key_path) && empty(self::$PUBLIC_KEY)) {
            throw new Exception(self::$public_key_path . 'file not exists', 1);
        }
        self::$PUBLIC_KEY = file_exists(self::$public_key_path) ? file_get_contents(self::$public_key_path) : self::$PUBLIC_KEY;
        return openssl_pkey_get_public(self::$PUBLIC_KEY);
    }

    /**
     * 私钥加密
     * @param string $data
     * @return null|string
     */
    public static function privEncrypt($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_private_encrypt($data,$encrypted,self::getPrivateKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * 公钥加密
     * @param string $data
     * @return null|string
     */
    public static function publicEncrypt($data = '')
    {
        if (!is_string($data)) {
            throw new Exception('params expired to be a string');
            return null;
        }
        return openssl_public_encrypt($data,$encrypted,self::getPublicKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * 私钥解密
     * @param string $encrypted
     * @return null
     */
    public static function privDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey())) ? $decrypted : null;
    }

    /**
     * 公钥解密
     * @param string $encrypted
     * @return null
     */
    public static function publicDecrypt($encrypted = '')
    {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey())) ? $decrypted : null;
    }
}
