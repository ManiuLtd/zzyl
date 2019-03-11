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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendsGroupAccounts`
--

LOCK TABLES `friendsGroupAccounts` WRITE;
/*!40000 ALTER TABLE `friendsGroupAccounts` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendsGroupDeskListCost`
--

LOCK TABLES `friendsGroupDeskListCost` WRITE;
/*!40000 ALTER TABLE `friendsGroupDeskListCost` DISABLE KEYS */;
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
  `is_Recommend` tinyint(1) DEFAULT '0' COMMENT '是否热门',
  PRIMARY KEY (`gameID`),
  KEY `is_Recommend` (`is_Recommend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gameBaseInfo`
--

LOCK TABLES `gameBaseInfo` WRITE;
/*!40000 ALTER TABLE `gameBaseInfo` DISABLE KEYS */;
INSERT INTO `gameBaseInfo` VALUES (10000900,'豹子王','10000900.DLL',180,180,0,0),(10001000,'奔驰宝马','10001000.DLL',180,180,0,0),(11000100,'飞禽走兽','11000100.DLL',180,180,0,0),(11100200,'百家乐','11100200.DLL',180,180,0,1),(11100604,'牌九','11100604.DLL',4,4,0,0),(12101105,'炸金花','12101105.DLL',5,5,1,0),(20170405,'21点','20170405.DLL',4,4,0,0),(30000004,'跑得快','30000004.DLL',4,4,0,0),(30000200,'百人牛牛','30000200.DLL',180,180,0,0),(30000404,'十三水','30000404.DLL',5,5,0,1),(30100008,'牛牛','30100008.DLL',8,8,1,0),(30100108,'三公','30100108.DLL',8,8,1,0),(36610103,'斗地主','36610103.DLL',3,3,0,0),(37550604,'小牌九','37550604.DLL',6,6,0,0),(40000100,'龙虎斗','40000100.DLL',180,180,0,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otherConfig`
--

LOCK TABLES `otherConfig` WRITE;
/*!40000 ALTER TABLE `otherConfig` DISABLE KEYS */;
INSERT INTO `otherConfig` VALUES ('bankMinSaveMoney','100','银行保存最低金币数'),('bankMinTakeMoney','100','银行最低取钱数'),('bankMinTransfer','100','银行最低转账数'),('bankSaveMoneyMuti','10','银行存钱必须是这个倍数'),('bankTakeMoneyMuti','10','银行取钱必须是这个倍数'),('bankTransferMuti','10','银行转账必须是这个数的倍数'),('buyingDeskCount','300','购买房卡次数'),('friendRewardMoney','100','用户打赏金币'),('friendRewardMoneyCount','5','用户打赏金币次数'),('friendTakeRewardMoneyCount','10','用户打赏金币次数'),('ftpIP','192.168.0.79','ftp服务器ip'),('ftpPasswd','jfik&*5jhfj','ftp服务器密码'),('ftpUser','zzylftp','ftp服务器账号'),('groupAllAlterNameCount','3','俱乐部更新ID总次数限制'),('groupCreateCount','5','俱乐部创建限制'),('groupEveAlterNameCount','1','俱乐部每日更新ID次数限制'),('groupJoinCount','10','加入俱乐部限制'),('groupManageRoomCount','5','俱乐部管理房间人数限制'),('groupMemberCount','150','俱乐部人数限制'),('groupRoomCount','1','俱乐部房间限制'),('groupTransferCount','3','俱乐部最多能授权管理员数量'),('logonGiveMoneyEveryDay','0','每日登录赠送金币数'),('regByPhoneSendMoney','10000','手机注册额外送金币'),('registerGiveJewels','100','注册送房卡'),('registerGiveMoney','5001','注册送金币'),('sendGiftMinJewels','100000000000','赠送最低房卡数'),('sendGiftMinMoney','10000000000000','赠送最低金币数'),('sendGiftMyLimitJewels','100000000000','赠送要求自己最低房卡数'),('sendGiftMyLimitMoney','1000000000000','赠送要求自己最低金币数'),('sendGiftRate','0.1','赠送系统扣除的税率'),('sendHornCostJewels','5','发送世界广播消耗房卡'),('supportMinLimitMoney','50','低于多少金币领取救济金'),('supportMoneyCount','2','每次领取救济金的金币数量每次领取救济金的金币数量'),('supportTimesEveryDay','15','每天领取救济金次数'),('useMagicExpressCostMoney','3','发送魔法表情消耗金币数');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privateDeskConfig`
--

LOCK TABLES `privateDeskConfig` WRITE;
/*!40000 ALTER TABLE `privateDeskConfig` DISABLE KEYS */;
INSERT INTO `privateDeskConfig` VALUES (30100008,6,2,1,0,0,0,0),(30100008,12,2,1,0,0,0,0),(30100108,6,2,1,0,0,0,0),(30100108,12,2,1,0,0,0,0),(30000004,6,2,1,0,0,0,0),(30000004,12,2,1,0,0,0,0),(12101105,6,2,1,0,0,0,0),(12101105,12,2,1,0,0,0,0),(30000404,6,2,1,0,0,0,0),(30000404,12,2,1,0,0,0,0),(11100604,6,2,1,0,0,0,0),(11100604,12,2,1,0,0,0,0),(37550604,6,2,1,0,0,0,0),(37550604,12,2,1,0,0,0,0),(20170405,6,2,1,0,0,0,0),(20170405,12,2,1,0,0,0,0),(36610103,6,2,1,0,0,0,0),(36610103,12,2,1,0,0,0,0),(37550604,6,3,1,30,0,0,0),(37550604,12,3,1,60,0,0,0),(30100108,6,3,1,30,0,0,0),(30100108,12,3,1,60,0,0,0),(30000004,6,3,1,30,0,0,0),(30000004,12,3,1,60,0,0,0),(12101105,6,3,1,30,0,0,0),(12101105,12,3,1,60,0,0,0),(30000404,6,3,1,30,0,0,0),(30000404,12,3,1,60,0,0,0),(11100604,6,3,1,30,0,0,0),(11100604,12,3,1,60,0,0,0),(20170405,6,3,1,30,0,0,0),(20170405,12,3,1,60,0,0,0),(30100008,6,3,1,30,0,0,0),(30100008,12,3,1,60,0,0,0),(36610103,6,3,1,30,0,0,0),(36610103,12,3,1,60,0,0,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redisBaseInfo`
--

LOCK TABLES `redisBaseInfo` WRITE;
/*!40000 ALTER TABLE `redisBaseInfo` DISABLE KEYS */;
INSERT INTO `redisBaseInfo` VALUES (1,'192.168.0.64',6380,'Yy304949708'),(2,'192.168.0.64',6381,'Yy304949708');
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
  `updateTime` int(11) DEFAULT '0',
  PRIMARY KEY (`roomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rewardsPool`
--

LOCK TABLES `rewardsPool` WRITE;
/*!40000 ALTER TABLE `rewardsPool` DISABLE KEYS */;
INSERT INTO `rewardsPool` VALUES (1,0,-447350,64040,0,0,0,0,0,0,0,0,'','',NULL),(2,0,0,28880,0,0,0,0,0,0,0,0,'','',NULL),(3,0,-1540500,82260,0,0,0,0,0,0,0,0,'','',NULL),(4,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(5,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(6,0,-1500,300,0,0,0,0,0,0,0,0,'','',NULL),(7,0,0,0,0,0,0,0,0,0,0,0,'','',1547272841),(8,0,0,0,0,0,0,0,0,0,0,0,'','',1547273391),(9,0,0,0,0,0,0,0,0,0,0,0,'','',1547271857),(10,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(11,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(12,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(13,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(14,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(15,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(16,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(17,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(18,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(19,0,9,9,0,0,0,0,0,0,0,0,'','',NULL),(20,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(21,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(22,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(23,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(24,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(25,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(26,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(27,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(28,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(29,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(30,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(31,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(32,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(33,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(34,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(35,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(36,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(37,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(38,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(39,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(40,0,0,0,0,0,0,0,0,0,0,0,'','',1547272500),(41,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(42,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(43,0,0,0,0,0,0,0,0,0,0,0,'','',1547274128),(44,0,0,0,0,0,0,0,0,0,0,0,'','',1547274122),(45,0,0,0,0,0,0,0,0,0,0,0,'','',1547274115),(46,0,0,0,0,0,0,0,0,0,0,0,'','',1547274109),(47,0,0,0,0,0,0,0,0,0,0,0,'','',1547274139),(48,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(49,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(50,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(51,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(52,0,0,0,0,0,0,0,0,0,0,0,'','',1547268860),(53,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(54,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(55,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(56,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(57,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(58,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(59,0,0,0,0,0,0,0,0,0,0,0,'','',1547272513),(60,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(61,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(62,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(63,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(64,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(65,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(66,0,0,0,0,0,0,0,0,0,0,0,'','',1547260430),(67,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(68,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(69,0,0,0,0,0,0,0,0,0,0,0,'','',1547260422),(70,0,0,0,0,0,0,0,0,0,0,0,'','',1547273582),(71,0,0,0,0,0,0,0,0,0,0,0,'','',NULL),(72,0,0,0,0,0,0,0,0,0,0,0,'','',NULL);
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
  `robotLessPoint` int(11) DEFAULT '0' COMMENT '机器人携带的最少钱数量',
  `robotMaxPoint` int(11) DEFAULT '0' COMMENT '机器人携带最大钱数量',
  `robotCount` int(11) DEFAULT '0' COMMENT '每桌最多的机器人数量',
  `status` tinyint(2) DEFAULT '0' COMMENT '0：未启动，1：已经启动',
  `currPeopleCount` int(11) DEFAULT '0' COMMENT '当前房间人数',
  `level` tinyint(2) DEFAULT '0' COMMENT '房间级别:0 房卡场 1 初级场 2 中级场 3 高级场',
  `is_Recommend` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  PRIMARY KEY (`roomID`),
  KEY `is_Recommend` (`is_Recommend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roomBaseInfo`
--

LOCK TABLES `roomBaseInfo` WRITE;
/*!40000 ALTER TABLE `roomBaseInfo` DISABLE KEYS */;
INSERT INTO `roomBaseInfo` VALUES (1,30000200,'百人牛牛初级场','192.168.0.64',4001,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,1),(2,30000200,'百人牛牛中级场','192.168.0.64',4002,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,10000,0,0,0,0,2,1),(3,30000200,'百人牛牛高级场','192.168.0.64',4003,'zzyl',0,1,1,180,0,0,15,0,'经典玩法',0,20000,0,0,0,0,3,1),(4,11100200,'百家乐初级场','192.168.0.64',4004,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,1),(5,11100200,'百家乐中级场','192.168.0.64',4005,'zzyl',0,1,1,180,0,0,2,0,'经典玩法',0,10000,0,0,0,0,2,1),(6,11100200,'百家乐高级场','192.168.0.64',4006,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,20000,0,0,0,0,3,1),(7,10000900,'豹子王初级场','192.168.0.64',4007,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,0),(8,10000900,'豹子王中级场','192.168.0.64',4008,'zzyl',0,1,1,180,0,0,2,0,'经典玩法',0,10000,0,0,0,0,2,1),(9,10000900,'豹子王高级场','192.168.0.64',4009,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,20000,0,0,0,0,3,0),(10,10001000,'奔驰宝马初级场','192.168.0.64',4010,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,1),(11,10001000,'奔驰宝马中级场','192.168.0.64',4011,'zzyl',0,1,1,180,0,0,2,0,'经典玩法',0,10000,0,0,0,0,2,1),(12,10001000,'奔驰宝马高级场','192.168.0.64',4012,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,20000,0,0,0,0,3,1),(13,11000100,'飞禽走兽初级场','192.168.0.64',4013,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,1),(14,11000100,'飞禽走兽中级场','192.168.0.64',4014,'zzyl',0,1,1,180,0,0,2,0,'经典玩法',0,10000,0,0,0,0,2,1),(15,11000100,'飞禽走兽高级场','192.168.0.64',4000,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,20000,0,0,0,0,3,1),(16,40000100,'龙虎斗初级场','192.168.0.64',4016,'zzyl',0,1,1,180,0,0,1,0,'经典玩法',0,5000,0,0,0,0,1,1),(17,40000100,'龙虎斗中级场','192.168.0.64',4017,'zzyl',0,1,1,180,0,0,2,0,'经典玩法',0,10000,0,0,0,0,2,1),(18,40000100,'龙虎斗高级场','192.168.0.64',4018,'zzyl',0,1,1,180,0,0,5,0,'经典玩法',0,20000,0,0,0,0,3,1),(19,30100008,'牛牛金币房','192.168.0.64',4019,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,4,0,0,0,1),(20,30100008,'牛牛VIP房','192.168.0.64',4020,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,4,0,0,0,1),(21,30100008,'牛牛试炼场','192.168.0.64',4021,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,4,0,0,0,1),(22,30100008,'牛牛初级场','192.168.0.64',4022,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,4,0,0,0,1),(23,30100008,'牛牛中级场','192.168.0.64',4023,'zzyl',0,0,200,200,20000,0,500,0,'经典玩法',0,10000,0,4,0,0,0,1),(24,30100008,'牛牛高级场','192.168.0.64',4024,'zzyl',0,0,200,200,50000,0,1000,0,'经典玩法',0,20000,0,4,0,0,0,1),(25,30100108,'三公金币房','192.168.0.64',4025,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,4,0,0,0,1),(26,30100108,'三公VIP房','192.168.0.64',4026,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,4,0,0,0,1),(27,30100108,'三公试炼场','192.168.0.64',4027,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,4,0,0,0,1),(28,30100108,'三公初级场','192.168.0.64',4028,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,4,0,0,0,1),(29,30100108,'三公中级场','192.168.0.64',4029,'zzyl',0,0,200,200,20000,0,500,0,'经典玩法',0,10000,0,4,0,0,0,1),(30,30100108,'三公高级场','192.168.0.64',4030,'zzyl',0,0,200,200,50000,0,1000,0,'经典玩法',0,20000,0,4,0,0,0,1),(31,30000004,'跑得快金币房','192.168.0.64',4031,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(32,30000004,'跑得快VIP房','192.168.0.64',4032,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(33,30000004,'跑得快试炼场','192.168.0.64',4033,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,2,0,0,0,1),(34,30000004,'跑得快初级场','192.168.0.64',4034,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,1),(35,30000004,'跑得快中级场','192.168.0.64',4035,'zzyl',0,0,200,200,20000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,1),(36,30000004,'跑得快高级场','192.168.0.64',4036,'zzyl',0,0,200,200,50000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,1),(37,12101105,'炸金花金币房','192.168.0.64',4037,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(38,12101105,'炸金花VIP房','192.168.0.64',4038,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(39,12101105,'炸金花试炼场','192.168.0.64',4039,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,2,0,0,0,1),(40,12101105,'炸金花初级场','192.168.0.64',4040,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,0),(41,12101105,'炸金花中级场','192.168.0.64',4041,'zzyl',0,0,200,200,30000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,1),(42,12101105,'炸金花高级场','192.168.0.64',4042,'zzyl',0,0,200,200,80000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,1),(43,30000404,'十三水金币房','192.168.0.64',4043,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,0),(44,30000404,'十三水VIP房','192.168.0.64',4044,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,0),(45,30000404,'十三水试炼场','192.168.0.64',4045,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,2,0,0,0,0),(46,30000404,'十三水初级场','192.168.0.64',4046,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,0),(47,30000404,'十三水中级场','192.168.0.64',4047,'zzyl',0,0,200,200,20000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,1),(48,30000404,'十三水高级场','192.168.0.64',4048,'zzyl',0,0,200,200,50000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,1),(49,11100604,'牌九金币房','192.168.0.64',4049,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(50,11100604,'牌九VIP房','192.168.0.64',4050,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(51,11100604,'牌九试炼场','192.168.0.64',4051,'zzyl',0,0,200,200,5000,0,10,0,'经典玩法',0,5000,0,2,0,0,0,1),(52,11100604,'牌九初级场','192.168.0.64',4052,'zzyl',0,0,200,200,20000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,0),(53,11100604,'牌九中级场','192.168.0.64',4053,'zzyl',0,0,200,200,100000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,1),(54,11100604,'牌九高级场','192.168.0.64',4054,'zzyl',0,0,200,200,200000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,1),(55,20170405,'21点金币房','192.168.0.64',4055,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(56,20170405,'21点VIP房','192.168.0.64',4056,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(57,20170405,'21点试炼场','192.168.0.64',4057,'zzyl',0,0,200,200,500,0,10,0,'经典玩法',0,5000,0,2,0,0,0,1),(58,20170405,'21点初级场','192.168.0.64',4058,'zzyl',0,0,200,200,5000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,1),(59,20170405,'21点中级场','192.168.0.64',4059,'zzyl',0,0,200,200,30000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,0),(60,20170405,'21点高级场','192.168.0.64',4060,'zzyl',0,0,200,200,50000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,1),(61,37550604,'小牌九金币房','192.168.0.64',4061,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(62,37550604,'小牌九VIP房','192.168.0.64',4062,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,2,0,0,0,1),(63,37550604,'小牌九试炼场','192.168.0.64',4063,'zzyl',0,0,200,200,5000,0,10,0,'经典玩法',0,5000,0,2,0,0,0,1),(64,37550604,'小牌九初级场','192.168.0.64',4064,'zzyl',0,0,200,200,20000,0,100,0,'经典玩法',0,5000,0,2,0,0,0,1),(65,37550604,'小牌九中级场','192.168.0.64',4065,'zzyl',0,0,200,200,100000,0,500,0,'经典玩法',0,10000,0,2,0,0,0,1),(66,37550604,'小牌九高级场','192.168.0.64',4066,'zzyl',0,0,200,200,200000,0,1000,0,'经典玩法',0,20000,0,2,0,0,0,0),(67,36610103,'斗地主金币房','192.168.0.64',4067,'zzyl',2,0,200,200,0,0,1,0,'经典玩法',0,10000,0,1,0,0,0,1),(68,36610103,'斗地主VIP房','192.168.0.64',4068,'zzyl',3,0,200,200,0,0,1,0,'经典玩法',0,10000,0,1,0,0,0,1),(69,36610103,'斗地主试炼场','192.168.0.64',4069,'zzyl',0,0,200,200,100,0,10,0,'经典玩法',0,5000,0,1,0,0,0,0),(70,36610103,'斗地主初级场','192.168.0.64',4070,'zzyl',0,0,200,200,2000,0,100,0,'经典玩法',0,5000,0,1,0,0,0,1),(71,36610103,'斗地主中级场','192.168.0.64',4071,'zzyl',0,0,200,200,10000,0,500,0,'经典玩法',0,10000,0,1,0,0,0,0),(72,36610103,'斗地主高级场','192.168.0.64',4072,'zzyl',0,0,200,200,20000,0,1000,0,'经典玩法',0,20000,0,1,0,0,0,1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_firecoinchange`
--

LOCK TABLES `statistics_firecoinchange` WRITE;
/*!40000 ALTER TABLE `statistics_firecoinchange` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_gamerecordinfo`
--

LOCK TABLES `statistics_gamerecordinfo` WRITE;
/*!40000 ALTER TABLE `statistics_gamerecordinfo` DISABLE KEYS */;
INSERT INTO `statistics_gamerecordinfo` VALUES (1,'804323',1,19,1547292498,1547292514,1547292526,'118039,118040,'),(2,'804323',1,19,1547292498,1547292531,1547292542,'118039,118040,'),(3,'804323',1,19,1547292498,1547292547,1547292557,'118039,118040,'),(4,'804323',1,19,1547292498,1547292562,1547292572,'118039,118040,'),(5,'804323',1,19,1547292498,1547292576,1547292585,'118039,118040,'),(6,'804323',1,19,1547292498,1547292589,1547292601,'118039,-320|118040,311|#118039,0|118040,9|#118039,2|118040,4|#'),(7,'804323',1,19,1547292498,1547292589,1547292599,'118039,118040,');
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_jewelschange`
--

LOCK TABLES `statistics_jewelschange` WRITE;
/*!40000 ALTER TABLE `statistics_jewelschange` DISABLE KEYS */;
INSERT INTO `statistics_jewelschange` VALUES (1,118001,1547217931,100,100,10,0,0,0,0),(2,118002,1547217946,100,100,10,0,0,0,0),(3,118003,1547266458,100,100,10,0,0,0,0),(4,118004,1547266458,100,100,10,0,0,0,0),(5,118005,1547266459,100,100,10,0,0,0,0),(6,118006,1547266459,100,100,10,0,0,0,0),(7,118007,1547266460,100,100,10,0,0,0,0),(8,118008,1547266460,100,100,10,0,0,0,0),(9,118009,1547266461,100,100,10,0,0,0,0),(10,118010,1547266461,100,100,10,0,0,0,0),(11,118011,1547266462,100,100,10,0,0,0,0),(12,118012,1547266462,100,100,10,0,0,0,0),(13,118013,1547266463,100,100,10,0,0,0,0),(14,118014,1547266463,100,100,10,0,0,0,0),(15,118015,1547266464,100,100,10,0,0,0,0),(16,118016,1547266464,100,100,10,0,0,0,0),(17,118017,1547266465,100,100,10,0,0,0,0),(18,118018,1547266465,100,100,10,0,0,0,0),(19,118019,1547266466,100,100,10,0,0,0,0),(20,118020,1547266466,100,100,10,0,0,0,0),(21,118021,1547266467,100,100,10,0,0,0,0),(22,118022,1547266467,100,100,10,0,0,0,0),(23,118023,1547266468,100,100,10,0,0,0,0),(24,118024,1547271172,100,100,10,0,0,0,0),(25,118025,1547271710,100,100,10,0,0,0,0),(26,118026,1547272933,100,100,10,0,0,0,0),(27,118027,1547276025,100,100,10,0,0,0,0),(28,118028,1547279107,100,100,10,0,0,0,0),(29,118029,1547286320,100,100,10,0,0,0,0),(30,118030,1547289914,100,100,10,0,0,0,0),(31,118031,1547289914,100,100,10,0,0,0,0),(32,118032,1547289914,100,100,10,0,0,0,0),(33,118033,1547289914,100,100,10,0,0,0,0),(34,118034,1547289914,100,100,10,0,0,0,0),(35,118035,1547289914,100,100,10,0,0,0,0),(36,118036,1547289914,100,100,10,0,0,0,0),(37,118037,1547289914,100,100,10,0,0,0,0),(38,118038,1547289914,100,100,10,0,0,0,0),(39,118039,1547292414,100,100,10,0,0,0,0),(40,118040,1547292442,100,100,10,0,0,0,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_logonandlogout`
--

LOCK TABLES `statistics_logonandlogout` WRITE;
/*!40000 ALTER TABLE `statistics_logonandlogout` DISABLE KEYS */;
INSERT INTO `statistics_logonandlogout` VALUES (1,118001,1,1547217931,'192.168.0.86',3,'00-E0-4C-ED-4C-9B'),(2,118002,1,1547217946,'192.168.0.64',3,'FC-AA-14-74-65-32'),(3,118001,2,1547218148,'192.168.0.86',0,''),(4,118001,1,1547218263,'192.168.0.86',3,'00-E0-4C-ED-4C-9B'),(5,118001,2,1547218908,'192.168.0.86',0,''),(6,118002,2,1547218931,'192.168.0.64',0,''),(7,118003,1,1547266458,'192.168.0.64',2,'aa'),(8,118004,1,1547266458,'192.168.0.64',2,'aa'),(9,118005,1,1547266459,'192.168.0.64',2,'aa'),(10,118006,1,1547266459,'192.168.0.64',2,'aa'),(11,118007,1,1547266460,'192.168.0.64',2,'aa'),(12,118008,1,1547266460,'192.168.0.64',2,'aa'),(13,118009,1,1547266461,'192.168.0.64',2,'aa'),(14,118010,1,1547266461,'192.168.0.64',2,'aa'),(15,118011,1,1547266462,'192.168.0.64',2,'aa'),(16,118012,1,1547266462,'192.168.0.64',2,'aa'),(17,118013,1,1547266463,'192.168.0.64',2,'aa'),(18,118014,1,1547266463,'192.168.0.64',2,'aa'),(19,118015,1,1547266464,'192.168.0.64',2,'aa'),(20,118016,1,1547266464,'192.168.0.64',2,'aa'),(21,118017,1,1547266465,'192.168.0.64',2,'aa'),(22,118018,1,1547266465,'192.168.0.64',2,'aa'),(23,118019,1,1547266466,'192.168.0.64',2,'aa'),(24,118020,1,1547266466,'192.168.0.64',2,'aa'),(25,118021,1,1547266467,'192.168.0.64',2,'aa'),(26,118022,1,1547266467,'192.168.0.64',2,'aa'),(27,118023,1,1547266468,'192.168.0.64',2,'aa'),(28,118003,2,1547266469,'192.168.0.64',0,''),(29,118004,2,1547266469,'192.168.0.64',0,''),(30,118005,2,1547266469,'192.168.0.64',0,''),(31,118006,2,1547266469,'192.168.0.64',0,''),(32,118007,2,1547266469,'192.168.0.64',0,''),(33,118008,2,1547266469,'192.168.0.64',0,''),(34,118009,2,1547266469,'192.168.0.64',0,''),(35,118010,2,1547266469,'192.168.0.64',0,''),(36,118011,2,1547266469,'192.168.0.64',0,''),(37,118012,2,1547266469,'192.168.0.64',0,''),(38,118013,2,1547266469,'192.168.0.64',0,''),(39,118014,2,1547266469,'192.168.0.64',0,''),(40,118015,2,1547266469,'192.168.0.64',0,''),(41,118016,2,1547266469,'192.168.0.64',0,''),(42,118017,2,1547266469,'192.168.0.64',0,''),(43,118018,2,1547266469,'192.168.0.64',0,''),(44,118019,2,1547266469,'192.168.0.64',0,''),(45,118020,2,1547266469,'192.168.0.64',0,''),(46,118021,2,1547266469,'192.168.0.64',0,''),(47,118022,2,1547266469,'192.168.0.64',0,''),(48,118023,2,1547266469,'192.168.0.64',0,''),(49,118003,1,1547267072,'192.168.0.64',2,'aa'),(50,118004,1,1547267073,'192.168.0.64',2,'aa'),(51,118005,1,1547267074,'192.168.0.64',2,'aa'),(52,118006,1,1547267075,'192.168.0.64',2,'aa'),(53,118007,1,1547267076,'192.168.0.64',2,'aa'),(54,118003,2,1547267077,'192.168.0.64',0,''),(55,118004,2,1547267077,'192.168.0.64',0,''),(56,118005,2,1547267077,'192.168.0.64',0,''),(57,118006,2,1547267077,'192.168.0.64',0,''),(58,118007,2,1547267077,'192.168.0.64',0,''),(59,118003,1,1547267080,'192.168.0.64',2,'aa'),(60,118004,1,1547267081,'192.168.0.64',2,'aa'),(61,118003,2,1547267082,'192.168.0.64',0,''),(62,118004,2,1547267082,'192.168.0.64',0,''),(63,118024,1,1547271173,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(64,118025,1,1547271710,'192.168.0.60',3,'48-8A-D2-53-AE-BE'),(65,118025,2,1547271720,'192.168.0.60',0,''),(66,118025,1,1547271724,'192.168.0.60',3,'48-8A-D2-53-AE-BE'),(67,118025,2,1547271730,'192.168.0.60',0,''),(68,118002,1,1547272312,'192.168.0.64',3,'FC-AA-14-74-65-32'),(69,118002,3,1547272317,'192.168.0.64',13,'FC-AA-14-74-65-32'),(70,118002,3,1547272599,'192.168.0.64',4,'FC-AA-14-74-65-32'),(71,118024,3,1547272770,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(72,118026,1,1547272934,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(73,118026,2,1547272947,'192.168.0.68',0,''),(74,118026,1,1547272953,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(75,118026,3,1547272960,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(76,118002,3,1547273220,'192.168.0.64',1,'FC-AA-14-74-65-32'),(77,118002,3,1547273251,'192.168.0.64',2,'FC-AA-14-74-65-32'),(78,118024,3,1547273646,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(79,118026,3,1547273648,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(80,118026,2,1547273741,'192.168.0.68',0,''),(81,118024,2,1547273743,'192.168.0.68',0,''),(82,118026,1,1547273750,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(83,118026,3,1547273750,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(84,118024,1,1547273755,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(85,118024,3,1547273762,'192.168.0.68',2,'40-8D-5C-1A-02-09'),(86,118024,3,1547273771,'192.168.0.68',1,'40-8D-5C-1A-02-09'),(87,118024,3,1547274262,'192.168.0.68',2,'40-8D-5C-1A-02-09'),(88,118026,3,1547274263,'192.168.0.68',2,'40-8D-5C-1A-02-09'),(89,118002,3,1547274325,'192.168.0.64',45,'FC-AA-14-74-65-32'),(90,118026,2,1547274501,'192.168.0.68',0,''),(91,118024,2,1547274501,'192.168.0.68',0,''),(92,118026,1,1547274501,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(93,118024,1,1547274501,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(94,118027,1,1547276025,'192.168.0.64',3,'FC-AA-14-74-65-32'),(95,118027,3,1547276030,'192.168.0.64',4,'FC-AA-14-74-65-32'),(96,118024,1,1547276654,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(97,118024,1,1547277795,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(98,118024,3,1547277799,'192.168.0.68',2,'40-8D-5C-1A-02-09'),(99,118024,2,1547277805,'192.168.0.68',0,''),(100,118024,1,1547278150,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(101,118024,3,1547278159,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(102,118028,1,1547279107,'192.168.0.64',3,'FC-AA-14-74-65-32'),(103,118028,2,1547279153,'192.168.0.64',0,''),(104,118024,1,1547281443,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(105,118024,3,1547281444,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(106,118024,3,1547281514,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(107,118024,2,1547281866,'192.168.0.68',0,''),(108,118024,1,1547281871,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(109,118024,3,1547281875,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(110,118024,3,1547282046,'192.168.0.68',6,'40-8D-5C-1A-02-09'),(111,118024,3,1547282184,'192.168.0.68',9,'40-8D-5C-1A-02-09'),(112,118024,2,1547282336,'192.168.0.68',0,''),(113,118024,1,1547282340,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(114,118024,2,1547282427,'192.168.0.68',0,''),(115,118024,1,1547282431,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(116,118024,3,1547282476,'192.168.0.68',9,'40-8D-5C-1A-02-09'),(117,118025,1,1547282512,'192.168.0.60',3,'48-8A-D2-53-AE-BE'),(118,118025,2,1547282527,'192.168.0.60',0,''),(119,118025,1,1547282568,'192.168.0.60',3,'48-8A-D2-53-AE-BE'),(120,118024,2,1547282584,'192.168.0.68',0,''),(121,118024,1,1547282584,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(122,118024,2,1547282732,'192.168.0.68',0,''),(123,118024,1,1547282740,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(124,118024,2,1547282762,'192.168.0.68',0,''),(125,118024,1,1547282767,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(126,118025,2,1547282771,'192.168.0.60',0,''),(127,118024,3,1547282772,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(128,118024,2,1547282789,'192.168.0.68',0,''),(129,118024,1,1547282794,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(130,118024,3,1547282795,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(131,118024,2,1547282876,'192.168.0.68',0,''),(132,118024,1,1547282876,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(133,118024,2,1547282998,'192.168.0.68',0,''),(134,118024,1,1547283003,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(135,118024,3,1547283008,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(136,118024,2,1547283062,'192.168.0.68',0,''),(137,118024,1,1547283063,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(138,118024,1,1547283722,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(139,118024,2,1547283730,'192.168.0.68',0,''),(140,118024,1,1547284046,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(141,118024,3,1547284050,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(142,118024,2,1547284167,'192.168.0.68',0,''),(143,118024,1,1547284182,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(144,118024,3,1547284182,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(145,118024,3,1547284878,'192.168.0.68',9,'40-8D-5C-1A-02-09'),(146,118024,3,1547284886,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(147,118024,2,1547284968,'192.168.0.68',0,''),(148,118024,1,1547284968,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(149,118024,2,1547285066,'192.168.0.68',0,''),(150,118024,1,1547285108,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(151,118024,3,1547285372,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(152,118024,2,1547285606,'192.168.0.68',0,''),(153,118024,1,1547285611,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(154,118024,3,1547285612,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(155,118024,2,1547285698,'192.168.0.68',0,''),(156,118024,1,1547285702,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(157,118024,3,1547285703,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(158,118024,2,1547285859,'192.168.0.68',0,''),(159,118024,1,1547285863,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(160,118024,3,1547285864,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(161,118029,1,1547286320,'192.168.0.64',3,'FC-AA-14-74-65-32'),(162,118024,2,1547286394,'192.168.0.68',0,''),(163,118024,1,1547286398,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(164,118024,2,1547286490,'192.168.0.68',0,''),(165,118024,1,1547286494,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(166,118024,2,1547286605,'192.168.0.68',0,''),(167,118024,1,1547286611,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(168,118024,3,1547286620,'192.168.0.68',3,'40-8D-5C-1A-02-09'),(169,118024,2,1547286632,'192.168.0.68',0,''),(170,118003,1,1547289909,'192.168.0.64',2,'aa'),(171,118004,1,1547289909,'192.168.0.64',2,'aa'),(172,118005,1,1547289909,'192.168.0.64',2,'aa'),(173,118006,1,1547289909,'192.168.0.64',2,'aa'),(174,118008,1,1547289909,'192.168.0.64',2,'aa'),(175,118007,1,1547289909,'192.168.0.64',2,'aa'),(176,118009,1,1547289909,'192.168.0.64',2,'aa'),(177,118010,1,1547289909,'192.168.0.64',2,'aa'),(178,118011,1,1547289909,'192.168.0.64',2,'aa'),(179,118012,1,1547289909,'192.168.0.64',2,'aa'),(180,118013,1,1547289909,'192.168.0.64',2,'aa'),(181,118014,1,1547289909,'192.168.0.64',2,'aa'),(182,118016,1,1547289909,'192.168.0.64',2,'aa'),(183,118017,1,1547289909,'192.168.0.64',2,'aa'),(184,118018,1,1547289909,'192.168.0.64',2,'aa'),(185,118019,1,1547289909,'192.168.0.64',2,'aa'),(186,118020,1,1547289909,'192.168.0.64',2,'aa'),(187,118021,1,1547289909,'192.168.0.64',2,'aa'),(188,118022,1,1547289909,'192.168.0.64',2,'aa'),(189,118023,1,1547289909,'192.168.0.64',2,'aa'),(190,118030,1,1547289914,'192.168.0.64',2,'aa'),(191,118031,1,1547289914,'192.168.0.64',2,'aa'),(192,118032,1,1547289914,'192.168.0.64',2,'aa'),(193,118033,1,1547289914,'192.168.0.64',2,'aa'),(194,118034,1,1547289914,'192.168.0.64',2,'aa'),(195,118035,1,1547289914,'192.168.0.64',2,'aa'),(196,118036,1,1547289914,'192.168.0.64',2,'aa'),(197,118037,1,1547289914,'192.168.0.64',2,'aa'),(198,118038,1,1547289914,'192.168.0.64',2,'aa'),(199,118007,2,1547289989,'192.168.0.64',0,''),(200,118008,2,1547289989,'192.168.0.64',0,''),(201,118009,2,1547289989,'192.168.0.64',0,''),(202,118010,2,1547289989,'192.168.0.64',0,''),(203,118011,2,1547289989,'192.168.0.64',0,''),(204,118012,2,1547289989,'192.168.0.64',0,''),(205,118013,2,1547289989,'192.168.0.64',0,''),(206,118014,2,1547289989,'192.168.0.64',0,''),(207,118006,2,1547289989,'192.168.0.64',0,''),(208,118016,2,1547289989,'192.168.0.64',0,''),(209,118017,2,1547289989,'192.168.0.64',0,''),(210,118018,2,1547289989,'192.168.0.64',0,''),(211,118019,2,1547289989,'192.168.0.64',0,''),(212,118020,2,1547289989,'192.168.0.64',0,''),(213,118021,2,1547289989,'192.168.0.64',0,''),(214,118022,2,1547289989,'192.168.0.64',0,''),(215,118023,2,1547289989,'192.168.0.64',0,''),(216,118030,2,1547289989,'192.168.0.64',0,''),(217,118031,2,1547289989,'192.168.0.64',0,''),(218,118032,2,1547289989,'192.168.0.64',0,''),(219,118033,2,1547289989,'192.168.0.64',0,''),(220,118034,2,1547289989,'192.168.0.64',0,''),(221,118035,2,1547289989,'192.168.0.64',0,''),(222,118036,2,1547289989,'192.168.0.64',0,''),(223,118037,2,1547289989,'192.168.0.64',0,''),(224,118038,2,1547289989,'192.168.0.64',0,''),(225,118003,1,1547290092,'',2,'aa'),(226,118005,1,1547290093,'192.168.0.64',2,'aa'),(227,118007,1,1547290094,'192.168.0.64',2,'aa'),(228,118008,1,1547290095,'192.168.0.64',2,'aa'),(229,118009,1,1547290095,'192.168.0.64',2,'aa'),(230,118010,1,1547290096,'192.168.0.64',2,'aa'),(231,118003,2,1547290096,'192.168.0.64',0,''),(232,118005,2,1547290096,'192.168.0.64',0,''),(233,118007,2,1547290096,'192.168.0.64',0,''),(234,118008,2,1547290096,'192.168.0.64',0,''),(235,118009,2,1547290096,'192.168.0.64',0,''),(236,118010,2,1547290096,'192.168.0.64',0,''),(237,118003,1,1547290215,'192.168.0.64',2,'aa'),(238,118004,1,1547290215,'192.168.0.64',2,'aa'),(239,118005,1,1547290215,'192.168.0.64',2,'aa'),(240,118006,1,1547290215,'192.168.0.64',2,'aa'),(241,118007,1,1547290215,'192.168.0.64',2,'aa'),(242,118008,1,1547290215,'192.168.0.64',2,'aa'),(243,118009,1,1547290215,'192.168.0.64',2,'aa'),(244,118010,1,1547290215,'192.168.0.64',2,'aa'),(245,118011,1,1547290215,'192.168.0.64',2,'aa'),(246,118012,1,1547290215,'192.168.0.64',2,'aa'),(247,118013,1,1547290215,'192.168.0.64',2,'aa'),(248,118014,1,1547290215,'192.168.0.64',2,'aa'),(249,118015,1,1547290215,'192.168.0.64',2,'aa'),(250,118016,1,1547290215,'192.168.0.64',2,'aa'),(251,118018,1,1547290215,'192.168.0.64',2,'aa'),(252,118019,1,1547290215,'192.168.0.64',2,'aa'),(253,118020,1,1547290215,'192.168.0.64',2,'aa'),(254,118021,1,1547290215,'192.168.0.64',2,'aa'),(255,118022,1,1547290215,'192.168.0.64',2,'aa'),(256,118023,1,1547290215,'192.168.0.64',2,'aa'),(257,118030,1,1547290215,'192.168.0.64',2,'aa'),(258,118032,1,1547290215,'192.168.0.64',2,'aa'),(259,118031,1,1547290215,'192.168.0.64',2,'aa'),(260,118033,1,1547290215,'192.168.0.64',2,'aa'),(261,118034,1,1547290215,'192.168.0.64',2,'aa'),(262,118035,1,1547290215,'192.168.0.64',2,'aa'),(263,118036,1,1547290215,'192.168.0.64',2,'aa'),(264,118037,1,1547290215,'192.168.0.64',2,'aa'),(265,118038,1,1547290215,'192.168.0.64',2,'aa'),(266,118004,2,1547290261,'192.168.0.64',0,''),(267,118005,2,1547290341,'192.168.0.64',0,''),(268,118006,2,1547290341,'192.168.0.64',0,''),(269,118007,2,1547290341,'192.168.0.64',0,''),(270,118008,2,1547290341,'192.168.0.64',0,''),(271,118009,2,1547290341,'192.168.0.64',0,''),(272,118010,2,1547290341,'192.168.0.64',0,''),(273,118011,2,1547290341,'192.168.0.64',0,''),(274,118012,2,1547290341,'192.168.0.64',0,''),(275,118013,2,1547290341,'192.168.0.64',0,''),(276,118014,2,1547290341,'192.168.0.64',0,''),(277,118015,2,1547290341,'192.168.0.64',0,''),(278,118016,2,1547290341,'192.168.0.64',0,''),(279,118018,2,1547290341,'192.168.0.64',0,''),(280,118019,2,1547290341,'192.168.0.64',0,''),(281,118020,2,1547290341,'192.168.0.64',0,''),(282,118021,2,1547290341,'192.168.0.64',0,''),(283,118022,2,1547290341,'192.168.0.64',0,''),(284,118023,2,1547290341,'192.168.0.64',0,''),(285,118030,2,1547290341,'192.168.0.64',0,''),(286,118031,2,1547290341,'192.168.0.64',0,''),(287,118032,2,1547290341,'192.168.0.64',0,''),(288,118033,2,1547290341,'192.168.0.64',0,''),(289,118034,2,1547290341,'192.168.0.64',0,''),(290,118035,2,1547290341,'192.168.0.64',0,''),(291,118036,2,1547290341,'192.168.0.64',0,''),(292,118037,2,1547290341,'192.168.0.64',0,''),(293,118038,2,1547290341,'192.168.0.64',0,''),(294,118029,1,1547291154,'192.168.0.64',3,'FC-AA-14-74-65-32'),(295,118029,3,1547291529,'192.168.0.64',45,'FC-AA-14-74-65-32'),(296,118029,3,1547291545,'192.168.0.64',45,'FC-AA-14-74-65-32'),(297,118039,1,1547292416,'192.168.0.64',3,'FC-AA-14-74-65-32'),(298,118040,1,1547292443,'192.168.0.64',3,'FC-AA-14-74-65-32'),(299,118040,2,1547292892,'192.168.0.64',0,''),(300,118040,1,1547292897,'192.168.0.64',3,'FC-AA-14-74-65-32');
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
  KEY `time` (`time`) USING BTREE,
  KEY `reason` (`reason`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_moneychange`
--

LOCK TABLES `statistics_moneychange` WRITE;
/*!40000 ALTER TABLE `statistics_moneychange` DISABLE KEYS */;
INSERT INTO `statistics_moneychange` VALUES (1,118001,1547217931,5001,5001,10,0,0,0,0),(2,118002,1547217946,5001,5001,10,0,0,0,0),(3,118003,1547266458,5001,5001,10,0,0,0,0),(4,118004,1547266458,5001,5001,10,0,0,0,0),(5,118005,1547266459,5001,5001,10,0,0,0,0),(6,118006,1547266459,5001,5001,10,0,0,0,0),(7,118007,1547266460,5001,5001,10,0,0,0,0),(8,118008,1547266460,5001,5001,10,0,0,0,0),(9,118009,1547266461,5001,5001,10,0,0,0,0),(10,118010,1547266461,5001,5001,10,0,0,0,0),(11,118011,1547266462,5001,5001,10,0,0,0,0),(12,118012,1547266462,5001,5001,10,0,0,0,0),(13,118013,1547266463,5001,5001,10,0,0,0,0),(14,118014,1547266463,5001,5001,10,0,0,0,0),(15,118015,1547266464,5001,5001,10,0,0,0,0),(16,118016,1547266464,5001,5001,10,0,0,0,0),(17,118017,1547266465,5001,5001,10,0,0,0,0),(18,118018,1547266465,5001,5001,10,0,0,0,0),(19,118019,1547266466,5001,5001,10,0,0,0,0),(20,118020,1547266466,5001,5001,10,0,0,0,0),(21,118021,1547266467,5001,5001,10,0,0,0,0),(22,118022,1547266467,5001,5001,10,0,0,0,0),(23,118023,1547266468,5001,5001,10,0,0,0,0),(24,118024,1547271172,5001,5001,10,0,0,0,0),(25,118024,1547271186,5002,1,1005,0,0,0,0),(26,118025,1547271710,5001,5001,10,0,0,0,0),(27,118025,1547271721,5002,1,1005,0,0,0,0),(28,118002,1547272346,5001,0,8,13,0,0,1),(29,118002,1547272380,5001,0,8,13,0,0,1),(30,118002,1547272414,5001,0,8,13,0,0,1),(31,118002,1547272448,5001,0,8,13,0,0,1),(32,118002,1547272482,5001,0,8,13,0,0,1),(33,118002,1547272516,5001,0,8,13,0,0,1),(34,118002,1547272550,5001,0,8,13,0,0,1),(35,118024,1547272805,9998400,-1600,3,1,0,0,0),(36,118024,1547272805,9998400,0,8,1,0,0,1),(37,118024,1547272840,10012800,14400,3,1,0,600,0),(38,118024,1547272840,10012800,-600,8,1,0,0,1),(39,118024,1547272875,10017600,4800,3,1,0,200,0),(40,118024,1547272875,10017600,-200,8,1,0,0,1),(41,118024,1547272910,10017600,0,8,1,0,0,1),(42,118026,1547272933,5001,5001,10,0,0,0,0),(43,118026,1547272946,5002,1,1005,0,0,0,0),(44,118024,1547272945,10017600,0,8,1,0,0,1),(45,118024,1547272980,10017600,0,8,1,0,0,1),(46,118026,1547272980,10000000,0,8,1,0,0,1),(47,118024,1547273015,10017600,0,8,1,0,0,1),(48,118026,1547273015,10000000,0,8,1,0,0,1),(49,118024,1547273050,10012600,-5000,3,1,0,0,0),(50,118024,1547273050,10012600,0,8,1,0,0,1),(51,118026,1547273050,9999950,-50,3,1,0,0,0),(52,118026,1547273050,9999950,0,8,1,0,0,1),(53,118024,1547273085,10013560,960,3,1,0,40,0),(54,118024,1547273085,10013560,-40,8,1,0,0,1),(55,118026,1547273085,9998950,-1000,3,1,0,0,0),(56,118026,1547273085,9998950,0,8,1,0,0,1),(57,118024,1547273120,10012560,-1000,3,1,0,0,0),(58,118024,1547273120,10012560,0,8,1,0,0,1),(59,118026,1547273120,9999910,960,3,1,0,40,0),(60,118026,1547273120,9999910,-40,8,1,0,0,1),(61,118024,1547273155,10012560,0,8,1,0,0,1),(62,118026,1547273155,9999910,0,8,1,0,0,1),(63,118024,1547273190,10012560,0,8,1,0,0,1),(64,118026,1547273190,9999910,0,8,1,0,0,1),(65,118024,1547273225,10012560,0,8,1,0,0,1),(66,118026,1547273225,9999910,0,8,1,0,0,1),(67,118002,1547273225,5001,0,8,1,0,0,1),(68,118002,1547273260,5001,0,8,2,0,0,1),(69,118024,1547273260,10012560,0,8,1,0,0,1),(70,118026,1547273260,9999910,0,8,1,0,0,1),(71,118002,1547273295,5001,0,8,2,0,0,1),(72,118024,1547273295,10012560,0,8,1,0,0,1),(73,118026,1547273295,9999910,0,8,1,0,0,1),(74,118002,1547273330,5001,0,8,2,0,0,1),(75,118024,1547273330,10012560,0,8,1,0,0,1),(76,118026,1547273330,9999910,0,8,1,0,0,1),(77,118024,1547273365,10012560,0,8,1,0,0,1),(78,118026,1547273365,9999910,0,8,1,0,0,1),(79,118024,1547273400,10012560,0,8,1,0,0,1),(80,118026,1547273400,9999910,0,8,1,0,0,1),(81,118024,1547273435,10012560,0,8,1,0,0,1),(82,118026,1547273435,9999910,0,8,1,0,0,1),(83,118024,1547273470,10012560,0,8,1,0,0,1),(84,118026,1547273470,9999910,0,8,1,0,0,1),(85,118026,1547273505,9999910,0,8,1,0,0,1),(86,118026,1547273540,9999910,0,8,1,0,0,1),(87,118026,1547273575,9999910,0,8,1,0,0,1),(88,118026,1547273610,9999910,0,8,1,0,0,1),(89,118024,1547273681,10012560,0,8,1,0,0,1),(90,118026,1547273681,9999910,0,8,1,0,0,1),(91,118024,1547273716,10007560,-5000,3,1,0,0,0),(92,118024,1547273716,10007560,0,8,1,0,0,1),(93,118026,1547273716,10004710,4800,3,1,0,200,0),(94,118026,1547273716,10004710,-200,8,1,0,0,1),(95,118024,1547273751,10007560,0,8,1,0,0,1),(96,118026,1547273751,10004710,0,8,1,0,0,1),(97,118024,1547273786,10007560,0,8,1,0,0,1),(98,118026,1547273786,10004710,0,8,1,0,0,1),(99,118024,1547273821,10017160,9600,3,1,0,400,0),(100,118024,1547273821,10017160,-400,8,1,0,0,1),(101,118026,1547273821,9994710,-10000,3,1,0,0,0),(102,118026,1547273821,9994710,0,8,1,0,0,1),(103,118024,1547273856,10007160,-10000,3,1,0,0,0),(104,118024,1547273856,10007160,0,8,1,0,0,1),(105,118026,1547273856,10004310,9600,3,1,0,400,0),(106,118026,1547273856,10004310,-400,8,1,0,0,1),(107,118024,1547273891,9957160,-50000,3,1,0,0,0),(108,118024,1547273891,9957160,0,8,1,0,0,1),(109,118026,1547273891,10052310,48000,3,1,0,2000,0),(110,118026,1547273891,10052310,-2000,8,1,0,0,1),(111,118024,1547273926,9995560,38400,3,1,0,1600,0),(112,118024,1547273926,9995560,-1600,8,1,0,0,1),(113,118026,1547273926,10012310,-40000,3,1,0,0,0),(114,118026,1547273926,10012310,0,8,1,0,0,1),(115,118024,1547273961,9975560,-20000,3,1,0,0,0),(116,118024,1547273961,9975560,0,8,1,0,0,1),(117,118026,1547273961,10031510,19200,3,1,0,800,0),(118,118026,1547273961,10031510,-800,8,1,0,0,1),(119,118024,1547273996,9955560,-20000,3,1,0,0,0),(120,118024,1547273996,9955560,0,8,1,0,0,1),(121,118026,1547273996,10050710,19200,3,1,0,800,0),(122,118026,1547273996,10050710,-800,8,1,0,0,1),(123,118024,1547274031,10061160,105600,3,1,0,4400,0),(124,118024,1547274031,10061160,-4400,8,1,0,0,1),(125,118026,1547274031,9940710,-110000,3,1,0,0,0),(126,118026,1547274031,9940710,0,8,1,0,0,1),(127,118024,1547274066,10262760,201600,3,1,0,8400,0),(128,118024,1547274066,10262760,-8400,8,1,0,0,1),(129,118026,1547274066,9954150,13440,3,1,0,560,0),(130,118026,1547274066,9954150,-560,8,1,0,0,1),(131,118024,1547274101,10665960,403200,3,1,0,16800,0),(132,118024,1547274101,10665960,-16800,8,1,0,0,1),(133,118026,1547274101,10021350,67200,3,1,0,2800,0),(134,118026,1547274101,10021350,-2800,8,1,0,0,1),(135,118024,1547274136,10605960,-60000,3,1,0,0,0),(136,118024,1547274136,10605960,0,8,1,0,0,1),(137,118026,1547274136,10001350,-20000,3,1,0,0,0),(138,118026,1547274136,10001350,0,8,1,0,0,1),(139,118024,1547274171,10005960,-600000,3,1,0,0,0),(140,118024,1547274171,10005960,0,8,1,0,0,1),(141,118026,1547274171,9801350,-200000,3,1,0,0,0),(142,118026,1547274171,9801350,0,8,1,0,0,1),(143,118024,1547274206,10121160,115200,3,1,0,4800,0),(144,118024,1547274206,10121160,-4800,8,1,0,0,1),(145,118026,1547274206,9839750,38400,3,1,0,1600,0),(146,118026,1547274206,9839750,-1600,8,1,0,0,1),(147,118024,1547274241,10437960,316800,3,1,0,13200,0),(148,118024,1547274241,10437960,-13200,8,1,0,0,1),(149,118026,1547274241,9945350,105600,3,1,0,4400,0),(150,118026,1547274241,9945350,-4400,8,1,0,0,1),(151,118024,1547274276,10437960,0,8,2,0,0,1),(152,118026,1547274276,9945350,0,8,2,0,0,1),(153,118024,1547274311,10485960,48000,3,2,0,2000,0),(154,118024,1547274311,10485960,-2000,8,2,0,0,1),(155,118026,1547274311,9895350,-50000,3,2,0,0,0),(156,118026,1547274311,9895350,0,8,2,0,0,1),(157,118024,1547274346,9985960,-500000,3,2,0,0,0),(158,118024,1547274346,9985960,0,8,2,0,0,1),(159,118026,1547274346,10375350,480000,3,2,0,20000,0),(160,118026,1547274346,10375350,-20000,8,2,0,0,1),(161,118024,1547274381,10081960,96000,3,2,0,4000,0),(162,118024,1547274381,10081960,-4000,8,2,0,0,1),(163,118026,1547274381,10275350,-100000,3,2,0,0,0),(164,118026,1547274381,10275350,0,8,2,0,0,1),(165,118024,1547274417,10110760,28800,3,2,0,1200,0),(166,118024,1547274417,10110760,-1200,8,2,0,0,1),(167,118026,1547274417,10245350,-30000,3,2,0,0,0),(168,118026,1547274417,10245350,0,8,2,0,0,1),(169,118024,1547274452,10070760,-40000,3,2,0,0,0),(170,118024,1547274452,10070760,0,8,2,0,0,1),(171,118026,1547274452,10283750,38400,3,2,0,1600,0),(172,118026,1547274452,10283750,-1600,8,2,0,0,1),(173,118024,1547274487,10072680,1920,3,2,0,80,0),(174,118024,1547274487,10072680,-80,8,2,0,0,1),(175,118026,1547274487,10281750,-2000,3,2,0,0,0),(176,118026,1547274487,10281750,0,8,2,0,0,1),(177,118027,1547276025,5001,5001,10,0,0,0,0),(178,118024,1547278160,10072680,0,8,3,0,0,1),(179,118024,1547278195,10318920,246240,3,3,0,10260,0),(180,118024,1547278195,10318920,-10260,8,3,0,0,1),(181,118024,1547278230,10318920,0,8,3,0,0,1),(182,118024,1547278265,10318920,0,8,3,0,0,1),(183,118024,1547278300,11629320,1310400,3,3,0,54600,0),(184,118024,1547278300,11629320,-54600,8,3,0,0,1),(185,118024,1547278335,11629320,0,8,3,0,0,1),(186,118024,1547278370,11629320,0,8,3,0,0,1),(187,118024,1547278405,11629320,0,8,3,0,0,1),(188,118024,1547278440,11629320,0,8,3,0,0,1),(189,118024,1547278475,11629320,0,8,3,0,0,1),(190,118024,1547278510,11629320,0,8,3,0,0,1),(191,118024,1547278545,11629320,0,8,3,0,0,1),(192,118024,1547278580,11629320,0,8,3,0,0,1),(193,118024,1547278616,11629320,0,8,3,0,0,1),(194,118024,1547278651,11629320,0,8,3,0,0,1),(195,118028,1547279107,5001,5001,10,0,0,0,0),(196,118024,1547281474,11623320,-6000,3,3,0,0,0),(197,118024,1547281474,11623320,0,8,3,0,0,1),(198,118024,1547281544,11621820,-1500,3,3,0,0,0),(199,118024,1547281544,11621820,0,8,3,0,0,1),(200,118024,1547281579,11621820,0,8,3,0,0,1),(201,118024,1547281614,11621820,0,8,3,0,0,1),(202,118024,1547281649,11621820,0,8,3,0,0,1),(203,118024,1547281684,11621820,0,8,3,0,0,1),(204,118024,1547281719,11621820,0,8,3,0,0,1),(205,118024,1547281754,11621820,0,8,3,0,0,1),(206,118024,1547281789,11621820,0,8,3,0,0,1),(207,118024,1547281824,11621820,0,8,3,0,0,1),(208,118024,1547281894,11621820,0,8,3,0,0,1),(209,118024,1547281929,11633340,11520,3,3,0,480,0),(210,118024,1547281929,11633340,-480,8,3,0,0,1),(211,118024,1547281964,11641980,8640,3,3,0,360,0),(212,118024,1547281964,11641980,-360,8,3,0,0,1),(213,118024,1547281999,11641980,0,8,3,0,0,1),(214,118024,1547282035,11650620,8640,3,3,0,360,0),(215,118024,1547282035,11650620,-360,8,3,0,0,1),(216,118024,1547282063,11650620,0,8,6,0,0,1),(217,118024,1547282090,11657820,7200,3,6,0,300,0),(218,118024,1547282090,11657820,-300,8,6,0,0,1),(219,118024,1547282117,11654820,-3000,3,6,0,0,0),(220,118024,1547282117,11654820,0,8,6,0,0,1),(221,118024,1547282144,11653320,-1500,3,6,0,0,0),(222,118024,1547282144,11653320,0,8,6,0,0,1),(223,118024,1547282171,11651820,-1500,3,6,0,0,0),(224,118024,1547282171,11651820,0,8,6,0,0,1),(225,118024,1547282185,11651820,0,8,9,0,0,1),(226,118024,1547282207,11651820,0,8,9,0,0,1),(227,118024,1547282229,11651820,0,8,9,0,0,1),(228,118024,1547282251,11651820,0,8,9,0,0,1),(229,118024,1547282273,11651820,0,8,9,0,0,1),(230,118024,1547282295,11651820,0,8,9,0,0,1),(231,118024,1547282317,11651820,0,8,9,0,0,1),(232,118024,1547282339,11651820,0,8,9,0,0,1),(233,118024,1547282493,11651820,0,8,9,0,0,1),(234,118024,1547282515,11651820,0,8,9,0,0,1),(235,118024,1547282537,11651820,0,8,9,0,0,1),(236,118024,1547282559,11651820,0,8,9,0,0,1),(237,118024,1547282581,11651820,0,8,9,0,0,1),(238,118024,1547282797,11651820,0,8,3,0,0,1),(239,118024,1547282832,11201820,-450000,3,3,0,0,0),(240,118024,1547282832,11201820,0,8,3,0,0,1),(241,118024,1547282868,11201820,0,8,3,0,0,1),(242,118024,1547283039,11200320,-1500,3,3,0,0,0),(243,118024,1547283039,11200320,0,8,3,0,0,1),(244,118024,1547284085,11217600,17280,3,3,0,720,0),(245,118024,1547284085,11217600,-720,8,3,0,0,1),(246,118024,1547284120,11217600,0,8,3,0,0,1),(247,118024,1547284155,11217600,0,8,3,0,0,1),(248,118024,1547284190,11217600,0,8,3,0,0,1),(249,118024,1547284225,11214600,-3000,3,3,0,0,0),(250,118024,1547284225,11214600,0,8,3,0,0,1),(251,118024,1547284260,11214600,0,8,3,0,0,1),(252,118024,1547284295,11214600,0,8,3,0,0,1),(253,118024,1547284330,11214600,0,8,3,0,0,1),(254,118024,1547284365,11214600,0,8,3,0,0,1),(255,118024,1547284400,11214600,0,8,3,0,0,1),(256,118024,1547284435,11214600,0,8,3,0,0,1),(257,118024,1547284470,11214600,0,8,3,0,0,1),(258,118024,1547284505,11214600,0,8,3,0,0,1),(259,118024,1547284540,11214600,0,8,3,0,0,1),(260,118024,1547284575,11214600,0,8,3,0,0,1),(261,118024,1547284891,11214600,0,8,3,0,0,1),(262,118024,1547284926,11190600,-24000,3,3,0,0,0),(263,118024,1547284926,11190600,0,8,3,0,0,1),(264,118024,1547284961,11190600,0,8,3,0,0,1),(265,118024,1547285374,11190600,0,8,3,0,0,1),(266,118024,1547285409,11485800,295200,3,3,0,12300,0),(267,118024,1547285409,11485800,-12300,8,3,0,0,1),(268,118024,1547285444,11487240,1440,3,3,0,60,0),(269,118024,1547285444,11487240,-60,8,3,0,0,1),(270,118024,1547285479,11487240,0,8,3,0,0,1),(271,118024,1547285514,11487240,0,8,3,0,0,1),(272,118024,1547285549,11487240,0,8,3,0,0,1),(273,118024,1547285584,11487240,0,8,3,0,0,1),(274,118024,1547285619,11487240,0,8,3,0,0,1),(275,118024,1547285654,11504520,17280,3,3,0,720,0),(276,118024,1547285654,11504520,-720,8,3,0,0,1),(277,118024,1547285689,11510280,5760,3,3,0,240,0),(278,118024,1547285689,11510280,-240,8,3,0,0,1),(279,118024,1547285724,11523240,12960,3,3,0,540,0),(280,118024,1547285724,11523240,-540,8,3,0,0,1),(281,118024,1547285759,11547720,24480,3,3,0,1020,0),(282,118024,1547285759,11547720,-1020,8,3,0,0,1),(283,118024,1547285794,11547720,0,8,3,0,0,1),(284,118024,1547285829,11547720,0,8,3,0,0,1),(285,118024,1547285864,11547720,0,8,3,0,0,1),(286,118024,1547285899,11517720,-30000,3,3,0,0,0),(287,118024,1547285899,11517720,0,8,3,0,0,1),(288,118024,1547285935,11532120,14400,3,3,0,600,0),(289,118024,1547285935,11532120,-600,8,3,0,0,1),(290,118024,1547285970,11532120,0,8,3,0,0,1),(291,118024,1547286005,11532120,0,8,3,0,0,1),(292,118024,1547286040,11532120,0,8,3,0,0,1),(293,118024,1547286075,11532120,0,8,3,0,0,1),(294,118024,1547286110,11532120,0,8,3,0,0,1),(295,118029,1547286320,5001,5001,10,0,0,0,0),(296,118029,1547286332,5002,1,1005,0,0,0,0),(297,118030,1547289914,5001,5001,10,0,0,0,0),(298,118031,1547289914,5001,5001,10,0,0,0,0),(299,118032,1547289914,5001,5001,10,0,0,0,0),(300,118033,1547289914,5001,5001,10,0,0,0,0),(301,118034,1547289914,5001,5001,10,0,0,0,0),(302,118035,1547289914,5001,5001,10,0,0,0,0),(303,118036,1547289914,5001,5001,10,0,0,0,0),(304,118037,1547289914,5001,5001,10,0,0,0,0),(305,118038,1547289914,5001,5001,10,0,0,0,0),(306,118039,1547292414,5001,5001,10,0,0,0,0),(307,118040,1547292442,5001,5001,10,0,0,0,0),(308,118039,1547292526,5081,80,3,19,0,0,0),(309,118040,1547292526,4921,-80,3,19,0,0,0),(310,118039,1547292542,5001,-80,3,19,0,0,0),(311,118040,1547292542,5001,80,3,19,0,0,0),(312,118039,1547292557,4841,-160,3,19,0,0,0),(313,118040,1547292557,5161,160,3,19,0,0,0),(314,118039,1547292572,4681,-160,3,19,0,0,0),(315,118040,1547292572,5321,160,3,19,0,0,0),(316,118039,1547292585,4441,-240,3,19,0,0,0),(317,118040,1547292585,5561,240,3,19,0,0,0),(318,118039,1547292599,4681,240,3,19,0,0,0),(319,118040,1547292599,5321,-240,3,19,0,0,0),(320,118040,1547292601,5312,-9,8,19,0,0,1);
/*!40000 ALTER TABLE `statistics_moneychange` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userInfo`
--

LOCK TABLES `userInfo` WRITE;
/*!40000 ALTER TABLE `userInfo` DISABLE KEYS */;
INSERT INTO `userInfo` VALUES (118001,'15625579122','123456','玩家118001','15625579122',0,'',5001,0,'',100,0,-1,'192.168.0.86','http://ht.szhuomei.com/head/man/1492.jpg',0,'00-E0-4C-ED-4C-9B','{FABA8F5C-D73A-4238-894B-B000AE4C5E03}',0,0,0,0,1547217931,'192.168.0.86',4,0,1547217931,0,0,'','','',0,'','','',0,862,0,'',''),(118002,'游客806018','','游客806018','',1,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/1841.jpg',0,'FC-AA-14-74-65-32','{9EC0D08F-6AA7-41AF-90B3-7AA2EBA88997}',0,0,0,0,1547217946,'192.168.0.64',6,0,1547272312,11,0,'','','',0,'','','',1547272312,985,1,'',''),(118003,'HMRobot118100','e10adc3949ba59abbe56e057f20f883e','HMRobot118100','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002664.jpg',0,'aa','{9E30099E-8BFA-4644-B8BB-03ED048F8967}',0,0,0,0,1547266458,'192.168.0.64',1,0,1547266458,0,0,'','','',0,'','','',1547290215,22,1,'',''),(118004,'HMRobot118101','e10adc3949ba59abbe56e057f20f883e','HMRobot118101','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000417.jpg',0,'aa','{4A4801EF-2C69-41ED-8141-9C27F9C48121}',0,0,0,0,1547266458,'192.168.0.64',1,0,1547266458,0,0,'','','',0,'','','',0,62,0,'',''),(118005,'HMRobot118102','e10adc3949ba59abbe56e057f20f883e','HMRobot118102','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003606.jpg',0,'aa','{586B903A-9DAD-4E3A-A07C-9B7114F34695}',0,0,0,0,1547266459,'192.168.0.64',1,0,1547266459,0,0,'','','',0,'','','',0,142,0,'',''),(118006,'HMRobot118103','e10adc3949ba59abbe56e057f20f883e','HMRobot118103','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002385.jpg',0,'aa','{FAFB9224-A2EC-44E6-81F5-4D5192D4DF2B}',0,0,0,0,1547266459,'192.168.0.64',1,0,1547266459,0,0,'','','',0,'','','',0,218,0,'',''),(118007,'HMRobot118104','e10adc3949ba59abbe56e057f20f883e','HMRobot118104','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001830.jpg',0,'aa','{E870A80B-A7F1-45B9-A219-B5140597204B}',0,0,0,0,1547266460,'192.168.0.64',1,0,1547266460,0,0,'','','',0,'','','',0,218,0,'',''),(118008,'HMRobot118105','e10adc3949ba59abbe56e057f20f883e','HMRobot118105','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003083.jpg',0,'aa','{82507CCE-DC36-45DE-BCA5-260B70A60CAB}',0,0,0,0,1547266460,'192.168.0.64',1,0,1547266460,0,0,'','','',0,'','','',0,216,0,'',''),(118009,'HMRobot118106','e10adc3949ba59abbe56e057f20f883e','HMRobot118106','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003685.jpg',0,'aa','{F8B50F46-95CE-4B2E-905E-71080DD29CED}',0,0,0,0,1547266461,'192.168.0.64',1,0,1547266461,0,0,'','','',0,'','','',0,215,0,'',''),(118010,'HMRobot118107','e10adc3949ba59abbe56e057f20f883e','HMRobot118107','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003791.jpg',0,'aa','{6FDF8685-3567-4783-AFE3-91BDC43DF47C}',0,0,0,0,1547266461,'192.168.0.64',1,0,1547266461,0,0,'','','',0,'','','',0,214,0,'',''),(118011,'HMRobot118108','e10adc3949ba59abbe56e057f20f883e','HMRobot118108','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002759.jpg',0,'aa','{3C3B17DA-CB02-4E8B-A682-21246B4F7305}',0,0,0,0,1547266462,'192.168.0.64',1,0,1547266462,0,0,'','','',0,'','','',0,213,0,'',''),(118012,'HMRobot118109','e10adc3949ba59abbe56e057f20f883e','HMRobot118109','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003061.jpg',0,'aa','{52B5A501-33E0-4B37-A378-208C265E8D3B}',0,0,0,0,1547266462,'192.168.0.64',1,0,1547266462,0,0,'','','',0,'','','',0,213,0,'',''),(118013,'HMRobot118110','e10adc3949ba59abbe56e057f20f883e','HMRobot118110','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000241.jpg',0,'aa','{D30B0C26-3B72-49A6-9D15-D3A1DB5AF039}',0,0,0,0,1547266463,'192.168.0.64',1,0,1547266463,0,0,'','','',0,'','','',0,212,0,'',''),(118014,'HMRobot118111','e10adc3949ba59abbe56e057f20f883e','HMRobot118111','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002411.jpg',0,'aa','{9B912825-9166-4031-AACE-D7C420562691}',0,0,0,0,1547266463,'192.168.0.64',1,0,1547266463,0,0,'','','',0,'','','',0,212,0,'',''),(118015,'HMRobot118112','e10adc3949ba59abbe56e057f20f883e','HMRobot118112','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000355.jpg',0,'aa','{C45DBA00-3345-4831-A676-5128674CDEF2}',0,0,0,0,1547266464,'192.168.0.64',1,0,1547266464,0,0,'','','',0,'','','',0,131,0,'',''),(118016,'HMRobot118113','e10adc3949ba59abbe56e057f20f883e','HMRobot118113','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000976.jpg',0,'aa','{DA752359-37DA-43C3-9385-6327825AFAD7}',0,0,0,0,1547266464,'192.168.0.64',1,0,1547266464,0,0,'','','',0,'','','',0,211,0,'',''),(118017,'HMRobot118114','e10adc3949ba59abbe56e057f20f883e','HMRobot118114','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002082.jpg',0,'aa','{CC90DFCD-02AD-4D1C-93A9-7391FF1917EE}',0,0,0,0,1547266465,'192.168.0.64',1,0,1547266465,0,0,'','','',0,'','','',0,84,0,'',''),(118018,'HMRobot118115','e10adc3949ba59abbe56e057f20f883e','HMRobot118115','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003387.jpg',0,'aa','{D7401207-4ACE-4A3A-81F6-243431AE2E4A}',0,0,0,0,1547266465,'192.168.0.64',1,0,1547266465,0,0,'','','',0,'','','',0,210,0,'',''),(118019,'HMRobot118116','e10adc3949ba59abbe56e057f20f883e','HMRobot118116','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001276.jpg',0,'aa','{0BA86F31-6248-45C1-BE22-6659D57D51A6}',0,0,0,0,1547266466,'192.168.0.64',1,0,1547266466,0,0,'','','',0,'','','',0,209,0,'',''),(118020,'HMRobot118117','e10adc3949ba59abbe56e057f20f883e','HMRobot118117','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002723.jpg',0,'aa','{BACEED96-CB75-4CDB-93CA-F43132A77543}',0,0,0,0,1547266466,'192.168.0.64',1,0,1547266466,0,0,'','','',0,'','','',0,209,0,'',''),(118021,'HMRobot118118','e10adc3949ba59abbe56e057f20f883e','HMRobot118118','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002560.jpg',0,'aa','{17C2A9BC-BC63-4A1E-A976-86122BF57545}',0,0,0,0,1547266467,'192.168.0.64',1,0,1547266467,0,0,'','','',0,'','','',0,208,0,'',''),(118022,'HMRobot118119','e10adc3949ba59abbe56e057f20f883e','HMRobot118119','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002457.jpg',0,'aa','{25B77072-0A82-4E47-9440-D2902378D450}',0,0,0,0,1547266467,'192.168.0.64',1,0,1547266467,0,0,'','','',0,'','','',0,208,0,'',''),(118023,'HMRobot118120','e10adc3949ba59abbe56e057f20f883e','HMRobot118120','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000415.jpg',0,'aa','{6470142A-B566-4E94-81B6-8503A298A07E}',0,0,0,0,1547266468,'192.168.0.64',1,0,1547266468,0,0,'','','',0,'','','',0,207,0,'',''),(118024,'111111','96e79218965eb72c92a549dd5a330112','111111','',1,'',11532120,0,'',100,0,-1,'192.168.0.68','http://ht.szhuomei.com/head/man/1054.jpg',28,'40-8D-5C-1A-02-09','{458CE57B-A727-4D1A-91C1-64421F5CEFAE}',0,0,0,0,1547271172,'192.168.0.68',1,0,1547271173,136,0,'','','',28,'','','',0,7419,0,'',''),(118025,'游客819069','','游客819069','',1,'',5002,0,'',100,0,-1,'192.168.0.60','http://ht.szhuomei.com/head/man/1500.jpg',0,'48-8A-D2-53-AE-BE','{47187032-5159-48BF-A9CB-916AD2BFA2AB}',0,0,0,0,1547271710,'192.168.0.60',6,0,1547271710,0,0,'','','',0,'','','',0,234,0,'',''),(118026,'222222','96e79218965eb72c92a549dd5a330112','222222','',1,'',10281750,0,'',100,0,-1,'192.168.0.68','http://ht.szhuomei.com/head/man/1072.jpg',12,'40-8D-5C-1A-02-09','{DB9311EC-87D6-4855-8BB0-7565FF461DBF}',0,0,0,0,1547272933,'192.168.0.68',1,0,1547272934,43,0,'','','',12,'','','',1547274501,1552,1,'',''),(118027,'游客830965','','游客830965','',1,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/1085.jpg',0,'FC-AA-14-74-65-32','{D2AF0031-09F8-4738-846E-416E83900077}',0,0,0,0,1547276025,'192.168.0.64',6,0,1547276025,0,0,'','','',0,'','','',1547276025,0,1,'',''),(118028,'18948335137','e10adc3949ba59abbe56e057f20f883e','玩家118028','18948335137',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/1317.jpg',0,'FC-AA-14-74-65-32','{39DE15B3-CC2C-4B47-B2B5-C049937984B8}',0,0,0,0,1547279107,'192.168.0.64',4,0,1547279107,0,0,'','','',0,'','','',0,46,0,'',''),(118029,'游客523629','','游客523629','',1,'',5002,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/1230.jpg',0,'FC-AA-14-74-65-32','{37798AAF-96AB-47CC-8AC2-40C9FB3594C5}',0,0,0,0,1547286320,'192.168.0.64',6,0,1547286320,0,0,'','','',0,'','','',1547291154,0,1,'',''),(118030,'HMRobot118121','e10adc3949ba59abbe56e057f20f883e','HMRobot118121','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000337.jpg',0,'aa','{C7A50646-04B7-4BE8-B869-569D38028945}',0,0,0,0,1547289909,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118031,'HMRobot118122','e10adc3949ba59abbe56e057f20f883e','HMRobot118122','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001837.jpg',0,'aa','{2574FE4D-A1BE-45EF-BE95-473C2B309BC4}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118032,'HMRobot118123','e10adc3949ba59abbe56e057f20f883e','HMRobot118123','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1002929.jpg',0,'aa','{8B6092B7-826D-4FDC-938E-40FE59579FAD}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118033,'HMRobot118124','e10adc3949ba59abbe56e057f20f883e','HMRobot118124','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003922.jpg',0,'aa','{26ACA91B-0104-4370-8774-09DA265C9F6F}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118034,'HMRobot118125','e10adc3949ba59abbe56e057f20f883e','HMRobot118125','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001557.jpg',0,'aa','{D98B4AE8-4E3B-4E83-8F7A-C20B2A22BA94}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118035,'HMRobot118126','e10adc3949ba59abbe56e057f20f883e','HMRobot118126','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001374.jpg',0,'aa','{C4F9765B-31E3-4202-97C8-88CE3B998B1F}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118036,'HMRobot118127','e10adc3949ba59abbe56e057f20f883e','HMRobot118127','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1001390.jpg',0,'aa','{8C9654E3-B420-4055-87FC-7195B4A2A45E}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118037,'HMRobot118128','e10adc3949ba59abbe56e057f20f883e','HMRobot118128','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1000695.jpg',0,'aa','{45E2E46F-5B85-44CF-A6B4-0705EDE73575}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118038,'HMRobot118129','e10adc3949ba59abbe56e057f20f883e','HMRobot118129','',0,'',5001,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/woman/1003900.jpg',0,'aa','{4A938B01-AD73-4A98-A7AC-E7D06EE7B4B6}',0,0,0,0,1547289914,'192.168.0.64',1,0,1547289914,0,0,'','e10adc3949ba59abbe56e057f20f883e','',0,'','','',0,201,0,'',''),(118039,'LXW100','e10adc3949ba59abbe56e057f20f883e','LXW100','',1,'',4681,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/1916.jpg',2,'FC-AA-14-74-65-32','{7970366A-DDED-46AB-9357-67E52A6EE350}',0,16,0,0,1547292414,'192.168.0.64',1,0,1547292416,6,0,'','','',2,'','','',1547292416,0,1,'',''),(118040,'LXW200','e10adc3949ba59abbe56e057f20f883e','LXW200','',1,'',5312,0,'',100,0,-1,'192.168.0.64','http://ht.szhuomei.com/head/man/2020.jpg',4,'FC-AA-14-74-65-32','{16174B02-8A19-4206-8D0D-23B3DF548668}',0,0,0,0,1547292442,'192.168.0.64',1,0,1547292443,6,0,'','','',4,'','','',1547292897,449,1,'','');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户操作记录表';
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台用户角色';
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_admin_log`
--

LOCK TABLES `web_admin_log` WRITE;
/*!40000 ALTER TABLE `web_admin_log` DISABLE KEYS */;
INSERT INTO `web_admin_log` VALUES (1,1,'添加用户名为的代理',1546840302),(2,1,'登录了管理后台',1546848888),(3,1,'登录了管理后台',1546849038),(4,1,'添加用户名为的代理',1546851417),(5,1,'添加用户名为的代理',1546854133),(6,1,'编辑转盘数据',1546941125),(7,1,'登录了管理后台',1546952838),(8,1,'编辑签到配置',1547000127),(9,1,'登录了管理后台',1547023910),(10,1,'登录了管理后台',1547033768),(11,1,'登录了管理后台',1547115087);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_admin_member`
--

LOCK TABLES `web_admin_member` WRITE;
/*!40000 ALTER TABLE `web_admin_member` DISABLE KEYS */;
INSERT INTO `web_admin_member` VALUES (1,'adminser','boss','e10adc3949ba59abbe56e057f20f883e','15180092940',0,0,443,1547115087,'127.0.0.1',0,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COMMENT='后台节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_admin_menu`
--

LOCK TABLES `web_admin_menu` WRITE;
/*!40000 ALTER TABLE `web_admin_menu` DISABLE KEYS */;
INSERT INTO `web_admin_menu` VALUES (1,'系统管理','',0,0,1,'fa fa-cogs'),(2,'用户分组','Adminsystem/user_group',0,1,1,''),(3,'菜单管理','Adminsystem/menu_config',0,1,0,''),(9,'玩家管理','',0,0,4,'fa fa-user'),(11,'机器人管理','User/vbot_list',1,9,1,''),(12,'银行操作记录','Hall/bank',0,129,1,''),(13,'用户限制列表','User/limit_ip',0,9,3,''),(14,'在线玩家列表','User/online_list',1,9,4,''),(15,'代理管理','',0,0,9,'fa fa-user-secret'),(16,'代理列表','Agent/member_list',0,15,1,''),(17,'商城管理','',0,0,5,'fa fa-shopping-cart'),(18,'大厅功能管理','',0,0,7,'fa fa-desktop'),(19,'开发人员选项','',0,0,2,'fa fa-gamepad'),(20,'游戏管理','',0,0,6,'fa fa-rocket'),(21,'网站管理','',0,0,2,'fa fa-internet-explorer'),(22,'代理权限控制','Agent/member_rules',1,15,1,''),(23,'充值账单明细','Agent/bill_detail',1,17,2,''),(24,'代理信息统计','Agent/info_count',0,15,3,''),(25,'代理申请提现记录','Agent/apply_pos',0,15,3,''),(26,'抽奖','Hall/turntable',0,18,5,''),(27,'签到','Hall/sign',0,18,1,''),(28,'邮件管理','Hall/email',0,127,2,''),(29,'公告管理','Hall/notice',0,127,3,''),(30,'反馈管理','Hall/feedback',0,18,7,''),(31,'救济金','Hall/alms',0,18,3,''),(32,'转赠','Hall/given',0,18,4,''),(34,'实物兑换管理','Hall/convert',1,18,8,''),(35,'世界广播','Hall/radio',0,18,6,''),(36,'跳转和游戏参数管理','Hall/web_page',0,19,4,''),(37,'魔法表情管理','Game/magic',1,19,0,''),(38,'房间管理','Game/room',1,20,2,''),(39,'游戏列表','Game/game_list',0,20,3,''),(40,'版本控制','Game/version',0,19,2,''),(41,'充值分润列表','Mall/recharge_record',0,17,4,''),(42,'充值统计','Mall/data_count',0,17,3,''),(43,'商城商品管理','Mall/goods',0,17,1,''),(44,'平台数据记录','Operate/plat_data_record',1,124,0,''),(45,'实物兑换统计','Operate/convert_record',1,124,1,''),(46,'注册下载量统计','Operate/register_download_record',0,124,1,''),(47,'用户充值统计','Operate/user_recharge_count',1,124,3,''),(48,'在线用户统计','Operate/online_count',0,124,2,''),(49,'活跃玩家和流失玩家','Operate/active_loss_user',0,124,3,''),(51,'签到统计','Operate/sign_count',1,124,7,''),(52,'领取救济金统计','Operate/alms_count',1,124,8,''),(53,'抽奖统计','Operate/turntable_count',1,124,9,''),(54,'反馈统计','Operate/feedback_count',1,124,10,''),(55,'转赠统计','Operate/given_count',1,124,11,''),(56,'世界广播统计','Operate/radio_count',1,124,12,''),(57,'分享统计','Operate/share_count',1,124,13,''),(58,'游戏输赢统计','Operate/game_record_info',1,124,14,''),(59,'网站参数配置','Home/config',0,21,0,''),(60,'图片位管理','Home/img',0,21,1,''),(61,'产品中心','Home/product',0,21,2,''),(62,'新闻管理','Home/news',0,21,3,''),(63,'留言记录','Home/feedback',1,21,4,''),(64,'游戏底包上传','Game/upload_view',0,19,3,''),(65,'游戏常用参数配置','Hall/game_config',0,19,5,''),(66,'游戏分享','Hall/share',0,18,2,''),(67,'管理用户','Adminsystem/member_list',0,1,2,''),(68,'操作日志','Adminsystem/operation',0,1,3,''),(69,'VIP房管理','Game/vipRoom',1,19,0,''),(70,'金币变化日志','Operate/money_change_record',0,123,14,''),(71,'代理分成比例配置','Agent/agent_config',0,15,4,''),(72,'平台状态管理','Adminsystem/plat_status',0,19,1,''),(73,'钻石变化日志','Operate/jewels_change_record',0,123,15,''),(74,'银行操作记录','Hall/bank',1,18,12,''),(75,'登录管理','Hall/login',1,18,0,''),(76,'代理后台','Agent/Public/login',0,15,6,''),(77,'游戏控制','Hall/rewardsPool',0,20,1,''),(78,'充值','User/user_recharge',0,10,0,''),(79,'提取','User/user_pos',0,10,0,''),(80,'限制登录','User/limit_login',0,10,0,''),(81,'添加白名单','User/white',0,10,0,''),(82,'添加超端','User/set_supper_user',0,10,0,''),(84,'发送个人邮件','User/personal_send_email',0,10,0,''),(85,'绑定邀请码','User/bind_code',0,10,0,''),(86,'离开房间','User/clearRoom',0,10,0,''),(87,'充值记录','User/personal_recharge_record',0,10,0,''),(88,'金币变化记录','User/personal_money_change',0,10,0,''),(89,'钻石变化记录','User/personal_jewels_change',1,9,0,''),(90,'个人对局游戏记录','User/personal_game_record',0,10,0,''),(91,'个人房卡游戏记录','User/personal_game_jewsels_record',1,9,0,''),(92,'签到记录','User/personal_sign_record',0,10,0,''),(93,'抽奖记录','User/personal_turntable_record',0,10,0,''),(94,'分享记录','User/personal_share_record',0,10,0,''),(95,'实物兑换记录 ','User/personal_convert_record',0,10,0,''),(96,'转赠记录','User/personal_given_record',0,10,0,''),(97,'魔法表情记录','User/personal_magic_record',0,10,0,''),(98,'世界广播记录','User/personal_radio_record',0,10,0,''),(99,'登录记录','Hall/login',0,10,0,''),(100,'代理信息','User/agentinfo',0,10,0,''),(101,'订单管理','Mall/orders',0,17,2,''),(102,'后台充值提取记录','User/admin_action_record',1,17,6,''),(103,'代理申请列表','Agent/examine',0,15,2,''),(104,' 菜单管理','Wechat/index',0,112,0,''),(105,'关键词管理','wechat/keywords',0,112,0,''),(106,'关注默认回复','wechat/subscribe',0,112,0,''),(107,'用户管理','wechat/user',0,112,0,''),(108,'素材管理','wechat/material',0,112,0,''),(109,'图文管理','wechat/news',1,112,0,''),(110,'客服管理','wechat/kf',0,112,0,''),(111,'微信代理申请','Agent/apply',1,15,0,''),(112,'微信管理','Wechat/Index',1,0,0,'fa fa-qq'),(113,'代理收入明细','User/bill_detail',0,10,0,''),(114,'设置用户状态','User/set_user_status',0,10,0,''),(115,'好友打赏记录','Hall/reward',1,18,0,''),(116,'俱乐部列表','Club/index',0,118,0,''),(117,'俱乐部金币变化日志','Club/money_change_record',0,118,0,''),(118,'俱乐部管理','',0,0,8,'fa fa-group'),(119,'游戏输赢设置','Game/jackpotconfig',1,18,0,''),(120,'意见反馈','wechat/feedback',1,112,0,''),(122,'俱乐部火币变化日志','Club/firCoinChange',0,118,0,''),(123,'货币日志变化','',0,0,10,'fa fa-calendar'),(124,'平台数据统计','',0,0,12,'fa fa-line-chart'),(126,'统计图表','Hall/statistical_chart',0,18,8,''),(127,'邮件与公告','',0,0,3,'fa fa-bell'),(129,'银行管理','',0,0,11,'fa fa-bank'),(130,'银行统计','Hall/bank_count',0,129,0,''),(131,'代理申请页面','agent/register',0,15,7,''),(132,'大厅登入记录','User/logon_record',0,9,2,''),(133,'玩家列表','User/user_list',0,9,1,''),(135,'代理反馈问题','Agent/feedback',0,15,8,''),(136,'支付配置','Mall/payConfig',0,19,6,''),(137,'游戏登入记录','User/game_login',0,9,2,''),(138,'活动','Hall/broadcast',1,18,8,''),(139,'分佣日志','Commission/moneyChangeRecord',0,123,0,''),(140,'奖池数据','RewardsPool/rewardsPool',0,123,0,''),(141,'平台统计','commission/data',0,124,0,'');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理申请表';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理提现记录';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理审核';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请码绑定表';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='代理配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_agent_config`
--

LOCK TABLES `web_agent_config` WRITE;
/*!40000 ALTER TABLE `web_agent_config` DISABLE KEYS */;
INSERT INTO `web_agent_config` VALUES (1,'recharge_commission_level','0','充值返佣级别 0为无限级'),(2,'agent_level_ratio_1','60','一级代理获得分成比例 百分比'),(3,'agent_level_ratio_2','50','二级代理获得分成比例 百分比'),(4,'agent_level_ratio_3','40','三级代理获得分成比例 百分比'),(5,'exchange_proportion','100','兑换比例 1:value'),(6,'put_count','4','每日提现次数'),(7,'put_min_money','60','每次最低提现额度'),(8,'agent_notice','至尊娱乐棋牌','最新公告');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='兑换记录';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理反馈';
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
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
  `login_count` int(11) DEFAULT '0' COMMENT '代理登录次数',
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
  UNIQUE KEY `userid_2` (`userid`),
  UNIQUE KEY `userid_3` (`userid`),
  UNIQUE KEY `userid_4` (`userid`),
  KEY `userid` (`userid`),
  KEY `username` (`username`),
  KEY `agentid` (`agentid`),
  KEY `status` (`status`),
  KEY `register_time` (`register_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='代理会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_agent_member`
--

LOCK TABLES `web_agent_member` WRITE;
/*!40000 ALTER TABLE `web_agent_member` DISABLE KEYS */;
INSERT INTO `web_agent_member` VALUES (1,'15656569696',120050,'e10adc3949ba59abbe56e057f20f883e',1,'','597042','',1546840302,2,1546849977,'127.0.0.1',NULL,'asdfasdf','6211111111111111111',0,0,0,0,NULL,0,NULL,NULL,NULL,NULL,1,95),(2,'15555555555',120051,'e10adc3949ba59abbe56e057f20f883e',1,'','915874','',1546851417,0,NULL,NULL,NULL,'asdfas','6211111111111111111',0,0,0,0,NULL,0,NULL,NULL,NULL,NULL,1,95),(3,'15625652365',120052,'e10adc3949ba59abbe56e057f20f883e',1,'','247135','',1546854133,1,1546854175,'127.0.0.1',NULL,'arfwqef','6211111111111111111',0,0,0,0,NULL,0,NULL,NULL,NULL,NULL,1,95);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理后台充值记录';
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='充值配置新';
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
) ENGINE=InnoDB AUTO_INCREMENT=767 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_api_record`
--

LOCK TABLES `web_api_record` WRITE;
/*!40000 ALTER TABLE `web_api_record` DISABLE KEYS */;
INSERT INTO `web_api_record` VALUES (1,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194667\",\"deviceType\":\"1\"}','ODBjYjU3MTAzYjM5ZTI0MTJjOGYzZDVmZDE1YWFlZTk=','192.168.0.86',1547194431),(2,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194727\",\"deviceType\":\"1\"}','ZWI0MTMyNmZiMjNhNGE2NTY4ZGMxM2U2MjQzM2UxNjE=','192.168.0.86',1547194491),(3,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194787\",\"deviceType\":\"1\"}','NGQ3NjBiYzU5NDVmOTViODE2OTBiYTVjZmE5NmNiOTA=','192.168.0.86',1547194551),(4,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194847\",\"deviceType\":\"1\"}','Y2RkNjY1YzdjNTU5NWE1ZDdmNDRmOTE3NjNiYzdiYzk=','192.168.0.86',1547194611),(5,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194907\",\"deviceType\":\"1\"}','MDgzZjA4Yjk4MzRkMjJlMmFiYzhjN2FmZjM0MGE1YzY=','192.168.0.86',1547194671),(6,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547194967\",\"deviceType\":\"1\"}','MDUwYzQ4OGM4OTEzZmJhOWNmYjc1YjM5ZDk4Y2RkODg=','192.168.0.86',1547194731),(7,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209311\",\"deviceType\":\"1\"}','MmE2MzU2YmNlMTkzYjFiMTk2N2IwODA2MmI3MWFjZGM=','192.168.0.86',1547209076),(8,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209371\",\"deviceType\":\"1\"}','MTE5OGNjNWRhZWExZDUyZmVjNDViMTU0NTIyMmU0Yzc=','192.168.0.86',1547209136),(9,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209404\",\"deviceType\":\"1\"}','OWRkYTgwZTFiNDEwNWM4MTNlNjI2MGI4Zjk5MDgwMzg=','192.168.0.86',1547209169),(10,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209433\",\"deviceType\":\"1\"}','NzdjOTkwMWUwOTY5MDc4ZTFiMjhjZTg4NTZhZDkzNTg=','192.168.0.86',1547209198),(11,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','MzUzMjdiMTY0NTA4NWFjNTU3MmVjODRmYzNhNWMxNzA=','192.168.0.86',1547209199),(12,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','Njg0ZjIwYWFhMzE3MTU2NjZkZTYzYzY4MjU3NWRhM2E=','192.168.0.86',1547209199),(13,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','YzQwNzhlMjE4ZmEwMDdkMjliYmQ2N2ZjMTZhY2NhYjQ=','192.168.0.86',1547209199),(14,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','ZWIyMGEyZGM0Yjg4MmQ4MjI5ODhmNDJkYmM5MDRmNzE=','192.168.0.86',1547209199),(15,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','ZjBmMzM5MGUyYTE4NGJiMzc5MWRkNTIxYTdiNzhmZmQ=','192.168.0.86',1547209199),(16,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','NmM1ZDA1ZWVkY2Q2MzM1ZjU1ZmEyZTY2MTk0ZDk4NmY=','192.168.0.86',1547209199),(17,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209434\",\"deviceType\":\"1\"}','ZDJmYzc1OTIxNjVjNmY1YzFkMTFjOTA4ZDdkZjg3YTI=','192.168.0.86',1547209199),(18,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209439\",\"deviceType\":\"1\"}','YmFiNzg0NmUyYTVhZWVkNGYzNDY0ZDQ5ZTJkYzE0NGU=','192.168.0.86',1547209204),(19,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209439\",\"deviceType\":\"1\"}','M2UwMTczNzI0YmU0NTA0NTBiZTIxNTA0NzU1NmEzOTI=','192.168.0.86',1547209204),(20,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209439\",\"deviceType\":\"1\"}','MzZjMjBjZjQ5MDc3NTI2NzVjNjczMTk2ZGU0YzY3NTE=','192.168.0.86',1547209204),(21,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209440\",\"deviceType\":\"1\"}','Y2IyYmUwZDA3YzYzZjE5NDE5Y2E3ZDgzZmI2OGZhOTE=','192.168.0.86',1547209205),(22,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209443\",\"deviceType\":\"1\"}','Mjg1ZjcwMzgxYjY1MGRkMDhmNGIwNjk2ZmRhNDg1ODk=','192.168.0.86',1547209208),(23,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209498\",\"deviceType\":\"1\"}','MmI0MzZmMTJhNDVhZjQ2M2Y1NmU5MjUzMmZhODlmMDY=','192.168.0.86',1547209263),(24,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209559\",\"deviceType\":\"1\"}','ZjFiOGVlNTZlN2NmOTIxNGEzMDllOWEwNDY1MWQ5NTE=','192.168.0.86',1547209324),(25,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209619\",\"deviceType\":\"1\"}','M2UyYjA1MGVjOTY3NGU0ZDk0YmRmZjExYzY1YzM3NjE=','192.168.0.86',1547209384),(26,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209679\",\"deviceType\":\"1\"}','OGFhNDdhMDY1NjdmODA4N2JhZjQ5OGI5YjlmNDJiNzA=','192.168.0.86',1547209444),(27,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209732\",\"deviceType\":\"1\"}','OTM5MzUxYzJjZDMyZDEzN2U5N2VmYjhkYTYwZWY4MDE=','192.168.0.86',1547209497),(28,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','ZWY4OGIzZjg3Y2FjYmViYzFkNDcxZTkwMmM4NzQyNmY=','192.168.0.86',1547209498),(29,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','YzVmNWUzMjA0ZTZlMzA2ODI2MDRmNDU2MDQ0ZTVlZGU=','192.168.0.86',1547209498),(30,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','ZTJiNzllMzc1ZDg2YzJjYzZiZjhiN2ZlNjU0Mzk4Nzg=','192.168.0.86',1547209498),(31,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','YWQ5Yzk4M2UxYzk5MDhkYjZlMmNhMzc3NjE0ZjkzODg=','192.168.0.86',1547209498),(32,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','ODFlNDVjNjg2ZjdhZTFkYWM2NDZmYTExYmFlZTM1Y2I=','192.168.0.86',1547209498),(33,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','Yzg0MjI5ZDVmY2E4M2FhNTFlMjg2ZWE3MmZkMmQ4MWQ=','192.168.0.86',1547209498),(34,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209733\",\"deviceType\":\"1\"}','Y2FmZDcwYzRiYjNkNmNmM2JhMzlhNzEwZmI3Zjc0OGE=','192.168.0.86',1547209498),(35,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209736\",\"deviceType\":\"1\"}','NTYwNGNiNTk0ZTY5MGExOTUxODdhYjY4MTAwNDg2ZWY=','192.168.0.86',1547209501),(36,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209736\",\"deviceType\":\"1\"}','ZjMzZWU5Zjk0ZGQzMDkzMGVkYzkyYjM0MTkwOTNkMjA=','192.168.0.86',1547209501),(37,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209737\",\"deviceType\":\"1\"}','ZmJiOTRiYTJiMjYwZWEzNmM0ZGM4ZTUwZTJlNzk1NGE=','192.168.0.86',1547209502),(38,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209737\",\"deviceType\":\"1\"}','Y2MzYWYwODc4MTk1YzFmNGVlNWY5NWM1ZTIxZmVmMTQ=','192.168.0.86',1547209502),(39,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209739\",\"deviceType\":\"1\"}','YmM2NjZhNzRiMTAyNTczMGE4NTJjOTQxYzZlMjJlYjQ=','192.168.0.86',1547209504),(40,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209740\",\"deviceType\":\"1\"}','NWI0NjQ4NTg0OTA4NDUzNmQwYTUwNmUxOGZhZDRmMWM=','192.168.0.86',1547209505),(41,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209782\",\"deviceType\":\"1\"}','MDhhNDE1OTg0MmRjOTYyYWZiZTQ1OWEyNGNkMzk4YTU=','192.168.0.86',1547209547),(42,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','N2ExNTdkYzM4NDhiZDQwNGNlMTBiNmIxMTY3MjZiMjg=','192.168.0.86',1547209548),(43,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','ZThjNzE0Mjk3YjhkMzllYmRkOWI2NTdjMzEzZjc5NDY=','192.168.0.86',1547209548),(44,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','NjE2MjAxNzE4MTM3NzAxNjA3YjBhZGZhMTk1OTc4YTk=','192.168.0.86',1547209548),(45,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','NTdiOTU4NDQ5N2NjZGQxMGQ5NDYyYWFlY2UwYzFmNGQ=','192.168.0.86',1547209548),(46,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','OTQyMzRlM2U0ZTY1MzIwN2RlODc2NmVlMzYzMmJkMDM=','192.168.0.86',1547209549),(47,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209783\",\"deviceType\":\"1\"}','Yjk0MWE1NTE5ODljNjZlMTE0NTI0MWU1YjNlNTdkN2Q=','192.168.0.86',1547209549),(48,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209784\",\"deviceType\":\"1\"}','MjI2YjM0ZjNkMWUyMjU3OTFhODY5ODMwN2UxZmNjNTQ=','192.168.0.86',1547209549),(49,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209786\",\"deviceType\":\"1\"}','NGJlYTViZTJjNWY3OGI2MmMyOGU5ZmFlZTAyZTEyNDk=','192.168.0.86',1547209551),(50,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209786\",\"deviceType\":\"1\"}','ZDAwMWQwNTZjZjVlNDhlODNlYjQyODI4NTdiODQ0NTQ=','192.168.0.86',1547209551),(51,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209786\",\"deviceType\":\"1\"}','MzEzNzk5ZTk0NDlmOTc1ZWI0OTg1ZjFlOWRmZjEyN2Q=','192.168.0.86',1547209551),(52,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209787\",\"deviceType\":\"1\"}','ZGNiNjBiNmIxM2FkY2I5ZjRmNmE0ZmY4MDc5OWUyZDg=','192.168.0.86',1547209552),(53,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209789\",\"deviceType\":\"1\"}','MTQ0MWJmOTNkNWNhZTJiZGViNGM3NGI0MDZkY2U1YmU=','192.168.0.86',1547209554),(54,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209790\",\"deviceType\":\"1\"}','ZGI3NjEyZGEwMDYxMmRjOTNjYjEyOWZlZDhmZGY4YWY=','192.168.0.86',1547209555),(55,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209828\",\"deviceType\":\"1\"}','Y2M2OWFiNTdiM2RjOTEyM2QxZTg4ZjRhNGZmMWFiNTI=','192.168.0.86',1547209593),(56,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209846\",\"deviceType\":\"1\"}','YmU4YjZiODgyOWUyNzY0N2M2MzVhZTlkN2UwZGRkZGQ=','192.168.0.86',1547209611),(57,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209857\",\"deviceType\":\"1\"}','NGQwZmRmMWQwZWFkYzE1NDY1ZWFlMTM1YWJmZjYyM2Q=','192.168.0.86',1547209622),(58,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209906\",\"deviceType\":\"1\"}','YjQ1Y2Y0MmZmOGI0ZTA1MGVmM2JlM2JkZmE4YTA5NDI=','192.168.0.86',1547209671),(59,'{\"api\":\"User\",\"action\":\"getSumUserOnline\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547209966\",\"deviceType\":\"1\"}','MGYxMmUxZGFlNjQwZDIzZmNkODVlYWY1NGQ5MjRkZjc=','192.168.0.86',1547209731),(60,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210062\",\"deviceType\":\"1\"}','ZGQzOTFiZWI4NTI5NjY5OGJlMjc1NDY2ZDY3OTdmNjc=','192.168.0.86',1547209827),(61,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210063\",\"deviceType\":\"1\"}','NzgwNmFiYThhNDc0ZDE2N2Q0MDY4NmJiMGIzNmFmYTE=','192.168.0.86',1547209828),(62,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210063\",\"deviceType\":\"1\"}','OWFkMTc1ODFkMzVjMzE1NjdlNTdhYTNlYmY5MzZkMWU=','192.168.0.86',1547209828),(63,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210063\",\"deviceType\":\"1\"}','ZDU4MTUyZmJhNjMxYjkzOThiZmFmMGZhZGNkYWM1ODU=','192.168.0.86',1547209828),(64,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210063\",\"deviceType\":\"1\"}','NjY1OTk4Mzc2NzQyNDViNDllOGZlNjQzYmZmMjZiYTE=','192.168.0.86',1547209829),(65,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210064\",\"deviceType\":\"1\"}','YTIwZmI0NmJmMDMxMDBjNmQyMTg0ZDE2YmZlZjg4NTI=','192.168.0.86',1547209829),(66,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210064\",\"deviceType\":\"1\"}','Yzg5YjZkMmM4ODBhMDZlZDVkY2Y4NzM4ZWUwNjZkYzQ=','192.168.0.86',1547209829),(67,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210066\",\"deviceType\":\"1\"}','YzIwMTEyMDNlODNiZWZiYjM3ZDg3ZGQ4MmMwNTk1NjM=','192.168.0.86',1547209831),(68,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210066\",\"deviceType\":\"1\"}','MzcxMmUyZTkxMTYwYjUyMDU1YjdlMWYwN2FlNDlhMDQ=','192.168.0.86',1547209831),(69,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210067\",\"deviceType\":\"1\"}','ZjcwMWQyNTFhZGMwNWYzYWQyYjNiYTUwYjkwM2IwZGQ=','192.168.0.86',1547209832),(70,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210067\",\"deviceType\":\"1\"}','MTc5NjAzZTc3M2MwNGQ2MGNmYzdjZTNmYTlmMDFkYTk=','192.168.0.86',1547209832),(71,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210071\",\"deviceType\":\"1\"}','OWYzNjIwOTY2MjJjMjdiYmU5OGVlNmY4ZGIxYTY1ZTE=','192.168.0.86',1547209835),(72,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210077\",\"deviceType\":\"1\"}','NmQwZTU3OTExOWJjZTA5OWZkYmM4MmNiMjExNDI2YWU=','192.168.0.86',1547209842),(73,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210079\",\"deviceType\":\"1\"}','NmRiNjI3MzY0NGZlZTdiNjQ5YzI2MzZkNmJlYzYzNTI=','192.168.0.86',1547209844),(74,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210132\",\"deviceType\":\"1\"}','ZTcyZGVhODVlNDAwMWI2OTgzZWE3NGIxMThkYjM5MzI=','192.168.0.86',1547209897),(75,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210135\",\"deviceType\":\"1\"}','Y2VhZjBhODRmNDg5ODQ4ZTExMjZmMTVkYTRhYjZkZWM=','192.168.0.86',1547209900),(76,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210181\",\"deviceType\":\"1\"}','ZDcxZDIzNTgzODc2NjQ5ZWI1NWFhMTkzOTE1ZDNmMDE=','192.168.0.86',1547209946),(77,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210199\",\"deviceType\":\"1\"}','ZGJhNzgyMWQ5MDJhMWQ4NDNhNmRlYzFjOWZjMjZiNTk=','192.168.0.86',1547209963),(78,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210210\",\"deviceType\":\"1\"}','MmIzNzI2OTRmOTVjNTA0NWFjM2NkMTY2YTg5N2ZlOTg=','192.168.0.86',1547209975),(79,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','YWEzMjU0OGMxNDA5YjgzZTM5OTJlY2U3MjY2NWJlYzY=','192.168.0.86',1547209976),(80,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','ZDdiZTE0OTY0MWExODVlNjNjZWEyMjM1NWE3MjViODk=','192.168.0.86',1547209976),(81,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','YTFlZjc1N2NlMzRkNjJkMGUxNDAyMWQ1M2YwMTBmNmM=','192.168.0.86',1547209976),(82,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','YzBjMzg1ZjNiODcyNTU0OTM3NzBjZDllNzUyZDQ2MTY=','192.168.0.86',1547209976),(83,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','NjlmZWFmMzIzZTIwZWUwOWJmMGZkNDVkYmU1OGJmZjI=','192.168.0.86',1547209976),(84,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210211\",\"deviceType\":\"1\"}','YWYwMmU0MWMxYmFkNTBmOGQ5YTY3Yzc1YmVkNDMzZjA=','192.168.0.86',1547209976),(85,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210214\",\"deviceType\":\"1\"}','NGQ3ZmY0YWVmMzJjMjFkOWUwZTIzMWRhMGI4YjMzMmQ=','192.168.0.86',1547209978),(86,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210214\",\"deviceType\":\"1\"}','ODJkOTljYTI2MWZjN2I1OTdiY2IyM2RmMDE4YzJjNDQ=','192.168.0.86',1547209979),(87,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210214\",\"deviceType\":\"1\"}','ODI4OGY0ZjMzMmY3NTM1MGQ3MTY4YmFmNTJjZjJlMWM=','192.168.0.86',1547209979),(88,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210214\",\"deviceType\":\"1\"}','Zjk3MDVlYzI2YjJmYjIyMGQ1ZTQ1NmM1MmVjZjc4NDU=','192.168.0.86',1547209979),(89,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210215\",\"deviceType\":\"1\"}','MDUxY2E1ZTYwNjg5N2Y0OTA5OTM0NGU1NjcyM2VmYmM=','192.168.0.86',1547209980),(90,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210216\",\"deviceType\":\"1\"}','ZGU0YmI0NjA0MTZiOTYyNmQwZmEwNzM5N2Y4MGViNTI=','192.168.0.86',1547209981),(91,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210218\",\"deviceType\":\"1\"}','MmQ5MDc0Zjk2ZTg3MzliMmI4OWM0ODZlNjIyNDdlZTA=','192.168.0.86',1547209983),(92,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210273\",\"deviceType\":\"1\"}','NGFlYTRlYzRiMTc3MDQ0NmYwZjFlZjVhOGIwNzY3Yjg=','192.168.0.86',1547210038),(93,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210332\",\"deviceType\":\"1\"}','MDg1MjJlYjcxMDg3YzI5NzZlNTE4OGE2MzRkNzNmY2I=','192.168.0.86',1547210097),(94,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','NmNhYjliYjI1ZDI3YzEzZGZkMDI2MzdiZjYzNTgwZjA=','192.168.0.86',1547210098),(95,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','NDJkNjMwZjgxMDc2OTJkZmNhYzA2NTNlMGY1NmY3ZGU=','192.168.0.86',1547210098),(96,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','MjQwNWI1YmYxZDM5ZTg2NjY0MDgwNjY1ZDI3ZGMyYjk=','192.168.0.86',1547210098),(97,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','NmViMDU4Yzk5ZmI0Yzc0MTA0MzY5NzI1NjlmNzRiYTk=','192.168.0.86',1547210098),(98,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','YTBmN2FjOTYyYjMwNzczNWFjYjE0ODllYTY2OGY3ZTE=','192.168.0.86',1547210098),(99,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210333\",\"deviceType\":\"1\"}','MGVjMzk0NzY3ZDcyZjdiZjMxZTAyMjQ1ZWNmMmZkZTc=','192.168.0.86',1547210098),(100,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210334\",\"deviceType\":\"1\"}','ZmUxOWM3NTQ3M2QwM2YxNmZmZjBmZjkyOTRhZmMwNWE=','192.168.0.86',1547210099),(101,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210336\",\"deviceType\":\"1\"}','Y2FlNzMyMzE4NjIxYjg1NDE3YWNiMjk1Mjg5ZmM4MWQ=','192.168.0.86',1547210101),(102,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210336\",\"deviceType\":\"1\"}','NWE4NGY4YTFlMDNhYzJmYWZkMzdkM2ZhNTI3YTQ0YzQ=','192.168.0.86',1547210101),(103,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210337\",\"deviceType\":\"1\"}','NjFhOWViZGZlMzBiMWIyODhlZDA5N2MyZmU1OTA5ZDU=','192.168.0.86',1547210101),(104,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210337\",\"deviceType\":\"1\"}','ZDU0ZmVkMDgxN2ZlOWFlODMzNzI5MzViZjlkYWU2YjI=','192.168.0.86',1547210102),(105,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210340\",\"deviceType\":\"1\"}','YjYyNDMxNDBmZWJkODgyNjllZWNhYzI5YjAzYjQ4MjM=','192.168.0.86',1547210105),(106,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210341\",\"deviceType\":\"1\"}','MWVmN2Y4N2QwMzk2ZmM4OTQyODdjMjIzZGEzZjMxNjg=','192.168.0.86',1547210106),(107,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210384\",\"deviceType\":\"1\"}','NjZjNTY1ZWQzMmJiYTI1NWFhODAzYTkyN2Y4ZDNkMzM=','192.168.0.86',1547210149),(108,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210404\",\"deviceType\":\"1\"}','NGI0NGNjOWYxMTc2MWU5YjI4OWE0ZTQzM2Y3ZWU0YTY=','192.168.0.86',1547210169),(109,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210479\",\"deviceType\":\"1\"}','M2Y3YjVlNDBmNDJkNTY0OTg0OTM3M2Y5MTM2NGU1MGQ=','192.168.0.86',1547210244),(110,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210481\",\"deviceType\":\"1\"}','NzJkZTQ4YzE2ZjBmMWRmOWUwNDc0YzYwYjVmMTBmMDc=','192.168.0.86',1547210246),(111,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210481\",\"deviceType\":\"1\"}','NTk2MjdjMGQyNzY4OGNjZDZhMzhmM2VhMjdmZTQwM2I=','192.168.0.86',1547210246),(112,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210481\",\"deviceType\":\"1\"}','NzI0ODhlNjZmN2M3ODNjZGRkYzQ1ZThiNWUxNjQ1ZWM=','192.168.0.86',1547210246),(113,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210481\",\"deviceType\":\"1\"}','M2I2N2YxMDBlOTZiZmM2YjUzNzQ2MTM2Y2ViYjczNDE=','192.168.0.86',1547210246),(114,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210481\",\"deviceType\":\"1\"}','OTU5ZDkzYmFjMDkwZDhiODEzZDE4ZjAxODA2ZjQ3MjA=','192.168.0.86',1547210246),(115,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210482\",\"deviceType\":\"1\"}','MWU1MjA4YWE3ODBmZGY0YzVhNDk5YmE2NGNiZGU3NzY=','192.168.0.86',1547210247),(116,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210484\",\"deviceType\":\"1\"}','YWQyOGNhMzQxOTdhYjMwMjc1OGRmOGUzZjFmMzJmZGI=','192.168.0.86',1547210249),(117,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210484\",\"deviceType\":\"1\"}','Y2ExY2YyOTM2Yzc5Y2IxNzhhYWZiODczNjM4MDk2OTI=','192.168.0.86',1547210249),(118,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210485\",\"deviceType\":\"1\"}','MTMyZTc3OGE1NjZmNmMxNjUzZTU1MzdhNjgxYmNkNzE=','192.168.0.86',1547210250),(119,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210486\",\"deviceType\":\"1\"}','Nzk0OWY3MmU2NjhjMjYwYzc4Njg2YjEwMDcyNmNkNTE=','192.168.0.86',1547210251),(120,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210488\",\"deviceType\":\"1\"}','ZDA3NmRkNzIxODQ2NzY3ZTIxYjAxMmUyNzI0Y2ZiMGI=','192.168.0.86',1547210253),(121,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210489\",\"deviceType\":\"1\"}','ZWM0NjA0MTA5YTgxODJiNDkzYmVkNDQ1ZTM0NDk0MmI=','192.168.0.86',1547210254),(122,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210490\",\"deviceType\":\"1\"}','NzE0YjczZDUxZTFmNjRjMGE0OWQwNGFlNjMyNTliNmQ=','192.168.0.86',1547210255),(123,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210506\",\"deviceType\":\"1\"}','ZjMyZTlmYTc3ZmM2OTE4NzQ4OTI5Yjg4ZGI5NmJiNTQ=','192.168.0.86',1547210270),(124,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210518\",\"deviceType\":\"1\"}','ZGZlODM4ZTBiMTgzZWE5ZDVmNWE3ZGJlZWYxMGM5Yzk=','192.168.0.86',1547210283),(125,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210573\",\"deviceType\":\"1\"}','NmViZDJmMzQwYzljNmJjZjZkNThlYmNlNmRjM2I0Y2U=','192.168.0.86',1547210338),(126,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210574\",\"deviceType\":\"1\"}','YzkwNTMzNzM2NWZhYjRjN2U3OTIwMzRmMzNiMWQ1NjE=','192.168.0.86',1547210339),(127,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210574\",\"deviceType\":\"1\"}','OWNlZjhmNTRhM2Y3MTBiNWM5NjEzNWIzMGRjM2NjOTg=','192.168.0.86',1547210339),(128,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210574\",\"deviceType\":\"1\"}','YTJhMTI3NzBjMWQ0MzFkMThlYjA1MDUxYmI3NjI5Mzk=','192.168.0.86',1547210339),(129,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210574\",\"deviceType\":\"1\"}','N2QyNGY5MmVhNDQ4OTc2MTIyNzY5YjRiNmVjNWI5ZDc=','192.168.0.86',1547210339),(130,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210574\",\"deviceType\":\"1\"}','NDIyY2U1ZWM5M2QyOWEwN2IwMzI1ZDYwYjViOGMxODk=','192.168.0.86',1547210339),(131,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210575\",\"deviceType\":\"1\"}','NDVjNTUxZGI3NjYxNGVlZDQwNGY3MGVhN2JiMmE5NWI=','192.168.0.86',1547210339),(132,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210577\",\"deviceType\":\"1\"}','MWVlZWIyYzMzZDQ3ZjNmNjQzMmIzYjg2MjJlZTk4MDE=','192.168.0.86',1547210342),(133,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210577\",\"deviceType\":\"1\"}','MWMxZjMzNTQ4M2QwMmU4MjhmNTJhYTM2MjU0NDE3Yzc=','192.168.0.86',1547210342),(134,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210577\",\"deviceType\":\"1\"}','MDdhM2ZhZDhkYjBlYWZhNmU5YTUxMDNkOTIwZThlYzU=','192.168.0.86',1547210342),(135,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210578\",\"deviceType\":\"1\"}','Mjg5MTY4YjczYjQwYTE2ZjU3MzVhMTJiZDE4OTAyNTc=','192.168.0.86',1547210342),(136,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210578\",\"deviceType\":\"1\"}','M2JhZjIwYzIzZGYxNTdmYWEyNDY4NjkyMGJjNGYxYzA=','192.168.0.86',1547210343),(137,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210580\",\"deviceType\":\"1\"}','NjNkMTdjZDk4YTkxZWRhMzcwYTg5MjVlNDRmMjNlMzc=','192.168.0.86',1547210345),(138,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210582\",\"deviceType\":\"1\"}','MzRjYzBjNzVlMTMzNmQ1ODg5ZWUwNGE2YWYyZTA1NjI=','192.168.0.86',1547210346),(139,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210693\",\"deviceType\":\"1\"}','YjMzM2YwNmNlMDAyN2QwYmI3OThkM2UyMGU4ZDZjMjc=','192.168.0.86',1547210457),(140,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210754\",\"deviceType\":\"1\"}','MDY2ZTQwY2FhNjhhMTdmOGM5MWI5YmYyZTcwYWM5NTE=','192.168.0.86',1547210519),(141,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210755\",\"deviceType\":\"1\"}','YjcwNmFmYmIwYmJkMWU5MDhhMWQyOTQyYTJjZDcyOTA=','192.168.0.86',1547210520),(142,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210755\",\"deviceType\":\"1\"}','ZjY1NzZkZjY1ZGJmNmY3YzYwZmY1NjI5ZTc0MWMzZDE=','192.168.0.86',1547210520),(143,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210755\",\"deviceType\":\"1\"}','NWNlNTQyODNmOGMxN2ViMDA5MDEzOTljNGJiOGQ4MzQ=','192.168.0.86',1547210520),(144,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210755\",\"deviceType\":\"1\"}','ZWY5YjQ3YWM5ZjMxZmRlN2E4OTc1OTk0ZGMwNzUxZmE=','192.168.0.86',1547210520),(145,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210755\",\"deviceType\":\"1\"}','NmE2ZDhhY2M5ZmFmYjUxOGU0MzQ2M2M2YzdjYzRlYjA=','192.168.0.86',1547210520),(146,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210756\",\"deviceType\":\"1\"}','OGUyYWI0MzRmMWIyNzcwNGY1ZjQ0OGY2NDNmMmU0ZTU=','192.168.0.86',1547210520),(147,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210760\",\"deviceType\":\"1\"}','NjAyZDZkMGJkZTAxZDBiZGU0MzEyMjhjMWZhMGRkZDc=','192.168.0.86',1547210525),(148,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210760\",\"deviceType\":\"1\"}','Y2ZkOWQxZWVkMzZmNGVkOWZhM2EzMThlMzE5MWIyODg=','192.168.0.86',1547210525),(149,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210760\",\"deviceType\":\"1\"}','YTViYzkwNThkN2VkMmI3YTY1MTU0NmZkMjg1YmU4YzE=','192.168.0.86',1547210525),(150,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210761\",\"deviceType\":\"1\"}','NDlkMTAwYTUyN2IxMWFkNGMxYTk4OTBlNTcxNDIyOWQ=','192.168.0.86',1547210525),(151,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210761\",\"deviceType\":\"1\"}','N2I5MjFiMGVkZTQyNjAyNjk3ODU3MGMyMzEwZjY5YTg=','192.168.0.86',1547210526),(152,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210762\",\"deviceType\":\"1\"}','ZDI0ZWEwMWU2ZGQ5OGI0ZDRjNDBmNGU4Yzk2YzMxMDU=','192.168.0.86',1547210527),(153,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210764\",\"deviceType\":\"1\"}','M2FiZmFjZjM0MzEyYTUyM2MyNzE4Mjg0MWRlNzI1ZWE=','192.168.0.86',1547210529),(154,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210772\",\"deviceType\":\"1\"}','NWZjMjVmYWM2YjRmYWZkZGI4NTM3ZTM0ODBmZWRmOWI=','192.168.0.86',1547210537),(155,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210829\",\"deviceType\":\"1\"}','M2I5OGUwMmJkYjIwMTRiNmQ5ZGJlNmNhZTRlZjIzNWU=','192.168.0.86',1547210594),(156,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210832\",\"deviceType\":\"1\"}','ODM4ZGUxNjNkNjI4OTZjMDU3NjZkZTg5Y2ViY2JkZGI=','192.168.0.86',1547210597),(157,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210837\",\"deviceType\":\"1\"}','NzBlMWE4Mjg5ZTA3NWMwYTFmMDhmMDc4MDk4Zjk5MGI=','192.168.0.86',1547210601),(158,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210902\",\"deviceType\":\"1\"}','OTc5Mzg4YTRmODUwYzMzMTIyMGFkYzVlNGMzYWIxN2Y=','192.168.0.86',1547210667),(159,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210906\",\"deviceType\":\"1\"}','NzgyZmNmZjVlMDY0MjMwNDMwZGUwODE2NDQ2OTA5MTM=','192.168.0.86',1547210671),(160,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210909\",\"deviceType\":\"1\"}','MTE3OGNmZWExZGI0MDNmNWY0MDRhM2Q4MDE2ZjVhN2Y=','192.168.0.86',1547210674),(161,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210912\",\"deviceType\":\"1\"}','NzdlMmIzODg0OGVkMDA1MDE5OWY5ZTJlYzFkYWVkZGI=','192.168.0.86',1547210677),(162,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547210977\",\"deviceType\":\"1\"}','ODRhYTJlZWEzMjQ2NTM0NGI1NDRmMjhjNjJmZjhiYTY=','192.168.0.86',1547210742),(163,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211035\",\"deviceType\":\"1\"}','NGQ3MDhhYzJiNWZjYTU0M2Q2ZTg0MjgwMTRlYjNmNWI=','192.168.0.86',1547210800),(164,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','ZTc5NGFmMmIzY2E3Y2FkYzYyOTFkMDc5NzhkYWJjMmU=','192.168.0.86',1547210801),(165,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','ZWEzZWFhZGQ0NmQwODVlYWRiMzQxZTEwZmNhNWM4Njk=','192.168.0.86',1547210801),(166,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','MjBlNzI0NWRhNTU1ZjA3NTlhY2NkMGExMjEwYmM2NGQ=','192.168.0.86',1547210801),(167,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','NDZkM2JmZWU5MmViZGQyZTgxMTQyM2FkMzhlNDgyNDk=','192.168.0.86',1547210801),(168,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','ZjUxNDY0NTM0NWI4MzUyYzhiMGUyNGRhMjhiMTA2YTU=','192.168.0.86',1547210801),(169,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211036\",\"deviceType\":\"1\"}','NGIxZjhlZmVlN2U5NTQ5MzcyOWY0OGIwOTJlOWFkMWQ=','192.168.0.86',1547210801),(170,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211039\",\"deviceType\":\"1\"}','MjMyZDMxYjNkM2QwODllMGJjNDQzYmVhZmViZTNhY2I=','192.168.0.86',1547210803),(171,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211040\",\"deviceType\":\"1\"}','YzEzM2U5NjY3ZmY2MGRhOTY3YTE1NTFhNjU1NTZjNWI=','192.168.0.86',1547210804),(172,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211040\",\"deviceType\":\"1\"}','ZjJhYTc3NTNmNjc5ZmRjNDk4YTczYzQ3NzM2YzdhOGE=','192.168.0.86',1547210804),(173,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211040\",\"deviceType\":\"1\"}','ZDg0ZDY2N2ViNWRmMDM2ZjUwMmUxZDJlOTE4YTBjYTc=','192.168.0.86',1547210805),(174,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211041\",\"deviceType\":\"1\"}','NTI2MjJmYjljOTNiNjAyNDZiMWZhMjQ3OTQ2NGUyN2E=','192.168.0.86',1547210805),(175,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211042\",\"deviceType\":\"1\"}','NzRhOGJhNTE2NTdiZjFmOTAwZjUwOTA2N2I4MGNlYTI=','192.168.0.86',1547210807),(176,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211044\",\"deviceType\":\"1\"}','MmIzNDA1ZmM2YTY3YjNkM2U3ZDI4NDZmMmU4OGQ0YTE=','192.168.0.86',1547210809),(177,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211048\",\"deviceType\":\"1\"}','ODhmNDIyNWI3MjI1MzZiMGM5ZDliYmEzOWRjOGZhNGI=','192.168.0.86',1547210813),(178,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211052\",\"deviceType\":\"1\"}','OTc3OTY5NDZiNjViZTM1MzE3ZDgxNDVlNDJjMTU0ODg=','192.168.0.86',1547210817),(179,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211057\",\"deviceType\":\"1\"}','ZjcxMzNjMjhkZGMwNWY2MTQyY2Y4ZTM4NzRiZjg3ZjA=','192.168.0.86',1547210821),(180,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211060\",\"deviceType\":\"1\"}','ZmYzZTM0NmY3YWQ4Mjk4MzkwNDFmNTZkMDY3YjljY2M=','192.168.0.86',1547210825),(181,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211179\",\"deviceType\":\"1\"}','MDNmOTdjOGU0OGY1OWJhZDg1ODg1ZjJkMzg5MzMwMzM=','192.168.0.86',1547210944),(182,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211201\",\"deviceType\":\"1\"}','ODdmMmFkZTBjMWNjMTMyNTE2NWMxMzUzYWZmN2NjYTE=','192.168.0.86',1547210966),(183,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211201\",\"deviceType\":\"1\"}','ZWRmZmZiNzczNjA0MzMxNzU5MzVjYjViZjQ0NjA4MDM=','192.168.0.86',1547210966),(184,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211261\",\"deviceType\":\"1\"}','OWU1M2Q5NGRkYjY1NzAxZmRkMjUxZTQ4YWNiZWExMzk=','192.168.0.86',1547211026),(185,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','MWQzZDNmMDExMjMzODcwMWY0Y2JlMDgwNzU4M2RiN2U=','192.168.0.86',1547211141),(186,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','ZTY4MTY0ODY1YWE4ZWU1Nzk1ZTQ4MGRiMzFjZDIxNGM=','192.168.0.86',1547211142),(187,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','MTk1NjdmNjRjNzA1NThjNGU5YzExMzJlZmIyMzUxYTI=','192.168.0.86',1547211142),(188,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','M2QxYTM3ZWZlMmQ5NzQwY2E4OWM0YzRmZjYzYzQ1YWU=','192.168.0.86',1547211142),(189,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','YzU4MjA1YTRhNGYyOTNhYmYxYjA0OTFlMjkxMDRhMmU=','192.168.0.86',1547211142),(190,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','YjcyOTY4YWUyOWYyZDI2NGQyNzI0MTkzNzJhMDAwZTI=','192.168.0.86',1547211142),(191,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211377\",\"deviceType\":\"1\"}','ODc1MWUyNWQzZTRhNTJjZDVmZWNjNzQwOWM0ZGMyNTA=','192.168.0.86',1547211142),(192,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211380\",\"deviceType\":\"1\"}','N2VkMmNmY2QwODRhOTQ1MTYyNWRhZjk4ZjhhYTA4Nzc=','192.168.0.86',1547211145),(193,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211380\",\"deviceType\":\"1\"}','NWZhOTYzODg0OGQ0YTY5OGU3ZGEyZWU1ZTYwMDU2Y2E=','192.168.0.86',1547211145),(194,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211380\",\"deviceType\":\"1\"}','NTE5YjI1Mzg5Nzc4MDcwOWQ0NzdhMTg1YzZlNjc4YmQ=','192.168.0.86',1547211145),(195,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211381\",\"deviceType\":\"1\"}','MmNmNGRhNDVjZWI4M2M3YWZkMDg3MTAzY2ZhYTYwZmI=','192.168.0.86',1547211146),(196,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211384\",\"deviceType\":\"1\"}','MTE0YjhlNTQ0ZjgyNDg5ZmVjMWE0NmU2YTUzMDRjOTA=','192.168.0.86',1547211149),(197,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211393\",\"deviceType\":\"1\"}','ZTExNzc5ZWUxODg3NDcxNzBmNTFjOTEzZjhiNmJiZjM=','192.168.0.86',1547211158),(198,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211402\",\"deviceType\":\"1\"}','ZjBhMmE1ODc4ZGM5MGEzYTdmYzU4ODMwZDExMjdhM2Y=','192.168.0.86',1547211166),(199,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211435\",\"deviceType\":\"1\"}','ZTljOGNiNDg5MWU5MDcyMjVlNmZkZTMzN2Y3MDMyNDQ=','192.168.0.86',1547211199),(200,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211445\",\"deviceType\":\"1\"}','MjJmNWIxZTI2OWE3MzkxOTBlNTY2NGZkNGVjNmRhMjM=','192.168.0.86',1547211210),(201,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211515\",\"deviceType\":\"1\"}','NzlhZjZmOTM1NDE5NTIwMzdiNWU0OWI1ZTc3MGE3Mzc=','192.168.0.86',1547211279),(202,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211562\",\"deviceType\":\"1\"}','ZmVjYjllNDIzMTRmZmYzOGIzYjFjYmU5OTQ1MWYzYTI=','192.168.0.64',1547211569),(203,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211563\",\"deviceType\":\"1\"}','MWU2M2ViNzUxOTUxOWJmNDg1ODQ1NzhmNjU1NDY2MjI=','192.168.0.64',1547211570),(204,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211563\",\"deviceType\":\"1\"}','ZTcxOGQ3ODE3NDhhNTBjZDc3MjNlMGUyNDhhYmU5ZWQ=','192.168.0.64',1547211570),(205,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211563\",\"deviceType\":\"1\"}','NDhjNTgyYzdiMDcyMGZiMTI1MzE0ZDgwYjRiNGUyNDM=','192.168.0.64',1547211570),(206,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211563\",\"deviceType\":\"1\"}','OTExNTliYWFhNjgyN2M4NzgyNTM3YjU2MzczNjU1Zjc=','192.168.0.64',1547211570),(207,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211563\",\"deviceType\":\"1\"}','MWVlNDY0ZDM0MjBiMDBhY2Y2OTY0NTQ2MTkxYzMxZmY=','192.168.0.64',1547211570),(208,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211564\",\"deviceType\":\"1\"}','ZWJhNDc5YzkwOTdlZjIwOTJlZDI3NDBiMGYxYTFmYjM=','192.168.0.64',1547211571),(209,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211564\",\"deviceType\":\"1\"}','YTlkNzIxNmFkMjQyYjExNGIxNTdjZmZiNDk1MGNkMjg=','192.168.0.64',1547211571),(210,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211564\",\"deviceType\":\"1\"}','YzMyODYzYWU1YmNkY2NhMWRmYTNmZGJkOTg4NDE2Yjg=','192.168.0.64',1547211571),(211,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211565\",\"deviceType\":\"1\"}','MTJmNDQ2NmI3MzY4OWFlYWNjYmQ4YzA0NTZhNWE2ZGE=','192.168.0.64',1547211572),(212,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211565\",\"deviceType\":\"1\"}','YmJlN2RkNDU3Y2M3ZWI2MTczMDYxYzA5Y2QyZmFjODE=','192.168.0.64',1547211572),(213,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211568\",\"deviceType\":\"1\"}','YTZmZWFmNjcxMzUyZTllZWQyMDMyNGJjMmUyMDYwMjM=','192.168.0.64',1547211575),(214,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','MGQyZjQxYTU3ZmJjZjM3ODU4MTg3YzUyOTRhOTNhMGI=','192.168.0.64',1547211807),(215,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','NTgyNTA1M2M5Y2NkMjg1OTgzZTNmYzkyM2M2ZjE1YzU=','192.168.0.64',1547211807),(216,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','ZDQ4MGQzMjU2NTYzYTdhYmNmMmNkNDVjNTkxN2Q0ZDU=','192.168.0.64',1547211807),(217,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','NTc2M2M2ZDgxZGE5ZmZlYzlmNzhkNzk3ODA5MzEwYWM=','192.168.0.64',1547211807),(218,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','NjRiOGIwMGYxMDE0YTc4NDA4MzE1YzRjNDcyZmI3MGQ=','192.168.0.64',1547211807),(219,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','ZWFkNTM1YzUzZjBkZGQwMzI4NzU5Y2JlMzA2MTJhODM=','192.168.0.64',1547211807),(220,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211800\",\"deviceType\":\"1\"}','ODEzNDFiYTljOWY0MzNkZjU1Y2M4YWY3MjVkNGViZjk=','192.168.0.64',1547211807),(221,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211801\",\"deviceType\":\"1\"}','NjJjZTg4NmQ1ODE2NWFiMGY0NGFlYmY3NTIzNzcwY2M=','192.168.0.64',1547211808),(222,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211801\",\"deviceType\":\"1\"}','YTYyMWNlZTE5ZjllYTY3NjZiMmFlOWExYWUzZmM2Y2U=','192.168.0.64',1547211808),(223,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211802\",\"deviceType\":\"1\"}','MGYxYjg2NTc5MDdlZDk0ZTAwZGI3OTIxZTkyM2E0NmI=','192.168.0.64',1547211809),(224,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211802\",\"deviceType\":\"1\"}','MGMwOTE5OWUzMTU4MTdjMGQ1YWMwZDk5OWY1OWVhYWU=','192.168.0.64',1547211809),(225,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211805\",\"deviceType\":\"1\"}','M2QwMDI0ZTJlM2JiZThhOGQyOGVjMzYxZGIwMWUxNTk=','192.168.0.64',1547211812),(226,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211827\",\"deviceType\":\"1\"}','MGNiMjkwZWEyYWVlM2YxYjdjOWUzMjYyM2RiOTUzOWM=','192.168.0.64',1547211834),(227,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547211850\",\"deviceType\":\"1\"}','Mjk3NDM2YTdiNjliODk0NjY2YzZmODNkZTgyYjEzZjI=','192.168.0.64',1547211857),(228,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212125\",\"deviceType\":\"1\"}','ZDA0NTZhZjcwYTU1ODMzMDA0ODRjZDI5MjdiOGQzNzc=','192.168.0.64',1547212132),(229,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','NjZjMzlmMDNmMWMzYjFjM2Y1OWU4NmQyY2MxY2QxYjA=','192.168.0.64',1547212133),(230,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','M2JkMjg2OWYzMWY1OThlMmRiYTEwNTBmNDgxODc5ODk=','192.168.0.64',1547212133),(231,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','YzkyY2UzOWQ4ZDBlZTExMzEzYWY1YjA3ZDdhZmVjMmU=','192.168.0.64',1547212133),(232,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','MGU5MGJmMDA1OTM3YjEyYTBhNjI0MzdiMWNlNTBkMmE=','192.168.0.64',1547212133),(233,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','NDVkODI4MWNmNTRmNWQyNDVmNWZmODk4M2FlZjdkMTk=','192.168.0.64',1547212133),(234,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212126\",\"deviceType\":\"1\"}','YmY1NDBlZGU2NGE0NDNiNTgwNTBhMDZhOTNlNTE3NDI=','192.168.0.64',1547212133),(235,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212127\",\"deviceType\":\"1\"}','YjMxNzdhYzRmYmIyNTU0YTkwOTAwYjM3MzlmYjVjMTM=','192.168.0.64',1547212134),(236,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212127\",\"deviceType\":\"1\"}','YmIxNjlmMTg1Y2Q3ZTA0N2JkZGRjOTA0N2UwOWQxNzc=','192.168.0.64',1547212134),(237,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212127\",\"deviceType\":\"1\"}','NTYxNTFjZTdmOTc2Y2FiNTU5YWVkMjY2ZDQ0NzAxOTk=','192.168.0.64',1547212134),(238,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212128\",\"deviceType\":\"1\"}','ZjdkMDJjM2ZhMmMzYjVkYjliOGNmZTNlMGZmYzBmN2U=','192.168.0.64',1547212135),(239,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212130\",\"deviceType\":\"1\"}','Mzg5ZmNmZGE3NmFkMmY4OWViNTUzOTc3N2FhOTZjMjU=','192.168.0.64',1547212137),(240,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212131\",\"deviceType\":\"1\"}','ZDY0ODFhMGVmYzhmZjc5ZWJiOTBhY2YwYmE4NzRkYTE=','192.168.0.64',1547212138),(241,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212384\",\"deviceType\":\"1\"}','ZjNkMGE3ZDRmM2RkMzZlYzE5YTQxOTJlMDU1YWJmOTY=','192.168.0.86',1547212148),(242,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212385\",\"deviceType\":\"1\"}','ZjE1NDA3ZTc4MjFkYjg3ZjhiOWY1ZDg0Y2YwMGZlMGQ=','192.168.0.86',1547212150),(243,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212385\",\"deviceType\":\"1\"}','YTM1YjllM2RjYTI2MjEzMjhjYjhjYzc3M2YyMDc0MDY=','192.168.0.86',1547212150),(244,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212385\",\"deviceType\":\"1\"}','YTIwM2VhZDMyNzg1N2RiMDc4ZGNiY2MwNjI5NGE0YjU=','192.168.0.86',1547212150),(245,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212385\",\"deviceType\":\"1\"}','NzFhNDY4ZDRlMWZkMDM1M2M0NDQ5ZGE3ZTlmN2E0ZWM=','192.168.0.86',1547212150),(246,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212385\",\"deviceType\":\"1\"}','Y2Q4OGY2YjliMDUxNDdiOGE5NjRhNzA3MjI1NmRiNmU=','192.168.0.86',1547212150),(247,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212386\",\"deviceType\":\"1\"}','MjhiMTYyODY5M2UxMjllYzYyY2FmNzFjYmQxNmI3OGY=','192.168.0.86',1547212150),(248,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212388\",\"deviceType\":\"1\"}','YTlhNzRiOGVhZTY2MDRkYTFjZWQ3ZmJjYjNiNTNlMGI=','192.168.0.86',1547212152),(249,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212388\",\"deviceType\":\"1\"}','YjZhNGZlZTJiZTE0NzExYjZkMTQyY2M0MmRjOWVkOTk=','192.168.0.86',1547212152),(250,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212388\",\"deviceType\":\"1\"}','MDA4ODQ3YjA1OGRlMDVmZTA4NmM4MzZmMjYxMTFkMzQ=','192.168.0.86',1547212153),(251,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212389\",\"deviceType\":\"1\"}','MDYzZjJjNjMyYmU0OWVkNTA2ZjM2OTM3MGU2ZWM2YmM=','192.168.0.86',1547212153),(252,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212392\",\"deviceType\":\"1\"}','NTI4MDMwZmJhYzlhNDE1YmE2NjYyYjQ2MmEzNTUxMGY=','192.168.0.86',1547212156),(253,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212393\",\"deviceType\":\"1\"}','NGQ1OTc4NThkYzRjMDU4ZDZkYWNmYWJhZDM5NDgyY2I=','192.168.0.86',1547212158),(254,'{\"api\":\"club\",\"action\":\"friendsGroupList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212153\",\"deviceType\":\"1\"}','NjQ2OTlhMGVjMjFlNmYxZjJkNmQ1YmJkMTljMWIwMzQ=','192.168.0.64',1547212160),(255,'{\"api\":\"club\",\"action\":\"friendsGroupRecommendList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212153\",\"deviceType\":\"1\"}','ZjFhNDgzOThhYmRjYjFlNTQ2MmExOWVlN2JlNjZlMjI=','192.168.0.64',1547212160),(256,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212173\",\"deviceType\":\"1\"}','ZDM5NzU4MDk2ODI5YTRmNTNhNmU2NDQwZjcxOGViMWM=','192.168.0.64',1547212180),(257,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212427\",\"deviceType\":\"1\"}','NTc5YTk1MWUwNmVlZjU1N2JmNjJmMWYxY2I5NTUwYWQ=','192.168.0.86',1547212192),(258,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','NDNmOGE5MTc2YWY0NzdlNjYzNWJmNjdlNzE1Y2M5NzE=','192.168.0.86',1547212193),(259,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','YjQ0NmY3YTJmZDRiYzZhNjQ5ZDY4ZmM4ZTU1ZWUwNGM=','192.168.0.86',1547212193),(260,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','Y2JlMTk5ZTgwM2E0ZjczYmUzNjk2MzA3NGQ2ZWUzNmM=','192.168.0.86',1547212193),(261,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','MjM5OGEwNzA1ZGNkMTRmMWEyZjg3NDA4ZjlkODRjZjE=','192.168.0.86',1547212193),(262,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','NmI4M2M3NGU3ZjlmMGJhZGUzOGM0NTM0YWExZGQ2Njg=','192.168.0.86',1547212193),(263,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212429\",\"deviceType\":\"1\"}','ZDIxYWNlNWRlM2Y2ZjJkZGJhM2YwNzVkYzdiYTYxNWY=','192.168.0.86',1547212193),(264,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212432\",\"deviceType\":\"1\"}','MDdkNWQ0YTg3NmVjNGRhNWQ4MDdhOWNmM2U5YThiZmY=','192.168.0.86',1547212196),(265,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212432\",\"deviceType\":\"1\"}','MGRhNzcxNzE3YzEzMWVlZjA3NTAzY2E2NTA1NmE4Njg=','192.168.0.86',1547212196),(266,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212432\",\"deviceType\":\"1\"}','YjJhODNkM2RkNzkxNTgzNjMyMzA3ODMyYzc0NmNjZDg=','192.168.0.86',1547212196),(267,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212433\",\"deviceType\":\"1\"}','Yzg1OTYwNDkyZmU0YzVmMjJjYWE2Y2FhNDNmMmJlNWU=','192.168.0.86',1547212197),(268,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212434\",\"deviceType\":\"1\"}','YTlkNTUzZGNhZWRmNjU0NTRiOTk0MzI3NzI3NTMyYjc=','192.168.0.86',1547212198),(269,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212436\",\"deviceType\":\"1\"}','NmVkMWZjMjZlZmQxMWQ2N2EyOGVkZWZhYjI1MTI2NDI=','192.168.0.86',1547212200),(270,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212442\",\"deviceType\":\"1\"}','NWMzNmJmZjUwODRiNThjMmVhYTgzNzMzNzhmYmExNmE=','192.168.0.86',1547212206),(271,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212204\",\"deviceType\":\"1\"}','YTE3MDBmZWUzZjdlNmM2ZTk1MjdkZGE0YmJkYjhjOTI=','192.168.0.64',1547212211),(272,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212204\",\"deviceType\":\"1\"}','ZmZlNjQ0NWVlZDZjMjEyYzEyY2M4MDZmMzk4YjM5YmY=','192.168.0.64',1547212212),(273,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212468\",\"deviceType\":\"1\"}','ZTZhZmIxYjg1ZjM0OTc5YWFmNzVhMGExMGM4NmQzM2E=','192.168.0.86',1547212232),(274,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212468\",\"deviceType\":\"1\"}','ZTlkMjEwYWUyZGNiMTEwNjc0MzAyMjc0YjEzZmI2MjQ=','192.168.0.86',1547212232),(275,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212240\",\"deviceType\":\"1\"}','ZGE5OTI2NzQ0MTU3YzIzN2ZkZmRmZTBmNmM0YTdjOWI=','192.168.0.64',1547212247),(276,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212533\",\"deviceType\":\"1\"}','ZDBkMzU0YTE4M2FjZjJhMjcxZWJiYzNhNjRjN2IzMTU=','192.168.0.86',1547212298),(277,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212534\",\"deviceType\":\"1\"}','Njc1NDhjNDE5MTAzNDU3YTgwZmEzNjA4ZGQ0MjkyYjc=','192.168.0.86',1547212299),(278,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212534\",\"deviceType\":\"1\"}','NzgxN2IyNDA4ZjA5NDBhOGVkOTYxNjk1MDIwZDc2YWY=','192.168.0.86',1547212299),(279,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212534\",\"deviceType\":\"1\"}','NzQ4MWJlMTEwYTk3NTJhMzJkMjI3NzliZmFlN2FiNGQ=','192.168.0.86',1547212299),(280,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212534\",\"deviceType\":\"1\"}','NzdhYzNkYWNmMTFhNjJjNTQyMWU2NDIyN2U5ZGIxM2M=','192.168.0.86',1547212299),(281,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212534\",\"deviceType\":\"1\"}','ODMzNDE5YWU3OWFhZjY1YWM1ZWU0NDQ3N2ExMDEyYWQ=','192.168.0.86',1547212299),(282,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212535\",\"deviceType\":\"1\"}','MWIxZGViMGE1MDJiZDFmYWIzM2JhYzExNzRkN2I5MGQ=','192.168.0.86',1547212299),(283,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212537\",\"deviceType\":\"1\"}','MDQ4ZjNkZjZkODkxNDM0OGM4ZWJhNWU1MGU4ZTlmNmM=','192.168.0.86',1547212302),(284,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212537\",\"deviceType\":\"1\"}','OWE3ODYzNzg0N2FkMWMwZmMwNTBiYzQ0NTE5MDcxOWM=','192.168.0.86',1547212302),(285,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212538\",\"deviceType\":\"1\"}','YTFkNWQ1MWI1ZjJiNTExNzNmNzc4ZWZhMjVkNjg1MTI=','192.168.0.86',1547212302),(286,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212538\",\"deviceType\":\"1\"}','NzA0NTEwZjI1MDYwNzZmZDUzYzk5MWVhOGE5ODcxNWM=','192.168.0.86',1547212303),(287,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212540\",\"deviceType\":\"1\"}','ZjQ1NTI2ZmJhMGVjMGExNDM2ZDliNDYwN2I3MTU4NWI=','192.168.0.86',1547212304),(288,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212542\",\"deviceType\":\"1\"}','ODdkZDQwMjdjMTExZmVhNDk4MmMyNjU3ZDEwOTEyOTQ=','192.168.0.86',1547212306),(289,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212545\",\"deviceType\":\"1\"}','MjM1YTU0ZjYzYzU4MzdkNTliMmE1NWI1Mjg3Yzg2YzA=','192.168.0.86',1547212310),(290,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212569\",\"deviceType\":\"1\"}','YzZjOTJlZTIyOGRmNzljYTUzNjA1NWY0YmFjY2RjZjc=','192.168.0.86',1547212334),(291,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212569\",\"deviceType\":\"1\"}','YWVhYzQ3ZmNkMzlkMTVhMWFmNzk5OTEyOTBkNWY0YTU=','192.168.0.86',1547212334),(292,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212621\",\"deviceType\":\"1\"}','MzFkZGIwZTM2ZDVmY2E5OTJiMzhjODk0Mzc3ZjlmZDU=','192.168.0.86',1547212385),(293,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212402\",\"deviceType\":\"1\"}','MzNmNWY4NTA1ZWY1NzY5YjY2YzZjN2JiMmIyMjdmNTc=','192.168.0.64',1547212409),(294,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212411\",\"deviceType\":\"1\"}','YmI0M2YyYWFiMDM5NzI2OWFlNGM5ODBiNDJhOTg2YTY=','192.168.0.64',1547212418),(295,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212693\",\"deviceType\":\"1\"}','OTI3MmE4MzQyMDExY2EwM2M5OGM0MTMxZDRmZjZiZDg=','192.168.0.86',1547212458),(296,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','MGRmYzRiZDBlNGExOWYyZjdiMWI2NGZmYmRhMGRhZjc=','192.168.0.86',1547212459),(297,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','Zjk4MzA3NmU3MDA5MTFiMWVhZTQzM2FmZmZiNjA0MWU=','192.168.0.86',1547212459),(298,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','M2I1NGUxZTIzYzFjZDk3MTUzZDY2YjA5OWJmYzkzMTY=','192.168.0.86',1547212459),(299,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','ZWI1MjBhZTg2MGFkNTEzNmZhMWFmNzQ1OWJmYzhkMzE=','192.168.0.86',1547212459),(300,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','MmU5ZDJjZTgxMmM0MWExMWNlM2U0ZDYxYTQyMTQ3Njc=','192.168.0.86',1547212459),(301,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212694\",\"deviceType\":\"1\"}','ZmRjMmExYmQ1Y2Y4ZmNiYjEyMWQ5NTA3ZDAwZmU1ZTc=','192.168.0.86',1547212459),(302,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212699\",\"deviceType\":\"1\"}','YzBkYzViMWUxMWIzNjhjOGI4MzQ3NDU2M2Y0ODkzMjc=','192.168.0.86',1547212463),(303,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212699\",\"deviceType\":\"1\"}','MzQxYjgwY2FhZmQ5NTQ0M2Y1NDljNjliNWQ1ZjVlNWM=','192.168.0.86',1547212463),(304,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212699\",\"deviceType\":\"1\"}','NTcwN2QyZDIyYTk1YjNkODMwYTAyYmQ3MmUzMzNiMjA=','192.168.0.86',1547212463),(305,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212699\",\"deviceType\":\"1\"}','ZWY5MWY1YmJjODc0NjdiZDMwZTVmMzNiMWQzN2MwMGM=','192.168.0.86',1547212464),(306,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212700\",\"deviceType\":\"1\"}','NWUwYzBiZjA2ODU2ODg1YjE3Yzg3OTc5OTk2Y2EyZWE=','192.168.0.86',1547212465),(307,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212703\",\"deviceType\":\"1\"}','MjhmYjliOWIwNzFhYTMxODdiNjIyZmIyMWY1MGI5ZjY=','192.168.0.86',1547212468),(308,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212519\",\"deviceType\":\"1\"}','MDFmMDU4ZmQ0Y2VkNmVkZWMzMTJkYzU3YTViZDY3ZTI=','192.168.0.64',1547212526),(309,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212766\",\"deviceType\":\"1\"}','NDkzNTE0ZGY2ZmNkNzc3NDQ0ODFlYWZmNTdjNTk3YzY=','192.168.0.86',1547212531),(310,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212784\",\"deviceType\":\"1\"}','MzBkMzAzMmY2Y2Q5MzkwNTczZTU2NDRkMjRlN2U1YzA=','192.168.0.86',1547212548),(311,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','MDkwOWFkNDNmNjExZDQ1ZGFjYjM0YWY0NDc0YTExMWU=','192.168.0.86',1547212549),(312,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','MmYxMmYzNmYxOTJhM2QwN2NiZjlmOTU0MGFkOWEwY2M=','192.168.0.86',1547212550),(313,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','NmFlODE2NTgwNDA2OWU0NDg0MjQ2MjNhZjViZjEzZWI=','192.168.0.86',1547212550),(314,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','NDMyOTRjYjRiNzFiODliZTYwMzk4ODVlY2IyZTYyZmQ=','192.168.0.86',1547212550),(315,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','ZmM4N2RhODNjNGJjNjg0YjJlMmI3NzQwZDIyMzQyYmY=','192.168.0.86',1547212550),(316,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212785\",\"deviceType\":\"1\"}','OWY5YjMwNjNkNmM5ZGI2ZGIxMGRjZmE2MjBjZjdjZTE=','192.168.0.86',1547212550),(317,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212788\",\"deviceType\":\"1\"}','NDYwZGYwOGRmNDY3YjhlNTNlZjAxMzBkMDU5MmI2MmY=','192.168.0.86',1547212552),(318,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212788\",\"deviceType\":\"1\"}','NTMzOWRjMzBiNzMyNmJhMWUzNmI5MWQwNDBjNTljNGQ=','192.168.0.86',1547212552),(319,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212788\",\"deviceType\":\"1\"}','YzNlMDIyMDM1MWIxOGQ0MzA5ZjE5YTgyOTg4MjdkODE=','192.168.0.86',1547212552),(320,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212789\",\"deviceType\":\"1\"}','YzU1OWU3OGQyN2U5NTUwYjNiMWM3MmZkNzRjZTI2ODE=','192.168.0.86',1547212553),(321,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212792\",\"deviceType\":\"1\"}','MGQyNzgwMTFlMzRmNjczZTg4ZGI5OTZhOWNiNGZmMTQ=','192.168.0.86',1547212556),(322,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212803\",\"deviceType\":\"1\"}','ZThmN2NlNjA0NTY2MTM1MGNlM2I3MzUzZDY2ZWI3Y2E=','192.168.0.86',1547212567),(323,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212828\",\"deviceType\":\"1\"}','MWViYjY2YzZmZGEzNTNhNWNhNTBhMDg4ZDhmZDlkYjk=','192.168.0.86',1547212592),(324,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','MDZhNjRiNTY3ZDdlNDM1NDg3Zjc2MDMwYzQ4Y2JiMDU=','192.168.0.86',1547212593),(325,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','Nzc2ZWNjMWNkNTE5MzMyNGY1OTk2ODkwNWY3MjIwYTg=','192.168.0.86',1547212593),(326,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','YzQ1NzE5ZjIzZmY5NDk3NWFmNWRjNDEwMTI1ZjU4ODk=','192.168.0.86',1547212593),(327,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','OWIxZmVmMzkwMmI2OTZmOTljOTY1MzNiOTY3Mjk2OWY=','192.168.0.86',1547212594),(328,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','MGU2NzJjYzBjZjA5NDllMTMzNTA5ZDVhMTAyZGQ4NjA=','192.168.0.86',1547212594),(329,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212829\",\"deviceType\":\"1\"}','NGEyNDJkYmM0MjJjNzc2YTg3MjkwNjkwM2UxNTQyYWU=','192.168.0.86',1547212594),(330,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212833\",\"deviceType\":\"1\"}','ZGNkNDhlNmZmZGI4MzM3YTY0YTgxZTJiY2Q1Y2FmNzY=','192.168.0.86',1547212598),(331,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212834\",\"deviceType\":\"1\"}','ZDQ5MTAxNGUwNmMwM2MxNThkZWZmOGRjMDY0YjkzYWQ=','192.168.0.86',1547212598),(332,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212834\",\"deviceType\":\"1\"}','YzRlOTBlN2UzZDRjMDk0Y2M0NzE3ZTY4OTQ1NjY0YWI=','192.168.0.86',1547212598),(333,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212834\",\"deviceType\":\"1\"}','MmYwOTQ3OGJkNGQ1YjBlY2ViY2ZhODQyNWExMTg4OWQ=','192.168.0.86',1547212599),(334,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212835\",\"deviceType\":\"1\"}','OWJmZmUzNWY4MGEyMTk5NjIxMjIxMzM3NWU0MWE3YzA=','192.168.0.86',1547212600),(335,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212838\",\"deviceType\":\"1\"}','YjRjMzIxNTY2MTQyYTcxZjUwYjg3MGJhMjQ4MjhkNDI=','192.168.0.86',1547212603),(336,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212944\",\"deviceType\":\"1\"}','ZTM5ZjdlMWMxNWZlMjlhYTMzNDU1ODcxYTFjNzdhZGE=','192.168.0.86',1547212708),(337,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547212987\",\"deviceType\":\"1\"}','ZjI3YTczODc4ZmNjZWVkZGFlNWQxMWViNzE2MDk3OTc=','192.168.0.86',1547212751),(338,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213024\",\"deviceType\":\"1\"}','MGQyYTE4NWRlZmE1OGMxODc1ZGZlMjExZjNmNDJmNTU=','192.168.0.86',1547212789),(339,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213071\",\"deviceType\":\"1\"}','MTVlZmQ0OTZkNzBmYzA5NmJiZmMwMmMwYThjZTQ5YzQ=','192.168.0.86',1547212835),(340,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213177\",\"deviceType\":\"1\"}','MGQ2MWY2ZWNmYTJmMTQ3MWY3MmRiNTM2M2ExMGE0MzY=','192.168.0.86',1547212941),(341,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213273\",\"deviceType\":\"1\"}','MTFhMGM2ZWU4MGZkMzY3YmRjOGM0ZjlmNGQ2M2ExMGY=','192.168.0.86',1547213038),(342,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213273\",\"deviceType\":\"1\"}','MTljNzdkZmIyMWY5NDAzZjUwYWJmMzlhNGEzZDQ2MWI=','192.168.0.86',1547213038),(343,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213320\",\"deviceType\":\"1\"}','OWYyYzU0ZDY0MGMzODQyOTJiYTgwYmRiZTA4NTAwMGU=','192.168.0.86',1547213085),(344,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213346\",\"deviceType\":\"1\"}','NGUxOGJiNTExNDQyMDY2ODc4OGEwM2EyZTdkMjU5YTY=','192.168.0.86',1547213111),(345,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213355\",\"deviceType\":\"1\"}','YTA1MjBmYjI2OGVjOWUzYmYzNGVkNDU2MWZlY2UxNDI=','192.168.0.86',1547213120),(346,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213374\",\"deviceType\":\"1\"}','YjViZjAzMWYwM2QxYjExOTllNTBjMjllYzExYjBkOGE=','192.168.0.86',1547213138),(347,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213379\",\"deviceType\":\"1\"}','OTk3ZjEyMzYwMDE5YjcyZTE2MmIxOTBlNWNlZmNmYTM=','192.168.0.86',1547213143),(348,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213382\",\"deviceType\":\"1\"}','MGU1M2FjYzhlOGQ1YzVkNjMyNDVjNjkxZDI0ODRiODU=','192.168.0.86',1547213147),(349,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213394\",\"deviceType\":\"1\"}','ZGFhYjExZDliZTFlMGNjMmZkYjljMmJhNTQ0YjYzYmY=','192.168.0.86',1547213158),(350,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213421\",\"deviceType\":\"1\"}','NDAyMzU5MDBjY2Q1NDIxNDI4MTZiNDQwODNkMjY2NDk=','192.168.0.86',1547213185),(351,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213234\",\"deviceType\":\"1\"}','MDkyMTQ4ZTNhNTM4ODA0ZTE5MTU1OGIwNjdhMzUxZjg=','192.168.0.64',1547213241),(352,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','YThkODFjOWZmNzBmNmFjZDJlM2JjY2ExYzY5YWE4ODk=','192.168.0.64',1547213242),(353,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','NzcxYWU5MGY4YTM3NjI2MmU0MDIzZGVmY2YwYjc2YWM=','192.168.0.64',1547213242),(354,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','YzI2NGNmNWM5NTU0MWRhYTdhZjliNTY2MmZlNmM5MDM=','192.168.0.64',1547213242),(355,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','MGFmMjhjZDJiYjVkZTdmMTAwNzFkMGJjYjU1MzliMzU=','192.168.0.64',1547213242),(356,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','OTFjMGQ3ZTNjZWNhNDYwYzQ1ZGExOTc5YjFmMTYzMWM=','192.168.0.64',1547213242),(357,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213235\",\"deviceType\":\"1\"}','ZDYxOTc4NTAzZDc5Y2M1NzliMGVjNDE2NDc4ZTg4MDc=','192.168.0.64',1547213242),(358,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213236\",\"deviceType\":\"1\"}','N2FkNWI3MmUwYWY2M2RiNGFmNzM5ZWE3OTVkNjQyYTI=','192.168.0.64',1547213243),(359,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213236\",\"deviceType\":\"1\"}','MDRjMTFmM2E0MDRlMTIxNGY0ZjBjNGEzYTlmYjBkNTc=','192.168.0.64',1547213243),(360,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213236\",\"deviceType\":\"1\"}','NzEzNGRkZTU4Y2Y3NTBiNzA4ZTExMTFiMDkxMjIyNjQ=','192.168.0.64',1547213243),(361,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213237\",\"deviceType\":\"1\"}','OWIyMTk4MDgxYjNmMzlkMWM4ODNhMDc2MWI1MmRiZGM=','192.168.0.64',1547213244),(362,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213238\",\"deviceType\":\"1\"}','N2E5NzAyZjRkZWUxNDU0OWExYzRlNThhNTE5NTdiNTM=','192.168.0.64',1547213245),(363,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213240\",\"deviceType\":\"1\"}','ZmYzNGI1YjgxNTc3ODViOGQ2Njk2NGYzZDc1NGI5YjQ=','192.168.0.64',1547213247),(364,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547213245\",\"deviceType\":\"1\"}','NjI4OWI4OTYxOWVmN2M1NTYzNmJkOTlhOTFlNjU4MDA=','192.168.0.64',1547213252),(365,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214009\",\"deviceType\":\"1\"}','NTMwY2YwOTEwNDYwYjc3ODE1YjZiNmI1MWFlYjgyMzk=','192.168.0.86',1547213774),(366,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','NTczOThmZGVlZDk1YzM0OTU2NWFmMDFkMzhhNzI5YWU=','192.168.0.86',1547213774),(367,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','YjA5MjFmNzYyNGU2YWZmZWNkOWU0NjEzMmIxYWRhZGU=','192.168.0.86',1547213774),(368,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','YjE2YTY1ZmFkODM0ZjZiMjRiYTZhZWRiNWVlZjM3MjI=','192.168.0.86',1547213774),(369,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','NGI0NzM2ZDM0NjU2YTRkMjI4MzVjYzA1NDVhMDU1MWY=','192.168.0.86',1547213774),(370,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','MDliYjNhNTFiN2M0NGZiMzhhYWM4YmNmNzY1NGRiNGY=','192.168.0.86',1547213774),(371,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214010\",\"deviceType\":\"1\"}','NWRjMTg5M2ZiNmQ0MmFlOTdiODZkMWRmYTI4Mjk4ZGM=','192.168.0.86',1547213775),(372,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214012\",\"deviceType\":\"1\"}','OTVmNWU1ZjQ3YzAxODY4M2ExMDdhNmE3ZWY5MjEzNGM=','192.168.0.86',1547213777),(373,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214012\",\"deviceType\":\"1\"}','ZTYzNTk5YTU0ZjgyODhjYWE0MmI0OWNjMTM5YjhhN2E=','192.168.0.86',1547213777),(374,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214012\",\"deviceType\":\"1\"}','ZTBhYzQ3NDg1NTEwOGM3ZjhiNWEwMjk2YTkzMmQzMmU=','192.168.0.86',1547213777),(375,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214013\",\"deviceType\":\"1\"}','ZTc5YTM4NzdiMTMwYmJkZWE3ZjJhODA3NWE2Nzg0NWU=','192.168.0.86',1547213777),(376,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214014\",\"deviceType\":\"1\"}','OTEyNzA1NjlhZTg2NGIxZDYwMTE2ZGZiZjQ1OTlhM2U=','192.168.0.86',1547213778),(377,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214015\",\"deviceType\":\"1\"}','ODk3ZjUyYWNjM2IyMmMxNGI0OGYyZTY5YWJmOWI0OTc=','192.168.0.86',1547213779),(378,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214017\",\"deviceType\":\"1\"}','OWQ1YTIxOTNmMjJhNGI5MjQ3NWM5OTAzNTlhYjRiMDk=','192.168.0.86',1547213782),(379,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214029\",\"deviceType\":\"1\"}','ODg2YmNkNjgyMmU3ZmUzOGQ0NjRmOGUxNjg0NmY3MjQ=','192.168.0.86',1547213794),(380,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214075\",\"deviceType\":\"1\"}','ZmViZGU1NTY1NGRiN2NkZTY5OTk5NzMzNDU2MWI1Mzc=','192.168.0.86',1547213839),(381,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214183\",\"deviceType\":\"1\"}','YjcwMDg3ZTk4YzE0NGYyM2Q5Mjg4YmZjN2NkNjY3Y2Q=','192.168.0.86',1547213947),(382,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214207\",\"deviceType\":\"1\"}','OGE3Y2JhNTMwMjY3NzlkNDBiNTMwY2RiYzYwMTAwMzE=','192.168.0.86',1547213972),(383,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','OTRjZDE4N2I3MDU0YjIyZGEzYTc3NWU0MTVlNmU0MWQ=','192.168.0.86',1547213973),(384,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','N2EyY2I0MDc4Mzk4MTk0NmM0YzY1ZmZhNmU2ZWYxMmY=','192.168.0.86',1547213973),(385,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','MDY2MzRiODlhOGYxNWI4ZDk4MTE1YjZiMTk1NmYxMDk=','192.168.0.86',1547213974),(386,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','ZjNhMWZlMWQ5NjNmNmViYzk5OWQxNDk3MGI2YjI0MzY=','192.168.0.86',1547213974),(387,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','NzhhNjkzYmEzODg0M2FlNzI1MGQxM2U4YzVjMDk3ZGM=','192.168.0.86',1547213974),(388,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214209\",\"deviceType\":\"1\"}','NWQ2NzljZTJjMDc2NzYwZmZhMzg0NDAxZGZkMTJlMWI=','192.168.0.86',1547213974),(389,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214214\",\"deviceType\":\"1\"}','ZjY4ZTM0ZTJmODAzMjA2OGFjNjY5MTZhZGNmZjMwYmU=','192.168.0.86',1547213978),(390,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214215\",\"deviceType\":\"1\"}','ZmI2ZWZlN2I3ZmI4Y2E5OGQ1MDE3MWRlOTNlZDE0ZGY=','192.168.0.86',1547213979),(391,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214215\",\"deviceType\":\"1\"}','OTJlMWNmOTFlMjNhMDgwZWEzMzMwMjhmZmNkYjU4NDA=','192.168.0.86',1547213979),(392,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214215\",\"deviceType\":\"1\"}','OTRkYmUwNGNmZTAyYmY3NjkzNTc0MTRlOWI2OTY4M2Q=','192.168.0.86',1547213979),(393,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214216\",\"deviceType\":\"1\"}','MTQyNzdmYzBiNGE5MjJlYWFlYjJkNzQwNDQ4NWFmZjI=','192.168.0.86',1547213980),(394,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214219\",\"deviceType\":\"1\"}','YzJlYjJlNTU4OTA4MDVmYjQ5YTcxZGNkY2VmZGJmMTA=','192.168.0.86',1547213983),(395,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214228\",\"deviceType\":\"1\"}','ZTFlOTU4ZjcxNGQwOGEzNjA0OTdhNDM2MDJkNjMyYTc=','192.168.0.86',1547213992),(396,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214244\",\"deviceType\":\"1\"}','NGExMjViMWRmYTIwMDg4N2Y2YWNkODgzZmRmMTUzMmY=','192.168.0.86',1547214008),(397,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214244\",\"deviceType\":\"1\"}','YmUyNmM0YjA1NjBiODEyMTk5NDE4ODBhYWFlNTAxYzY=','192.168.0.86',1547214008),(398,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214412\",\"deviceType\":\"1\"}','OTZlNDU5OThlOThjZDc4OTFlMzJlNDg0NmNjZWY5NDg=','192.168.0.86',1547214177),(399,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214413\",\"deviceType\":\"1\"}','N2YzNWFmOTg3MjY2ZGFhNWIxMDc1NmJhNDM1MDQxMjY=','192.168.0.86',1547214178),(400,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214413\",\"deviceType\":\"1\"}','MDY0ZDJjZjAxNTgxNWExODg1ZjVhMWVkZmYwYjFiNjk=','192.168.0.86',1547214178),(401,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214413\",\"deviceType\":\"1\"}','ZDVlYmIzMzQwYWQ5ZDkxNmViMzRjNDdmM2M2OWIzZGM=','192.168.0.86',1547214178),(402,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214413\",\"deviceType\":\"1\"}','ZTQwZDg1MTc3YjBhMzEwYjcwNzY0ZjAyN2E3MWM4YTU=','192.168.0.86',1547214178),(403,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214413\",\"deviceType\":\"1\"}','YjFmNjhiZjBjODNmZTcxZDc1Njg4YmEyOGZhNDMzZDE=','192.168.0.86',1547214178),(404,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214414\",\"deviceType\":\"1\"}','NDNiZjhmYjAxMDhkZTExZGE3Y2NkNzY3NzhjN2FkMDM=','192.168.0.86',1547214178),(405,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214416\",\"deviceType\":\"1\"}','Y2FlMGRiZjYzMjRhNTA1ZWQ5MGRmZGQwNzQzMzcyZGY=','192.168.0.86',1547214180),(406,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214416\",\"deviceType\":\"1\"}','NzIwZGQ3ZDVlZTJlYWUzODliODQyZjczZmE1ODhjZWU=','192.168.0.86',1547214180),(407,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214417\",\"deviceType\":\"1\"}','MGM5MTllYzNmODFmYTY0YTVkZWNmYTQ0MGM5ZWU3MDU=','192.168.0.86',1547214181),(408,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214417\",\"deviceType\":\"1\"}','ZTk3ZDMyM2FiYWQ4MjYwOGI1ZWUzODQ1MmFmYjI5NDQ=','192.168.0.86',1547214181),(409,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214419\",\"deviceType\":\"1\"}','ODZhYTQyMmVmZTQ4NmI4MDI2ZTdmZmRiMmI3M2U3OGI=','192.168.0.86',1547214184),(410,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214421\",\"deviceType\":\"1\"}','YzFkMTc5MDJhMDlkMDE3ODQzOTQzYjFkM2E3YzI0Yzg=','192.168.0.86',1547214185),(411,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214432\",\"deviceType\":\"1\"}','ZTgwZDFiZDdmMjg3YjY1ZDE5ZTJmZDA2MzlhODZlZDU=','192.168.0.86',1547214196),(412,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214583\",\"deviceType\":\"1\"}','ZWI3OTQyYmZmMmUzZTUwOTFiNzQ2MDM5OTQ0MWE4MmU=','192.168.0.86',1547214347),(413,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214584\",\"deviceType\":\"1\"}','ZTQ0NjhmMmVhNjA5YzZjZDhlMTA2NGE1Zjc2ODNjNTE=','192.168.0.86',1547214348),(414,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214594\",\"deviceType\":\"1\"}','MmIzMzI1NGE1MjBmMDI1NmU4ZTU4OGYxZWJmYzYzNzk=','192.168.0.86',1547214358),(415,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214599\",\"deviceType\":\"1\"}','OWE0ZGQyOTBjYmMzZGYzNDgwMmQzYjU5MzVhYzc4NDA=','192.168.0.86',1547214363),(416,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214600\",\"deviceType\":\"1\"}','MjY3Yjc5MzZlZWRjOTU0Njc0ZjJhNjE2ZjFlZTNiZGY=','192.168.0.86',1547214364),(417,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214606\",\"deviceType\":\"1\"}','OWUxMzY3M2U0YTRlMjAyNTljNzkxZmVlODk2Njg3NWU=','192.168.0.86',1547214370),(418,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214613\",\"deviceType\":\"1\"}','ZWViYjYyYmMwYTIwY2YzOGQ1ODNiNmQ0MDMzYTRhN2Q=','192.168.0.86',1547214377),(419,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214727\",\"deviceType\":\"1\"}','MWE2MGViMjI2OWY2MjVhMTU2MzlkYjFlZWQwZjc4MDk=','192.168.0.86',1547214491),(420,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547214840\",\"deviceType\":\"1\"}','OTZlZDE0MDQ4N2M2MWQzNjgwOTNjNGE5NmE4NDY4YzI=','192.168.0.86',1547214604),(421,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215038\",\"deviceType\":\"1\"}','ZDQ0N2ZjNDhmMmU4ODc1ODY4OTkzZjY1NDBjYzFhZDM=','192.168.0.86',1547214803),(422,'{\"api\":\"pay\",\"action\":\"goodsList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215042\",\"deviceType\":\"1\"}','MWY1MjQ4ZDA0OTdhMGJjNjY2NDI0ZDk3Mjk3ZDBmYTU=','192.168.0.86',1547214806),(423,'{\"api\":\"club\",\"action\":\"friendsGroupList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215052\",\"deviceType\":\"1\"}','NTc4Mzc5OGRlY2U5NTcxMmQwZDNmMjg0MDJlOTJjMjk=','192.168.0.86',1547214816),(424,'{\"api\":\"club\",\"action\":\"friendsGroupRecommendList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215052\",\"deviceType\":\"1\"}','Y2I4YjAwNjMxYTQxZGE0M2Q2NjgyMjNlMTEzMmQ5NWQ=','192.168.0.86',1547214817),(425,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215804\",\"deviceType\":\"1\"}','MjBhNDE3NjM3ZmEyNWRmNzI0OTZkZDM1NzM2NWUyOGM=','192.168.0.86',1547215568),(426,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215806\",\"deviceType\":\"1\"}','M2VhNjZhZmZmZWUxZDY4N2E5YTM0MDllODgxYTc1MjE=','192.168.0.86',1547215570),(427,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215808\",\"deviceType\":\"1\"}','NGMzMzA0ZDhhM2Y3MzlkYjU5ZGNkODExMjBmNjg4Y2U=','192.168.0.86',1547215572),(428,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215808\",\"deviceType\":\"1\"}','ZTQzMDM2ZTY1YmM3MGFmM2ZlMmFiN2NkNTZjYTZmYTU=','192.168.0.86',1547215572),(429,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215808\",\"deviceType\":\"1\"}','MzI2YzgyYjRmYTk0OGY1NmUxY2IwMGI1YWNkNzgyMGY=','192.168.0.86',1547215572),(430,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215813\",\"deviceType\":\"1\"}','Y2M5N2RiODZlNjgwYTNjYjNhNDYwYjdjNGRjZjc5MGM=','192.168.0.86',1547215577),(431,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215823\",\"deviceType\":\"1\"}','ZTQ2YjczYTM1OWZmOTQ4MDZkMDNlNWU2ZWQzMjgxZGY=','192.168.0.86',1547215587),(432,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215828\",\"deviceType\":\"1\"}','OTU0NWI0ZWI3MDVkZDMyYWJlYmJmMTUwZTgyZmMxNTM=','192.168.0.86',1547215592),(433,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215839\",\"deviceType\":\"1\"}','MDRhNDFmYzk3OTM0NTQxNWM0MzA3ZDVmZTAwMGU2Mjk=','192.168.0.86',1547215603),(434,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215842\",\"deviceType\":\"1\"}','MzVlMzFhNWFmNjRkYTAzM2I4ZjA1ZjkyMWE3ODdlNjY=','192.168.0.86',1547215606),(435,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215877\",\"deviceType\":\"1\"}','Zjg4MGUyODJjYjJkYjk2YTc5MmViYzdkNThmODY3OGM=','192.168.0.86',1547215641),(436,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215887\",\"deviceType\":\"1\"}','NGQxNmJkY2IxMmYzYmZiNTZmZmZhZDFhMDYyZWQwMWY=','192.168.0.86',1547215651),(437,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215890\",\"deviceType\":\"1\"}','YTViMzQ2NjNiNjgxYTYzYTZiN2RjMTk2ZTI2ZGNlZDA=','192.168.0.86',1547215654),(438,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215897\",\"deviceType\":\"1\"}','Mzk1YjQ4YmM4NGQ5ZGMxODUxZGI5ZDY2ODdmZGZiMTE=','192.168.0.86',1547215661),(439,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547215906\",\"deviceType\":\"1\"}','NTJjMmU0MzY0MmJlYzNkMjFiZmFlODI2ODk5M2MwMDE=','192.168.0.86',1547215670),(440,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216064\",\"deviceType\":\"1\"}','ZTIwMTZhMDQ5NjkwMDhiNmMyZGZhODk0YTM3YzJhMzU=','192.168.0.86',1547215828),(441,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216466\",\"deviceType\":\"1\"}','NjU0M2RhZGE2NGE4ODg3YmU4NjNhMzdiZjk5ZmNhZjI=','192.168.0.86',1547216230),(442,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216467\",\"deviceType\":\"1\"}','ZTUyNmQ5Mzc0YTk1NzhiMjViNGJjYjU1ZWQwMjQxMjY=','192.168.0.86',1547216231),(443,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216467\",\"deviceType\":\"1\"}','ZWRhNDQyMzdlN2IzNDQwZWU1ZjdkYzFlYjNmZThmZmU=','192.168.0.86',1547216231),(444,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216467\",\"deviceType\":\"1\"}','MmRlNGEyNTMwYjczNmM0MmU1NjYzOTE3NzMzYmJiYWQ=','192.168.0.86',1547216231),(445,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216467\",\"deviceType\":\"1\"}','ZmMyNTU0ZmY2ZTE0NTM1ZDM5NTU2MDUzNGE0YzU1M2M=','192.168.0.86',1547216231),(446,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216467\",\"deviceType\":\"1\"}','NzhhNDNkMTEwY2U5YTA3Yjc3NzZlM2U1MTZiN2E5YjU=','192.168.0.86',1547216232),(447,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216468\",\"deviceType\":\"1\"}','MTMzNjkxMmU1OWQxNTBlZTQ0ZTgzM2FhMGRjZWY0OGI=','192.168.0.86',1547216232),(448,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216470\",\"deviceType\":\"1\"}','NjE1MWYwYWI1MjYwODdmN2IwZTExMjJhZTgyODMyYzk=','192.168.0.86',1547216234),(449,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216471\",\"deviceType\":\"1\"}','NzBlNDY3YWJkOTdmZTEyYTE5NjY2ZjcxNTg2MDc4NWQ=','192.168.0.86',1547216235),(450,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216471\",\"deviceType\":\"1\"}','MTIwZmI5ZTc2MzA1NTdkMDNmNmIwNWI0MjJkZDM0ZGY=','192.168.0.86',1547216235),(451,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216471\",\"deviceType\":\"1\"}','NDNlNjY4NjI4YzdkYTIyYWNhMWZkNzllY2JhZjM3NjA=','192.168.0.86',1547216235),(452,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216472\",\"deviceType\":\"1\"}','N2NjODFkNjAxMGI5ZmI1ZWJiZmViZGU5MjcyYzRhNzU=','192.168.0.86',1547216236),(453,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216475\",\"deviceType\":\"1\"}','NmI0ZWRhN2UyODRlNGU1ZTQzNWNiYjQ4YWU1NzIyZmU=','192.168.0.86',1547216239),(454,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216477\",\"deviceType\":\"1\"}','NTJmMWFmYmJlNzZjZDRlZjhkM2VkMzkyOTliZWMwZTE=','192.168.0.86',1547216241),(455,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216486\",\"deviceType\":\"1\"}','Y2NiYTM3NmZiZTVjMmEzNWVhNzAxZTBkZDZjMThmZWY=','192.168.0.86',1547216250),(456,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216498\",\"deviceType\":\"1\"}','ZDkzOTY3ZDQzMGI2YjY0ODg2OGJiZjU0YzNiNTliZDY=','192.168.0.86',1547216262),(457,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216532\",\"deviceType\":\"1\"}','YWYxNDMyOWFlZWUyNDczMjM2Y2QzNDU3MWEzNmVjNTY=','192.168.0.86',1547216296),(458,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216654\",\"deviceType\":\"1\"}','ZGJmNTAyM2ZjZDUxYjQ4ZTNiYzNiMGNiZGNlOWQ3M2Q=','192.168.0.86',1547216418),(459,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216684\",\"deviceType\":\"1\"}','NmU1N2Y1OWJiMzAzMTY1MDg4Yjc4M2JkMTcwMDBmNDQ=','192.168.0.86',1547216448),(460,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216685\",\"deviceType\":\"1\"}','ZTQ1MzFkNDFkOWJiZjZlNDlkMGQ5NDcxODQwMzNhYjk=','192.168.0.86',1547216449),(461,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216685\",\"deviceType\":\"1\"}','ODgzYmQxNmIyODhlNTM5MTgyMGMxMzFkNGI1MWVlNmM=','192.168.0.86',1547216449),(462,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216685\",\"deviceType\":\"1\"}','ZDM5YWM4ZWJmMjY5YmI2YjllMzQ1Njk0MDJjM2E5ODU=','192.168.0.86',1547216449),(463,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216685\",\"deviceType\":\"1\"}','MGIwOTE4NmRkNWE0MTMzOGFkYWU2ZjdmNjliYjM1MWM=','192.168.0.86',1547216449),(464,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216685\",\"deviceType\":\"1\"}','NmUwMzcwNGU3NzMzYjk4NTA2MGZmZmMxMjk4NTFlZDM=','192.168.0.86',1547216449),(465,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216686\",\"deviceType\":\"1\"}','YmNlYTMwMmJjNzk3ODFjNGEzZTI5ZTM2NzU4YjhhYzg=','192.168.0.86',1547216450),(466,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216689\",\"deviceType\":\"1\"}','MTIxMDA1MzcyMzM3Nzc4MDBjZjEyZTgwYzMyOWUyOTU=','192.168.0.86',1547216452),(467,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216689\",\"deviceType\":\"1\"}','MDg5Nzc4ZWIwNGU1OGFlMTllZTQ4NjhjNWIyMzMyMmY=','192.168.0.86',1547216452),(468,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216689\",\"deviceType\":\"1\"}','ZjJiMzdkNWJhMDllN2NlNTU5NDU3OTA1MzdhNmIyMmY=','192.168.0.86',1547216453),(469,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216690\",\"deviceType\":\"1\"}','NTYwNjY2ODQ0Yzg0MzkyMzdjOWM1YzI5Y2JkZThkMzI=','192.168.0.86',1547216453),(470,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216693\",\"deviceType\":\"1\"}','NDE3MDhkMWQxYzQ0MDQ2MDFhMDQyMjllYjdlNTFmMTY=','192.168.0.86',1547216456),(471,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216695\",\"deviceType\":\"1\"}','OWE0ZWY4NTA2ZTFjMTg0MmJmMzA5ZjhkNTRmYWY0NDk=','192.168.0.86',1547216459),(472,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216698\",\"deviceType\":\"1\"}','ZmNiYmJmZmRkZmU1MGVlNzM4Y2I5OTAwMGUxNjI1OTU=','192.168.0.86',1547216462),(473,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216859\",\"deviceType\":\"1\"}','MWMwZmZkMmRjZGZhNDU3NTU0NDQzMTI3MThmNTVhZjM=','192.168.0.86',1547216622),(474,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547216874\",\"deviceType\":\"1\"}','Y2UxM2UyYTNjYzA2ZDA0MmFiYzFhOTI2NjM4Mzg3NWE=','192.168.0.86',1547216638),(475,'{\"api\":\"phone\",\"action\":\"resetPhonePassword\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217060\",\"deviceType\":\"1\"}','Mjc5NGRmNDFmY2M1MDFiNjEwZWM3ODg1NWEwZjI2MTk=','192.168.0.86',1547216824),(476,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217081\",\"deviceType\":\"1\"}','ZDk2MTQwOWYyMjU1ZDNjNmZmOTJjNWRkOThlNDk5MTY=','192.168.0.86',1547216845),(477,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217157\",\"deviceType\":\"1\"}','ZTU0NTU0NWIwYTFmNmFjY2RjODc4N2ZkMzg5ZWM1YWM=','192.168.0.86',1547216921),(478,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','MmVlODM4YjUxNDZjODcwM2FkMTk3M2JhN2I0NjYxZjE=','192.168.0.86',1547216922),(479,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','MjljZmYyNTdiZWRlMDc0MDM1ZTM3NDdjYTA1MTQyNjU=','192.168.0.86',1547216922),(480,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','NDgzNzZkNDhkMDMyYmUzNzUyOTA0N2U4ZTQ4N2I4YWI=','192.168.0.86',1547216922),(481,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','NWE4NGZlYmQ2NjdmYzcxNWI5OWQzNDBlMjIxNWVhMGE=','192.168.0.86',1547216922),(482,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','MDNlZDZhN2E0MjhjYjZhOGU4ZTUxYWMyZTNhZTkwOWU=','192.168.0.86',1547216922),(483,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217158\",\"deviceType\":\"1\"}','NzAyNDFhYmE4ZmE1Mzc2MjAxYjI1ZmRjNTE2ZDU2YWU=','192.168.0.86',1547216922),(484,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217161\",\"deviceType\":\"1\"}','NmZlNjY1YTc5OGRlY2FlYTFjMWViYmM1NmU0NTY1NzE=','192.168.0.86',1547216924),(485,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217161\",\"deviceType\":\"1\"}','MThiNmFjZTk3OWM5OTc1ZjIwYzQyNjM0YTQ3Y2Y0OGQ=','192.168.0.86',1547216924),(486,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217161\",\"deviceType\":\"1\"}','YTJlN2EzYzgyNzQ4ZjM5ZmEwNTQxMWJkNTdjMjVhZmI=','192.168.0.86',1547216925),(487,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217161\",\"deviceType\":\"1\"}','OGU2NzEzZWVjYThlZWI5MTFjNzIxNTZjMWIxMDU0NGQ=','192.168.0.86',1547216925),(488,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217162\",\"deviceType\":\"1\"}','ZWQyMGIwYTMzNzhiOWZhNWRlMDJmMjYyNmI3MWU1MDY=','192.168.0.86',1547216926),(489,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217165\",\"deviceType\":\"1\"}','MWI5NjE0MDliYzViY2QxOGUwZGUwMzNmNmNhZWZiYTY=','192.168.0.86',1547216929),(490,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217862\",\"deviceType\":\"1\"}','M2ViMGNmNGZkNWMxNmU4ZWFiNzA1Njg2NzlhZmFmNWY=','192.168.0.86',1547217626),(491,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','NWRlMDkxMTI0OGNjOWI2NmU3YWZjOTY1YmFhMjA2Yzc=','192.168.0.86',1547217628),(492,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','YzhlNDZiNmE2Y2JkYmU3ZDcwMzNkMjhkMjYzMjBhODU=','192.168.0.86',1547217628),(493,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','YzkzMWE4ZDhiNjM2ODVmZGNkY2Q0MzQxZDQyZjczYTQ=','192.168.0.86',1547217628),(494,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','ZTg1Zjc0YjlhNjgxMGEyMzE2Nzc2YjllZDAwOGM4YWM=','192.168.0.86',1547217628),(495,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','YTExNThmYzhkN2Q3NjA5ZjU4MzM0OGNhNzUzMGQ0YTY=','192.168.0.86',1547217628),(496,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217864\",\"deviceType\":\"1\"}','ZjE0MjJiNzQ0ODk5ZDNmMmMzMTczOGJjNmY4ZGUzYjk=','192.168.0.86',1547217628),(497,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217867\",\"deviceType\":\"1\"}','N2YxOTQ2OTI1MzU0YzE3MmRjNTRiMWEzODZiMmI1Yjk=','192.168.0.86',1547217630),(498,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217867\",\"deviceType\":\"1\"}','NTQwNThhZmUyYjQ0NWFkMTQ3ODM0Mjg3YzhhMzEyN2Y=','192.168.0.86',1547217630),(499,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217867\",\"deviceType\":\"1\"}','YWRlZjAwYTM1YTMwYTg0MTk0YjRmYTVjNjMxNjFkYWI=','192.168.0.86',1547217631),(500,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217868\",\"deviceType\":\"1\"}','YjExYWRiZWVhNmZlYTY0ZDJiNWFmNDc1NGMzNzdhZGM=','192.168.0.86',1547217631),(501,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217869\",\"deviceType\":\"1\"}','MzhjNDVmYzU3ODcyZmUxMGYwOWM0OGNlYWM4Y2Y0YjQ=','192.168.0.86',1547217633),(502,'{\"api\":\"user\",\"action\":\"getUserHeadUrlList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217871\",\"deviceType\":\"1\"}','ZWExMDAzOWZhMTgzNjliYjVlMzVjYmE4ZTg0MWZkNDI=','192.168.0.86',1547217635),(503,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217871\",\"deviceType\":\"1\"}','ODQ1YjhjYjg1ZGEwYjNmOTY3MDJiZTUyMzMzYzBhNGE=','192.168.0.86',1547217635),(504,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217897\",\"deviceType\":\"1\"}','OWFiN2ZmZWNiMmEwM2EyMjcyMWJmM2JiYmY4NDE4ODA=','192.168.0.86',1547217661),(505,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217914\",\"deviceType\":\"1\"}','ODRkNGFhMWJmMjY1YjI4NDNlZDE5OWFjM2JjYTI2YTk=','192.168.0.86',1547217678),(506,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217914\",\"deviceType\":\"1\"}','MGE2ZjhhMTIzZGNjNzA1YWY3YjA2YjA2ZDE4NTcxYjg=','192.168.0.86',1547217678),(507,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','ZTNkZTIxMzQ3MDk5MTNmMjg1OGNhYjQ0YzBlZTMwNDg=','192.168.0.86',1547217938),(508,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','YTllYTE2NWVhMjBiYjk2OGRiMmYwOWMyYTFhODUwNjg=','192.168.0.86',1547217939),(509,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','YmJjNzRlMGE5MmQ5NjExNzI5NGQ3ZGIwY2RhNmQ0YTc=','192.168.0.86',1547217939),(510,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','ZmU2NDFjN2IxZTkwYWEwNzM3NGZiYTRmZmY0ODI0OTQ=','192.168.0.86',1547217939),(511,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','NGM2YTY4MTEzOGNiNTI3NTExOWY4YTcwMDY3MTc1NzY=','192.168.0.86',1547217939),(512,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218175\",\"deviceType\":\"1\"}','NjI0M2RjZmU4ODc3YTc3Yjk2OGZiNGExY2ExY2RlZjc=','192.168.0.86',1547217939),(513,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218176\",\"deviceType\":\"1\"}','YmRhMzUxMDZiYmUyMzFlNzg4ZDIzN2IyZmQ3NzM5NzA=','192.168.0.86',1547217939),(514,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218178\",\"deviceType\":\"1\"}','N2Y2ODI3NDg5MWEwYjM4NjFkMWE3NmIyMjMwZTI4ODY=','192.168.0.86',1547217941),(515,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218178\",\"deviceType\":\"1\"}','N2EyMGQ4ZDJiYzRhOWQzMDY5ZTcyYmI4ZTcwMGE2ODA=','192.168.0.86',1547217941),(516,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218178\",\"deviceType\":\"1\"}','MGRjMzk4ZmY5MmY2N2U5N2Y0YzAyOTE5NDU0Y2ZhODU=','192.168.0.86',1547217942),(517,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218179\",\"deviceType\":\"1\"}','MDYzMzRhM2JiMTUxYTlhYWZkMTQ1ZGIxYTQxNzU2N2E=','192.168.0.86',1547217942),(518,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218180\",\"deviceType\":\"1\"}','NzljNjBiN2JmZjQxOTJiMTEzMTllOGY1OTRkYWYwZDg=','192.168.0.86',1547217944),(519,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218182\",\"deviceType\":\"1\"}','NDMwNmUzNWI4YzcyZTM2NTYwZjlkNmJiNmM1NWY2YzU=','192.168.0.86',1547217946),(520,'{\"api\":\"phone\",\"action\":\"sendCode\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218189\",\"deviceType\":\"1\"}','M2JhNDdlZmE3YmNiYmE4MmI4YTc5NGE4ZDMwNGI4Nzg=','192.168.0.86',1547217952),(521,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217946\",\"deviceType\":\"1\"}','MzE0ZGIyOTRmMzVkYTM3NDgxNDUwMzI0OGE0YjM0YWM=','192.168.0.64',1547217953),(522,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','YjAyYzJjZjk1OTRhNjNhNDcwZGVhMWNhNTQxNTUwN2Q=','192.168.0.64',1547217954),(523,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','YTMwNjk0YzFkOWVhNDU3ZWU1OGNlOWU4ZDA5OGRkY2E=','192.168.0.64',1547217954),(524,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','ZTFmM2E3OTBiODczOWE1MGQzYmQxZmZjYTUyMDgzMDg=','192.168.0.64',1547217954),(525,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','MGZmY2UyNDg4MDRiOWQ3OTI2ZjRiNmU1YjM4OWE3ODk=','192.168.0.64',1547217954),(526,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','NWZjYmM2ZWJlNzEyN2Y5YjRmZjk0M2Q0MzMzYjIzMjU=','192.168.0.64',1547217954),(527,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217947\",\"deviceType\":\"1\"}','ZmJjMzEwNDAwYTVjNjgxYzI3YWNlNjljOTJhYTY4ODA=','192.168.0.64',1547217955),(528,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217948\",\"deviceType\":\"1\"}','ZGU0MjUxYWQzMzUxNDU5ZTkwYWM1YWUzMTE4ODZiNDE=','192.168.0.64',1547217955),(529,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217948\",\"deviceType\":\"1\"}','YzEwMzI5OWM5YzM0M2Y5YTUxZWZmYTIxM2I5N2RkMTA=','192.168.0.64',1547217955),(530,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217949\",\"deviceType\":\"1\"}','ZjE1OWI2ODU5ZGM0ZmQ1NzgyNjNiNDc2MzVjZGIxMmQ=','192.168.0.64',1547217956),(531,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217949\",\"deviceType\":\"1\"}','NzVhMmNkMzFjMzhhMGE1YzMwMTljZjczZTc3M2ZjZTk=','192.168.0.64',1547217956),(532,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217951\",\"deviceType\":\"1\"}','NjA4ZDkwOTY2ODUxYjQzYzcxNGI5ODUxNjIyNDIxNjk=','192.168.0.64',1547217958),(533,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547217952\",\"deviceType\":\"1\"}','MmU3OWNhODgyZjk0MDhjMTg1MTFhODBhNmJkY2Y2Nzc=','192.168.0.64',1547217960),(534,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218239\",\"deviceType\":\"1\"}','MmVhMjA5MDIxNWI0YmZiYTFjZDYxYWM1OTM5MWJmYjU=','192.168.0.86',1547218002),(535,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218351\",\"deviceType\":\"1\"}','NGY3ZDZkMzA3ZjZkOTAwYzg0MWM3ZWNmNGY0NWU4YWE=','192.168.0.86',1547218115),(536,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218388\",\"deviceType\":\"1\"}','MzFiNGIwNjc4Y2YwMDZlM2NkODIwZWNlNzUzYzdiZDQ=','192.168.0.86',1547218151),(537,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218256\",\"deviceType\":\"1\"}','NzhlMGMxMWRmMmMxODg2OTY4ZTgwNTcwZWIxYjlkYjQ=','192.168.0.64',1547218264),(538,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218506\",\"deviceType\":\"1\"}','YTc4NTQ2YzU2NjE1ZDM5MGJhMjQ3YjI1NmJlNjZlNjU=','192.168.0.86',1547218270),(539,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218263\",\"deviceType\":\"1\"}','MGY0MmRjNWI5MzY1ZDZjZDc4OGQyYjZiNWM0N2NiNmE=','192.168.0.64',1547218271),(540,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','NzY5YmM2NzllODQ3MTMyMDY4OWJiMDYyYmFiY2Q3MTE=','192.168.0.86',1547218271),(541,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','OWE2N2Q5NTNhM2IzOTc3ZTRlYjczYjhlMGQ4YzY0NDA=','192.168.0.86',1547218271),(542,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','OTU5NzVjZGViNjkxNzI5MzkxYmRjYjgwNDVlMWEwMjU=','192.168.0.86',1547218271),(543,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','ZDQ4ZGU0NTJjYWE1YmUxOTU0YTAyMzdlNDgyMjBiNDM=','192.168.0.86',1547218271),(544,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','ZmVkYjM4MTY0NTc4ZjM3NzI2ZmM0OTdkMTQ4MTI5NmI=','192.168.0.86',1547218271),(545,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218507\",\"deviceType\":\"1\"}','YWUxZTYwNDdjMTk4ZjAyZDZmYjE0Y2E0ZTkzNGYyMTE=','192.168.0.86',1547218271),(546,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218509\",\"deviceType\":\"1\"}','NzY2ZmRjZDA0M2IwZjFiZjI5ZTE0YTBmMWUzMmMyMTM=','192.168.0.86',1547218273),(547,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218509\",\"deviceType\":\"1\"}','ODZkYjUwMjNmZGE4M2UxNDMzOGZiNTViM2I0YWMxMjQ=','192.168.0.86',1547218273),(548,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218510\",\"deviceType\":\"1\"}','MjQ5MzRkMzE4YjRiNGFkMmNkNGQwOGI1NjljODRlYzU=','192.168.0.86',1547218273),(549,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218510\",\"deviceType\":\"1\"}','OWEyOGEwMzlkMGNjYjM1YWY3NWMyNjlhOWZlZjNhM2M=','192.168.0.86',1547218274),(550,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218513\",\"deviceType\":\"1\"}','ZjVkNTBlMTA2YzU4YThlZjVmZTYwMTYwNGZhMjNhNWM=','192.168.0.86',1547218277),(551,'{\"api\":\"user\",\"action\":\"getUserInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218513\",\"deviceType\":\"1\"}','MTM2YWZiZTBmNmE2OGUwZGZlZjRkMzUyZDg1NjdiYmQ=','192.168.0.86',1547218277),(552,'{\"api\":\"email\",\"action\":\"userEmailList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218463\",\"deviceType\":\"1\"}','ODRjNDg0NDA4ZmVhODRlOWQxMmNjN2NlYTkzOThhOWQ=','192.168.0.64',1547218470),(553,'{\"api\":\"record\",\"action\":\"simpleGradeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218465\",\"deviceType\":\"1\"}','ZDQxNTFlYzEyMWZlYWM5NDI5YmQ4NDZiNTFkZTU0ZTg=','192.168.0.64',1547218472),(554,'{\"api\":\"phone\",\"action\":\"bindPhoneInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218468\",\"deviceType\":\"1\"}','NGQwN2QzOTQ2YjJjNzU2NjI0MDg2ODRjOGVkNTBiZTA=','192.168.0.64',1547218475),(555,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218588\",\"deviceType\":\"1\"}','Mjc0NmVkYzEyYWUwNjdlYzZiMDZjNzY3ZmUzMzc1YzU=','192.168.0.64',1547218595),(556,'{\"api\":\"record\",\"action\":\"simpleGradeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547219071\",\"deviceType\":\"1\"}','YjU2YzliZmEzODcyNzBkZWY4NTgwZDE4YTExZmRlZmI=','192.168.0.86',1547218835),(557,'{\"api\":\"record\",\"action\":\"simpleGradeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547219073\",\"deviceType\":\"1\"}','MTM5Yjg0NWRiNjUwYjQwMmM2MmZhYzQ5OGNiNjVmMGE=','192.168.0.86',1547218837),(558,'{\"api\":\"record\",\"action\":\"simpleGradeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547219073\",\"deviceType\":\"1\"}','ZWViYTg5NzI5YjgzOWIzYTJjYjg4N2I5NDU5NzlhYzA=','192.168.0.86',1547218837),(559,'{\"api\":\"record\",\"action\":\"simpleGradeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547219074\",\"deviceType\":\"1\"}','MzA5MDkzYTYxYzVhNGYwMTMwZDcyZjg5OTY2NmNiY2E=','192.168.0.86',1547218838),(560,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547218923\",\"deviceType\":\"1\"}','YWE0MGIwZmU4NzNkNzdhMjI1MWE4OTYxM2E3MjEzNjY=','192.168.0.64',1547218931),(561,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','ZWEwMDNkYjExYTVkYTVkYTI0ZTE3YWNmY2VkZmFkNTA=','192.168.0.68',1547271181),(562,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','N2Y3ODdmY2Y5NzUxMGJjYWRjYmIxNTYwZWEwODI5YTE=','192.168.0.68',1547271182),(563,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','YjJlYjU5ZjFhMjZmMGIyMzAzMTgzNTU3NTE2Mzc2NDA=','192.168.0.68',1547271182),(564,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','N2ZkNWM1NzgyMWU1ZDk3MzZmYzA0MGQ1MWU1ZjFiNDU=','192.168.0.68',1547271182),(565,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','YzNjYmFlMTQwY2I4ZTg1MzczMmVkZmI4NGIzNjU5NjY=','192.168.0.68',1547271182),(566,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271174\",\"deviceType\":\"1\"}','NTBiNTg4NjQ4ZGMxMjJiZDczMTcxNjJkZGMyZGViYjU=','192.168.0.68',1547271182),(567,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271175\",\"deviceType\":\"1\"}','Y2FhMjNkMDU2NjlhM2JjM2MwNTA5ZWY3NjVjOTg1ZjQ=','192.168.0.68',1547271182),(568,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271175\",\"deviceType\":\"1\"}','YjdhY2RjNGIxNzEyZWYxMGJjZjI3MDU1NzRmZWEyMDA=','192.168.0.68',1547271183),(569,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271175\",\"deviceType\":\"1\"}','M2IwOWU0MDQ2MWQwNzg5ODQ4NzBmMDEyZjU0NGEwZTQ=','192.168.0.68',1547271183),(570,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271176\",\"deviceType\":\"1\"}','ZGZlNGRhN2U0Yzg3N2JiYzBhYWFiMTI5YjQyYzM2MzE=','192.168.0.68',1547271183),(571,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271176\",\"deviceType\":\"1\"}','NDZhYjlmNzU5Y2QzMThhNTgxMDYyY2YzMjdiODFhZjI=','192.168.0.68',1547271184),(572,'{\"api\":\"welfare\",\"action\":\"sign\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271179\",\"deviceType\":\"1\"}','NTFmNjNmMzYwMzM5OTk4MTEzZjA4M2I1OTIzYTZmYzc=','192.168.0.68',1547271186),(573,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271179\",\"deviceType\":\"1\"}','NmYwMzM0ZGY1Y2YwZWY1NDlkNGM4NjZiYTdiMmI1MGE=','192.168.0.68',1547271187),(574,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271710\",\"deviceType\":\"1\"}','NWQ4M2VlNDhhMzQ0NjU2MWIyMzhiMjNiOGY1ODExNGQ=','192.168.0.60',1547271719),(575,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','NDM0NWIwN2IzNmIyNjRjNzY2YjdjMTMzNTkyYmU1Mjc=','192.168.0.60',1547271719),(576,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','MGJmYTQxNGZlMjU5M2ZhZmZkNmQwNjAwMjkzMTkzZmU=','192.168.0.60',1547271719),(577,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','MGM3OGFmNTBlZWY4MzczYjZkOWMxZDEyZmFiZjZkYzY=','192.168.0.60',1547271719),(578,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','NTMzNDU5N2M5NWMzMTRhOTk5Mzc4NDMyNTg4Y2MxZjg=','192.168.0.60',1547271719),(579,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','NWJlMDExZDdhZjY2YmNlNjhmNzI2ZGNmY2MxMzdjMGQ=','192.168.0.60',1547271719),(580,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','MTdmNGVkMjkzYjdlNzcwNTE5NjhiZjIxMTBkZTVhMDI=','192.168.0.60',1547271719),(581,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','ZTk4ZjNiNTdjZmU5NDYwYjQyYTkyMzVlNGMwYWFhYjQ=','192.168.0.60',1547271720),(582,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271711\",\"deviceType\":\"1\"}','YWQ1OTVjZGQzMDhlY2Y3NDQyNjBiNTFlM2ZkM2EwMjk=','192.168.0.60',1547271720),(583,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271712\",\"deviceType\":\"1\"}','ZTBiMzk2ZjgxNmM1NDVkMjEwY2E4MDYxZTE4MDZlODA=','192.168.0.60',1547271720),(584,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271712\",\"deviceType\":\"1\"}','ZDlkYzkzNWFhZDVmMDZhMGE3YThiYTU0M2FjZWFmYTM=','192.168.0.60',1547271721),(585,'{\"api\":\"welfare\",\"action\":\"sign\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271713\",\"deviceType\":\"1\"}','NWZlODEyMjNmYjlmZGIxMzkyZjNkZWIxM2M2YWVkZTY=','192.168.0.60',1547271721),(586,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271715\",\"deviceType\":\"1\"}','ZmU4Zjk2NTU5ZTQ4NDljZWMyOGI4YzNiN2ZlNTc0ZDY=','192.168.0.60',1547271724),(587,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271723\",\"deviceType\":\"1\"}','OWFlNjQ0MDU1YWFmNTRiM2RkMjRjYmZhYzE0NTFmYTg=','192.168.0.60',1547271732),(588,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','YjgyZDZhZTQ3MDMzNzU0ZWMzZGE3ZTNmMWZkZTRkMzc=','192.168.0.60',1547271732),(589,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','NTBmMzdiODFjZmJjM2YxOWExY2MxM2EyYmZjZmNjY2M=','192.168.0.60',1547271732),(590,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','ZDNmOWI4NTYwY2RiZTI2ZjFkNjhhOGM5MmMyMGQxZjU=','192.168.0.60',1547271732),(591,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','MjRkYmJmMzMwYzU0NzU3YTIxOGJkZjJiMmJmNmZkMGQ=','192.168.0.60',1547271732),(592,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','ZDc5ZGQ2N2ZkZDY4MTViNzZiZGJiODliMzY3NTYyMWM=','192.168.0.60',1547271732),(593,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271724\",\"deviceType\":\"1\"}','M2YzNjhjYjIyZGNiN2Q5MzAwMmJjYjMxYTJhNTZmMDU=','192.168.0.60',1547271732),(594,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271725\",\"deviceType\":\"1\"}','N2I0MjJjMjExYzU4OWQ5NDVhNzlhZjMzZTg0YzI0NDM=','192.168.0.60',1547271733),(595,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271725\",\"deviceType\":\"1\"}','NzgzZDRlNDA4ZjFkYmM3ZTgxY2RmM2U2MWVkNjE2YzE=','192.168.0.60',1547271733),(596,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271726\",\"deviceType\":\"1\"}','ZjA5OTg1OGFiODg2MzU4M2I5ZDJmYTA0YmIzYjUxYjc=','192.168.0.60',1547271734),(597,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547271729\",\"deviceType\":\"1\"}','YmYzZjgyOTQwYTczNzY0M2Y4N2M1NzQzNzIzZGUwNmU=','192.168.0.60',1547271737),(598,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272312\",\"deviceType\":\"1\"}','NTg1OTkzOTRiZGMzZmQ4NzViZTJjNzI1ZTQzYzY0OGQ=','192.168.0.64',1547272320),(599,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','YmY1NDdjZDExYWYxYzMyMGRhZDk3NWJlYmU0YThkZGY=','192.168.0.64',1547272321),(600,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','MTA3ODE4YTZjMmM4NWUzOGE1N2IxOTM3ODY1ODY0NmI=','192.168.0.64',1547272321),(601,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','NTc5NTNiODIzMDhmNmJkYjUyYjM4YWNkMmYxMjI1MjU=','192.168.0.64',1547272321),(602,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','MzAzYzdmMTBjM2MyMjNmMzQyMzBkMzdiYjE0ZmQ1ZjQ=','192.168.0.64',1547272321),(603,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','NWI0N2E2NzRmNzlhZjVmNjU0Yjk3ZWI2MGE0ZjIwODA=','192.168.0.64',1547272321),(604,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272313\",\"deviceType\":\"1\"}','YWFjNDZjMjllOGY2MWFiNmQzNWU1N2JlN2NjMDZiYmY=','192.168.0.64',1547272321),(605,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272314\",\"deviceType\":\"1\"}','MDljMWQ3OGY5ZGIzZWRiOTc4M2Y1NmQ5MDhhOTA5YTU=','192.168.0.64',1547272322),(606,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272314\",\"deviceType\":\"1\"}','NDBlYzUzMTBiNjIyMzQwNzQxM2Y3MjQ4NWIzOWE0ODQ=','192.168.0.64',1547272322),(607,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272314\",\"deviceType\":\"1\"}','Nzg3YTI5ZWIxOTZkOWM4MzVhYjQyZjFkNWRkNzYzMGE=','192.168.0.64',1547272322),(608,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272315\",\"deviceType\":\"1\"}','NmI0ZjRiZTUxMmU1YjM1NDlkMjRkMDMxYzBiOGJlNWM=','192.168.0.64',1547272323),(609,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272584\",\"deviceType\":\"1\"}','NmZiYzNkNDU3MGJkNTk2MTk0NDUwZTg0MDU5ZWI3ZjM=','192.168.0.64',1547272592),(610,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272584\",\"deviceType\":\"1\"}','ZjJlMTNlMjk4OGU1ZmJmODMyNmIwN2RkMjBiMTU0MjQ=','192.168.0.64',1547272592),(611,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272584\",\"deviceType\":\"1\"}','YWNkNTk2ZGM1MzBhZjVlNGI5ZWI5NDFlNmQyY2Y5MTg=','192.168.0.64',1547272592),(612,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272584\",\"deviceType\":\"1\"}','ZjFkMzIzOTA2NjAwMzdlNDZkODg3M2U5NDQ2NWZhYTY=','192.168.0.64',1547272592),(613,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272586\",\"deviceType\":\"1\"}','OWU2MTU1ZWY2OGE3MGJhODBhY2VmMWVlMzVkNjQ4ODU=','192.168.0.64',1547272594),(614,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272589\",\"deviceType\":\"1\"}','NDdjZmY5ZGNkNTYyOTlkZDg2ZjBhZWM4NTc2ZjgxMTQ=','192.168.0.64',1547272598),(615,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272615\",\"deviceType\":\"1\"}','ZWNkNzcwZTA4NmJkZmVkYWU0MWI1N2I1YjkyYjk5Y2Y=','192.168.0.64',1547272624),(616,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272615\",\"deviceType\":\"1\"}','MDY5ODU4MDZjNWQ5MmU5OGNjMjQ2YzAxODJkMzQxZTM=','192.168.0.64',1547272624),(617,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272615\",\"deviceType\":\"1\"}','MTI5Nzc2Y2MyYTU3YTgwNGMyYTQ5OGQxYjIwNDE1NTU=','192.168.0.64',1547272624),(618,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272615\",\"deviceType\":\"1\"}','NjJmNTUxNzFiMGYzMjY1MWM5MjZhNTYyMzIyNDFlZmY=','192.168.0.64',1547272624),(619,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272617\",\"deviceType\":\"1\"}','MGZiODU1ZWUxNDIwNTNjNDIxYTFlNmM1Yjg4MjVmNjY=','192.168.0.64',1547272626),(620,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272620\",\"deviceType\":\"1\"}','OWQxNGRkNGFiZTAwZmJhNTQ0YTQyZGQyNTQzZmFkY2I=','192.168.0.64',1547272629),(621,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272934\",\"deviceType\":\"1\"}','YmQwYjQwYmMwNzQ3OGI4ZDU1M2Q2MjQwY2ViYTg2NmU=','192.168.0.68',1547272942),(622,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','ZmJhYmU3MTZjNmExMjg1ZDljNTg3ZGY1MTI2ZmE0YjI=','192.168.0.68',1547272943),(623,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','NWIwM2FhMTM4N2UzNjkyN2JlMzg3OGRiZDA0MDFhY2U=','192.168.0.68',1547272943),(624,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','YjI1ZGJlM2E2YTRmZjY2MWQ1ZTBhZTRlOGU0NzYzMjU=','192.168.0.68',1547272943),(625,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','YjM2OWMyYWJlOWU2ZTY0ZDRiZGY2ODliMGI2ODI5MjI=','192.168.0.68',1547272943),(626,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','YWIyZTZkMjg0MGMwOTFkNDk4ZGU1OWEzNzRmZjI3MGQ=','192.168.0.68',1547272943),(627,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272935\",\"deviceType\":\"1\"}','MWEwZTNiYjZjMDVkZmQ4M2MxNzRmMTJmMTZjNTgyZjI=','192.168.0.68',1547272943),(628,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272936\",\"deviceType\":\"1\"}','ZmNhYWJhNGU3NDFiMTNkYzg2MzlkNDlhNTkzM2VlOGQ=','192.168.0.68',1547272943),(629,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272936\",\"deviceType\":\"1\"}','ZDg2ZThhNzUyMGU1N2JkNjJmNmFkYWQ3OWEwMzhmMGY=','192.168.0.68',1547272944),(630,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272936\",\"deviceType\":\"1\"}','ZDY2Yjk5ZGZiNDNmZGEwOGI2M2YzN2U2YTQxYzdhYWU=','192.168.0.68',1547272944),(631,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272937\",\"deviceType\":\"1\"}','MGM1ZGQzNTgxYmViY2MwZTQzZDAzZjc4YzAwNDBkZjM=','192.168.0.68',1547272945),(632,'{\"api\":\"welfare\",\"action\":\"sign\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272938\",\"deviceType\":\"1\"}','NTU2Y2M4Yzc5YjVmM2Y3M2YzMDU2MWYyODRlZDMyZWQ=','192.168.0.68',1547272946),(633,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272940\",\"deviceType\":\"1\"}','NWQ1YjI2ZWVjZDJiZjQ4ZGI0NmFmZTNmZTYyZWM0NzI=','192.168.0.68',1547272948),(634,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','NjNjNDZlYmM0YWUxNjQyNDQzODA4MjVhOTBlY2JmNjE=','192.168.0.68',1547272961),(635,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','YmUxNzM1ZjJkNTdlN2FkNjE4Yzk4YmZmMmUyNTVmMGU=','192.168.0.68',1547272962),(636,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','OTRmMzI3NjJhZDA0NzkxZjE2NjNkMWMyN2FmZDMyZDU=','192.168.0.68',1547272962),(637,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','ODNmOGY5MDlmMjI0M2EyMzNmNGRjZGU1YmIyOTkwZjQ=','192.168.0.68',1547272962),(638,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','OWE4YzYxNzA1ODBlM2VhZTFmNTgxMDU2OWFjZjE0YTM=','192.168.0.68',1547272962),(639,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272954\",\"deviceType\":\"1\"}','NzMwNzc3NDJkMDE5MGM2YzIyYWYyMTE4MWM4ZWE3Njk=','192.168.0.68',1547272962),(640,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272955\",\"deviceType\":\"1\"}','NDhkZmQ2YjNlZDFmOTNkYWE2NDkwOWUyMTQ2Y2ZiOTA=','192.168.0.68',1547272962),(641,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272955\",\"deviceType\":\"1\"}','MWJhMDdjZmIzYzhhN2QwMGFmODRhNjExNjI1NzZlM2Y=','192.168.0.68',1547272963),(642,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272955\",\"deviceType\":\"1\"}','NDExYjVkYzJlZGJlNGZjZjZhYjdjZTg2M2I5MTU2ZDA=','192.168.0.68',1547272963),(643,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272956\",\"deviceType\":\"1\"}','MGFhODU3YzhlMzU5NDkzYWQ4MmI4M2YyYzljZjgzNGI=','192.168.0.68',1547272964),(644,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547272960\",\"deviceType\":\"1\"}','ZDY3NjM2ODdjYjMyYTAxNmVhNGNjMGU0OTQzMWYwM2U=','192.168.0.68',1547272967),(645,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273246\",\"deviceType\":\"1\"}','MDE1MDZlMGNiNzFlMjZmZjFkYTc0MTU1ZWU3NTk5ZWI=','192.168.0.64',1547273255),(646,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273246\",\"deviceType\":\"1\"}','YzA5NWMyZmU2MmY3MzAyYjczZDdmZDk4NDI5MTEwNTQ=','192.168.0.64',1547273255),(647,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273246\",\"deviceType\":\"1\"}','MWZjZWUwMDRkNDQwOTFkMTAxZTE3Y2JkOTFjM2YwZjQ=','192.168.0.64',1547273255),(648,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273246\",\"deviceType\":\"1\"}','YmZlMjYxMzc3YWIzM2E1NzdiZjAzZWRiMjVjNjJiOTY=','192.168.0.64',1547273255),(649,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273248\",\"deviceType\":\"1\"}','NzhiYmQ0YzU0OGNkY2I4NDJkZTJjZTM1MjRkM2M0Yzg=','192.168.0.64',1547273257),(650,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273346\",\"deviceType\":\"1\"}','NzZkMTdmY2JiZDc0NDNhNmRjODQ0MDE1YjUxMTAwZjA=','192.168.0.64',1547273354),(651,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273346\",\"deviceType\":\"1\"}','MGM4YzkwM2QyNDIwMzZlM2NmYmIwYjIxNTE1MDViOTI=','192.168.0.64',1547273354),(652,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273346\",\"deviceType\":\"1\"}','YTZlYzY4ZTdhZTkzMzBjMjFlOTllYjU2NDg0YmE1NmU=','192.168.0.64',1547273354),(653,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273346\",\"deviceType\":\"1\"}','YjE3OGUyNzYxNWI0MWJmMGUzYzlhYTdjYTY4YTQ3OGE=','192.168.0.64',1547273354),(654,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273348\",\"deviceType\":\"1\"}','YjgzMzA1NzYwYjJkNzg3MDg4YzZmMTQ1NzI5MDZmZTE=','192.168.0.64',1547273356),(655,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273638\",\"deviceType\":\"1\"}','ODJiNDkyNTkyZjlkYTc2ZTQ0YzMzNGNiNGU3MTYxMjE=','192.168.0.68',1547273645),(656,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273638\",\"deviceType\":\"1\"}','MjM1NGMzMzUwOTMzY2NiYmEyOGM0OTdiMTBmMDQ1MGY=','192.168.0.68',1547273645),(657,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273638\",\"deviceType\":\"1\"}','Njc3MDc1N2EyODBiODNiMTAzNDZmZjIyYjQ1NzhhMmE=','192.168.0.68',1547273645),(658,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273638\",\"deviceType\":\"1\"}','MDY4MzE3OTdlMGUwZmQ3ODAwMmZlZTJhM2Q4ZTFkNzY=','192.168.0.68',1547273645),(659,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273640\",\"deviceType\":\"1\"}','ZjA5OGE1NDczOTI1YWYyMDM0ZjIyZTE3NTNhMTkwYzQ=','192.168.0.68',1547273647),(660,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273640\",\"deviceType\":\"1\"}','NDk4NGQ5YWQzY2JjNTg3NjQ5YjZkMDQ1YjQ2OGM0ZDc=','192.168.0.68',1547273648),(661,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273640\",\"deviceType\":\"1\"}','M2UzOWVmZTY3MzVjYWIyNjAyNTg5NDIzNTQ2NzUzMGU=','192.168.0.68',1547273648),(662,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273640\",\"deviceType\":\"1\"}','ZWYzN2M0ZGZmODg3YzY5ZmUzNDk0NDM1OTFmZGVhMWU=','192.168.0.68',1547273648),(663,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273640\",\"deviceType\":\"1\"}','N2UxZTJkOTNjMjg1MTQ2NThiZDVmOTQ2NDZmNWNlNjk=','192.168.0.68',1547273648),(664,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273642\",\"deviceType\":\"1\"}','NDU4YTUyOTg1NzlhMmNlNmMwMmNmYjZlOWExYmY4NDk=','192.168.0.68',1547273650),(665,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273751\",\"deviceType\":\"1\"}','NDYzZjEyOGI5NDM2MmEzNmViMzAyN2M5ZWQwMTIwYjc=','192.168.0.68',1547273758),(666,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273751\",\"deviceType\":\"1\"}','YjAxZjYyZjBhM2FlNGMzMzNjMTU3MWNkNjFjNmI4YzE=','192.168.0.68',1547273758),(667,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','YzgwYjU2MjQzNDA0NDQ2ZDYzODVmMWUwMzJjZGMwYTA=','192.168.0.68',1547273763),(668,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','NzE4N2ViMzhlYWJlYzI2NGM4ZTkyMDYzZGQxNjc0MTA=','192.168.0.68',1547273764),(669,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','OTRjNTExMGZiZTE5M2U4NjBkMDZmNjNiNWJiNzFkZWE=','192.168.0.68',1547273764),(670,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','ZmMxMGMzMmFkOTM5OTQzMDgwMjc3ODM5NGM5Y2QzY2M=','192.168.0.68',1547273764),(671,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','NWQ5ZDgyM2I3MGIzY2I5ZjUyN2Y3ZGY1Mjk4MzQzMWU=','192.168.0.68',1547273764),(672,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273756\",\"deviceType\":\"1\"}','NDEwZDBlNGQzMThmNWZjMTc5MWE3ZTc3MjdmYWMxMDQ=','192.168.0.68',1547273764),(673,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273757\",\"deviceType\":\"1\"}','ZjNmY2E5NzA1ODcxZmNmMzA0ZDMwMGU4MzFkMWE2ZmQ=','192.168.0.68',1547273764),(674,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273757\",\"deviceType\":\"1\"}','MDc3ZWI3YWE2OTQwZjk0ZTllNGRiMmRiYmM5OGVlNWM=','192.168.0.68',1547273765),(675,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273757\",\"deviceType\":\"1\"}','YzYzNzA3ZDc3M2U1OGRmNWM3ZGViYjMxZGUyMWU1YmM=','192.168.0.68',1547273765),(676,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273758\",\"deviceType\":\"1\"}','NDE5Y2ZjZTVkYzcyZThlMTgwODUzMDYwMDY0Y2NlNTE=','192.168.0.68',1547273766),(677,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273761\",\"deviceType\":\"1\"}','OTlhMzdlMzZjYzZjMGM5MWIxYTA0YTdjMjk1YTcwMWQ=','192.168.0.68',1547273769),(678,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273769\",\"deviceType\":\"1\"}','MDE4NGVjNWNmNjlkNmRhN2Q3MTUzZDM4NDAwZGU0ZWQ=','192.168.0.68',1547273776),(679,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273769\",\"deviceType\":\"1\"}','NzhmMGMwYzI5M2ZjMWEyMTAzZGJhZjMxODQ4ZDk4ZmE=','192.168.0.68',1547273776),(680,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273769\",\"deviceType\":\"1\"}','Nzc4ODdiMDUyZWQwOTdhNmUwMjA1ZjZkNDZhZGRiODI=','192.168.0.68',1547273776),(681,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273769\",\"deviceType\":\"1\"}','NzdhYjFhNWYyOWQ2NzJhMjQxOTk5ZWRjYzZmYzc2MDg=','192.168.0.68',1547273776),(682,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547273771\",\"deviceType\":\"1\"}','ZjcwODI2NTgyZTA3MDRkMDE3ODA4YTVlNmMzMWQ0YzM=','192.168.0.68',1547273778),(683,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274254\",\"deviceType\":\"1\"}','ZWRkNjBkMDMwYzRkNDYwM2ViN2VjNTQzMDgxNjUxNjI=','192.168.0.68',1547274262),(684,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274254\",\"deviceType\":\"1\"}','MmQ3MjY1NTViNGU0MmVmMDIxOWEwMDAwMTViOWJhNWE=','192.168.0.68',1547274262),(685,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274254\",\"deviceType\":\"1\"}','ZTk2MTVhZWY1MDczNTNhNzJmMDI0NGZiZGNkZDI5MWY=','192.168.0.68',1547274262),(686,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274254\",\"deviceType\":\"1\"}','Y2VlOTllZjMxMzNkNWJjY2Q2YmFlOTJmYjMwZmQyNzA=','192.168.0.68',1547274262),(687,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274256\",\"deviceType\":\"1\"}','NGI2NjYyYzliZGZmZjliMzNkZGIzNjQyNzhiNzIxM2I=','192.168.0.68',1547274263),(688,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274257\",\"deviceType\":\"1\"}','MzQzOWI0Zjc2MmI3MDA2Njk2MDJlZDllY2RhMmFiMWI=','192.168.0.68',1547274265),(689,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274257\",\"deviceType\":\"1\"}','M2E3MmMwYzA5NWQ4Yjk5YjY2MWY1MDJmOGJhZGI2YTk=','192.168.0.68',1547274265),(690,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274257\",\"deviceType\":\"1\"}','MTgzZWZlNWUyNjQxNGIxZDg2OTI5ZjM0ZDhhNjg4YWY=','192.168.0.68',1547274265),(691,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274257\",\"deviceType\":\"1\"}','MGQ0NjYxYTNhNTBjOWVkNDhjZjVmZGM1ZWNlOTM2NzU=','192.168.0.68',1547274265),(692,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274257\",\"deviceType\":\"1\"}','MjkxNDk3ZDI0MDdiMzlkNzcyNTg2MGZiM2EwZDEzNDQ=','192.168.0.68',1547274265),(693,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274258\",\"deviceType\":\"1\"}','NmNkZDM5NTIzOTRlYThjOTBlNWRjYzU0MGYyNTZmMDQ=','192.168.0.68',1547274266),(694,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274258\",\"deviceType\":\"1\"}','MjFiNjM5NTVkMzA1ZjVmZDMxMGY1ZDNmMzc4ZmVkOTA=','192.168.0.68',1547274266),(695,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274259\",\"deviceType\":\"1\"}','ZjhiMzFiNDdjY2E4ZmQ2MTY1YThjNDUzMDUyYWUwZWM=','192.168.0.68',1547274267),(696,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274331\",\"deviceType\":\"1\"}','YjMwYWFmZmEwMzk5NjhkMjJkYjA1NjBlMzEyMGE0OWE=','192.168.0.64',1547274339),(697,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274331\",\"deviceType\":\"1\"}','M2UzNzI1ZWVmM2FhNGI0NDc0MDgwMTU5ODM3MjM0YmM=','192.168.0.64',1547274339),(698,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274331\",\"deviceType\":\"1\"}','MGI0MzMzMzAzYzFjNTBlNzgxMGY0ODI1NjY0NDhjMGU=','192.168.0.64',1547274339),(699,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274331\",\"deviceType\":\"1\"}','ODliNjQ5NWJkNDljYjAxNjkwNTU5YmY4NzBhZTQxOWI=','192.168.0.64',1547274339),(700,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274333\",\"deviceType\":\"1\"}','N2EwODFmYmVjMDcyYzYzYWFmOTg2ZDMyN2NhNDQzNGM=','192.168.0.64',1547274341),(701,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MjFhNjhkMzA5YzQzODZhZTliMTBlOGY2M2NhYWUwZTU=','192.168.0.68',1547274509),(702,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','ZjczN2Q2NGViMTVlNzhhMDEzYzhhYTI1NTc0N2I3ZDc=','192.168.0.68',1547274509),(703,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','YTFhMjBhY2YzMjBmOWVkMWFlMWQ2OTZhMDNiOTM1ZjA=','192.168.0.68',1547274510),(704,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MjZlMDg5MzZhNjMwMDEyNWEzMzJhNjI5NzAwYmFjMWM=','192.168.0.68',1547274510),(705,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','NGEyOWVjM2JhMGU5ZDIxMzBmY2E3Y2ViODcxMGUxYjk=','192.168.0.68',1547274510),(706,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MDViMDc3N2UxYjk0MGQ2MTExYWVhZDM0YTMzOWY2MjA=','192.168.0.68',1547274510),(707,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MGVlYjM2NWViZDRlNmQ5YjVkMTMyNDk1Mzg5MWQ0YzY=','192.168.0.68',1547274510),(708,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MzMyOWU0MDFhOTNjNTFhNGJmYTc4ZTRiODJjOTQ1OWY=','192.168.0.68',1547274510),(709,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','NmMwNWNmOTk2Yjc5ZWFjMmE5Zjg0OTI4ZGU4YzVlZTA=','192.168.0.68',1547274510),(710,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274502\",\"deviceType\":\"1\"}','MDNkOTU3NTc0ZjM1ZTgwZjU5OWZmZjA5NzhjZjMzYjU=','192.168.0.68',1547274511),(711,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274504\",\"deviceType\":\"1\"}','YjNiOWU0MDdlZGVmZDFmM2NkY2E2Nzc0ZWJkZThmM2Y=','192.168.0.68',1547274512),(712,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547274504\",\"deviceType\":\"1\"}','YTVjN2NkYzM4YmY0ZTdlMjdkY2VjZTk0MWNjYTc3YzM=','192.168.0.68',1547274512),(713,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','ZjRhYjdhMmJhOTRjNDMwNGYwMzM3Zjc5YThkYzYzMjM=','192.168.0.64',1547276033),(714,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','MTFmZmRkMjMwZTFlMjY1NzNiZmRlMmNmNWYwMThmMmE=','192.168.0.64',1547276034),(715,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','ODQ0MmJkZjRlNmQ4Y2NjODY5MGE5MzVmMTY0ZTI0N2I=','192.168.0.64',1547276034),(716,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','ZWNjMzU4NGM1ZmExMmY3OTdiZmFjMGE5OGFiMmU5MmM=','192.168.0.64',1547276034),(717,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','M2QwYmJkODYzZGI5MWY5YWU3Yzk4OWIzNDc1MzMwNmI=','192.168.0.64',1547276034),(718,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','OTJmNTgyZDI2MWRmOTdhYjFjODVhMDc2NjlkNDY4NWU=','192.168.0.64',1547276034),(719,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276025\",\"deviceType\":\"1\"}','NjM5ZWIzODVlOWU3MDExYjYwMWQ0YmViYjVlNWNmOGY=','192.168.0.64',1547276034),(720,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276026\",\"deviceType\":\"1\"}','NTE3M2Y2OWQzZWQ2NDY2NDg2OGQzNTllMmQ5N2U4YzQ=','192.168.0.64',1547276035),(721,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276026\",\"deviceType\":\"1\"}','MDlmMGIzMzkyY2YxNmQ3YWI5MmI4MzcxZmY4YzI3Zjc=','192.168.0.64',1547276035),(722,'{\"api\":\"welfare\",\"action\":\"signConfig\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276027\",\"deviceType\":\"1\"}','MzgyMzYwOTQ1ZTMzOWJiNDllNTg3YTAzOTZlZmU2ZWQ=','192.168.0.64',1547276035),(723,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276027\",\"deviceType\":\"1\"}','ODdkODNiM2M1NjMzMmE1MDRkZTc4NDM5NjViNzBmMDM=','192.168.0.64',1547276036),(724,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276033\",\"deviceType\":\"1\"}','MjAzNTYwY2ZkYjU3NjJlNzBhMTc2ZDEyZmY1OTc2NTY=','192.168.0.64',1547276042),(725,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276033\",\"deviceType\":\"1\"}','NGZlNjc1ZWJlMjlkNTVmYzc4OGFiZDk0NGZhNjI2ZGQ=','192.168.0.64',1547276042),(726,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276033\",\"deviceType\":\"1\"}','MDM0MjI1ZjE1YTNiNzczYWQyNDc4NGEwYTA5M2JkMTM=','192.168.0.64',1547276042),(727,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276033\",\"deviceType\":\"1\"}','M2FlNTk5M2NkYTYxYzY5MTc4NzA4ZGIwMGYyNmU5OTk=','192.168.0.64',1547276042),(728,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276035\",\"deviceType\":\"1\"}','M2M5YzVkZGNiNGNjNDkwOGQxMTA3YWFlZTM4MzU2NWQ=','192.168.0.64',1547276044),(729,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276038\",\"deviceType\":\"1\"}','ZGUwMGJhZjA3MWQ2MWVjZGJjZjI1MzgzODVkYjZiNWU=','192.168.0.64',1547276047),(730,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276655\",\"deviceType\":\"1\"}','OTZmNjhkNzFiZDM4NWFjNTA5MjAwZjBkODhhNGZjYWQ=','192.168.0.68',1547276663),(731,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','Yzc1NzZiMzRkNWY0YTY2MjFlZjUyMGZmY2NmOWMxNTk=','192.168.0.68',1547276663),(732,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','ZWM5MzMyNGFhNzNmOGIxNjc2MWFkM2RiMDRiMmYyYTc=','192.168.0.68',1547276663),(733,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','OTM3ZTE3ZjEwNmIxNTdiNDc2MTZkNWQ1ODBjODdjOTM=','192.168.0.68',1547276663),(734,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','NTQwZTM2ZDVjZjhmMmQ4YTA5MmY3YThhYTk0MjRiMzk=','192.168.0.68',1547276663),(735,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','ZWM5YzliMzg3MzMzY2U5MTM5ZTcyODRkNjY0OWJiODU=','192.168.0.68',1547276664),(736,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276656\",\"deviceType\":\"1\"}','NWU1ZmI4M2JjOWY3MWJmYjgwYmI3OTIxNDI2MzRkZDQ=','192.168.0.68',1547276664),(737,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276657\",\"deviceType\":\"1\"}','ZDE3MDliNGQ5NmFiNjkxYmUxYmZmMWUzYjMwOTI0OTE=','192.168.0.68',1547276664),(738,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276657\",\"deviceType\":\"1\"}','N2QyYWJkNzZjZjUzOWY3MzNkODlkMmEyNTIxOTc4MTM=','192.168.0.68',1547276664),(739,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276658\",\"deviceType\":\"1\"}','OGRiM2Q2ODBkYWViNmIzNWI4NjhmOWY4OGVmMjI4YjQ=','192.168.0.68',1547276665),(740,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547276661\",\"deviceType\":\"1\"}','YWUxMDE4YTA4MDliZmQxOThjYzFhZTA0ZDUxZmJkNGU=','192.168.0.68',1547276668),(741,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','YTM1NmI5YjQwZmM5YjQxZjFhNjM0OTcwMTY1NzNmYTg=','192.168.0.68',1547277803),(742,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','NzU3YzRiYTczMTk5ODZhYzcxMmE5MjVhMjljMjIyODE=','192.168.0.68',1547277804),(743,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','MTMxM2I4ODVhM2FkNjdkN2MxYzVkYzQwNTJiMDRkZWE=','192.168.0.68',1547277804),(744,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','ZDY2NGRmOGRhM2RkNjFiYmJjY2Q1ODYzYmY0OGYzOWQ=','192.168.0.68',1547277804),(745,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','ZjJkMTgxZTQxNDk1ZTM2ZTZkOGRhNzE2OWE4ZDYzYTc=','192.168.0.68',1547277804),(746,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','ZmU1NTE4MzE3M2ZlNzg5OTFhYWVkMmE0ZmJkNjYzYTI=','192.168.0.68',1547277804),(747,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277796\",\"deviceType\":\"1\"}','YWZkYTJlNTFkYzkxN2M4Y2U0NTM0MDNiNjc3MDRkYWM=','192.168.0.68',1547277804),(748,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277797\",\"deviceType\":\"1\"}','ZmZjMmY3ZWM3NDc4NzdjZDNiNDFiZWZiMTIyMzFjZjY=','192.168.0.68',1547277805),(749,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277797\",\"deviceType\":\"1\"}','YWRhNDk3YjMyNWNiYzk3NWM1MDA4NDc1MmFjYmNiNmM=','192.168.0.68',1547277805),(750,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277798\",\"deviceType\":\"1\"}','NTk4OTEzZTg3M2NkMmUyYTk0YzI5MTczNTQ1OGMxMTY=','192.168.0.68',1547277806),(751,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277803\",\"deviceType\":\"1\"}','NjUxZDFmODIxNjZkNWVhZTk2MWU4ZjY5YWViMDczNDQ=','192.168.0.68',1547277811),(752,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277803\",\"deviceType\":\"1\"}','NjM5YmQ3YjQzMTFiZTgwOTczOTBiOWQ1NzljN2YzYWE=','192.168.0.68',1547277811),(753,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277803\",\"deviceType\":\"1\"}','NTIzOTJmNzMwNGYzODQ3NjE3N2MzMzcxMWRmNGFkNzA=','192.168.0.68',1547277811),(754,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277803\",\"deviceType\":\"1\"}','NDJhMjU5YWFjZDUyZDY1OGVjMGU2YTA4MmU3ZWRhNDA=','192.168.0.68',1547277811),(755,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547277805\",\"deviceType\":\"1\"}','NzljNzEzNTY0ZDdmN2FkN2E1NWU2MjFjODNhZWNlMDk=','192.168.0.68',1547277813),(756,'{\"api\":\"agent\",\"action\":\"getAgentInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278150\",\"deviceType\":\"1\"}','NDJmZmViYzg4N2NmYzE0MDAzMjk1MmRhOWJlZDU4YTc=','192.168.0.68',1547278158),(757,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','MzMwY2Y5MjllZjYxNGUwZDIzNmVkNjYwOGEyNWZjYjM=','192.168.0.68',1547278159),(758,'{\"api\":\"lobby\",\"action\":\"gameList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','NDViY2U1M2M1Y2M1OGI0NDE5YjJkMDQxMWRhYTZhMGQ=','192.168.0.68',1547278159),(759,'{\"api\":\"config\",\"action\":\"config\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','NmVjMWI0MWNlYzMwZDUxOTAwZjllOGI0MzZmODdiYjY=','192.168.0.68',1547278159),(760,'{\"api\":\"welfare\",\"action\":\"supportInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','NTkxNWIyMDBkZmQwNmUwNzRkYzhkNGM2YzE3NzRmMjc=','192.168.0.68',1547278159),(761,'{\"api\":\"user\",\"action\":\"refreshResource\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','N2QyODg3OGExMDA1Mjk3MGYwMDBjNjk2MDNlMTM0YTM=','192.168.0.68',1547278159),(762,'{\"api\":\"lobby\",\"action\":\"roomList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278151\",\"deviceType\":\"1\"}','NTAwOTZiMzE1MWQwOTNiNjNmY2IwMThiMDVkYmFhYzU=','192.168.0.68',1547278159),(763,'{\"api\":\"welfare\",\"action\":\"turntableInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278152\",\"deviceType\":\"1\"}','NmNlMjYxZWRhOGEzMTA4N2E0OWI0MTRmYTQyZjhlMDg=','192.168.0.68',1547278160),(764,'{\"api\":\"welfare\",\"action\":\"signInfo\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278152\",\"deviceType\":\"1\"}','ZjcwNTliMzNjZWRhMDZhMWVlMmFlMzgwMmRmZDM3OTE=','192.168.0.68',1547278160),(765,'{\"api\":\"feedback\",\"action\":\"feedbackList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278153\",\"deviceType\":\"1\"}','NTRjOTk5OWI1OTc0OGIwZGZlMzYzZGM2Y2JjODk2Y2U=','192.168.0.68',1547278161),(766,'{\"api\":\"notice\",\"action\":\"normalNoticeList\",\"uuid\":\"A1D62-DAB28-80Z59-ACW87-1ETD9\",\"timestamp\":\"1547278157\",\"deviceType\":\"1\"}','YTdkOWMzYWMyOWRkOGRkNThkMmEwNmE4MTExY2Y3Y2Q=','192.168.0.68',1547278164);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_club`
--

LOCK TABLES `web_club` WRITE;
/*!40000 ALTER TABLE `web_club` DISABLE KEYS */;
INSERT INTO `web_club` VALUES (100006,'123456',120051,0,1546854050),(100007,'654321',120052,0,1546854137);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_club_member`
--

LOCK TABLES `web_club_member` WRITE;
/*!40000 ALTER TABLE `web_club_member` DISABLE KEYS */;
INSERT INTO `web_club_member` VALUES (100006,120051,31,1546854050),(100007,120052,31,1546854137);
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
  `invite_userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏反馈表';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈回复';
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_game_config`
--

LOCK TABLES `web_game_config` WRITE;
/*!40000 ALTER TABLE `web_game_config` DISABLE KEYS */;
INSERT INTO `web_game_config` VALUES (1,'kefu_phone_1','18025305658','客服1电话'),(2,'bind_agentid_send_jewels','0','绑定代理号送的房卡数'),(3,'bind_agentid_send_money','5','绑定代理号送的金币'),(6,'android_packet_address','http://47.107.147.29/download/zzyl-101.apk','游戏底包安卓包地址'),(7,'apple_packet_address','itms-services://?action=download-manifest&amp;url=https://ios.ipa.szhmwl.com/plist/b6d767d2f8ed5d21a44b0e5886680cb9.plist','游戏底包苹果包下载地址'),(8,'share_begin_time','1509638400','分享活动开始时间'),(9,'share_end_time','4099737600','分享活动结束时间'),(10,'share_interval','30','分享奖励间隔时间'),(11,'share_img1','http://huo.qq/Uploads/home_img/5c2db4c9a6adc.png','分享图片'),(12,'share_url','dwadadwadwaddwad','分享链接'),(13,'share_address','2','分享到哪个平台才有奖励'),(14,'share_send_money','1','分享送金币数'),(15,'share_send_jewels','0','分享赠送房卡数'),(19,'bindAgent_sendUser_money','5','代理邀请玩家 绑定的玩家赠送的奖励 金币'),(20,'bindAgent_sendUser_diamonds','10','代理邀请玩家 绑定的玩家赠送的奖励 钻石'),(21,'user_invitationUser_SendMoney','1','玩家邀请玩家 绑定的玩家赠送的奖励 金币'),(22,'user_invitationUser_SendDiamonds','5','玩家邀请玩家 绑定的玩家赠送的奖励 钻石'),(23,'userBind_sendMoney','5','玩家邀请玩家 邀请的玩家赠送的的奖励 金币'),(24,'userBind_sendDiamonds','2','玩家邀请玩家 邀请的玩家赠送的的奖励 钻石'),(25,'share_img2','http://ht.szhuomei.com/Uploads/home_img/5a571bd12adc5.png','分享图片2'),(26,'share_img3','http://ht.szhuomei.com/Uploads/home_img/5a571bda27e20.png','分享图片3'),(27,'share_img4','http://ht.szhuomei.com/Uploads/home_img/5a571bf8c0db3.png','分享图片4'),(28,'share_img5','http://ht.szhuomei.com/Uploads/home_img/5a571bfab043f.png','分享图片5'),(29,'BindPhoneSendMoney','1','绑定手机');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_home_config`
--

LOCK TABLES `web_home_config` WRITE;
/*!40000 ALTER TABLE `web_home_config` DISABLE KEYS */;
INSERT INTO `web_home_config` VALUES (3,'abbreviation','至尊娱乐','简称',1504679233),(4,'title','至尊娱乐','网站标题',1504679233),(5,'phone','18025305658','联系方式',1504679252),(6,'address','深圳市宝安区海秀路19号国际西岸商务大厦801','公司地址',1504679272),(7,'beian','Copyright 2012-2014 huomei, All Rights Reserved 宝城企业.至尊 版权所有 备案号：粤ICP备17067659号','公司备案信息',1504679295),(8,'desc','深圳市至尊网络科技有限公司，该公司是一家长期专注于定制开发特色棋牌游戏、手机棋牌游戏、捕鱼游戏等等的高新科技游戏软件开发公司，力求打造“专业化、国际化、产业化、品牌化”的高科技游戏软件产品，成为独具魅力的领跑者。 至尊集结了一群年轻而充满梦想与激情的游戏从业人员，团队核心成员均来自腾讯、中青宝、博雅互动等一线棋牌游戏开发公司，具有五年以上棋牌游戏研发经验，自公司成立以来，始终秉承“诚为本、技至精”的核心经营理念，不断钻研市场需求，不断打磨产品品质，与中手游、百度、91等众多知名互联网企业精诚合作，并取得了丰硕的成果。由至尊科技一手打造的房卡金币双模式棋牌游戏平台，铸就了大量网络棋牌游戏行业的成功案例。 在不断优化自身产品的同时，至尊科技将推出更多适应市场的运营级游戏产品和平台，凭借自身丰富的资源优势，成熟的经验优势，强大的技术优势，优质的服务优势，更好的为客户创造独特价值。','公司介绍',1504679361),(9,'email','2041302270@qq.com','邮箱',1504679958),(10,'qq','2041302270','qq',1504679970);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_packet_version`
--

LOCK TABLES `web_packet_version` WRITE;
/*!40000 ALTER TABLE `web_packet_version` DISABLE KEYS */;
INSERT INTO `web_packet_version` VALUES (1,1,1,'appStore','1.0.6','http://app.g97767.cn/eq9','','appStore'),(2,1,2,'android','1.0.1','http://47.107.147.29/download/zzyl-101.apk','','平台包安卓1.0.0版本!'),(3,1,3,'ios签名包','1.0.0','itms-services://?action=download-manifest&amp;url=https://ios.ipa.szhmwl.com/plist/b6d767d2f8ed5d21a44b0e5886680cb9.plist','','ios签名包'),(4,2,1,'至尊娱乐','1.0.47','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201812122206.zip','','大厅资源包'),(10,2,30100008,'牛牛(牛将军)','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NJJ-201812052128.zip','','牛牛(牛将军)'),(12,2,30100108,'三公','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NSG-201812052128.zip','','三公'),(14,2,30000004,'跑得快','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/PDK-201812052128.zip','','跑得快'),(15,2,30000200,'百人牛牛','1.0.7','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BRNN-201812062238.zip','','百人牛牛'),(17,2,20170405,'21点','1.0.7','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/21D-201812052128.zip','','21点'),(29,2,30000404,'十三水','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FZSSS-201812052128.zip','1.0.0','十三水'),(30,2,11100200,'百家乐','1.0.5','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/HL30M-201812052128.zip','1.0.0','百家乐'),(31,2,10000900,'豹子王','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BZW-201812052128.zip','1.0.0','豹子王'),(32,2,10001000,'奔驰宝马','1.0.6','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BCBM-201812052128.zip','1.0.0','奔驰宝马'),(33,2,11100604,'牌九','1.0.8','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BBPJ-201812052128.zip','1.0.0','牌九'),(34,2,12101105,'炸金花','1.0.7','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/LTZJH5-201812052128.zip','1.0.0','炸金花'),(35,2,11000100,'飞禽走兽','1.0.5','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FQZS-201812052128.zip','1.0.0','飞禽走兽');
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_packet_version_test`
--

LOCK TABLES `web_packet_version_test` WRITE;
/*!40000 ALTER TABLE `web_packet_version_test` DISABLE KEYS */;
INSERT INTO `web_packet_version_test` VALUES (1,1,1,'appStore','1.0.47','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201812122206.zip','1.0.6','appStore'),(2,1,2,'android','6.2.3','http://haoyue.szhuomei.com/Lobby-201807180930.zip','','平台包安卓1.3.2版本'),(3,1,3,'ios签名包','1.0.0','http://www.baidu.com','','ios签名包'),(4,2,1,'至尊娱乐棋牌大厅','1.0.47','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/lobby/Lobby-201812122206.zip','','大厅资源包'),(5,2,36610103,'斗地主','7.0.0','http://haoyue.szhuomei.com/CSDDZ-201808101517.zip','','斗地主'),(6,2,30000404,'十三水','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FZSSS-201811261934.zip','','十三水'),(7,2,20161010,'广东推倒胡','7.0.0','http://haoyue.szhuomei.com/TDHMJ-201808101517.zip','','广东推倒胡'),(8,2,12101105,'炸金花','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/LTZJH5-201811242038.zip','','炸金花'),(9,2,20170405,'21点','1.0.3','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/21D-201811261934.zip','','21点'),(10,2,30100006,'牛牛(牛将军)','7.0.0','http://haoyue.szhuomei.com/NJJ-201808101517.zip','','牛牛(牛将军)'),(11,2,11100604,'牌九','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BBPJ-201811242038.zip','','牌九'),(12,2,30100108,'三公','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/NSG-201811242038.zip','','三公'),(13,2,23510004,'山西推到胡','7.0.0','http://haoyue.szhuomei.com/SXTDH-201808101517.zip','','山西推到胡'),(14,2,30000004,'跑得快','1.0.2','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/PDK-201811242038.zip','','跑得快'),(15,2,30000200,'百人牛牛','1.0.7','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BRNN-201812062238.zip','','百人牛牛'),(16,2,37460003,'跑胡子','7.0.0','http://haoyue.szhuomei.com/PHZ3-201808101517.zip','','跑胡子'),(17,2,20161004,'红中麻将','7.0.0','http://haoyue.szhuomei.com/HZMJ-201808101517.zip','','红中麻将'),(18,2,30000006,'十点半','7.0.0','http://haoyue.szhuomei.com/SDB-201808101517.zip','','十点半'),(19,2,26600004,'潮汕麻将','7.0.0','http://haoyue.szhuomei.com/CSMJ-201808101517.zip','','潮汕麻将（下线）'),(20,2,11100200,'欢乐30秒','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/HL30M-201811242038.zip','','欢乐30秒'),(21,2,20173124,'血战麻将','7.0.0','http://haoyue.szhuomei.com/XZMJ-201808101517.zip','','血战麻将'),(22,2,10000900,'豹子王','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BZW-201811242038.zip','','豹子王'),(23,2,30000007,'德州扑克','7.0.0','http://haoyue.szhuomei.com/TEXAS-201808101517.zip','','德州扑克'),(24,2,27910004,'南昌麻将','7.0.0','http://haoyue.szhuomei.com/NCMJ-201808101517.zip','','南昌麻将2、4人共用'),(25,2,37910005,'窝龙','7.0.0','http://haoyue.szhuomei.com/WL-201808101517.zip','','窝龙'),(26,2,10001000,'奔驰宝马','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/BCBM-201811242038.zip','','奔驰宝马'),(28,2,11000100,'飞禽走兽','1.0.1','http://hm-zzxl.oss-cn-shenzhen.aliyuncs.com/FQZS-201811242038.zip','','飞禽走兽');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_pay_goods`
--

LOCK TABLES `web_pay_goods` WRITE;
/*!40000 ALTER TABLE `web_pay_goods` DISABLE KEYS */;
INSERT INTO `web_pay_goods` VALUES (5,'金币',100,1,'人民币',100,0,0,NULL,1,10001,1,0,'com.globIcon.10000',1,NULL),(6,'金币',1000,1,'人民币',1000,0,0,NULL,1,10002,0,1,'com.globIcon.36000',2,NULL),(7,'金币',5000,1,'人民币',5000,0,0,NULL,1,10003,0,0,'com.globIcon.94000',3,NULL),(8,'金币',10000,1,'人民币',10000,0,0,NULL,1,10004,0,1,'com.globIcon.142000',4,NULL),(9,'金币',20000,1,'人民币',20000,0,0,NULL,1,10005,0,1,'com.globIcon.216000',5,NULL),(10,'金币',50000,1,'人民币',50000,0,0,NULL,1,10006,0,0,'com.globIcon.332000',6,NULL),(11,'金币',100000,1,'软妹币',100000,0,0,NULL,1,10007,0,0,'com.globIcon.596000',7,NULL),(12,'金币',300000,1,'软妹币',300000,0,0,NULL,1,10008,0,0,'com.globIcon.1292000',8,NULL),(13,'钻石',1,2,'人民币',100,0,0,NULL,1,10009,0,0,'com.coupon.1',1,NULL),(14,'钻石',4,2,'人民币',300,0,0,NULL,1,10010,0,0,'com.coupon.4',2,NULL),(15,'钻石',11,2,'人民币',800,0,0,NULL,1,10011,0,0,'com.coupon.11',3,NULL),(16,'钻石',17,2,'人民币',1200,0,0,NULL,1,10012,0,0,'com.coupon.17',4,NULL),(17,'钻石',26,2,'人民币',1800,0,0,NULL,1,10013,0,0,'com.coupon.26',5,NULL),(18,'钻石',40,2,'人民币',2800,0,0,NULL,1,10014,0,0,'com.coupon.40',6,NULL),(19,'钻石',75,2,'人民币',5000,0,0,NULL,1,10015,0,0,'com.coupon.75',7,NULL),(20,'钻石',162,2,'人民币',10800,0,0,NULL,1,10016,0,0,'com.coupon.162',8,NULL),(21,'钻石',1,4,'金币',2000000,1,0,1504234004,0,10017,1,0,'',1,NULL),(22,'十元话费券',1,4,'金币',22000000,1,0,1504237130,0,10018,1,0,'',2,NULL),(23,'50元话费券',1,4,'金币',102000000,1,0,1504237202,0,10019,1,0,'',3,NULL),(24,'100元话费券',1,4,'金币',200000000,1,0,1504237230,0,10020,1,0,'',4,NULL),(25,'小米移动电源',1,4,'金币',238000000,1,0,1504237263,0,10021,1,0,'',5,NULL),(26,'小米平衡车',1,4,'金币',NULL,1,0,1504237298,0,10022,1,0,'',6,NULL),(27,'ipad',1,4,'金币',NULL,1,0,1504237342,0,10023,1,0,'',7,NULL),(28,'iphone7',1,4,'金币',NULL,1,0,1504237378,0,10024,1,0,'',8,NULL),(29,'阿斯顿发生',1500,1,'3',4,0,NULL,NULL,1,NULL,1,1,'',NULL,'http://huo.qq/Public/Uploads/goods/5c308c6958310.png'),(30,'asdf',23400,1,'234',234,0,NULL,NULL,1,NULL,0,1,'',NULL,'http://huo.qq/Public/Uploads/goods/5c309190628dd.png');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_pay_orders`
--

LOCK TABLES `web_pay_orders` WRITE;
/*!40000 ALTER TABLE `web_pay_orders` DISABLE KEYS */;
INSERT INTO `web_pay_orders` VALUES (1,'20190107210352463223120052',120052,'金币',100,1,'人民币',100,0,1,1546866232,'鑫宝',NULL,NULL,NULL,NULL,NULL,1546867297);
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_phone_code`
--

LOCK TABLES `web_phone_code` WRITE;
/*!40000 ALTER TABLE `web_phone_code` DISABLE KEYS */;
INSERT INTO `web_phone_code` VALUES (1,'18948335137',0,20190108,'852403',8,1546931621),(2,'15626519209',0,20190108,'211100',3,1546932739),(3,'13879889603',0,20190108,'923177',1,1546933146),(4,'13476106738',0,20190108,'501953',1,1546958382),(5,'15626519209',0,20190110,'908718',1,1547126543),(6,'15626519209',0,20190111,'601752',4,1547196779),(7,'15625579122',0,20190111,'956027',10,1547217952),(8,'15623186812',0,20190111,'633382',1,1547211167),(9,'13476106738',0,20190111,'205105',1,1547211210),(10,'18948335137',0,20190111,'975911',3,1547213253),(11,'18934885137',0,20190111,'320231',1,1547212418),(12,'13923830675',0,20190111,'552056',3,1547214197),(13,'18948335137',0,20190112,'652886',1,1547279134);
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
  `recharge_userid` int(11) DEFAULT NULL COMMENT 'å……å€¼çŽ©å®¶ID',
  `recharge_name` varchar(50) DEFAULT NULL COMMENT 'å……å€¼åå­—',
  `recharge_order_sn` varchar(50) DEFAULT NULL COMMENT 'å……å€¼è®¢å•å·',
  `recharge_amount` int(11) DEFAULT NULL COMMENT 'å……å€¼é‡‘é¢',
  `agent_level` int(11) DEFAULT NULL COMMENT 'ä»£ç†ç­‰çº§',
  `agent_username` varchar(50) DEFAULT NULL COMMENT 'ä»£ç†æ‰‹æœºå·ç ',
  `agent_commission` int(11) DEFAULT NULL COMMENT 'åˆ†ä½£æ•°é‡',
  `agent_userid` int(11) DEFAULT NULL COMMENT 'ä»£ç†çŽ©å®¶ID',
  `level` int(11) DEFAULT NULL COMMENT 'åˆ†ä½£å±‚çº§',
  `buy_type` int(11) DEFAULT NULL COMMENT 'è´­ä¹°èµ„æºç±»åž‹',
  `buy_num` int(11) DEFAULT NULL COMMENT 'è´­ä¹°æ•°é‡',
  `time` int(11) DEFAULT NULL COMMENT 'åˆ†ä½£æ—¶é—´',
  `commission_type` tinyint(1) NOT NULL COMMENT '1å……å€¼åˆ†é”€ 2å¯¹æˆ˜èµ¢å®¶åˆ†é”€',
  `get_amount_user_type` tinyint(1) DEFAULT '1' COMMENT 'èŽ·å¾—åˆ†ä½£ç”¨æˆ·ç±»åž‹ 1ä»£ç†ç”¨æˆ· 2å¹³å°è¿è¥å•†',
  `foreign_key_id` int(10) unsigned NOT NULL COMMENT 'æŠ½æ°´è¡¨è®°å½•id',
  PRIMARY KEY (`id`),
  KEY `recharge_userid` (`recharge_userid`),
  KEY `agent_userid` (`agent_userid`),
  KEY `time` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_recharge_commission`
--

LOCK TABLES `web_recharge_commission` WRITE;
/*!40000 ALTER TABLE `web_recharge_commission` DISABLE KEYS */;
INSERT INTO `web_recharge_commission` VALUES (1,120003,'test name','',12,0,'平台运营商',12,0,0,100,12,1547036163,2,2,123),(2,120003,'test name','',14,0,'平台运营商',14,0,0,100,14,1547038563,2,2,139),(3,120003,'test name','',17,0,'平台运营商',17,0,0,100,17,1547038803,2,2,152),(4,120001,'test name','',16,0,'平台运营商',16,0,0,100,16,1547103666,2,2,196),(5,120003,'test name','',16,0,'平台运营商',16,0,0,100,16,1547103666,2,2,197),(6,118149,'test name','',4,0,'平台运营商',4,0,0,100,4,1547103725,2,2,200),(7,120000,'test name','',510,0,'平台运营商',510,0,0,100,510,1547105585,2,2,212),(8,120003,'test name','',20000,0,'平台运营商',20000,0,0,100,20000,1547107085,2,2,215),(9,120003,'test name','',1372,0,'平台运营商',1372,0,0,100,1372,1547110865,2,2,276),(10,120003,'test name','',154672,0,'平台运营商',154672,0,0,100,154672,1547110926,2,2,281),(11,120003,'test name','',74,0,'平台运营商',74,0,0,100,74,1547113326,2,2,309),(12,120003,'test name','',66,0,'平台运营商',66,0,0,100,66,1547114225,2,2,342),(13,120003,'test name','',56,0,'平台运营商',56,0,0,100,56,1547114645,2,2,358),(14,120003,'test name','',400,0,'平台运营商',400,0,0,100,400,1547115125,2,2,383),(15,120003,'test name','',400,0,'平台运营商',400,0,0,100,400,1547115125,2,2,385),(16,120003,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547115125,2,2,387),(17,120003,'test name','',402,0,'平台运营商',402,0,0,100,402,1547115185,2,2,392),(18,120003,'test name','',404,0,'平台运营商',404,0,0,100,404,1547115246,2,2,394),(19,120003,'test name','',400,0,'平台运营商',400,0,0,100,400,1547115306,2,2,399),(20,120001,'test name','',8000,0,'平台运营商',8000,0,0,100,8000,1547115366,2,2,408),(21,120001,'test name','',8000,0,'平台运营商',8000,0,0,100,8000,1547115425,2,2,412),(22,120003,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547115485,2,2,418),(23,120003,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547115485,2,2,422),(24,120003,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547115545,2,2,426),(25,120005,'test name','',4,0,'平台运营商',4,0,0,100,4,1547171947,2,2,479),(26,120003,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172007,2,2,483),(27,120003,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172007,2,2,487),(28,120003,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172007,2,2,491),(29,120003,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172067,2,2,495),(30,120005,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172067,2,2,501),(31,120005,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172307,2,2,516),(32,120003,'test name','',14,0,'平台运营商',14,0,0,100,14,1547172367,2,2,526),(33,120003,'test name','',400,0,'平台运营商',400,0,0,100,400,1547172427,2,2,537),(34,120003,'test name','',400,0,'平台运营商',400,0,0,100,400,1547172487,2,2,549),(35,120001,'test name','',400,0,'平台运营商',400,0,0,100,400,1547172547,2,2,551),(36,120078,'test name','',4,0,'平台运营商',4,0,0,100,4,1547172907,2,2,567),(37,120078,'test name','',300,0,'平台运营商',300,0,0,100,300,1547172967,2,2,574),(38,120003,'test name','',30,0,'平台运营商',30,0,0,100,30,1547174587,2,2,619),(39,120003,'test name','',924,0,'平台运营商',924,0,0,100,924,1547175007,2,2,632),(40,120003,'test name','',852,0,'平台运营商',852,0,0,100,852,1547175067,2,2,634),(41,120003,'test name','',2906,0,'平台运营商',2906,0,0,100,2906,1547175247,2,2,639),(42,120003,'test name','',604,0,'平台运营商',604,0,0,100,604,1547175427,2,2,645),(43,120003,'test name','',6,0,'平台运营商',6,0,0,100,6,1547175427,2,2,647),(44,120003,'test name','',5898,0,'平台运营商',5898,0,0,100,5898,1547175607,2,2,653),(45,120003,'test name','',2280,0,'平台运营商',2280,0,0,100,2280,1547175667,2,2,656),(46,120003,'test name','',240,0,'平台运营商',240,0,0,100,240,1547176447,2,2,669),(47,120003,'test name','',160,0,'平台运营商',160,0,0,100,160,1547176447,2,2,671),(48,120003,'test name','',60,0,'平台运营商',60,0,0,100,60,1547176987,2,2,694),(49,120003,'test name','',792,0,'平台运营商',792,0,0,100,792,1547177948,2,2,705),(50,120003,'test name','',80,0,'平台运营商',80,0,0,100,80,1547178127,2,2,710),(51,118024,'test name','',600,0,'平台运营商',600,0,0,100,600,1547272870,2,2,38),(52,118024,'test name','',200,0,'平台运营商',200,0,0,100,200,1547272930,2,2,40),(53,118024,'test name','',40,0,'平台运营商',40,0,0,100,40,1547273109,2,2,54),(54,118026,'test name','',40,0,'平台运营商',40,0,0,100,40,1547273169,2,2,60),(55,118026,'test name','',200,0,'平台运营商',200,0,0,100,200,1547273770,2,2,94),(56,118024,'test name','',400,0,'平台运营商',400,0,0,100,400,1547273830,2,2,100),(57,118026,'test name','',400,0,'平台运营商',400,0,0,100,400,1547273890,2,2,106),(58,118026,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547273950,2,2,110),(59,118024,'test name','',1600,0,'平台运营商',1600,0,0,100,1600,1547273950,2,2,112),(60,118026,'test name','',800,0,'平台运营商',800,0,0,100,800,1547274009,2,2,118),(61,118026,'test name','',800,0,'平台运营商',800,0,0,100,800,1547274009,2,2,122),(62,118024,'test name','',4400,0,'平台运营商',4400,0,0,100,4400,1547274070,2,2,124),(63,118024,'test name','',8400,0,'平台运营商',8400,0,0,100,8400,1547274130,2,2,128),(64,118026,'test name','',560,0,'平台运营商',560,0,0,100,560,1547274130,2,2,130),(65,118024,'test name','',16800,0,'平台运营商',16800,0,0,100,16800,1547274130,2,2,132),(66,118026,'test name','',2800,0,'平台运营商',2800,0,0,100,2800,1547274130,2,2,134),(67,118024,'test name','',4800,0,'平台运营商',4800,0,0,100,4800,1547274250,2,2,144),(68,118026,'test name','',1600,0,'平台运营商',1600,0,0,100,1600,1547274250,2,2,146),(69,118024,'test name','',13200,0,'平台运营商',13200,0,0,100,13200,1547274250,2,2,148),(70,118026,'test name','',4400,0,'平台运营商',4400,0,0,100,4400,1547274250,2,2,150),(71,118024,'test name','',2000,0,'平台运营商',2000,0,0,100,2000,1547274370,2,2,154),(72,118026,'test name','',20000,0,'平台运营商',20000,0,0,100,20000,1547274370,2,2,160),(73,118024,'test name','',4000,0,'平台运营商',4000,0,0,100,4000,1547274429,2,2,162),(74,118024,'test name','',1200,0,'平台运营商',1200,0,0,100,1200,1547274429,2,2,166),(75,118026,'test name','',1600,0,'平台运营商',1600,0,0,100,1600,1547274490,2,2,172),(76,118024,'test name','',80,0,'平台运营商',80,0,0,100,80,1547274550,2,2,174),(77,118024,'test name','',10260,0,'平台运营商',10260,0,0,100,10260,1547278210,2,2,180),(78,118024,'test name','',54600,0,'平台运营商',54600,0,0,100,54600,1547278330,2,2,184),(79,118024,'test name','',480,0,'平台运营商',480,0,0,100,480,1547281990,2,2,210),(80,118024,'test name','',300,0,'平台运营商',300,0,0,100,300,1547282110,2,2,218),(81,118024,'test name','',720,0,'平台运营商',720,0,0,100,720,1547284150,2,2,245),(82,118024,'test name','',12300,0,'平台运营商',12300,0,0,100,12300,1547285470,2,2,267),(83,118024,'test name','',60,0,'平台运营商',60,0,0,100,60,1547285470,2,2,269),(84,118024,'test name','',240,0,'平台运营商',240,0,0,100,240,1547285710,2,2,278),(85,118024,'test name','',540,0,'平台运营商',540,0,0,100,540,1547285770,2,2,280),(86,118024,'test name','',1020,0,'平台运营商',1020,0,0,100,1020,1547285770,2,2,282),(87,118024,'test name','',600,0,'平台运营商',600,0,0,100,600,1547285951,2,2,289),(88,118040,'test name','',9,0,'平台运营商',9,0,0,100,9,1547292611,2,2,320);
/*!40000 ALTER TABLE `web_recharge_commission` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_sign_config`
--

LOCK TABLES `web_sign_config` WRITE;
/*!40000 ALTER TABLE `web_sign_config` DISABLE KEYS */;
INSERT INTO `web_sign_config` VALUES (1,1,'金币',1,1,-8),(2,2,'金币',3,1,0),(3,3,'金币',3,1,3),(4,4,'金币',3,1,4),(5,5,'金币',3,1,0),(6,6,'金币',5,1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_sign_record`
--

LOCK TABLES `web_sign_record` WRITE;
/*!40000 ALTER TABLE `web_sign_record` DISABLE KEYS */;
INSERT INTO `web_sign_record` VALUES (1,'金币',1,1,1,1546839888,'111111',120001,1,0,1),(2,'金币',1,1,1,1546840259,'SDCSDF',120050,1,0,1),(3,'金币',1,1,1,1546854041,'ASDFASDFF',120051,1,0,1),(4,'金币',1,1,1,1546854081,'DFASDFA',120052,1,0,1),(5,'金币',1,1,1,1546865350,'222222',120003,1,0,1),(6,'金币',1,1,1,1546912985,'SDCVSDC',120046,1,0,1),(7,'金币',1,3,2,1546915698,'222222',120003,4,0,2),(8,'金币',1,3,2,1546916977,'111111',120001,4,0,2),(9,'金币',1,1,1,1546926731,'游客123719',120054,1,0,1),(10,'金币',1,1,1,1546934084,'13879889603',118001,1,0,1),(11,'金币',1,1,1,1546952918,'DFASDFAWD',120057,1,0,1),(12,'金币',1,1,1,1546958913,'13476106738',120059,1,0,1),(13,'金币',1,1,1,1547036214,'TI123456',120063,1,0,1),(14,'金币',1,3,3,1547037036,'222222',120003,7,0,3),(15,'金币',1,3,3,1547037985,'111111',120001,7,0,3),(16,'金币',1,1,1,1547045358,'游客901011',120066,1,0,1),(17,'金币',1,1,1,1547086691,'VASDFVA',120068,1,0,1),(18,'金币',1,1,1,1547087149,'FVERFAWERF',120069,1,0,1),(19,'金币',1,1,1,1547087949,'AREWFASF',120070,1,0,1),(20,'金币',1,1,1,1547093951,'FCASDFAS',120071,1,0,1),(21,'金币',1,1,1,1547101399,'DFVGAFVAS',120072,1,0,1),(22,'金币',1,3,4,1547103348,'111111',120001,10,0,4),(23,'金币',1,3,4,1547103373,'222222',120003,10,0,4),(24,'金币',1,1,1,1547113321,'ASDFGVASDV',120074,1,0,1),(25,'金币',1,1,1,1547114466,'FDBGAFGAFAS',120075,1,0,1),(26,'金币',1,1,1,1547114745,'ASDFASDFA',120076,1,0,1),(27,'金币',1,3,5,1547171664,'222222',120003,13,0,5),(28,'金币',1,1,1,1547171779,'333333',120005,1,0,1),(29,'金币',1,3,5,1547171795,'111111',120001,13,0,5),(30,'金币',1,1,1,1547172364,'GBR014',120077,1,0,1),(31,'金币',1,1,1,1547172399,'GBR015',120078,1,0,1),(32,'金币',1,1,1,1547177046,'GWERGF',120079,1,0,1),(33,'金币',1,1,1,1547195372,'',120108,1,0,1),(34,'金币',1,1,1,1547197588,'',120116,1,0,1),(35,'金币',1,1,1,1547197945,'',120117,1,0,1),(36,'金币',1,1,1,1547199408,'',120128,1,0,1),(37,'金币',1,1,1,1547202889,'',120140,1,0,1),(38,'金币',1,1,1,1547271186,'111111',118024,1,0,1),(39,'金币',1,1,1,1547271721,'游客819069',118025,1,0,1),(40,'金币',1,1,1,1547272946,'222222',118026,1,0,1),(41,'金币',1,1,1,1547286332,'游客523629',118029,1,0,1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='socket连接配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_socket_config`
--

LOCK TABLES `web_socket_config` WRITE;
/*!40000 ALTER TABLE `web_socket_config` DISABLE KEYS */;
INSERT INTO `web_socket_config` VALUES (1,'192.168.0.64',4015);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_turntable_config`
--

LOCK TABLES `web_turntable_config` WRITE;
/*!40000 ALTER TABLE `web_turntable_config` DISABLE KEYS */;
INSERT INTO `web_turntable_config` VALUES (1,'金币',1,66,1),(2,'金币',2,10,1),(3,'金币',4,7,1),(4,'金币',3,6,1),(5,'金币',3,5,1),(6,'金币',5,4,1),(7,'金币',57,1,1),(8,'无',0,1,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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

-- Dump completed on 2019-01-12 20:38:22
