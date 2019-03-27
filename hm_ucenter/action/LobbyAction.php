<?php
namespace action;

use config\EnumConfig;
use config\ErrorConfig;
use model\AppModel;
use model\ClubModel;
use model\LobbyModel;
use notify\CenterNotify;

/**
 * 大厅业务
 * Class LobbyAction
 */
class LobbyAction extends AppAction
{
    const NOTICE_NUM = 10;
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
     * 获取游戏列表
     * @param $params
     */
    public function gameList($params)
    {
        AppModel::returnJson(ErrorConfig::ERROR_CODE, '请求超时');
        $is_recommend = (int)$params['is_recommend'];
        $gameList = LobbyModel::getInstance()->getGameList($is_recommend, 'gameID');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $gameList);
    }

    /**
     * 获取房间列表
     * @param $params
     */
    public function roomList($params)
    {
        $gameID = (int)$params['gameID'];
        $is_recommend = (int)$params['is_recommend'];
        $roomList = LobbyModel::getInstance()->getRoomList($gameID, $is_recommend);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $roomList);
    }

    /**
     * 获取购买桌子列表
     * @param $params
     */
    public function buyDeskList($params)
    {
        $gameID = (int)$params['gameID'];
        $buyDeskList = LobbyModel::getInstance()->buyDeskList($gameID);
        if (empty($buyDeskList)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE, "无游戏ID为{$gameID}的购买桌子配置信息");
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $buyDeskList);
    }

    // 发送世界广播
    public function sendHorn($param)
    {
        $userID = (int)$param['userID'];
        $content = $param['content'];

        $config = LobbyModel::getInstance()->getConfig();

        $user = LobbyModel::getInstance()->getUserInfo($userID, ['jewels', 'name']);

        // 钻石不足
        if ($user['jewels'] < $config['sendHornCostJewels']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_LACK_OF_DIAMONDS);
        }

        LobbyModel::getInstance()->addHorn($userID, $content, $config['sendHornCostJewels']);
        LobbyModel::getInstance()->changeUserResource($userID, EnumConfig::E_ResourceType['JEWELS'], -$config['sendHornCostJewels'], EnumConfig::E_ResourceChangeReason['SEND_HORN']);

        CenterNotify::sendHorn($userID, $user['name'], iconv('UTF-8', 'GB2312', $content));
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 玩家购买房间列表
     * @param $param
     */
    public function userBuyRoomList($param)
    {
        $userID = $param['userID'];

        $buyRoomList = LobbyModel::getInstance()->getBuyRoomList($userID);
        $userBuyRoomList = [];
        foreach ($buyRoomList as $buyRoom) {
            $userBuyRoom = [
                'buyDeskTime' => $buyRoom['createTime'],
                'roomID' => $buyRoom['roomID'],
                'gameCount' => $buyRoom['buyGameCount'],
                'userCount' => $buyRoom['curGameCount'],
                'passwd' => $buyRoom['passwd'],
                'gameRules' => $buyRoom['gameRules'],
                'maxDeskUserCount' => $buyRoom['maxDeskUserCount'],
            ];
            $userBuyRoomList[] = $userBuyRoom;
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $userBuyRoomList);
    }

    /**
     * 解散房间
     * @param $param
     */
    public function dismissRoom($param)
    {
        $passwd = $param['passwd'];
        $userID = $param['userID'];

        // 房间不存在
        if (!LobbyModel::getInstance()->isBuyRoomExists($passwd)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_MSG_DESK_DONT_IT);
        }
        // 获取房间信息
        $roomInfo = LobbyModel::getInstance()->getBuyRoomInfo($passwd);
        CenterNotify::dismissDesk($userID, $roomInfo['deskIdx'], $roomInfo['roomID']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT);
    }
    
    /**
     * 进入俱乐部
     */
    public function enterPublicGroup($params) {
        $userID = (int)$params['userID'];
        $status = (int)$params['status'];
        //验证参数，1进入 2离开
        if(!in_array($status, [1,2]))
            AppModel::returnJson(ErrorConfig::ERROR_CODE, ErrorConfig::ERROR_PARAMETER_ERROR);
        //发送消息给中心服
        CenterNotify::enterOrLeaveCenter($userID, $status);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, []);
    }

    /**
     * 特殊俱乐部（大厅）牌桌列表
     * @param $params
     */
    public function publicGroupDeskList($params)
    {
        $friendsGroupID = 0;//俱乐部id，
        $deskType = 0;// 0牌桌 1VIP
        $floor = (int)$params['floor'];
        $result = [];

        if ($deskType == 0) {
            $result = ClubModel::getInstance()->getFriendsGroupOpenDeskList($friendsGroupID, $floor);
        } elseif ($deskType == 1) {
            $result = ClubModel::getInstance()->getFriendsGroupVIPRoomList($friendsGroupID, $floor);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE, ErrorConfig::SUCCESS_MSG_DEFAULT, $result);
    }

//    /**
//     * 测试
//     */
//    public function test($params) {
//        $userID = $params['userID'];
//        //推送小红点变化
//        CenterNotify::sendRedSportAll($userID, EnumConfig::E_RedSpotType['FG']);
//    }
}
