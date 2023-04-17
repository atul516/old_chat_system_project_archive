-- MySQL dump 10.13  Distrib 5.1.46, for suse-linux-gnu (i686)
--
-- Host: localhost    Database: chat
-- ------------------------------------------------------
-- Server version	5.1.46-log

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
-- Table structure for table `current_chat_pairs`
--

DROP TABLE IF EXISTS `current_chat_pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `current_chat_pairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiated_by` int(11) NOT NULL,
  `accepted_by` int(11) NOT NULL,
  `timestamp` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_log`
--

DROP TABLE IF EXISTS `error_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `error_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `interests_info`
--

DROP TABLE IF EXISTS `interests_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interests_info` (
  `uniqid` int(11) NOT NULL,
  `about` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `things_to_talk` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uniqid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_store`
--

DROP TABLE IF EXISTS `message_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chat_pair_id` int(11) NOT NULL,
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `sent_on` datetime NOT NULL,
  `timestamp` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modification`
--

DROP TABLE IF EXISTS `modification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modification` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `uniqid` int(11) NOT NULL,
  `nick` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `mod_time` datetime NOT NULL,
  `type` tinyint(4) NOT NULL,
  `timestamp` int(16) NOT NULL,
  PRIMARY KEY (`index`),
  KEY `mod_time` (`mod_time`)
) ENGINE=MyISAM AUTO_INCREMENT=5112 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_users`
--

DROP TABLE IF EXISTS `online_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_users` (
  `uniqid` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `first_logged_in` datetime NOT NULL,
  `first_loop_request` datetime DEFAULT NULL,
  `last_online` int(20) DEFAULT NULL,
  `receive_chats_flag` binary(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uniqid`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=MyISAM AUTO_INCREMENT=924 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pending_requests`
--

DROP TABLE IF EXISTS `pending_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pending_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `randomid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=210 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perm_message_store`
--

DROP TABLE IF EXISTS `perm_message_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perm_message_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chat_pair_id` int(11) NOT NULL,
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `sent_on` datetime NOT NULL,
  `timestamp` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test45`
--

DROP TABLE IF EXISTS `test45`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test45` (
  `Password` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uncertain`
--

DROP TABLE IF EXISTS `uncertain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uncertain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqid` int(11) NOT NULL,
  `finish_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `finish_time` (`finish_time`)
) ENGINE=MyISAM AUTO_INCREMENT=3346 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-11-20  3:58:30
