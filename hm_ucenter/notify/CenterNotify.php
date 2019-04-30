<?php
namespace notify;
use config\ProtocolConfig;
use config\StructConfig;
use helper\LogHelper;
use manager\SocketManager;

/**
 * 推送消息给中心服
 * Class CenterNotify
 */
final class CenterNotify
{
    const PACKET_HEAD_LEN = 28;

    //网络数据包头
    public static function netMsgHead($uSize, $uMainID, $uAssistantID)
    {
        $pack = '';
        $pack .= pack('L', $uSize);
        $pack .= pack('L', $uMainID);
        $pack .= pack('L', $uAssistantID);
        $pack .= pack('L', 0);
        $pack .= pack('L', 0);
        return $pack;
    }

    //中心数据包头
    public static function centerMsgHead($uCenterID, $targetID)
    {
        $pack = '';
        $pack .= pack('L', $uCenterID);
        $pack .= pack('l', $targetID);
        return $pack;
    }

    //打包所有数据
    public static function packAllMsg($netMsgHead, $centerMsgHead, $struct)
    {
        $pack = '';
        $pack .= $netMsgHead;
        $pack .= $centerMsgHead;
        $pack .= $struct;
        return $pack;
    }

    public static function send($uCenterID, $targetID, $struct, $uMainID = 0, $uAssistantID = 0)
    {
        //大小 包头28+结构体长度
        $uSize = self::PACKET_HEAD_LEN + strlen($struct);

        $netMsgHead = self::netMsgHead($uSize, $uMainID, $uAssistantID);

        $centerMsgHead = self::centerMsgHead($uCenterID, $targetID);

        $data = self::packAllMsg($netMsgHead, $centerMsgHead, $struct);

        $sendResult = SocketManager::getCenterSocket()->send_data($data);
        LogHelper::printInfo("send uCenterID={$uCenterID},targetID={$targetID},uMainID={$uMainID},uAssistantID={$uAssistantID}");
        //发送是否成功
        if (!$sendResult) {
            LogHelper::printError("发送数据失败 uCenterID={$uCenterID},targetID={$targetID},uMainID={$uMainID},uAssistantID={$uAssistantID}");
            return false;
        }
        return true;
    }

    /**
     * 修改玩家身份
     * @param $userID
     * @param $type
     * @param $statusValue
     * @param $otherValue
     * @param $moneyLimit
     */
    public static function userStatus($userID, $type, $statusValue, $otherValue, $moneyLimit)
    {
        $struct = StructConfig::userStatus($type, $statusValue, $otherValue, $moneyLimit);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_IDENTUSER, $userID, $struct);
    }

    /**
     * 发送公告
     * @param $notice
     */
    public static function sendNotice($notice)
    {
        $struct = StructConfig::sendNotice($notice);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTICE, 0, $struct);
    }

    /**
     * 给所有玩家推送邮件小红点
     */
    public static function allMailRedSport()
    {
        $struct = StructConfig::allMailRedSport();
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_REQ_ALL_USER_MAIL, 0, $struct);
    }

    /**
     * 开服通知
     */
    public static function openServer()
    {
        $struct = StructConfig::openServer();
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_OPEN_SERVER, 0, $struct);
    }

    /**
     * 关服通知
     * @param $server_status
     */
    public static function closeServer($server_status)
    {
        $struct = StructConfig::closeServer($server_status);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_CLOSE_SERVER, 0, $struct);
    }

    /**
     * 用户小红点通知
     * @param $userID
     * @param $type
     */
    public static function sendRedSport($userID, $type)
    {
        $struct = StructConfig::sendRedSport($type);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_RED_SPOT, $userID, $struct);
    }

    /**
     * 俱乐部小红点通知
     * @param $friendsGroupID
     * @param $type
     */
    public static function sendRedSportAll($friendsGroupID, $type)
    {
        $struct = StructConfig::sendRedSport($type);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_RED_FG_SPOT, $friendsGroupID, $struct);
    }

    /**
     * 资源变化
     * @param $userID
     * @param $resourceType
     * @param $value
     * @param $changeValue
     * @param int $reason
     * @param int $reserveData
     * @param int $isNotifyRoom
     */
    public static function resourceChange($userID, $resourceType, $value, $changeValue, $reason = 0, $reserveData = 0, $isNotifyRoom = 0)
    {
        $struct = StructConfig::resourceChange($resourceType, $value, $changeValue, $reason, $reserveData, $isNotifyRoom);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_RESOURCE_CHANGE, $userID, $struct);
    }

    /**
     * 发送世界广播
     * @param $userID
     * @param $userName
     * @param $content
     */
    public static function sendHorn($userID, $userName, $content)
    {
        $struct = StructConfig::sendHorn($userID, $userName, $content);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_SEND_HORN, $userID, $struct);
    }

    /**
     * 解散房间
     * @param $userID
     * @param $deskID
     * @param $roomID
     */
    public static function dismissDesk($userID, $deskID, $roomID)
    {
        $struct = StructConfig::dismissDesk($userID, $deskID, $roomID);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_MASTER_DISSMISS_DESK, $userID, $struct);
    }

    /**
     * 推送好友通知
     * @param $userID
     * @param $notifyID
     * @param $type
     * @param $targetID
     * @param $param1
     * @param $param2
     */
    public static function friendNotify($userID, $notifyID, $type, $targetID, $param1 = 0, $param2 = 0)
    {
        $struct = StructConfig::friendNotify($notifyID, $type, $targetID, $param1, $param2);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_LOGON_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIEND_NOTIFY);
    }

    /**
     * 推送添加好友
     * @param $userID
     * @param $friend
     */
    public static function addFriend($userID, $friend)
    {
        $struct = StructConfig::addFriend($friend);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_LOGON_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIEND_ADDOK);
    }

    /**
     * 推送删除好友
     * @param $userID
     * @param $friendID
     */
    public static function delFriend($userID, $friendID)
    {
        $struct = StructConfig::delFriend($friendID);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_LOGON_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIEND_DELOK);
    }

    /**
     * 推送删除好友通知
     * @param $userID
     * @param $notifyID
     */
    public static function delFriendNotify($userID, $notifyID)
    {
        $struct = StructConfig::delFriendNotify($notifyID);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_LOGON_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIEND_DELNOTIFY);
    }

    /**
     * 推送俱乐部信息
     * @param $userID
     * @param $friendsGroup
     */
    public static function friendsGroup($userID, $friendsGroup)
    {
        $struct = StructConfig::friendsGroup($friendsGroup);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_CREATE_FG);
    }

    /**
     * 推送俱乐部解散信息
     * @param $userID
     * @param $friendsGroupID
     */
    public static function friendsGroupDismiss($userID, $friendsGroupID)
    {
        $struct = StructConfig::friendsGroupDismiss($friendsGroupID);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_DISMISS);
    }

    /**
     * 推送俱乐部通知
     * @param $userID
     * @param $friendsGroupNotify
     */
    public static function friendsGroupNotify($userID, $friendsGroupNotify)
    {
        $struct = StructConfig::friendsGroupNotify($friendsGroupNotify);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_NOTIFY_MSG);
    }

    /**
     * 推送俱乐部通知 给俱乐部所有成员
     * @param $friendsGroupID
     * @param $friendsGroupNotify
     */
    public static function friendsGroupNotifyAll($friendsGroupID, $friendsGroupNotify)
    {
        $struct = StructConfig::friendsGroupNotify($friendsGroupNotify);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_NOTIFY_MSG);
    }

    /**
     * 推送俱乐部成员变化
     * @param $userID
     * @param $friendsGroupID
     * @param $type
     * @param $friendsGroupMember
     */
    public static function friendsGroupMemberChange($userID, $friendsGroupID, $type, $friendsGroupMember)
    {
        $struct = StructConfig::friendsGroupMemberChange($friendsGroupID, $type, $friendsGroupMember);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_USERID, $userID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_PEOPLE_CHANGE);
    }

    /**
     * 俱乐部成员变化
     * @param $friendsGroupID
     * @param $type
     * @param $friendsGroupMember
     */
    public static function friendsGroupMemberChangeAll($friendsGroupID, $type, $friendsGroupMember)
    {
        $struct = StructConfig::friendsGroupMemberChange($friendsGroupID, $type, $friendsGroupMember);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_PEOPLE_CHANGE);
    }

    /**
     * 俱乐部公告变化
     * @param $friendsGroupID
     * @param $name\
     */
    public static function friendsGroupNameChangeAll($friendsGroupID, $name)
    {
        $struct = StructConfig::friendsGroupNameChange($friendsGroupID, $name);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_NAME_CHANGE);
    }

    /**
     * 俱乐部公告变化
     * @param $friendsGroupID
     * @param $notice
     */
    public static function friendsGroupNoticeChangeAll($friendsGroupID, $notice)
    {
        $struct = StructConfig::friendsGroupNoticeChange($friendsGroupID, $notice);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_NOTICE_CHANGE);
    }

    /**
     * 俱乐部微信(签名)变化
     * @param $friendsGroupID
     * @param $wechat
     */
    public static function friendsGroupWechatChangeAll($friendsGroupID, $wechat)
    {
        $struct = StructConfig::friendsGroupWechatChange($friendsGroupID, $wechat);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_WECHAT_CHANGE);
    }

    /**
     * 俱乐部成员身份变化
     * @param $friendsGroupID
     * @param $statusChangeInfoList
     */
    public static function friendsGroupMemberStatusChangeAll($friendsGroupID, $statusChangeInfoList)
    {
        $struct = StructConfig::friendsGroupMemberStatusChange($friendsGroupID, $statusChangeInfoList);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_STATUS_CHANGE);
    }

    /**
     * 俱乐部成员权限变化
     * @param $friendsGroupID
     * @param $userID
     * @param $newPower
     */
    public static function friendsGroupMemberPowerChangeAll($friendsGroupID, $userID, $newPower)
    {
        $struct = StructConfig::friendsGroupMemberPowerChange($friendsGroupID, $userID, $newPower);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_POWER_CHANGE);
    }

    /**
     * 俱乐部成员火币变化
     * @param $friendsGroupID
     * @param $fireCoinChangeInfoList
     */
    public static function friendsGroupMemberFireCoinChangeAll($friendsGroupID, $fireCoinChangeInfoList)
    {
        $struct = StructConfig::friendsGroupMemberFireCoinChange($friendsGroupID, $fireCoinChangeInfoList);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_NOTIFY_FG, $friendsGroupID, $struct, ProtocolConfig::MSG_MAIN_FRIENDSGROUP_NOTIFY, ProtocolConfig::MSG_NTF_LOGON_FRIENDSGROUP_FIRECOIN_CHANGE);
    }

    /**
     * 解散俱乐部房间
     * @param $userID
     * @param $deskID
     * @param $roomID
     */
    public static function dismissFriendsGroupDesk($userID, $deskID, $roomID)
    {
        $struct = StructConfig::dismissDesk($userID, $deskID, $roomID);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_FG_DISSMISS_DESK, $userID, $struct);
    }

    /**
     * 进入或离开娱乐中心
     * @param $userID
     * @param $status
     */
    public static function enterOrLeaveCenter($userID, $status)
    {
        $struct = StructConfig::enterOrLeaveCenter($userID, $status);
        return self::send(ProtocolConfig::PLATFORM_MESSAGE_ENTER_OR_LEAVE_CENTER, $userID, $struct);
    }
}
