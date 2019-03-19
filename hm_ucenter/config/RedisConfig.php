<?php
namespace config;

final class RedisConfig
{
    /*
        TODO String（字符串）
     */
    const String_friendsGroupID = 'friendsGroupID'; //俱乐部自增ID 六位数
    const String_friendsGroupNotifyID = 'friendsGroupNotifyID';//俱乐部通知自增ID
    const String_emailInfoMaxIDSet = 'emailInfoMaxIDSet'; //邮件自增ID TODO 奇怪的ID
    const String_audioID = 'audioID'; //语音自增ID
    const String_fadeOnlineUserNum = 'strFadeOnlineUserNum';//当在线用户低于某数时使用
    const String_phoneCode = 'phoneCode';//phoneCode|phone

    /*
        TODO  Hash（字典）
     */
    const Hash_emailDetailInfo = 'emailDetailInfo'; //邮件详细信息
    const Hash_userToEmailDetailInfo = 'userToEmailDetailInfo'; //玩家对应详细邮件信息
    const Hash_friendsGroup = 'friendsGroup'; //俱乐部信息
    const Hash_friendsGroupToUser = 'friendsGroupToUser'; //俱乐部的玩家信息
    const Hash_userToFriendsGroup = 'userToFriendsGroup'; //玩家的俱乐部信息
    const Hash_friendsGroupNotify = 'friendsGroupNotify'; //俱乐部通知信息
    const Hash_packetVersion = 'packetVersion'; //包版本信息
    const Hash_packetVersionTest = 'packetVersionTest'; //测试包版本信息
    const Hash_webUserInfo = 'webUserInfo'; //玩家统计信息
    const Hash_superiorAgentSet = 'superiorAgentSet'; //上级代理信息
    const Hash_lowerAgentSet = 'lowerAgentSet'; //下级代理信息

    //数据缓存
    const Hash_web_turntable_config = 'web_turntable_config';
    const Hash_web_sign_config = 'web_sign_config';
    const Hash_web_game_config = 'web_game_config';

    /*
        TODO Set（集合）
     */
    const Set_friendsGroupNameSet = 'friendsGroupNameSet'; //俱乐部名字集合
    const Set_friendsGroupIDSet = 'friendsGroupIDSet'; //俱乐部ID集合

    /*
        TODO  Sorted Set（有序集合）
     */
    const SSet_userEmailSet = 'userEmailSet'; //玩家邮件ID的有序集合
    const SSet_friendsGroupToUserSet = 'friendsGroupToUserSet'; //俱乐部的玩家ID有序集合
    const SSet_userToFriendsGroupSet = 'userToFriendsGroupSet'; //玩家的俱乐部ID有序集合
    const SSet_userToFriendsGroupNotifySet = 'userToFriendsGroupNotifySet'; //玩家的俱乐部通知ID有序集合
    const SSet_packetVersionSet = 'packetVersionSet'; //包版本集合
    const SSet_packetVersionTestSet = 'packetVersionTestSet'; //测试包版本集合

    /*
        TODO  List（列表）
     */
}


/*哈希表*/
#define TBL_CACHE_DESK			"privateDeskInfo"			// 字段信息详见PrivateDeskInfo结构体
#define TBL_GRADE_DETAIL		"gradeDetailInfo"			// 单局战绩详情 hash
#define TBL_GRADE_SIMPLE		"gradeSimpleInfo"			// 大结算战绩信息 hash
#define TBL_USER_REDSPOT		"userRedSpotCount"			// 玩家小红点哈希
#define TBL_FRIENDSGROUP		"friendsGroup"				// 俱乐部信息
#define TBL_FRIENDSGROUPTOUSER	"friendsGroupToUser"		// 俱乐部成员（获取设置火币）
#define TBL_USERTOFRIENDSGROUP	"userToFriendsGroup"		// 玩家对俱乐部哈希表（获取玩家权限）
#define TBL_FG_CLOSE_SAVE_DESK  "FGCloseSaveDesk"			// 临时保存在数据库中的牌桌
#define TBL_EMAIL_DETAIL		"emailDetailInfo"			// 每封邮件详情
#define TBL_USER_EMAIL_DETAIL	"userToEmailDetailInfo"		// 玩家的邮件详情(是否已读，是否已经领取)
#define TBL_FG_NOTIFY			"friendsGroupNotify"		// 俱乐部通知

/*字符串、索引*/
#define TBL_CACHE_DESK_PASSWD			"privateDeskPasswd"			// redis key， string类型 保存了deskpasswd和deskMixID的映射
#define TBL_MAX_GRADEKEY				"roomMaxGradeKey"			// 房间最大的战绩ID(战绩详情 单局战绩)
#define TBL_MAX_GRADESIMPLEKEY			"roomMaxGradeSimpleKey"		// 房间最大的战绩ID(战绩简介 大结算)
#define TBL_ROBOT_INFO_INDEX			"robotInfoIndex"			// 机器人信息索引
#define TBL_SERVER_STATUS				"ServerPlatfromStatus"		// 服务器状态
#define TBL_SERVER_STATUS_IP			"ServerPlatfromStatusIP"	// 服务器白名单
#define TBL_MARK_DESK_INDEX				"BuyDeskMarkIndex"			// 购买桌子需要用到的索引
#define TBL_CURMAX_USERID				"curSystemMaxUserID"		// 当前系统最大的userID
#define TBL_USER_BUY_DESK				"sameUserBuyDesk"			// 同步购买桌子数据
#define TBL_FG_BUY_DESK					"sameFGBuyDesk"				// 同步俱乐部购买桌子数据
#define TBL_FG_ROOM_INFO				"FGRoomInfo"				// 俱乐部房间（牌桌）
#define TBL_EMAIL_INFO_MIN_ID_INDEX		"clearEmailInfoMinID"		// 未清理的邮件最小id
#define TBL_EMAIL_INFO_MAX_ID			"emailInfoMaxIDSet"			// 邮件最大id
#define TBL_GRADE_DETAIL_MIN_ID_INDEX	"clearGradeDetailMinID"		// 未清理的单局战绩详情最小id
#define TBL_GRADE_SIMPLE_MIN_ID_INDEX	"clearGradeSimpleMinID"		// 未清理的大结算战绩信息最小id
#define	TBL_FG_NOTIFY_ID				"friendsGroupNotifyID"		// 俱乐部自增id
#define	TBL_FG_NOTIFY_CLEAR_ID			"friendsGroupNotifyClearID" // 俱乐部清理通知id

/*集合*/
#define TBL_SERVER_ONLINE_USER_SET		"allServerOnlineUserSet"		// 玩家在线集合 set
#define CACHE_UPDATE_SET				"cacheUpdateSet"				// 需要更新的玩家数据集合 set
#define CACHE_USER_BUYDESK_SET			"cacheUserBuyDeskSet"			// 玩家开房列表 set
#define CACHE_WIN_COUNT_UPDATE_SET		"cacheWinCountUpdateSet"		// 专门清理每周胜局数 set
#define TBL_GRADE_SIMPLE_SET			"gradeSimpleSet"				// 大结算战绩集合 set(包含了所有小结算的ID)
#define TBL_USER_GRADE_SET				"userGradeSet"					// 玩家的大结算战绩集合  SortedSet
#define TBL_WEB_AGENTMEMBER				"web_agentmember"				// 存放代理的集合 set
#define TBL_FRIENDSGROUPTOUSER_SET		"friendsGroupToUserSet"			// 俱乐部成员（获取俱乐部玩家） SortedSet
#define TBL_USER_EMAIL_SET				"userEmailSet"					// 玩家的邮件集合
#define	TBL_FG_NOTIFY_SET				"userToFriendsGroupNotifySet"	// 俱乐部通知集合
