<?php
namespace config;

/**
 * 枚举配置
 * Class EnumConfig
 */
final class EnumConfig
{
    //资源类型
    const E_ResourceType = array(
        'RMB' => 0, //人民币
        'MONEY' => 1, // 金币
        'JEWELS' => 2, // 钻石
        'BANKMONEY' => 3, // 银行金币
        'FIRECOIN' => 4, // 俱乐部火币
    );

    //资源类型名字
    const E_ResourceTypeName = array(
        self::E_ResourceType['RMB'] => '人民币',
        self::E_ResourceType['MONEY'] => '金币',
        self::E_ResourceType['JEWELS'] => '钻石',
        self::E_ResourceType['BANKMONEY'] => '银行金币',
        self::E_ResourceType['FIRECOIN'] => '俱乐部火币',
    );

    //资源类型名字(接口提示文字)
    const E_ResourceTypeNames = array(
        self::E_ResourceType['RMB'] => '人民币',
        self::E_ResourceType['MONEY'] => '金币',
        self::E_ResourceType['JEWELS'] => '钻石',
        // self::E_ResourceType['BANKMONEY'] => '银行金币',
        self::E_ResourceType['FIRECOIN'] => '至尊币',
    );

    // 资源变化类型
    const E_ResourceChangeReason = array(
        'DEFAULT' => 0,
        'CREATE_ROOM' => 1,              // 创建房间消耗
        'GAME_BEGIN' => 2,               // 游戏开始
        'GAME_FINISHED' => 3,            // 游戏结束
        'GAME_SELLETE_ROLLBACK' => 4,    // 大结算没有生成战绩返还
        'GAME_SELLETE_NORAML' => 5,      // 大结算普通支付
        'GAME_SELLETE_AA' => 6,          // 游戏大结算AA支付
        'GOLD_ROOM_PUMP' => 7,           // 金币房抽水返还
        'ROOM_PUMP_CONSUME' => 8,        // 房间抽水扣除
        'SYSTEM_SUBSIDY' => 9,           // 系统补贴金币(原因是游戏扣除金币扣成了负数)
        'REGISTER' => 10,                // 注册
        'PHONE_REGISTER' => 1022,        //手机注册
        'MAGIC_EXPRESS' => 11,           // 魔法表情

        'BANK_SAVE' => 1000,             // 银行存钱
        'BANK_TAKE' => 1001,             // 银行取钱
        'BANK_TRAN' => 1002,             // 银行转账
        'GIVE' => 1003,                  // 转赠
        'SUPPORT' => 1004,               // 救济金
        'SIGN' => 1005,                  // 签到
        'BACK_RECHARGE' => 1006,         // 后台充值
        'BACK_UNRECHARGE' => 10060,         // 后台提取
        'PAY_RECHARGE' => 1007,          // 商城支付充值
        'AGENT_RECHARGE' => 1008,        // 代理充值
        'TURNTABLE' => 1009,             // 转盘
        'SHARE' => 1010,                 // 分享
        'FRIEND_REWARD' => 1011,         // 好友打赏
        'BIND_PHONE' => 1012,            // 绑定手机
        'SEND_HORN' => 1013,             // 世界广播
        'USER_MAIL' => 1014,             // 个人邮件
        'BIND_AGENT' => 1015,            // 绑定代理
        'INVITE_ENTER' => 1016,          // 邀请进入游戏
        'AGENT_GIVE' => 1017,            // 代理转赠
        'CLUB_RECHARGE' => 1018,         // 俱乐部充值兑换
        'BACK_CLUB_RECHARGE' => 1019,    // 后台俱乐部充值兑换
        'EXIT_CLUB' => 1020,             // 退出俱乐部
        'SYSTEM_MAIL' => 1021,           // 系统邮件
        'CASH_WITHDRAWAL' => 1022,           // 金币兑换申请
        'CASH_WITHDRAWAL_JUJUE' => 1023,           // 金币兑换申请审批拒绝
        'CASH_WITHDRAWAL_TXDXXZH' => 1024,           // 代理提现到游戏账户
    );

    // 资源变化类型
    const E_ResourceChangeReasonName = array(
        self::E_ResourceChangeReason['DEFAULT'] => '默认原因',
        self::E_ResourceChangeReason['CREATE_ROOM'] => '创建房间',
        self::E_ResourceChangeReason['GAME_BEGIN'] => '游戏开始',
        self::E_ResourceChangeReason['GAME_FINISHED'] => '游戏结束',
        self::E_ResourceChangeReason['GAME_SELLETE_ROLLBACK'] => '提前解散',
        self::E_ResourceChangeReason['GAME_SELLETE_NORAML'] => '房主支付',
        self::E_ResourceChangeReason['GAME_SELLETE_AA'] => 'AA支付',
        self::E_ResourceChangeReason['GOLD_ROOM_PUMP'] => '抽水返还',
        self::E_ResourceChangeReason['ROOM_PUMP_CONSUME'] => '抽水扣除',
        self::E_ResourceChangeReason['SYSTEM_SUBSIDY'] => '系统补贴',
        self::E_ResourceChangeReason['REGISTER'] => '注册',
        self::E_ResourceChangeReason['PHONE_REGISTER'] => '手机注册',
        self::E_ResourceChangeReason['MAGIC_EXPRESS'] => '魔法表情',

        self::E_ResourceChangeReason['BANK_SAVE'] => '银行存钱',
        self::E_ResourceChangeReason['BANK_TAKE'] => '银行取钱',
        self::E_ResourceChangeReason['BANK_TRAN'] => '银行转账',
        self::E_ResourceChangeReason['GIVE'] => '转赠',
        self::E_ResourceChangeReason['SUPPORT'] => '救济金',
        self::E_ResourceChangeReason['SIGN'] => '签到',
        self::E_ResourceChangeReason['BACK_RECHARGE'] => '后台充值',
        self::E_ResourceChangeReason['BACK_UNRECHARGE'] => '后台提取',
        self::E_ResourceChangeReason['PAY_RECHARGE'] => '商城充值',
        self::E_ResourceChangeReason['AGENT_RECHARGE'] => '代理充值',
        self::E_ResourceChangeReason['TURNTABLE'] => '转盘抽奖',
        self::E_ResourceChangeReason['SHARE'] => '分享',
        self::E_ResourceChangeReason['FRIEND_REWARD'] => '好友打赏',
        self::E_ResourceChangeReason['BIND_PHONE'] => '绑定手机',
        self::E_ResourceChangeReason['SEND_HORN'] => '世界广播',
        self::E_ResourceChangeReason['USER_MAIL'] => '个人邮件',
        self::E_ResourceChangeReason['BIND_AGENT'] => '绑定代理',
        self::E_ResourceChangeReason['INVITE_ENTER'] => '邀请进入游戏',
        self::E_ResourceChangeReason['AGENT_GIVE'] => '代理转赠',
        self::E_ResourceChangeReason['PAY_RECHARGE'] => '商城充值',
        self::E_ResourceChangeReason['AGENT_RECHARGE'] => '代理充值',
        self::E_ResourceChangeReason['CLUB_RECHARGE'] => '充值兑换',
        self::E_ResourceChangeReason['BACK_CLUB_RECHARGE'] => '后台充值兑换',
        self::E_ResourceChangeReason['EXIT_CLUB'] => '退出俱乐部',
        self::E_ResourceChangeReason['SYSTEM_MAIL'] => '系统邮件',
        self::E_ResourceChangeReason['CASH_WITHDRAWAL'] => '用户提现申请',
        self::E_ResourceChangeReason['CASH_WITHDRAWAL_JUJUE'] => '提现申请审批拒绝',
    );

    //小红点类型
    const E_RedSpotType = array(
        'MAIL' => 0, //邮件小红点
        'FRIEND' => 1, //好友小红点
        'FG' => 2, //俱乐部小红点
    );

    //游戏房间类型
    const E_RoomType = array(
        'GOLD' => 0, //金币场
        'CARD' => 1, //积分房
        'PRIVATE' => 2, //金币房
        'MATCH' => 3, //VIP房
    );

    //游戏房间类型
    const E_RoomTypeName = array(
        self::E_RoomType['GOLD'] => '金币场',
        self::E_RoomType['CARD'] => '积分房',
        self::E_RoomType['PRIVATE'] => '金币房',
        self::E_RoomType['MATCH'] => 'VIP房',
    );

    //////////////////////////////俱乐部相关枚举定义///////////////////////////

    //俱乐部身份
    const E_FriendsGroupMemberStatus = array(
        'NORMAL' => 0, //普通群员
        'KING' => 1, //群主
        'MANAGER' => 2, //管理员
    );

    //俱乐部身份权重 用于比较大小
    const E_FriendsGroupStatusWeight = array(
        self::E_FriendsGroupMemberStatus['NORMAL'] => 0, //普通群员
        self::E_FriendsGroupMemberStatus['MANAGER'] => 2, //管理员
        self::E_FriendsGroupMemberStatus['KING'] => 4, //群主
    );

    //俱乐部权限
    const E_FriendsGroupPowerType = array(
        'NO' => 0, //无权限
        'DEL' => 1, //删除成员
        'DESK' => 2, //创建和解散牌桌
        'VIP' => 4, //创建和解散VIP房
        'FIRE_COIN' => 8, //兑换及充值火币
        'SET' => 16, //设置俱乐部微信或者公告或者改名
        'ALL' => 31, //全部权限，所有权限或运算得到
    );

    //俱乐部 redis userToFriendsGroupSet Score
    const E_Redis_U2F_Set_Score = array(
        'CREATE_FG' => 0, //创建俱乐部
        'JOIN_FG' => 1, //加入俱乐部
    );

    //俱乐部 redis friendsGroupToUserSet Score
    const E_Redis_F2U_Set_Score = array(
        self::E_FriendsGroupMemberStatus['KING'] => 0, //群主
        self::E_FriendsGroupMemberStatus['MANAGER'] => 1, //管理员
        self::E_FriendsGroupMemberStatus['NORMAL'] => 2, //普通群员
    );

    // 俱乐部通知类型
    const E_FriendsGroupNotifyType = array(
        'REQ_JOIN' => 1, // xxx申请加入群xxx
        'INVITE_JOIN' => 2, // xxx邀请您加入群xxx
        'DELETED' => 3, // 您已经被移除群xxx
        'QUIT' => 4, // xxx退出群xxx
        'DISMISS' => 5, // xxx群已经被解散
        'REQ_JOIN_OK' => 6, // 群主同意您加入群xxx
        'REQ_JOIN_FAIL' => 7, // 群主拒绝您加入群xxx
        'ECV_INVITE_JOIN' => 8, // xxx邀请xxx加入群xxx
        'REFUSE_JOIN' => 9, // xxx拒绝加入群xxx
        'AGREE_JOIN' => 10, // xxx同意加入群xxx
        'TRANSFER' => 11, // 玩家xxx将俱乐部xxx转让给了玩家xxx
        'AUTH' => 12, // 您已成为俱乐部XXX，ID（XXX）的管理员
    );

    // 俱乐部加入状态
    const E_FriendsGroupJoinStatus = array(
        'IN' => 1, // 在俱乐部中
        'APPLY' => 2, // 已经发送俱乐部申请
        'NOT' => 3, // 不在俱乐部中
    );

    //银行操作类型
    const E_BankOperateType = array(
        'SAVE' => 1, //银行存款
        'TAKE' => 2, //银行取款
        'TRAN' => 3, //银行转账
    );

    //银行操作类型
    const E_BankOperateTypeName = array(
        self::E_BankOperateType['SAVE'] => '银行存款',
        self::E_BankOperateType['TAKE'] => '银行取款',
        self::E_BankOperateType['TRAN'] => '银行转账',
    );

    //反馈类型
    const E_FeedbackType = array(
        'GAME' => 1, //游戏问题
        'LOGIN' => 2, //登陆问题
        'PAY' => 3, //支付问题
        'REPORT' => 4, //举报
        'OTHER' => 5, //其他
    );

    //反馈状态类型
    const E_FeedbackReadType = array(
        'NONE' => 0, //新反馈
        'NO_READ' => 1, //未读
        'READ' => 2, //已读
        'CLOSE' => 3, //关闭
    );

    //反馈回复状态类型
    const E_FeedbackReplyStatus = array(
        'NONE' => 0, //未答复
        'REPLAY' => 1, //已答复
    );

    //反馈回复类型
    const E_FeedbackReplyType = array(
        'SYSTEM' => 1, //系统回复
        'USER' => 2, //用户回复
    );

    //好友通知类型
    const E_FriendNotifyType = array(
        'REQ_ADD' => 1, //xxx请求添加你为好友
        'ANSWER_ADD' => 2, //xxx同意/拒绝添加你为好友
        'REWARD' => 3, //xxx打赏了你
        'MESSAGE' => 4, //有来自xxx的信息
        'INVITE_PLAYGAME' => 5, //xxx开设了xx房间，邀请你来玩
        'DEL' => 6, //xxx把你删除了

    );

    //在线状态
    const E_UserOnlineStatus = array(
        'OFF' => 0, //离线
        'ON' => 1, //在线
    );

    //在线状态名字
    const E_UserOnlineStatusName = array(
        self::E_UserOnlineStatus['OFF'] => '离线',
        self::E_UserOnlineStatus['ON'] => '在线',
    );

    //好友打赏状态
    const E_FriendRewardStatus = array(
        'OFF' => 0, //未打赏
        'ON' => 1, //已打赏
    );

    //公告类型
    const E_NoticeType = array(
        'NORMAL' => 1, //普通公告 - 跑马灯显示
        'SPECIAL' => 2, //系统公告 -弹框提示
        'STOP' => 3, //停服公告 -弹框提示 并强制客户端退出游戏
        'BIG_EVENT' => 4,//大事件公告
    );

    //公告类型名字
    const E_NoticeTypeName = array(
        self::E_NoticeType['NORMAL'] => '普通公告',
        self::E_NoticeType['SPECIAL'] => '系统公告',
        self::E_NoticeType['STOP'] => '停服公告',
    );

    //手机验证码类型
    const E_PhoneCodeType = array(
        'BIND' => 0, //绑定手机
        'PASSWORD' => 1, //找回密码
        'REGISTER' => 2,//注册
        'CASH' => 3,//提现
    );

    //登录服状态
    const E_LogonServerStatus = array(
        'STOP' => 0, //停止
        'RUN' => 1, //正常
    );

    //操作类型
    const E_OperateType = array(
        'AGREE' => 1, //同意
        'REFUSE' => 2, //拒绝
    );

    //变化类型
    const E_ChangeType = array(
        'UPDATE' => 0, //更新
        'ADD' => 1, //增加
        'DEL' => 2, //删除
    );

    //订单类型
    const E_OrderStatus = array(
        'NEW' => 0, //新创建
        'GIVE' => 1, //已到账
        'PAY_FAIL' => 2, //支付失败
        'NOT_GIVE' => 3, //未到账
    );

    //平台类型
    const E_PlatformType = array(
        'APP_STORE' => 1, //苹果AppStore
        'ANDROID' => 2, //安卓
        'IOS_SIGN' => 3, //苹果企业签名包
    );

    //版本返回类型
    const E_VersionReturnType = array(
        'JSON_ALL' => 0, //返回json 全部信息
        'STRING_VERSION' => 1, //返回string 版本号
        'STRING_ADDRESS' => 2, //返回string 下载地址
    );

    //包版本类型
    const E_VersionPacketType = array(
        'PLATFORM' => 1, //平台包
        'GAME' => 2, //游戏包
    );

    //服务器状态
    const E_ServerStatus = array(
        'NORMAL' => 0, //正常
        'STOP' => 1, //停服
        'TEST' => 2, //测试
        'BLACK' => 3, //黑名单
    );

    //测试服务器状态
    const E_ServerTestStatus = array(
        'OPEN' => 0, //打开
        'CLOSE' => 1, //关闭
    );

    //支付类型
    const E_PayType = array(
        'PING_GUO' => 1, //苹果支付
        'WEI_XIN' => 2,//微信支付
        'HUI_FU_BAO' => 3,//汇付宝
        'WANG_SHI_FU' => 4, //旺实富
        'JIAN_FU' => 5,
        'XIN_BAO' => 6,
        'HUI_FU_BAO_ZL' => 7,
        'MA_FU' => 8,
        'ZHI_FU_BAO' => 9,
    );
    //支付类型名
    const E_PayTypeName = array(
        self::E_PayType['PING_GUO'] => '苹果支付', //苹果支付
        self::E_PayType['WEI_XIN'] => '微信支付',//微信支付
        self::E_PayType['HUI_FU_BAO'] => '汇付宝',//汇付宝
        self::E_PayType['WANG_SHI_FU'] => '旺实富', //旺实富
        self::E_PayType['JIAN_FU'] => '简付',
        self::E_PayType['XIN_BAO'] => '鑫宝',
        self::E_PayType['HUI_FU_BAO_ZL'] => '汇付宝直链',
        self::E_PayType['MA_FU'] => '码付',
        self::E_PayType['ZHI_FU_BAO'] => '支付宝',
    );



    //支付类型状态
    const E_PayTypeStatus = array(
        'CLOSE' => 0,//关闭
        'OPEN' => 1,//打开
    );

    //支付方式
    const E_PayWay = array(
        'WIN_XIN' => 1,//微信
        'ZHI_FU_BAO' => 2,//支付宝
        'QI_TA' => 3,//其他
    );

    //是否第三方支付
    const E_PayThird = array(
        'NO' => 0,//不是
        'IS' => 1,//是
    );

    //设备类型
    const E_DeviceType = array(
        'WINDOWS' => 1,//windows
        'IOS' => 2,//ios
        'ANDROID' => 3,//android
    );

    //邀请玩家奖励领取状态
    const E_ShareCodeRewardStatus = array(
        'NONE' => 0,//被邀请过 未发奖励
        'SEND' => 1,//被邀请过 登陆后发送过奖励
        'NOT' => 2,//没有被邀请过,从app端登录生成数据
    );

    //邀请类型
    const E_InviteEnterType = array(
        'NONE' => 0,//无
        'USER' => 1,//普通用户
        'AGENT' => 2,//代理
    );

    //操作火币变化的ID
    const E_ChangeFireCoinID = array(
        'NONE' => 0,//无
        'BACK' => 1,//后台修改
    );

    //otherConfig表在redis中的类型
    const E_SystemConfigType = array(
        'OTHER' => 1,//其他
        'BANK' => 2,//银行
        'GIVE' => 3,//赠送
        'FG' => 4,//俱乐部
        'FTP' => 5,//FTP
    );

    //登录设备类型
    const E_LoginDeviceType = array(
        'UNKNOWN' => 0,//未知
        'IOS' => 1,//ios
        'ANDROID' => 2,//android
        'WINDOWS' => 3,//windows
    );

    //登录设备类型名
    const E_LoginDeviceTypeName = array(
        self::E_LoginDeviceType['UNKNOWN'] => 'unknown',
        self::E_LoginDeviceType['IOS'] => 'ios',
        self::E_LoginDeviceType['ANDROID'] => 'android',
        self::E_LoginDeviceType['WINDOWS'] => 'windows',
    );

    //游戏用户类型
    const E_GameUserType = array(
        'PLAYER' => 0,//普通玩家
        'ROBOT' => 1,//机器人
    );

    //用户登录类型
    const E_UserLoginType = array(
        'LOBBY_IN' => 1,//大厅登录
        'LOBBY_OUT' => 2,//大厅登出
        'GAME_IN' => 3,//游戏登录
    );

    //用户登录类型
    const E_UserLoginTypeName = array(
        self::E_UserLoginType['LOBBY_IN'] => '大厅登录',
        self::E_UserLoginType['LOBBY_OUT'] => '大厅登出',
        self::E_UserLoginType['GAME_IN'] => '游戏登录',
    );

    //用户性别类型
    const E_UserSexType = array(
        'NV' => 0,//女
        'NAN' => 1,//男
        'NONE' => 2,//保密
    );

    //用户性别类型名字
    const E_UserSexTypeName = array(
        self::E_UserSexType['NV'] => '女',
        self::E_UserSexType['NAN'] => '男',
        self::E_UserSexType['NONE'] => '保密',
    );

    //用户身份类型
    const E_UserStatusType = array(
        'NONE' => 0,//无
        'SUPER' => 1,//超端
        'WIN' => 2,//赢玩家
        'LOSE' => 4,//输玩家
        'BAN' => 8,//封号
    );

    //用户身份类型名字
    const E_UserStatusTypeName = array(
        self::E_UserStatusType['NONE'] => '无',
        self::E_UserStatusType['SUPER'] => '超端',
        self::E_UserStatusType['WIN'] => '赢玩家',
        self::E_UserStatusType['LOSE'] => '输玩家',
        self::E_UserStatusType['BAN'] => '封号',
    );

    //分享类型
    const E_ShareType = array(
        'NONE' => 0,//无
        'WX_FRIEND' => 1,//微信好友
        'WX_ZONE' => 2,//微信朋友圈
        'QQ_FRIEND' => 3,//QQ好友
        'QQ_ZONE' => 4,//QQ空间
    );

    //分享类型名字
    const E_ShareTypeName = array(
        self::E_ShareType['NONE'] => '无',
        self::E_ShareType['WX_FRIEND'] => '微信好友',
        self::E_ShareType['WX_ZONE'] => '微信朋友圈',
        self::E_ShareType['QQ_FRIEND'] => 'QQ好友',
        self::E_ShareType['QQ_ZONE'] => 'QQ空间',
    );

    //代理等级名字
    const E_AgentLevelName = array(
        0 => '普通用户',
        1 => '代理',//统一为代理
//        1 => '一级代理',
        2 => '二级代理',
        3 => '三级代理',
    );

    //商品类型
    const E_PayGoodsBuyType = [
        'MONEY' => 1,//金币
        'CART' => 2,//房卡
        'TOOLS' => 3,//道具
        'MATERIAL' => 4,//实物
    ];

    //充值类型
    const E_RechargeType = [
        'MONEY' => 1,
        'CART' => 2,
    ];

    //分佣受益用户
    const E_CommissionUserType = [
        'AGENT' => 1,//代理
        'OPERATOR' => 2,//平台
    ];

    const E_AdminPageHideType = [
        'ALL' => 1,
        'LEFT_BAR' => 2,
    ];

    const E_AgentStatus = [
        'DISABLED' => 1,//启用
        'INABLE' => 0,//禁用
    ];

    const E_getConfigType = [
        'MYSQL' => 1,
        'REDIS' => 2,
    ];

    const E_GameRecommend = [
        'YES' => 1,
        'NO' => 0,
    ];

    const E_ResetPasswdVerifyType = [
        'OLD_PASSWORD' => 1,//通过旧密码
        'PHONE_CODE' => 2,//通过手机验证码
    ];
    //
    const E_WebHomeProductTeyp = [
        'DOWNLOAD' => 1,
        'ANNOUNCE' => 2,
    ];
    const E_WebHomeProductTeypName = [
        self::E_WebHomeProductTeyp['DOWNLOAD'] => 'web下载',
        self::E_WebHomeProductTeyp['ANNOUNCE'] => 'app图片公告',
    ];

}
