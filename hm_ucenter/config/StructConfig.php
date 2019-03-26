<?php
namespace config;

/**
 * 消息体结构
 * Class StructConfig
 */
final class StructConfig
{

    const G_WAR_RECORD_PLAYER_NUM = 9;

    /**
     * 修改玩家身份
     * @param $type
     * @param $statusValue
     * @param $otherValue
     * @return string
     */
    public static function userStatus($type, $statusValue, $otherValue, $moneyLimit)
    {
        $pack = '';
        $pack .= pack('C', $type);//0 取消 1设置
        $pack .= pack('C', $statusValue);//1超端 2赢玩家 4 输玩家 8 封号
        $pack .= pack('l', $otherValue);//封号在此填写时间 -1 永久
        $pack .= pack('q', $moneyLimit);//赢玩家赢钱上限
        return $pack;
    }

    /**
     * 发送公告
     * @param $notice
     * @return string
     */
    public static function sendNotice($notice)
    {
        $pack = '';
        $pack .= pack('a24', iconv('UTF-8', 'GB2312', $notice['title'])); // 公告标题
        $pack .= pack('a1024', iconv('UTF-8', 'GB2312', $notice['content'])); // 公告内容
        $pack .= pack('l', $notice['interval']); // 间隔
        $pack .= pack('l', $notice['times']); // 次数
        $pack .= pack('l', $notice['type']); // 类型 (1:普通 2：特殊 3:弹出)
        return $pack;
    }

    /**
     * 全部玩家邮件小红点
     * @return string
     */
    public static function allMailRedSport()
    {
        $pack = '';
        return $pack;
    }

    /**
     * 开服通知
     * @return string
     */
    public static function openServer()
    {
        $pack = '';
        return $pack;
    }

    /**
     * 关服通知
     * @param $server_status
     * @return string
     */
    public static function closeServer($server_status)
    {
        $pack = '';
        $pack .= pack('l', $server_status); //服务器状态
        return $pack;
    }

    /**
     * 小红点
     * @param $type
     * @return string
     */
    public static function sendRedSport($type)
    {
        $pack = '';
        $pack .= pack('C', $type); // 0 邮件 1 好友 2 俱乐部
        return $pack;
    }

    /**
     * 资源变化
     * @param $resourceType
     * @param $value
     * @param $changeValue
     * @param $reason
     * @param $reserveData
     * @param $isNotifyRoom
     * @return string
     */
    public static function resourceChange($resourceType, $value, $changeValue, $reason, $reserveData, $isNotifyRoom)
    {
        $pack = '';
        $pack .= pack('l', $resourceType); // 资源类型
        $pack .= pack('q', $value); // 全量
        $pack .= pack('q', $changeValue); // 增量
        $pack .= pack('l', $reason); // 原因
        $pack .= pack('l', $reserveData); // 预留字段，主要保存俱乐部id
        $pack .= pack('C', $isNotifyRoom); // 是否通知到游戏
        return $pack;
    }

    /**
     * 发送世界广播
     * @param $userID
     * @param $userName
     * @param $content
     * @return string
     */
    public static function sendHorn($userID, $userName, $content)
    {
        $pack = '';
        $pack .= pack('l', $userID); // ID
        $pack .= pack('a64', $userName); // 用户名
        $pack .= pack('a1024', $content); // 内容
        return $pack;
    }

    /**
     * 解散房间
     * @param $userID
     * @param $deskID
     * @param $roomID
     * @return string
     */
    public static function dismissDesk($userID, $deskID, $roomID)
    {
        $pack = '';
        $pack .= pack('l', $userID); // 用户ID
        $pack .= pack('l', $deskID); // 桌子ID
        $pack .= pack('C', 1); // 删除
        $pack .= pack('l', $roomID); // 房间ID
        return $pack;
    }

    /**
     * 好友通知信息
     * @param $notifyID
     * @param $type
     * @param $targetID
     * @param $param1
     * @param $param2
     * @return string
     */
    public static function friendNotify($notifyID, $type, $targetID, $param1, $param2)
    {
        $pack = '';
        $pack .= pack('l', $notifyID); // ID
        $pack .= pack('l', $type); // 类型
        $pack .= pack('l', $targetID); // 发送者
        $pack .= pack('l', time());
        $pack .= pack('l', $param1); // 其他参数1
        $pack .= pack('l', $param2); // 其他参数2
        return $pack;
    }

    /**
     * 好友添加
     * @param $friend
     * @return string
     */
    public static function addFriend($friend)
    {
        $pack = '';
        $pack .= pack('l', $friend['userID']); // 好友ID
        $pack .= pack('C', $friend['onlineStatus']); //在线状态 0:不在线 1：在线
        $pack .= pack('C', $friend['rewardStatus']); //打赏状态 0:可打赏 1：已打赏
        return $pack;
    }

    /**
     * 好友删除
     * @param $friendID
     * @return string
     */
    public static function delFriend($friendID)
    {
        $pack = '';
        $pack .= pack('l', $friendID); // 好友ID
        return $pack;
    }

    /**
     * 好友通知删除
     * @param $notify
     * @return string
     */
    public static function delFriendNotify($notify)
    {
        $pack = '';
        $pack .= pack('l', $notify); // 通知ID
        return $pack;
    }

    /**
     * 俱乐部
     * @param $friendsGroup
     * @return string
     */
    public static function friendsGroup($friendsGroup)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroup['friendsGroupID']); // 俱乐部ID
        $pack .= pack('C', $friendsGroup['status']); // 身份
        $pack .= pack('l', $friendsGroup['currOnlineCount']); // 在线人数
        $pack .= pack('l', $friendsGroup['peopleCount']); // 俱乐部人数
        $pack .= pack('l', $friendsGroup['deskCount']); // 已开牌桌数
        $pack .= pack('l', $friendsGroup['VIPRoomCount']); // VIP房间数
        for ($i = 0; $i <= self::G_WAR_RECORD_PLAYER_NUM - 1; $i++) {
            $memberUserID = 0;
            if (!empty($friendsGroup['frontMember'][$i])) {
                $memberUserID = $friendsGroup['frontMember'][$i];
            }
            $pack .= pack('l', $memberUserID); // 前9个玩家ID
        }
        $pack .= pack('l', $friendsGroup['createTime']); // 创建时间
        $pack .= pack('l', $friendsGroup['masterID']); // 群主
        $pack .= pack('a24', $friendsGroup['name']); // 名字
        $pack .= pack('a128', $friendsGroup['notice']); // 公告
        $pack .= pack('a48', $friendsGroup['wechat']); // 签名(微信)
        $pack .= pack('C', $friendsGroup['power']); // 权限
        $pack .= pack('l', $friendsGroup['fireCoin']); // 携带火币
        return $pack;
    }

    /**
     * 俱乐部解散
     * @param $friendsGroupID
     * @return string
     */
    public static function friendsGroupDismiss($friendsGroupID)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); // 俱乐部ID
        return $pack;
    }

    /**
     * 俱乐部通知
     * @param $friendsGroupNotify
     * @return string
     */
    public static function friendsGroupNotify($friendsGroupNotify)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupNotify['notifyID']); // notifyID
        $pack .= pack('l', 0); // userID 用不到了
        $pack .= pack('l', $friendsGroupNotify['targetFriendsGroupID']); // 俱乐部ID
        $pack .= pack('a24', $friendsGroupNotify['name']); // 俱乐部名字
        $pack .= pack('C', $friendsGroupNotify['type']); // 类型
        $pack .= pack('l', $friendsGroupNotify['time']); // 时间
        $pack .= pack('l', $friendsGroupNotify['param1']); // 其他参数1
        $pack .= pack('l', $friendsGroupNotify['param2']); // 其他参数2
        $pack .= pack('C', 0); // 是否操作 用不到了
        return $pack;
    }

    /**
     * 俱乐部成员
     * @param $friendsGroupMember
     * @return string
     */
    public static function friendsGroupMember($friendsGroupMember)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupMember['userID']); //玩家ID
        $pack .= pack('l', $friendsGroupMember['joinTime']); //加入时间
        $pack .= pack('C', $friendsGroupMember['onlineStatus']); //在线状态
        $pack .= pack('l', $friendsGroupMember['score']); //积分变化
        $pack .= pack('q', $friendsGroupMember['money']); //金币变化
        $pack .= pack('l', $friendsGroupMember['fireCoin']); //火币变化
        $pack .= pack('l', $friendsGroupMember['status']); //身份
        $pack .= pack('l', $friendsGroupMember['carryFireCoin']); //携带火币
        $pack .= pack('l', $friendsGroupMember['power']); //权限
        return $pack;
    }

    /**
     * 俱乐部成员变化
     * @param $friendsGroupID
     * @param $type : //0更新，1增加，2删除
     * @param $friendsGroupMember
     * @return string
     */
    public static function friendsGroupMemberChange($friendsGroupID, $type, $friendsGroupMember)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('C', $type); //变化类型
        $pack .= self::friendsGroupMember($friendsGroupMember);
        return $pack;
    }

    /**
     * 俱乐部名字变化
     * @param $friendsGroupID
     * @param $name
     * @return string
     */
    public static function friendsGroupNameChange($friendsGroupID, $name)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('a48', $name); //新名字
        return $pack;
    }

    /**
     * 俱乐部公告变化
     * @param $friendsGroupID
     * @param $notice
     * @return string
     */
    public static function friendsGroupNoticeChange($friendsGroupID, $notice)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('a128', $notice); //公告
        return $pack;
    }

    /**
     * 俱乐部微信(签名)变化
     * @param $friendsGroupID
     * @param $wechat
     * @return string
     */
    public static function friendsGroupWechatChange($friendsGroupID, $wechat)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('a48', $wechat); //微信(签名)
        return $pack;
    }

    /**
     * 身份变化信息结构
     * @param $statusChangeInfo
     * @return string
     */
    public static function statusChangeInfo($statusChangeInfo)
    {
        $pack = '';
        $pack .= pack('l', $statusChangeInfo['userID']); //成员ID
        $pack .= pack('C', $statusChangeInfo['status']); //新身份
        $pack .= pack('C', $statusChangeInfo['power']); //新权限
        return $pack;
    }

    /**
     * 俱乐部成员身份变化
     * @param $friendsGroupID
     * @param $statusChangeInfoList
     * @return string
     */
    public static function friendsGroupMemberStatusChange($friendsGroupID, $statusChangeInfoList)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('l', count($statusChangeInfoList)); //俱乐部ID
        foreach ($statusChangeInfoList as $statusChangeInfo) {
            $pack .= self::statusChangeInfo($statusChangeInfo);
        }
        return $pack;
    }

    /**
     * 俱乐部成员权限变化
     * @param $friendsGroupID
     * @param $userID
     * @param $newPower
     * @return string
     */
    public static function friendsGroupMemberPowerChange($friendsGroupID, $userID, $newPower)
    {
        $pack = '';
        $pack .= pack('l', $userID); //成员ID
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        $pack .= pack('C', $newPower); //新权限
        return $pack;
    }

    /**
     * 火币变化信息结构
     * @param $fireCoinChangeInfo
     * @return string
     */
    public static function fireCoinChangeInfo($fireCoinChangeInfo)
    {
        $pack = '';
        $pack .= pack('l', $fireCoinChangeInfo['userID']); //成员ID
        $pack .= pack('q', $fireCoinChangeInfo['newFireCoin']); //新火币
        return $pack;
    }

    /**
     * 进入或离开娱乐中心
     * @param $userID
     * $status 1 进入 2 离开
     * @return string
     */
    public static function enterOrLeaveCenter($userID, $status)
    {
        $pack = '';
        $pack .= pack('i', $userID); //成员ID
        $pack .= pack('i', $status); //
        return $pack;
    }

    /**
     * 俱乐部成员火币变化
     * @param $friendsGroupID
     * @param $fireCoinChangeInfoList
     * @return string
     */
    public static function friendsGroupMemberFireCoinChange($friendsGroupID, $fireCoinChangeInfoList)
    {
        $pack = '';
        $pack .= pack('l', $friendsGroupID); //俱乐部ID
        for ($i = 0; $i < self::G_WAR_RECORD_PLAYER_NUM; $i++) {
            $fireCoinChangeInfo = $fireCoinChangeInfoList[$i];
            if (empty($fireCoinChangeInfo)) {
                $fireCoinChangeInfo = array(
                    'userID' => 0,
                    'newFireCoin' => 0,
                );
            }
            $pack .= self::fireCoinChangeInfo($fireCoinChangeInfo);
        }
        return $pack;
    }
}
