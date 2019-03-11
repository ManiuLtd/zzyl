<?php
namespace action;

use config\ErrorConfig;
use config\MysqlConfig;
use helper\FunctionHelper;
use model\AppModel;
use model\ConfigModel;

/**
 * 配置业务
 * Class ConfigAction
 */
class ConfigAction extends AppAction
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
     * 获取配置信息
     * @param $params
     */
    public function config($params)
    {
        $otherConfig = ConfigModel::getInstance()->getOtherConfig();
        if (empty($otherConfig)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, MysqlConfig::Table_otherconfig . "没有配置数据！ ");
        }
        $gameConfig = ConfigModel::getInstance()->getGameConfig();
        if (empty($gameConfig)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, MysqlConfig::Table_web_game_config . "没有配置数据！");
        }
        $config = ConfigModel::getInstance()->getConfig();
        $reConfig = [];
        foreach ($config as $k => $v) {
            $reConfig[lcfirst($k)] = $v;
            switch (lcfirst($k)) {
                case 'bindPhoneSendMoney':
                    // echo '-----';
                    $reConfig[lcfirst($k)] = FunctionHelper::MoneyOutput($v);
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        unset($config);
		AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $reConfig);
	}
}
