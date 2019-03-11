<?php
namespace helper;
use config\ErrorConfig;
use manager\DBManager;
use manager\RedisManager;
use manager\SocketManager;
use model\AppModel;

/**
 * 日志 帮助类
 * Class LogHelper
 */
final class LogHelper
{
    const LOG_LEVEL_NO = 0;
    const LOG_LEVEL_LOG = 1;
    const LOG_LEVEL_INFO = 2;
    const LOG_LEVEL_WARN = 4;
    const LOG_LEVEL_DEBUG = 8;
    const LOG_LEVEL_ERROR = 16;
    //以上或运算得到
    const LOG_LEVEL_ALL = 31;
    // 日志等级
    const LOG_LEVEL = self::LOG_LEVEL_ALL;

    const TAG_INFO = 'INFO';
    const TAG_WARN = 'WARM';
    const TAG_DEBUG = 'DEBUG';
    const TAG_ERROR = 'ERROR';
    const TAG_ERRORS = 'ERRORS';
    const TAG_EXCEPTION = 'EXCEPTION';
    const TAG_COST = 'COST';
    const TAG_COST_MORE = 'COST_MORE';
    const TAG_SHUTDOWN = 'SHUTDOWN';
    const WEB_ = 'WEB_';
    const TASK_ = 'TASK_';
    const APP_ = 'APP_';

    const MEMORY_LIMIT_ON = false;
    const COST_MORE_MS = 300;

    private static $marker = array(
        'time' => array(),
        'mem' => array(),
        'peak' => array(),
    );

    private static $_isRegisterHandler = false;

    /**
     * 某个等级的日志是否打开
     * @param $level
     * @return bool
     */
    private static function isLogOpen($level)
    {
        return (self::LOG_LEVEL & $level) == $level;
    }

    /**
     * 写日志
     * @param $tag
     * @param $string
     */
    public static function writeLog($tag, $string)
    {
        if (defined('PLAT_UUID')) {
            $tag = self::APP_ . $tag;
        } elseif (defined('TASK_UUID')) {
            $tag = self::TASK_ . $tag;
        } else {
            $tag = self::WEB_ . $tag;
        }
        $DOCUMENT_ROOT = dirname(dirname(__DIR__));
        $pathName = "{$DOCUMENT_ROOT}/logs/{$tag}/";
        $fileName = date('Y-m-d', time()) . '.log';
        //文件夹不存在 需要创建
        if (!file_exists($pathName)) {
            mkdir($pathName, 0715, true);
            chmod($pathName, 0715);
        }
        $path = $pathName . $fileName;
        //精确到毫秒
        $time = FunctionHelper::u_date('Y-m-d H:i:s.u');
        if (is_array($string)) {
            $string = json_encode($string, JSON_UNESCAPED_UNICODE);
        }
        $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN_REMOTE_ADDR';
        $content = "[{$tag}][{$time}][$remoteAddr]:{$string}" . PHP_EOL;

        //追加写入打开文件
        $file = fopen($path, "a") or die("Unable to open file！ path = {$path}!");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * 打印自定义标签的日志信息
     * @param $tag
     * @param $string
     */
    public static function printLog($tag, $string)
    {
        //不能使用系统定义的tag
        if ($tag == self::TAG_INFO || $tag == self::TAG_WARN || $tag == self::TAG_DEBUG || $tag == self::TAG_ERROR) {
            return;
        }
        if (!self::isLogOpen(self::LOG_LEVEL_LOG)) {
            return;
        }
        self::writeLog($tag, $string);
    }

    /**
     * 打印一般日志信息
     * @param $string
     */
    public static function printInfo($string)
    {
        if (!self::isLogOpen(self::LOG_LEVEL_INFO)) {
            return;
        }
        self::writeLog(self::TAG_INFO, $string);
    }

    /**
     * 打印警告日志信息
     * @param $string
     */
    public static function printWarning($string)
    {
        if (!self::isLogOpen(self::LOG_LEVEL_WARN)) {
            return;
        }
        self::writeLog(self::TAG_WARN, $string);
    }

    /**
     * 打印调试日志信息
     * @param $string
     */
    public static function printDebug($string)
    {
        if (!self::isLogOpen(self::LOG_LEVEL_DEBUG)) {
            return;
        }
        self::writeLog(self::TAG_DEBUG, $string);
    }

    /**
     * 打印错误日志信息
     * @param $string
     */
    public static function printError($string)
    {
        if (!self::isLogOpen(self::LOG_LEVEL_ERROR)) {
            return;
        }
        self::writeLog(self::TAG_ERROR, $string);
    }

    /**
     * 标记名字
     * @param $name
     */
    public static function mark($name)
    {
        $name = is_array($name) ? json_encode($name) : $name;
        self::$marker['time'][$name] = microtime(true);
        if (self::MEMORY_LIMIT_ON) {
            self::$marker['mem'][$name] = memory_get_usage();
            self::$marker['peak'][$name] = function_exists('memory_get_peak_usage') ? memory_get_peak_usage() : self::$marker['mem'][$name];
        }
    }

    /**
     * 标记的名字是否存在
     * @param $name
     * @return bool
     */
    public static function isMarkExist($name)
    {
        $name = is_array($name) ? json_encode($name) : $name;
        if (!isset(self::$marker['time'][$name])) {
            return false;
        }
        return true;
    }

    /**
     * 区间使用时间查看
     * @param $start
     * @param $end
     * @param int $decimals
     * @return int|string
     */
    public static function useTime($start, $end, $decimals = 6)
    {
        $start = is_array($start) ? json_encode($start) : $start;
        $end = is_array($end) ? json_encode($end) : $end;
        if (!self::isMarkExist($start)) {
            return 0;
        }

        if (!self::isMarkExist($end)) {
            self::$marker['time'][$end] = microtime(true);
        }
        return number_format(self::$marker['time'][$end] - self::$marker['time'][$start], $decimals);
    }

    /**
     * 区间使用内存查看
     * @param $start
     * @param $end
     * @return string
     */
    public static function useMemory($start, $end)
    {
        $start = is_array($start) ? json_encode($start) : $start;
        $end = is_array($end) ? json_encode($end) : $end;
        if (!self::MEMORY_LIMIT_ON) {
            return '';
        }

        if (!isset(self::$marker['mem'][$start])) {
            return '';
        }

        if (!isset(self::$marker['mem'][$end])) {
            self::$marker['mem'][$end] = memory_get_usage();
        }

        return number_format((self::$marker['mem'][$end] - self::$marker['mem'][$start]) / 1024);
    }

    /**
     * 区间使用内存峰值查看
     * @param $start
     * @param $end
     * @return string
     */
    public static function getMemPeak($start, $end)
    {
        $start = is_array($start) ? json_encode($start) : $start;
        $end = is_array($end) ? json_encode($end) : $end;
        if (!self::MEMORY_LIMIT_ON) {
            return '';
        }

        if (!isset(self::$marker['peak'][$start])) {
            return '';
        }

        if (!isset(self::$marker['peak'][$end])) {
            self::$marker['peak'][$end] = function_exists('memory_get_peak_usage') ? memory_get_peak_usage() : memory_get_usage();
        }

        return number_format(max(self::$marker['peak'][$start], self::$marker['peak'][$end]) / 1024);
    }

    public static function registerHandler()
    {
        if (self::$_isRegisterHandler) {
            return;
        }
        self::$_isRegisterHandler = true;

        set_error_handler(array('\helper\LogHelper', 'handleErrors'), (E_ALL | E_STRICT) & ~E_DEPRECATED & ~E_NOTICE);
        set_exception_handler(array('\helper\LogHelper', 'handleException'));
        register_shutdown_function(array('\helper\LogHelper', 'handleShutdown'));
    }

    /**
     * 捕获错误 并打印 ERRORS 日志信息
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public static function handleErrors($errno, $errstr, $errfile, $errline)
    {
        $err = '';
        $err .= "err:{$errstr},errno:{$errno}\n";
        $err .= "line:{$errline},file:{$errfile}\n";
        //打印 ERRORS 信息
        self::writeLog(self::TAG_ERRORS, $err);
    }

    /**
     * 捕获异常 并打印 EXCEPTION 信息
     * @param $ex
     */
    public static function handleException($ex)
    {
        $msg = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();
        $stack = $ex->getTraceAsString();
        $err = '';
        $err .= "err:{$msg}\n";
        $err .= "line:{$line},file:{$file}\n";
        $err .= "{$stack}\n";
        //打印 EXCEPTION 信息
        self::writeLog(self::TAG_EXCEPTION, $err);
    }

    /**
     * 注册成功后 正常退出 异常 exit die 都会执行
     */
    public static function handleShutdown()
    {
        //打印 SHUTDOWN 信息
        $error = error_get_last();
        /* value   constant
            1   E_ERROR
            2   E_WARNING
            4   E_PARSE
            8   E_NOTICE
            16   E_CORE_ERROR
            32   E_CORE_WARNING
            64   E_COMPILE_ERROR
            128   E_COMPILE_WARNING
            256   E_USER_ERROR
            512   E_USER_WARNING
            1024   E_USER_NOTICE
            2047   E_ALL
        */

        //关闭连接
        SocketManager::closeAllSocket();
        RedisManager::closeAllRedis();
        DBManager::closeAllDB();

        if (!empty($error) && $error['type'] != 8 && $error['type'] != 2) {
            self::writeLog(self::TAG_SHUTDOWN, $error);
        }

        $_REQUEST['client_ip'] = FunctionHelper::get_client_ip();
        if (defined('PLAT_UUID')) {
            //打印 COST 信息
            $api = $_REQUEST['api'];
            $action = $_REQUEST['action'];

            if (!self::isMarkExist(LOG_MARK_START)) {
                self::writeLog(self::TAG_COST, "标记异常 无请求耗时统计");
                return;
            }

            if (!self::isMarkExist(LOG_MARK_END)) {
                $costTime = self::useTime(LOG_MARK_START, LOG_MARK_END);
                self::writeLog(self::TAG_COST, "请求异常结束 api = {$api}, action = {$action} " . '耗时[' . $costTime * 1000 . ']毫秒');
                AppModel::returnJson(ErrorConfig::HANDLE_CODE, ErrorConfig::ERROR_HANDLE, $error);
            } else {
                $costTime = self::useTime(LOG_MARK_START, LOG_MARK_END);
                self::writeLog(self::TAG_COST, "请求正常结束 api = {$api}, action = {$action} " . '耗时[' . $costTime * 1000 . ']毫秒');
            }
        } elseif (defined('TASK_UUID')) {
            $costTime = self::useTime(LOG_MARK_START, LOG_MARK_END);
            self::writeLog(self::TAG_COST, '耗时[' . $costTime * 1000 . ']毫秒');
        } else {
            $costTime = self::useTime(LOG_MARK_START, LOG_MARK_END);
            $PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : 'UNKNOWN_PHP_SELF';
            $PATH_INFO = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'UNKNOWN_PATH_INFO';
            $_REQUEST['PHP_SELF'] = $PHP_SELF;
            $_REQUEST['PATH_INFO'] = $PATH_INFO;
            self::writeLog(self::TAG_COST, "self = {$PHP_SELF}, path = {$PATH_INFO} " . '耗时[' . $costTime * 1000 . ']毫秒');
        }
        //超过 self::COST_MORE_MS 毫秒的另外打印
        if ($costTime * 1000 > self::COST_MORE_MS) {
            self::writeLog(self::TAG_COST_MORE, ['耗时[' . $costTime * 1000 . ']毫秒', $_REQUEST]);
        } else {
            //打印详细的请求消息
            self::writeLog(self::TAG_COST, $_REQUEST);
        }
    }

    /**
     * 支付成功后台告知后台推送语音提示
     */
    public static function pushSpeech()
    {
        // 指明给谁推送，为空表示向所有在线用户推送
        $to_uid = "1111";
        // 推送的url地址，使用自己的服务器地址
        //$push_api_url = "http://huo.qq:2121/";
        $push_api_url = "http://47.107.147.29:2121/";
        $post_data = array(
            "type" => "publish",
            "content" => "这个是推送的测试数据",
            "to" => $to_uid,
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        var_export($return);
    }
}
