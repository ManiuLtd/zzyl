<?php
namespace config;

final class ProtocolConfig
{

    ///////////////////////////////登录服///////////////////////////////////////////
    // 登录服推送
    const MSG_MAIN_LOGON_NOTIFY = 103;
    const MSG_NTF_LOGON_USER_SQUEEZE = 1; //玩家被顶号（挤掉）
    const MSG_NTF_LOGON_NOTICE = 2; //公告
    const MSG_NTF_LOGON_HORN = 3; //喇叭
    const MSG_NTF_LOGON_USER_EXIST = 4; //玩家已经登录
    const MSG_NTF_LOGON_RESOURCE_CHANGE = 5; //资源变化
    const MSG_NTF_LOGON_SUPPORT_INFO = 6; //破产补助信息（登录成功之后额外推送）
    const MSG_NTF_LOGON_USERCOUNT_CHANGE = 7; //人数变化
    const MSG_NTF_LOGON_FRIEND_NOTIFY = 8; //好友通知
    const MSG_NTF_LOGON_FRIEND_ADDOK = 9; //添加好友成功
    const MSG_NTF_LOGON_FRIEND_DELOK = 10; //删除好友成功
    const MSG_NTF_LOGON_FRIEND_DELNOTIFY = 11; //删除好友通知
    const MSG_NTF_LOGON_FRIEND_LOGINOROUT = 12; //通知好友登录或者登出
    const MSG_NTF_LOGON_FRIEND_REDSPOT = 13; //通知好友小红点
    const MSG_NTF_LOGON_CLOSE_SERVER = 14; // 关服通知
    const MSG_NTF_LOGON_EMAIL_REDSPOT = 15; // 邮件小红点
    const MSG_NTF_LOGON_NEW_EMAIL = 16; // 新邮件通知
    // 俱乐部通知
    const MSG_MAIN_FRIENDSGROUP_NOTIFY = 108;
    const MSG_NTF_LOGON_FRIENDSGROUP_NOTIFY_MSG = 1; // 服务器主动推送的一条通知消息
    const MSG_NTF_LOGON_FRIENDSGROUP_ROOM_MSG = 2; // 服务器主动推送的俱乐部开房消息
    const MSG_NTF_LOGON_FRIENDSGROUP_ACCO_MSG = 3; // 服务器主动推送的俱乐部战绩消息
    const MSG_NTF_LOGON_FRIENDSGROUP_REDSPOT = 4; // 通知俱乐部小红点
    const MSG_NTF_LOGON_FRIENDSGROUP_MSG_HAVE_CHANGE = 5; // 俱乐部聊天消息变化通知
    const MSG_NTF_LOGON_FRIENDSGROUP_CREATE_FG = 6; // 俱乐部增加
    const MSG_NTF_LOGON_FRIENDSGROUP_NAME_CHANGE = 7; // 俱乐部名字变化
    const MSG_NTF_LOGON_FRIENDSGROUP_PEOPLE_CHANGE = 8; // 俱乐部成员变化
    const MSG_NTF_LOGON_FRIENDSGROUP_DISMISS = 9; // 俱乐部解散
    const MSG_NTF_LOGON_FRIENDSGROUP_NOTICE_CHANGE = 10; // 俱乐部公告变化通知
    const MSG_NTF_LOGON_FRIENDSGROUP_DESK_INFO_MSG = 11; // 服务器主动推送的俱乐部桌子消息(增加)
    const MSG_NTF_LOGON_FRIENDSGROUP_DESK_INFO_CHANGE = 12; // 俱乐部桌子列表变化通知（更新和删除）
    const MSG_NTF_LOGON_FRIENDSGROUP_STATUS_CHANGE = 13; // 俱乐部身份变化通知
    const MSG_NTF_LOGON_FRIENDSGROUP_WECHAT_CHANGE = 14; // 俱乐部微信变化通知
    const MSG_NTF_LOGON_FRIENDSGROUP_POWER_CHANGE = 15; // 俱乐部玩家权限变更
    const MSG_NTF_LOGON_FRIENDSGROUP_NEW_VIPROOM_MSG = 16; // 推送的俱乐部VIP房间(增加)
    const MSG_NTF_LOGON_FRIENDSGROUP_VIPROOM_CHANGE = 17; // vip房间变化通知（更新和删除）
    const MSG_NTF_LOGON_FRIENDSGROUP_FIRECOIN_CHANGE = 18; // 玩家火币变化通知

    ///////////////////////////////中心服///////////////////////////////////////////
    // 和中心服通信的消息定义 10001-20000
    const PLATFORM_MESSAGE_BEGIN = 10000;

    const PLATFORM_MESSAGE_NOTICE = 10001; // 公告
    const PLATFORM_MESSAGE_REQ_ALL_USER_MAIL = 10002; // 请求全服邮件通知
    const PLATFORM_MESSAGE_CLOSE_SERVER = 10003; // 关服，保存数据
    const PLATFORM_MESSAGE_OPEN_SERVER = 10004; // 开服，恢复数据
    const PLATFORM_MESSAGE_SEND_HORN = 10005; // 发送喇叭
    const PLATFORM_MESSAGE_MASTER_DISSMISS_DESK = 10006; // 房主解散房间
    const PLATFORM_MESSAGE_FG_DISSMISS_DESK = 10007; // 俱乐部牌桌解散房间
    const PLATFORM_MESSAGE_RESOURCE_CHANGE = 10008; // （通知某个人）资源变化
    const PLATFORM_MESSAGE_NOTIFY_USERID = 10009; // 向一个玩家推送消息
    const PLATFORM_MESSAGE_NOTIFY_FG = 10010; // 向俱乐部所有玩家推送消息
    const PLATFORM_MESSAGE_IDENTUSER = 10011; // 设置用户身份
    const PLATFORM_MESSAGE_RED_SPOT = 10012; // 请求通知小红点
    const PLATFORM_MESSAGE_RED_FG_SPOT = 10013; // 请求通知俱乐部小红点
    const PLATFORM_MESSAGE_ENTER_OR_LEAVE_CENTER = 10014;//进入或离开娱乐中心大厅

    const PLATFORM_MESSAGE_END = 20000;
}
