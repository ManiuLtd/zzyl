<?php

use helper\LogHelper;

defined('APP_ROOT_PATH') or define('APP_ROOT_PATH', (dirname(__DIR__)));
defined('APP_CONFIG_PATH') or define('APP_CONFIG_PATH', APP_ROOT_PATH . '/' . 'config');
defined('APP_HELPER_PATH') or define('APP_HELPER_PATH', APP_ROOT_PATH . '/' . 'helper');
defined('APP_MANAGER_PATH') or define('APP_MANAGER_PATH', APP_ROOT_PATH . '/' . 'manager');
defined('APP_API_PATH') or define('APP_API_PATH', APP_ROOT_PATH . '/' . 'api');
defined('APP_ACTION_PATH') or define('APP_ACTION_PATH', APP_ROOT_PATH . '/' . 'action');
defined('APP_MODEL_PATH') or define('APP_MODEL_PATH', APP_ROOT_PATH . '/' . 'model');
defined('APP_NOTIFY_PATH') or define('APP_NOTIFY_PATH', APP_ROOT_PATH . '/' . 'notify');
defined('APP_LOGIC_PATH') or define('APP_LOGIC_PATH', APP_ROOT_PATH . '/' . 'logic');
defined('APP_PAY_PATH') or define('APP_PAY_PATH', APP_ROOT_PATH . '/' . 'pay');

/**
 * 实现类的按需加载
 * Class LoadHelper
 */
final class LoadHelper
{
    private static $_isRegisterAutoload = false;
    private static $_files = array();
    // private static $_paths = [APP_CONFIG_PATH, APP_HELPER_PATH, APP_MANAGER_PATH, APP_API_PATH, APP_ACTION_PATH, APP_MODEL_PATH, APP_NOTIFY_PATH, APP_PAY_PATH];
    private static $_paths = [APP_ROOT_PATH];

    /**
     * 注册自动加载函数
     */
    public static function registerAutoload()
    {
        if (self::$_isRegisterAutoload) {
            return;
        }
        self::$_isRegisterAutoload = true;
//        self::add_dir_to_path(self::$_paths);
        spl_autoload_register(array('LoadHelper', 'auto_load1'));
    }

    /**
     * 类自动加载器
     * @param $class_name
     * @return bool
     */
    public static function auto_load($class_name)
    {
        if (isset(self::$_files[$class_name])) {
            $file = self::$_files[$class_name];
        } else {
            $file = self::find_file($class_name);
        }
        if (file_exists($file)) {
            require_once($file);
            return true;
        }

        return false;
    }

    /**
     * 类自动加载器
     * @param $class_name
     * @return bool
     */
    public static function auto_load1($class_name)
    {
        // if (isset(self::$_files[$class_name])) {
        //     $file = self::$_files[$class_name];
        // } else {
        //     $file = self::find_file($class_name);
        // }

        $file = APP_ROOT_PATH . '/' . $class_name . '.php';
        $file = str_replace('\\', '/', $file);
        if (file_exists($file)) {
            require_once($file);
            return true;
        }

        return false;
    }

    /**
     * 查找 PATH_ARRAY 目录下文件 文件名和类名必须大小写一致
     * @param $file_name
     * @param string $ext
     * @return bool|mixed|string
     */
    public static function find_file($file_name, $ext = '.php')
    {
        $found = false;
        foreach (self::$_paths as $path) {
            $file = $path . '/' . $file_name . $ext;
            if (is_file($file)) {
                $found = self::$_files[$file_name] = $file;
                break;
            }
        }
        return $found;
    }

    public static function add_dir_to_path($dir)
    {
        $find = [];
        foreach ($dir as $path) {
            $find = self::find_dir_in_dir($path, $find);
        }
        self::$_paths = array_merge(self::$_paths, $find);
    }

    public static function find_dir_in_dir($dir, &$find = [])
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir . '/' . $file)) {
                    $find[] = $dir . '/' . $file;
                    self::find_dir_in_dir($dir . '/' . $file, $find);
                }
            }
        }
        return $find;
    }
}

//注册类自动加载 类名和文件名严格一致
LoadHelper::registerAutoload();

defined('LOG_MARK_START') or define('LOG_MARK_START', 'start');
defined('LOG_MARK_END') or define('LOG_MARK_END', 'end');

LogHelper::registerHandler();

//开始统计耗时
LogHelper::mark(LOG_MARK_START);
LogHelper::printLog(LogHelper::TAG_COST, "--------------------[LOG_MARK_START]--------------------");
