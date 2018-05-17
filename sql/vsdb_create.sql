CREATE DATABASE  IF NOT EXISTS `vsdb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `vsdb`;
-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: d-dbmysql02.pgj.rj.gov.br    Database: vsdb
-- ------------------------------------------------------
-- Server version	5.6.23-log

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
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Schema\n',
  `name` varchar(40) NOT NULL COMMENT 'Descrição do Schema',
  `dt_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_excluded` tinyint(1) DEFAULT NULL,
  `dt_excluded` datetime DEFAULT NULL,
  `who_excluded_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_accounts_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1 COMMENT='Schema dos bancos de dados oracle';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `name`, `dt_create`, `is_excluded`, `dt_excluded`, `who_excluded_id`) VALUES (1,'Schema_Test','2017-03-16 16:32:28',1,'2017-03-21 14:56:40',NULL);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analysts`
--

DROP TABLE IF EXISTS `analysts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do analista\n\n',
  `name` varchar(50) NOT NULL COMMENT 'Nome analista\n',
  `team` char(1) NOT NULL,
  `login` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `dt_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `number_rows` int(2) DEFAULT '20',
  `last_evolutions` tinyint(4) DEFAULT '0',
  `is_excluded` tinyint(1) DEFAULT NULL,
  `dt_excluded` datetime DEFAULT NULL,
  `who_excluded_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_analysts_id` (`id`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analysts`
--

LOCK TABLES `analysts` WRITE;
/*!40000 ALTER TABLE `analysts` DISABLE KEYS */;
INSERT INTO `analysts` (`id`, `name`, `team`, `login`, `password`, `dt_create`, `number_rows`, `last_evolutions`, `is_excluded`, `dt_excluded`, `who_excluded_id`) VALUES (1,'analyst_test','D','test','c06f8f426cec62bc59be133288afe5f2','2015-07-03 13:52:02',10,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `analysts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `environments`
--

DROP TABLE IF EXISTS `environments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `environments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do ambiente',
  `name` char(30) NOT NULL COMMENT 'Descrição do ambiente\n',
  `dt_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_excluded` tinyint(1) DEFAULT NULL,
  `dt_excluded` datetime DEFAULT NULL,
  `who_excluded_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_enviroments_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `environments`
--

LOCK TABLES `environments` WRITE;
/*!40000 ALTER TABLE `environments` DISABLE KEYS */;
INSERT INTO `environments` (`id`, `name`, `dt_create`, `is_excluded`, `dt_excluded`, `who_excluded_id`) VALUES (1,'Desenvolvimento','2015-07-03 13:52:02',0,NULL,NULL),(2,'Homologação','2015-07-03 13:52:02',0,NULL,NULL),(3,'Correção','2015-07-03 13:52:02',0,NULL,NULL),(4,'Produção','2015-07-03 13:52:02',0,NULL,NULL),(5,'Qualidade','2015-07-03 19:56:32',0,NULL,NULL);
/*!40000 ALTER TABLE `environments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evolutions`
--

DROP TABLE IF EXISTS `evolutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evolutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador da evolução.',
  `environment_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `script_id` int(11) NOT NULL,
  `analyst_id` int(11) NOT NULL,
  `glpi` int(11) NOT NULL,
  `description` varchar(1000) NOT NULL COMMENT 'Descrição da solicitação',
  `evolution_glpi` int(11) DEFAULT NULL,
  `dt_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data do script',
  `is_excluded` tinyint(1) DEFAULT NULL,
  `dt_excluded` datetime DEFAULT NULL,
  `who_excluded_id` int(11) DEFAULT NULL,
  `sequence_id` int(11) NOT NULL,
  `event` char(1) NOT NULL DEFAULT 'N',
  `dt_evolution` datetime DEFAULT NULL,
  `evolution_environment_id` int(11) DEFAULT NULL,
  `analyst_dba_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_evolution_id` (`id`),
  KEY `idx_fk_evolutions_accounts` (`account_id`),
  KEY `idx_fk_evolutions_analysts` (`analyst_id`),
  KEY `idx_fk_evolutions_enviroments` (`environment_id`) USING BTREE,
  KEY `idx_fk_evolutions_scripts` (`script_id`),
  KEY `idx_fk_evolutions_analysts_dba` (`analyst_dba_id`),
  KEY `idx_fk_evolutions_env_enviroments` (`evolution_environment_id`),
  KEY `idx_evolution_glpi` (`glpi`),
  KEY `fk_evolutions_evolutions_glpi_idx` (`evolution_glpi`),
  CONSTRAINT `fk_evolutions_accounts_id` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_analysts_dba_id` FOREIGN KEY (`analyst_dba_id`) REFERENCES `analysts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_analysts_id` FOREIGN KEY (`analyst_id`) REFERENCES `analysts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_env_enviroments` FOREIGN KEY (`evolution_environment_id`) REFERENCES `environments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_enviroments_id` FOREIGN KEY (`environment_id`) REFERENCES `environments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_evolutions_glpi` FOREIGN KEY (`evolution_glpi`) REFERENCES `evolutions` (`glpi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evolutions_scripts_id` FOREIGN KEY (`script_id`) REFERENCES `scripts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evolutions`
--

LOCK TABLES `evolutions` WRITE;
/*!40000 ALTER TABLE `evolutions` DISABLE KEYS */;
/*!40000 ALTER TABLE `evolutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scripts`
--

DROP TABLE IF EXISTS `scripts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do script\n',
  `version` int(11) NOT NULL,
  `glpi` int(11) NOT NULL COMMENT 'Identificador do GLPI',
  `name` varchar(100) NOT NULL,
  `script` longtext NOT NULL COMMENT 'Descrição do script\n',
  `observation` varchar(150) DEFAULT NULL,
  `dt_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data do script',
  `is_excluded` tinyint(1) DEFAULT NULL,
  `dt_excluded` datetime DEFAULT NULL,
  `who_excluded_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_scripts_glpi` (`glpi`) USING BTREE,
  KEY `idx_scripts_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1428 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scripts`
--

LOCK TABLES `scripts` WRITE;
/*!40000 ALTER TABLE `scripts` DISABLE KEYS */;
/*!40000 ALTER TABLE `scripts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `sequences_versions`
--

DROP TABLE IF EXISTS `sequences_versions`;
/*!50001 DROP VIEW IF EXISTS `sequences_versions`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `sequences_versions` AS SELECT 
 1 AS `sequence_id`,
 1 AS `version`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `sequences_versions`
--

/*!50001 DROP VIEW IF EXISTS `sequences_versions`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`dba`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `sequences_versions` AS select `ev`.`sequence_id` AS `sequence_id`,max(`sc`.`version`) AS `version` from (`evolutions` `ev` join `scripts` `sc` on((`ev`.`script_id` = `sc`.`id`))) where (isnull(`ev`.`is_excluded`) or (`ev`.`is_excluded` = 0)) group by `ev`.`sequence_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-17 18:40:43
