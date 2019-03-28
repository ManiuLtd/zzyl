<?php
namespace action;
use config\EnumConfig;
use config\ErrorConfig;
use helper\FunctionHelper;
use model\AgentModel;
use model\AppModel;
use model\ServerModel;

/**
 * 服务器业务
 * Class ServerAction
 */
class ServerAction extends AppAction
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        //
//        parent::__construct();
    }

    private function __clone()
    {
    }

    const SERVER_WHITE_OPEN = true;
    const SERVER_BLACK_OPEN = false;

    /**
     * 获取登录服信息
     * @param $params
     */
    public function getLogonServer($params)
    {
        var_dump($params);
        var_dump( ServerModel::getInstance());exit;
        $logonServerList = ServerModel::getInstance()->getLogonServerList();

        if (empty($logonServerList)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_SERVER_NOTFIND);
        }

        $curPeople = array_column($logonServerList, 'curPeople');
        // 排序
        array_multisort($curPeople, SORT_ASC, $logonServerList);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $logonServerList[0]);
    }

    /**
     * 获取服务器状态信息
     * @param $params
     */
    public function serverStatus($params)
    {
        $server_status = ServerModel::getInstance()->getServerStatus();
        $result = array(
            'server_status' => $server_status,
            'msg' => '',
        );
        if ($server_status == EnumConfig::E_ServerStatus['NORMAL']) {
            $result['msg'] = '服务器正常运行中...';
            if (self::SERVER_BLACK_OPEN) {
                //是否在黑名单
                $ip = FunctionHelper::get_client_ip();
                $isBlack = ServerModel::getInstance()->isInServerBlack($ip);
                if ($isBlack) {
                    $result['server_status'] = EnumConfig::E_ServerStatus['BLACK'];
                    $result['msg'] = '登录IP限制，请联系客服';
                }
            }
        } elseif ($server_status == EnumConfig::E_ServerStatus['STOP']) {
            $result['msg'] = '服务器停服更新中...';
        } elseif ($server_status == EnumConfig::E_ServerStatus['TEST']) {
            $result['msg'] = '服务器停服维护中...';
            //是否在白名单
            if (self::SERVER_WHITE_OPEN) {
                $ip = FunctionHelper::get_client_ip();
                $isWhite = ServerModel::getInstance()->isInServerWhite($ip);
                if ($isWhite) {
                    $result['server_status'] = EnumConfig::E_ServerStatus['NORMAL'];
                    $result['msg'] = '添加白名单测试中...';
                }
            }
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    /**
     * 获取测试状态
     * @param $params
     */
    public function serverTestStatus($params)
    {
        $server_test_status = ServerModel::getInstance()->getServerTestStatus();
        $result = array(
            'server_test_status' => $server_test_status,
        );
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

    public function loopBattleRecord() {
        ini_set('display_errors', 1);
        (new AgentModel())->loopBattleRecord();
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
}
