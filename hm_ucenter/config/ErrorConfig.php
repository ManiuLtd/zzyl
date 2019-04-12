<?php
namespace config;

/**
 * 错误配置
 * Class ErrorConfig
 */
final class ErrorConfig {
	// 错误码
	const SUCCESS_CODE = 0; // 成功
	const ERROR_CODE = 1; // 失败
	const HANDLE_CODE = 10086; // 需要处理的

	// 公共错误
	const ERROR_MSG_SERVER_NOTICE = '服务器异常错误';
	const ERROR_PARAMETER_ERROR = '参数错误';
	const ERROR_HANDLE = '哎呀,出错了!';

	// 服务器
	const ERROR_SERVER_NOTFIND = '暂时没有可用服务器';
	const ERROR_REDIS_NOT_STARTED = 'REDIS服务器未启动';

	// 错误消息
	const SUCCESS_MSG_DEFAULT = '请求成功';
	const ERROR_MSG_DEFAULT = '请求失败';
	const ERROR_MSG_REPEAT = '重复请求';
	const ERROR_MSG_TIMESTAMP = '请求时间错误';
	const ERROR_MSG_PARAMETER = '参数错误';
    const ERROR_NOT_PARAMETER = '参数不能为空';
    const ERROR_NOT_PARAMETER_GESHI = '密码必须包含数字和字母';
    const ERROR_NOT_NUMBER = '金额必须为正数值';
	const ERROR_MSG_UUID = 'UUID不匹配';
	const ERROR_MSG_SIGN = '签名不正确';
	const ERROR_MSG_PLAY_GAME = '游戏中不能操作';
	const ERROR_MSG_DOES_NOT_EXIST = '用户不存在';
	const ERROR_MSG_USERID_REQUIRED = 'userID 参数不能为空';
	const ERROR_MSG_REQUEST_RESTRICTION = '请求限制';
	// 打赏
	const ERROR_MSG_REWARD_RECLAIM = '重复领取';
	const ERROR_MSG_HAVE_BEEN_RECEIVED = '你领取过该打赏或该打赏不存在';
	// 银行
	const ERROR_MSG_BANK_PASSWD_LEN = '请输入6位数字密码';
	const ERROR_MSG_BANK_PASSWD_YES = '密码不正确';
	const ERROR_MSG_BANK_REPASSWD_ATYPISM = '两次密码输入不一致';
	const ERROR_MSG_BANK_OLDPASSWD_YES = '原密码输入不正确';
	const ERROR_MSG_BANK_PASSWD_ISNUMBER = '银行密码长度必须在8到16个字符之间';
    const ERROR_MSG_BANK_REPASSWD_LOGIN = '不能与游戏登录密码的一致';
	const ERROR_MSG_INCORRECT_MAILBOX_FORMAT = '邮箱格式不正确';
	const ERROR_MSG_EMAIL_DONT_IS = '指定邮箱不存在';
	const ERROR_MSG_DEPOSIT_GOLD_COSIN_BEYOND_ITS_OWN_LIMITS = '存款金币超出自身金币';
	const ERROR_MSG_TRANSFER_USERS_DO_NOT_EXIST = '收款用户不存在';
	const ERROR_MSG_DEPOSIT_GOLD_BANK_BEYOND_ITS_OWN_LIMITS = '转账金币超出自身存款金币';
	const ERROR_MSG_TARGETUSER_DO_NOT_EXIST = '目标用户不存在';
	const ERROR_MSG_TARGETUSER_DO_NOT_LOGIN = '目标用户未登录';
	// 好友
	const ERROR_MSG_IT_IS_ALREADY_FRIEND = '已经是好友了';
	const ERROR_MSG_DONTS_ALREADY_FRIEND = '不是好友关系';
	const ERROR_MSG_YOU_CANT_MAKE_FRIENS_WITH_YOURSELF = '不能加自己为好友';
	const ERROR_MSG_ADD_FRIEND_NOTICE = '你已发出申请,请等待对方回应';
	const ERROR_MSG_HAVA_BEEN_TREATED = '该消息不存在或已经处理过';
	const ERROR_MSG_IS_REWARD_USER = '你今天已经打赏过该好友';
	const ERROR_MSG_SEND_MESSAGE_ERROR = '消息通知失败';
	// 救济金
	const ERROR_MSG_UPPER_LIMIT = '当天领取上限';
	const ERROR_MSG_NOT_CONFORMING_TO_THE_CLAIM = '不符合领取条件';
	// 世界广播
	const ERROR_MSG_LACK_OF_DIAMONDS = '钻石不足';

	// 房间不存在
	const ERROR_MSG_THE_ROOM_DOES_NOT_EXIST = '房间不存在';
	// 俱乐部消息
	const ERROR_MSG_CLUBNAME_CANT_NOT_EMPTY = '请输入俱乐部名称';
	const ERROR_MSG_THE_CLUB_DOES_NOT_EXIST = '俱乐部不存在或已被解散';
    const ERROR_MSG_THE_CLUB_DOES_NOT_money= '超过成员所能拥有的最大至尊币数量';
	const ERROR_MSG_YOU_ARE_ALREADY_A_GROUP_OF_OWNERS = '你已经是群主';
	const ERROR_MSG_THE_MESSAGE_DOES_NOT_EXIST = '消息不存在或已经处理过';
	const ERROR_MSG_THE_REQUEST_HAS_BEEN_SUBMITTED = '已经发送加入俱乐部申请';
	const ERROR_MSG_YOU_ARE_ALREADY_A_MEMBER_OF_THE_CLUB = '你已经是该俱乐部成员';
	const THE_NAME_OF_THE_CLUB_HAS_ALREADY_EXITED = '俱乐部名称已经存在';
	const ERROR_MSG_LOGON_CLUB_DOES_NOT_EXIST = '俱乐部不存在';
	const ERROR_MSG_YOU_ARE_NOT_IN_THE_CLUB = '你不在该俱乐部中或已退出';
	const ERROR_MSG_YOU_HAVE_NO_AUTHORITY = '你没有权限';
	//签到或转盘
	const ERROR_MSG_SING_FAIL = '签到失败';
	const ERROR_MSG_SING_TOO = '今天已经签到过了';
	const ERROR_MSG_TURNTABLE_FAIL = '转盘失败';
	const ERROR_MSG_TURNTABLE_CONFIG = '请配置信息';
	const ERROR_MSG_TURNTABLE_TOO = '今天已经抽过奖啦';

	//验证码
    const ERROR_MSG_CODE_TYPE_NO = '验证码类型不存在';
    const ERROR_MSG_CODE_PERSONAL_CENTER = '请前往个人中心绑定手机号';

    //提现
    const ERROR_MSG_BEYOND_MONEY = '兑换金额超出携带金额';
    const ERROR_MSG_LOWER_THAN_MONEY = '兑换金额不得低于100';
    const ERROR_MSG_KEEP_BOTTOM_MONEY = '账户必须留底3元';
    const ERROR_MSG_KEEP_ADD_BANK = '请添加银行账户';
    const ERROR_MSG_KEEP_ADD_ZFB = '请添加支付宝账户';
    const ERROR_MSG_KEEP_ADD_SKZH = '请添加收款账户';
    const ERROR_MSG_KEEP_ADD_TXCS = '今日提现次数已经达到上限,请明日在来吧';

	//手机绑定
	const ERROR_MSG_BIND_TOO = '已绑定';
	const ERROR_MSG_BIND_NOT = '未绑定';
	const ERROR_MSG_BIND_SUCCESS = '绑定成功';
	const ERROR_MSG_BIND_FAIL = '绑定失败';
    const ERROR_MSG_BIND_PHONE_NO = '手机号码不存在';
	const ERROR_MSG_BIND_PHONE_FALSE = '手机号码不正确';
	const ERROR_MSG_BIND_PHONE_NOT = '该手机已被绑定或已注册';
	const ERROR_MSG_PHONE_NOT_BIND = '手机未绑定';
	const ERROR_MSG_BIND_PHONE_TOO = '你已经绑定过';
	const ERROR_MSG_BIND_PHONE_NUMDER = '账号未绑定手机号码';
	const ERROR_MSG_BIND_PHONE_UPPER_LIMIT = '发送已达上限,请明天再试!';
	const ERROR_MSG_BIND_PHONE = '发送失败!';
	const ERROR_MSG_BIND_PHONE_PASSWORD = '两次密码输入不一致';
	const ERROR_MSG_BIND_PHONE_CODE = '验证码不正确';
	const ERROR_MSG_BIND_PHONE_CODE_TOO = '验证码已过期';
	const SUCCESS_MSG_BIND_PHONE = '发送成功';
	const ERROR_MSG_INVITE_NOT = '邀请码不存在';
	const ERROR_MSG_INVITE_OWN = '您不能绑定您自己的邀请码';
	const ERROR_MSG_INVITE_OWN_THREE = '您不能绑定您自己的三级代理';

	//反馈
	const ERROR_MSG_FEEDBACK_ID = '反馈id不存在';
	const ERROR_MSG_FEEDBACK_END = '该条反馈已经结束';
	const SUCCESS_MSG_FEEDBACK = '反馈成功';
	const ERROR_MSG_FEEDBACK = '提交反馈失败';
	const SUCCESS_MSG_FEEDBACK_REPLY = '回复成功';
	const ERROR_MSG_FEEDBACK_REPLY = '回复失败';
	//分享
	const SUCCESS_MSG_SHARE = '分享成功';
	const ERROR_MSG_SHARE = '分享失败';
	//商城
	const ERROR_MSG_ORDER = '商品不存在';
	// 房间
	const ERROR_MSG_DESK_DONT_IT = '房间不存在';

	// 邀请好友
	const ERROR_MSG_YOU_HAVA_INVITED_THE_PLAYER = '你已经邀请过该玩家';

	//消息转发错误码
	const ERROR_MSG_SOCKET_CODE = [
        'PF_SUCCESS' => 0,                 // 操作成功
        'PF_NOUSER' => 1,					// 用户不存在
        'PF_SIZEERROR' => 2,               // 数据长度不对
        'PF_DATA_NULL' => 3,               // 数据包为空
        'PF_NOTICE_NUM_NULL' => 4,         // 公告数值小于0
        'PF_REDIS_NULL' => 5,				// redis为空
        'PF_CLOSE_STATUS_ERR' => 6,		// 发送的停服状态不正确
        'PF_DISSMISS_DESK_ERR' => 7,       // 桌子索引小于0
        'PF_SEND_DATA_ERR' => 8,			// 发送数据失败
        'PF_REDSPOT_NOT_EXIST' => 9,		// 没有记录玩家小红点数据
        'PF_USER_IS_PLAYING' => 10,        // 玩家正在游戏中
        'PF_USER_OFFLINE'    => 11,        // 玩家不在线
    ];

    const ERROR_MSG_SOCKET_CODE_MSG = [
        self::ERROR_MSG_SOCKET_CODE['PF_SUCCESS'] => '操作成功',                 // 操作成功
        self::ERROR_MSG_SOCKET_CODE['PF_NOUSER'] => '用户不存在',					// 用户不存在
        self::ERROR_MSG_SOCKET_CODE['PF_SIZEERROR'] => '数据长度不对',               // 数据长度不对
        self::ERROR_MSG_SOCKET_CODE['PF_DATA_NULL'] => '数据包为空',               // 数据包为空
        self::ERROR_MSG_SOCKET_CODE['PF_NOTICE_NUM_NULL'] => '公告数值小于0',         // 公告数值小于0
        self::ERROR_MSG_SOCKET_CODE['PF_REDIS_NULL'] => 'redis为空',				// redis为空
        self::ERROR_MSG_SOCKET_CODE['PF_CLOSE_STATUS_ERR'] => '发送的停服状态不正确',		// 发送的停服状态不正确
        self::ERROR_MSG_SOCKET_CODE['PF_DISSMISS_DESK_ERR'] => '桌子索引小于0',       // 桌子索引小于0
        self::ERROR_MSG_SOCKET_CODE['PF_SEND_DATA_ERR'] => '发送数据失败',			// 发送数据失败
        self::ERROR_MSG_SOCKET_CODE['PF_REDSPOT_NOT_EXIST'] => '没有记录玩家小红点数据',		// 没有记录玩家小红点数据
        self::ERROR_MSG_SOCKET_CODE['PF_USER_IS_PLAYING'] => '玩家正在游戏中',        // 玩家正在游戏中
        self::ERROR_MSG_SOCKET_CODE['PF_USER_OFFLINE']    => '玩家不在线',        // 玩家不在线
    ];
}
