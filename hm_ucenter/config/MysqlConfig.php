<?php
namespace config;

final class MysqlConfig
{
    /*
     TODO game Table
     */
    const Table_friendsgroupdesklistcost = 'friendsGroupDeskListCost'; //俱乐部牌桌列表消耗记录
    const Table_friendsgroupaccounts = 'friendsGroupAccounts'; //俱乐部战绩记录
    const Table_gamebaseinfo = 'gameBaseInfo'; //游戏基本信息
    const Table_logonbaseinfo = 'logonBaseInfo'; //登录服基本信息
    const Table_otherconfig = 'otherConfig'; //游戏配置
    const Table_privatedeskconfig = 'privateDeskConfig'; //桌子购买信息
    const Table_redisbaseinfo = 'redisBaseInfo'; //redis配置
    const Table_rewardspool = 'rewardsPool'; //奖池配置表
    const Table_roombaseinfo = 'roomBaseInfo'; //房间基本信息
    const Table_statistics_firecoinchange = 'statistics_firecoinchange'; //火币变化
    const Table_statistics_gamerecordinfo = 'statistics_gamerecordinfo'; //游戏记录
    const Table_statistics_jewelschange = 'statistics_jewelschange'; //钻石变化
    const Table_statistics_logonandlogout = 'statistics_logonandlogout'; //登录登出
    const Table_statistics_moneychange = 'statistics_moneychange'; //金币变化
    const Table_statistics_rewardspool = 'statistics_rewardspool'; //奖池记录
    const Table_statistics_day_performance = 'statistics_day_performance'; //奖池记录
    const Table_pumping_ratio = 'pumping_ratio'; //代理奖励规则
    const Table_userinfo = 'userInfo'; //玩家信息

    /*
     TODO web Table
     */

    //管理相关
    const Table_web_admin_action = 'web_admin_action'; //
    const Table_web_admin_group = 'web_admin_group'; //
    const Table_web_admin_log = 'web_admin_log'; //
    const Table_web_admin_member = 'web_admin_member'; //
    const Table_web_admin_menu = 'web_admin_menu'; //
    const Table_web_admin_login_history = 'web_admin_login_history';

    //代理相关
    const Table_web_agent_apply = 'web_agent_apply'; //
    const Table_web_agent_apply_pos = 'web_agent_apply_pos'; //
    const Table_web_agent_audit = 'web_agent_audit'; //
    const Table_web_agent_bind = 'web_agent_bind'; //
    const Table_web_agent_config = 'web_agent_config'; //
    const Table_web_agent_group = 'web_agent_group'; //
    const Table_web_agent_member = 'web_agent_member'; //
    const Table_web_agent_menu = 'web_agent_menu'; //
    const Table_web_agent_exchange = 'web_agent_exchange'; //
    const Table_web_agent_recharge = 'web_agent_recharge'; //
    const Table_web_agent_recharge_config = 'web_agent_recharge_config'; //
    const Table_web_agent_bind_reward = 'web_agent_bind_reward'; // 绑定代理获得的奖励表
    const Table_web_guarantee_amout_log = 'web_guarantee_amout_log'; // 保底金额变化日志表


    const Table_web_api_record = 'web_api_record'; //api记录

    const Table_web_bank_record = 'web_bank_record'; //银行记录

    const Table_web_bill_detail = 'web_bill_detail'; //

    const Table_web_club = 'web_club'; //俱乐部
    const Table_web_club_config = 'web_club_config'; //是否创建俱乐部配置表
    const Table_web_club_member = 'web_club_member'; //俱乐部成员

    const Table_web_code_invitation = 'web_code_invitation'; //

    const Table_web_email = 'web_email'; //邮件

    const Table_web_feedback = 'web_feedback'; //反馈记录
    const Table_web_feedback_reply = 'web_feedback_reply'; //反馈回复记录

    const Table_web_friend = 'web_friend'; //好友记录
    const Table_web_friend_notify = 'web_friend_notify'; //好友通知
    const Table_web_friend_reward = 'web_friend_reward'; //好友打赏

    const Table_web_game_config = 'web_game_config'; //游戏配置

    const Table_web_give_record = 'web_give_record'; //转赠记录

    const Table_web_horn = 'web_horn'; //世界广播记录

    //网页相关
    const Table_web_home_ad = 'web_home_ad'; //
    const Table_web_home_config = 'web_home_config'; //
    const Table_web_home_feedback = 'web_home_feedback'; //
    const Table_web_home_menu = 'web_home_menu'; //
    const Table_web_home_news = 'web_home_news'; //
    const Table_web_home_product = 'web_home_product'; //

    const Table_web_notice = 'web_notice'; //公告记录

    const Table_web_packet_version = 'web_packet_version'; //包版本信息
    const Table_web_packet_version_test = 'web_packet_version_test'; //测试包版本信息

    const Table_web_pay_config = 'web_pay_config'; //支付配置
    const Table_web_pay_goods = 'web_pay_goods'; //商城物品
    const Table_web_pay_orders = 'web_pay_orders'; //支付订单

    const Table_web_phone_code = 'web_phone_code'; // 手机验证码

    const Table_web_recharge_commission = 'web_recharge_commission'; //

    const Table_web_server_black = 'web_server_black'; //黑名单
    const Table_web_server_info = 'web_server_info'; //服务器信息
    const Table_web_server_white = 'web_server_white'; //白名单

    const Table_web_share_code = 'web_share_code'; //
    const Table_web_share_record = 'web_share_record'; //分享记录

    const Table_web_sign_config = 'web_sign_config'; //签到配置
    const Table_web_sign_record = 'web_sign_record'; //签到记录

    const Table_web_socket_config = 'web_socket_config'; //socket配置

    const Table_web_support_record = 'web_support_record'; //补助记录

    const Table_web_turntable_config = 'web_turntable_config'; // 转盘配置
    const Table_web_turntable_record = 'web_turntable_record'; //转盘抽奖记录

    const Table_web_fun_config = 'web_fun_config';//

    const Table_web_users = 'web_users'; //

    const Table_web_vip_room = 'web_vip_room'; //

    const Table_user_cash_bank = 'user_cash_bank'; //用户提现银行卡表
    const Table_user_cash_application = 'user_cash_application'; //用户提现申请表

    //微信相关
    const Table_web_wechat_article = 'web_wechat_article'; //
    const Table_web_wechat_feedback = 'web_wechat_feedback'; //
    const Table_web_wechat_keywords = 'web_wechat_keywords'; //
    const Table_web_wechat_kf = 'web_wechat_kf'; //
    const Table_web_wechat_material = 'web_wechat_material'; //
    const Table_web_wechat_menu = 'web_wechat_menu'; //
    const Table_web_wechat_subscribe = 'web_wechat_subscribe'; //
    const Table_web_wechat_user = 'web_wechat_user'; //
}
