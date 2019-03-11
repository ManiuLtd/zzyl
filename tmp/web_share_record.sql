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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `web_share_record`
--

LOCK TABLES `web_share_record` WRITE;
/*!40000 ALTER TABLE `web_share_record` DISABLE KEYS */;
INSERT INTO `web_share_record` VALUES (1,120010,'妹平哥',1,1545795706,0,0,0,0,1),(2,120007,'kk',1,1545825253,0,0,0,0,1),(3,120007,'kk',1,1546050064,0,0,0,0,2),(4,120005,'BigJohn',1,1546414341,0,0,0,0,1),(5,120007,'kk',1,1546496154,0,0,0,0,3),(6,120007,'kk',1,1546496189,0,0,0,0,4),(7,120007,'kk',1,1546498345,5,0,5,0,5),(8,120007,'kk',1,1546498532,0,0,5,0,6),(9,120007,'kk',1,1546498589,0,0,5,0,7),(10,120007,'kk',1,1546499527,0,0,5,0,8);
/*!40000 ALTER TABLE `web_share_record` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-03 15:33:29
