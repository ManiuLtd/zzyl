-- MySQL dump 10.13  Distrib 5.6.40, for Linux (x86_64)
--
-- Host: localhost    Database: zhizunyule
-- ------------------------------------------------------
-- Server version	5.6.40-log

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
-- Table structure for table `friendsGroupAccounts`
--

DROP TABLE IF EXISTS `friendsGroupAccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendsGroupAccounts` (
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
-- Dumping data for table `friendsGroupAccounts`
--

LOCK TABLES `friendsGroupAccounts` WRITE;
/*!40000 ALTER TABLE `friendsGroupAccounts` DISABLE KEYS */;
INSERT INTO `friendsGroupAccounts` VALUES (1,100002,119004,0,1542856938,3,6,6,1,'831780','119002,-300|119004,291|#119002,0|119004,9|#119002,3|119004,3|#',3,1,1,3),(2,100002,119004,0,1542857302,3,6,6,1,'127210','119004,291|119002,-300|#119004,9|119002,0|#119004,4|119002,2|#',3,1,1,3);
/*!40000 ALTER TABLE `friendsGroupAccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friendsGroupDeskListCost`
--

DROP TABLE IF EXISTS `friendsGroupDeskListCost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendsGroupDeskListCost` (
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
-- Dumping data for table `friendsGroupDeskListCost`
--

LOCK TABLES `friendsGroupDeskListCost` WRITE;
/*!40000 ALTER TABLE `friendsGroupDeskListCost` DISABLE KEYS */;
INSERT INTO `friendsGroupDeskListCost` VALUES (1,100002,1542856696,119002,0,0,50000,0,0,0,'',119004),(2,100002,1542856702,119004,0,0,50000,0,0,0,'',119004),(3,100002,1542856938,119004,0,3,0,0,0,9,'831780',0),(4,100002,1542857302,119004,0,3,0,0,0,9,'127210',0),(5,100006,1542931116,119002,0,0,100,0,0,0,'',119002),(6,100022,1542969688,119036,0,0,10000,0,0,0,'',119036),(7,100022,1542969696,119037,0,0,10000,0,0,0,'',119036),(8,100023,1542985291,119039,0,0,1000,0,0,0,'',119039),(9,100004,1542989349,119001,0,0,10000,0,0,0,'',119001),(10,100023,1543050344,119038,0,0,1000,0,0,0,'',119039),(11,100023,1543050395,119045,0,0,1000,0,0,0,'',119039),(12,100025,1543051043,119047,0,0,55555,0,0,0,'',119047),(13,100025,1543051046,119046,0,0,555555,0,0,0,'',119047),(14,100019,1543052255,119004,0,0,100000,0,0,0,'',119004),(15,100019,1543052259,119014,0,0,10000,0,0,0,'',119004),(16,100024,1543056585,119001,0,0,1000,0,0,0,'',119001),(17,100026,1543076694,119051,0,0,10000,0,0,0,'',119051),(18,100003,1543197928,119004,0,0,20000,0,0,0,'',119013),(19,100003,1543197957,119013,0,0,200000,0,0,0,'',119013),(20,100003,1543241215,119011,0,0,100000,0,0,0,'',119011),(21,100003,1543241221,119010,0,0,1000000,0,0,0,'',119011);
/*!40000 ALTER TABLE `friendsGroupDeskListCost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gameBaseInfo`
--

DROP TABLE IF EXISTS `gameBaseInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gameBaseInfo` (
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
-- Dumping data for table `gameBaseInfo`
--

LOCK TABLES `gameBaseInfo` WRITE;
/*!40000 ALTER TABLE `gameBaseInfo` DISABLE KEYS */;
INSERT INTO `gameBaseInfo` VALUES (10001000,'奔驰宝马','10001000.DLL',180,180,0),(10000900,'豹子王','10000900.DLL',180,180,0),(11100200,'欢乐30秒','11100200.DLL',180,180,0),(11100604,'牌九','11100604.DLL',4,4,0),(12101105,'炸金花','12101105.DLL',5,5,1),(20170405,'21点','20170405.DLL',4,4,0),(30000004,'跑得快','30000004.DLL',3,3,0),(30000200,'百人牛牛','30000200.DLL',180,180,0),(30000404,'十三水','30000404.DLL',5,5,0),(30100008,'牛牛','30100008.DLL',8,8,1),(11000100,'飞禽走兽','11000100.DLL',180,0,0),(30100108,'三公','30100108.DLL',8,8,1);
/*!40000 ALTER TABLE `gameBaseInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logonBaseInfo`
--

DROP TABLE IF EXISTS `logonBaseInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logonBaseInfo` (
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
-- Dumping data for table `logonBaseInfo`
--

LOCK TABLES `logonBaseInfo` WRITE;
/*!40000 ALTER TABLE `logonBaseInfo` DISABLE KEYS */;
INSERT INTO `logonBaseInfo` VALUES (1,'192.168.0.64',3015,1500,0,0),(2,'192.168.0.64',3016,2000,0,0),(3,'192.168.0.64',3017,1500,0,0),(4,'192.168.0.64',3018,1500,0,0);
/*!40000 ALTER TABLE `logonBaseInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otherConfig`
--

DROP TABLE IF EXISTS `otherConfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otherConfig` (
  `keyConfig` varchar(128) NOT NULL DEFAULT '' COMMENT '配置主键',
  `valueConfig` varchar(128) DEFAULT '' COMMENT '配置值',
  `describe` varchar(256) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`keyConfig`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otherConfig`
--

LOCK TABLES `otherConfig` WRITE;
/*!40000 ALTER TABLE `otherConfig` DISABLE KEYS */;
INSERT INTO `otherConfig` VALUES ('supportTimesEveryDay','2','每天领取救济金次数'),('supportMinLimitMoney','200','低于多少金币领取救济金'),('supportMoneyCount','4','每次领取救济金的金币数量每次领取救济金的金币数量'),('registerGiveMoney','100000','注册送金币'),('registerGiveJewels','100','注册送房卡'),('logonGiveMoneyEveryDay','0','每日登录赠送金币数'),('sendHornCostJewels','5','发送世界广播消耗房卡'),('useMagicExpressCostMoney','3','发送魔法表情消耗金币数'),('friendRewardMoney','100','用户打赏金币'),('friendRewardMoneyCount','5','用户打赏金币次数'),('buyingDeskCount','300','购买房卡次数'),('sendGiftMyLimitMoney','500','赠送要求自己最低金币数'),('sendGiftMyLimitJewels','100','赠送要求自己最低房卡数'),('sendGiftMinMoney','100','赠送最低金币数'),('sendGiftMinJewels','100','赠送最低房卡数'),('sendGiftRate','0.01','赠送系统扣除的税率'),('bankMinSaveMoney','100','银行保存最低金币数'),('bankSaveMoneyMuti','10','银行存钱必须是这个倍数'),('bankMinTakeMoney','100','银行最低取钱数'),('bankTakeMoneyMuti','10','银行取钱必须是这个倍数'),('bankMinTransfer','100','银行最低转账数'),('bankTransferMuti','10','银行转账必须是这个数的倍数'),('groupCreateCount','5','俱乐部创建限制'),('groupMemberCount','150','俱乐部人数限制'),('groupJoinCount','10','加入俱乐部限制'),('groupManageRoomCount','5','俱乐部管理房间人数限制'),('groupRoomCount','1','俱乐部房间限制'),('groupAllAlterNameCount','3','俱乐部更新ID总次数限制'),('groupEveAlterNameCount','1','俱乐部每日更新ID次数限制'),('friendTakeRewardMoneyCount','10','用户打赏金币次数'),('groupTransferCount','3','俱乐部最多能授权管理员数量'),('ftpIP','172.18.105.236','ftp服务器ip'),('ftpUser','ftpuser','ftp服务器账号'),('ftpPasswd','123456','ftp服务器密码');
/*!40000 ALTER TABLE `otherConfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privateDeskConfig`
--

DROP TABLE IF EXISTS `privateDeskConfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privateDeskConfig` (
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
-- Dumping data for table `privateDeskConfig`
--

LOCK TABLES `privateDeskConfig` WRITE;
/*!40000 ALTER TABLE `privateDeskConfig` DISABLE KEYS */;
INSERT INTO `privateDeskConfig` VALUES (30100008,6,2,1,0,0,0,0),(30100008,12,2,1,0,0,0,0),(30100108,6,2,1,0,0,0,0),(30100108,12,2,1,0,0,0,0),(30000004,6,2,1,0,0,0,0),(30000004,12,2,1,0,0,0,0),(12101105,6,2,1,0,0,0,0),(12101105,12,2,1,0,0,0,0),(30000404,6,2,1,0,0,0,0),(30000404,12,2,1,0,0,0,0),(11100604,6,2,1,0,0,0,0),(11100604,12,2,1,0,0,0,0),(20170405,6,2,1,0,0,0,0),(20170405,12,2,1,0,0,0,0),(30100008,6,3,1,3,1,0,0),(30100008,12,3,1,6,2,0,0),(30100108,6,3,1,3,1,0,0),(30100108,12,3,1,6,2,0,0),(30000004,6,3,1,3,1,0,0),(30000004,12,3,1,6,2,0,0),(12101105,6,3,1,3,1,0,0),(12101105,12,3,1,6,2,0,0),(30000404,6,3,1,3,1,0,0),(30000404,12,3,1,6,2,0,0),(11100604,6,3,1,3,1,0,0),(11100604,12,3,1,6,2,0,0),(20170405,6,3,1,3,1,0,0),(20170405,12,3,1,6,2,0,0);
/*!40000 ALTER TABLE `privateDeskConfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `redisBaseInfo`
--

DROP TABLE IF EXISTS `redisBaseInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redisBaseInfo` (
  `redisTypeID` int(4) NOT NULL,
  `ip` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT 'redis的ip',
  `port` int(11) DEFAULT '6379' COMMENT 'redis',
  `passwd` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT 'redis密码，没有密码不配置',
  PRIMARY KEY (`redisTypeID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redisBaseInfo`
--

LOCK TABLES `redisBaseInfo` WRITE;
/*!40000 ALTER TABLE `redisBaseInfo` DISABLE KEYS */;
INSERT INTO `redisBaseInfo` VALUES (1,'192.168.0.64',6380,''),(2,'192.168.0.64',6381,'');
/*!40000 ALTER TABLE `redisBaseInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rewardsPool`
--

DROP TABLE IF EXISTS `rewardsPool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rewardsPool` (
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
-- Dumping data for table `rewardsPool`
--

LOCK TABLES `rewardsPool` WRITE;
/*!40000 ALTER TABLE `rewardsPool` DISABLE KEYS */;
INSERT INTO `rewardsPool` VALUES (1,0,0,0,0,0,0,0,0,0,0,0,'',''),(2,0,33000,0,0,0,0,0,0,0,0,0,'',''),(3,0,0,0,0,0,0,0,0,0,0,0,'',''),(4,0,0,0,0,0,0,0,0,0,0,0,'',''),(5,0,0,0,0,0,0,0,0,0,0,0,'',''),(6,0,0,0,0,0,0,0,0,0,0,0,'',''),(7,0,0,0,0,0,0,0,0,0,0,0,'',''),(8,0,9000,0,0,0,0,0,0,0,0,0,'',''),(9,0,0,0,0,0,0,0,0,0,0,0,'',''),(10,0,0,0,0,0,0,0,0,0,0,0,'',''),(11,0,0,0,0,0,0,0,0,0,0,0,'',''),(12,0,0,0,0,0,0,0,0,0,0,0,'',''),(13,0,0,0,0,0,0,0,0,0,0,0,'',''),(14,0,0,0,0,0,0,0,0,0,0,0,'',''),(15,0,0,0,0,0,0,0,0,0,0,0,'',''),(16,0,0,0,0,0,0,0,0,0,0,0,'',''),(17,0,0,0,0,0,0,0,0,0,0,0,'',''),(18,0,0,0,0,0,0,0,0,0,0,0,'',''),(19,0,0,0,0,0,0,0,0,0,0,0,'',''),(20,0,3000,0,0,0,0,0,0,0,0,0,'',''),(21,0,0,0,0,0,0,0,0,0,0,0,'',''),(22,0,0,0,0,0,0,0,0,0,0,0,'',''),(23,0,0,0,0,0,0,0,0,0,0,0,'',''),(24,0,0,0,0,0,0,0,0,0,0,0,'',''),(25,0,0,0,0,0,0,0,0,0,0,0,'',''),(26,0,0,0,0,0,0,0,0,0,0,0,'',''),(27,0,0,0,0,0,0,0,0,0,0,0,'',''),(28,0,0,0,0,0,0,0,0,0,0,0,'',''),(29,0,0,0,0,0,0,0,0,0,0,0,'',''),(30,0,0,0,0,0,0,0,0,0,0,0,'',''),(31,0,0,0,0,0,0,0,0,0,0,0,'',''),(32,0,18000,0,0,0,0,0,0,0,0,0,'',''),(33,0,0,0,0,0,0,0,0,0,0,0,'',''),(34,0,0,0,0,0,0,0,0,0,0,0,'',''),(35,0,0,0,0,0,0,0,0,0,0,0,'',''),(36,0,0,0,0,0,0,0,0,0,0,0,'',''),(37,0,0,0,0,0,0,0,0,0,0,0,'',''),(38,0,0,0,0,0,0,0,0,0,0,0,'',''),(39,0,0,0,0,0,0,0,0,0,0,0,'',''),(40,0,0,0,0,0,0,0,0,0,0,0,'',''),(41,0,0,0,0,0,0,0,0,0,0,0,'',''),(42,0,0,0,0,0,0,0,0,0,0,0,'',''),(43,0,0,0,0,0,0,0,0,0,0,0,'',''),(44,0,0,0,0,0,0,0,0,0,0,0,'',''),(45,0,0,0,0,0,0,0,0,0,0,0,'',''),(46,0,0,0,0,0,0,0,0,0,0,0,'',''),(47,0,0,0,0,0,0,0,0,0,0,0,'',''),(48,0,0,0,0,0,0,0,0,0,0,0,'',''),(49,0,0,0,0,0,0,0,0,0,0,0,'',''),(50,0,0,0,0,0,0,0,0,0,0,0,'',''),(51,0,0,0,0,0,0,0,0,0,0,0,'',''),(52,0,0,0,0,0,0,0,0,0,0,0,'',''),(53,0,0,0,0,0,0,0,0,0,0,0,'',''),(54,0,0,0,0,0,0,0,0,0,0,0,'',''),(55,0,0,0,0,0,0,0,0,0,0,0,'',''),(56,0,0,0,0,0,0,0,0,0,0,0,'',''),(57,0,0,0,0,0,0,0,0,0,0,0,'','');
/*!40000 ALTER TABLE `rewardsPool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roomBaseInfo`
--

DROP TABLE IF EXISTS `roomBaseInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roomBaseInfo` (
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
  `level` tinyint(2) DEFAULT '0' COMMENT '房间级别:0 房卡场 1 初级场 2 中级场 3 高级场',
  PRIMARY KEY (`roomID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roomBaseInfo`
--

LOCK TABLES `roomBaseInfo` WRITE;
/*!40000 ALTER TABLE `roomBaseInfo` DISABLE KEYS */;
INSERT INTO `roomBaseInfo` VALUES (1,30000200,'百人牛牛初级场','192.168.0.64',4001,'zzyl',0,1,1,180,0,0,100,0,'经典玩法',0,0,0,0,1),(2,30000200,'百人牛牛中级场','192.168.0.64',4002,'zzyl',0,1,1,180,0,0,500,0,'经典玩法',0,0,0,0,2),(3,30000200,'百人牛牛高级场','192.168.0.64',4003,'zzyl',0,1,1,180,0,0,1500,0,'经典玩法',0,0,0,0,3),(4,11100200,'欢乐30秒初级场','192.168.0.64',4004,'zzyl',0,1,1,180,0,0,100,0,'经典玩法',0,0,0,0,1),(5,11100200,'欢乐30秒中级场','192.168.0.64',4005,'zzyl',0,1,1,180,0,0,200,0,'经典玩法',0,0,0,0,2),(6,11100200,'欢乐30秒高级场','192.168.0.64',4006,'zzyl',0,1,1,180,0,0,500,0,'经典玩法',0,0,0,0,3),(7,10000900,'豹子王初级场','192.168.0.64',4007,'zzyl',0,1,1,180,0,0,100,0,'经典玩法',0,0,0,0,1),(8,10000900,'豹子王中级场','192.168.0.64',4008,'zzyl',0,1,1,180,0,0,200,0,'经典玩法',0,0,0,0,2),(9,10000900,'豹子王高级场','192.168.0.64',4009,'zzyl',0,1,1,180,0,0,500,0,'经典玩法',0,0,0,0,3),(10,10001000,'奔驰宝马初级场','192.168.0.64',4010,'zzyl',0,1,1,180,0,0,100,0,'经典玩法',0,0,0,0,1),(11,10001000,'奔驰宝马中级场','192.168.0.64',4011,'zzyl',0,1,1,180,0,0,200,0,'经典玩法',0,0,0,0,2),(12,10001000,'奔驰宝马高级场','192.168.0.64',4012,'zzyl',0,1,1,180,0,0,500,0,'经典玩法',0,0,0,0,3),(13,11000100,'飞禽走兽初级场','192.168.0.64',4013,'zzyl',0,1,1,180,0,0,100,0,'经典玩法',0,0,0,0,1),(14,11000100,'飞禽走兽中级场','192.168.0.64',4014,'zzyl',0,1,1,180,0,0,200,0,'经典玩法',0,0,0,0,2),(15,11000100,'飞禽走兽高级场','192.168.0.64',4015,'zzyl',0,1,1,180,0,0,500,0,'经典玩法',0,0,0,0,3),(16,30100008,'牛牛金币房','192.168.0.64',4016,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(17,30100008,'牛牛VIP房','192.168.0.64',4017,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(18,30100108,'三公金币房','192.168.0.64',4018,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(19,30100108,'三公VIP房','192.168.0.64',4019,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(20,30000004,'跑得快金币房','192.168.0.64',4020,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(21,30000004,'跑得快VIP房','192.168.0.64',4021,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(22,12101105,'炸金花金币房','192.168.0.64',4022,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(23,12101105,'炸金花VIP房','192.168.0.64',4023,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(24,30000404,'十三水金币房','192.168.0.64',4024,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(25,30000404,'十三水VIP房','192.168.0.64',4025,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(26,11100604,'牌九金币房','192.168.0.64',4026,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(27,11100604,'牌九VIP房','192.168.0.64',4027,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(28,20170405,'21点金币房','192.168.0.64',4028,'zzyl',2,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0),(29,20170405,'21点VIP房','192.168.0.64',4029,'zzyl',3,0,200,200,0,0,100,0,'经典玩法',0,0,0,0,0);
/*!40000 ALTER TABLE `roomBaseInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics_firecoinchange`
--

DROP TABLE IF EXISTS `statistics_firecoinchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_firecoinchange` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT '0' COMMENT '玩家id',#赢家或输家id
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
-- Dumping data for table `statistics_firecoinchange`
--

LOCK TABLES `statistics_firecoinchange` WRITE;
/*!40000 ALTER TABLE `statistics_firecoinchange` DISABLE KEYS */;
INSERT INTO `statistics_firecoinchange` VALUES (1,119002,1542856696,50000,50000,1018,0,100002,0,0),(2,119004,1542856702,50000,50000,1018,0,100002,0,0),(3,119002,1542856772,49600,-400,3,3,100002,0,0),(4,119004,1542856772,50400,400,3,3,100002,0,0),(5,119002,1542856810,49300,-300,3,3,100002,0,0),(6,119004,1542856810,50700,300,3,3,100002,0,0),(7,119002,1542856842,49500,200,3,3,100002,0,0),(8,119004,1542856842,50500,-200,3,3,100002,0,0),(9,119002,1542856879,49700,200,3,3,100002,0,0),(10,119004,1542856879,50300,-200,3,3,100002,0,0),(11,119002,1542856912,49900,200,3,3,100002,0,0),(12,119004,1542856912,50100,-200,3,3,100002,0,0),(13,119002,1542856936,49700,-200,3,3,100002,0,0),(14,119004,1542856936,50300,200,3,3,100002,0,0),(15,119004,1542856938,50291,-9,8,3,100002,0,0),(16,119004,1542857144,50391,100,3,3,100002,0,0),(17,119002,1542857144,49600,-100,3,3,100002,0,0),(18,119004,1542857172,50591,200,3,3,100002,0,0),(19,119002,1542857172,49400,-200,3,3,100002,0,0),(20,119004,1542857200,50391,-200,3,3,100002,0,0),(21,119002,1542857200,49600,200,3,3,100002,0,0),(22,119004,1542857215,50791,400,3,3,100002,0,0),(23,119002,1542857215,49200,-400,3,3,100002,0,0),(24,119004,1542857256,50191,-600,3,3,100002,0,0),(25,119002,1542857256,49800,600,3,3,100002,0,0),(26,119004,1542857300,50591,400,3,3,100002,0,0),(27,119002,1542857300,49400,-400,3,3,100002,0,0),(28,119004,1542857302,50582,-9,8,3,100002,0,0),(29,119002,1542931116,100,100,1018,0,100006,0,0),(30,119036,1542969688,10000,10000,1018,0,100022,0,0),(31,119037,1542969696,10000,10000,1018,0,100022,0,0),(32,119039,1542985291,1000,1000,1018,0,100023,0,0),(33,119001,1542989349,10000,10000,1018,0,100004,0,0),(34,119038,1543050344,1000,1000,1018,0,100023,0,0),(35,119045,1543050395,1000,1000,1018,0,100023,0,0),(36,119047,1543051043,55555,55555,1018,0,100025,0,0),(37,119046,1543051046,555555,555555,1018,0,100025,0,0),(38,119004,1543052255,100000,100000,1018,0,100019,0,0),(39,119014,1543052259,10000,10000,1018,0,100019,0,0),(40,119001,1543056585,1000,1000,1018,0,100024,0,0),(41,119051,1543076694,10000,10000,1018,0,100026,0,0),(42,119004,1543197928,20000,20000,1018,0,100003,0,0),(43,119013,1543197957,200000,200000,1018,0,100003,0,0),(44,119011,1543241215,100000,100000,1018,0,100003,0,0),(45,119010,1543241221,1000000,1000000,1018,0,100003,0,0);
/*!40000 ALTER TABLE `statistics_firecoinchange` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `statistics_gamerecordinfo`
--

LOCK TABLES `statistics_gamerecordinfo` WRITE;
/*!40000 ALTER TABLE `statistics_gamerecordinfo` DISABLE KEYS */;
INSERT INTO `statistics_gamerecordinfo` VALUES (1,'831780',2,3,1542856640,1542856922,1542856938,'119002,-300|119004,291|#119002,0|119004,9|#119002,3|119004,3|#'),(2,'127210',3,3,1542856938,1542857280,1542857302,'119004,291|119002,-300|#119004,9|119002,0|#119004,4|119002,2|#'),(3,'624097',1,2,1542879680,1542880123,1542880143,'119013,119015,'),(4,'624097',1,2,1542879680,1542880156,1542880171,'119013,119015,'),(5,'624097',1,2,1542879680,1542880195,1542880213,'119013,119015,'),(6,'624097',1,2,1542879680,1542880218,1542880238,'119013,119015,'),(7,'624097',1,2,1542879680,1542880243,1542880255,'119013,119015,'),(8,'624097',1,2,1542879680,1542880260,1542880272,'119013,6790|119015,-7000|#119013,210|119015,0|#119013,4|119015,2|#'),(9,'624097',1,2,1542879680,1542880260,1542880270,'119013,119015,'),(10,'118585',2,8,1542881402,1542881509,1542881541,'119013,119014,'),(11,'118585',2,8,1542881402,1542881558,1542881595,'119013,119014,'),(12,'118585',2,8,1542881402,1542881603,1542881635,'119013,119014,'),(13,'118585',2,8,1542881402,1542881658,1542881679,'119013,119014,'),(14,'118585',2,8,1542881402,1542881702,1542881720,'119013,119014,'),(15,'118585',2,8,1542881402,1542881736,1542881769,'119013,-400|119014,388|#119013,0|119014,12|#119013,3|119014,3|#'),(16,'118585',2,8,1542881402,1542881736,1542881768,'119013,119014,');
/*!40000 ALTER TABLE `statistics_gamerecordinfo` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_jewelschange`
--

LOCK TABLES `statistics_jewelschange` WRITE;
/*!40000 ALTER TABLE `statistics_jewelschange` DISABLE KEYS */;
INSERT INTO `statistics_jewelschange` VALUES (1,119000,1542818414,100,100,10,0,0,0,0),(2,119000,1542820339,97,-3,1,3,100001,0,0),(3,119001,1542853917,100,100,10,0,0,0,0),(4,119002,1542853920,100,100,10,0,0,0,0),(5,119003,1542854170,100,100,10,0,0,0,0),(6,119004,1542854595,100,100,10,0,0,0,0),(7,119005,1542854998,100,100,10,0,0,0,0),(8,119004,1542856640,97,-3,1,3,100002,0,0),(9,119004,1542856938,94,-3,1,3,100002,0,0),(10,119006,1542857018,100,100,10,0,0,0,0),(11,119004,1542857302,91,-3,1,3,100002,0,0),(12,119007,1542867815,100,100,10,0,0,0,0),(13,119008,1542868487,100,100,10,0,0,0,0),(14,119009,1542875019,100,100,10,0,0,0,0),(15,119010,1542876308,100,100,10,0,0,0,0),(16,119011,1542876377,100,100,10,0,0,0,0),(17,119012,1542876420,100,100,10,0,0,0,0),(18,119013,1542876503,100,100,10,0,0,0,0),(19,119014,1542876845,100,100,10,0,0,0,0),(20,119015,1542877142,100,100,10,0,0,0,0),(21,119016,1542877886,100,100,10,0,0,0,0),(22,119013,1542902814,95,-5,1013,0,0,0,0),(23,119013,1542902821,90,-5,1013,0,0,0,0),(24,119035,1542968788,95,-5,1013,0,0,0,0),(25,119007,1543486846,100,100,10,0,0,0,0);
/*!40000 ALTER TABLE `statistics_jewelschange` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=1020 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_logonandlogout`
--

LOCK TABLES `statistics_logonandlogout` WRITE;
/*!40000 ALTER TABLE `statistics_logonandlogout` DISABLE KEYS */;
INSERT INTO `statistics_logonandlogout` VALUES (1,119000,1,1542818415,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(2,119000,2,1542818908,'119.123.64.255',0,''),(3,119000,1,1542820304,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(4,119000,2,1542820345,'119.123.64.255',0,''),(5,119001,1,1542853917,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(6,119002,1,1542853920,'112.97.57.94',2,'00000000-1963-ce13-0000-00006d56d02a'),(7,119003,1,1542854170,'119.123.71.141',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(8,119003,2,1542854187,'119.123.71.141',0,''),(9,119002,2,1542854221,'112.97.57.94',0,''),(10,119001,2,1542854248,'223.104.63.29',0,''),(11,119002,1,1542854511,'112.97.57.94',2,'00000000-1963-ce13-0000-00006d56d02a'),(12,119002,2,1542854533,'112.97.57.94',0,''),(13,119002,1,1542854551,'112.97.57.94',2,'00000000-1963-ce13-0000-00006d56d02a'),(14,119004,1,1542854595,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(15,119004,2,1542854896,'119.123.64.255',0,''),(16,119004,1,1542854900,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(17,119001,1,1542854902,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(18,119004,2,1542854920,'119.123.64.255',0,''),(19,119004,1,1542854925,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(20,119005,1,1542854998,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(21,119002,2,1542855034,'112.97.57.94',0,''),(22,119002,1,1542855034,'112.97.57.94',2,'00000000-1963-ce13-0000-00006d56d02a'),(23,119005,2,1542855041,'119.123.71.141',0,''),(24,119002,2,1542855065,'112.97.57.94',0,''),(25,119000,1,1542855110,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(26,119001,2,1542855203,'223.104.63.29',0,''),(27,119004,2,1542855225,'119.123.64.255',0,''),(28,119001,1,1542855362,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(29,119000,2,1542855412,'119.123.64.255',0,''),(30,119000,1,1542855900,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(31,119000,2,1542855918,'119.123.64.255',0,''),(32,119000,1,1542855956,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(33,119000,2,1542855966,'119.123.64.255',0,''),(34,119000,1,1542856043,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(35,119000,2,1542856046,'119.123.64.255',0,''),(36,119001,2,1542856154,'223.104.63.29',0,''),(37,119004,1,1542856496,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(38,119002,1,1542856581,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(39,119004,2,1542856799,'119.123.64.255',0,''),(40,119004,1,1542856803,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(41,119002,2,1542856889,'112.97.63.12',0,''),(42,119000,1,1542856893,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(43,119002,1,1542856898,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(44,119000,2,1542856902,'119.123.64.255',0,''),(45,119006,1,1542857018,'223.104.63.1',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(46,119002,2,1542857097,'112.97.63.12',0,''),(47,119002,1,1542857097,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(48,119002,2,1542857123,'112.97.63.12',0,''),(49,119002,1,1542857123,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(50,119004,2,1542857139,'119.123.64.255',0,''),(51,119004,1,1542857146,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(52,119002,2,1542857268,'112.97.63.12',0,''),(53,119002,1,1542857274,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(54,119006,2,1542857320,'223.104.63.1',0,''),(55,119001,1,1542857406,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(56,119001,2,1542857423,'223.104.63.29',0,''),(57,119004,2,1542857556,'119.123.64.255',0,''),(58,119004,1,1542857560,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(59,119002,2,1542857580,'112.97.63.12',0,''),(60,119001,1,1542857704,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(61,119002,1,1542857743,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(62,119001,2,1542857825,'223.104.63.29',0,''),(63,119004,2,1542857885,'119.123.64.255',0,''),(64,119004,1,1542857895,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(65,119002,2,1542857956,'112.97.63.12',0,''),(66,119000,1,1542857997,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(67,119000,2,1542858066,'119.123.64.255',0,''),(68,119000,1,1542858081,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(69,119004,2,1542858295,'119.123.64.255',0,''),(70,119000,2,1542858353,'119.123.64.255',0,''),(71,119004,1,1542858399,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(72,119000,1,1542858436,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(73,119000,2,1542858738,'119.123.64.255',0,''),(74,119000,1,1542858800,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(75,119000,2,1542858816,'119.123.64.255',0,''),(76,119001,1,1542859065,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(77,119004,2,1542859079,'119.123.64.255',0,''),(78,119004,1,1542859091,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(79,119000,1,1542859136,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(80,119001,2,1542859292,'223.104.63.29',0,''),(81,119001,1,1542859296,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(82,119001,2,1542859309,'223.104.63.29',0,''),(83,119001,1,1542859314,'223.104.63.29',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(84,119001,1,1542859318,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(85,119001,2,1542859378,'223.104.63.29',0,''),(86,119004,2,1542859405,'119.123.64.255',0,''),(87,119004,1,1542859431,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(88,119000,2,1542859446,'119.123.64.255',0,''),(89,119006,1,1542859524,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(90,119004,2,1542859731,'119.123.64.255',0,''),(91,119006,2,1542859830,'119.123.64.255',0,''),(92,119001,1,1542862152,'223.104.63.37',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(93,119001,1,1542862156,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(94,119001,2,1542864231,'223.104.63.37',0,''),(95,119006,1,1542864566,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(96,119005,1,1542864858,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(97,119006,2,1542864880,'119.123.64.255',0,''),(98,119005,2,1542865175,'119.123.71.141',0,''),(99,119004,1,1542865365,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(100,119004,1,1542865379,'',2,'00000000-7790-83ba-0000-00007afe3671'),(101,119002,1,1542865519,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(102,119004,2,1542865524,'119.123.64.255',0,''),(103,119002,2,1542865527,'112.97.63.12',0,''),(104,119004,1,1542865529,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(105,119004,1,1542865531,'',2,'00000000-7790-83ba-0000-00007afe3671'),(106,119006,1,1542865545,'119.123.71.141',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(107,119006,1,1542865555,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(108,119004,2,1542865591,'112.97.63.12',0,''),(109,119006,1,1542865601,'119.123.71.141',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(110,119006,1,1542865605,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(111,119006,1,1542865617,'119.123.71.141',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(112,119006,1,1542865621,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(113,119005,1,1542865630,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(114,119005,1,1542865644,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(115,119005,2,1542865741,'119.123.71.141',0,''),(116,119004,1,1542865741,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(117,119004,2,1542865759,'119.123.64.255',0,''),(118,119004,1,1542865766,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(119,119004,1,1542865810,'',2,'00000000-7790-83ba-0000-00007afe3671'),(120,119004,2,1542865846,'119.123.64.255',0,''),(121,119006,2,1542865861,'112.97.63.12',0,''),(122,119000,1,1542865870,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(123,119002,1,1542865875,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(124,119004,1,1542865881,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(125,119001,1,1542865882,'223.104.63.37',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(126,119002,2,1542865882,'112.97.63.12',0,''),(127,119004,1,1542865883,'',2,'00000000-7790-83ba-0000-00007afe3671'),(128,119001,2,1542865891,'223.104.63.37',0,''),(129,119000,2,1542865896,'119.123.64.255',0,''),(130,119004,1,1542865897,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(131,119004,1,1542865900,'',2,'00000000-7790-83ba-0000-00007afe3671'),(132,119005,1,1542865920,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(133,119004,2,1542865922,'119.123.64.255',0,''),(134,119005,1,1542865925,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(135,119005,1,1542865936,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(136,119005,1,1542865942,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(137,119005,2,1542866011,'119.123.64.255',0,''),(138,119005,1,1542866054,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(139,119005,1,1542866056,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(140,119005,1,1542866078,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(141,119005,1,1542866085,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(142,119006,1,1542866155,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(143,119006,1,1542866181,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(144,119006,1,1542866249,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(145,119005,1,1542866250,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(146,119006,2,1542866253,'119.123.64.255',0,''),(147,119006,1,1542866254,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(148,119005,1,1542866260,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(149,119002,1,1542866268,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(150,119002,2,1542866280,'112.97.63.12',0,''),(151,119002,1,1542866285,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(152,119006,2,1542866473,'119.123.64.255',0,''),(153,119005,2,1542866557,'119.123.71.141',0,''),(154,119002,2,1542866688,'112.97.63.12',0,''),(155,119001,1,1542866743,'223.104.63.37',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(156,119000,1,1542866773,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(157,119001,2,1542866811,'223.104.63.37',0,''),(158,119006,1,1542866981,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(159,119006,2,1542867298,'119.123.64.255',0,''),(160,119006,1,1542867369,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(161,119006,2,1542867675,'119.123.64.255',0,''),(162,119006,1,1542867721,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(163,119007,1,1542867817,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(164,119007,2,1542867871,'119.123.64.255',0,''),(165,119006,2,1542868162,'119.123.64.255',0,''),(166,119006,1,1542868181,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(167,119006,1,1542868196,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(168,119006,2,1542868353,'119.123.64.255',0,''),(169,119004,1,1542868434,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(170,119004,1,1542868437,'',2,'00000000-7790-83ba-0000-00007afe3671'),(171,119004,2,1542868488,'119.123.64.255',0,''),(172,119008,1,1542868488,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(173,119002,1,1542868512,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(174,119002,2,1542868517,'112.97.63.12',0,''),(175,119004,1,1542868518,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(176,119002,1,1542868522,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(177,119004,1,1542868528,'',2,'00000000-7790-83ba-0000-00007afe3671'),(178,119002,2,1542868533,'112.97.63.12',0,''),(179,119008,2,1542868558,'119.123.64.255',0,''),(180,119004,2,1542868578,'119.123.64.255',0,''),(181,119006,1,1542868614,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(182,119006,1,1542868640,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(183,119006,2,1542868728,'119.123.64.255',0,''),(184,119006,1,1542869760,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(185,119006,1,1542869765,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(186,119006,2,1542869853,'119.123.64.255',0,''),(187,119006,1,1542869859,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(188,119006,1,1542869863,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(189,119006,2,1542870018,'119.123.64.255',0,''),(190,119006,1,1542870022,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(191,119006,1,1542870029,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(192,119006,2,1542870373,'119.123.64.255',0,''),(193,119006,1,1542870403,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(194,119006,1,1542870422,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(195,119006,2,1542870498,'112.97.63.12',0,''),(196,119002,1,1542870520,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(197,119006,1,1542870522,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(198,119006,1,1542870525,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(199,119002,2,1542870533,'112.97.63.12',0,''),(200,119006,1,1542870542,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(201,119006,1,1542870547,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(202,119006,2,1542870607,'117.136.39.215',0,''),(203,119002,1,1542870612,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(204,119002,2,1542870618,'112.97.63.12',0,''),(205,119006,1,1542870622,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(206,119006,1,1542870635,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(207,119006,2,1542870753,'117.136.39.215',0,''),(208,119006,1,1542870765,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(209,119006,1,1542870951,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(210,119006,2,1542871068,'112.97.63.12',0,''),(211,119002,1,1542871073,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(212,119004,1,1542871157,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(213,119004,1,1542871160,'',2,'00000000-7790-83ba-0000-00007afe3671'),(214,119002,2,1542871326,'112.97.63.12',0,''),(215,119002,1,1542871344,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(216,119006,1,1542871345,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(217,119006,1,1542871346,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(218,119006,2,1542871457,'119.123.64.255',0,''),(219,119004,1,1542871462,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(220,119004,1,1542871467,'',2,'00000000-7790-83ba-0000-00007afe3671'),(221,119000,1,1542871493,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(222,119000,2,1542871502,'119.123.64.255',0,''),(223,119004,2,1542871518,'223.104.63.37',0,''),(224,119000,1,1542871544,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(225,119002,2,1542871553,'112.97.63.12',0,''),(226,119002,1,1542871554,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(227,119001,1,1542871555,'223.104.63.37',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(228,119002,2,1542871606,'112.97.63.12',0,''),(229,119002,1,1542871606,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(230,119001,2,1542871608,'223.104.63.37',0,''),(231,119002,2,1542871658,'112.97.63.12',0,''),(232,119002,1,1542871686,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(233,119002,1,1542871689,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(234,119002,1,1542871702,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(235,119002,1,1542871703,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(236,119001,1,1542871711,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(237,119000,1,1542871805,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(238,119002,2,1542871818,'117.136.39.215',0,''),(239,119002,1,1542872012,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(240,119006,1,1542872023,'117.136.39.215',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(241,119006,1,1542872025,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(242,119001,2,1542872082,'119.123.71.141',0,''),(243,119006,2,1542872084,'117.136.39.215',0,''),(244,119000,2,1542872105,'119.123.64.255',0,''),(245,119000,1,1542872215,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(246,119002,1,1542872221,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(247,119000,2,1542872237,'119.123.64.255',0,''),(248,119000,1,1542872240,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(249,119000,2,1542872255,'119.123.64.255',0,''),(250,119000,1,1542872260,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(251,119000,2,1542872268,'119.123.64.255',0,''),(252,119002,2,1542872298,'112.97.63.12',0,''),(253,119000,1,1542872323,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(254,119002,1,1542872329,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(255,119000,2,1542872339,'119.123.64.255',0,''),(256,119002,1,1542872384,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(257,119002,1,1542872435,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(258,119002,1,1542872439,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(259,119002,1,1542872565,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(260,119000,1,1542872565,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(261,119000,2,1542872576,'119.123.64.255',0,''),(262,119008,1,1542872577,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(263,119000,1,1542872581,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(264,119000,2,1542872583,'119.123.64.255',0,''),(265,119008,2,1542872585,'119.123.64.255',0,''),(266,119008,1,1542872588,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(267,119002,1,1542872591,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(268,119002,2,1542872629,'112.97.63.12',0,''),(269,119000,1,1542872642,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(270,119000,2,1542872646,'119.123.64.255',0,''),(271,119000,1,1542872657,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(272,119000,2,1542872664,'119.123.64.255',0,''),(273,119002,1,1542872683,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(274,119002,1,1542872686,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(275,119002,2,1542872724,'119.123.64.255',0,''),(276,119000,1,1542872735,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(277,119000,2,1542872743,'119.123.64.255',0,''),(278,119002,1,1542872760,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(279,119000,1,1542872801,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(280,119002,1,1542872811,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(281,119000,2,1542872820,'119.123.64.255',0,''),(282,119008,2,1542872821,'119.123.64.255',0,''),(283,119004,1,1542872838,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(284,119004,1,1542872839,'',2,'00000000-7790-83ba-0000-00007afe3671'),(285,119000,1,1542872875,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(286,119000,2,1542872880,'119.123.64.255',0,''),(287,119002,2,1542872895,'112.97.63.12',0,''),(288,119002,1,1542872903,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(289,119004,2,1542872913,'119.123.64.255',0,''),(290,119002,1,1542872918,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(291,119002,1,1542872971,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(292,119002,1,1542873068,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(293,119002,2,1542873138,'119.123.64.255',0,''),(294,119002,1,1542873139,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(295,119002,2,1542873203,'112.97.63.12',0,''),(296,119002,1,1542873314,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(297,119002,1,1542873317,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(298,119005,1,1542873425,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(299,119001,1,1542873435,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(300,119001,2,1542873440,'119.123.71.141',0,''),(301,119002,2,1542873513,'112.97.63.12',0,''),(302,119002,1,1542873563,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(303,119002,1,1542873564,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(304,119002,2,1542873614,'112.97.63.12',0,''),(305,119002,1,1542873737,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(306,119005,2,1542873741,'119.123.71.141',0,''),(307,119006,1,1542873765,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(308,119002,1,1542873811,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(309,119002,2,1542873903,'112.97.63.12',0,''),(310,119005,1,1542874054,'119.123.71.141',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(311,119006,2,1542874074,'119.123.64.255',0,''),(312,119005,2,1542874079,'119.123.71.141',0,''),(313,119001,1,1542874134,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(314,119001,2,1542874160,'119.123.71.141',0,''),(315,119002,1,1542874201,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(316,119002,2,1542874377,'112.97.63.12',0,''),(317,119001,1,1542874469,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(318,119002,1,1542874474,'112.97.63.12',2,'00000000-1963-ce13-0000-00006d56d02a'),(319,119002,1,1542874478,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(320,119006,1,1542874506,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(321,119006,1,1542874512,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(322,119002,2,1542874548,'112.97.63.12',0,''),(323,119006,2,1542874578,'119.123.64.255',0,''),(324,119006,1,1542874829,'119.123.64.255',2,'00000000-5cd3-86f0-0000-0000649894d9'),(325,119006,1,1542874834,'',2,'00000000-5cd3-86f0-0000-0000649894d9'),(326,119006,2,1542874893,'119.123.64.255',0,''),(327,119009,1,1542875020,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(328,119001,1,1542875082,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(329,119001,1,1542875159,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(330,119001,1,1542875171,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(331,119001,1,1542875174,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(332,119001,1,1542875267,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(333,119001,1,1542875269,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(334,119001,1,1542875287,'119.123.71.141',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(335,119009,2,1542875320,'119.123.64.255',0,''),(336,119001,1,1542875336,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(337,119001,2,1542875418,'119.123.71.141',0,''),(338,119001,1,1542875821,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(339,119001,1,1542875857,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(340,119000,1,1542876279,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(341,119011,1,1542876379,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(342,119011,2,1542876432,'119.123.64.255',0,''),(343,119011,1,1542876438,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(344,119011,2,1542876440,'119.123.64.255',0,''),(345,119011,1,1542876457,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(346,119001,2,1542876468,'119.123.64.255',0,''),(347,119013,1,1542876503,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(348,119011,2,1542876547,'119.123.64.255',0,''),(349,119011,1,1542876570,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(350,119011,2,1542876573,'119.123.64.255',0,''),(351,119004,1,1542876623,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(352,119004,1,1542876625,'',2,'00000000-7790-83ba-0000-00007afe3671'),(353,119013,2,1542876815,'119.123.66.240',0,''),(354,119004,2,1542876828,'119.123.64.255',0,''),(355,119014,1,1542876847,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(356,119000,2,1542877017,'119.123.64.255',0,''),(357,119000,1,1542877052,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(358,119014,2,1542877085,'119.123.64.255',0,''),(359,119015,1,1542877166,'119.123.64.255',3,'00-E0-70-4C-71-50'),(360,119004,1,1542877175,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(361,119004,1,1542877177,'',2,'00000000-7790-83ba-0000-00007afe3671'),(362,119000,2,1542877353,'119.123.64.255',0,''),(363,119004,2,1542877398,'119.123.66.240',0,''),(364,119014,1,1542877415,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(365,119015,2,1542877495,'119.123.64.255',0,''),(366,119015,1,1542877513,'119.123.64.255',3,'00-E0-70-4C-71-50'),(367,119013,1,1542877542,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(368,119000,1,1542877598,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(369,119015,2,1542877623,'119.123.64.255',0,''),(370,119003,1,1542877661,'119.123.66.240',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(371,119014,2,1542877718,'119.123.64.255',0,''),(372,119003,2,1542877745,'119.123.66.240',0,''),(373,119004,1,1542877751,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(374,119004,1,1542877759,'',2,'00000000-7790-83ba-0000-00007afe3671'),(375,119004,1,1542877782,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(376,119002,1,1542877785,'112.97.61.190',2,'00000000-1963-ce13-0000-00006d56d02a'),(377,119002,1,1542877789,'',2,'00000000-1963-ce13-0000-00006d56d02a'),(378,119004,1,1542877794,'',2,'00000000-7790-83ba-0000-00007afe3671'),(379,119013,2,1542877830,'119.123.66.240',0,''),(380,119013,1,1542877838,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(381,119002,2,1542877855,'119.123.66.240',0,''),(382,119004,1,1542877859,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(383,119004,1,1542877861,'',2,'00000000-7790-83ba-0000-00007afe3671'),(384,119004,2,1542877938,'119.123.64.255',0,''),(385,119004,1,1542877952,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(386,119004,1,1542877953,'',2,'00000000-7790-83ba-0000-00007afe3671'),(387,119004,1,1542878122,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(388,119004,1,1542878123,'',2,'00000000-7790-83ba-0000-00007afe3671'),(389,119015,1,1542878128,'119.123.64.255',3,'00-E0-70-4C-71-50'),(390,119004,2,1542878668,'119.123.66.240',0,''),(391,119000,2,1542878684,'119.123.64.255',0,''),(392,119000,1,1542878688,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(393,119002,1,1542878705,'112.97.61.190',2,'00000000-1963-ce13-0000-00006d56d02a'),(394,119001,1,1542878817,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(395,119001,1,1542878830,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(396,119000,2,1542879188,'119.123.64.255',0,''),(397,119000,1,1542879264,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(398,119000,2,1542879275,'119.123.64.255',0,''),(399,119013,1,1542879415,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(400,119015,1,1542879420,'119.123.64.255',3,'00-E0-70-4C-71-50'),(401,119015,1,1542879451,'119.123.64.255',3,'00-E0-70-4C-71-50'),(402,119015,1,1542879666,'119.123.64.255',3,'00-E0-70-4C-71-50'),(403,119000,1,1542879684,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(404,119005,1,1542879801,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(405,119016,1,1542879807,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(406,119016,2,1542879810,'119.123.64.255',0,''),(407,119000,1,1542879872,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(408,119013,1,1542879885,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(409,119001,1,1542879886,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(410,119001,2,1542879906,'119.123.66.240',0,''),(411,119001,1,1542879913,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(412,119000,2,1542879930,'119.123.64.255',0,''),(413,119000,1,1542879934,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(414,119001,2,1542879935,'119.123.66.240',0,''),(415,119001,1,1542879939,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(416,119001,2,1542879960,'119.123.66.240',0,''),(417,119001,1,1542879969,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(418,119001,2,1542880032,'119.123.66.240',0,''),(419,119001,1,1542880069,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(420,119001,2,1542880074,'119.123.66.240',0,''),(421,119015,2,1542880080,'119.123.64.255',0,''),(422,119015,1,1542880085,'119.123.64.255',3,'00-E0-70-4C-71-50'),(423,119001,1,1542880086,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(424,119004,1,1542880473,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(425,119004,1,1542880480,'',2,'00000000-7790-83ba-0000-00007afe3671'),(426,119004,2,1542880544,'119.123.64.255',0,''),(427,119014,1,1542880593,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(428,119014,2,1542880628,'119.123.64.255',0,''),(429,119014,1,1542880634,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(430,119015,1,1542880679,'119.123.64.255',3,'00-E0-70-4C-71-50'),(431,119014,1,1542880685,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(432,119014,1,1542880707,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(433,119005,1,1542880715,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(434,119014,2,1542880745,'119.123.64.255',0,''),(435,119013,1,1542880820,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(436,119005,2,1542880830,'119.123.66.240',0,''),(437,119005,1,1542880831,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(438,119000,1,1542880849,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(439,119005,2,1542880855,'119.123.66.240',0,''),(440,119005,1,1542880855,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(441,119005,2,1542880910,'119.123.66.240',0,''),(442,119005,1,1542880911,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(443,119005,2,1542880935,'119.123.66.240',0,''),(444,119005,1,1542880935,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(445,119014,1,1542880942,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(446,119005,2,1542880959,'119.123.66.240',0,''),(447,119005,1,1542880960,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(448,119005,2,1542880984,'119.123.66.240',0,''),(449,119005,1,1542880984,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(450,119005,2,1542881008,'119.123.66.240',0,''),(451,119005,1,1542881009,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(452,119005,2,1542881033,'119.123.66.240',0,''),(453,119005,1,1542881033,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(454,119003,1,1542881047,'119.123.66.240',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(455,119005,2,1542881057,'119.123.66.240',0,''),(456,119005,1,1542881058,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(457,119004,1,1542881064,'119.123.64.255',2,'00000000-7790-83ba-0000-00007afe3671'),(458,119004,1,1542881067,'',2,'00000000-7790-83ba-0000-00007afe3671'),(459,119005,2,1542881082,'119.123.66.240',0,''),(460,119005,1,1542881082,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(461,119005,2,1542881106,'119.123.66.240',0,''),(462,119005,1,1542881106,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(463,119005,2,1542881131,'119.123.66.240',0,''),(464,119005,1,1542881131,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(465,119005,2,1542881156,'119.123.66.240',0,''),(466,119005,1,1542881156,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(467,119005,2,1542881180,'119.123.66.240',0,''),(468,119005,1,1542881181,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(469,119005,2,1542881205,'119.123.66.240',0,''),(470,119005,1,1542881205,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(471,119004,2,1542881206,'119.123.66.240',0,''),(472,119006,1,1542881211,'117.136.39.211',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(473,119006,1,1542881216,'',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(474,119005,2,1542881229,'119.123.66.240',0,''),(475,119005,1,1542881229,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(476,119013,1,1542881257,'119.123.66.240',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(477,119005,1,1542881287,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(478,119015,1,1542881301,'119.123.64.255',3,'00-E0-70-4C-71-50'),(479,119003,1,1542881309,'119.123.66.240',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(480,119014,1,1542881323,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(481,119006,1,1542881378,'117.136.39.211',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(482,119006,2,1542881381,'117.136.39.211',0,''),(483,119006,1,1542881382,'117.136.39.211',2,'00000000-4968-c4c9-ffff-ffffb822b527'),(484,119001,1,1542881418,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(485,119001,2,1542881652,'119.123.66.240',0,''),(486,119000,1,1542881850,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(487,119006,2,1542881879,'117.136.39.211',0,''),(488,119000,2,1542882079,'119.123.64.255',0,''),(489,119003,2,1542882285,'119.123.66.240',0,''),(490,119000,1,1542882492,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(491,119016,1,1542882493,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(492,119016,2,1542883573,'119.123.64.255',0,''),(493,119016,1,1542883573,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(494,119014,2,1542883573,'119.123.64.255',0,''),(495,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(496,119000,2,1542883573,'119.123.64.255',0,''),(497,119016,2,1542883573,'119.123.64.255',0,''),(498,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(499,119015,2,1542883573,'119.123.64.255',0,''),(500,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(501,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(502,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(503,119013,2,1542883573,'119.123.66.240',0,''),(504,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(505,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(506,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(507,119005,2,1542883573,'119.123.66.240',0,''),(508,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(509,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(510,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(511,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(512,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(513,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(514,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(515,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(516,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(517,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(518,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(519,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(520,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(521,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(522,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(523,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(524,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(525,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(526,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(527,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(528,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(529,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(530,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(531,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(532,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(533,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(534,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(535,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(536,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(537,119014,1,1542883573,'',3,'40-8D-5C-1C-E0-01'),(538,119005,1,1542883573,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(539,119016,1,1542883573,'',3,'FC-AA-14-FF-DE-E4'),(540,119015,1,1542883573,'',3,'00-E0-70-4C-71-50'),(541,119013,1,1542883573,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(542,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(543,119005,1,1542883574,'',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(544,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(545,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(546,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(547,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(548,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(549,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(550,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(551,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(552,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(553,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(554,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(555,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(556,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(557,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(558,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(559,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(560,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(561,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(562,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(563,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(564,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(565,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(566,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(567,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(568,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(569,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(570,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(571,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(572,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(573,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(574,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(575,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(576,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(577,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(578,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(579,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(580,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(581,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(582,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(583,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(584,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(585,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(586,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(587,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(588,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(589,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(590,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(591,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(592,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(593,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(594,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(595,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(596,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(597,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(598,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(599,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(600,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(601,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(602,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(603,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(604,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(605,119014,1,1542883574,'',3,'40-8D-5C-1C-E0-01'),(606,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(607,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(608,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(609,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(610,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(611,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(612,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(613,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(614,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(615,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(616,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(617,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(618,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(619,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(620,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(621,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(622,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(623,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(624,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(625,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(626,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(627,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(628,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(629,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(630,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(631,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(632,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(633,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(634,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(635,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(636,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(637,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(638,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(639,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(640,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(641,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(642,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(643,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(644,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(645,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(646,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(647,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(648,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(649,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(650,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(651,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(652,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(653,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(654,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(655,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(656,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(657,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(658,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(659,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(660,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(661,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(662,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(663,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(664,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(665,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(666,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(667,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(668,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(669,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(670,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(671,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(672,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(673,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(674,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(675,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(676,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(677,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(678,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(679,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(680,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(681,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(682,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(683,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(684,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(685,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(686,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(687,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(688,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(689,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(690,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(691,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(692,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(693,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(694,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(695,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(696,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(697,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(698,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(699,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(700,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(701,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(702,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(703,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(704,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(705,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(706,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(707,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(708,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(709,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(710,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(711,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(712,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(713,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(714,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(715,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(716,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(717,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(718,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(719,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(720,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(721,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(722,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(723,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(724,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(725,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(726,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(727,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(728,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(729,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(730,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(731,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(732,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(733,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(734,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(735,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(736,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(737,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(738,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(739,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(740,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(741,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(742,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(743,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(744,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(745,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(746,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(747,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(748,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(749,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(750,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(751,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(752,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(753,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(754,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(755,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(756,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(757,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(758,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(759,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(760,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(761,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(762,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(763,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(764,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(765,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(766,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(767,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(768,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(769,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(770,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(771,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(772,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(773,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(774,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(775,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(776,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(777,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(778,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(779,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(780,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(781,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(782,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(783,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(784,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(785,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(786,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(787,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(788,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(789,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(790,119013,1,1542883574,'',2,'ffffffff-9830-5345-ffff-ffff814fd29e'),(791,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(792,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(793,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(794,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(795,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(796,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(797,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(798,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(799,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(800,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(801,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(802,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(803,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(804,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(805,119015,1,1542883574,'',3,'00-E0-70-4C-71-50'),(806,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(807,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(808,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(809,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(810,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(811,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(812,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(813,119016,1,1542883574,'',3,'FC-AA-14-FF-DE-E4'),(814,119016,1,1542883576,'119.123.64.255',3,'FC-AA-14-FF-DE-E4'),(815,119000,1,1542883576,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(816,119005,1,1542884581,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(817,119005,2,1542884621,'119.123.66.240',0,''),(818,119003,1,1542884640,'112.97.57.74',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(819,119003,2,1542884649,'112.97.57.74',0,''),(820,119005,1,1542884711,'119.123.66.240',2,'00000000-20cc-bf45-ffff-ffffbfc17ac5'),(821,119005,2,1542884717,'119.123.66.240',0,''),(822,119001,1,1542884721,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(823,119002,1,1542884732,'112.97.61.190',2,'00000000-1963-ce13-0000-00006d56d02a'),(824,119014,1,1542884738,'119.123.64.255',3,'40-8D-5C-1C-E0-01'),(825,119002,2,1542884758,'112.97.61.190',0,''),(826,119001,2,1542884764,'119.123.66.240',0,''),(827,119015,1,1542885012,'119.123.64.255',3,'00-E0-70-4C-71-50'),(828,119002,1,1542885221,'112.97.61.190',2,'00000000-1963-ce13-0000-00006d56d02a'),(829,119002,2,1542885592,'112.97.61.190',0,''),(830,119015,2,1542886110,'119.123.64.255',0,''),(831,119001,1,1542886477,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(832,119001,2,1542886732,'119.123.66.240',0,''),(833,119001,1,1542886857,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(834,119001,2,1542886949,'119.123.66.240',0,''),(835,119001,1,1542886950,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(836,119003,1,1542887064,'119.123.64.255',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(837,119003,2,1542887068,'119.123.64.255',0,''),(838,119001,2,1542887073,'119.123.66.240',0,''),(839,119001,1,1542887077,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(840,119001,2,1542887175,'119.123.66.240',0,''),(841,119001,1,1542887196,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(842,119000,1,1542887207,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(843,119001,2,1542887247,'119.123.66.240',0,''),(844,119001,1,1542887262,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(845,119001,2,1542887282,'119.123.66.240',0,''),(846,119001,1,1542887287,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(847,119001,1,1542887291,'',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(848,119001,2,1542887312,'119.123.66.240',0,''),(849,119003,1,1542887464,'119.123.64.255',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(850,119003,2,1542887473,'119.123.64.255',0,''),(851,119000,2,1542887522,'119.123.64.255',0,''),(852,119000,1,1542887526,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(853,119000,2,1542887560,'119.123.64.255',0,''),(854,119000,1,1542887563,'119.123.64.255',3,'40-8D-5C-1A-02-09'),(855,119000,2,1542887624,'119.123.64.255',0,''),(856,119003,1,1542887816,'119.123.64.255',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(857,119003,2,1542887820,'119.123.64.255',0,''),(858,119000,1,1542887902,'119.123.64.255',3,'9C-5C-8E-C2-FE-8D'),(859,119000,2,1542887954,'119.123.64.255',0,''),(860,119001,1,1542888199,'119.123.66.240',2,'ffffffff-dd3c-1f3c-ffff-ffff96ec89ac'),(861,119003,1,1542888222,'119.123.64.255',2,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe'),(862,119000,1,1543486164,'192.168.0.64',3,'FC-AA-14-74-65-32'),(863,119000,2,1543486168,'192.168.0.64',0,''),(864,119000,1,1543486170,'192.168.0.64',3,'FC-AA-14-74-65-32'),(865,119005,1,1543486170,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(866,119000,2,1543486172,'192.168.0.64',0,''),(867,119000,1,1543486248,'192.168.0.64',3,'FC-AA-14-74-65-32'),(868,119000,2,1543486252,'192.168.0.64',0,''),(869,119005,1,1543486253,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(870,119005,2,1543486268,'192.168.0.72',0,''),(871,119005,1,1543486270,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(872,119005,2,1543486451,'192.168.0.72',0,''),(873,119005,1,1543486453,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(874,119005,2,1543486458,'192.168.0.72',0,''),(875,119005,1,1543486464,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(876,119000,1,1543486495,'192.168.0.64',3,'FC-AA-14-74-65-32'),(877,119005,1,1543486509,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(878,119000,2,1543486519,'192.168.0.64',0,''),(879,119000,1,1543486519,'192.168.0.64',3,'FC-AA-14-74-65-32'),(880,119005,2,1543486525,'192.168.0.72',0,''),(881,119000,2,1543486531,'192.168.0.64',0,''),(882,119000,1,1543486548,'192.168.0.64',3,'FC-AA-14-74-65-32'),(883,119000,2,1543486554,'192.168.0.64',0,''),(884,119000,1,1543486582,'192.168.0.64',3,'FC-AA-14-74-65-32'),(885,119000,2,1543486752,'192.168.0.64',0,''),(886,119000,1,1543486790,'192.168.0.64',3,'FC-AA-14-74-65-32'),(887,119000,2,1543486841,'192.168.0.64',0,''),(888,119000,1,1543486844,'',3,'FC-AA-14-74-65-32'),(889,119000,1,1543486845,'',3,'FC-AA-14-74-65-32'),(890,119000,1,1543486845,'',3,'FC-AA-14-74-65-32'),(891,119000,1,1543486847,'',3,'FC-AA-14-74-65-32'),(892,119000,1,1543486847,'',3,'FC-AA-14-74-65-32'),(893,119000,1,1543486848,'192.168.0.64',3,'FC-AA-14-74-65-32'),(894,119000,2,1543486859,'192.168.0.64',0,''),(895,119000,1,1543486892,'192.168.0.64',3,'FC-AA-14-74-65-32'),(896,119000,2,1543486912,'192.168.0.64',0,''),(897,119000,1,1543486912,'192.168.0.64',3,'FC-AA-14-74-65-32'),(898,119005,1,1543486925,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(899,119000,2,1543486927,'192.168.0.64',0,''),(900,119000,1,1543486927,'192.168.0.64',3,'FC-AA-14-74-65-32'),(901,119000,2,1543486933,'192.168.0.64',0,''),(902,119001,1,1543486942,'192.168.0.64',3,'FC-AA-14-74-65-32'),(903,119005,2,1543486949,'192.168.0.72',0,''),(904,119001,2,1543486961,'192.168.0.64',0,''),(905,119001,1,1543486961,'192.168.0.64',3,'FC-AA-14-74-65-32'),(906,119001,2,1543486985,'192.168.0.64',0,''),(907,119001,1,1543486985,'192.168.0.64',3,'FC-AA-14-74-65-32'),(908,119001,2,1543486995,'192.168.0.64',0,''),(909,119001,1,1543486995,'192.168.0.64',3,'FC-AA-14-74-65-32'),(910,119001,2,1543487012,'192.168.0.64',0,''),(911,119001,1,1543487012,'192.168.0.64',3,'FC-AA-14-74-65-32'),(912,119005,1,1543487017,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(913,119001,2,1543487029,'192.168.0.64',0,''),(914,119001,1,1543487029,'192.168.0.64',3,'FC-AA-14-74-65-32'),(915,119001,2,1543487046,'192.168.0.64',0,''),(916,119005,2,1543487046,'192.168.0.72',0,''),(917,119001,1,1543487046,'192.168.0.64',3,'FC-AA-14-74-65-32'),(918,119005,1,1543487046,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(919,119005,2,1543487063,'192.168.0.72',0,''),(920,119001,2,1543487063,'192.168.0.64',0,''),(921,119001,1,1543487063,'192.168.0.64',3,'FC-AA-14-74-65-32'),(922,119005,1,1543487063,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(923,119001,2,1543487080,'192.168.0.64',0,''),(924,119005,2,1543487080,'192.168.0.72',0,''),(925,119001,1,1543487080,'192.168.0.64',3,'FC-AA-14-74-65-32'),(926,119005,1,1543487080,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(927,119005,2,1543487097,'192.168.0.72',0,''),(928,119001,2,1543487097,'192.168.0.64',0,''),(929,119001,1,1543487097,'192.168.0.64',3,'FC-AA-14-74-65-32'),(930,119005,1,1543487097,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(931,119001,2,1543487114,'192.168.0.64',0,''),(932,119005,2,1543487114,'192.168.0.72',0,''),(933,119001,1,1543487114,'192.168.0.64',3,'FC-AA-14-74-65-32'),(934,119005,1,1543487114,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(935,119001,2,1543487131,'192.168.0.64',0,''),(936,119005,2,1543487131,'192.168.0.72',0,''),(937,119001,1,1543487131,'192.168.0.64',3,'FC-AA-14-74-65-32'),(938,119005,1,1543487131,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(939,119001,2,1543487141,'192.168.0.64',0,''),(940,119001,1,1543487236,'192.168.0.64',3,'FC-AA-14-74-65-32'),(941,119005,1,1543487240,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(942,119001,2,1543487245,'192.168.0.64',0,''),(943,119005,2,1543487246,'192.168.0.72',0,''),(944,119005,1,1543487246,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(945,119005,2,1543487249,'192.168.0.72',0,''),(946,119005,1,1543487249,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(947,119000,1,1543487250,'192.168.0.64',3,'FC-AA-14-74-65-32'),(948,119000,2,1543487254,'192.168.0.64',0,''),(949,119000,1,1543487254,'192.168.0.64',3,'FC-AA-14-74-65-32'),(950,119000,2,1543487256,'192.168.0.64',0,''),(951,119000,1,1543487256,'192.168.0.64',3,'FC-AA-14-74-65-32'),(952,119005,2,1543487256,'192.168.0.72',0,''),(953,119005,1,1543487257,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(954,119000,2,1543487257,'192.168.0.64',0,''),(955,119000,1,1543487257,'192.168.0.64',3,'FC-AA-14-74-65-32'),(956,119000,2,1543487258,'192.168.0.64',0,''),(957,119000,1,1543487258,'192.168.0.64',3,'FC-AA-14-74-65-32'),(958,119000,2,1543487259,'192.168.0.64',0,''),(959,119000,1,1543487259,'192.168.0.64',3,'FC-AA-14-74-65-32'),(960,119000,2,1543487284,'192.168.0.64',0,''),(961,119000,1,1543487284,'192.168.0.64',3,'FC-AA-14-74-65-32'),(962,119005,2,1543487288,'192.168.0.72',0,''),(963,119005,1,1543487288,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(964,119000,2,1543487301,'192.168.0.64',0,''),(965,119000,1,1543487301,'192.168.0.64',3,'FC-AA-14-74-65-32'),(966,119005,2,1543487307,'192.168.0.72',0,''),(967,119005,1,1543487308,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(968,119005,2,1543487310,'192.168.0.72',0,''),(969,119005,1,1543487311,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(970,119005,2,1543487313,'192.168.0.72',0,''),(971,119005,1,1543487313,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(972,119005,2,1543487316,'192.168.0.72',0,''),(973,119005,1,1543487316,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(974,119005,2,1543487317,'192.168.0.72',0,''),(975,119005,1,1543487317,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(976,119005,2,1543487318,'192.168.0.72',0,''),(977,119000,2,1543487318,'192.168.0.64',0,''),(978,119000,1,1543487318,'192.168.0.64',3,'FC-AA-14-74-65-32'),(979,119005,1,1543487318,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(980,119005,2,1543487320,'192.168.0.72',0,''),(981,119005,1,1543487320,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(982,119005,2,1543487324,'192.168.0.72',0,''),(983,119005,1,1543487324,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(984,119005,2,1543487325,'192.168.0.72',0,''),(985,119005,1,1543487325,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(986,119005,2,1543487330,'192.168.0.72',0,''),(987,119005,1,1543487330,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(988,119000,2,1543487335,'192.168.0.64',0,''),(989,119000,1,1543487335,'192.168.0.64',3,'FC-AA-14-74-65-32'),(990,119005,2,1543487349,'192.168.0.72',0,''),(991,119005,1,1543487349,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(992,119000,2,1543487352,'192.168.0.64',0,''),(993,119000,1,1543487352,'192.168.0.64',3,'FC-AA-14-74-65-32'),(994,119000,2,1543487369,'192.168.0.64',0,''),(995,119000,1,1543487369,'192.168.0.64',3,'FC-AA-14-74-65-32'),(996,119000,2,1543487386,'192.168.0.64',0,''),(997,119000,1,1543487386,'192.168.0.64',3,'FC-AA-14-74-65-32'),(998,119000,2,1543487403,'192.168.0.64',0,''),(999,119000,1,1543487403,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1000,119000,2,1543487420,'192.168.0.64',0,''),(1001,119000,1,1543487420,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1002,119000,2,1543487437,'192.168.0.64',0,''),(1003,119000,1,1543487437,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1004,119000,2,1543487454,'192.168.0.64',0,''),(1005,119000,1,1543487454,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1006,119000,2,1543487471,'192.168.0.64',0,''),(1007,119000,1,1543487471,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1008,119005,2,1543487480,'192.168.0.72',0,''),(1009,119005,1,1543487480,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(1010,119005,2,1543487481,'192.168.0.72',0,''),(1011,119005,1,1543487481,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(1012,119005,2,1543487485,'192.168.0.72',0,''),(1013,119005,1,1543487485,'192.168.0.72',3,'FC-AA-14-FF-DE-E4'),(1014,119000,2,1543487488,'192.168.0.64',0,''),(1015,119000,1,1543487488,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1016,119000,2,1543487494,'192.168.0.64',0,''),(1017,119000,1,1543487534,'192.168.0.64',3,'FC-AA-14-74-65-32'),(1018,119000,3,1543487536,'192.168.0.64',7,'FC-AA-14-74-65-32'),(1019,119005,1,1543487540,'192.168.0.72',3,'FC-AA-14-FF-DE-E4');
/*!40000 ALTER TABLE `statistics_logonandlogout` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_moneychange`
--

LOCK TABLES `statistics_moneychange` WRITE;
/*!40000 ALTER TABLE `statistics_moneychange` DISABLE KEYS */;
INSERT INTO `statistics_moneychange` VALUES (1,119000,1542818414,1000000,1000000,10,0,0,0,0),(2,119001,1542853917,1000000,1000000,10,0,0,0,0),(3,119002,1542853920,1000000,1000000,10,0,0,0,0),(4,119003,1542854170,1000000,1000000,10,0,0,0,0),(5,119004,1542854595,1000000,1000000,10,0,0,0,0),(6,119005,1542854998,1000000,1000000,10,0,0,0,0),(7,119006,1542857018,1000000,1000000,10,0,0,0,0),(8,119000,1542858021,997000,-3000,1,2,0,0,0),(9,119000,1542858095,1000000,3000,4,2,0,0,0),(10,119001,1542859082,997000,-3000,1,32,0,0,0),(11,119004,1542859116,997000,-3000,1,32,0,0,0),(12,119000,1542859233,997000,-3000,1,32,0,0,0),(13,119000,1542859243,1000000,3000,4,32,0,0,0),(14,119001,1542859253,994000,-3000,1,2,0,0,0),(15,119000,1542866785,997000,-3000,1,32,0,0,0),(16,119007,1542867815,1000000,1000000,10,0,0,0,0),(17,119007,1542867854,997000,-3000,1,2,0,0,0),(18,119008,1542868487,1000000,1000000,10,0,0,0,0),(19,119009,1542875019,1000000,1000000,10,0,0,0,0),(20,119010,1542876308,1000000,1000000,10,0,0,0,0),(21,119011,1542876377,1000000,1000000,10,0,0,0,0),(22,119012,1542876420,1000000,1000000,10,0,0,0,0),(23,119013,1542876503,1000000,1000000,10,0,0,0,0),(24,119014,1542876845,1000000,1000000,10,0,0,0,0),(25,119015,1542877142,1000000,1000000,10,0,0,0,0),(26,119016,1542877886,1000000,1000000,10,0,0,0,0),(27,119013,1542878028,990000,-10000,1000,0,0,0,0),(28,119013,1542878037,1000000,10000,1001,0,0,0,0),(29,119015,1542879086,997000,-3000,1,2,0,0,0),(30,119015,1542879463,994000,-3000,1,2,0,0,0),(31,119015,1542879479,991000,-3000,1,2,0,0,0),(32,119015,1542879680,988000,-3000,1,2,0,0,0),(33,119001,1542879898,991000,-3000,1,32,0,0,0),(34,119013,1542880143,1004000,4000,3,2,0,0,0),(35,119015,1542880143,984000,-4000,3,2,0,0,0),(36,119013,1542880171,1008000,4000,3,2,0,0,0),(37,119015,1542880171,980000,-4000,3,2,0,0,0),(38,119013,1542880213,1006000,-2000,3,2,0,0,0),(39,119015,1542880213,982000,2000,3,2,0,0,0),(40,119013,1542880238,1009000,3000,3,2,0,0,0),(41,119015,1542880238,979000,-3000,3,2,0,0,0),(42,119013,1542880255,1011000,2000,3,2,0,0,0),(43,119015,1542880255,977000,-2000,3,2,0,0,0),(44,119013,1542880270,1007000,-4000,3,2,0,0,0),(45,119015,1542880270,981000,4000,3,2,0,0,0),(46,119013,1542880272,1006790,-210,8,2,0,0,0),(47,119005,1542880742,997000,-3000,1,2,0,0,0),(48,119014,1542881018,997000,-3000,1,32,0,0,0),(49,119013,1542881113,1003790,-3000,1,8,0,0,0),(50,119013,1542881387,1000790,-3000,1,8,0,0,0),(51,119013,1542881400,1003790,3000,4,8,0,0,0),(52,119013,1542881402,1000790,-3000,1,8,0,0,0),(53,119013,1542881541,1000990,200,3,8,0,0,0),(54,119014,1542881541,996800,-200,3,8,0,0,0),(55,119013,1542881595,1001090,100,3,8,0,0,0),(56,119014,1542881595,996700,-100,3,8,0,0,0),(57,119013,1542881635,1000790,-300,3,8,0,0,0),(58,119014,1542881635,997000,300,3,8,0,0,0),(59,119013,1542881679,1000490,-300,3,8,0,0,0),(60,119014,1542881679,997300,300,3,8,0,0,0),(61,119013,1542881720,1000190,-300,3,8,0,0,0),(62,119014,1542881720,997600,300,3,8,0,0,0),(63,119013,1542881768,1000390,200,3,8,0,0,0),(64,119014,1542881768,997400,-200,3,8,0,0,0),(65,119014,1542881769,997388,-12,8,8,0,0,0),(66,119013,1542882106,997390,-3000,1,2,0,0,0),(67,119013,1542882163,994390,-3000,1,8,0,0,0),(68,119014,1542882194,994388,-3000,1,14,0,0,0),(69,119014,1542882254,991388,-3000,1,20,0,0,0),(70,119014,1542882353,994388,3000,4,14,0,0,0),(71,119014,1542882407,991388,-3000,1,38,0,0,0),(72,119014,1542882434,994388,3000,4,38,0,0,0),(73,119014,1542882474,991388,-3000,1,2,0,0,0),(74,119015,1542883573,978000,-3000,1,2,0,0,0),(75,119001,1542884737,988000,-3000,1,2,0,0,0),(76,119001,1542886859,985000,-3000,1,32,0,0,0),(77,119019,1542938938,1000500,500,1005,0,0,0,0),(78,119003,1542946361,1000500,500,1005,0,0,0,0),(79,119003,1542951002,1000788,288,1009,0,0,0,0),(80,119006,1542951128,1000200,500,1005,0,0,0,0),(81,119001,1542951362,979500,500,1005,0,0,0,0),(82,119002,1542951633,1000500,500,1005,0,0,0,0),(83,119004,1542951761,997500,500,1005,0,0,0,0),(84,119005,1542952184,997500,500,1005,0,0,0,0),(85,119023,1542955216,1000500,500,1005,0,0,0,0),(86,119025,1542955217,1000500,500,1005,0,0,0,0),(87,119022,1542955220,1000500,500,1005,0,0,0,0),(88,119024,1542955227,1000500,500,1005,0,0,0,0),(89,119027,1542961208,1000500,500,1005,0,0,0,0),(90,119032,1542962864,1000500,500,1005,0,0,0,0),(91,119035,1542968729,1000001,1,1005,0,0,0,0),(92,119036,1542968891,1000001,1,1005,0,0,0,0),(93,119037,1542969549,1000001,1,1005,0,0,0,0),(94,119040,1542979423,1000001,1,1005,0,0,0,0),(95,119042,1542981937,1000001,1,1005,0,0,0,0),(96,119041,1542981939,1000001,1,1005,0,0,0,0),(97,119044,1542989842,1000001,1,1005,0,0,0,0),(98,119001,1542989941,971957,2,1005,0,0,0,0),(99,119022,1543023723,976093,2,1005,0,0,0,0),(100,119023,1543023731,946864,2,1005,0,0,0,0),(101,119024,1543023733,1017097,2,1005,0,0,0,0),(102,119002,1543027446,1000502,2,1005,0,0,0,0),(103,119002,1543027726,1002502,2000,1012,0,0,0,0),(104,119003,1543028553,997790,2,1005,0,0,0,0),(105,119005,1543036468,998375,2,1005,0,0,0,0),(106,119042,1543045272,998303,2,1005,0,0,0,0),(107,119041,1543046537,1000779,2,1005,0,0,0,0),(108,119013,1543048458,975691,1,1005,0,0,0,0),(109,119048,1543060075,1000001,1,1005,0,0,0,0),(110,119013,1543063973,855891,2000,1012,0,0,0,0),(111,119001,1543066548,970957,2000,1012,0,0,0,0),(112,119019,1543067721,1000502,2,1005,0,0,0,0),(113,119050,1543069435,1000001,1,1005,0,0,0,0),(114,119051,1543075659,1000001,1,1005,0,0,0,0),(115,119003,1543092890,997792,2,1005,0,0,0,0),(116,119005,1543110505,986377,2,1005,0,0,0,0),(117,119049,1543194644,1000001,1,1005,0,0,0,0),(118,119052,1543195652,1000001,1,1005,0,0,0,0),(119,119003,1543196972,997795,3,1005,0,0,0,0),(120,119002,1543198764,1002503,1,1005,0,0,0,0),(121,119001,1543198818,865958,1,1005,0,0,0,0),(122,119051,1543204458,868903,2,1005,0,0,0,0),(123,119042,1543210874,998304,1,1005,0,0,0,0),(124,119000,1543213429,891001,1,1005,0,0,0,0),(125,119113,1543217246,1000001,1,1005,0,0,0,0),(126,119006,1543221400,1000589,1,1005,0,0,0,0),(127,119115,1543222106,1000001,1,1005,0,0,0,0),(128,119019,1543223455,1000503,1,1005,0,0,0,0),(129,119013,1543225387,1172674,1,1007,0,0,0,0),(130,119117,1543230887,1000001,1,1005,0,0,0,0),(131,119118,1543233661,1000001,1,1005,0,0,0,0),(132,119004,1543238824,1001,1,1005,0,0,0,0),(133,119004,1543238898,1004,3,1007,0,0,0,0),(134,119005,1543239038,1003,3,1005,0,0,0,0),(135,119007,1543239290,100001,1,1005,0,0,0,0),(136,119008,1543239435,100001,1,1005,0,0,0,0),(137,119009,1543240119,100001,1,1005,0,0,0,0),(138,119009,1543240355,99902,1,1009,0,0,0,0),(139,119011,1543240485,100001,1,1005,0,0,0,0),(140,119010,1543240920,99401,1,1005,0,0,0,0),(141,119012,1543241325,100001,1,1005,0,0,0,0),(142,119013,1543244799,100001,1,1005,0,0,0,0),(143,119001,1543250973,50396,2,1005,0,0,0,0),(144,119004,1543252459,1782,2,1005,0,0,0,0),(145,119009,1543268603,102204,2,1005,0,0,0,0),(146,119014,1543275382,100001,1,1005,0,0,0,0),(147,119012,1543281178,100503,2,1005,0,0,0,0),(148,119015,1543281257,100001,1,1005,0,0,0,0),(149,119018,1543296966,100001,1,1005,0,0,0,0),(150,119000,1543486579,87664,1,1005,0,0,0,0),(151,119005,1543486592,100001,1,1005,0,0,0,0),(152,119007,1543486846,100000,100000,10,0,0,0,0);
/*!40000 ALTER TABLE `statistics_moneychange` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics_rewardspoo`
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
-- Dumping data for table `statistics_rewardspool`
--

LOCK TABLES `statistics_rewardspool` WRITE;
/*!40000 ALTER TABLE `statistics_rewardspool` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistics_rewardspool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userInfo`
--

DROP TABLE IF EXISTS `userInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userInfo` (
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
-- Dumping data for table `userInfo`
--

LOCK TABLES `userInfo` WRITE;
/*!40000 ALTER TABLE `userInfo` DISABLE KEYS */;
INSERT INTO `userInfo` VALUES (118000,'HMRobot118000','e10adc3949ba59abbe56e057f20f883e','孟晶宜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118001,'HMRobot118001','e10adc3949ba59abbe56e057f20f883e','经玮宜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118002,'HMRobot118002','e10adc3949ba59abbe56e057f20f883e','贺达鲁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118003,'HMRobot118003','e10adc3949ba59abbe56e057f20f883e','平雁汉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118004,'HMRobot118004','e10adc3949ba59abbe56e057f20f883e','和飞勇','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118005,'HMRobot118005','e10adc3949ba59abbe56e057f20f883e','王倩会','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118006,'HMRobot118006','e10adc3949ba59abbe56e057f20f883e','成雅睿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118007,'HMRobot118007','e10adc3949ba59abbe56e057f20f883e','臧梦洪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118008,'HMRobot118008','e10adc3949ba59abbe56e057f20f883e','常珊雷','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118009,'HMRobot118009','e10adc3949ba59abbe56e057f20f883e','莫进虎','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118010,'HMRobot118010','e10adc3949ba59abbe56e057f20f883e','梅寿声','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118011,'HMRobot118011','e10adc3949ba59abbe56e057f20f883e','洪田沛','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118012,'HMRobot118012','e10adc3949ba59abbe56e057f20f883e','陶雪伦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118013,'HMRobot118013','e10adc3949ba59abbe56e057f20f883e','龚婷珊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118014,'HMRobot118014','e10adc3949ba59abbe56e057f20f883e','熊根宝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118015,'HMRobot118015','e10adc3949ba59abbe56e057f20f883e','俞克亚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118016,'HMRobot118016','e10adc3949ba59abbe56e057f20f883e','孙麒升','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118017,'HMRobot118017','e10adc3949ba59abbe56e057f20f883e','樊茜佳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118018,'HMRobot118018','e10adc3949ba59abbe56e057f20f883e','陈勇思','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118019,'HMRobot118019','e10adc3949ba59abbe56e057f20f883e','吉天志','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118020,'HMRobot118020','e10adc3949ba59abbe56e057f20f883e','黄川彤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118021,'HMRobot118021','e10adc3949ba59abbe56e057f20f883e','昌峰东','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118022,'HMRobot118022','e10adc3949ba59abbe56e057f20f883e','茅小万','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118023,'HMRobot118023','e10adc3949ba59abbe56e057f20f883e','堪如为','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118024,'HMRobot118024','e10adc3949ba59abbe56e057f20f883e','陈方同','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118025,'HMRobot118025','e10adc3949ba59abbe56e057f20f883e','伏士良','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118026,'HMRobot118026','e10adc3949ba59abbe56e057f20f883e','曹涛德','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118027,'HMRobot118027','e10adc3949ba59abbe56e057f20f883e','萧军桂','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118028,'HMRobot118028','e10adc3949ba59abbe56e057f20f883e','滕同恒','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118029,'HMRobot118029','e10adc3949ba59abbe56e057f20f883e','蓝儿钰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118030,'HMRobot118030','e10adc3949ba59abbe56e057f20f883e','酆林锋','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118031,'HMRobot118031','e10adc3949ba59abbe56e057f20f883e','吴敬立','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118032,'HMRobot118032','e10adc3949ba59abbe56e057f20f883e','计源忠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118033,'HMRobot118033','e10adc3949ba59abbe56e057f20f883e','水晋欣','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118034,'HMRobot118034','e10adc3949ba59abbe56e057f20f883e','诸慧一','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118035,'HMRobot118035','e10adc3949ba59abbe56e057f20f883e','咎炜岚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118036,'HMRobot118036','e10adc3949ba59abbe56e057f20f883e','左棋鲁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118037,'HMRobot118037','e10adc3949ba59abbe56e057f20f883e','窦英月','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118038,'HMRobot118038','e10adc3949ba59abbe56e057f20f883e','干铭枫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118039,'HMRobot118039','e10adc3949ba59abbe56e057f20f883e','昌连夏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118040,'HMRobot118040','e10adc3949ba59abbe56e057f20f883e','陶航晖','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118041,'HMRobot118041','e10adc3949ba59abbe56e057f20f883e','谈枫贤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118042,'HMRobot118042','e10adc3949ba59abbe56e057f20f883e','昌捷皓','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118043,'HMRobot118043','e10adc3949ba59abbe56e057f20f883e','林刚田','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118044,'HMRobot118044','e10adc3949ba59abbe56e057f20f883e','孔中茜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118045,'HMRobot118045','e10adc3949ba59abbe56e057f20f883e','熊元栋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118046,'HMRobot118046','e10adc3949ba59abbe56e057f20f883e','徐伟月','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118047,'HMRobot118047','e10adc3949ba59abbe56e057f20f883e','项生清','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118048,'HMRobot118048','e10adc3949ba59abbe56e057f20f883e','郁勇冰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118049,'HMRobot118049','e10adc3949ba59abbe56e057f20f883e','纪川鑫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118050,'HMRobot118050','e10adc3949ba59abbe56e057f20f883e','花芳柏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118051,'HMRobot118051','e10adc3949ba59abbe56e057f20f883e','咎玲敏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118052,'HMRobot118052','e10adc3949ba59abbe56e057f20f883e','干金承','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118053,'HMRobot118053','e10adc3949ba59abbe56e057f20f883e','屈湘岩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118054,'HMRobot118054','e10adc3949ba59abbe56e057f20f883e','平梦伦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118055,'HMRobot118055','e10adc3949ba59abbe56e057f20f883e','蔡伟佩','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118056,'HMRobot118056','e10adc3949ba59abbe56e057f20f883e','史枫一','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118057,'HMRobot118057','e10adc3949ba59abbe56e057f20f883e','伍鹏锋','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118058,'HMRobot118058','e10adc3949ba59abbe56e057f20f883e','钟松定','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118059,'HMRobot118059','e10adc3949ba59abbe56e057f20f883e','梅冬田','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118060,'HMRobot118060','e10adc3949ba59abbe56e057f20f883e','廉胜靖','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118061,'HMRobot118061','e10adc3949ba59abbe56e057f20f883e','孙文华','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118062,'HMRobot118062','e10adc3949ba59abbe56e057f20f883e','赵菊珠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118063,'HMRobot118063','e10adc3949ba59abbe56e057f20f883e','闵勤宏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118064,'HMRobot118064','e10adc3949ba59abbe56e057f20f883e','吴庭西','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118065,'HMRobot118065','e10adc3949ba59abbe56e057f20f883e','祝权运','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118066,'HMRobot118066','e10adc3949ba59abbe56e057f20f883e','夏高彪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118067,'HMRobot118067','e10adc3949ba59abbe56e057f20f883e','穆玲立','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118068,'HMRobot118068','e10adc3949ba59abbe56e057f20f883e','何芬湘','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118069,'HMRobot118069','e10adc3949ba59abbe56e057f20f883e','万申智','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118070,'HMRobot118070','e10adc3949ba59abbe56e057f20f883e','孟诚政','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118071,'HMRobot118071','e10adc3949ba59abbe56e057f20f883e','喻勇辰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118072,'HMRobot118072','e10adc3949ba59abbe56e057f20f883e','宣世真','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118073,'HMRobot118073','e10adc3949ba59abbe56e057f20f883e','龚彦迪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118074,'HMRobot118074','e10adc3949ba59abbe56e057f20f883e','柯诗耀','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118075,'HMRobot118075','e10adc3949ba59abbe56e057f20f883e','宋晨鸿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118076,'HMRobot118076','e10adc3949ba59abbe56e057f20f883e','解佩久','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118077,'HMRobot118077','e10adc3949ba59abbe56e057f20f883e','康龙保','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118078,'HMRobot118078','e10adc3949ba59abbe56e057f20f883e','郁乐仲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118079,'HMRobot118079','e10adc3949ba59abbe56e057f20f883e','虞翔仪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118080,'HMRobot118080','e10adc3949ba59abbe56e057f20f883e','应西翰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118081,'HMRobot118081','e10adc3949ba59abbe56e057f20f883e','邓诗厚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118082,'HMRobot118082','e10adc3949ba59abbe56e057f20f883e','常其征','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118083,'HMRobot118083','e10adc3949ba59abbe56e057f20f883e','葛慧嘉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118084,'HMRobot118084','e10adc3949ba59abbe56e057f20f883e','宗玲芬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118085,'HMRobot118085','e10adc3949ba59abbe56e057f20f883e','陈兵秋','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118086,'HMRobot118086','e10adc3949ba59abbe56e057f20f883e','余灵棋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118087,'HMRobot118087','e10adc3949ba59abbe56e057f20f883e','解秀向','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118088,'HMRobot118088','e10adc3949ba59abbe56e057f20f883e','麻子爱','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118089,'HMRobot118089','e10adc3949ba59abbe56e057f20f883e','虞满松','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118090,'HMRobot118090','e10adc3949ba59abbe56e057f20f883e','倪启成','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118091,'HMRobot118091','e10adc3949ba59abbe56e057f20f883e','钱冰钰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118092,'HMRobot118092','e10adc3949ba59abbe56e057f20f883e','邵宇菁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118093,'HMRobot118093','e10adc3949ba59abbe56e057f20f883e','何蔚天','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118094,'HMRobot118094','e10adc3949ba59abbe56e057f20f883e','昌艺舒','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118095,'HMRobot118095','e10adc3949ba59abbe56e057f20f883e','谢昌沛','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118096,'HMRobot118096','e10adc3949ba59abbe56e057f20f883e','左纯波','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118097,'HMRobot118097','e10adc3949ba59abbe56e057f20f883e','丁泽依','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118098,'HMRobot118098','e10adc3949ba59abbe56e057f20f883e','沈靖乐','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118099,'HMRobot118099','e10adc3949ba59abbe56e057f20f883e','卫逸诗','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118100,'HMRobot118100','e10adc3949ba59abbe56e057f20f883e','骆龙培','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118101,'HMRobot118101','e10adc3949ba59abbe56e057f20f883e','樊咏东','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118102,'HMRobot118102','e10adc3949ba59abbe56e057f20f883e','周杰家','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118103,'HMRobot118103','e10adc3949ba59abbe56e057f20f883e','季碧之','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118104,'HMRobot118104','e10adc3949ba59abbe56e057f20f883e','吉鲁玲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118105,'HMRobot118105','e10adc3949ba59abbe56e057f20f883e','崔大欣','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118106,'HMRobot118106','e10adc3949ba59abbe56e057f20f883e','金权江','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118107,'HMRobot118107','e10adc3949ba59abbe56e057f20f883e','安亦仁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118108,'HMRobot118108','e10adc3949ba59abbe56e057f20f883e','单章亮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118109,'HMRobot118109','e10adc3949ba59abbe56e057f20f883e','任雄友','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118110,'HMRobot118110','e10adc3949ba59abbe56e057f20f883e','支政依','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118111,'HMRobot118111','e10adc3949ba59abbe56e057f20f883e','成奇沛','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118112,'HMRobot118112','e10adc3949ba59abbe56e057f20f883e','蔡晋琳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118113,'HMRobot118113','e10adc3949ba59abbe56e057f20f883e','江双纯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118114,'HMRobot118114','e10adc3949ba59abbe56e057f20f883e','徐正志','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118115,'HMRobot118115','e10adc3949ba59abbe56e057f20f883e','咎启日','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118116,'HMRobot118116','e10adc3949ba59abbe56e057f20f883e','席慧晨','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118117,'HMRobot118117','e10adc3949ba59abbe56e057f20f883e','石信良','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118118,'HMRobot118118','e10adc3949ba59abbe56e057f20f883e','纪显森','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118119,'HMRobot118119','e10adc3949ba59abbe56e057f20f883e','元田嘉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118120,'HMRobot118120','e10adc3949ba59abbe56e057f20f883e','卜奎兆','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118121,'HMRobot118121','e10adc3949ba59abbe56e057f20f883e','尹锡志','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118122,'HMRobot118122','e10adc3949ba59abbe56e057f20f883e','汪延睿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118123,'HMRobot118123','e10adc3949ba59abbe56e057f20f883e','凤磊博','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118124,'HMRobot118124','e10adc3949ba59abbe56e057f20f883e','娄臣燕','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118125,'HMRobot118125','e10adc3949ba59abbe56e057f20f883e','常碧洁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118126,'HMRobot118126','e10adc3949ba59abbe56e057f20f883e','秦昊伟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118127,'HMRobot118127','e10adc3949ba59abbe56e057f20f883e','何君胜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118128,'HMRobot118128','e10adc3949ba59abbe56e057f20f883e','范冬祥','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118129,'HMRobot118129','e10adc3949ba59abbe56e057f20f883e','沈敏崇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118130,'HMRobot118130','e10adc3949ba59abbe56e057f20f883e','陶广雁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118131,'HMRobot118131','e10adc3949ba59abbe56e057f20f883e','房森彤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118132,'HMRobot118132','e10adc3949ba59abbe56e057f20f883e','盛焕珊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118133,'HMRobot118133','e10adc3949ba59abbe56e057f20f883e','董孟卓','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118134,'HMRobot118134','e10adc3949ba59abbe56e057f20f883e','姚宇爱','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118135,'HMRobot118135','e10adc3949ba59abbe56e057f20f883e','姜刚鑫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118136,'HMRobot118136','e10adc3949ba59abbe56e057f20f883e','孔百艳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118137,'HMRobot118137','e10adc3949ba59abbe56e057f20f883e','颜琪剑','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118138,'HMRobot118138','e10adc3949ba59abbe56e057f20f883e','丁政定','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118139,'HMRobot118139','e10adc3949ba59abbe56e057f20f883e','黄廷雯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118140,'HMRobot118140','e10adc3949ba59abbe56e057f20f883e','严蕾友','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118141,'HMRobot118141','e10adc3949ba59abbe56e057f20f883e','童诚钧','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118142,'HMRobot118142','e10adc3949ba59abbe56e057f20f883e','贺凯昌','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118143,'HMRobot118143','e10adc3949ba59abbe56e057f20f883e','元冰全','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118144,'HMRobot118144','e10adc3949ba59abbe56e057f20f883e','戴风鸿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118145,'HMRobot118145','e10adc3949ba59abbe56e057f20f883e','姚泰有','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118146,'HMRobot118146','e10adc3949ba59abbe56e057f20f883e','元铭涵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118147,'HMRobot118147','e10adc3949ba59abbe56e057f20f883e','熊祖百','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118148,'HMRobot118148','e10adc3949ba59abbe56e057f20f883e','骆妍京','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118149,'HMRobot118149','e10adc3949ba59abbe56e057f20f883e','顾妍泉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118150,'HMRobot118150','e10adc3949ba59abbe56e057f20f883e','施源基','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118151,'HMRobot118151','e10adc3949ba59abbe56e057f20f883e','昌菲冬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118152,'HMRobot118152','e10adc3949ba59abbe56e057f20f883e','陈妍可','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118153,'HMRobot118153','e10adc3949ba59abbe56e057f20f883e','董敏双','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118154,'HMRobot118154','e10adc3949ba59abbe56e057f20f883e','倪孟孝','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118155,'HMRobot118155','e10adc3949ba59abbe56e057f20f883e','宗泉坚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118156,'HMRobot118156','e10adc3949ba59abbe56e057f20f883e','柏亚军','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118157,'HMRobot118157','e10adc3949ba59abbe56e057f20f883e','卢宏升','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118158,'HMRobot118158','e10adc3949ba59abbe56e057f20f883e','昌长昌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118159,'HMRobot118159','e10adc3949ba59abbe56e057f20f883e','项晖恩','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118160,'HMRobot118160','e10adc3949ba59abbe56e057f20f883e','梅冠少','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118161,'HMRobot118161','e10adc3949ba59abbe56e057f20f883e','杨宝锦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118162,'HMRobot118162','e10adc3949ba59abbe56e057f20f883e','杭国德','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118163,'HMRobot118163','e10adc3949ba59abbe56e057f20f883e','虞会紫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118164,'HMRobot118164','e10adc3949ba59abbe56e057f20f883e','杜祖彪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118165,'HMRobot118165','e10adc3949ba59abbe56e057f20f883e','华飞新','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118166,'HMRobot118166','e10adc3949ba59abbe56e057f20f883e','傅玮跃','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118167,'HMRobot118167','e10adc3949ba59abbe56e057f20f883e','王帆润','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118168,'HMRobot118168','e10adc3949ba59abbe56e057f20f883e','于碧谦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118169,'HMRobot118169','e10adc3949ba59abbe56e057f20f883e','周贵孝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118170,'HMRobot118170','e10adc3949ba59abbe56e057f20f883e','汪奕伟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118171,'HMRobot118171','e10adc3949ba59abbe56e057f20f883e','穆保娜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118172,'HMRobot118172','e10adc3949ba59abbe56e057f20f883e','许水承','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118173,'HMRobot118173','e10adc3949ba59abbe56e057f20f883e','卜咏金','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118174,'HMRobot118174','e10adc3949ba59abbe56e057f20f883e','戚桂平','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118175,'HMRobot118175','e10adc3949ba59abbe56e057f20f883e','单日腾','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118176,'HMRobot118176','e10adc3949ba59abbe56e057f20f883e','冯雨标','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118177,'HMRobot118177','e10adc3949ba59abbe56e057f20f883e','洪芬平','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118178,'HMRobot118178','e10adc3949ba59abbe56e057f20f883e','华如一','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118179,'HMRobot118179','e10adc3949ba59abbe56e057f20f883e','贲来顺','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118180,'HMRobot118180','e10adc3949ba59abbe56e057f20f883e','柏麒洋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118181,'HMRobot118181','e10adc3949ba59abbe56e057f20f883e','严立田','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118182,'HMRobot118182','e10adc3949ba59abbe56e057f20f883e','诸久骏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118183,'HMRobot118183','e10adc3949ba59abbe56e057f20f883e','明涵捷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118184,'HMRobot118184','e10adc3949ba59abbe56e057f20f883e','强钧廷','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118185,'HMRobot118185','e10adc3949ba59abbe56e057f20f883e','咎兰如','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118186,'HMRobot118186','e10adc3949ba59abbe56e057f20f883e','卢政乃','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118187,'HMRobot118187','e10adc3949ba59abbe56e057f20f883e','马爽尧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118188,'HMRobot118188','e10adc3949ba59abbe56e057f20f883e','费静培','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118189,'HMRobot118189','e10adc3949ba59abbe56e057f20f883e','熊声涵','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118190,'HMRobot118190','e10adc3949ba59abbe56e057f20f883e','华宁建','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118191,'HMRobot118191','e10adc3949ba59abbe56e057f20f883e','刁厚爽','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118192,'HMRobot118192','e10adc3949ba59abbe56e057f20f883e','裘汝进','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118193,'HMRobot118193','e10adc3949ba59abbe56e057f20f883e','米岳和','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118194,'HMRobot118194','e10adc3949ba59abbe56e057f20f883e','凤涛雁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118195,'HMRobot118195','e10adc3949ba59abbe56e057f20f883e','马斌勇','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118196,'HMRobot118196','e10adc3949ba59abbe56e057f20f883e','管宇亦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118197,'HMRobot118197','e10adc3949ba59abbe56e057f20f883e','蒋燕升','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118198,'HMRobot118198','e10adc3949ba59abbe56e057f20f883e','贾书卫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118199,'HMRobot118199','e10adc3949ba59abbe56e057f20f883e','唐武宏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118200,'HMRobot118200','e10adc3949ba59abbe56e057f20f883e','马扬一','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118201,'HMRobot118201','e10adc3949ba59abbe56e057f20f883e','强洲满','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118202,'HMRobot118202','e10adc3949ba59abbe56e057f20f883e','曹晓瑜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118203,'HMRobot118203','e10adc3949ba59abbe56e057f20f883e','纪珠夫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118204,'HMRobot118204','e10adc3949ba59abbe56e057f20f883e','诸征海','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118205,'HMRobot118205','e10adc3949ba59abbe56e057f20f883e','雷瑜银','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118206,'HMRobot118206','e10adc3949ba59abbe56e057f20f883e','宗靖昕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118207,'HMRobot118207','e10adc3949ba59abbe56e057f20f883e','时正江','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118208,'HMRobot118208','e10adc3949ba59abbe56e057f20f883e','尤康德','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118209,'HMRobot118209','e10adc3949ba59abbe56e057f20f883e','吉进中','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118210,'HMRobot118210','e10adc3949ba59abbe56e057f20f883e','严君小','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118211,'HMRobot118211','e10adc3949ba59abbe56e057f20f883e','路基松','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118212,'HMRobot118212','e10adc3949ba59abbe56e057f20f883e','成慧少','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118213,'HMRobot118213','e10adc3949ba59abbe56e057f20f883e','贺彪冰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118214,'HMRobot118214','e10adc3949ba59abbe56e057f20f883e','昌标子','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118215,'HMRobot118215','e10adc3949ba59abbe56e057f20f883e','窦顺劲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118216,'HMRobot118216','e10adc3949ba59abbe56e057f20f883e','云谦权','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118217,'HMRobot118217','e10adc3949ba59abbe56e057f20f883e','颜非汝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118218,'HMRobot118218','e10adc3949ba59abbe56e057f20f883e','祝岩俊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118219,'HMRobot118219','e10adc3949ba59abbe56e057f20f883e','傅焕真','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118220,'HMRobot118220','e10adc3949ba59abbe56e057f20f883e','元可刚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118221,'HMRobot118221','e10adc3949ba59abbe56e057f20f883e','杜真升','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118222,'HMRobot118222','e10adc3949ba59abbe56e057f20f883e','云茜天','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118223,'HMRobot118223','e10adc3949ba59abbe56e057f20f883e','蒋诚庭','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118224,'HMRobot118224','e10adc3949ba59abbe56e057f20f883e','黄秀昊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118225,'HMRobot118225','e10adc3949ba59abbe56e057f20f883e','蔡中曼','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118226,'HMRobot118226','e10adc3949ba59abbe56e057f20f883e','安政子','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118227,'HMRobot118227','e10adc3949ba59abbe56e057f20f883e','曹祥素','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118228,'HMRobot118228','e10adc3949ba59abbe56e057f20f883e','平延同','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118229,'HMRobot118229','e10adc3949ba59abbe56e057f20f883e','吉其真','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118230,'HMRobot118230','e10adc3949ba59abbe56e057f20f883e','闵方雅','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118231,'HMRobot118231','e10adc3949ba59abbe56e057f20f883e','沈日锦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118232,'HMRobot118232','e10adc3949ba59abbe56e057f20f883e','姜宁玲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118233,'HMRobot118233','e10adc3949ba59abbe56e057f20f883e','齐毅玮','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118234,'HMRobot118234','e10adc3949ba59abbe56e057f20f883e','酆书星','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118235,'HMRobot118235','e10adc3949ba59abbe56e057f20f883e','方芳和','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118236,'HMRobot118236','e10adc3949ba59abbe56e057f20f883e','邱显丹','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118237,'HMRobot118237','e10adc3949ba59abbe56e057f20f883e','邹培镇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118238,'HMRobot118238','e10adc3949ba59abbe56e057f20f883e','齐璇雷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118239,'HMRobot118239','e10adc3949ba59abbe56e057f20f883e','霍玉博','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118240,'HMRobot118240','e10adc3949ba59abbe56e057f20f883e','堪树庭','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118241,'HMRobot118241','e10adc3949ba59abbe56e057f20f883e','齐城羽','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118242,'HMRobot118242','e10adc3949ba59abbe56e057f20f883e','萧孟康','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118243,'HMRobot118243','e10adc3949ba59abbe56e057f20f883e','林蕾道','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118244,'HMRobot118244','e10adc3949ba59abbe56e057f20f883e','倪崇哲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118245,'HMRobot118245','e10adc3949ba59abbe56e057f20f883e','莫诗强','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118246,'HMRobot118246','e10adc3949ba59abbe56e057f20f883e','史鲁男','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118247,'HMRobot118247','e10adc3949ba59abbe56e057f20f883e','林安兵','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118248,'HMRobot118248','e10adc3949ba59abbe56e057f20f883e','马源建','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118249,'HMRobot118249','e10adc3949ba59abbe56e057f20f883e','杭孝铁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118250,'HMRobot118250','e10adc3949ba59abbe56e057f20f883e','麻斌之','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118251,'HMRobot118251','e10adc3949ba59abbe56e057f20f883e','汤彪皓','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118252,'HMRobot118252','e10adc3949ba59abbe56e057f20f883e','戚斌升','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118253,'HMRobot118253','e10adc3949ba59abbe56e057f20f883e','史田鹤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118254,'HMRobot118254','e10adc3949ba59abbe56e057f20f883e','伏中潮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118255,'HMRobot118255','e10adc3949ba59abbe56e057f20f883e','项彪萌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118256,'HMRobot118256','e10adc3949ba59abbe56e057f20f883e','王平梅','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118257,'HMRobot118257','e10adc3949ba59abbe56e057f20f883e','杜石化','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118258,'HMRobot118258','e10adc3949ba59abbe56e057f20f883e','尹颖少','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118259,'HMRobot118259','e10adc3949ba59abbe56e057f20f883e','酆超道','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118260,'HMRobot118260','e10adc3949ba59abbe56e057f20f883e','倪奕洲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118261,'HMRobot118261','e10adc3949ba59abbe56e057f20f883e','朱丹然','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118262,'HMRobot118262','e10adc3949ba59abbe56e057f20f883e','江荣翰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118263,'HMRobot118263','e10adc3949ba59abbe56e057f20f883e','齐秋学','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118264,'HMRobot118264','e10adc3949ba59abbe56e057f20f883e','蒋建卓','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118265,'HMRobot118265','e10adc3949ba59abbe56e057f20f883e','窦基夫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118266,'HMRobot118266','e10adc3949ba59abbe56e057f20f883e','费川克','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118267,'HMRobot118267','e10adc3949ba59abbe56e057f20f883e','蓝善岚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118268,'HMRobot118268','e10adc3949ba59abbe56e057f20f883e','平连江','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118269,'HMRobot118269','e10adc3949ba59abbe56e057f20f883e','夏晋西','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118270,'HMRobot118270','e10adc3949ba59abbe56e057f20f883e','咎兵一','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118271,'HMRobot118271','e10adc3949ba59abbe56e057f20f883e','方翔城','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118272,'HMRobot118272','e10adc3949ba59abbe56e057f20f883e','曹卓辰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118273,'HMRobot118273','e10adc3949ba59abbe56e057f20f883e','纪西泉','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118274,'HMRobot118274','e10adc3949ba59abbe56e057f20f883e','陈强岚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118275,'HMRobot118275','e10adc3949ba59abbe56e057f20f883e','霍乐逸','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118276,'HMRobot118276','e10adc3949ba59abbe56e057f20f883e','于超学','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118277,'HMRobot118277','e10adc3949ba59abbe56e057f20f883e','强红艳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118278,'HMRobot118278','e10adc3949ba59abbe56e057f20f883e','尤显爱','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118279,'HMRobot118279','e10adc3949ba59abbe56e057f20f883e','汪光满','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118280,'HMRobot118280','e10adc3949ba59abbe56e057f20f883e','纪石艺','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118281,'HMRobot118281','e10adc3949ba59abbe56e057f20f883e','朱乐亦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118282,'HMRobot118282','e10adc3949ba59abbe56e057f20f883e','粱宪显','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118283,'HMRobot118283','e10adc3949ba59abbe56e057f20f883e','龚振根','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118284,'HMRobot118284','e10adc3949ba59abbe56e057f20f883e','颜运全','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118285,'HMRobot118285','e10adc3949ba59abbe56e057f20f883e','强卫怀','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118286,'HMRobot118286','e10adc3949ba59abbe56e057f20f883e','伍翰石','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118287,'HMRobot118287','e10adc3949ba59abbe56e057f20f883e','经岩勋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118288,'HMRobot118288','e10adc3949ba59abbe56e057f20f883e','陈萌秋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118289,'HMRobot118289','e10adc3949ba59abbe56e057f20f883e','马晶庭','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118290,'HMRobot118290','e10adc3949ba59abbe56e057f20f883e','孔建凤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118291,'HMRobot118291','e10adc3949ba59abbe56e057f20f883e','章学雯','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118292,'HMRobot118292','e10adc3949ba59abbe56e057f20f883e','昌其军','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118293,'HMRobot118293','e10adc3949ba59abbe56e057f20f883e','祁雪立','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118294,'HMRobot118294','e10adc3949ba59abbe56e057f20f883e','郎靖咏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118295,'HMRobot118295','e10adc3949ba59abbe56e057f20f883e','郝琴冠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118296,'HMRobot118296','e10adc3949ba59abbe56e057f20f883e','毛麟江','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118297,'HMRobot118297','e10adc3949ba59abbe56e057f20f883e','方启进','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118298,'HMRobot118298','e10adc3949ba59abbe56e057f20f883e','郎杰京','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118299,'HMRobot118299','e10adc3949ba59abbe56e057f20f883e','韦兰劲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118300,'HMRobot118300','e10adc3949ba59abbe56e057f20f883e','丁云铁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118301,'HMRobot118301','e10adc3949ba59abbe56e057f20f883e','米劲喜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118302,'HMRobot118302','e10adc3949ba59abbe56e057f20f883e','安宪高','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118303,'HMRobot118303','e10adc3949ba59abbe56e057f20f883e','刁圣中','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118304,'HMRobot118304','e10adc3949ba59abbe56e057f20f883e','唐磊威','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118305,'HMRobot118305','e10adc3949ba59abbe56e057f20f883e','解双学','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118306,'HMRobot118306','e10adc3949ba59abbe56e057f20f883e','鲁丽文','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118307,'HMRobot118307','e10adc3949ba59abbe56e057f20f883e','喻宜诗','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118308,'HMRobot118308','e10adc3949ba59abbe56e057f20f883e','包华敏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118309,'HMRobot118309','e10adc3949ba59abbe56e057f20f883e','娄刚凌','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118310,'HMRobot118310','e10adc3949ba59abbe56e057f20f883e','骆婷申','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118311,'HMRobot118311','e10adc3949ba59abbe56e057f20f883e','夏源泉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118312,'HMRobot118312','e10adc3949ba59abbe56e057f20f883e','郁京怀','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118313,'HMRobot118313','e10adc3949ba59abbe56e057f20f883e','齐祖长','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118314,'HMRobot118314','e10adc3949ba59abbe56e057f20f883e','萧素波','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118315,'HMRobot118315','e10adc3949ba59abbe56e057f20f883e','郁锦顺','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118316,'HMRobot118316','e10adc3949ba59abbe56e057f20f883e','雷满来','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118317,'HMRobot118317','e10adc3949ba59abbe56e057f20f883e','贝萍淑','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118318,'HMRobot118318','e10adc3949ba59abbe56e057f20f883e','胡兆雷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118319,'HMRobot118319','e10adc3949ba59abbe56e057f20f883e','祁扬峰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118320,'HMRobot118320','e10adc3949ba59abbe56e057f20f883e','邵波斌','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118321,'HMRobot118321','e10adc3949ba59abbe56e057f20f883e','乐标越','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118322,'HMRobot118322','e10adc3949ba59abbe56e057f20f883e','徐双炳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118323,'HMRobot118323','e10adc3949ba59abbe56e057f20f883e','卫雅威','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118324,'HMRobot118324','e10adc3949ba59abbe56e057f20f883e','戴义勋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118325,'HMRobot118325','e10adc3949ba59abbe56e057f20f883e','颜长聪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118326,'HMRobot118326','e10adc3949ba59abbe56e057f20f883e','汤秋鑫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118327,'HMRobot118327','e10adc3949ba59abbe56e057f20f883e','华夫雷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118328,'HMRobot118328','e10adc3949ba59abbe56e057f20f883e','汤露顺','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118329,'HMRobot118329','e10adc3949ba59abbe56e057f20f883e','倪谦辰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118330,'HMRobot118330','e10adc3949ba59abbe56e057f20f883e','郝锡贵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118331,'HMRobot118331','e10adc3949ba59abbe56e057f20f883e','昌威柏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118332,'HMRobot118332','e10adc3949ba59abbe56e057f20f883e','黄达天','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118333,'HMRobot118333','e10adc3949ba59abbe56e057f20f883e','禹子雷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118334,'HMRobot118334','e10adc3949ba59abbe56e057f20f883e','郑克芝','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118335,'HMRobot118335','e10adc3949ba59abbe56e057f20f883e','宣蔚瑜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118336,'HMRobot118336','e10adc3949ba59abbe56e057f20f883e','赵滨紫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118337,'HMRobot118337','e10adc3949ba59abbe56e057f20f883e','汤小一','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118338,'HMRobot118338','e10adc3949ba59abbe56e057f20f883e','戚琦灵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118339,'HMRobot118339','e10adc3949ba59abbe56e057f20f883e','苗滨敏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118340,'HMRobot118340','e10adc3949ba59abbe56e057f20f883e','岑友石','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118341,'HMRobot118341','e10adc3949ba59abbe56e057f20f883e','蓝辰鹏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118342,'HMRobot118342','e10adc3949ba59abbe56e057f20f883e','许群道','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118343,'HMRobot118343','e10adc3949ba59abbe56e057f20f883e','梅川伊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118344,'HMRobot118344','e10adc3949ba59abbe56e057f20f883e','平承年','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118345,'HMRobot118345','e10adc3949ba59abbe56e057f20f883e','丁凤培','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118346,'HMRobot118346','e10adc3949ba59abbe56e057f20f883e','殷满潮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118347,'HMRobot118347','e10adc3949ba59abbe56e057f20f883e','苏臣乐','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118348,'HMRobot118348','e10adc3949ba59abbe56e057f20f883e','钮翔若','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118349,'HMRobot118349','e10adc3949ba59abbe56e057f20f883e','庞纯子','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118350,'HMRobot118350','e10adc3949ba59abbe56e057f20f883e','魏庆力','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118351,'HMRobot118351','e10adc3949ba59abbe56e057f20f883e','平思瑜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118352,'HMRobot118352','e10adc3949ba59abbe56e057f20f883e','钮萌羽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118353,'HMRobot118353','e10adc3949ba59abbe56e057f20f883e','曹定梦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118354,'HMRobot118354','e10adc3949ba59abbe56e057f20f883e','骆振明','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118355,'HMRobot118355','e10adc3949ba59abbe56e057f20f883e','钟菁忠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118356,'HMRobot118356','e10adc3949ba59abbe56e057f20f883e','祝涛洁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118357,'HMRobot118357','e10adc3949ba59abbe56e057f20f883e','粱吉谦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118358,'HMRobot118358','e10adc3949ba59abbe56e057f20f883e','毕山依','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118359,'HMRobot118359','e10adc3949ba59abbe56e057f20f883e','夏麟礼','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118360,'HMRobot118360','e10adc3949ba59abbe56e057f20f883e','尹延雁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118361,'HMRobot118361','e10adc3949ba59abbe56e057f20f883e','屈军炜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118362,'HMRobot118362','e10adc3949ba59abbe56e057f20f883e','乐良银','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118363,'HMRobot118363','e10adc3949ba59abbe56e057f20f883e','谈敏蔚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118364,'HMRobot118364','e10adc3949ba59abbe56e057f20f883e','朱颖虎','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118365,'HMRobot118365','e10adc3949ba59abbe56e057f20f883e','刁羽平','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118366,'HMRobot118366','e10adc3949ba59abbe56e057f20f883e','韩麟君','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118367,'HMRobot118367','e10adc3949ba59abbe56e057f20f883e','于定洪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118368,'HMRobot118368','e10adc3949ba59abbe56e057f20f883e','俞继征','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118369,'HMRobot118369','e10adc3949ba59abbe56e057f20f883e','陈欣维','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118370,'HMRobot118370','e10adc3949ba59abbe56e057f20f883e','管申波','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118371,'HMRobot118371','e10adc3949ba59abbe56e057f20f883e','何宁瑞','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118372,'HMRobot118372','e10adc3949ba59abbe56e057f20f883e','酆跃红','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118373,'HMRobot118373','e10adc3949ba59abbe56e057f20f883e','汤智海','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118374,'HMRobot118374','e10adc3949ba59abbe56e057f20f883e','乐忠海','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118375,'HMRobot118375','e10adc3949ba59abbe56e057f20f883e','邓标天','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118376,'HMRobot118376','e10adc3949ba59abbe56e057f20f883e','谢翔艳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118377,'HMRobot118377','e10adc3949ba59abbe56e057f20f883e','咎彬凌','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118378,'HMRobot118378','e10adc3949ba59abbe56e057f20f883e','郁颖然','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118379,'HMRobot118379','e10adc3949ba59abbe56e057f20f883e','钮书权','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118380,'HMRobot118380','e10adc3949ba59abbe56e057f20f883e','金辰孝','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118381,'HMRobot118381','e10adc3949ba59abbe56e057f20f883e','吕润士','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118382,'HMRobot118382','e10adc3949ba59abbe56e057f20f883e','田康伟','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118383,'HMRobot118383','e10adc3949ba59abbe56e057f20f883e','章一有','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118384,'HMRobot118384','e10adc3949ba59abbe56e057f20f883e','霍勇爽','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118385,'HMRobot118385','e10adc3949ba59abbe56e057f20f883e','喻传菁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118386,'HMRobot118386','e10adc3949ba59abbe56e057f20f883e','尹梦威','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118387,'HMRobot118387','e10adc3949ba59abbe56e057f20f883e','倪涛彬','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118388,'HMRobot118388','e10adc3949ba59abbe56e057f20f883e','裘鹏萍','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118389,'HMRobot118389','e10adc3949ba59abbe56e057f20f883e','马潮永','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118390,'HMRobot118390','e10adc3949ba59abbe56e057f20f883e','穆力保','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118391,'HMRobot118391','e10adc3949ba59abbe56e057f20f883e','奚越舒','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118392,'HMRobot118392','e10adc3949ba59abbe56e057f20f883e','潘秋方','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118393,'HMRobot118393','e10adc3949ba59abbe56e057f20f883e','余妍磊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118394,'HMRobot118394','e10adc3949ba59abbe56e057f20f883e','廉景善','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118395,'HMRobot118395','e10adc3949ba59abbe56e057f20f883e','贲贤炜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118396,'HMRobot118396','e10adc3949ba59abbe56e057f20f883e','宗逸升','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118397,'HMRobot118397','e10adc3949ba59abbe56e057f20f883e','钮灿思','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118398,'HMRobot118398','e10adc3949ba59abbe56e057f20f883e','席可连','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118399,'HMRobot118399','e10adc3949ba59abbe56e057f20f883e','殷毅男','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118400,'HMRobot118400','e10adc3949ba59abbe56e057f20f883e','马心泉','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118401,'HMRobot118401','e10adc3949ba59abbe56e057f20f883e','何年宗','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118402,'HMRobot118402','e10adc3949ba59abbe56e057f20f883e','倪福贤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118403,'HMRobot118403','e10adc3949ba59abbe56e057f20f883e','裘晋高','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118404,'HMRobot118404','e10adc3949ba59abbe56e057f20f883e','和星德','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118405,'HMRobot118405','e10adc3949ba59abbe56e057f20f883e','支月成','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118406,'HMRobot118406','e10adc3949ba59abbe56e057f20f883e','祁维捷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118407,'HMRobot118407','e10adc3949ba59abbe56e057f20f883e','米乃信','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118408,'HMRobot118408','e10adc3949ba59abbe56e057f20f883e','项碧珊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118409,'HMRobot118409','e10adc3949ba59abbe56e057f20f883e','董皓滨','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118410,'HMRobot118410','e10adc3949ba59abbe56e057f20f883e','袁群为','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118411,'HMRobot118411','e10adc3949ba59abbe56e057f20f883e','董然山','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118412,'HMRobot118412','e10adc3949ba59abbe56e057f20f883e','米俊青','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118413,'HMRobot118413','e10adc3949ba59abbe56e057f20f883e','成仲燕','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118414,'HMRobot118414','e10adc3949ba59abbe56e057f20f883e','项涛化','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118415,'HMRobot118415','e10adc3949ba59abbe56e057f20f883e','郭依红','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118416,'HMRobot118416','e10adc3949ba59abbe56e057f20f883e','罗友毅','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118417,'HMRobot118417','e10adc3949ba59abbe56e057f20f883e','何礼山','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118418,'HMRobot118418','e10adc3949ba59abbe56e057f20f883e','魏瑞泽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118419,'HMRobot118419','e10adc3949ba59abbe56e057f20f883e','方江磊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118420,'HMRobot118420','e10adc3949ba59abbe56e057f20f883e','卫仪寿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118421,'HMRobot118421','e10adc3949ba59abbe56e057f20f883e','金梅昌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118422,'HMRobot118422','e10adc3949ba59abbe56e057f20f883e','齐哲大','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118423,'HMRobot118423','e10adc3949ba59abbe56e057f20f883e','诸雪萍','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118424,'HMRobot118424','e10adc3949ba59abbe56e057f20f883e','郁露瑜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118425,'HMRobot118425','e10adc3949ba59abbe56e057f20f883e','崔圣恩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118426,'HMRobot118426','e10adc3949ba59abbe56e057f20f883e','陶欣璇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118427,'HMRobot118427','e10adc3949ba59abbe56e057f20f883e','苏栋辰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118428,'HMRobot118428','e10adc3949ba59abbe56e057f20f883e','苗爽翰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118429,'HMRobot118429','e10adc3949ba59abbe56e057f20f883e','马尧家','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118430,'HMRobot118430','e10adc3949ba59abbe56e057f20f883e','陈冬腾','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118431,'HMRobot118431','e10adc3949ba59abbe56e057f20f883e','柯根霖','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118432,'HMRobot118432','e10adc3949ba59abbe56e057f20f883e','解义革','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118433,'HMRobot118433','e10adc3949ba59abbe56e057f20f883e','鲁鸣崇','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118434,'HMRobot118434','e10adc3949ba59abbe56e057f20f883e','苏满中','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118435,'HMRobot118435','e10adc3949ba59abbe56e057f20f883e','贾贵羽','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118436,'HMRobot118436','e10adc3949ba59abbe56e057f20f883e','葛刚世','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118437,'HMRobot118437','e10adc3949ba59abbe56e057f20f883e','姜信岳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118438,'HMRobot118438','e10adc3949ba59abbe56e057f20f883e','管川腾','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118439,'HMRobot118439','e10adc3949ba59abbe56e057f20f883e','邱一曼','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118440,'HMRobot118440','e10adc3949ba59abbe56e057f20f883e','康帆胜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118441,'HMRobot118441','e10adc3949ba59abbe56e057f20f883e','云岳帆','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118442,'HMRobot118442','e10adc3949ba59abbe56e057f20f883e','熊鹤鸿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118443,'HMRobot118443','e10adc3949ba59abbe56e057f20f883e','卜申骏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118444,'HMRobot118444','e10adc3949ba59abbe56e057f20f883e','周少向','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118445,'HMRobot118445','e10adc3949ba59abbe56e057f20f883e','房鹏艳','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118446,'HMRobot118446','e10adc3949ba59abbe56e057f20f883e','汤晓海','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118447,'HMRobot118447','e10adc3949ba59abbe56e057f20f883e','吴福启','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118448,'HMRobot118448','e10adc3949ba59abbe56e057f20f883e','蒋梓纯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118449,'HMRobot118449','e10adc3949ba59abbe56e057f20f883e','殷宇权','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118450,'HMRobot118450','e10adc3949ba59abbe56e057f20f883e','范康夏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118451,'HMRobot118451','e10adc3949ba59abbe56e057f20f883e','蔡丹圣','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118452,'HMRobot118452','e10adc3949ba59abbe56e057f20f883e','宣航良','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118453,'HMRobot118453','e10adc3949ba59abbe56e057f20f883e','曹泽广','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118454,'HMRobot118454','e10adc3949ba59abbe56e057f20f883e','樊有臣','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118455,'HMRobot118455','e10adc3949ba59abbe56e057f20f883e','徐楚田','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118456,'HMRobot118456','e10adc3949ba59abbe56e057f20f883e','俞萍颖','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118457,'HMRobot118457','e10adc3949ba59abbe56e057f20f883e','经克章','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118458,'HMRobot118458','e10adc3949ba59abbe56e057f20f883e','任希琪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118459,'HMRobot118459','e10adc3949ba59abbe56e057f20f883e','苗喜江','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118460,'HMRobot118460','e10adc3949ba59abbe56e057f20f883e','崔玮洁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118461,'HMRobot118461','e10adc3949ba59abbe56e057f20f883e','莫谦炜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118462,'HMRobot118462','e10adc3949ba59abbe56e057f20f883e','高健晶','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118463,'HMRobot118463','e10adc3949ba59abbe56e057f20f883e','柏麟群','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118464,'HMRobot118464','e10adc3949ba59abbe56e057f20f883e','贾亚保','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118465,'HMRobot118465','e10adc3949ba59abbe56e057f20f883e','蒋琪世','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118466,'HMRobot118466','e10adc3949ba59abbe56e057f20f883e','诸菁贤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118467,'HMRobot118467','e10adc3949ba59abbe56e057f20f883e','元会江','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118468,'HMRobot118468','e10adc3949ba59abbe56e057f20f883e','奚芬良','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118469,'HMRobot118469','e10adc3949ba59abbe56e057f20f883e','石万萌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118470,'HMRobot118470','e10adc3949ba59abbe56e057f20f883e','项祥虎','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118471,'HMRobot118471','e10adc3949ba59abbe56e057f20f883e','林显静','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118472,'HMRobot118472','e10adc3949ba59abbe56e057f20f883e','禹大铁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118473,'HMRobot118473','e10adc3949ba59abbe56e057f20f883e','茅铁平','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118474,'HMRobot118474','e10adc3949ba59abbe56e057f20f883e','颜圣群','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118475,'HMRobot118475','e10adc3949ba59abbe56e057f20f883e','贾安京','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118476,'HMRobot118476','e10adc3949ba59abbe56e057f20f883e','李羽铭','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118477,'HMRobot118477','e10adc3949ba59abbe56e057f20f883e','任萌沛','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118478,'HMRobot118478','e10adc3949ba59abbe56e057f20f883e','祁冠桐','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118479,'HMRobot118479','e10adc3949ba59abbe56e057f20f883e','苗刚菊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118480,'HMRobot118480','e10adc3949ba59abbe56e057f20f883e','皮昌艳','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118481,'HMRobot118481','e10adc3949ba59abbe56e057f20f883e','石林启','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118482,'HMRobot118482','e10adc3949ba59abbe56e057f20f883e','陶林久','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118483,'HMRobot118483','e10adc3949ba59abbe56e057f20f883e','殷卓江','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118484,'HMRobot118484','e10adc3949ba59abbe56e057f20f883e','钟黎良','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118485,'HMRobot118485','e10adc3949ba59abbe56e057f20f883e','高恩方','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118486,'HMRobot118486','e10adc3949ba59abbe56e057f20f883e','舒晨子','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118487,'HMRobot118487','e10adc3949ba59abbe56e057f20f883e','堪忠昕','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118488,'HMRobot118488','e10adc3949ba59abbe56e057f20f883e','鲍田来','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118489,'HMRobot118489','e10adc3949ba59abbe56e057f20f883e','舒凯向','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118490,'HMRobot118490','e10adc3949ba59abbe56e057f20f883e','汤其燕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118491,'HMRobot118491','e10adc3949ba59abbe56e057f20f883e','柯世宝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118492,'HMRobot118492','e10adc3949ba59abbe56e057f20f883e','许克雁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118493,'HMRobot118493','e10adc3949ba59abbe56e057f20f883e','钮善军','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118494,'HMRobot118494','e10adc3949ba59abbe56e057f20f883e','胡妍章','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118495,'HMRobot118495','e10adc3949ba59abbe56e057f20f883e','赵舒兆','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118496,'HMRobot118496','e10adc3949ba59abbe56e057f20f883e','姜扬仲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118497,'HMRobot118497','e10adc3949ba59abbe56e057f20f883e','苏克希','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118498,'HMRobot118498','e10adc3949ba59abbe56e057f20f883e','陶夏子','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118499,'HMRobot118499','e10adc3949ba59abbe56e057f20f883e','邱星炜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118500,'HMRobot118500','e10adc3949ba59abbe56e057f20f883e','米爱夫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118501,'HMRobot118501','e10adc3949ba59abbe56e057f20f883e','杨峰梓','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118502,'HMRobot118502','e10adc3949ba59abbe56e057f20f883e','鲍诗虹','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118503,'HMRobot118503','e10adc3949ba59abbe56e057f20f883e','庞正君','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118504,'HMRobot118504','e10adc3949ba59abbe56e057f20f883e','彭瑜菊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118505,'HMRobot118505','e10adc3949ba59abbe56e057f20f883e','季石伯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118506,'HMRobot118506','e10adc3949ba59abbe56e057f20f883e','严江芝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118507,'HMRobot118507','e10adc3949ba59abbe56e057f20f883e','史梅越','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118508,'HMRobot118508','e10adc3949ba59abbe56e057f20f883e','梅显菲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118509,'HMRobot118509','e10adc3949ba59abbe56e057f20f883e','纪向继','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118510,'HMRobot118510','e10adc3949ba59abbe56e057f20f883e','金亦静','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118511,'HMRobot118511','e10adc3949ba59abbe56e057f20f883e','咎琳裕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118512,'HMRobot118512','e10adc3949ba59abbe56e057f20f883e','钱栋培','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118513,'HMRobot118513','e10adc3949ba59abbe56e057f20f883e','乐越震','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118514,'HMRobot118514','e10adc3949ba59abbe56e057f20f883e','齐康云','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118515,'HMRobot118515','e10adc3949ba59abbe56e057f20f883e','祝倩卓','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118516,'HMRobot118516','e10adc3949ba59abbe56e057f20f883e','费峰迪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118517,'HMRobot118517','e10adc3949ba59abbe56e057f20f883e','傅卓珊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118518,'HMRobot118518','e10adc3949ba59abbe56e057f20f883e','郑和福','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118519,'HMRobot118519','e10adc3949ba59abbe56e057f20f883e','周雯钧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118520,'HMRobot118520','e10adc3949ba59abbe56e057f20f883e','支如涵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118521,'HMRobot118521','e10adc3949ba59abbe56e057f20f883e','岑兴道','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118522,'HMRobot118522','e10adc3949ba59abbe56e057f20f883e','应智霖','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118523,'HMRobot118523','e10adc3949ba59abbe56e057f20f883e','毕瑜钧','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118524,'HMRobot118524','e10adc3949ba59abbe56e057f20f883e','汤震冠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118525,'HMRobot118525','e10adc3949ba59abbe56e057f20f883e','郝洁同','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118526,'HMRobot118526','e10adc3949ba59abbe56e057f20f883e','陶富航','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118527,'HMRobot118527','e10adc3949ba59abbe56e057f20f883e','娄岩莲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118528,'HMRobot118528','e10adc3949ba59abbe56e057f20f883e','元润连','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118529,'HMRobot118529','e10adc3949ba59abbe56e057f20f883e','汤有黎','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118530,'HMRobot118530','e10adc3949ba59abbe56e057f20f883e','安倩怀','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118531,'HMRobot118531','e10adc3949ba59abbe56e057f20f883e','吉嘉亮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118532,'HMRobot118532','e10adc3949ba59abbe56e057f20f883e','胡辰燕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118533,'HMRobot118533','e10adc3949ba59abbe56e057f20f883e','冯剑开','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118534,'HMRobot118534','e10adc3949ba59abbe56e057f20f883e','伏晋栋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118535,'HMRobot118535','e10adc3949ba59abbe56e057f20f883e','冯绍承','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118536,'HMRobot118536','e10adc3949ba59abbe56e057f20f883e','钟嘉雁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118537,'HMRobot118537','e10adc3949ba59abbe56e057f20f883e','左靖发','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118538,'HMRobot118538','e10adc3949ba59abbe56e057f20f883e','计绍紫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118539,'HMRobot118539','e10adc3949ba59abbe56e057f20f883e','江善辰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118540,'HMRobot118540','e10adc3949ba59abbe56e057f20f883e','郭雄岚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118541,'HMRobot118541','e10adc3949ba59abbe56e057f20f883e','姜依丽','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118542,'HMRobot118542','e10adc3949ba59abbe56e057f20f883e','吕如兴','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118543,'HMRobot118543','e10adc3949ba59abbe56e057f20f883e','于厚诗','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118544,'HMRobot118544','e10adc3949ba59abbe56e057f20f883e','张洋聪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118545,'HMRobot118545','e10adc3949ba59abbe56e057f20f883e','黄振宜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118546,'HMRobot118546','e10adc3949ba59abbe56e057f20f883e','昌逸年','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118547,'HMRobot118547','e10adc3949ba59abbe56e057f20f883e','娄羽胜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118548,'HMRobot118548','e10adc3949ba59abbe56e057f20f883e','江武菊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118549,'HMRobot118549','e10adc3949ba59abbe56e057f20f883e','潘波宇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118550,'HMRobot118550','e10adc3949ba59abbe56e057f20f883e','钱奎楚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118551,'HMRobot118551','e10adc3949ba59abbe56e057f20f883e','计枫传','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118552,'HMRobot118552','e10adc3949ba59abbe56e057f20f883e','茅锋森','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118553,'HMRobot118553','e10adc3949ba59abbe56e057f20f883e','季凌宏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118554,'HMRobot118554','e10adc3949ba59abbe56e057f20f883e','孙炳礼','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118555,'HMRobot118555','e10adc3949ba59abbe56e057f20f883e','严双进','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118556,'HMRobot118556','e10adc3949ba59abbe56e057f20f883e','娄博男','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118557,'HMRobot118557','e10adc3949ba59abbe56e057f20f883e','韦标山','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118558,'HMRobot118558','e10adc3949ba59abbe56e057f20f883e','凤镇一','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118559,'HMRobot118559','e10adc3949ba59abbe56e057f20f883e','阮远仪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118560,'HMRobot118560','e10adc3949ba59abbe56e057f20f883e','成仁奎','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118561,'HMRobot118561','e10adc3949ba59abbe56e057f20f883e','余军钰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118562,'HMRobot118562','e10adc3949ba59abbe56e057f20f883e','何娟睿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118563,'HMRobot118563','e10adc3949ba59abbe56e057f20f883e','鲁绍诗','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118564,'HMRobot118564','e10adc3949ba59abbe56e057f20f883e','纪景蕾','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118565,'HMRobot118565','e10adc3949ba59abbe56e057f20f883e','庞超钧','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118566,'HMRobot118566','e10adc3949ba59abbe56e057f20f883e','刁传泽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118567,'HMRobot118567','e10adc3949ba59abbe56e057f20f883e','徐钧铭','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118568,'HMRobot118568','e10adc3949ba59abbe56e057f20f883e','危晶刚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118569,'HMRobot118569','e10adc3949ba59abbe56e057f20f883e','云潮山','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118570,'HMRobot118570','e10adc3949ba59abbe56e057f20f883e','倪红蕾','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118571,'HMRobot118571','e10adc3949ba59abbe56e057f20f883e','秦岳钰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118572,'HMRobot118572','e10adc3949ba59abbe56e057f20f883e','滕行城','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118573,'HMRobot118573','e10adc3949ba59abbe56e057f20f883e','诸生锦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118574,'HMRobot118574','e10adc3949ba59abbe56e057f20f883e','金一满','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118575,'HMRobot118575','e10adc3949ba59abbe56e057f20f883e','童一祥','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118576,'HMRobot118576','e10adc3949ba59abbe56e057f20f883e','姜显麒','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118577,'HMRobot118577','e10adc3949ba59abbe56e057f20f883e','平凡延','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118578,'HMRobot118578','e10adc3949ba59abbe56e057f20f883e','吉芬曼','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118579,'HMRobot118579','e10adc3949ba59abbe56e057f20f883e','闵昊元','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118580,'HMRobot118580','e10adc3949ba59abbe56e057f20f883e','章年岚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118581,'HMRobot118581','e10adc3949ba59abbe56e057f20f883e','单水小','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118582,'HMRobot118582','e10adc3949ba59abbe56e057f20f883e','卢青申','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118583,'HMRobot118583','e10adc3949ba59abbe56e057f20f883e','卫勤祥','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118584,'HMRobot118584','e10adc3949ba59abbe56e057f20f883e','罗双鹤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118585,'HMRobot118585','e10adc3949ba59abbe56e057f20f883e','卢芳靖','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118586,'HMRobot118586','e10adc3949ba59abbe56e057f20f883e','蒋威洪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118587,'HMRobot118587','e10adc3949ba59abbe56e057f20f883e','禹城麟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118588,'HMRobot118588','e10adc3949ba59abbe56e057f20f883e','应祖秀','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118589,'HMRobot118589','e10adc3949ba59abbe56e057f20f883e','谈庭萍','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118590,'HMRobot118590','e10adc3949ba59abbe56e057f20f883e','吉成善','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118591,'HMRobot118591','e10adc3949ba59abbe56e057f20f883e','明书申','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118592,'HMRobot118592','e10adc3949ba59abbe56e057f20f883e','罗奇行','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118593,'HMRobot118593','e10adc3949ba59abbe56e057f20f883e','霍涛福','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118594,'HMRobot118594','e10adc3949ba59abbe56e057f20f883e','龚迪萍','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118595,'HMRobot118595','e10adc3949ba59abbe56e057f20f883e','花心有','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118596,'HMRobot118596','e10adc3949ba59abbe56e057f20f883e','解力仲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118597,'HMRobot118597','e10adc3949ba59abbe56e057f20f883e','任城飞','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118598,'HMRobot118598','e10adc3949ba59abbe56e057f20f883e','钟石仲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118599,'HMRobot118599','e10adc3949ba59abbe56e057f20f883e','宣雁岩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118600,'HMRobot118600','e10adc3949ba59abbe56e057f20f883e','龚廷豪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118601,'HMRobot118601','e10adc3949ba59abbe56e057f20f883e','席家冬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118602,'HMRobot118602','e10adc3949ba59abbe56e057f20f883e','花家伊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118603,'HMRobot118603','e10adc3949ba59abbe56e057f20f883e','唐迪菲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118604,'HMRobot118604','e10adc3949ba59abbe56e057f20f883e','柏跃航','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118605,'HMRobot118605','e10adc3949ba59abbe56e057f20f883e','鲁勋喜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118606,'HMRobot118606','e10adc3949ba59abbe56e057f20f883e','贺君有','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118607,'HMRobot118607','e10adc3949ba59abbe56e057f20f883e','岑勤长','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118608,'HMRobot118608','e10adc3949ba59abbe56e057f20f883e','秦辰爱','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118609,'HMRobot118609','e10adc3949ba59abbe56e057f20f883e','单军霖','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118610,'HMRobot118610','e10adc3949ba59abbe56e057f20f883e','郁磊和','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118611,'HMRobot118611','e10adc3949ba59abbe56e057f20f883e','蔡夫万','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118612,'HMRobot118612','e10adc3949ba59abbe56e057f20f883e','毕莲铁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118613,'HMRobot118613','e10adc3949ba59abbe56e057f20f883e','解浩武','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118614,'HMRobot118614','e10adc3949ba59abbe56e057f20f883e','茅慧小','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118615,'HMRobot118615','e10adc3949ba59abbe56e057f20f883e','卫立奎','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118616,'HMRobot118616','e10adc3949ba59abbe56e057f20f883e','奚爽孝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118617,'HMRobot118617','e10adc3949ba59abbe56e057f20f883e','包显继','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118618,'HMRobot118618','e10adc3949ba59abbe56e057f20f883e','米珍中','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118619,'HMRobot118619','e10adc3949ba59abbe56e057f20f883e','孟青靖','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118620,'HMRobot118620','e10adc3949ba59abbe56e057f20f883e','郭佩群','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118621,'HMRobot118621','e10adc3949ba59abbe56e057f20f883e','强宝贵','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118622,'HMRobot118622','e10adc3949ba59abbe56e057f20f883e','穆全朝','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118623,'HMRobot118623','e10adc3949ba59abbe56e057f20f883e','茅圣石','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118624,'HMRobot118624','e10adc3949ba59abbe56e057f20f883e','谈培保','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118625,'HMRobot118625','e10adc3949ba59abbe56e057f20f883e','卫晨梅','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118626,'HMRobot118626','e10adc3949ba59abbe56e057f20f883e','范贵亮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118627,'HMRobot118627','e10adc3949ba59abbe56e057f20f883e','滕腾儿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118628,'HMRobot118628','e10adc3949ba59abbe56e057f20f883e','周浩真','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118629,'HMRobot118629','e10adc3949ba59abbe56e057f20f883e','樊磊朝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118630,'HMRobot118630','e10adc3949ba59abbe56e057f20f883e','粱菲湘','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118631,'HMRobot118631','e10adc3949ba59abbe56e057f20f883e','王彪承','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118632,'HMRobot118632','e10adc3949ba59abbe56e057f20f883e','喻宗坤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118633,'HMRobot118633','e10adc3949ba59abbe56e057f20f883e','王爽奇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118634,'HMRobot118634','e10adc3949ba59abbe56e057f20f883e','莫炳燕','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118635,'HMRobot118635','e10adc3949ba59abbe56e057f20f883e','华城鲁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118636,'HMRobot118636','e10adc3949ba59abbe56e057f20f883e','杨羽振','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118637,'HMRobot118637','e10adc3949ba59abbe56e057f20f883e','毕声冰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118638,'HMRobot118638','e10adc3949ba59abbe56e057f20f883e','宋行宜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118639,'HMRobot118639','e10adc3949ba59abbe56e057f20f883e','齐满开','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118640,'HMRobot118640','e10adc3949ba59abbe56e057f20f883e','马松宏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118641,'HMRobot118641','e10adc3949ba59abbe56e057f20f883e','汤翔雨','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118642,'HMRobot118642','e10adc3949ba59abbe56e057f20f883e','支克冠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118643,'HMRobot118643','e10adc3949ba59abbe56e057f20f883e','盛慧枫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118644,'HMRobot118644','e10adc3949ba59abbe56e057f20f883e','宗沛岚','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118645,'HMRobot118645','e10adc3949ba59abbe56e057f20f883e','粱高紫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118646,'HMRobot118646','e10adc3949ba59abbe56e057f20f883e','臧福裕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118647,'HMRobot118647','e10adc3949ba59abbe56e057f20f883e','麻水扬','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118648,'HMRobot118648','e10adc3949ba59abbe56e057f20f883e','顾达青','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118649,'HMRobot118649','e10adc3949ba59abbe56e057f20f883e','颜生珊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118650,'HMRobot118650','e10adc3949ba59abbe56e057f20f883e','平可宁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118651,'HMRobot118651','e10adc3949ba59abbe56e057f20f883e','任铭慧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118652,'HMRobot118652','e10adc3949ba59abbe56e057f20f883e','施芳蕾','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118653,'HMRobot118653','e10adc3949ba59abbe56e057f20f883e','岑淑满','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118654,'HMRobot118654','e10adc3949ba59abbe56e057f20f883e','左长达','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118655,'HMRobot118655','e10adc3949ba59abbe56e057f20f883e','陈灿福','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118656,'HMRobot118656','e10adc3949ba59abbe56e057f20f883e','高滨鲁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118657,'HMRobot118657','e10adc3949ba59abbe56e057f20f883e','穆善若','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118658,'HMRobot118658','e10adc3949ba59abbe56e057f20f883e','许媛勤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118659,'HMRobot118659','e10adc3949ba59abbe56e057f20f883e','凤书道','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118660,'HMRobot118660','e10adc3949ba59abbe56e057f20f883e','凌辰麒','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118661,'HMRobot118661','e10adc3949ba59abbe56e057f20f883e','解书征','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118662,'HMRobot118662','e10adc3949ba59abbe56e057f20f883e','禹伟树','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118663,'HMRobot118663','e10adc3949ba59abbe56e057f20f883e','尤克彤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118664,'HMRobot118664','e10adc3949ba59abbe56e057f20f883e','石月坤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118665,'HMRobot118665','e10adc3949ba59abbe56e057f20f883e','宋奎倩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118666,'HMRobot118666','e10adc3949ba59abbe56e057f20f883e','汪邦景','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118667,'HMRobot118667','e10adc3949ba59abbe56e057f20f883e','康家全','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118668,'HMRobot118668','e10adc3949ba59abbe56e057f20f883e','虞方豪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118669,'HMRobot118669','e10adc3949ba59abbe56e057f20f883e','徐逸冬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118670,'HMRobot118670','e10adc3949ba59abbe56e057f20f883e','丁星菲','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118671,'HMRobot118671','e10adc3949ba59abbe56e057f20f883e','汪伊红','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118672,'HMRobot118672','e10adc3949ba59abbe56e057f20f883e','戚锋寿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118673,'HMRobot118673','e10adc3949ba59abbe56e057f20f883e','岑梅勤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118674,'HMRobot118674','e10adc3949ba59abbe56e057f20f883e','时乐非','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118675,'HMRobot118675','e10adc3949ba59abbe56e057f20f883e','汤鸣新','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118676,'HMRobot118676','e10adc3949ba59abbe56e057f20f883e','常寿泰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118677,'HMRobot118677','e10adc3949ba59abbe56e057f20f883e','何立化','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118678,'HMRobot118678','e10adc3949ba59abbe56e057f20f883e','姚坤启','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118679,'HMRobot118679','e10adc3949ba59abbe56e057f20f883e','左宝怀','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118680,'HMRobot118680','e10adc3949ba59abbe56e057f20f883e','计子思','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118681,'HMRobot118681','e10adc3949ba59abbe56e057f20f883e','宋伦怡','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118682,'HMRobot118682','e10adc3949ba59abbe56e057f20f883e','韦瑜琴','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118683,'HMRobot118683','e10adc3949ba59abbe56e057f20f883e','皮凡方','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118684,'HMRobot118684','e10adc3949ba59abbe56e057f20f883e','粱倩丹','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118685,'HMRobot118685','e10adc3949ba59abbe56e057f20f883e','章裕坚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118686,'HMRobot118686','e10adc3949ba59abbe56e057f20f883e','董鸣炜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118687,'HMRobot118687','e10adc3949ba59abbe56e057f20f883e','柏平帆','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118688,'HMRobot118688','e10adc3949ba59abbe56e057f20f883e','康迪满','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118689,'HMRobot118689','e10adc3949ba59abbe56e057f20f883e','滕超元','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118690,'HMRobot118690','e10adc3949ba59abbe56e057f20f883e','薛一阳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118691,'HMRobot118691','e10adc3949ba59abbe56e057f20f883e','郁海仲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118692,'HMRobot118692','e10adc3949ba59abbe56e057f20f883e','傅翔峰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118693,'HMRobot118693','e10adc3949ba59abbe56e057f20f883e','宣安兰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118694,'HMRobot118694','e10adc3949ba59abbe56e057f20f883e','裘棋晋','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118695,'HMRobot118695','e10adc3949ba59abbe56e057f20f883e','倪豪伟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118696,'HMRobot118696','e10adc3949ba59abbe56e057f20f883e','蔡儿冠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118697,'HMRobot118697','e10adc3949ba59abbe56e057f20f883e','于颖有','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118698,'HMRobot118698','e10adc3949ba59abbe56e057f20f883e','纪耀帆','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118699,'HMRobot118699','e10adc3949ba59abbe56e057f20f883e','韦善思','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118700,'HMRobot118700','e10adc3949ba59abbe56e057f20f883e','高威琪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118701,'HMRobot118701','e10adc3949ba59abbe56e057f20f883e','臧权水','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118702,'HMRobot118702','e10adc3949ba59abbe56e057f20f883e','包哲满','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118703,'HMRobot118703','e10adc3949ba59abbe56e057f20f883e','苗沛静','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118704,'HMRobot118704','e10adc3949ba59abbe56e057f20f883e','奚京会','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118705,'HMRobot118705','e10adc3949ba59abbe56e057f20f883e','冯达满','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118706,'HMRobot118706','e10adc3949ba59abbe56e057f20f883e','史莲媛','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118707,'HMRobot118707','e10adc3949ba59abbe56e057f20f883e','石芝洁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118708,'HMRobot118708','e10adc3949ba59abbe56e057f20f883e','方凌进','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118709,'HMRobot118709','e10adc3949ba59abbe56e057f20f883e','冯东妍','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118710,'HMRobot118710','e10adc3949ba59abbe56e057f20f883e','邱海扬','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118711,'HMRobot118711','e10adc3949ba59abbe56e057f20f883e','危彦城','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118712,'HMRobot118712','e10adc3949ba59abbe56e057f20f883e','单雯冬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118713,'HMRobot118713','e10adc3949ba59abbe56e057f20f883e','吴晶鸿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118714,'HMRobot118714','e10adc3949ba59abbe56e057f20f883e','郭珠佳','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118715,'HMRobot118715','e10adc3949ba59abbe56e057f20f883e','戚靖珠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118716,'HMRobot118716','e10adc3949ba59abbe56e057f20f883e','臧仁丹','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118717,'HMRobot118717','e10adc3949ba59abbe56e057f20f883e','梅鑫才','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118718,'HMRobot118718','e10adc3949ba59abbe56e057f20f883e','徐怡梅','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118719,'HMRobot118719','e10adc3949ba59abbe56e057f20f883e','曹怡晶','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118720,'HMRobot118720','e10adc3949ba59abbe56e057f20f883e','徐礼宏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118721,'HMRobot118721','e10adc3949ba59abbe56e057f20f883e','冯睿云','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118722,'HMRobot118722','e10adc3949ba59abbe56e057f20f883e','明冰卫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118723,'HMRobot118723','e10adc3949ba59abbe56e057f20f883e','左和昌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118724,'HMRobot118724','e10adc3949ba59abbe56e057f20f883e','石飞羽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118725,'HMRobot118725','e10adc3949ba59abbe56e057f20f883e','诸洋政','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118726,'HMRobot118726','e10adc3949ba59abbe56e057f20f883e','丁孝菁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118727,'HMRobot118727','e10adc3949ba59abbe56e057f20f883e','章力佳','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118728,'HMRobot118728','e10adc3949ba59abbe56e057f20f883e','梅谦靖','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118729,'HMRobot118729','e10adc3949ba59abbe56e057f20f883e','杜传树','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118730,'HMRobot118730','e10adc3949ba59abbe56e057f20f883e','花成宜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118731,'HMRobot118731','e10adc3949ba59abbe56e057f20f883e','郑全青','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118732,'HMRobot118732','e10adc3949ba59abbe56e057f20f883e','盛少青','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118733,'HMRobot118733','e10adc3949ba59abbe56e057f20f883e','支申嘉','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118734,'HMRobot118734','e10adc3949ba59abbe56e057f20f883e','毕兆城','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118735,'HMRobot118735','e10adc3949ba59abbe56e057f20f883e','郁静凤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118736,'HMRobot118736','e10adc3949ba59abbe56e057f20f883e','毛晋毅','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118737,'HMRobot118737','e10adc3949ba59abbe56e057f20f883e','茅美芳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118738,'HMRobot118738','e10adc3949ba59abbe56e057f20f883e','强家久','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118739,'HMRobot118739','e10adc3949ba59abbe56e057f20f883e','赵芝石','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118740,'HMRobot118740','e10adc3949ba59abbe56e057f20f883e','丁明昌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118741,'HMRobot118741','e10adc3949ba59abbe56e057f20f883e','凤显万','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118742,'HMRobot118742','e10adc3949ba59abbe56e057f20f883e','梅水枫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118743,'HMRobot118743','e10adc3949ba59abbe56e057f20f883e','路双百','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118744,'HMRobot118744','e10adc3949ba59abbe56e057f20f883e','郑辉滨','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118745,'HMRobot118745','e10adc3949ba59abbe56e057f20f883e','时喜士','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118746,'HMRobot118746','e10adc3949ba59abbe56e057f20f883e','纪根正','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118747,'HMRobot118747','e10adc3949ba59abbe56e057f20f883e','谢昊水','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118748,'HMRobot118748','e10adc3949ba59abbe56e057f20f883e','房剑亦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118749,'HMRobot118749','e10adc3949ba59abbe56e057f20f883e','吴庭凤','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118750,'HMRobot118750','e10adc3949ba59abbe56e057f20f883e','臧岚新','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118751,'HMRobot118751','e10adc3949ba59abbe56e057f20f883e','万奎威','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118752,'HMRobot118752','e10adc3949ba59abbe56e057f20f883e','崔波丹','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118753,'HMRobot118753','e10adc3949ba59abbe56e057f20f883e','纪彤日','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118754,'HMRobot118754','e10adc3949ba59abbe56e057f20f883e','方力安','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118755,'HMRobot118755','e10adc3949ba59abbe56e057f20f883e','邬佩德','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118756,'HMRobot118756','e10adc3949ba59abbe56e057f20f883e','樊其根','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118757,'HMRobot118757','e10adc3949ba59abbe56e057f20f883e','马家志','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118758,'HMRobot118758','e10adc3949ba59abbe56e057f20f883e','计真显','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118759,'HMRobot118759','e10adc3949ba59abbe56e057f20f883e','汪邦有','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118760,'HMRobot118760','e10adc3949ba59abbe56e057f20f883e','时正福','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118761,'HMRobot118761','e10adc3949ba59abbe56e057f20f883e','吕源廷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118762,'HMRobot118762','e10adc3949ba59abbe56e057f20f883e','鲍峰灿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118763,'HMRobot118763','e10adc3949ba59abbe56e057f20f883e','江世芳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118764,'HMRobot118764','e10adc3949ba59abbe56e057f20f883e','应刚亮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118765,'HMRobot118765','e10adc3949ba59abbe56e057f20f883e','包运丹','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118766,'HMRobot118766','e10adc3949ba59abbe56e057f20f883e','元棋正','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118767,'HMRobot118767','e10adc3949ba59abbe56e057f20f883e','阮成月','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118768,'HMRobot118768','e10adc3949ba59abbe56e057f20f883e','彭华翔','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118769,'HMRobot118769','e10adc3949ba59abbe56e057f20f883e','康力焕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118770,'HMRobot118770','e10adc3949ba59abbe56e057f20f883e','吉志飞','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118771,'HMRobot118771','e10adc3949ba59abbe56e057f20f883e','汪彦荣','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118772,'HMRobot118772','e10adc3949ba59abbe56e057f20f883e','贾剑惠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118773,'HMRobot118773','e10adc3949ba59abbe56e057f20f883e','狄定虹','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118774,'HMRobot118774','e10adc3949ba59abbe56e057f20f883e','郝茜银','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118775,'HMRobot118775','e10adc3949ba59abbe56e057f20f883e','奚传夏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118776,'HMRobot118776','e10adc3949ba59abbe56e057f20f883e','胡宜强','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118777,'HMRobot118777','e10adc3949ba59abbe56e057f20f883e','时宁群','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118778,'HMRobot118778','e10adc3949ba59abbe56e057f20f883e','卜玲坚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118779,'HMRobot118779','e10adc3949ba59abbe56e057f20f883e','贝东高','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118780,'HMRobot118780','e10adc3949ba59abbe56e057f20f883e','莫贤芳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118781,'HMRobot118781','e10adc3949ba59abbe56e057f20f883e','项菁凡','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118782,'HMRobot118782','e10adc3949ba59abbe56e057f20f883e','黄嘉孟','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118783,'HMRobot118783','e10adc3949ba59abbe56e057f20f883e','水翔雅','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118784,'HMRobot118784','e10adc3949ba59abbe56e057f20f883e','祁忠百','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118785,'HMRobot118785','e10adc3949ba59abbe56e057f20f883e','魏楚虎','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118786,'HMRobot118786','e10adc3949ba59abbe56e057f20f883e','项勇恩','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118787,'HMRobot118787','e10adc3949ba59abbe56e057f20f883e','单凡继','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118788,'HMRobot118788','e10adc3949ba59abbe56e057f20f883e','喻金会','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118789,'HMRobot118789','e10adc3949ba59abbe56e057f20f883e','鲍洲日','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118790,'HMRobot118790','e10adc3949ba59abbe56e057f20f883e','咎跃裕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118791,'HMRobot118791','e10adc3949ba59abbe56e057f20f883e','章仲真','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118792,'HMRobot118792','e10adc3949ba59abbe56e057f20f883e','平乃凌','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118793,'HMRobot118793','e10adc3949ba59abbe56e057f20f883e','高伟桂','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118794,'HMRobot118794','e10adc3949ba59abbe56e057f20f883e','元锦丹','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118795,'HMRobot118795','e10adc3949ba59abbe56e057f20f883e','岑旭慧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118796,'HMRobot118796','e10adc3949ba59abbe56e057f20f883e','王正武','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118797,'HMRobot118797','e10adc3949ba59abbe56e057f20f883e','左波然','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118798,'HMRobot118798','e10adc3949ba59abbe56e057f20f883e','江峰洁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118799,'HMRobot118799','e10adc3949ba59abbe56e057f20f883e','尹泰臣','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118800,'HMRobot118800','e10adc3949ba59abbe56e057f20f883e','邵若玲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118801,'HMRobot118801','e10adc3949ba59abbe56e057f20f883e','窦斌坚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118802,'HMRobot118802','e10adc3949ba59abbe56e057f20f883e','莫发生','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118803,'HMRobot118803','e10adc3949ba59abbe56e057f20f883e','狄玮美','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118804,'HMRobot118804','e10adc3949ba59abbe56e057f20f883e','水佳有','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118805,'HMRobot118805','e10adc3949ba59abbe56e057f20f883e','项刚美','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118806,'HMRobot118806','e10adc3949ba59abbe56e057f20f883e','胡钢嘉','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118807,'HMRobot118807','e10adc3949ba59abbe56e057f20f883e','皮鲁孟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118808,'HMRobot118808','e10adc3949ba59abbe56e057f20f883e','许先久','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118809,'HMRobot118809','e10adc3949ba59abbe56e057f20f883e','朱行欣','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118810,'HMRobot118810','e10adc3949ba59abbe56e057f20f883e','薛骏化','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118811,'HMRobot118811','e10adc3949ba59abbe56e057f20f883e','韩发信','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118812,'HMRobot118812','e10adc3949ba59abbe56e057f20f883e','姚行龙','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118813,'HMRobot118813','e10adc3949ba59abbe56e057f20f883e','夏帆剑','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118814,'HMRobot118814','e10adc3949ba59abbe56e057f20f883e','孙冬夏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118815,'HMRobot118815','e10adc3949ba59abbe56e057f20f883e','常晶云','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118816,'HMRobot118816','e10adc3949ba59abbe56e057f20f883e','宗维梓','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118817,'HMRobot118817','e10adc3949ba59abbe56e057f20f883e','周树亮','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118818,'HMRobot118818','e10adc3949ba59abbe56e057f20f883e','魏帆永','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118819,'HMRobot118819','e10adc3949ba59abbe56e057f20f883e','贾凡明','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118820,'HMRobot118820','e10adc3949ba59abbe56e057f20f883e','路城怡','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118821,'HMRobot118821','e10adc3949ba59abbe56e057f20f883e','卜先潮','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118822,'HMRobot118822','e10adc3949ba59abbe56e057f20f883e','余洁纯','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118823,'HMRobot118823','e10adc3949ba59abbe56e057f20f883e','昌廷逸','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118824,'HMRobot118824','e10adc3949ba59abbe56e057f20f883e','祝顺汉','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118825,'HMRobot118825','e10adc3949ba59abbe56e057f20f883e','邬凤明','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118826,'HMRobot118826','e10adc3949ba59abbe56e057f20f883e','蒋兵川','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118827,'HMRobot118827','e10adc3949ba59abbe56e057f20f883e','葛乃树','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118828,'HMRobot118828','e10adc3949ba59abbe56e057f20f883e','闵汉枫','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118829,'HMRobot118829','e10adc3949ba59abbe56e057f20f883e','屈润华','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118830,'HMRobot118830','e10adc3949ba59abbe56e057f20f883e','卢伦耀','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118831,'HMRobot118831','e10adc3949ba59abbe56e057f20f883e','殷如鸿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118832,'HMRobot118832','e10adc3949ba59abbe56e057f20f883e','袁彤捷','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118833,'HMRobot118833','e10adc3949ba59abbe56e057f20f883e','喻诗兴','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118834,'HMRobot118834','e10adc3949ba59abbe56e057f20f883e','蓝泽尧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118835,'HMRobot118835','e10adc3949ba59abbe56e057f20f883e','伏良飞','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118836,'HMRobot118836','e10adc3949ba59abbe56e057f20f883e','诸萍培','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118837,'HMRobot118837','e10adc3949ba59abbe56e057f20f883e','堪传辰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118838,'HMRobot118838','e10adc3949ba59abbe56e057f20f883e','任伟斌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118839,'HMRobot118839','e10adc3949ba59abbe56e057f20f883e','霍鹤凌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118840,'HMRobot118840','e10adc3949ba59abbe56e057f20f883e','殷伊定','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118841,'HMRobot118841','e10adc3949ba59abbe56e057f20f883e','康英儿','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118842,'HMRobot118842','e10adc3949ba59abbe56e057f20f883e','朱保立','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118843,'HMRobot118843','e10adc3949ba59abbe56e057f20f883e','顾岩彤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118844,'HMRobot118844','e10adc3949ba59abbe56e057f20f883e','尤骏开','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118845,'HMRobot118845','e10adc3949ba59abbe56e057f20f883e','应开浩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118846,'HMRobot118846','e10adc3949ba59abbe56e057f20f883e','蔡芳爽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118847,'HMRobot118847','e10adc3949ba59abbe56e057f20f883e','郑田崇','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118848,'HMRobot118848','e10adc3949ba59abbe56e057f20f883e','李运勤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118849,'HMRobot118849','e10adc3949ba59abbe56e057f20f883e','纪嘉峰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118850,'HMRobot118850','e10adc3949ba59abbe56e057f20f883e','姜麟乐','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118851,'HMRobot118851','e10adc3949ba59abbe56e057f20f883e','朱水寿','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118852,'HMRobot118852','e10adc3949ba59abbe56e057f20f883e','麻咏贵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118853,'HMRobot118853','e10adc3949ba59abbe56e057f20f883e','危湘裕','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118854,'HMRobot118854','e10adc3949ba59abbe56e057f20f883e','郎勋邦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118855,'HMRobot118855','e10adc3949ba59abbe56e057f20f883e','齐灵蕾','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118856,'HMRobot118856','e10adc3949ba59abbe56e057f20f883e','魏蕾昌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118857,'HMRobot118857','e10adc3949ba59abbe56e057f20f883e','皮培岚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118858,'HMRobot118858','e10adc3949ba59abbe56e057f20f883e','萧纯洲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118859,'HMRobot118859','e10adc3949ba59abbe56e057f20f883e','霍梓楠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118860,'HMRobot118860','e10adc3949ba59abbe56e057f20f883e','管虎慧','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118861,'HMRobot118861','e10adc3949ba59abbe56e057f20f883e','强虎怡','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118862,'HMRobot118862','e10adc3949ba59abbe56e057f20f883e','尤萍骏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118863,'HMRobot118863','e10adc3949ba59abbe56e057f20f883e','干哲夏','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118864,'HMRobot118864','e10adc3949ba59abbe56e057f20f883e','潘露涵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118865,'HMRobot118865','e10adc3949ba59abbe56e057f20f883e','阮萌化','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118866,'HMRobot118866','e10adc3949ba59abbe56e057f20f883e','应虹日','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118867,'HMRobot118867','e10adc3949ba59abbe56e057f20f883e','邬仪潮','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118868,'HMRobot118868','e10adc3949ba59abbe56e057f20f883e','崔俊娟','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118869,'HMRobot118869','e10adc3949ba59abbe56e057f20f883e','常瑞炜','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118870,'HMRobot118870','e10adc3949ba59abbe56e057f20f883e','苏成涛','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118871,'HMRobot118871','e10adc3949ba59abbe56e057f20f883e','许庆雪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118872,'HMRobot118872','e10adc3949ba59abbe56e057f20f883e','方喜志','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118873,'HMRobot118873','e10adc3949ba59abbe56e057f20f883e','童延永','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118874,'HMRobot118874','e10adc3949ba59abbe56e057f20f883e','韦斌宝','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118875,'HMRobot118875','e10adc3949ba59abbe56e057f20f883e','诸田颖','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118876,'HMRobot118876','e10adc3949ba59abbe56e057f20f883e','韩天桐','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118877,'HMRobot118877','e10adc3949ba59abbe56e057f20f883e','吕兆柏','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118878,'HMRobot118878','e10adc3949ba59abbe56e057f20f883e','戚连平','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118879,'HMRobot118879','e10adc3949ba59abbe56e057f20f883e','毛可雯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118880,'HMRobot118880','e10adc3949ba59abbe56e057f20f883e','齐连龙','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118881,'HMRobot118881','e10adc3949ba59abbe56e057f20f883e','鲍鹏鹤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118882,'HMRobot118882','e10adc3949ba59abbe56e057f20f883e','骆洪琴','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118883,'HMRobot118883','e10adc3949ba59abbe56e057f20f883e','黄靖素','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118884,'HMRobot118884','e10adc3949ba59abbe56e057f20f883e','纪雨洋','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118885,'HMRobot118885','e10adc3949ba59abbe56e057f20f883e','华红鹤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118886,'HMRobot118886','e10adc3949ba59abbe56e057f20f883e','姚燕妮','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118887,'HMRobot118887','e10adc3949ba59abbe56e057f20f883e','田亮强','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118888,'HMRobot118888','e10adc3949ba59abbe56e057f20f883e','支德礼','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118889,'HMRobot118889','e10adc3949ba59abbe56e057f20f883e','吴红菊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118890,'HMRobot118890','e10adc3949ba59abbe56e057f20f883e','方连勤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118891,'HMRobot118891','e10adc3949ba59abbe56e057f20f883e','童艺磊','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118892,'HMRobot118892','e10adc3949ba59abbe56e057f20f883e','卫丽士','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118893,'HMRobot118893','e10adc3949ba59abbe56e057f20f883e','尤越政','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118894,'HMRobot118894','e10adc3949ba59abbe56e057f20f883e','花梓珍','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118895,'HMRobot118895','e10adc3949ba59abbe56e057f20f883e','朱怀纯','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118896,'HMRobot118896','e10adc3949ba59abbe56e057f20f883e','廉爱力','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118897,'HMRobot118897','e10adc3949ba59abbe56e057f20f883e','廉钧孟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118898,'HMRobot118898','e10adc3949ba59abbe56e057f20f883e','马凤信','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118899,'HMRobot118899','e10adc3949ba59abbe56e057f20f883e','邹琳逸','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118900,'HMRobot118900','e10adc3949ba59abbe56e057f20f883e','章玉传','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118901,'HMRobot118901','e10adc3949ba59abbe56e057f20f883e','裘士继','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118902,'HMRobot118902','e10adc3949ba59abbe56e057f20f883e','席其铁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118903,'HMRobot118903','e10adc3949ba59abbe56e057f20f883e','姜锡洪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118904,'HMRobot118904','e10adc3949ba59abbe56e057f20f883e','刁西明','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118905,'HMRobot118905','e10adc3949ba59abbe56e057f20f883e','李大慧','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118906,'HMRobot118906','e10adc3949ba59abbe56e057f20f883e','舒金行','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118907,'HMRobot118907','e10adc3949ba59abbe56e057f20f883e','臧淇孟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118908,'HMRobot118908','e10adc3949ba59abbe56e057f20f883e','彭洲珠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118909,'HMRobot118909','e10adc3949ba59abbe56e057f20f883e','王博梦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118910,'HMRobot118910','e10adc3949ba59abbe56e057f20f883e','禹男辰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118911,'HMRobot118911','e10adc3949ba59abbe56e057f20f883e','元灿非','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118912,'HMRobot118912','e10adc3949ba59abbe56e057f20f883e','萧群子','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118913,'HMRobot118913','e10adc3949ba59abbe56e057f20f883e','祁泽华','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118914,'HMRobot118914','e10adc3949ba59abbe56e057f20f883e','屈山达','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118915,'HMRobot118915','e10adc3949ba59abbe56e057f20f883e','王喜迪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118916,'HMRobot118916','e10adc3949ba59abbe56e057f20f883e','明发珠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118917,'HMRobot118917','e10adc3949ba59abbe56e057f20f883e','诸廷铭','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118918,'HMRobot118918','e10adc3949ba59abbe56e057f20f883e','万川如','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118919,'HMRobot118919','e10adc3949ba59abbe56e057f20f883e','尤树淇','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118920,'HMRobot118920','e10adc3949ba59abbe56e057f20f883e','孙基雷','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118921,'HMRobot118921','e10adc3949ba59abbe56e057f20f883e','云宇岩','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118922,'HMRobot118922','e10adc3949ba59abbe56e057f20f883e','管卓岳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118923,'HMRobot118923','e10adc3949ba59abbe56e057f20f883e','伏宇虹','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118924,'HMRobot118924','e10adc3949ba59abbe56e057f20f883e','曹化日','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118925,'HMRobot118925','e10adc3949ba59abbe56e057f20f883e','戴蕾雯','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118926,'HMRobot118926','e10adc3949ba59abbe56e057f20f883e','盛光依','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118927,'HMRobot118927','e10adc3949ba59abbe56e057f20f883e','岑大升','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118928,'HMRobot118928','e10adc3949ba59abbe56e057f20f883e','谈珊扬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118929,'HMRobot118929','e10adc3949ba59abbe56e057f20f883e','诸莲朝','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118930,'HMRobot118930','e10adc3949ba59abbe56e057f20f883e','郑冬佩','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118931,'HMRobot118931','e10adc3949ba59abbe56e057f20f883e','张谦潮','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118932,'HMRobot118932','e10adc3949ba59abbe56e057f20f883e','朱艺根','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118933,'HMRobot118933','e10adc3949ba59abbe56e057f20f883e','徐雁义','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118934,'HMRobot118934','e10adc3949ba59abbe56e057f20f883e','金茜高','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118935,'HMRobot118935','e10adc3949ba59abbe56e057f20f883e','王光杰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118936,'HMRobot118936','e10adc3949ba59abbe56e057f20f883e','孔帆飞','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118937,'HMRobot118937','e10adc3949ba59abbe56e057f20f883e','尤安万','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118938,'HMRobot118938','e10adc3949ba59abbe56e057f20f883e','凤霖雄','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118939,'HMRobot118939','e10adc3949ba59abbe56e057f20f883e','崔新怡','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118940,'HMRobot118940','e10adc3949ba59abbe56e057f20f883e','蔡培静','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118941,'HMRobot118941','e10adc3949ba59abbe56e057f20f883e','韩久宗','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118942,'HMRobot118942','e10adc3949ba59abbe56e057f20f883e','奚惠峰','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118943,'HMRobot118943','e10adc3949ba59abbe56e057f20f883e','席碧同','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118944,'HMRobot118944','e10adc3949ba59abbe56e057f20f883e','王秀海','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118945,'HMRobot118945','e10adc3949ba59abbe56e057f20f883e','庞一进','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118946,'HMRobot118946','e10adc3949ba59abbe56e057f20f883e','支林显','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118947,'HMRobot118947','e10adc3949ba59abbe56e057f20f883e','沈道明','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118948,'HMRobot118948','e10adc3949ba59abbe56e057f20f883e','蓝根素','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118949,'HMRobot118949','e10adc3949ba59abbe56e057f20f883e','穆可杰','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118950,'HMRobot118950','e10adc3949ba59abbe56e057f20f883e','秦雷彦','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118951,'HMRobot118951','e10adc3949ba59abbe56e057f20f883e','高诗启','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118952,'HMRobot118952','e10adc3949ba59abbe56e057f20f883e','顾心铁','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118953,'HMRobot118953','e10adc3949ba59abbe56e057f20f883e','杭毅长','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118954,'HMRobot118954','e10adc3949ba59abbe56e057f20f883e','田柏聪','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118955,'HMRobot118955','e10adc3949ba59abbe56e057f20f883e','祝琪惠','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118956,'HMRobot118956','e10adc3949ba59abbe56e057f20f883e','马阳栋','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118957,'HMRobot118957','e10adc3949ba59abbe56e057f20f883e','霍坚宜','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118958,'HMRobot118958','e10adc3949ba59abbe56e057f20f883e','屈钢冬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118959,'HMRobot118959','e10adc3949ba59abbe56e057f20f883e','姜申维','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118960,'HMRobot118960','e10adc3949ba59abbe56e057f20f883e','董连申','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118961,'HMRobot118961','e10adc3949ba59abbe56e057f20f883e','彭雪宁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118962,'HMRobot118962','e10adc3949ba59abbe56e057f20f883e','梅劲阳','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118963,'HMRobot118963','e10adc3949ba59abbe56e057f20f883e','茅向雷','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118964,'HMRobot118964','e10adc3949ba59abbe56e057f20f883e','酆化卫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118965,'HMRobot118965','e10adc3949ba59abbe56e057f20f883e','凌瑜乐','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118966,'HMRobot118966','e10adc3949ba59abbe56e057f20f883e','房楚立','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118967,'HMRobot118967','e10adc3949ba59abbe56e057f20f883e','熊裕东','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118968,'HMRobot118968','e10adc3949ba59abbe56e057f20f883e','薛雁洲','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118969,'HMRobot118969','e10adc3949ba59abbe56e057f20f883e','萧仪启','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118970,'HMRobot118970','e10adc3949ba59abbe56e057f20f883e','罗浩之','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118971,'HMRobot118971','e10adc3949ba59abbe56e057f20f883e','唐万勤','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118972,'HMRobot118972','e10adc3949ba59abbe56e057f20f883e','昌升江','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118973,'HMRobot118973','e10adc3949ba59abbe56e057f20f883e','水非彬','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118974,'HMRobot118974','e10adc3949ba59abbe56e057f20f883e','夏麒娟','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118975,'HMRobot118975','e10adc3949ba59abbe56e057f20f883e','邓圣裕','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118976,'HMRobot118976','e10adc3949ba59abbe56e057f20f883e','房圣雁','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118977,'HMRobot118977','e10adc3949ba59abbe56e057f20f883e','计奎民','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118978,'HMRobot118978','e10adc3949ba59abbe56e057f20f883e','邓洲晶','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118979,'HMRobot118979','e10adc3949ba59abbe56e057f20f883e','郭敏京','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118980,'HMRobot118980','e10adc3949ba59abbe56e057f20f883e','杭震冬','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118981,'HMRobot118981','e10adc3949ba59abbe56e057f20f883e','樊为延','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118982,'HMRobot118982','e10adc3949ba59abbe56e057f20f883e','高民聪','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118983,'HMRobot118983','e10adc3949ba59abbe56e057f20f883e','房东磊','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118984,'HMRobot118984','e10adc3949ba59abbe56e057f20f883e','柏洁树','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118985,'HMRobot118985','e10adc3949ba59abbe56e057f20f883e','岑炳旭','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118986,'HMRobot118986','e10adc3949ba59abbe56e057f20f883e','孙红光','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118987,'HMRobot118987','e10adc3949ba59abbe56e057f20f883e','姚奕福','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118988,'HMRobot118988','e10adc3949ba59abbe56e057f20f883e','童帆传','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118989,'HMRobot118989','e10adc3949ba59abbe56e057f20f883e','郁然惠','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118990,'HMRobot118990','e10adc3949ba59abbe56e057f20f883e','岑鹤学','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118991,'HMRobot118991','e10adc3949ba59abbe56e057f20f883e','项淑延','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118992,'HMRobot118992','e10adc3949ba59abbe56e057f20f883e','穆怀彦','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118993,'HMRobot118993','e10adc3949ba59abbe56e057f20f883e','诸宏为','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118994,'HMRobot118994','e10adc3949ba59abbe56e057f20f883e','姚群斌','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118995,'HMRobot118995','e10adc3949ba59abbe56e057f20f883e','时显爽','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118996,'HMRobot118996','e10adc3949ba59abbe56e057f20f883e','穆爱淑','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118997,'HMRobot118997','e10adc3949ba59abbe56e057f20f883e','郁梅涵','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118998,'HMRobot118998','e10adc3949ba59abbe56e057f20f883e','臧凯枫','',0,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(118999,'HMRobot118999','e10adc3949ba59abbe56e057f20f883e','潘权厚','',1,'',0,0,'',0,0,-1,'','',0,'','',1,0,0,0,0,'',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(119000,'LXW100','e10adc3949ba59abbe56e057f20f883e','LXW100','',1,'',87664,0,'',100,16,1,'192.168.0.64','http://ht.szhuomei.com/head/man/1300.jpg',24,'FC-AA-14-74-65-32','{E84F7BD7-A421-41AE-B1CE-CC612B501ACE}',0,16,0,0,1543367686,'192.168.0.64',1,2,1543438800,63,0,'','','',24,'','','',1543487534,33013,1,'',''),(119001,'LXW200','e10adc3949ba59abbe56e057f20f883e','LXW200','',1,'',95487,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/2046.jpg',21,'FC-AA-14-74-65-32','{A03C76B9-3DAA-4B48-A722-98A48BE9B836}',0,0,0,0,1543369185,'192.168.0.64',1,0,1543438800,43,0,'','','',21,'','','',0,55240,0,'',''),(119002,'Phone119002','066dde6986793933cbe38eed1355846c','kk','',1,'',1000000,0,'',100,0,-1,'112.97.61.190','http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIZle6KRs18nFlNtLKHmSthxRhQOg2nNKs5YuacDLQQLC0FJvawDoC9S8rGE95aTBO5icicg94wxYzA/132',5,'00000000-1963-ce13-0000-00006d56d02a','{60C91E4E-8D09-4DB6-8577-F82A3E0783C7}',0,0,0,0,1542853920,'112.97.57.94',2,0,1542853920,12,0,'','','oQgmV0rfD-Z0f_JnC9Yyot4HMh9Y',5,'113.886108','22.548728','广东省深圳市宝安区',0,4598,0,'',''),(119003,'Phone119003','e3c26a592e6655ce1928139d2f117d48','藏','',1,'',1000000,0,'',100,0,-1,'119.123.64.255','http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKqDeE8vfUhEVWQJOVKlqGmLyPvPibvvFd3O8iacppnhQJJYogLJ0e55Z010SaYZiawf7PMKKFBVhgibw/132',0,'ffffffff-d1e4-afa8-ffff-ffffe688dbfe','{CA4EF5B7-1D8B-4CEE-BB7C-F29DFFC575A3}',0,0,0,0,1542854170,'119.123.71.141',2,0,1542854170,0,0,'','','oQgmV0hHkVHRkFMokMdWX-Pb7f1k',0,'113.887842','22.548805','广东省深圳市宝安区',1542888222,1103,1,'',''),(119004,'Phone119004','1cd7b3ae9ff4605060312e47ef7d2fb4','模拟器wang','',0,'',997000,0,'',91,32,1,'119.123.64.255','http://thirdwx.qlogo.cn/mmopen/vi_32/SPwuqHB1jVcgE8IUUE7aDzabAqnSwZhs2OtEnU8lXW1WPwBzBxUJd6v0vdKLEICxJfs1wgibZgmU7EGXQHp1Maw/132',7,'00000000-7790-83ba-0000-00007afe3671','{C4320F2C-1BB2-4421-B516-A741A7185218}',0,0,0,0,1542854595,'119.123.64.255',2,0,1542854595,12,0,'','','oQgmV0uz7pojX7yIgIVN6315qB2g',7,'','','',0,5445,0,'',''),(119005,'CCCCCCCCCC','12acd97a4e9f587fc2f0de2108b158c3','CCCCCCCCCC','',1,'',100001,0,'',100,16,1,'192.168.0.72','http://ht.szhuomei.com/head/man/1203.jpg',0,'FC-AA-14-FF-DE-E4','{13C2D505-9860-447B-BE17-11D9D25FFB27}',0,0,0,0,1543409688,'192.168.0.72',1,0,1543479270,0,0,'','','',0,'','','',1543487540,1143,1,'',''),(119006,'Phone119006','41ff1ffd732973409fcb57603fdb0694','夜雨','',1,'',1000000,0,'',100,0,-1,'117.136.39.211','http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKF2n4DZFZLfWlX2h5TvLJuW5X9V8RCvcmOHDUpgrPt9G6YugbAZUBteXQIs2X6jfXzhRLtYydSNA/132',0,'00000000-4968-c4c9-ffff-ffffb822b527','{FE28FA5C-D507-4481-BA55-627B65985734}',0,0,0,0,1542857018,'223.104.63.1',2,0,1542857018,0,0,'','','oQgmV0mRFAv5y8vtF5x3yk2gwQt0',0,'113.887816','22.548795','广东省深圳市宝安区',0,4756,0,'',''),(119007,'XIE123','e10adc3949ba59abbe56e057f20f883e','XIE123','',1,'',997000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1493.jpg',0,'FC-AA-14-FF-DE-E4','{3A63CE18-52F0-41E4-9069-36CD2CF4A505}',0,0,0,0,1542867815,'119.123.64.255',1,1,1542867817,0,0,'','','',0,'','','',0,54,0,'',''),(119009,'HHH123','e10adc3949ba59abbe56e057f20f883e','HHH123','',1,'',1000000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/2053.jpg',0,'FC-AA-14-FF-DE-E4','{CC26B828-EA37-4170-971B-BBE0C13F3A25}',0,0,0,0,1542875019,'119.123.64.255',1,0,1542875020,0,0,'','','',0,'','','',0,300,0,'',''),(119008,'XIE1234','e10adc3949ba59abbe56e057f20f883e','XIE1234','',1,'',1000000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1010.jpg',0,'FC-AA-14-FF-DE-E4','{4ADF820A-9762-4AC9-9CF6-EAF60A256C0A}',0,0,0,0,1542868487,'119.123.64.255',1,0,1542868488,0,0,'','','',0,'','','',0,311,0,'',''),(119013,'Phone119013','d5ee6056bff31ec1e6c42f70f3810c6d','测试至尊科技','',1,'2420643340@qq.com',994390,0,'123456',100,0,-1,'119.123.66.240','http://thirdwx.qlogo.cn/mmopen/vi_32/vUghQNWW0yMOiaboncpZxztVztfCIP3VWpn9HbAaTabtb7pntHMS8tVehibSnKNCEOCWm4pIoJhbwOjGjz4oUsHw/132',7,'ffffffff-9830-5345-ffff-ffff814fd29e','{C721DC45-32DF-4EF0-8311-E8D05340325B}',0,0,0,0,1542876503,'119.123.66.240',2,2,1542876503,12,0,'','','oQgmV0hy5fn2FIzWZHAaPi1schjE',7,'113.890232','22.550347','广东省深圳市宝安区',1542883574,2916,1,'',''),(119010,'123456','e10adc3949ba59abbe56e057f20f883e','123456','',1,'',1000000,0,'',100,0,-1,'','http://ht.szhuomei.com/head/man/1259.jpg',0,'','',0,0,0,0,1542876308,'119.123.64.255',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(119011,'TT1234576','e10adc3949ba59abbe56e057f20f883e','TT1234576','',1,'',1000000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1327.jpg',0,'FC-AA-14-FF-DE-E4','{6E10EAC2-8E36-4A76-8E5A-0F271C09C0E3}',0,0,0,0,1542876377,'119.123.64.255',1,0,1542876379,0,0,'','','',0,'','','',0,148,0,'',''),(119012,'HUOMEI123456','e10adc3949ba59abbe56e057f20f883e','HUOMEI123456','',1,'',1000000,0,'',100,0,-1,'','http://ht.szhuomei.com/head/man/1027.jpg',0,'','',0,0,0,0,1542876420,'119.123.64.255',1,0,0,0,0,'','','',0,'','','',0,0,0,'',''),(119014,'81770595','fe1651d5388510d718a6e215ce94d475','81770595','',1,'',991388,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1115.jpg',3,'40-8D-5C-1C-E0-01','{56EE195A-5615-4F61-8E4F-63367CB8E82A}',0,0,0,0,1542876845,'119.123.64.255',1,0,1542876847,6,0,'','','',3,'','','',1542884738,2864,1,'',''),(119015,'GBR007','e10adc3949ba59abbe56e057f20f883e','GBR007','',1,'',978000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1794.jpg',2,'00-E0-70-4C-71-50','{C8281E4A-5D4F-4C10-92DC-9C03888BDA51}',0,0,0,0,1542877142,'119.123.64.255',1,0,1542877166,6,0,'','','',2,'','','',0,4223,0,'',''),(119016,'FFA123','e10adc3949ba59abbe56e057f20f883e','FFA123','',1,'',1000000,0,'',100,0,-1,'119.123.64.255','http://ht.szhuomei.com/head/man/1554.jpg',0,'FC-AA-14-FF-DE-E4','{AF327170-E6F1-42BB-ABEA-FA22EBA1FA69}',0,0,0,0,1542877886,'119.123.64.255',1,0,1542879807,0,0,'','','',0,'','','',1542883576,1083,1,'','');
/*!40000 ALTER TABLE `userInfo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_admin_action`
--

LOCK TABLES `web_admin_action` WRITE;
/*!40000 ALTER TABLE `web_admin_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_admin_action` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_admin_group`
--

LOCK TABLES `web_admin_group` WRITE;
/*!40000 ALTER TABLE `web_admin_group` DISABLE KEYS */;
INSERT INTO `web_admin_group` VALUES (1,'管理员','1,2,3,9,12,13,14,15,16,18,19,20,21,24,25,26,27,28,29,30,31,32,35,36,38,39,40,46,48,49,59,60,61,62,64,65,66,67,68,70,71,72,73,76,77,103,116,117,118,122,123,124,126,127,129,130,131,132,133,135,136,137','管理员',0),(2,'商务','9,12,13,14,15,16,17,18,20,24,25,26,27,28,29,30,31,32,35,38,39,41,42,43,46,48,49,66,70,71,73,76,77,101,103,116,117,118,122,123,124,126,127,129,130,131,132,133,135,137','商务',0);
/*!40000 ALTER TABLE `web_admin_group` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_admin_log`
--

LOCK TABLES `web_admin_log` WRITE;
/*!40000 ALTER TABLE `web_admin_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_admin_member`
--

LOCK TABLES `web_admin_member` WRITE;
/*!40000 ALTER TABLE `web_admin_member` DISABLE KEYS */;
INSERT INTO `web_admin_member` VALUES (1,'adminser','boss','e10adc3949ba59abbe56e057f20f883e','15180092940',0,0,443,1543223409,'119.123.69.223',0,1);
/*!40000 ALTER TABLE `web_admin_member` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_admin_menu`
--

LOCK TABLES `web_admin_menu` WRITE;
/*!40000 ALTER TABLE `web_admin_menu` DISABLE KEYS */;
INSERT INTO `web_admin_menu` VALUES (1,'系统管理','',0,0,1,'fa fa-cogs'),(2,'用户分组','Adminsystem/user_group',0,1,1,''),(3,'菜单管理','Adminsystem/menu_config',0,1,0,''),(9,'玩家管理','',0,0,4,'fa fa-user'),(11,'机器人管理','User/vbot_list',1,9,1,''),(12,'银行操作记录','Hall/bank',0,129,1,''),(13,'用户限制列表','User/limit_ip',0,9,3,''),(14,'在线玩家列表','User/online_list',1,9,4,''),(15,'代理管理','',0,0,9,'fa fa-user-secret'),(16,'代理列表','Agent/member_list',0,15,1,''),(17,'商城管理','',0,0,5,'fa fa-shopping-cart'),(18,'大厅功能管理','',0,0,7,'fa fa-desktop'),(19,'开发人员选项','',0,0,2,'fa fa-gamepad'),(20,'游戏管理','',0,0,6,'fa fa-rocket'),(21,'网站管理','',0,0,2,'fa fa-internet-explorer'),(22,'代理权限控制','Agent/member_rules',1,15,1,''),(23,'充值账单明细','Agent/bill_detail',1,17,2,''),(24,'代理信息统计','Agent/info_count',0,15,3,''),(25,'代理申请提现记录','Agent/apply_pos',0,15,3,''),(26,'抽奖','Hall/turntable',0,18,5,''),(27,'签到','Hall/sign',0,18,1,''),(28,'邮件管理','Hall/email',0,127,2,''),(29,'公告管理','Hall/notice',0,127,3,''),(30,'反馈管理','Hall/feedback',0,18,7,''),(31,'救济金','Hall/alms',0,18,3,''),(32,'转赠','Hall/given',0,18,4,''),(34,'实物兑换管理','Hall/convert',1,18,8,''),(35,'世界广播','Hall/radio',0,18,6,''),(36,'跳转和游戏参数管理','Hall/web_page',0,19,4,''),(37,'魔法表情管理','Game/magic',1,19,0,''),(38,'房间管理','Game/room',1,20,2,''),(39,'游戏列表','Game/game_list',0,20,3,''),(40,'版本控制','Game/version',0,19,2,''),(41,'充值分润列表','Mall/recharge_record',0,17,4,''),(42,'充值统计','Mall/data_count',0,17,3,''),(43,'商城商品管理','Mall/goods',0,17,1,''),(44,'平台数据记录','Operate/plat_data_record',1,124,0,''),(45,'实物兑换统计','Operate/convert_record',1,124,1,''),(46,'注册下载量统计','Operate/register_download_record',0,124,1,''),(47,'用户充值统计','Operate/user_recharge_count',1,124,3,''),(48,'在线用户统计','Operate/online_count',0,124,2,''),(49,'活跃玩家和流失玩家','Operate/active_loss_user',0,124,3,''),(51,'签到统计','Operate/sign_count',1,124,7,''),(52,'领取救济金统计','Operate/alms_count',1,124,8,''),(53,'抽奖统计','Operate/turntable_count',1,124,9,''),(54,'反馈统计','Operate/feedback_count',1,124,10,''),(55,'转赠统计','Operate/given_count',1,124,11,''),(56,'世界广播统计','Operate/radio_count',1,124,12,''),(57,'分享统计','Operate/share_count',1,124,13,''),(58,'游戏输赢统计','Operate/game_record_info',1,124,14,''),(59,'网站参数配置','Home/config',0,21,0,''),(60,'图片位管理','Home/img',0,21,1,''),(61,'产品中心','Home/product',0,21,2,''),(62,'新闻管理','Home/news',0,21,3,''),(63,'留言记录','Home/feedback',1,21,4,''),(64,'游戏底包上传','Game/upload_view',0,19,3,''),(65,'游戏常用参数配置','Hall/game_config',0,19,5,''),(66,'游戏分享','Hall/share',0,18,2,''),(67,'管理用户','Adminsystem/member_list',0,1,2,''),(68,'操作日志','Adminsystem/operation',0,1,3,''),(69,'VIP房管理','Game/vipRoom',1,19,0,''),(70,'金币变化日志','Operate/money_change_record',0,123,14,''),(71,'代理分成比例配置','Agent/agent_config',0,15,4,''),(72,'平台状态管理','Adminsystem/plat_status',0,19,1,''),(73,'钻石变化日志','Operate/jewels_change_record',0,123,15,''),(74,'银行操作记录','Hall/bank',1,18,12,''),(75,'登录管理','Hall/login',1,18,0,''),(76,'代理后台','Agent/Public/login',0,15,6,''),(77,'游戏控制','Hall/rewardsPool',0,20,1,''),(78,'充值','User/user_recharge',0,10,0,''),(79,'提取','User/user_pos',0,10,0,''),(80,'限制登录','User/limit_login',0,10,0,''),(81,'添加白名单','User/white',0,10,0,''),(82,'添加超端','User/set_supper_user',0,10,0,''),(84,'发送个人邮件','User/personal_send_email',0,10,0,''),(85,'绑定邀请码','User/bind_code',0,10,0,''),(86,'离开房间','User/clearRoom',0,10,0,''),(87,'充值记录','User/personal_recharge_record',0,10,0,''),(88,'金币变化记录','User/personal_money_change',0,10,0,''),(89,'钻石变化记录','User/personal_jewels_change',1,9,0,''),(90,'个人对局游戏记录','User/personal_game_record',0,10,0,''),(91,'个人房卡游戏记录','User/personal_game_jewsels_record',1,9,0,''),(92,'签到记录','User/personal_sign_record',0,10,0,''),(93,'抽奖记录','User/personal_turntable_record',0,10,0,''),(94,'分享记录','User/personal_share_record',0,10,0,''),(95,'实物兑换记录 ','User/personal_convert_record',0,10,0,''),(96,'转赠记录','User/personal_given_record',0,10,0,''),(97,'魔法表情记录','User/personal_magic_record',0,10,0,''),(98,'世界广播记录','User/personal_radio_record',0,10,0,''),(99,'登录记录','Hall/login',0,10,0,''),(100,'代理信息','User/agentinfo',0,10,0,''),(101,'订单管理','Mall/orders',0,17,2,''),(102,'后台充值提取记录','User/admin_action_record',1,17,6,''),(103,'代理申请列表','Agent/examine',0,15,2,''),(104,' 菜单管理','Wechat/index',0,112,0,''),(105,'关键词管理','wechat/keywords',0,112,0,''),(106,'关注默认回复','wechat/subscribe',0,112,0,''),(107,'用户管理','wechat/user',0,112,0,''),(108,'素材管理','wechat/material',0,112,0,''),(109,'图文管理','wechat/news',1,112,0,''),(110,'客服管理','wechat/kf',0,112,0,''),(111,'微信代理申请','Agent/apply',1,15,0,''),(112,'微信管理','Wechat/Index',1,0,0,'fa fa-qq'),(113,'代理收入明细','User/bill_detail',0,10,0,''),(114,'设置用户状态','User/set_user_status',0,10,0,''),(115,'好友打赏记录','Hall/reward',1,18,0,''),(116,'俱乐部列表','Club/index',0,118,0,''),(117,'俱乐部金币变化日志','Club/money_change_record',0,118,0,''),(118,'俱乐部管理','',0,0,8,'fa fa-group'),(119,'游戏输赢设置','Game/jackpotconfig',1,18,0,''),(120,'意见反馈','wechat/feedback',1,112,0,''),(122,'俱乐部火币变化日志','Club/firCoinChange',0,118,0,''),(123,'货币日志变化','',0,0,10,'fa fa-calendar'),(124,'平台数据统计','',0,0,12,'fa fa-line-chart'),(126,'统计图表','Hall/statistical_chart',0,18,8,''),(127,'邮件与公告','',0,0,3,'fa fa-bell'),(129,'银行管理','',0,0,11,'fa fa-bank'),(130,'银行统计','Hall/bank_count',0,129,0,''),(131,'代理申请页面','agent/register',0,15,7,''),(132,'大厅登入记录','User/logon_record',0,9,2,''),(133,'玩家列表','User/user_list',0,9,1,''),(135,'代理反馈问题','Agent/feedback',0,15,8,''),(136,'支付配置','Mall/payConfig',0,19,6,''),(137,'游戏登入记录','User/game_login',0,9,2,''),(138,'活动','Hall/broadcast',1,18,8,'');
/*!40000 ALTER TABLE `web_admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_apply`
--

LOCK TABLES `web_agent_apply` WRITE;
/*!40000 ALTER TABLE `web_agent_apply` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_apply` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_apply_pos`
--

LOCK TABLES `web_agent_apply_pos` WRITE;
/*!40000 ALTER TABLE `web_agent_apply_pos` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_apply_pos` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_audit`
--

LOCK TABLES `web_agent_audit` WRITE;
/*!40000 ALTER TABLE `web_agent_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_bind`
--

LOCK TABLES `web_agent_bind` WRITE;
/*!40000 ALTER TABLE `web_agent_bind` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_bind` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_bind_reward`
--

LOCK TABLES `web_agent_bind_reward` WRITE;
/*!40000 ALTER TABLE `web_agent_bind_reward` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_bind_reward` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_config`
--

LOCK TABLES `web_agent_config` WRITE;
/*!40000 ALTER TABLE `web_agent_config` DISABLE KEYS */;
INSERT INTO `web_agent_config` VALUES (1,'recharge_commission_level','0','充值返佣级别 0为无限级'),(2,'agent_level_ratio_1','60','一级代理获得分成比例 百分比'),(3,'agent_level_ratio_2','50','二级代理获得分成比例 百分比'),(4,'agent_level_ratio_3','40','三级代理获得分成比例 百分比'),(5,'exchange_proportion','100','兑换比例 1:value'),(6,'put_count','3','每日提现次数'),(7,'put_min_money','80','每次最低提现额度'),(8,'agent_notice','至尊娱乐棋牌','最新公告');
/*!40000 ALTER TABLE `web_agent_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_exchange`
--

LOCK TABLES `web_agent_exchange` WRITE;
/*!40000 ALTER TABLE `web_agent_exchange` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_exchange` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_feedback`
--

LOCK TABLES `web_agent_feedback` WRITE;
/*!40000 ALTER TABLE `web_agent_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_feedback` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_group`
--

LOCK TABLES `web_agent_group` WRITE;
/*!40000 ALTER TABLE `web_agent_group` DISABLE KEYS */;
INSERT INTO `web_agent_group` VALUES (1,'1级代理','1,2,3,4,5,6','1级代理权限控制',0),(2,'2级代理','1,2,3,4,5,6','2级代理权限控制',0),(3,'3级代理','1,2,3,4,5,6','3级代理权限控制',0);
/*!40000 ALTER TABLE `web_agent_group` ENABLE KEYS */;
UNLOCK TABLES;

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
  `commission_rate` tinyint(1) NOT NULL COMMENT '分销比率',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `username` (`username`),
  KEY `agentid` (`agentid`),
  KEY `status` (`status`),
  KEY `register_time` (`register_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_agent_member`
--

LOCK TABLES `web_agent_member` WRITE;
/*!40000 ALTER TABLE `web_agent_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_member` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_menu`
--

LOCK TABLES `web_agent_menu` WRITE;
/*!40000 ALTER TABLE `web_agent_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_menu` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_recharge`
--

LOCK TABLES `web_agent_recharge` WRITE;
/*!40000 ALTER TABLE `web_agent_recharge` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_agent_recharge` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_agent_recharge_config`
--

LOCK TABLES `web_agent_recharge_config` WRITE;
/*!40000 ALTER TABLE `web_agent_recharge_config` DISABLE KEYS */;
INSERT INTO `web_agent_recharge_config` VALUES (1,'微信支付','wx123',1,'http://w.php.com/Public/newadmin/img/1529477603753.jpg'),(2,'支付支付','zfb123',2,'http://w.php.com/Public/newadmin/img/1529477605553.jpg'),(3,'银行卡','ic123',3,'49641579441467');
/*!40000 ALTER TABLE `web_agent_recharge_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_api_record`
--

LOCK TABLES `web_api_record` WRITE;
/*!40000 ALTER TABLE `web_api_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_api_record` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_bank_record`
--

LOCK TABLES `web_bank_record` WRITE;
/*!40000 ALTER TABLE `web_bank_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_bank_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_bill_detail`
--

LOCK TABLES `web_bill_detail` WRITE;
/*!40000 ALTER TABLE `web_bill_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_bill_detail` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_broadcast`
--

LOCK TABLES `web_broadcast` WRITE;
/*!40000 ALTER TABLE `web_broadcast` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_broadcast` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_club`
--

LOCK TABLES `web_club` WRITE;
/*!40000 ALTER TABLE `web_club` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_club` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_club_member`
--

LOCK TABLES `web_club_member` WRITE;
/*!40000 ALTER TABLE `web_club_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_club_member` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_code_invitation`
--

LOCK TABLES `web_code_invitation` WRITE;
/*!40000 ALTER TABLE `web_code_invitation` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_code_invitation` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_email`
--

LOCK TABLES `web_email` WRITE;
/*!40000 ALTER TABLE `web_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_email` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游戏反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_feedback`
--

LOCK TABLES `web_feedback` WRITE;
/*!40000 ALTER TABLE `web_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_feedback` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_feedback_reply`
--

LOCK TABLES `web_feedback_reply` WRITE;
/*!40000 ALTER TABLE `web_feedback_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_feedback_reply` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_friend`
--

LOCK TABLES `web_friend` WRITE;
/*!40000 ALTER TABLE `web_friend` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_friend` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_friend_notify`
--

LOCK TABLES `web_friend_notify` WRITE;
/*!40000 ALTER TABLE `web_friend_notify` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_friend_notify` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_friend_reward`
--

LOCK TABLES `web_friend_reward` WRITE;
/*!40000 ALTER TABLE `web_friend_reward` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_friend_reward` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_game_config`
--

LOCK TABLES `web_game_config` WRITE;
/*!40000 ALTER TABLE `web_game_config` DISABLE KEYS */;
INSERT INTO `web_game_config` VALUES (1,'kefu_phone_1','18025305658','客服1电话'),(2,'bind_agentid_send_jewels','1','绑定代理号送的房卡数'),(3,'bind_agentid_send_money','5','绑定代理号送的金币'),(6,'android_packet_address','http://47.107.147.29/download/zzyl-101.apk','游戏底包安卓包地址'),(7,'apple_packet_address','http://app.3k832.cn/ehs ','游戏底包苹果包下载地址'),(8,'share_begin_time','1509638400','分享活动开始时间'),(9,'share_end_time','4099737600','分享活动结束时间'),(10,'share_interval','4','分享奖励间隔时间'),(11,'share_img1','http://ht.szhuomei.com/Uploads/home_img/5a570b6b7d8f9.png','分享图片'),(12,'share_url','dwadadwadwaddwad','分享链接'),(13,'share_address','2','分享到哪个平台才有奖励'),(14,'share_send_money','2','分享送金币数'),(15,'share_send_jewels','1','分享赠送房卡数'),(19,'bindAgent_sendUser_money','5','代理邀请玩家 绑定的玩家赠送的奖励 金币'),(20,'bindAgent_sendUser_diamonds','10','代理邀请玩家 绑定的玩家赠送的奖励 钻石'),(21,'user_invitationUser_SendMoney','1','玩家邀请玩家 绑定的玩家赠送的奖励 金币'),(22,'user_invitationUser_SendDiamonds','5','玩家邀请玩家 绑定的玩家赠送的奖励 钻石'),(23,'userBind_sendMoney','5','玩家邀请玩家 邀请的玩家赠送的的奖励 金币'),(24,'userBind_sendDiamonds','2','玩家邀请玩家 邀请的玩家赠送的的奖励 钻石'),(25,'share_img2','http://ht.szhuomei.com/Uploads/home_img/5a571bd12adc5.png','分享图片2'),(26,'share_img3','http://ht.szhuomei.com/Uploads/home_img/5a571bda27e20.png','分享图片3'),(27,'share_img4','http://ht.szhuomei.com/Uploads/home_img/5a571bf8c0db3.png','分享图片4'),(28,'share_img5','http://ht.szhuomei.com/Uploads/home_img/5a571bfab043f.png','分享图片5'),(29,'BindPhoneSendMoney','2000','绑定手机');
/*!40000 ALTER TABLE `web_game_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_give_record`
--

LOCK TABLES `web_give_record` WRITE;
/*!40000 ALTER TABLE `web_give_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_give_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_ad`
--

LOCK TABLES `web_home_ad` WRITE;
/*!40000 ALTER TABLE `web_home_ad` DISABLE KEYS */;
INSERT INTO `web_home_ad` VALUES (3,'logo','http://w.php.com/Uploads/home_img/5b7635746ea9b.jpg','网站logo图片',0,1504519961),(4,'anzhuo','http://w.php.com/Uploads/home_img/5b76356b880ad.jpg','下载二维码   安卓系统',0,1504522558),(5,'轮播图1','http://w.php.com/Uploads/home_img/5b763560a4e85.jpg','网站轮播图',1,1504522610),(6,'轮播图2','http://w.php.com/Uploads/home_img/5b76355a251bc.jpg','轮播图2',1,1504522675),(7,'轮播图3','http://w.php.com/Uploads/home_img/5b763552302a3.jpg','轮播图3',1,1504522689),(8,'qrcode','http://w.php.com/Uploads/home_img/5b763540bfaf4.png','公众号二维码',0,1504680088);
/*!40000 ALTER TABLE `web_home_ad` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_config`
--

LOCK TABLES `web_home_config` WRITE;
/*!40000 ALTER TABLE `web_home_config` DISABLE KEYS */;
INSERT INTO `web_home_config` VALUES (4,'title','至尊娱乐','网站标题',1504679233),(5,'phone','18025305658','联系方式',1504679252),(6,'address','深圳市宝安区海秀路19号国际西岸商务大厦801','公司地址',1504679272),(7,'beian','Copyright 2012-2014 huomei, All Rights Reserved 宝城企业.至尊 版权所有 备案号：粤ICP备17067659号','公司备案信息',1504679295),(8,'desc','深圳市至尊网络科技有限公司，该公司是一家长期专注于定制开发特色棋牌游戏、手机棋牌游戏、捕鱼游戏等等的高新科技游戏软件开发公司，力求打造“专业化、国际化、产业化、品牌化”的高科技游戏软件产品，成为独具魅力的领跑者。 至尊集结了一群年轻而充满梦想与激情的游戏从业人员，团队核心成员均来自腾讯、中青宝、博雅互动等一线棋牌游戏开发公司，具有五年以上棋牌游戏研发经验，自公司成立以来，始终秉承“诚为本、技至精”的核心经营理念，不断钻研市场需求，不断打磨产品品质，与中手游、百度、91等众多知名互联网企业精诚合作，并取得了丰硕的成果。由至尊科技一手打造的房卡金币双模式棋牌游戏平台，铸就了大量网络棋牌游戏行业的成功案例。 在不断优化自身产品的同时，至尊科技将推出更多适应市场的运营级游戏产品和平台，凭借自身丰富的资源优势，成熟的经验优势，强大的技术优势，优质的服务优势，更好的为客户创造独特价值。','公司介绍',1504679361),(9,'email','2041302270@qq.com','邮箱',1504679958),(10,'qq','2041302270','qq',1504679970),(3,'abbreviation','至尊娱乐','简称',1504679233);
/*!40000 ALTER TABLE `web_home_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_feedback`
--

LOCK TABLES `web_home_feedback` WRITE;
/*!40000 ALTER TABLE `web_home_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_home_feedback` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_menu`
--

LOCK TABLES `web_home_menu` WRITE;
/*!40000 ALTER TABLE `web_home_menu` DISABLE KEYS */;
INSERT INTO `web_home_menu` VALUES (1,'首页','Index/index'),(2,'下载中心','Index/download'),(3,'新闻中心','Index/news'),(4,'联系我们','Index/lianxi');
/*!40000 ALTER TABLE `web_home_menu` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_news`
--

LOCK TABLES `web_home_news` WRITE;
/*!40000 ALTER TABLE `web_home_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_home_news` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_home_product`
--

LOCK TABLES `web_home_product` WRITE;
/*!40000 ALTER TABLE `web_home_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_home_product` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_horn`
--

LOCK TABLES `web_horn` WRITE;
/*!40000 ALTER TABLE `web_horn` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_horn` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_notice`
--

LOCK TABLES `web_notice` WRITE;
/*!40000 ALTER TABLE `web_notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_notice` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_packet_version`
--

LOCK TABLES `web_packet_version` WRITE;
/*!40000 ALTER TABLE `web_packet_version` DISABLE KEYS */;
INSERT INTO `web_packet_version` VALUES (1,1,1,'appStore','1.0.6','http://app.3k832.cn/ehs ','','appStore'),(2,1,2,'android','1.0.1','http://47.107.147.29/download/zzyl-101.apk','','平台包安卓1.0.0版本!'),(3,1,3,'ios签名包','1.0.0','','','ios签名包'),(4,2,1,'至尊娱乐','1.0.30','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201811262303.zip','','大厅资源包'),(10,2,30100008,'牛牛(牛将军)','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NJJ-201811242038.zip','','牛牛(牛将军)'),(12,2,30100108,'三公','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NSG-201811242038.zip','','三公'),(14,2,30000004,'跑得快','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/PDK-201811242038.zip','','跑得快'),(15,2,30000200,'百人牛牛','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BRNN-201811242038.zip','','百人牛牛'),(17,2,20170405,'21点','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/21D-201811261934.zip','','21点'),(29,2,30000404,'十三水','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FZSSS-201811261934.zip','1.0.0','十三水'),(30,2,11100200,'百家乐','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/HL30M-201811242038.zip','1.0.0','百家乐'),(31,2,10000900,'豹子王','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BZW-201811242038.zip','1.0.0','豹子王'),(32,2,10001000,'奔驰宝马','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BCBM-201811242038.zip','1.0.0','奔驰宝马'),(33,2,11100604,'牌九','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BBPJ-201811242038.zip','1.0.0','牌九'),(34,2,12101105,'炸金花','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/LTZJH5-201811242038.zip','1.0.0','炸金花'),(35,2,11000100,'飞禽走兽','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FQZS-201811242038.zip','1.0.0','飞禽走兽');
/*!40000 ALTER TABLE `web_packet_version` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_packet_version_test`
--

LOCK TABLES `web_packet_version_test` WRITE;
/*!40000 ALTER TABLE `web_packet_version_test` DISABLE KEYS */;
INSERT INTO `web_packet_version_test` VALUES (1,1,1,'appStore','1.0.30','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201811262303.zip','1.0.6','appStore'),(2,1,2,'android','6.2.3','http://haoyue.szhuomei.com/Lobby-201807180930.zip','','平台包安卓1.3.2版本'),(3,1,3,'ios签名包','1.0.0','http://www.baidu.com','','ios签名包'),(4,2,1,'至尊娱乐棋牌大厅','1.0.30','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201811262303.zip','','大厅资源包'),(5,2,36610103,'斗地主','7.0.0','http://haoyue.szhuomei.com/CSDDZ-201808101517.zip','','斗地主'),(6,2,30000404,'十三水','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FZSSS-201811261934.zip','','十三水'),(7,2,20161010,'广东推倒胡','7.0.0','http://haoyue.szhuomei.com/TDHMJ-201808101517.zip','','广东推倒胡'),(8,2,12101105,'炸金花','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/LTZJH5-201811242038.zip','','炸金花'),(9,2,20170405,'21点','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/21D-201811261934.zip','','21点'),(10,2,30100006,'牛牛(牛将军)','7.0.0','http://haoyue.szhuomei.com/NJJ-201808101517.zip','','牛牛(牛将军)'),(11,2,11100604,'牌九','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BBPJ-201811242038.zip','','牌九'),(12,2,30100108,'三公','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NSG-201811242038.zip','','三公'),(13,2,23510004,'山西推到胡','7.0.0','http://haoyue.szhuomei.com/SXTDH-201808101517.zip','','山西推到胡'),(14,2,30000004,'跑得快','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/PDK-201811242038.zip','','跑得快'),(15,2,30000200,'百人牛牛','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BRNN-201811242038.zip','','百人牛牛'),(16,2,37460003,'跑胡子','7.0.0','http://haoyue.szhuomei.com/PHZ3-201808101517.zip','','跑胡子'),(17,2,20161004,'红中麻将','7.0.0','http://haoyue.szhuomei.com/HZMJ-201808101517.zip','','红中麻将'),(18,2,30000006,'十点半','7.0.0','http://haoyue.szhuomei.com/SDB-201808101517.zip','','十点半'),(19,2,26600004,'潮汕麻将','7.0.0','http://haoyue.szhuomei.com/CSMJ-201808101517.zip','','潮汕麻将（下线）'),(20,2,11100200,'欢乐30秒','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/HL30M-201811242038.zip','','欢乐30秒'),(21,2,20173124,'血战麻将','7.0.0','http://haoyue.szhuomei.com/XZMJ-201808101517.zip','','血战麻将'),(22,2,10000900,'豹子王','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BZW-201811242038.zip','','豹子王'),(23,2,30000007,'德州扑克','7.0.0','http://haoyue.szhuomei.com/TEXAS-201808101517.zip','','德州扑克'),(24,2,27910004,'南昌麻将','7.0.0','http://haoyue.szhuomei.com/NCMJ-201808101517.zip','','南昌麻将2、4人共用'),(25,2,37910005,'窝龙','7.0.0','http://haoyue.szhuomei.com/WL-201808101517.zip','','窝龙'),(26,2,10001000,'奔驰宝马','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BCBM-201811242038.zip','','奔驰宝马'),(28,2,11000100,'飞禽走兽','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FQZS-201811242038.zip','','飞禽走兽');
/*!40000 ALTER TABLE `web_packet_version_test` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_pay_config`
--

LOCK TABLES `web_pay_config` WRITE;
/*!40000 ALTER TABLE `web_pay_config` DISABLE KEYS */;
INSERT INTO `web_pay_config` VALUES (1,'苹果支付','wx624ff377019b69a1','1487308352','12345678123456781234567812345aab',0,'12311','12','','1',0),(2,'微信支付','wx624ff377019b69a1','1487308352','12345678123456781234567812345aab',0,'','http://ht.szhuomei.com/hm_ucenter/pay/wei_xin/notify.php','','1',0),(3,'汇付宝','2112482','2116650','C53514AFB85F443BAE134E90',0,'','http://ht.szhuomei.com/hm_ucenter/pay/hui_fu_bao/notify.php','http://','1',0),(4,'旺实富','69018071165702','69018071165702','046909094053n7SiSWYr',0,'','http://ht.szhuomei.com/hm_ucenter/pay/wang_shi_fu/notify.php','http://','1',0),(5,'简付','180778708','180778708','i4dlpqh8eza9i2kuy9oo6oaez6hw6dor',0,'','http://ht.szhuomei.com/hm_ucenter/pay/jian_fu/notify.php','http://ht.szhuomei.com/hm_ucenter/pay/jian_fu/index.php','1',1),(6,'鑫宝','10068','10068','c4ek3opkh9f8j52cr8qcgs9i2k48f6z2',1,'','http://47.107.147.29/hm_ucenter/pay/xin_bao/notify.php','http://ht.szhuomei.com/hm_ucenter/pay/xin_bao/index.php','1',1),(7,'汇付宝直连','2112482','2112482','C53514AFB85F443BAE134E90',0,'','http://ht.szhuomei.com/hm_ucenter/pay/hui_fu_bao_zl/notify.php','http://','1',1);
/*!40000 ALTER TABLE `web_pay_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_pay_goods`
--

LOCK TABLES `web_pay_goods` WRITE;
/*!40000 ALTER TABLE `web_pay_goods` DISABLE KEYS */;
INSERT INTO `web_pay_goods` VALUES (5,'金币',1,1,'人民币',100,0,0,NULL,1,10001,1,0,'com.globIcon.10000',1,NULL),(6,'金币',3,1,'人民币',300,0,0,NULL,1,10002,0,1,'com.globIcon.36000',2,NULL),(7,'金币',9,1,'人民币',800,0,0,NULL,1,10003,0,0,'com.globIcon.94000',3,NULL),(8,'金币',14,1,'软妹币',1200,0,0,NULL,1,10004,0,1,'com.globIcon.142000',4,NULL),(9,'金币',21,1,'人民币',1800,0,0,NULL,1,10005,0,1,'com.globIcon.216000',5,NULL),(10,'金币',33,1,'人民币',2800,0,0,NULL,1,10006,0,0,'com.globIcon.332000',6,NULL),(11,'金币',59,1,'软妹币',5000,0,0,NULL,1,10007,0,0,'com.globIcon.596000',7,NULL),(12,'金币',129,1,'软妹币',10800,0,0,NULL,1,10008,0,0,'com.globIcon.1292000',8,NULL),(13,'钻石',1,2,'人民币',100,0,0,NULL,1,10009,0,0,'com.coupon.1',1,NULL),(14,'钻石',4,2,'人民币',300,0,0,NULL,1,10010,0,0,'com.coupon.4',2,NULL),(15,'钻石',11,2,'人民币',800,0,0,NULL,1,10011,0,0,'com.coupon.11',3,NULL),(16,'钻石',17,2,'人民币',1200,0,0,NULL,1,10012,0,0,'com.coupon.17',4,NULL),(17,'钻石',26,2,'人民币',1800,0,0,NULL,1,10013,0,0,'com.coupon.26',5,NULL),(18,'钻石',40,2,'人民币',2800,0,0,NULL,1,10014,0,0,'com.coupon.40',6,NULL),(19,'钻石',75,2,'人民币',5000,0,0,NULL,1,10015,0,0,'com.coupon.75',7,NULL),(20,'钻石',162,2,'人民币',10800,0,0,NULL,1,10016,0,0,'com.coupon.162',8,NULL),(21,'钻石',1,4,'金币',2000000,1,0,1504234004,0,10017,1,0,'',1,NULL),(22,'十元话费券',1,4,'金币',22000000,1,0,1504237130,0,10018,1,0,'',2,NULL),(23,'50元话费券',1,4,'金币',102000000,1,0,1504237202,0,10019,1,0,'',3,NULL),(24,'100元话费券',1,4,'金币',200000000,1,0,1504237230,0,10020,1,0,'',4,NULL),(25,'小米移动电源',1,4,'金币',238000000,1,0,1504237263,0,10021,1,0,'',5,NULL),(26,'小米平衡车',1,4,'金币',NULL,1,0,1504237298,0,10022,1,0,'',6,NULL),(27,'ipad',1,4,'金币',NULL,1,0,1504237342,0,10023,1,0,'',7,NULL),(28,'iphone7',1,4,'金币',NULL,1,0,1504237378,0,10024,1,0,'',8,NULL);
/*!40000 ALTER TABLE `web_pay_goods` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_pay_orders`
--

LOCK TABLES `web_pay_orders` WRITE;
/*!40000 ALTER TABLE `web_pay_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_pay_orders` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_phone_code`
--

LOCK TABLES `web_phone_code` WRITE;
/*!40000 ALTER TABLE `web_phone_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_phone_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_recharge_commission`
--

DROP TABLE IF EXISTS `web_recharge_commission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_recharge_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recharge_userid` int(11) DEFAULT NULL COMMENT '充值玩家ID',--赢家id
  `recharge_name` varchar(50) DEFAULT NULL COMMENT '充值名字',
  `recharge_order_sn` varchar(50) DEFAULT NULL COMMENT '充值订单号',--statistics_firecoinchange id
  `recharge_amount` int(11) DEFAULT NULL COMMENT '充值金额',--分销总金额
  `agent_level` int(11) DEFAULT NULL COMMENT '代理等级',
  `agent_username` varchar(50) DEFAULT NULL COMMENT '代理手机号码',
  `agent_commission` int(11) DEFAULT NULL COMMENT '分佣数量',--上级链获得佣金
  `agent_userid` int(11) DEFAULT NULL COMMENT '代理玩家ID',--代理链id
  `level` int(11) DEFAULT NULL COMMENT '分佣层级',
  `buy_type` int(11) DEFAULT NULL COMMENT '购买资源类型',
  `buy_num` int(11) DEFAULT NULL COMMENT '购买数量',
  `time` int(11) DEFAULT NULL COMMENT '分佣时间',
  `commission_type` tinyint(1) NOT NULL comment '1充值分销 2对战赢家分销',
  PRIMARY KEY (`id`),
  KEY `recharge_userid` (`recharge_userid`),
  KEY `agent_userid` (`agent_userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_battle_commission`
--

LOCK TABLES `web_battle_commission` WRITE;
/*!40000 ALTER TABLE `web_battle_commission` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_battle_commission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `web_server_black`
--

--
-- Table structure for table `web_battle_commission`
--

DROP TABLE IF EXISTS `web_battle_commission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `web_battle_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `win_userid` int(11) DEFAULT NULL COMMENT '赢家ID',
  -- `recharge_name` varchar(50) DEFAULT NULL COMMENT '充值名字',
  `recharge_order_sn` varchar(50) DEFAULT NULL COMMENT '充值订单号',
  `commission_amount` int(11) DEFAULT NULL COMMENT '分销金额',
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
-- Dumping data for table `web_battle_commission`
--

LOCK TABLES `web_battle_commission` WRITE;
/*!40000 ALTER TABLE `web_battle_commission` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_battle_commission` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_server_black`
--

LOCK TABLES `web_server_black` WRITE;
/*!40000 ALTER TABLE `web_server_black` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_server_black` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_server_info`
--

LOCK TABLES `web_server_info` WRITE;
/*!40000 ALTER TABLE `web_server_info` DISABLE KEYS */;
INSERT INTO `web_server_info` VALUES (1,0,1);
/*!40000 ALTER TABLE `web_server_info` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_server_white`
--

LOCK TABLES `web_server_white` WRITE;
/*!40000 ALTER TABLE `web_server_white` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_server_white` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_share_code`
--

LOCK TABLES `web_share_code` WRITE;
/*!40000 ALTER TABLE `web_share_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_share_code` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_share_record`
--

LOCK TABLES `web_share_record` WRITE;
/*!40000 ALTER TABLE `web_share_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_share_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_sign_config`
--

LOCK TABLES `web_sign_config` WRITE;
/*!40000 ALTER TABLE `web_sign_config` DISABLE KEYS */;
INSERT INTO `web_sign_config` VALUES (1,1,'金币',1,1,1),(2,2,'金币',2,1,2),(3,3,'房卡',2,1,3),(4,4,'金币',3,1,4),(5,5,'金币',3,1,5),(6,6,'房卡',5,1,6);
/*!40000 ALTER TABLE `web_sign_config` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_sign_record`
--

LOCK TABLES `web_sign_record` WRITE;
/*!40000 ALTER TABLE `web_sign_record` DISABLE KEYS */;
INSERT INTO `web_sign_record` VALUES (1,'金币',1,1,1,1543486579,'LXW100',119000,1,0,1),(2,'金币',1,1,1,1543486592,'CCCCCCCCCC',119005,1,0,1);
/*!40000 ALTER TABLE `web_sign_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_socket_config`
--

LOCK TABLES `web_socket_config` WRITE;
/*!40000 ALTER TABLE `web_socket_config` DISABLE KEYS */;
INSERT INTO `web_socket_config` VALUES (1,'172.18.194.16',4015);
/*!40000 ALTER TABLE `web_socket_config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_support_record`
--

LOCK TABLES `web_support_record` WRITE;
/*!40000 ALTER TABLE `web_support_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_support_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_turntable_config`
--

LOCK TABLES `web_turntable_config` WRITE;
/*!40000 ALTER TABLE `web_turntable_config` DISABLE KEYS */;
INSERT INTO `web_turntable_config` VALUES (1,'金币',1,20,1),(2,'金币',2,10,1),(3,'金币',4,7,1),(4,'金币',3,6,1),(5,'金币',3,5,1),(6,'金币',5,4,1),(7,'金币',58,1,1),(8,'无',0,47,0);
/*!40000 ALTER TABLE `web_turntable_config` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_turntable_record`
--

LOCK TABLES `web_turntable_record` WRITE;
/*!40000 ALTER TABLE `web_turntable_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_turntable_record` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_vip_room`
--

LOCK TABLES `web_vip_room` WRITE;
/*!40000 ALTER TABLE `web_vip_room` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_vip_room` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_article`
--

LOCK TABLES `web_wechat_article` WRITE;
/*!40000 ALTER TABLE `web_wechat_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_article` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_feedback`
--

LOCK TABLES `web_wechat_feedback` WRITE;
/*!40000 ALTER TABLE `web_wechat_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_feedback` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_keywords`
--

LOCK TABLES `web_wechat_keywords` WRITE;
/*!40000 ALTER TABLE `web_wechat_keywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_keywords` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_kf`
--

LOCK TABLES `web_wechat_kf` WRITE;
/*!40000 ALTER TABLE `web_wechat_kf` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_kf` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_material`
--

LOCK TABLES `web_wechat_material` WRITE;
/*!40000 ALTER TABLE `web_wechat_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_material` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_menu`
--

LOCK TABLES `web_wechat_menu` WRITE;
/*!40000 ALTER TABLE `web_wechat_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_menu` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `web_wechat_subscribe`
--

LOCK TABLES `web_wechat_subscribe` WRITE;
/*!40000 ALTER TABLE `web_wechat_subscribe` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_subscribe` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Dumping data for table `web_wechat_user`
--

LOCK TABLES `web_wechat_user` WRITE;
/*!40000 ALTER TABLE `web_wechat_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_wechat_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-29 18:33:23
