-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: 172.17.0.19    Database: zhizunyule
-- ------------------------------------------------------
-- Server version	5.6.42

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
  `private_key` varchar(2000) NOT NULL COMMENT 'ç§é’¥',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `public_key` varchar(2000) NOT NULL COMMENT 'ç§é’¥',
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
INSERT INTO `web_pay_config` VALUES (1,'苹果支付','wx624ff377019b69a1','1487308352','12345678123456781234567812345aab',0,'12311','12','','1',0),(2,'微信支付','wx624ff377019b69a1','1487308352','12345678123456781234567812345aab',0,'','http://ht.szhuomei.com/hm_ucenter/pay/wei_xin/notify.php','','1',0),(3,'汇付宝','2112482','2116650','C53514AFB85F443BAE134E90',0,'','http://ht.szhuomei.com/hm_ucenter/pay/hui_fu_bao/notify.php','http://','1',0),(4,'旺实富','69018071165702','69018071165702','046909094053n7SiSWYr',0,'','http://ht.szhuomei.com/hm_ucenter/pay/wang_shi_fu/notify.php','http://','1',0),(5,'简付','180778708','180778708','i4dlpqh8eza9i2kuy9oo6oaez6hw6dor',0,'','http://ht.szhuomei.com/hm_ucenter/pay/jian_fu/notify.php','http://ht.szhuomei.com/hm_ucenter/pay/jian_fu/index.php','1',1),(6,'鑫宝','10068','10068','c4ek3opkh9f8j52cr8qcgs9i2k48f6z2',0,'','http://47.107.147.29/hm_ucenter/pay/xin_bao/notify.php','http://ht.szhuomei.com/hm_ucenter/pay/xin_bao/index.php','1',1),(7,'汇付宝直连','2112482','2112482','C53514AFB85F443BAE134E90',0,'','http://ht.szhuomei.com/hm_ucenter/pay/hui_fu_bao_zl/notify.php','http://','1',1),(8,'码付','9','10548','51269f3425b83e21dc58a3c1f429b1fd',0,'','https://zzyl.szhmkeji.com/hm_ucenter/pay/ma_fu/notify.php','http://zzyl.szhmkeji.com/logs/pay/result.php','1',1),(9,'支付宝','1','2018111762239203','MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDIo1hG43FYRKXgSJyZC0N2UMuoq7mUFQoAKfTc2MH/nAxcCzhTNqNiKTYSkAk010We/NRnrl9qmXggoxrVNDzPLV+mqfy4EajNA+tw8qNJ5r/c2vKbu1LoyCNMltIAWBkxiU34YhJxJIBxBCWySFlOSwFs5clbvD3TuI0/9qgpiA47I6564+3UjMk4voO3mWy1QB5NnJ1qHPSUwgxgOj+64YHjGYtZ9UC2RMwYb7zbXbl+GR8ZzJTCVWIFxdw0b4RiP/8kE9bLJOXmaV1KprVX4LoG6MOl0Ojo+8Y/aJ5Vzq4gNlQDF8eronUjOT2UrVD8Is4afgrpdfQCGdNiajeBAgMBAAECggEBAJGMHV/fYrv7oLFhO2iyiQ/tcLA9UvSfho+z4BPNkn4Q/85NvWBwRHnTF4zDvEn+2FyLYLKOBl3YUxKp/68mo+5PJhJLQi9tcBy5eTSIb/gOUqSumExsyapgdWr8Bf+F6VDMICnNSz+UkTBrJ8V7Qn3PX4tV7oqDtf4+5Qi/sCHfbAHb3sF7xOGFnZT2ihJBZgJrOM+jtrJGCoaG1LXft/rzO/KSWU0Hpb9m28m+2MHdDZ05upa2Ziiuqil2j/Z8bYGi36YUe+KXayKZIUjktwzl6APioXQKP34vwoRAzr36uQgq/sdbuSDzy83lfbkFoDNZK8rkYXp/UIyEATaChtkCgYEA/ypr6Tskc/hiigQUY4yz1l0VA8wfbdR2AnZktn6QzJSLpmhGK5dbZw1E/WYhuSi4c+DNoQYQ8iJgVyDTgRC6Gzg+titwzsYCZhBxPWRaqBzwVoYj1FqfwfR00Ctnl7suSMcRKxEH3N9XlqndcyVF+NANK51gfKl7t3wqPnYWbk8CgYEAyUtIW4SuIqGpr7M0Y4Posr6OtA04LzJrLpM6ImOafepi32wlNiwHTnWt/xdOyYG+HquyeIHFBYD9nEfnLjFqf6v9bDQjH4CcJvop8dEtoNwOI8KI4JF8U0TfAh5IHAJnUsFh3LI1IjmADh6hRDEH2yqPAsGbqePqiivxjdLF2S8CgYBNnbrLbCkPeauHrewByAfgGdpNmGarAuiOoTLLbD5hbIH6MVe+5MBx3VByAAIcD23kGaqS44R+EsTZW+vaI5tosjgpM5eR3htWroOzl/YXkw3sU8tMSfC0j8aEl99gARj4HE11my2YGvvKoYrx/pGFOMv4y1tRMECotIUpEMSL8wKBgQCu5E7o3R2jD0zsUEprUFekvbYlL/sp3qeLXvW6nUnErxnavw6Rw61ReSOFKE4W/DggU9OLqkxxXMJ8v2hYFS1P4jIciiweGdMSdnZtGO6I/cj3PvVIEH+xpXwRThge5eO53jVV2CwcIVNmcSxyoBsQTCjXHzpNYK4OAOIt8/xclwKBgAPz1zd2stJ+QYG9XC9ws28n3Gd5BxMWhtjYiiQDgZUU4MIwg6gm22k6oMmmwi+9047Vb3oTyL5inPUXFgNI0TXQWChQGv7Fj3CycUXNsTKahRKsLON5louBpN38pdOv8Os8ek5+060usAIZnRAqQlbTMn2wWitRw2Yy3LWMxtiN',1,'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl6fjQATqUAEMya+KkOLj7pBhYGivoYGM21U8RRWOY3zT1lf/7WhWe/ufy4jKNGd5Q8Q57IVijqAiqpoiOs/f4EEjBG4T+BJ1N8y8jhapeeEGWJrItoRwJDrmUA0NdH1fjjhpWfeJgBLKZZJ88WrUOzxOrmozghsf0DLHbIlpb2M3AjVNOzY39bHbqx7WjZGXACa/0DyZw52Frg6VYDpeB5VTC8liqlPdnTjw36o1FEjvTMa8XjQ88bWSvpnBTlxLatj7h331U3bPGDTC5un/qd8kP4QQI8UXQ2cwQqH6qiZxb/PevhUUMDiDGtov8bbDslAy8VzMPaYdL9VQR0DPowIDAQAB','https://zzyl.szhmkeji.com/hm_ucenter/pay/zhi_fu_bao/notify.php','','',1);
/*!40000 ALTER TABLE `web_pay_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-28 10:49:23
