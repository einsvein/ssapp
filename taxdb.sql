-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: taxdb
-- ------------------------------------------------------
-- Server version	10.11.6-MariaDB-0+deb12u1

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
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_name` varchar(255) NOT NULL DEFAULT 'utlandLoenn',
  `initial_liability` varchar(50) NOT NULL DEFAULT 'resident',
  `tax_question` text NOT NULL DEFAULT 'tax liability for salary from Country B',
  `conclusion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `double_tax_residency` varchar(255) DEFAULT NULL,
  `answers` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cases`
--

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;
INSERT INTO `cases` VALUES
(2,'utlandLoenn','resident','tax liability for salary from Country B','Case type: <a href=\"double_tax_treaty.php?case_id=2\">Double Tax Treaty</a>','2025-03-02 13:59:09','Country A','{\"stayPermanent\":\"yes\",\"taxResidentA10\":\"yes\",\"stayA61\":\"yes\",\"residentTaxB\":\"yes\"}'),
(4,'utlandLoenn','resident','tax liability for salary from Country B','Case type: <a href=\"double_tax_treaty.php?case_id=4\">Double Tax Treaty</a>','2025-03-02 14:17:51','Country B','{\"stayPermanent\":\"no\",\"residentTaxB\":\"yes\"}'),
(5,'utlandLoenn','resident','tax liability for salary from Country B','Case type: <a href=\"termination_tax_residency.php\">Termination of Tax Residency</a>','2025-03-02 14:32:54',NULL,'{\"stayPermanent\":\"yes\",\"taxResidentA10\":\"no\",\"stayA61\":\"no\",\"homeAccessA\":\"no\",\"residentTaxB\":\"yes\"}'),
(6,'utlandLoenn','resident','tax liability for salary from Country B','Claim not accepted: <a href=\"avoidance_double_tax.php\">Avoidance of Double Taxation</a>','2025-03-02 14:35:04',NULL,'{\"stayPermanent\":\"no\",\"residentTaxB\":\"no\"}');
/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_residency_questions`
--

DROP TABLE IF EXISTS `tax_residency_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_residency_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `question_key` varchar(50) NOT NULL,
  `answer` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`),
  CONSTRAINT `tax_residency_questions_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_residency_questions`
--

LOCK TABLES `tax_residency_questions` WRITE;
/*!40000 ALTER TABLE `tax_residency_questions` DISABLE KEYS */;
INSERT INTO `tax_residency_questions` VALUES
(6,2,'permanent_home','both','2025-03-02 13:59:43'),
(7,2,'vital_interests','Country A','2025-03-02 13:59:49'),
(21,4,'permanent_home','both','2025-03-02 14:32:34'),
(22,4,'vital_interests','both','2025-03-02 14:32:37'),
(23,4,'habitual_abode','Country B','2025-03-02 14:32:40');
/*!40000 ALTER TABLE `tax_residency_questions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-04 17:55:10
