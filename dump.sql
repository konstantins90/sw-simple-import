/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.20-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: db
-- ------------------------------------------------------
-- Server version	10.6.20-MariaDB-ubu2004

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `marge` float NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `mapping` text DEFAULT NULL,
  `mapping_properties` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'default-config',NULL,1,'2024-12-14 19:22:57','2024-12-14 22:30:58','{\"name\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"Наименование\"},\"description\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"Аннотация\"},\"productNumber\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"ISBN\"},\"ean\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"ISBN\"},\"stock\":{\"type\":\"default\",\"default\":\"100\",\"csv\":\"\"},\"taxId\":{\"type\":\"default\",\"default\":\"7\",\"csv\":\"\"},\"manufacturer\":{\"type\":\"csv\",\"default\":\"Издательство\",\"csv\":\"Издательство\"},\"weight\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"Вес\"},\"media\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"Печ ссылка\"},\"salesChannelId\":{\"type\":\"default\",\"default\":\"555\",\"csv\":\"\"},\"visibility\":{\"type\":\"default\",\"default\":\"666\",\"csv\":\"\"}}',NULL),(3,'тест',NULL,1,'2024-12-14 22:00:51','2024-12-14 22:00:51','',NULL),(4,'Demo',NULL,1,'2024-12-14 22:20:50','2024-12-14 22:20:50','{\"name\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"Наименование\"},\"description\":{\"type\":\"default\",\"default\":\"100\",\"csv\":\"\"},\"productNumber\":{\"type\":\"csv\",\"default\":\"\",\"csv\":\"ISBN\"},\"ean\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"stock\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"taxId\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"manufacturer\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"weight\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"media\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"salesChannelId\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"},\"visibility\":{\"type\":\"default\",\"default\":\"\",\"csv\":\"\"}}',NULL);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `config_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `files_fi_ecb45f` (`config_id`),
  CONSTRAINT `files_fk_ecb45f` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (22,'nigma.csv','n/i/nigma_675de8551989e.csv','pending',1,'2024-12-14 20:19:33','2024-12-14 20:19:33'),(23,'demo.csv','d/e/demo_675de85b7be74.csv','pending',1,'2024-12-14 20:19:39','2024-12-14 20:19:39');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_history`
--

DROP TABLE IF EXISTS `import_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `imported_at` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `count_imported_products` int(11) NOT NULL DEFAULT 0,
  `count_errors` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `import_history_fi_568a7d` (`file_id`),
  CONSTRAINT `import_history_fk_568a7d` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_history`
--

LOCK TABLES `import_history` WRITE;
/*!40000 ALTER TABLE `import_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propel_migration`
--

DROP TABLE IF EXISTS `propel_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propel_migration` (
  `version` int(11) DEFAULT 0,
  `execution_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propel_migration`
--

LOCK TABLES `propel_migration` WRITE;
/*!40000 ALTER TABLE `propel_migration` DISABLE KEYS */;
INSERT INTO `propel_migration` VALUES (1734133172,'2024-12-14 00:39:38'),(1734203729,'2024-12-14 20:15:34'),(1734206838,'2024-12-14 21:07:25'),(1734207501,'2024-12-14 21:18:23');
/*!40000 ALTER TABLE `propel_migration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-06 20:49:31
