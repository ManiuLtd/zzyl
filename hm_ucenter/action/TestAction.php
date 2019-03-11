<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/10
 * Time: 12:01
 */

namespace action;

use model\ServerModel;
use model\ShareModel;
use model\AgentModel;
use model\AppModel;
use model\AudioModel;
use model\BankModel;
use model\ClubModel;
use model\ConfigModel;
use model\EmailModel;
use model\FeedbackModel;
use model\FriendModel;
use model\GiveModel;
use model\LobbyModel;
use model\NoticeModel;
use model\PayModel;
use model\PhoneModel;
use model\RankModel;
use model\RecordModel;
use model\UpdateModel;
use model\UserModel;
use model\WelfareModel;

use helper\FunctionHelper;
use helper\DBHelper;
use helper\ExceptionHelper;
use helper\LogHelper;
use helper\RedisHelper;
use helper\SocketHelper;

use config\AgentConfig;
use config\EnumConfig;
use config\MysqlTableFieldConfig;
use config\ConnectConfig;
use config\ErrorConfig;
use config\ProtocolConfig;
use config\GameRedisConfig;
use config\RedisConfig;
use config\DynamicConfig;
use config\GeneralConfig;
use config\StructConfig;
use config\MysqlConfig;

use notify\CenterNotify;

use logic\AgentLogic;
use logic\NotifyLogic;
use logic\PhoneLogic;

use manager\DBManager;
use manager\RedisManager;
use manager\SocketManager;
use pay\hui_fu_bao\HuiFuBaoPay;
use pay\hui_fu_bao_zl\HuiFuBaoZLPay;
use pay\jian_fu\JianFuPay;
use pay\ma_fu\MaFuPay;
use pay\ping_guo\PingGuoPay;
use pay\wang_shi_fu\WangShiFuPay;
use pay\wei_xin\WeiXinPay;
use pay\xin_bao\XinBaoPay;
use pay\AppPay;

class TestAction
{
    private function __construct()
    {
    }
}