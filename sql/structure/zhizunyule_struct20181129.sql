-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: zhizunyule
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `friendsgroupaccounts`
--

DROP TABLE IF EXISTS `friendsgroupaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendsgroupaccounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主id',
  `friendsGroupID` int(11) DEFAULT '0' COMMENT '俱乐部id',
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `msgID` int(11) DEFAULT '0' COMMENT '消息id',
  `time` int(11) DEFAULT '0' COMMENT '生成时间',
  `roomID` int(11) DEFAULT '0' COMMENT '房间id',
  `realPlayCount` int(11) DEFAULT '0' COMMENT '实际游戏局数',
  `playCount` int(11) DEFAULT '0' COMMENT '最大游戏局数',
  `playMode` int(11) DEFAULT '0' COMMENT '游戏模式',
  `passwd` varchar(20) DEFAULT '' COMMENT '游戏房间号',
  `userInfoList` varchar(1024) DEFAULT '' COMMENT '战绩列表',
  `roomType` int(4) DEFAULT '0' COMMENT '房间类型',
  `srcType` int(4) DEFAULT '0' COMMENT '牌桌号码',
  `roomTipType` int(4) DEFAULT '0' COMMENT '抽水方式',
  `roomTipTypeNums` int(4) DEFAULT '0' COMMENT '抽水率',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE,
  KEY `friendsGroupID` (`friendsGroupID`) USING BTREE,
  KEY `roomType` (`roomType`) USING BTREE,
  KEY `roomID` (`roomID`) USING BTREE,
  KEY `time` (`time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `friendsgroupdesklistcost`
--

DROP TABLE IF EXISTS `friendsgroupdesklistcost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendsgroupdesklistcost` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主id',
  `friendsGroupID` int(11) DEFAULT '0' COMMENT '俱乐部id',
  `time` int(11) DEFAULT '0' COMMENT '生成时间',
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `costMoney` bigint(20) DEFAULT '0' COMMENT '消耗金币',
  `costJewels` int(11) DEFAULT '0' COMMENT '消耗钻石',
  `fireCoinRecharge` bigint(20) DEFAULT '0' COMMENT '火币充值',
  `fireCoinExchange` bigint(20) DEFAULT '0' COMMENT '火币兑换',
  `moneyPump` bigint(20) DEFAULT '0' COMMENT '金币抽水',
  `fireCoinPump` bigint(20) DEFAULT '0' COMMENT '火币抽水',
  `passwd` varchar(20) DEFAULT '' COMMENT '房间号码',
  `operationID` int(11) DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE,
  KEY `friendsGroupID` (`friendsGroupID`) USING BTREE,
  KEY `time` (`time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gamebaseinfo`
--

DROP TABLE IF EXISTS `gamebaseinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gamebaseinfo` (
  `gameID` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id',
  `name` varchar(64) DEFAULT '' COMMENT '游戏名字',
  `dllName` varchar(24) DEFAULT '' COMMENT '动态库名字',
  `deskPeople` int(11) DEFAULT '0' COMMENT '桌子人数',
  `watcherCount` int(11) DEFAULT '0' COMMENT '旁观者人数，canWatch=1的时候才有效',
  `canWatch` int(11) DEFAULT '0' COMMENT '这个游戏是否支持旁观',
  PRIMARY KEY (`gameID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logonbaseinfo`
--

DROP TABLE IF EXISTS `logonbaseinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logonbaseinfo` (
  `logonID` int(6) NOT NULL DEFAULT '0' COMMENT '网关服主id',
  `ip` varchar(24) CHARACTER SET utf8 DEFAULT '' COMMENT '网关服ip地址',
  `port` int(11) DEFAULT '3015' COMMENT '网关服端口',
  `maxPeople` int(11) DEFAULT '3000' COMMENT '网关服最多容纳的最大人数',
  `curPeople` int(11) DEFAULT '0' COMMENT '当前人数',
  `status` tinyint(2) DEFAULT '0' COMMENT '0：关闭状态，1：开启状态',
  PRIMARY KEY (`logonID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `otherconfig`
--

DROP TABLE IF EXISTS `otherconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otherconfig` (
  `keyConfig` varchar(128) NOT NULL DEFAULT '' COMMENT '配置主键',
  `valueConfig` varchar(128) DEFAULT '' COMMENT '配置值',
  `describe` varchar(256) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`keyConfig`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `privatedeskconfig`
--

DROP TABLE IF EXISTS `privatedeskconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privatedeskconfig` (
  `gameID` int(11) DEFAULT '0' COMMENT '游戏id',
  `count` int(11) DEFAULT '0' COMMENT '局数',
  `roomType` int(11) DEFAULT '0' COMMENT '房间类型',
  `costResType` int(11) DEFAULT '2' COMMENT '购买房间需要消耗的资源类型',
  `costNums` int(11) DEFAULT '0' COMMENT '消耗资源数量',
  `AAcostNums` int(11) DEFAULT '0' COMMENT 'AA支付消耗资源数量',
  `otherCostNums` int(11) DEFAULT '0' COMMENT '大赢家支付消耗资源数量',
  `peopleCount` int(11) DEFAULT '0' COMMENT '人数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `redisbaseinfo`
--

DROP TABLE IF EXISTS `redisbaseinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redisbaseinfo` (
  `redisTypeID` int(4) NOT NULL,
  `ip` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT 'redis的ip',
  `port` int(11) DEFAULT '6379' COMMENT 'redis',
  `passwd` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT 'redis密码，没有密码不配置',
  PRIMARY KEY (`redisTypeID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rewardspool`
--

DROP TABLE IF EXISTS `rewardspool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rewardspool` (
  `roomID` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `poolMoney` bigint(20) DEFAULT '0' COMMENT '奖池',
  `gameWinMoney` bigint(20) DEFAULT '0' COMMENT '游戏输赢钱',
  `percentageWinMoney` bigint(20) DEFAULT '0' COMMENT '抽水获取金币数量',
  `otherWinMoney` bigint(20) DEFAULT '0' COMMENT '其它方式获得金币数量',
  `allGameWinMoney` bigint(20) DEFAULT '0' COMMENT '累计输赢金币数量',
  `allPercentageWinMoney` bigint(20) DEFAULT '0' COMMENT '累计抽水',
  `allOtherWinMoney` bigint(20) DEFAULT '0' COMMENT '累计其它方式赢钱',
  `platformCtrlType` tinyint(3) unsigned DEFAULT '0' COMMENT '0：根据platformCtrlPercent值进行控制，1：根据spotControlInfo进行多点控制',
  `platformCtrlPercent` int(11) DEFAULT '0' COMMENT '单点控制千分比，比如500：表示10局赢5局',
  `realPeopleFailPercent` int(11) DEFAULT '0' COMMENT '真人玩家输概率',
  `realPeopleWinPercent` int(11) DEFAULT '0' COMMENT '真人玩家赢概率',
  `spotControlInfo` varchar(64) DEFAULT '' COMMENT '多点控制：100,200,300 这种格式',
  `detailInfo` varchar(512) DEFAULT '' COMMENT '房间控制详情，比如豹子王出豹子概率。100,200,300',
  PRIMARY KEY (`roomID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roombaseinfo`
--

DROP TABLE IF EXISTS `roombaseinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roombaseinfo` (
  `roomID` int(11) NOT NULL,
  `gameID` int(11) DEFAULT '0',
  `name` varchar(48) DEFAULT '',
  `ip` varchar(24) DEFAULT '',
  `port` int(11) DEFAULT '0',
  `serviceName` varchar(48) DEFAULT '',
  `type` int(11) DEFAULT '0',
  `sort` int(11) DEFAULT '0',
  `deskCount` int(11) DEFAULT '1',
  `maxPeople` int(11) DEFAULT '10',
  `minPoint` int(11) DEFAULT '0',
  `maxPoint` int(11) DEFAULT '0',
  `basePoint` int(11) DEFAULT '1' COMMENT '底注',
  `gameBeginCostMoney` int(11) DEFAULT '0' COMMENT '开局消耗',
  `describe` varchar(64) DEFAULT '' COMMENT '房间说明',
  `roomSign` int(11) DEFAULT '0' COMMENT '房间标识',
  `robotCount` int(11) DEFAULT '0' COMMENT '每桌最多的机器人数量',
  `status` tinyint(2) DEFAULT '0' COMMENT '0：未启动，1：已经启动',
  `currPeopleCount` int(11) DEFAULT '0' COMMENT '当前房间人数',
  PRIMARY KEY (`roomID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_firecoinchange`
--

DROP TABLE IF EXISTS `statistics_firecoinchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_firecoinchange` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `time` int(11) DEFAULT '0' COMMENT '时间',
  `fireCoin` bigint(20) DEFAULT '0' COMMENT '玩家变化之后的火币',
  `changeFireCoin` bigint(20) DEFAULT '0' COMMENT '变化火币值',
  `reason` int(11) DEFAULT '0' COMMENT '变化原因',
  `roomID` int(11) DEFAULT '0' COMMENT '房间id',
  `friendsGroupID` int(11) DEFAULT '0' COMMENT '俱乐部id',
  `rateFireCoin` int(11) DEFAULT '0' COMMENT '抽水火币',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_gamerecordinfo`
--

DROP TABLE IF EXISTS `statistics_gamerecordinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_gamerecordinfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `passwd` varchar(20) DEFAULT '' COMMENT '房间号',
  `deskIdx` int(11) DEFAULT '-1',
  `roomID` int(11) DEFAULT '0',
  `createTime` int(11) DEFAULT '0',
  `beginTime` int(11) DEFAULT '0',
  `finishedTime` int(11) DEFAULT '0',
  `userIDList` varchar(1024) DEFAULT '' COMMENT '战绩列表',
  PRIMARY KEY (`id`),
  KEY `roomID` (`roomID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_jewelschange`
--

DROP TABLE IF EXISTS `statistics_jewelschange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_jewelschange` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  `jewels` int(11) DEFAULT '0',
  `changeJewels` int(11) DEFAULT '0',
  `reason` int(11) DEFAULT '0',
  `roomID` int(11) DEFAULT '0',
  `friendsGroupID` int(11) DEFAULT '0',
  `rateJewels` int(11) DEFAULT '0' COMMENT '钻石抽水',
  `status` tinyint(1) DEFAULT '0' COMMENT '计算代理提成使用',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE,
  KEY `time` (`time`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_logonandlogout`
--

DROP TABLE IF EXISTS `statistics_logonandlogout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_logonandlogout` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT '0',
  `type` int(11) DEFAULT '0' COMMENT '1：大厅登录，2：大厅登出，3：金币场登录',
  `time` int(11) DEFAULT '0' COMMENT '操作时间',
  `ip` varchar(24) DEFAULT '',
  `platformType` int(11) DEFAULT '0' COMMENT '登陆平台类型。如果登录金币场，记录的是roomID',
  `macAddr` varchar(64) DEFAULT '' COMMENT '物理地址',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=862 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_moneychange`
--

DROP TABLE IF EXISTS `statistics_moneychange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_moneychange` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT '0' COMMENT '金币变化用户',
  `time` int(11) DEFAULT '0' COMMENT '时间',
  `money` bigint(20) DEFAULT '0' COMMENT '变化之后的金币',
  `changeMoney` bigint(20) DEFAULT '0' COMMENT '变化金币',
  `reason` int(11) DEFAULT '0' COMMENT '变化原因',
  `roomID` int(11) DEFAULT '0' COMMENT '房间id',
  `friendsGroupID` int(11) DEFAULT '0',
  `rateMoney` bigint(20) DEFAULT '0' COMMENT '抽水金币',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `time` (`time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_rewardspool`
--

DROP TABLE IF EXISTS `statistics_rewardspool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_rewardspool` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `time` bigint(20) DEFAULT '0' COMMENT '生成时间',
  `roomID` int(11) DEFAULT '0' COMMENT '房间id',
  `poolMoney` bigint(20) DEFAULT '0' COMMENT '奖池',
  `gameWinMoney` bigint(20) DEFAULT '0' COMMENT '游戏输赢钱',
  `percentageWinMoney` bigint(20) DEFAULT '0' COMMENT '抽水获取金币数量',
  `otherWinMoney` bigint(20) DEFAULT '0' COMMENT '其它方式获得金币数量',
  PRIMARY KEY (`id`),
  KEY `roomID` (`roomID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userInfo`
--

DROP TABLE IF EXISTS `userInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userInfo` (
  `userID` int(11) NOT NULL,
  `account` varchar(64) DEFAULT '',
  `passwd` varchar(64) DEFAULT '',
  `name` varchar(64) DEFAULT '',
  `phone` varchar(24) DEFAULT '',
  `sex` tinyint(2) DEFAULT '1',
  `mail` varchar(64) DEFAULT '',
  `money` bigint(20) DEFAULT '0',
  `bankMoney` bigint(20) DEFAULT '0',
  `bankPasswd` varchar(20) DEFAULT '',
  `jewels` int(11) DEFAULT '0',
  `roomID` int(11) DEFAULT '0' COMMENT '???????r',
  `deskIdx` int(11) DEFAULT '-1',
  `logonIP` varchar(24) DEFAULT '' COMMENT '??i',
  `headURL` varchar(256) DEFAULT '',
  `winCount` int(11) DEFAULT '0',
  `macAddr` varchar(64) DEFAULT '',
  `token` varchar(64) DEFAULT '' COMMENT '??t',
  `isVirtual` tinyint(2) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '???????1',
  `reqSupportTimes` int(11) DEFAULT '0',
  `lastReqSupportDate` int(11) DEFAULT '0',
  `registerTime` int(11) DEFAULT '0',
  `registerIP` varchar(24) DEFAULT '' COMMENT '??i',
  `registerType` tinyint(2) DEFAULT '1',
  `buyingDeskCount` int(11) DEFAULT '0',
  `lastCrossDayTime` int(11) DEFAULT '0',
  `totalGameCount` int(11) DEFAULT '0',
  `sealFinishTime` int(11) DEFAULT '0' COMMENT '?????-1?????0',
  `wechat` varchar(24) DEFAULT '',
  `phonePasswd` varchar(64) DEFAULT '',
  `accountInfo` varchar(64) DEFAULT '',
  `totalGameWinCount` int(11) DEFAULT '0',
  `Lng` varchar(12) DEFAULT '',
  `Lat` varchar(12) DEFAULT '',
  `address` varchar(64) DEFAULT '',
  `lastCalcOnlineToTime` bigint(20) DEFAULT '0',
  `allOnlineToTime` bigint(20) DEFAULT '0',
  `IsOnline` tinyint(2) DEFAULT '0' COMMENT '0?????1',
  `motto` varchar(128) DEFAULT '',
  `xianliao` varchar(64) DEFAULT '',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `userID` int(11) NOT NULL,
  `account` varchar(64) DEFAULT '' COMMENT '账号',
  `passwd` varchar(64) DEFAULT '' COMMENT '密码',
  `name` varchar(64) DEFAULT '' COMMENT '玩家名字',
  `phone` varchar(24) DEFAULT '' COMMENT '电话号码',
  `sex` tinyint(2) DEFAULT '1' COMMENT '性别',
  `mail` varchar(64) DEFAULT '' COMMENT '邮件',
  `money` bigint(20) DEFAULT '0' COMMENT '玩家金币',
  `bankMoney` bigint(20) DEFAULT '0' COMMENT '银行金币',
  `bankPasswd` varchar(20) DEFAULT '' COMMENT '银行密码',
  `jewels` int(11) DEFAULT '0' COMMENT '钻石数量',
  `roomID` int(11) DEFAULT '0' COMMENT '玩家所有房间的roomID',
  `deskIdx` int(11) DEFAULT '-1' COMMENT '玩家所在的桌子索引',
  `logonIP` varchar(24) DEFAULT '' COMMENT '登陆ip',
  `headURL` varchar(256) DEFAULT '' COMMENT '头像',
  `winCount` int(11) DEFAULT '0' COMMENT '胜局数',
  `macAddr` varchar(64) DEFAULT '' COMMENT '物理地址',
  `token` varchar(64) DEFAULT '' COMMENT '链接token',
  `isVirtual` tinyint(2) DEFAULT '0' COMMENT '是否是机器人',
  `status` int(11) DEFAULT '0' COMMENT '标识玩家身份，1超端',
  `reqSupportTimes` int(11) DEFAULT '0' COMMENT '领取救济金次数',
  `lastReqSupportDate` int(11) DEFAULT '0' COMMENT '上次领取救济金时间',
  `registerTime` int(11) DEFAULT '0' COMMENT '注册时间',
  `registerIP` varchar(24) DEFAULT '' COMMENT '注册ip',
  `registerType` tinyint(2) DEFAULT '1' COMMENT '注册类型',
  `buyingDeskCount` int(11) DEFAULT '0' COMMENT '已经购买的桌子数量，不包括俱乐部牌桌',
  `lastCrossDayTime` int(11) DEFAULT '0' COMMENT '上次登录时间',
  `totalGameCount` int(11) DEFAULT '0' COMMENT '总共游戏局数',
  `sealFinishTime` int(11) DEFAULT '0' COMMENT '封号时间，-1永久封号，0正常',
  `wechat` varchar(24) DEFAULT '' COMMENT '玩家微信',
  `phonePasswd` varchar(64) DEFAULT '' COMMENT '手机登陆密码',
  `accountInfo` varchar(64) DEFAULT '' COMMENT '微信号信息',
  `totalGameWinCount` int(11) DEFAULT '0' COMMENT '累计赢局数',
  `Lng` varchar(12) DEFAULT '' COMMENT '经度',
  `Lat` varchar(12) DEFAULT '' COMMENT '纬度',
  `address` varchar(64) DEFAULT '' COMMENT '地址',
  `lastCalcOnlineToTime` bigint(20) DEFAULT '0' COMMENT '上次计算在线时长的时间',
  `allOnlineToTime` bigint(20) DEFAULT '0' COMMENT '总共在线时长',
  `IsOnline` tinyint(2) DEFAULT '0' COMMENT '0：不在线，1：在线',
  `motto` varchar(128) DEFAULT '' COMMENT '玩家的个性签名',
  `xianliao` varchar(64) DEFAULT '' COMMENT '闲聊唯一信息',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_admin_action`
--

DROP TABLE IF EXISTS `web_admin_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_admin_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminuser` varchar(30) DEFAULT NULL,
  `actionType` int(11) DEFAULT NULL COMMENT '操作类型',
  `resourceType` int(11) DEFAULT NULL COMMENT '资源类型',
  `resourceNum` int(11) DEFAULT NULL COMMENT '资源数量',
  `actionTime` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `actionDesc` varchar(50) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户操作记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_admin_group`
--

DROP TABLE IF EXISTS `web_admin_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_admin_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL COMMENT '名称',
  `rules` varchar(255) DEFAULT NULL COMMENT '节点',
  `desc` varchar(200) DEFAULT NULL COMMENT '描述',
  `disabled` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台用户角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_admin_log`
--

DROP TABLE IF EXISTS `web_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_admin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '后台用户id',
  `_action` varchar(255) DEFAULT NULL COMMENT '行为',
  `_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8 COMMENT='操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_admin_member`
--

DROP TABLE IF EXISTS `web_admin_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_admin_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL COMMENT '账号',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `group_id` int(11) DEFAULT NULL COMMENT '角色id',
  `register_time` int(11) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` varchar(30) DEFAULT NULL,
  `disabled` int(11) DEFAULT NULL,
  `user_operation` tinyint(2) DEFAULT '0' COMMENT '用户操作权限 0不可以操作 1可以操作',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_admin_menu`
--

DROP TABLE IF EXISTS `web_admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `link_url` varchar(200) DEFAULT NULL COMMENT '节点路径',
  `hide` int(11) DEFAULT NULL COMMENT '1关闭 0 显示',
  `pid` int(11) DEFAULT NULL COMMENT '父id',
  `level` int(11) DEFAULT NULL COMMENT '排序 越小越在前',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COMMENT='后台节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_apply`
--

DROP TABLE IF EXISTS `web_agent_apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL COMMENT '名称',
  `weixin` varchar(100) DEFAULT NULL COMMENT '微信号',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `user_id` int(11) DEFAULT NULL COMMENT '游戏id',
  `apply_ip` varchar(50) DEFAULT NULL COMMENT 'IP地址',
  `addtime` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理申请表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_apply_pos`
--

DROP TABLE IF EXISTS `web_agent_apply_pos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_apply_pos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) DEFAULT NULL COMMENT '名称',
  `userid` int(11) DEFAULT NULL COMMENT '游戏id',
  `level_agent` int(11) DEFAULT NULL COMMENT '代理等级',
  `bankcard` varchar(50) DEFAULT NULL COMMENT '银行卡',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信号',
  `apply_time` int(11) DEFAULT NULL COMMENT '提现时间',
  `front_balance` int(11) DEFAULT NULL COMMENT '提现前总金额',
  `after_balance` int(11) DEFAULT NULL COMMENT '提现后总金额',
  `apply_amount` int(11) DEFAULT NULL COMMENT '提现金额',
  `status` int(11) DEFAULT NULL COMMENT '0 是待审核 1 是通过 2 未通过',
  `agentid` varchar(10) DEFAULT NULL COMMENT '邀请码',
  `handle_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `agentid` (`agentid`),
  KEY `username` (`username`),
  KEY `apply_time` (`apply_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理提现记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_audit`
--

DROP TABLE IF EXISTS `web_agent_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_audit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) DEFAULT NULL COMMENT '手机账号',
  `userid` int(11) DEFAULT NULL COMMENT '游戏id',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `agent_level` int(11) DEFAULT NULL COMMENT '代理等级',
  `superior_agentid` varchar(10) DEFAULT NULL COMMENT '上级代理邀请码',
  `agentid` varchar(10) DEFAULT NULL COMMENT '代理邀请码',
  `superior_username` varchar(30) DEFAULT NULL COMMENT '上级代理手机账号',
  `register_time` int(11) DEFAULT NULL COMMENT '申请时间',
  `login_count` int(11) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` varchar(30) DEFAULT NULL,
  `last_login_address` varchar(100) DEFAULT NULL,
  `wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
  `bankcard` varchar(50) DEFAULT NULL COMMENT '银行卡',
  `balance` int(11) DEFAULT NULL COMMENT '总佣金',
  `disabled` int(11) DEFAULT NULL,
  `under_money` int(11) DEFAULT NULL COMMENT '直属佣金',
  `not_under_money` int(11) DEFAULT NULL COMMENT '非直属佣金',
  `email` varchar(50) DEFAULT NULL,
  `history_pos_money` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `username` (`username`),
  KEY `agentid` (`agentid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理审核';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_bind`
--

DROP TABLE IF EXISTS `web_agent_bind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL COMMENT '游戏id',
  `agentID` varchar(10) DEFAULT NULL COMMENT '邀请码',
  `username` varchar(50) DEFAULT NULL COMMENT '昵称',
  `agentname` varchar(50) DEFAULT NULL COMMENT '上级手机号码',
  `bind_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `agentID` (`agentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请码绑定表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_bind_reward`
--

DROP TABLE IF EXISTS `web_agent_bind_reward`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_bind_reward` (
  `userID` int(11) NOT NULL COMMENT '用户ID',
  `money` int(11) DEFAULT '0' COMMENT '绑定给的金币数',
  `jewels` int(11) DEFAULT '0' COMMENT '绑定给的钻石数',
  `time` int(11) DEFAULT '0' COMMENT '给奖励的时间',
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_config`
--

DROP TABLE IF EXISTS `web_agent_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `_desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='代理配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_exchange`
--

DROP TABLE IF EXISTS `web_agent_exchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jewels` int(11) DEFAULT NULL COMMENT '兑换的钻石数',
  `money` int(11) DEFAULT NULL COMMENT '金币数',
  `ratio` int(8) DEFAULT NULL COMMENT '对不比例 钻石:金币 1:?',
  `exchange_time` int(11) DEFAULT NULL COMMENT '兑换时间',
  `userID` int(11) NOT NULL COMMENT '游戏id',
  `status` tinyint(2) DEFAULT '1' COMMENT '1 是正常  0 是异常',
  PRIMARY KEY (`id`,`userID`),
  KEY `userID` (`userID`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='兑换记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_feedback`
--

DROP TABLE IF EXISTS `web_agent_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `userID` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `type` bigint(2) DEFAULT NULL COMMENT '类型',
  `status` bigint(2) DEFAULT NULL COMMENT '1 是未处理 2 已处理',
  `reply` text COMMENT '回复内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理反馈';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_group`
--

DROP TABLE IF EXISTS `web_agent_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL,
  `rules` varchar(255) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `disabled` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_member`
--

DROP TABLE IF EXISTS `web_agent_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `userid` int(11) DEFAULT NULL COMMENT '游戏id',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `agent_level` int(11) DEFAULT NULL COMMENT '代理等级',
  `superior_agentid` varchar(10) DEFAULT NULL COMMENT '上级代理邀请码',
  `agentid` varchar(10) DEFAULT NULL COMMENT '代理邀请码',
  `superior_username` varchar(30) DEFAULT NULL COMMENT '代理手机号码',
  `register_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `login_count` int(11) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` varchar(30) DEFAULT NULL,
  `last_login_address` varchar(100) DEFAULT NULL,
  `wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
  `bankcard` varchar(50) DEFAULT NULL COMMENT '银行卡',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '总佣金',
  `disabled` int(11) DEFAULT NULL,
  `under_money` int(11) NOT NULL DEFAULT '0' COMMENT '直属佣金',
  `not_under_money` int(11) NOT NULL DEFAULT '0' COMMENT '非直属佣金',
  `email` varchar(50) DEFAULT NULL,
  `history_pos_money` int(11) NOT NULL DEFAULT '0' COMMENT '已提现金额',
  `status` int(11) DEFAULT NULL,
  `wx_token` varchar(100) DEFAULT NULL,
  `rel_name` varchar(100) DEFAULT NULL,
  `media_id` varchar(150) DEFAULT NULL,
  `is_franchisee` tinyint(1) DEFAULT '0' COMMENT '加盟商 0否 1是',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `username` (`username`),
  KEY `agentid` (`agentid`),
  KEY `status` (`status`),
  KEY `register_time` (`register_time`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='代理会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_menu`
--

DROP TABLE IF EXISTS `web_agent_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) DEFAULT NULL,
  `link_url` varchar(200) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_recharge`
--

DROP TABLE IF EXISTS `web_agent_recharge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(255) DEFAULT NULL COMMENT '游戏id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1 是微信充值 2我支付宝充值 3 是银行卡充值',
  `money` float(8,2) DEFAULT NULL COMMENT '充值金额',
  `account` varchar(50) DEFAULT NULL COMMENT '账号',
  `recharge_time` int(11) DEFAULT NULL COMMENT '充值时间',
  `status` tinyint(2) DEFAULT '1' COMMENT '1 带审核 2 充值成功',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理后台充值记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_agent_recharge_config`
--

DROP TABLE IF EXISTS `web_agent_recharge_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_agent_recharge_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `pay_account` varchar(50) DEFAULT NULL COMMENT '收款账号',
  `type` tinyint(2) DEFAULT NULL COMMENT '1 是微信 2我支付宝 3 银行卡',
  `body` varchar(255) DEFAULT NULL COMMENT '微信支付宝支付就存二维码,银行卡就存卡号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='充值配置新';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_api_record`
--

DROP TABLE IF EXISTS `web_api_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_api_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `api` varchar(256) NOT NULL DEFAULT '' COMMENT '请求url关键参数',
  `sign` varchar(128) NOT NULL,
  `ip` varchar(32) NOT NULL DEFAULT '0.0.0.0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`sign`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_bank_record`
--

DROP TABLE IF EXISTS `web_bank_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_bank_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主id',
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `type` int(11) DEFAULT '0' COMMENT '类型',
  `targetID` int(11) DEFAULT '0' COMMENT '目标类型',
  `money` bigint(20) DEFAULT '0' COMMENT '存储金币',
  `time` int(11) DEFAULT '0' COMMENT '生成时间',
  `total_cost_bank_money` int(11) DEFAULT '0' COMMENT '累计转出银行金币',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_bill_detail`
--

DROP TABLE IF EXISTS `web_bill_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_bill_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(11) DEFAULT NULL,
  `agent_level` int(11) DEFAULT NULL,
  `front_balance` int(11) DEFAULT NULL,
  `handle_money` int(11) DEFAULT NULL,
  `after_balance` int(11) DEFAULT NULL,
  `_desc` varchar(200) DEFAULT NULL,
  `make_time` int(11) DEFAULT NULL,
  `make_userid` int(11) DEFAULT NULL,
  `make_name` varchar(50) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `under_amount` int(11) DEFAULT NULL,
  `under_commission` int(11) DEFAULT NULL,
  `agentid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `make_userid` (`make_userid`),
  KEY `make_time` (`make_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_broadcast`
--

DROP TABLE IF EXISTS `web_broadcast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_broadcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_club`
--

DROP TABLE IF EXISTS `web_club`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_club` (
  `friendsGroupID` int(11) NOT NULL COMMENT '俱乐部ID',
  `name` varchar(32) NOT NULL COMMENT '俱乐部名字',
  `masterID` int(11) NOT NULL DEFAULT '0' COMMENT '群主ID',
  `hotCount` int(11) NOT NULL DEFAULT '0' COMMENT '热度',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`friendsGroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_club_member`
--

DROP TABLE IF EXISTS `web_club_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_club_member` (
  `friendsGroupID` int(11) NOT NULL COMMENT '俱乐部ID',
  `userID` int(11) NOT NULL COMMENT '成员id',
  `power` int(11) NOT NULL DEFAULT '0' COMMENT '权限',
  `joinTime` int(11) NOT NULL DEFAULT '0' COMMENT '加入时间',
  PRIMARY KEY (`friendsGroupID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_code_invitation`
--

DROP TABLE IF EXISTS `web_code_invitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_code_invitation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `invite_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `diamonds` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_email`
--

DROP TABLE IF EXISTS `web_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_email` (
  `emailID` int(11) NOT NULL COMMENT '邮件ID',
  `sendtime` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `isHaveGoods` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含附件',
  `senderID` int(11) NOT NULL DEFAULT '0' COMMENT '发送者ID',
  `contentCount` int(11) NOT NULL DEFAULT '0' COMMENT '内容长度',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '内容',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `mailType` int(11) NOT NULL DEFAULT '0' COMMENT '奖励类型',
  `goodsList` varchar(256) NOT NULL DEFAULT '' COMMENT '邮件奖励',
  PRIMARY KEY (`emailID`),
  KEY `mailType` (`mailType`),
  KEY `sendtime` (`sendtime`),
  KEY `senderID` (`senderID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_feedback`
--

DROP TABLE IF EXISTS `web_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL COMMENT '游戏id',
  `username` varchar(50) DEFAULT NULL COMMENT '游戏名称',
  `content` varchar(255) DEFAULT NULL COMMENT '反馈内容',
  `type` int(11) DEFAULT NULL COMMENT '反馈类型 1游戏问题 2登录问题 3支付问题 4举报 5其他',
  `f_time` int(11) DEFAULT NULL COMMENT '反馈时间',
  `read_type` int(11) DEFAULT NULL COMMENT '反馈状态 0是未回复 1是已回复 2是用户已查看 3是结束反馈',
  `has_back` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='游戏反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_feedback_reply`
--

DROP TABLE IF EXISTS `web_feedback_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_feedback_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `c_content` varchar(200) DEFAULT NULL COMMENT '回复内容',
  `c_id` int(11) DEFAULT NULL COMMENT '反馈id',
  `c_type` int(11) DEFAULT NULL COMMENT '1是后台回复,2是客户信息',
  `c_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='反馈回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_friend`
--

DROP TABLE IF EXISTS `web_friend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_friend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `friendID` int(11) DEFAULT '0' COMMENT '好友id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_friend_notify`
--

DROP TABLE IF EXISTS `web_friend_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_friend_notify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',
  `type` int(11) DEFAULT '0' COMMENT '通知类型',
  `targetID` int(11) DEFAULT '0' COMMENT '目标玩家id',
  `param1` int(11) DEFAULT '0' COMMENT '特殊参数1',
  `param2` int(11) DEFAULT '0' COMMENT '特殊参数2',
  `time` int(11) DEFAULT '0' COMMENT '生成时间',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `targetID` (`targetID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_friend_reward`
--

DROP TABLE IF EXISTS `web_friend_reward`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_friend_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `targetID` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `notifyID` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `intime` (`notifyID`) USING BTREE,
  KEY `intargetID` (`targetID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_game_config`
--

DROP TABLE IF EXISTS `web_game_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_game_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_give_record`
--

DROP TABLE IF EXISTS `web_give_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_give_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL COMMENT '用户ID',
  `targetID` int(11) NOT NULL COMMENT '目标用户ID',
  `type` int(11) NOT NULL COMMENT '资源类型',
  `value` bigint(11) NOT NULL COMMENT '资源数量',
  `real_value` bigint(11) NOT NULL COMMENT '实际数量',
  `time` int(11) NOT NULL COMMENT '时间',
  `total_cost_money` bigint(11) DEFAULT '0' COMMENT '累计转赠金币',
  `total_cost_jewels` bigint(11) DEFAULT '0' COMMENT '累计转赠钻石',
  `total_money_count` int(11) DEFAULT '0' COMMENT '累计转赠金币次数',
  `total_jewels_count` int(11) DEFAULT '0' COMMENT '累计转赠钻石次数',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_ad`
--

DROP TABLE IF EXISTS `web_home_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_config`
--

DROP TABLE IF EXISTS `web_home_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL,
  `value` varchar(2000) DEFAULT NULL,
  `desc` varchar(800) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_feedback`
--

DROP TABLE IF EXISTS `web_home_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_menu`
--

DROP TABLE IF EXISTS `web_home_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(30) DEFAULT NULL,
  `link_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_news`
--

DROP TABLE IF EXISTS `web_home_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `content` longtext,
  `author` varchar(50) DEFAULT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_home_product`
--

DROP TABLE IF EXISTS `web_home_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_home_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_horn`
--

DROP TABLE IF EXISTS `web_horn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_horn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `reqTime` int(11) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `content` varchar(1024) DEFAULT NULL,
  `total_cost_jewels` int(11) DEFAULT '0' COMMENT '累计消耗钻石',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_notice`
--

DROP TABLE IF EXISTS `web_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '公告类型',
  `title` varchar(24) NOT NULL COMMENT '公告标题',
  `content` varchar(1024) NOT NULL COMMENT '公告内容',
  `begin_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `time` int(11) NOT NULL,
  `interval` int(11) DEFAULT '0' COMMENT '间隔',
  `times` int(11) DEFAULT '1' COMMENT '次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_packet_version`
--

DROP TABLE IF EXISTS `web_packet_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_packet_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `packet_type` int(11) NOT NULL COMMENT '包类型',
  `packet_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT '包名字',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `address` varchar(256) NOT NULL COMMENT '下载地址',
  `check_version` varchar(20) DEFAULT '' COMMENT '审核版本',
  `desc` varchar(50) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_packet_version_test`
--

DROP TABLE IF EXISTS `web_packet_version_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_packet_version_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `packet_type` int(11) NOT NULL COMMENT '包类型',
  `packet_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT '包名字',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `address` varchar(256) NOT NULL COMMENT '下载地址',
  `check_version` varchar(20) DEFAULT '' COMMENT '审核版本',
  `desc` varchar(50) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_pay_config`
--

DROP TABLE IF EXISTS `web_pay_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_pay_config` (
  `type` int(11) unsigned NOT NULL COMMENT '支付类型',
  `name` varchar(32) NOT NULL COMMENT '名字',
  `app_id` varchar(64) NOT NULL COMMENT '应用ID',
  `mch_id` varchar(64) NOT NULL COMMENT '商户ID',
  `private_key` varchar(128) NOT NULL COMMENT '私钥',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `public_key` varchar(128) DEFAULT '' COMMENT '公钥',
  `notify_url` varchar(256) DEFAULT '' COMMENT '回调地址',
  `return_url` varchar(256) DEFAULT '' COMMENT '成功返回页面',
  `version` varchar(32) DEFAULT '1' COMMENT '版本号',
  `third` int(11) NOT NULL DEFAULT '1' COMMENT '第三方',
  PRIMARY KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_pay_goods`
--

DROP TABLE IF EXISTS `web_pay_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_pay_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyGoods` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `buyNum` int(11) DEFAULT NULL COMMENT '商品数量',
  `buyType` int(11) DEFAULT NULL COMMENT '商品类型 1金币 2房卡 3道具 4实物',
  `consumeGoods` varchar(50) DEFAULT NULL COMMENT '消耗资源',
  `consumeNum` int(11) DEFAULT NULL COMMENT '消耗数量',
  `consumeType` int(11) DEFAULT NULL COMMENT '消耗类型 1 人民币 2 金币 3房卡',
  `buyCount` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `status` int(11) DEFAULT NULL COMMENT '是否上架 1是 0否',
  `goodsID` int(11) DEFAULT NULL,
  `is_New` int(11) DEFAULT NULL COMMENT '是否上新',
  `is_Hot` int(11) DEFAULT NULL COMMENT '是否最热',
  `appleID` varchar(20) DEFAULT NULL COMMENT '苹果支付 appleID',
  `picName` int(11) DEFAULT NULL,
  `goods_img` varchar(200) DEFAULT NULL COMMENT '实物商品图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_pay_orders`
--

DROP TABLE IF EXISTS `web_pay_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_pay_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单号',
  `userID` int(11) DEFAULT NULL COMMENT '游戏id',
  `buyGoods` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `buyNum` int(11) DEFAULT NULL COMMENT '数量',
  `buyType` int(11) DEFAULT NULL COMMENT '商品类型 1 金币 2 砖石 4 实物',
  `consumeGoods` varchar(50) DEFAULT NULL COMMENT '消耗资源名称',
  `consumeNum` int(11) DEFAULT NULL COMMENT '消耗资源类型',
  `consumeType` int(11) DEFAULT NULL COMMENT '消耗资源类型',
  `status` int(11) DEFAULT NULL COMMENT '状态 ',
  `create_time` int(11) DEFAULT NULL,
  `pay_desc` varchar(100) DEFAULT NULL COMMENT '描述',
  `realname` varchar(50) DEFAULT NULL COMMENT '名称',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `handle` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '游戏昵称',
  `handleTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `order_sn` (`order_sn`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='订单列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_phone_code`
--

DROP TABLE IF EXISTS `web_phone_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_phone_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` char(24) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `code` char(24) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_recharge_commission`
--

DROP TABLE IF EXISTS `web_recharge_commission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_recharge_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recharge_userid` int(11) DEFAULT NULL COMMENT '充值玩家ID',
  `recharge_name` varchar(50) DEFAULT NULL COMMENT '充值名字',
  `recharge_order_sn` varchar(50) DEFAULT NULL COMMENT '充值订单号',
  `recharge_amount` int(11) DEFAULT NULL COMMENT '充值金额',
  `agent_level` int(11) DEFAULT NULL COMMENT '代理等级',
  `agent_username` varchar(50) DEFAULT NULL COMMENT '代理手机号码',
  `agent_commission` int(11) DEFAULT NULL COMMENT '分佣数量',
  `agent_userid` int(11) DEFAULT NULL COMMENT '代理玩家ID',
  `level` int(11) DEFAULT NULL COMMENT '分佣层级',
  `buy_type` int(11) DEFAULT NULL COMMENT '购买资源类型',
  `buy_num` int(11) DEFAULT NULL COMMENT '购买数量',
  `time` int(11) DEFAULT NULL COMMENT '分佣时间',
  PRIMARY KEY (`id`),
  KEY `recharge_userid` (`recharge_userid`),
  KEY `agent_userid` (`agent_userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_server_black`
--

DROP TABLE IF EXISTS `web_server_black`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_server_black` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL COMMENT 'IP',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_server_info`
--

DROP TABLE IF EXISTS `web_server_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_server_info` (
  `server_id` int(11) NOT NULL COMMENT '服务器ID',
  `server_status` int(11) NOT NULL COMMENT '服务器状态 0正常 1停服 2测试',
  `server_test_status` int(11) NOT NULL COMMENT '测试服状态 0打开 1关闭',
  PRIMARY KEY (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_server_white`
--

DROP TABLE IF EXISTS `web_server_white`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_server_white` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL COMMENT 'IP',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_share_code`
--

DROP TABLE IF EXISTS `web_share_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_share_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unionid` varchar(128) DEFAULT NULL,
  `invite_userid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_share_record`
--

DROP TABLE IF EXISTS `web_share_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_share_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `share_address` int(11) DEFAULT NULL,
  `share_time` int(11) DEFAULT NULL,
  `send_money` int(11) DEFAULT NULL,
  `send_jewels` int(11) DEFAULT NULL,
  `total_get_money` int(11) DEFAULT '0' COMMENT '累计获取金币',
  `total_get_jewels` int(11) DEFAULT '0' COMMENT '累计获取钻石',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_sign_config`
--

DROP TABLE IF EXISTS `web_sign_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_sign_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dateNum` int(11) DEFAULT NULL,
  `prize` varchar(50) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `prizeType` int(11) DEFAULT NULL,
  `picNum` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_sign_record`
--

DROP TABLE IF EXISTS `web_sign_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_sign_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prize` varchar(20) DEFAULT NULL,
  `prizeType` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `dateNum` int(11) DEFAULT NULL,
  `signTime` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `total_get_money` int(11) DEFAULT '0' COMMENT '累计获取金币',
  `total_get_jewels` int(11) DEFAULT '0' COMMENT '累计获取钻石',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `signTime` (`signTime`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_socket_config`
--

DROP TABLE IF EXISTS `web_socket_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_socket_config` (
  `socketTypeID` int(4) NOT NULL,
  `ip` varchar(128) DEFAULT '127.0.0.1' COMMENT 'socket的ip',
  `port` int(11) DEFAULT '4015' COMMENT 'socket的端口',
  PRIMARY KEY (`socketTypeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='socket连接配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_support_record`
--

DROP TABLE IF EXISTS `web_support_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_support_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL COMMENT '用户ID',
  `time` int(11) NOT NULL,
  `money` bigint(20) NOT NULL DEFAULT '0',
  `total_get_money` int(11) DEFAULT '0' COMMENT '累计获取金币',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_turntable_config`
--

DROP TABLE IF EXISTS `web_turntable_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_turntable_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prize` varchar(50) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `chance` double DEFAULT NULL,
  `prizeType` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_turntable_record`
--

DROP TABLE IF EXISTS `web_turntable_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_turntable_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `turntableTime` int(11) DEFAULT NULL,
  `prize` varchar(50) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `prizeType` int(11) DEFAULT NULL,
  `total_get_money` int(11) DEFAULT '0' COMMENT '累计获取金币',
  `total_get_jewels` int(11) DEFAULT '0' COMMENT '累计获取钻石',
  `total_count` int(11) DEFAULT '0' COMMENT '累计次数',
  `total_reward_count` int(11) DEFAULT '0' COMMENT '累计中奖次数',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `turntableTime` (`turntableTime`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_vip_room`
--

DROP TABLE IF EXISTS `web_vip_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_vip_room` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `roomID` int(11) DEFAULT NULL,
  `roomPassword` int(11) DEFAULT NULL,
  `roomBeginTime` int(11) DEFAULT NULL,
  `roomEndTime` int(11) DEFAULT NULL,
  `agentID` varchar(10) DEFAULT NULL,
  `canChangePassword` int(11) DEFAULT NULL,
  `bindBeginTime` int(11) DEFAULT NULL,
  `bindEndTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `gameID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_article`
--

DROP TABLE IF EXISTS `web_wechat_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `content` longtext,
  `thumb_media_id` varchar(100) DEFAULT NULL,
  `digest` varchar(255) DEFAULT NULL,
  `source_url` varchar(100) DEFAULT NULL,
  `show_cover` int(11) DEFAULT NULL,
  `need_open_comment` int(11) DEFAULT NULL,
  `only_fans_can_comment` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `multiple` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_feedback`
--

DROP TABLE IF EXISTS `web_wechat_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(100) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `add_ip` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_keywords`
--

DROP TABLE IF EXISTS `web_wechat_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `content` longtext,
  `status` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_kf`
--

DROP TABLE IF EXISTS `web_wechat_kf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_kf` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `invite_status` int(11) DEFAULT NULL,
  `invite_wx` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_material`
--

DROP TABLE IF EXISTS `web_wechat_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_material` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `media_url` varchar(100) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `video_title` varchar(100) DEFAULT NULL,
  `video_desc` varchar(100) DEFAULT NULL,
  `local` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_menu`
--

DROP TABLE IF EXISTS `web_wechat_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `key` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `pagepath` varchar(100) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_subscribe`
--

DROP TABLE IF EXISTS `web_wechat_subscribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_subscribe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) DEFAULT NULL,
  `content` longtext,
  `status` int(11) DEFAULT NULL,
  `image_mediaid` int(11) DEFAULT NULL,
  `music_mediaid` int(11) DEFAULT NULL,
  `video_mediaid` int(11) DEFAULT NULL,
  `news_mediaid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `web_wechat_user`
--

DROP TABLE IF EXISTS `web_wechat_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_wechat_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT NULL,
  `unionid` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `tagid_list` varchar(100) DEFAULT NULL,
  `subscribe` int(11) DEFAULT NULL,
  `batchblock` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `headimgurl_file` varchar(200) DEFAULT NULL,
  `media_id_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-29 13:42:24
