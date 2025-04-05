-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: sysapae
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointment_types`
--

DROP TABLE IF EXISTS `appointment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_types`
--

LOCK TABLES `appointment_types` WRITE;
/*!40000 ALTER TABLE `appointment_types` DISABLE KEYS */;
INSERT INTO `appointment_types` VALUES (1,'Consulta'),(2,'Retorno'),(3,'Acompanhamento');
/*!40000 ALTER TABLE `appointment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_type_id` int NOT NULL,
  `professional_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_appointments_appointment_types1_idx` (`appointment_type_id`),
  KEY `fk_appointments_professionals1_idx` (`professional_id`),
  KEY `fk_appointments_patients1_idx` (`patient_id`),
  CONSTRAINT `fk_appointments_appointment_types1` FOREIGN KEY (`appointment_type_id`) REFERENCES `appointment_types` (`id`),
  CONSTRAINT `fk_appointments_patients1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `fk_appointments_professionals1` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disabilities`
--

DROP TABLE IF EXISTS `disabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disabilities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disabilities`
--

LOCK TABLES `disabilities` WRITE;
/*!40000 ALTER TABLE `disabilities` DISABLE KEYS */;
/*!40000 ALTER TABLE `disabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_disabilities`
--

DROP TABLE IF EXISTS `patient_disabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_disabilities` (
  `patient_id` int NOT NULL,
  `disability_id` int NOT NULL,
  KEY `fk_patients_has_disabilities_disabilities1_idx` (`disability_id`),
  KEY `fk_patients_has_disabilities_patients1_idx` (`patient_id`),
  CONSTRAINT `fk_patients_has_disabilities_disabilities1` FOREIGN KEY (`disability_id`) REFERENCES `disabilities` (`id`),
  CONSTRAINT `fk_patients_has_disabilities_patients1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_disabilities`
--

LOCK TABLES `patient_disabilities` WRITE;
/*!40000 ALTER TABLE `patient_disabilities` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_disabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sex_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birth` date NOT NULL,
  `cpf` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cns` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sus` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `responsible` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `zip_code` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `complement` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uf` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `observation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `active` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Y',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_patients_sex1_idx` (`sex_id`),
  CONSTRAINT `fk_patients_sex1` FOREIGN KEY (`sex_id`) REFERENCES `sex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professional_specialities`
--

DROP TABLE IF EXISTS `professional_specialities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professional_specialities` (
  `professional_id` int NOT NULL,
  `speciality_id` int NOT NULL,
  KEY `fk_professionals_has_specialities_specialities1_idx` (`speciality_id`),
  KEY `fk_professionals_has_specialities_professionals1_idx` (`professional_id`),
  CONSTRAINT `fk_professionals_has_specialities_professionals1` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`id`),
  CONSTRAINT `fk_professionals_has_specialities_specialities1` FOREIGN KEY (`speciality_id`) REFERENCES `specialities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professional_specialities`
--

LOCK TABLES `professional_specialities` WRITE;
/*!40000 ALTER TABLE `professional_specialities` DISABLE KEYS */;
/*!40000 ALTER TABLE `professional_specialities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professionals`
--

DROP TABLE IF EXISTS `professionals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professionals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `crm` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip_code` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complement` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `district` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reference` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_general_ci,
  `active` char(1) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professionals`
--

LOCK TABLES `professionals` WRITE;
/*!40000 ALTER TABLE `professionals` DISABLE KEYS */;
/*!40000 ALTER TABLE `professionals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sex`
--

DROP TABLE IF EXISTS `sex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sex` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sex`
--

LOCK TABLES `sex` WRITE;
/*!40000 ALTER TABLE `sex` DISABLE KEYS */;
INSERT INTO `sex` VALUES (1,'Masculino'),(2,'Feminino');
/*!40000 ALTER TABLE `sex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialities`
--

DROP TABLE IF EXISTS `specialities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialities`
--

LOCK TABLES `specialities` WRITE;
/*!40000 ALTER TABLE `specialities` DISABLE KEYS */;
INSERT INTO `specialities` VALUES (1,'Psicólogo'),(2,'Fisioterapeuta'),(3,'Fonoáudiologo'),(4,'Psiquiatra'),(5,'Nutricionista'),(6,'Pedagogo'),(7,'Assistente Social'),(8,'Terapia Ocupacional'),(9,'Dentista');
/*!40000 ALTER TABLE `specialities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_document`
--

DROP TABLE IF EXISTS `system_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_document` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `submission_date` date DEFAULT NULL,
  `archive_date` date DEFAULT NULL,
  `filename` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `in_trash` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `system_folder_id` int DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `content_type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_document_user_idx` (`system_user_id`),
  KEY `sys_document_folder_idx` (`system_folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_document`
--

LOCK TABLES `system_document` WRITE;
/*!40000 ALTER TABLE `system_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_document_bookmark`
--

DROP TABLE IF EXISTS `system_document_bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_document_bookmark` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_document_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_document_bookmark_user_idx` (`system_user_id`),
  KEY `sys_document_bookmark_document_idx` (`system_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_document_bookmark`
--

LOCK TABLES `system_document_bookmark` WRITE;
/*!40000 ALTER TABLE `system_document_bookmark` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_document_bookmark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_document_group`
--

DROP TABLE IF EXISTS `system_document_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_document_group` (
  `id` int NOT NULL,
  `document_id` int DEFAULT NULL,
  `system_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_document_group_document_idx` (`document_id`),
  KEY `sys_document_group_group_idx` (`system_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_document_group`
--

LOCK TABLES `system_document_group` WRITE;
/*!40000 ALTER TABLE `system_document_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_document_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_document_user`
--

DROP TABLE IF EXISTS `system_document_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_document_user` (
  `id` int NOT NULL,
  `document_id` int DEFAULT NULL,
  `system_user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_document_user_document_idx` (`document_id`),
  KEY `sys_document_user_user_idx` (`system_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_document_user`
--

LOCK TABLES `system_document_user` WRITE;
/*!40000 ALTER TABLE `system_document_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_document_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_folder`
--

DROP TABLE IF EXISTS `system_folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_folder` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `in_trash` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `system_folder_parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_folder_user_id_idx` (`system_user_id`),
  KEY `sys_folder_name_idx` (`name`),
  KEY `sys_folder_parend_id_idx` (`system_folder_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_folder`
--

LOCK TABLES `system_folder` WRITE;
/*!40000 ALTER TABLE `system_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_folder_bookmark`
--

DROP TABLE IF EXISTS `system_folder_bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_folder_bookmark` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_folder_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_folder_bookmark_user_idx` (`system_user_id`),
  KEY `sys_folder_bookmark_folder_idx` (`system_folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_folder_bookmark`
--

LOCK TABLES `system_folder_bookmark` WRITE;
/*!40000 ALTER TABLE `system_folder_bookmark` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_folder_bookmark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_folder_group`
--

DROP TABLE IF EXISTS `system_folder_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_folder_group` (
  `id` int NOT NULL,
  `system_folder_id` int DEFAULT NULL,
  `system_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_folder_group_folder_idx` (`system_folder_id`),
  KEY `sys_folder_group_group_idx` (`system_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_folder_group`
--

LOCK TABLES `system_folder_group` WRITE;
/*!40000 ALTER TABLE `system_folder_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_folder_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_folder_user`
--

DROP TABLE IF EXISTS `system_folder_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_folder_user` (
  `id` int NOT NULL,
  `system_folder_id` int DEFAULT NULL,
  `system_user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_folder_user_folder_idx` (`system_folder_id`),
  KEY `sys_folder_user_user_idx` (`system_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_folder_user`
--

LOCK TABLES `system_folder_user` WRITE;
/*!40000 ALTER TABLE `system_folder_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_folder_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_group`
--

DROP TABLE IF EXISTS `system_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_group` (
  `id` int NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_group_name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_group`
--

LOCK TABLES `system_group` WRITE;
/*!40000 ALTER TABLE `system_group` DISABLE KEYS */;
INSERT INTO `system_group` VALUES (3,'Application - Programs'),(1,'Template - Admin'),(2,'Template - Users');
/*!40000 ALTER TABLE `system_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_group_program`
--

DROP TABLE IF EXISTS `system_group_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_group_program` (
  `id` int NOT NULL,
  `system_group_id` int DEFAULT NULL,
  `system_program_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_group_program_program_idx` (`system_program_id`),
  KEY `sys_group_program_group_idx` (`system_group_id`),
  CONSTRAINT `system_group_program_ibfk_1` FOREIGN KEY (`system_group_id`) REFERENCES `system_group` (`id`),
  CONSTRAINT `system_group_program_ibfk_2` FOREIGN KEY (`system_program_id`) REFERENCES `system_program` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_group_program`
--

LOCK TABLES `system_group_program` WRITE;
/*!40000 ALTER TABLE `system_group_program` DISABLE KEYS */;
INSERT INTO `system_group_program` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(9,1,9),(10,1,10),(11,1,11),(12,1,12),(13,1,13),(14,1,14),(15,1,15),(16,1,16),(17,1,17),(18,1,18),(19,1,19),(20,1,20),(21,1,21),(22,1,22),(23,1,23),(24,1,24),(25,1,25),(26,1,26),(27,1,27),(28,1,28),(29,2,29),(30,2,30),(31,2,31),(32,2,32),(33,2,33),(34,2,34),(35,2,35),(36,2,36),(37,2,37),(38,1,38),(39,1,39),(40,1,40),(41,1,41),(42,1,42),(43,1,43),(44,1,44),(45,1,45),(46,2,46),(47,2,47),(48,2,48),(49,2,49),(50,2,50),(51,2,51),(52,2,52),(53,2,53),(54,2,54),(55,2,55),(56,2,56),(57,2,57),(58,2,58),(59,2,59),(60,2,60),(61,2,61),(62,2,62),(63,2,63),(64,2,64);
/*!40000 ALTER TABLE `system_group_program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_message`
--

DROP TABLE IF EXISTS `system_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_message` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_user_to_id` int DEFAULT NULL,
  `subject` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `dt_message` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `checked` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `removed` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `viewed` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `sys_message_user_id_idx` (`system_user_id`),
  KEY `sys_message_user_to_idx` (`system_user_to_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_message`
--

LOCK TABLES `system_message` WRITE;
/*!40000 ALTER TABLE `system_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_message_tag`
--

DROP TABLE IF EXISTS `system_message_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_message_tag` (
  `id` int NOT NULL,
  `system_message_id` int NOT NULL,
  `tag` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_message_tag_msg_idx` (`system_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_message_tag`
--

LOCK TABLES `system_message_tag` WRITE;
/*!40000 ALTER TABLE `system_message_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_message_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_notification`
--

DROP TABLE IF EXISTS `system_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_notification` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_user_to_id` int DEFAULT NULL,
  `subject` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `dt_message` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `action_url` text COLLATE utf8mb4_general_ci,
  `action_label` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `checked` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_notification_user_id_idx` (`system_user_id`),
  KEY `sys_notification_user_to_idx` (`system_user_to_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_notification`
--

LOCK TABLES `system_notification` WRITE;
/*!40000 ALTER TABLE `system_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_post`
--

DROP TABLE IF EXISTS `system_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_post` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `active` char(1) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `sys_post_user_idx` (`system_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_post`
--

LOCK TABLES `system_post` WRITE;
/*!40000 ALTER TABLE `system_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_post_comment`
--

DROP TABLE IF EXISTS `system_post_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_post_comment` (
  `id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `system_user_id` int NOT NULL,
  `system_post_id` int NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_post_comment_user_idx` (`system_user_id`),
  KEY `sys_post_comment_post_idx` (`system_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_post_comment`
--

LOCK TABLES `system_post_comment` WRITE;
/*!40000 ALTER TABLE `system_post_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_post_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_post_like`
--

DROP TABLE IF EXISTS `system_post_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_post_like` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_post_id` int NOT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_post_like_user_idx` (`system_user_id`),
  KEY `sys_post_like_post_idx` (`system_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_post_like`
--

LOCK TABLES `system_post_like` WRITE;
/*!40000 ALTER TABLE `system_post_like` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_post_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_post_share_group`
--

DROP TABLE IF EXISTS `system_post_share_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_post_share_group` (
  `id` int NOT NULL,
  `system_group_id` int DEFAULT NULL,
  `system_post_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_post_share_group_group_idx` (`system_group_id`),
  KEY `sys_post_share_group_post_idx` (`system_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_post_share_group`
--

LOCK TABLES `system_post_share_group` WRITE;
/*!40000 ALTER TABLE `system_post_share_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_post_share_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_post_tag`
--

DROP TABLE IF EXISTS `system_post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_post_tag` (
  `id` int NOT NULL,
  `system_post_id` int NOT NULL,
  `tag` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_post_tag_post_idx` (`system_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_post_tag`
--

LOCK TABLES `system_post_tag` WRITE;
/*!40000 ALTER TABLE `system_post_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_post_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_preference`
--

DROP TABLE IF EXISTS `system_preference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_preference` (
  `id` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  KEY `sys_preference_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_preference`
--

LOCK TABLES `system_preference` WRITE;
/*!40000 ALTER TABLE `system_preference` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_preference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_program`
--

DROP TABLE IF EXISTS `system_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_program` (
  `id` int NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `controller` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_program_name_idx` (`name`),
  KEY `sys_program_controller_idx` (`controller`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_program`
--

LOCK TABLES `system_program` WRITE;
/*!40000 ALTER TABLE `system_program` DISABLE KEYS */;
INSERT INTO `system_program` VALUES (1,'System Administration Dashboard','SystemAdministrationDashboard'),(2,'System Program Form','SystemProgramForm'),(3,'System Program List','SystemProgramList'),(4,'System Group Form','SystemGroupForm'),(5,'System Group List','SystemGroupList'),(6,'System Unit Form','SystemUnitForm'),(7,'System Unit List','SystemUnitList'),(8,'System Role Form','SystemRoleForm'),(9,'System Role List','SystemRoleList'),(10,'System User Form','SystemUserForm'),(11,'System User List','SystemUserList'),(12,'System Preference form','SystemPreferenceForm'),(13,'System Log Dashboard','SystemLogDashboard'),(14,'System Access Log','SystemAccessLogList'),(15,'System ChangeLog View','SystemChangeLogView'),(16,'System Sql Log','SystemSqlLogList'),(17,'System Request Log','SystemRequestLogList'),(18,'System Request Log View','SystemRequestLogView'),(19,'System PHP Error','SystemPHPErrorLogView'),(20,'System Session vars','SystemSessionVarsView'),(21,'System Database Browser','SystemDatabaseExplorer'),(22,'System Table List','SystemTableList'),(23,'System Data Browser','SystemDataBrowser'),(24,'System SQL Panel','SystemSQLPanel'),(25,'System Modules','SystemModulesCheckView'),(26,'System files diff','SystemFilesDiff'),(27,'System Information','SystemInformationView'),(28,'System PHP Info','SystemPHPInfoView'),(29,'Common Page','CommonPage'),(30,'Welcome View','WelcomeView'),(31,'Welcome dashboard','WelcomeDashboardView'),(32,'System Profile View','SystemProfileView'),(33,'System Profile Form','SystemProfileForm'),(34,'System Notification List','SystemNotificationList'),(35,'System Notification Form View','SystemNotificationFormView'),(36,'System Support form','SystemSupportForm'),(37,'System Profile 2FA Form','SystemProfile2FAForm'),(38,'System Wiki list','SystemWikiList'),(39,'System Wiki form','SystemWikiForm'),(40,'System Wiki page picker','SystemWikiPagePicker'),(41,'System Post list','SystemPostList'),(42,'System Post form','SystemPostForm'),(43,'System schedule list','SystemScheduleList'),(44,'System schedule form','SystemScheduleForm'),(45,'System schedule log','SystemScheduleLogList'),(46,'System Message Form','SystemMessageForm'),(47,'System Message List','SystemMessageList'),(48,'System Message Form View','SystemMessageFormView'),(49,'System Documents','SystemDriveList'),(50,'System Folder form','SystemFolderForm'),(51,'System Share folder','SystemFolderShareForm'),(52,'System Share document','SystemDocumentShareForm'),(53,'System Document properties','SystemDocumentFormWindow'),(54,'System Folder properties','SystemFolderFormView'),(55,'System Document upload','SystemDriveDocumentUploadForm'),(56,'Post View list','SystemPostFeedView'),(57,'Post Comment form','SystemPostCommentForm'),(58,'Post Comment list','SystemPostCommentList'),(59,'System Wiki search','SystemWikiSearchList'),(60,'System Wiki view','SystemWikiView'),(61,'System Message Tag form','SystemMessageTagForm'),(62,'System Contacts list','SystemContactsList'),(63,'Text document editor','SystemTextDocumentEditor'),(64,'System document create form','SystemDriveDocumentCreateForm');
/*!40000 ALTER TABLE `system_program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_program_method_role`
--

DROP TABLE IF EXISTS `system_program_method_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_program_method_role` (
  `id` int NOT NULL,
  `system_program_id` int DEFAULT NULL,
  `system_role_id` int DEFAULT NULL,
  `method_name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_program_method_role_program_idx` (`system_program_id`),
  KEY `sys_program_method_role_role_idx` (`system_role_id`),
  CONSTRAINT `system_program_method_role_ibfk_1` FOREIGN KEY (`system_program_id`) REFERENCES `system_program` (`id`),
  CONSTRAINT `system_program_method_role_ibfk_2` FOREIGN KEY (`system_role_id`) REFERENCES `system_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_program_method_role`
--

LOCK TABLES `system_program_method_role` WRITE;
/*!40000 ALTER TABLE `system_program_method_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_program_method_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_role`
--

DROP TABLE IF EXISTS `system_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_role` (
  `id` int NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custom_code` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_role_name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_role`
--

LOCK TABLES `system_role` WRITE;
/*!40000 ALTER TABLE `system_role` DISABLE KEYS */;
INSERT INTO `system_role` VALUES (1,'Role A',''),(2,'Role B','');
/*!40000 ALTER TABLE `system_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_schedule`
--

DROP TABLE IF EXISTS `system_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_schedule` (
  `id` int NOT NULL,
  `schedule_type` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `class_name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `method` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `monthday` char(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `weekday` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hour` char(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `minute` char(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_schedule`
--

LOCK TABLES `system_schedule` WRITE;
/*!40000 ALTER TABLE `system_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_unit`
--

DROP TABLE IF EXISTS `system_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_unit` (
  `id` int NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `connection_name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custom_code` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alias` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cpf_cnpj` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone2` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complement` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `district` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reference` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` char(1) COLLATE utf8mb4_general_ci DEFAULT 'Y',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sys_unit_name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_unit`
--

LOCK TABLES `system_unit` WRITE;
/*!40000 ALTER TABLE `system_unit` DISABLE KEYS */;
INSERT INTO `system_unit` VALUES (1,'Unit A','unit_a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y','2025-02-22 18:51:14',NULL),(2,'Unit B','unit_b',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y','2025-02-22 18:51:14',NULL);
/*!40000 ALTER TABLE `system_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_user_group`
--

DROP TABLE IF EXISTS `system_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_user_group` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_group_group_idx` (`system_group_id`),
  KEY `sys_user_group_user_idx` (`system_user_id`),
  CONSTRAINT `system_user_group_ibfk_1` FOREIGN KEY (`system_user_id`) REFERENCES `system_users` (`id`),
  CONSTRAINT `system_user_group_ibfk_2` FOREIGN KEY (`system_group_id`) REFERENCES `system_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_user_group`
--

LOCK TABLES `system_user_group` WRITE;
/*!40000 ALTER TABLE `system_user_group` DISABLE KEYS */;
INSERT INTO `system_user_group` VALUES (1,1,1),(2,1,2),(3,1,3),(4,2,2);
/*!40000 ALTER TABLE `system_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_user_old_password`
--

DROP TABLE IF EXISTS `system_user_old_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_user_old_password` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_old_password_user_idx` (`system_user_id`),
  CONSTRAINT `system_user_old_password_ibfk_1` FOREIGN KEY (`system_user_id`) REFERENCES `system_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_user_old_password`
--

LOCK TABLES `system_user_old_password` WRITE;
/*!40000 ALTER TABLE `system_user_old_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_user_old_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_user_program`
--

DROP TABLE IF EXISTS `system_user_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_user_program` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_program_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_program_program_idx` (`system_program_id`),
  KEY `sys_user_program_user_idx` (`system_user_id`),
  CONSTRAINT `system_user_program_ibfk_1` FOREIGN KEY (`system_user_id`) REFERENCES `system_users` (`id`),
  CONSTRAINT `system_user_program_ibfk_2` FOREIGN KEY (`system_program_id`) REFERENCES `system_program` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_user_program`
--

LOCK TABLES `system_user_program` WRITE;
/*!40000 ALTER TABLE `system_user_program` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_user_program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_user_role`
--

DROP TABLE IF EXISTS `system_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_user_role` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_role_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_role_user_idx` (`system_user_id`),
  KEY `sys_user_role_role_idx` (`system_role_id`),
  CONSTRAINT `system_user_role_ibfk_1` FOREIGN KEY (`system_user_id`) REFERENCES `system_users` (`id`),
  CONSTRAINT `system_user_role_ibfk_2` FOREIGN KEY (`system_role_id`) REFERENCES `system_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_user_role`
--

LOCK TABLES `system_user_role` WRITE;
/*!40000 ALTER TABLE `system_user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_user_unit`
--

DROP TABLE IF EXISTS `system_user_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_user_unit` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `system_unit_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_unit_user_idx` (`system_user_id`),
  KEY `sys_user_unit_unit_idx` (`system_unit_id`),
  CONSTRAINT `system_user_unit_ibfk_1` FOREIGN KEY (`system_user_id`) REFERENCES `system_users` (`id`),
  CONSTRAINT `system_user_unit_ibfk_2` FOREIGN KEY (`system_unit_id`) REFERENCES `system_unit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_user_unit`
--

LOCK TABLES `system_user_unit` WRITE;
/*!40000 ALTER TABLE `system_user_unit` DISABLE KEYS */;
INSERT INTO `system_user_unit` VALUES (1,1,1),(2,1,2),(3,2,1),(4,2,2);
/*!40000 ALTER TABLE `system_user_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_users`
--

DROP TABLE IF EXISTS `system_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_users` (
  `id` int NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `accepted_term_policy` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `function_name` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_general_ci,
  `accepted_term_policy_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `accepted_term_policy_data` text COLLATE utf8mb4_general_ci,
  `frontpage_id` int DEFAULT NULL,
  `system_unit_id` int DEFAULT NULL,
  `active` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custom_code` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_secret` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_user_program_idx` (`frontpage_id`),
  KEY `sys_users_name_idx` (`name`),
  CONSTRAINT `system_users_ibfk_1` FOREIGN KEY (`frontpage_id`) REFERENCES `system_program` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_users`
--

LOCK TABLES `system_users` WRITE;
/*!40000 ALTER TABLE `system_users` DISABLE KEYS */;
INSERT INTO `system_users` VALUES (1,'Administrator','admin','$2y$10$xuR3XEc3J6tpv7myC9gPj.Ab5GacSeHSZoYUTYtOg.cEc22G.iBwa','admin@admin.net','Y','+123 456 789','Admin Street, 123','Administrator','I\'m the administrator',NULL,NULL,30,NULL,'Y',NULL,NULL),(2,'User','user','$2y$10$MUYN29LOSHrCSGhrzvYG8O/PtAjbWvCubaUSTJGhVTJhm69WNFJs.','user@user.net','Y','+123 456 789','User Street, 123','End user','I\'m the end user',NULL,NULL,30,NULL,'Y',NULL,NULL);
/*!40000 ALTER TABLE `system_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_wiki_page`
--

DROP TABLE IF EXISTS `system_wiki_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_wiki_page` (
  `id` int NOT NULL,
  `system_user_id` int DEFAULT NULL,
  `created_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `active` char(1) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y',
  `searchable` char(1) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y',
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_wiki_page_user_idx` (`system_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_wiki_page`
--

LOCK TABLES `system_wiki_page` WRITE;
/*!40000 ALTER TABLE `system_wiki_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_wiki_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_wiki_share_group`
--

DROP TABLE IF EXISTS `system_wiki_share_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_wiki_share_group` (
  `id` int NOT NULL,
  `system_group_id` int DEFAULT NULL,
  `system_wiki_page_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_wiki_share_group_group_idx` (`system_group_id`),
  KEY `sys_wiki_share_group_page_idx` (`system_wiki_page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_wiki_share_group`
--

LOCK TABLES `system_wiki_share_group` WRITE;
/*!40000 ALTER TABLE `system_wiki_share_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_wiki_share_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_wiki_tag`
--

DROP TABLE IF EXISTS `system_wiki_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_wiki_tag` (
  `id` int NOT NULL,
  `system_wiki_page_id` int NOT NULL,
  `tag` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_wiki_tag_page_idx` (`system_wiki_page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_wiki_tag`
--

LOCK TABLES `system_wiki_tag` WRITE;
/*!40000 ALTER TABLE `system_wiki_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_wiki_tag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-08 16:16:25
