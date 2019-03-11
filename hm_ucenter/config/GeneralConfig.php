<?php
namespace config;

final class GeneralConfig {
	const MONEY_TIMES = 100;//金币倍数
	const OTHER_CONFIG_MONEY = ['registerGiveMoney', 'supportMinLimitMoney', 'useMagicExpressCostMoney', 'logonGiveMoneyEveryDay', 'friendRewardMoney', 'sendGiftMyLimitMoney', 'sendGiftMinMoney','bankMinSaveMoney', 'bankMinTakeMoney', 'bankMinTransfer', 'supportMoneyCount', 'regByPhoneSendMoney'];
	const BILL_DETAIL_MONEY = ['front_balance', 'handle_money', 'after_balance', 'amount', 'commission', 'under_amount', 'under_commission'];
	const WEB_RECHARGE_COMMISSION = ['recharge_amount', 'agent_commission'];
	const STATISTICS_MONEYCHANGE_MONEY = ['money', 'changemoney'];
	const TURNTABLE_CONFIG_MONEY = [1];
	const TIMES_RESOURCE_TYPE = [EnumConfig::E_ResourceType['MONEY'], EnumConfig::E_ResourceType['JEWELS'], EnumConfig::E_ResourceType['BANKMONEY'], EnumConfig::E_ResourceType['FIRECOIN']];//倍数变化资源类型
	const SIGN_CONFIG_SOURCE_TYPE = self::TIMES_RESOURCE_TYPE;
	const GAME_CONFIG_MONEY = ['share_send_money', 'BindPhoneSendMoney'];
	const USERINFO_MONEY = ['money', 'bankMoney'];
	const WEB_SHARE_RECORD_MONEY = ['send_money', 'total_get_money'];
	const WEB_TURNTABLE_RECORD_MONEY = ['total_get_money'];

	const BOTTOM_ONLINE_USER = 100;
}
