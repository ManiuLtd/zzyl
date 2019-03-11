<?php
namespace helper;
use config\DynamicConfig;
use config\ErrorConfig;
use model\AppModel;
use config\EnumConfig;

/**
 * 常用函数 帮助类
 * Class FunctionHelper
 */
final class FunctionHelper
{
    const ENCODING_ARRAY = ['ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'];

    /**
     * 转换字符串编码
     * @param string $string
     * @param string $to_encode
     */
    public static function iconvStringEncode(&$string = '', $to_encode = 'UTF-8')
    {
        $encode = mb_detect_encoding($string, self::ENCODING_ARRAY);
        if ($encode != $to_encode) {
            $string = iconv($encode, $to_encode, $string);
        }
    }

    /**
     * 把数组值为字符串的编码并转为指定编码
     * @param array $array
     * @param string $to_encode
     */
    public static function iconvArrayEncode(&$array = [], $to_encode = 'UTF-8')
    {
        foreach ($array as &$value) {
            self::iconvEncode($value, $to_encode);
        }
    }

    /**
     * 转换编码
     * @param $data
     * @param string $to_encode
     */
    public static function iconvEncode(&$data, $to_encode = 'UTF-8')
    {
        if (empty($data)) {
            return;
        }
        if (is_array($data)) {
            self::iconvArrayEncode($data, $to_encode);
        } elseif (is_string($data)) {
            self::iconvStringEncode($data, $to_encode);
        }
    }


    /**
     * 多维数组array key 在 $intKeyArray中的转为int
     * @param array $array
     * @param array $intKeyArray 为空表示全转
     */
    public static function arrayValueToInt(&$array = [], $intKeyArray = [])
    {
        if (empty($array)) {
            return;
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                self::arrayValueToInt($value, $intKeyArray);
            } else {
                if (empty($intKeyArray) || in_array($key, $intKeyArray)) {
                    $array[$key] = (int)$value;
                }
            }
        }
    }

    /**
     * 多维数组array 在 $needKeyArray中的转为int
     * @param array $array
     * @param array $needKeyArray
     */
    public static function arrayNeedValueToInt(&$array = [], $needKeyArray = [])
    {
        if (empty($array) || empty($needKeyArray)) {
            return;
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                self::arrayNeedValueToInt($value, $needKeyArray);
            } else {
                if (isset($needKeyArray[$key])) {
                    if ($needKeyArray[$key] == 1) {
                        $array[$key] = (int)$value;
                    }
                } else {
                    unset($array[$key]);
                }
            }
        }
    }

    /**
     * 分割字符串返回数组
     * @param $str 'v1,v2,...|v1,v2,...|v1,v2,...|'
     * @param array {key1,key2,...} 不传时返回的数据 key为0开始
     * @param string 默认 | 不传即可 不建议使用特殊的符号如 % \
     * @param string 默认 , 不传即可 不建议使用特殊的符号如 % \
     * @return array { key1 = v1,key2 = v2,...}
     */
    public static function splitStringToArray($str, $keyList = [], $symbol1 = '|', $symbol2 = ',')
    {
        $splitArray = [];
        $strArray = explode($symbol1, $str);
        foreach ($strArray as $key1 => $value1) {
            if (!empty($value1)) {
                $data = [];
                $infoArray = explode($symbol2, $value1);
                foreach ($infoArray as $key2 => $value2) {
                    $key = empty($keyList[$key2]) ? $key2 : $keyList[$key2];
                    $data[$key] = (int)$value2;
                }
                $splitArray[] = $data;
            }
        }
        return $splitArray;
    }

    /**
     * 返回数组中指定多列
     * @param  Array $input 需要取出数组列的多维数组
     * @param  String $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
     * @param  String $index_key 作为返回数组的索引的列
     * @return Array
     */
    public static function array_columns($input, $column_keys = null, $index_key = null)
    {
        $result = array();
        $keys = isset($column_keys) ? explode(',', $column_keys) : array();
        if ($input) {
            foreach ($input as $k => $v) {
                // 指定返回列
                if ($keys) {
                    $tmp = array();
                    foreach ($keys as $key) {
                        $tmp[$key] = $v[$key];
                    }
                } else {
                    $tmp = $v;
                }
                // 指定索引列
                if (isset($index_key)) {
                    $result[$v[$index_key]] = $tmp;
                } else {
                    $result[] = $tmp;
                }
            }
        }
        return $result;
    }

    /**
     * 获取客户端IP地址
     * @return string
     */
    public static function get_client_ip()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'unknown';
        }
        return $ip;
    }

    /**
     * 把数组参数拼接成get请求string
     * @param $arrayData
     * @return string
     */
    public static function array_convert_get_params($arrayData)
    {
        if (empty($arrayData)) {
            return '';
        }
        $params = '?';
        foreach ($arrayData as $key => $value) {
            if ($params != '?') {
                $params .= '&';
            }
            $params .= "{$key}={$value}";
        }
        return $params;
    }

    /**
     * 把数组参数拼接成get请求string
     * @param $arrayData
     * @return string
     */
    public static function array_convert_post_params($arrayData)
    {
        if (empty($arrayData)) {
            return '';
        }
        $params = '';
        foreach ($arrayData as $key => $value) {
            if ($params != '') {
                $params .= '&';
            }
            $params .= "{$key}={$value}";
        }
        return $params;
    }

    /**
     * 生成随机数
     * @param int $length
     * @return string
     */
    public static function randomString($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 将xml转为array
     * @param  string $xml xml字符串或者xml文件名
     * @param  bool $is_file 传入的是否是xml文件名
     * @return array    转换得到的数组
     */
    public static function xmlToArray($xml = '', $is_file = false)
    {
        if ($xml == '') {
            return [];
        }
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        if ($is_file) {
            if (!file_exists($xml)) return false;
            $xml_str = file_get_contents($xml);
        } else {
            $xml_str = $xml;
        }
        $result = json_decode(json_encode(simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }

    /**
     * 数组转xml字符
     * @param $array
     * @return bool|string
     */
    public static function arrayToXml($array = [])
    {
        if (empty($array)) {
            return '';
        }
        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 毫秒级时间
     * @param string $format
     * @param null $u_timestamp
     * @return bool|string
     */
    public static function u_date($format = 'u', $u_timestamp = null)
    {
        if (is_null($u_timestamp)) {
            $u_timestamp = microtime(true);
        }

        $timestamp = floor($u_timestamp);

        $milliseconds = round(($u_timestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    /**
     * 过滤特殊字符
     * @param array $array
     */
    public static function arrayStringToDB(&$array = [])
    {
        if (empty($array)) {
            return;
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                self::arrayStringToDB($value);
            } else {
                if (is_string($value)) {
                    $value = addslashes($value);
                }
            }
        }
    }

    /**
     * 错误处理，写入错误日志
     * @param array $array
     * @logMsg mixed 如果是数组 [提示信息,其他]
     */
    public static function Err($errMsg, $httpCode, $code, $logMsg)
    {
        if (1 === DynamicConfig::DEBUG_LEVEL) {
            //抛出自定义异常
            throw new ExceptionHelper($errMsg, $httpCode, $code, $logMsg);
        } elseif (0 === DynamicConfig::DEBUG_LEVEL) {
            LogHelper::printError($logMsg);
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "喵~~，程序员小哥哥出差了");
        } elseif (2 === DynamicConfig::DEBUG_LEVEL) {
            LogHelper::printError($logMsg);
            if (is_array($logMsg)) {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, $logMsg[0]);
            } else {
                AppModel::returnJson(ErrorConfig::ERROR_CODE, $logMsg);
            }

        }
    }

    /**
     *输出金币
     * $sourceType 资源类型
     */
    public static function moneyOutput($money, $sourceType = EnumConfig::E_ResourceType['MONEY']) {
        $arrTimes = [
            EnumConfig::E_ResourceType['MONEY'] => 100,
            EnumConfig::E_ResourceType['JEWELS'] => 1,
            EnumConfig::E_ResourceType['BANKMONEY'] => 1,
            EnumConfig::E_ResourceType['FIRECOIN'] => 1,
            EnumConfig::E_ResourceType['RMB'] => 100,
        ];
        $times = $arrTimes[$sourceType];
        $money = (int)$money;
        if (!is_numeric($money) || !isset($arrTimes[$sourceType])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '金币格式错误或资源类型错误');
        }
        $floats = log($times, 10);
        return sprintf('%.' . $floats . 'f', $money/$times);
    }

    /**
     *输入金币
     */
    public static function moneyInput($money, $sourceType = EnumConfig::E_ResourceType['MONEY']) {
        $arrTimes = [
            EnumConfig::E_ResourceType['MONEY'] => 100,
            EnumConfig::E_ResourceType['JEWELS'] => 1,
            EnumConfig::E_ResourceType['BANKMONEY'] => 1,
            EnumConfig::E_ResourceType['FIRECOIN'] => 1,
            EnumConfig::E_ResourceType['RMB'] => 100,
        ];
        $times = $arrTimes[$sourceType];
//        $money = (int)$money;
        if (!is_numeric($money)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '金币格式错误:' . $money);
        }
        if (!isset($arrTimes[$sourceType])) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, '资源类型错误');
        }
        return $money * $times;
    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    public static function curlRequest($url,$arrParams=[],$method='get',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if('post' == $method) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($arrParams));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }

    public static function moneyInArrayOutput(&$dataSlave, $dataHandle) {
        array_walk($dataHandle,function(&$v,$k){$v = strtolower($v);});
        foreach ($dataSlave as $k => &$v) {
            foreach ($v as $kk => &$vv) {
                if (in_array(strtolower($kk), $dataHandle)) {
                    $vv = Self::MoneyOutput($vv);
                }
            }
        }
        return $dataSlave;
    }

    public static function moneyInArrayInput(&$dataSlave, $dataHandle) {
        array_walk($dataHandle,function(&$v,$k){$v = strtolower($v);});
        foreach ($dataSlave as $k => &$v) {
            foreach ($v as $kk => &$vv) {
                if (in_array(strtolower($kk), $dataHandle)) {
                    $vv = Self::MoneyInput($vv);
                }
            }
        }
        return $dataSlave;
    }

    public static function getServerHost() {
        return  $_SERVER['HTTP_HOST'];
    }

    public static function getWebProtocal() {
        return self::isHttps() ? 'https://' : 'http://';
    }

    public static function isHttps(){
        if(!isset($_SERVER['HTTPS'])) return FALSE;
        if($_SERVER['HTTPS'] === 1){ //Apache  
            return TRUE;
        }elseif($_SERVER['HTTPS'] === 'on'){ //IIS  
            return TRUE;
        }elseif($_SERVER['SERVER_PORT'] == 443){ //其他  
            return TRUE;
        }
            return FALSE;
    }

    /**
     *
     * @param $time
     * @return string
     */
    public static function friendTime($time) {
        if ($time <= 60) {
            return $time . '秒';
        } elseif ($time <=60 * 60) {
            return floor($time/60) . '分' . ($time%60) . '秒';
        } elseif ($time <=60 * 60 * 24) {
            return floor($time/60/60) . '小时' . floor($time%(60*60)/60) . '分' . ($time%(60)) . '秒';
        } else {
            return floor($time/(60*60*24)) . '天' . floor($time%(60*60*24)/(60*60)) .'小时' . floor($time%(60*60)/60) . '分' . ($time%(60)) . '秒';
        }
    }
}
