<?php
namespace config;

use manager\DBManager;

final class MysqlTableFieldConfig
{
	const GAME_CONFIG_REGISTER_GIVE_JEWELS = 'registerGiveJewels';//注册送房卡
	const GAME_CONFIG_SEND_HORN_COST_JEWELS = 'sendHornCostJewels';//发送世界广播消耗房卡
	const GAME_CONFIG_BUYING_DESK_COUNT = 'buyingDeskCount';//购买房卡次数
	const GAME_CONFIG_SEND_GIFT_MY_LIMIT_JEWELS = 'sendGiftMyLimitJewels';//赠送要求自己最低房卡数
	const GAME_CONFIG_SEND_GIFT_MIN_JEWELS = 'sendGiftMinJewels';//赠送最低房卡数

    const OTHER_CONFIG_LIST = [
        self::OTHER_CONFIG_REG_BY_PHONE_SEND_MONEY,self::OTHER_CONFIG_CONTACT_QRCODE,self::OTHER_CONFIG_TEST_FIELD_INITIAL_MONEY
    ];
    const OTHER_CONFIG_LIST_DV = [
        self::OTHER_CONFIG_REG_BY_PHONE_SEND_MONEY => ['value' => 0, 'desc' => '手机注册额外送金币'],
        self::OTHER_CONFIG_CONTACT_QRCODE => ['value' => '', 'desc' => 'app客服二维码'],
        self::OTHER_CONFIG_TEST_FIELD_INITIAL_MONEY => ['value' => 500000, 'desc' => '试炼场玩家初始金币'],
    ];
    const OTHER_CONFIG_REG_BY_PHONE_SEND_MONEY = 'regByPhoneSendMoney';//手机注册额外送金币
    const OTHER_CONFIG_CONTACT_QRCODE = 'contactQrcode';//
    const OTHER_CONFIG_TEST_FIELD_INITIAL_MONEY = 'TestFieldInitialMoney';//试炼场玩家初始金币

    public static function initConfigData($table = MysqlConfig::Table_otherconfig) {
        $res = DBManager::getMysql()->queryAll('select * from ' . $table);
        $initData = self::OTHER_CONFIG_LIST;
        $data = [];
        foreach ($res as $k => $v) {
//            var_dump(array_search($v['keyConfig'], $initData));
            if (false !== $index = array_search($v['keyConfig'], $initData)) {
//                var_export($index);
                unset($initData[$index]);
            }
        }
//        $data = array_column($initData, '')
        foreach ($initData as $k => $v) {
            $data = [
                'keyConfig' => $v,
                'valueConfig' => self::OTHER_CONFIG_LIST_DV[$v]['value'],
                'describe' => self::OTHER_CONFIG_LIST_DV[$v]['desc'],
            ];
            DBManager::getMysql()->insert($table, $data);
        }
        var_export($data);
    }
}