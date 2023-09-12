-- MySQL dump 10.19  Distrib 10.3.36-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 34.23.143.201    Database: aulas-steam-regional-simat
-- ------------------------------------------------------
-- Server version	5.7.40-google-log

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
-- Table structure for table `dtops`
--

DROP TABLE IF EXISTS `dtops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dtops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `codigo_dane` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_dane` (`codigo_dane`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dtops`
--

LOCK TABLES `dtops` WRITE;
/*!40000 ALTER TABLE `dtops` DISABLE KEYS */;
/*!40000 ALTER TABLE `dtops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollment_years`
--

DROP TABLE IF EXISTS `enrollment_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enrollment_years` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `year_UNIQUE` (`year`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollment_years`
--

LOCK TABLES `enrollment_years` WRITE;
/*!40000 ALTER TABLE `enrollment_years` DISABLE KEYS */;
INSERT INTO `enrollment_years` VALUES (1,2022,'año 2022','2022-11-10 21:25:17','2022-11-10 21:25:17'),(2,2023,'año 2023','2023-03-10 17:03:14','2023-03-10 17:03:14');
/*!40000 ALTER TABLE `enrollment_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
INSERT INTO `grades` VALUES (10,'Transicion'),(11,'Primero'),(12,'Segundo'),(13,'Tercero'),(14,'Cuarto'),(15,'Quinto'),(16,'Sexto'),(17,'Septimo'),(18,'Octavo'),(19,'Noveno'),(20,'Decimo'),(21,'Once');
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'1'),(2,'101'),(3,'201'),(4,'301'),(5,'401'),(6,'501'),(7,'601'),(8,'701'),(9,'801'),(10,'901'),(11,'1001'),(12,'1101');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `headquarters`
--

DROP TABLE IF EXISTS `headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `headquarters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `town_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `codigo_dane` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_dane` (`codigo_dane`),
  KEY `fk_headquarters_town` (`town_id`),
  KEY `fk_headquarters_institution` (`institution_id`),
  CONSTRAINT `fk_headquarters_institution` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_headquarters_town` FOREIGN KEY (`town_id`) REFERENCES `towns` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `headquarters`
--

LOCK TABLES `headquarters` WRITE;
/*!40000 ALTER TABLE `headquarters` DISABLE KEYS */;
/*!40000 ALTER TABLE `headquarters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `headquarters_has_grade_has_group`
--

DROP TABLE IF EXISTS `headquarters_has_grade_has_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `headquarters_has_grade_has_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headquarters_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_all` (`headquarters_id`,`grade_id`,`group_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_grade_has_group_grade1_idx` (`grade_id`),
  KEY `fk_grade_has_group_headquarters1_idx` (`headquarters_id`),
  KEY `fk_grade_has_group_group1_idx` (`group_id`) USING BTREE,
  CONSTRAINT `fk_grade_has_group_grade1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grade_has_group_group1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grade_has_group_headquarters1` FOREIGN KEY (`headquarters_id`) REFERENCES `headquarters` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `headquarters_has_grade_has_group`
--

LOCK TABLES `headquarters_has_grade_has_group` WRITE;
/*!40000 ALTER TABLE `headquarters_has_grade_has_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `headquarters_has_grade_has_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `headquarters_has_grade_has_group_has_user`
--

DROP TABLE IF EXISTS `headquarters_has_grade_has_group_has_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `headquarters_has_grade_has_group_has_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `headquarters_has_grade_has_group_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id_UNIQUE` (`student_id`),
  KEY `fk_headquarters_has_grade_has_group_has_simat_headquarter_idx` (`headquarters_has_grade_has_group_id`),
  CONSTRAINT `fk_headquarters_has_grade_has_group_has_simat_headquarters` FOREIGN KEY (`headquarters_has_grade_has_group_id`) REFERENCES `headquarters_has_grade_has_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_headquarters_has_grade_has_group_has_simat_users` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1728 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `headquarters_has_grade_has_group_has_user`
--

LOCK TABLES `headquarters_has_grade_has_group_has_user` WRITE;
/*!40000 ALTER TABLE `headquarters_has_grade_has_group_has_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `headquarters_has_grade_has_group_has_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ind_category`
--

DROP TABLE IF EXISTS `ind_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ind_category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ind_category`
--

LOCK TABLES `ind_category` WRITE;
/*!40000 ALTER TABLE `ind_category` DISABLE KEYS */;
INSERT INTO `ind_category` VALUES (1,'Indicadores personales'),(2,'Indicadores de notas'),(3,'Indicador de desercion'),(4,'Indicador de instituciones'),(5,'Indicador de actividades');
/*!40000 ALTER TABLE `ind_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ind_types`
--

DROP TABLE IF EXISTS `ind_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ind_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ind_types`
--

LOCK TABLES `ind_types` WRITE;
/*!40000 ALTER TABLE `ind_types` DISABLE KEYS */;
INSERT INTO `ind_types` VALUES (1,'percentage'),(2,'number'),(3,'date');
/*!40000 ALTER TABLE `ind_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indicators`
--

DROP TABLE IF EXISTS `indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `indicators` (
  `id` int(11) NOT NULL,
  `ind_type_id` int(11) NOT NULL,
  `und_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `operator_id` int(20) NOT NULL,
  `ind_category_id` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_indicators_ind_types` (`ind_type_id`),
  KEY `fk_indicators_und` (`und_id`),
  KEY `fk_indicators_ind_category_idx` (`ind_category_id`),
  KEY `fk_indicators_operators_idx` (`operator_id`),
  CONSTRAINT `fk_indicators_ind_category` FOREIGN KEY (`ind_category_id`) REFERENCES `ind_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_indicators_ind_types` FOREIGN KEY (`ind_type_id`) REFERENCES `ind_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_indicators_operators` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_indicators_und` FOREIGN KEY (`und_id`) REFERENCES `unds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indicators`
--

LOCK TABLES `indicators` WRITE;
/*!40000 ALTER TABLE `indicators` DISABLE KEYS */;
INSERT INTO `indicators` VALUES (2,3,4,'last_signin','Cantidad de sesiones el último mes','Cuantas personas han ingresado el último mes',4,3),(5,2,3,'grade_point_average','Promedio de notas ','Promedio de notas de un estudiante en un curso',2,2),(6,2,5,'number_of_courses','Cantidad de cursos','Indica la cantidad de cursos por institución',3,4),(9,1,1,'global_c_progress','Progreso del curso ','Progreso de todos los estudiantes en el curso',3,5),(10,2,5,'quantity_students','Cantidad de estudiantes','Cantidad de estudiantes en la institución',3,4),(12,2,5,'students_by_gender','Cantidad de estudiantes por género','Cantidad de estudiantes por género',3,1),(13,2,5,'average_student_age','Edad promedio del estudiante','Edad promedio de los estudiantes',2,1),(14,2,5,'ethnic_students','Cantidad de estudiantes pertenecientes a una etnia','Cantidad de estudiantes pertenecientes a una etnia',3,1),(15,2,5,'students_disability','Cantidad de estudiantes con discapacidad','Cantidad de estudiantes con alguna discapacidad',3,1);
/*!40000 ALTER TABLE `indicators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institutions`
--

DROP TABLE IF EXISTS `institutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institutions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `codigo_dane` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_dane` (`codigo_dane`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institutions`
--

LOCK TABLES `institutions` WRITE;
/*!40000 ALTER TABLE `institutions` DISABLE KEYS */;
/*!40000 ALTER TABLE `institutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operators`
--

DROP TABLE IF EXISTS `operators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators` (
  `id` int(11) NOT NULL,
  `operator` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operators`
--

LOCK TABLES `operators` WRITE;
/*!40000 ALTER TABLE `operators` DISABLE KEYS */;
INSERT INTO `operators` VALUES (1,'frecuency_number_percentage'),(2,'grade_point_average'),(3,'count_students'),(4,'dates');
/*!40000 ALTER TABLE `operators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `platform`
--

DROP TABLE IF EXISTS `platform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform` (
  `id_platform` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_platform`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform`
--

LOCK TABLES `platform` WRITE;
/*!40000 ALTER TABLE `platform` DISABLE KEYS */;
/*!40000 ALTER TABLE `platform` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `platform_indicators`
--

DROP TABLE IF EXISTS `platform_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_indicators` (
  `platform_id_platform` int(11) NOT NULL,
  `indicators_id` int(11) NOT NULL,
  `calc_indicator` varchar(155) NOT NULL,
  `institutions_id` int(11) NOT NULL,
  PRIMARY KEY (`platform_id_platform`,`indicators_id`,`institutions_id`),
  KEY `fk_platform_has_indicators_indicators1_idx` (`indicators_id`),
  KEY `fk_platform_has_indicators_platform1_idx` (`platform_id_platform`),
  KEY `fk_platform_indicators_institutions1_idx` (`institutions_id`),
  CONSTRAINT `fk_platform_has_indicators_indicators1` FOREIGN KEY (`indicators_id`) REFERENCES `indicators` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_platform_has_indicators_platform1` FOREIGN KEY (`platform_id_platform`) REFERENCES `platform` (`id_platform`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_platform_indicators_institutions1` FOREIGN KEY (`institutions_id`) REFERENCES `institutions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_indicators`
--

LOCK TABLES `platform_indicators` WRITE;
/*!40000 ALTER TABLE `platform_indicators` DISABLE KEYS */;
/*!40000 ALTER TABLE `platform_indicators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simat`
--

DROP TABLE IF EXISTS `simat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_information` int(11) NOT NULL,
  `type_document` int(11) NOT NULL,
  `number_document` varchar(45) NOT NULL,
  `expedition_department` varchar(45) NOT NULL,
  `municipal_expedition` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `second_surname` varchar(45) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `second_name` varchar(45) DEFAULT NULL,
  `residence_adress` varchar(250) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `residence_department` varchar(45) NOT NULL,
  `residence_municipality` varchar(45) NOT NULL,
  `stratum` int(11) NOT NULL,
  `sisben` double DEFAULT NULL,
  `birth_date` date NOT NULL,
  `final_date` date DEFAULT NULL,
  `age` int(11) NOT NULL,
  `birth_department` varchar(45) NOT NULL,
  `birth_municipality` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `population_victim_conflict` varchar(45) NOT NULL,
  `department_expedition` varchar(45) DEFAULT NULL,
  `municipality_expedition` varchar(45) DEFAULT NULL,
  `from_private_sector` char(1) NOT NULL,
  `from_other_municipality` char(1) NOT NULL,
  `disability` varchar(150) DEFAULT NULL,
  `exeptional_capabilities` varchar(150) NOT NULL,
  `ethnicity` varchar(150) DEFAULT NULL,
  `precucordos` varchar(45) NOT NULL,
  `journal` varchar(45) NOT NULL,
  `characterization` varchar(45) NOT NULL,
  `specialty` varchar(45) NOT NULL,
  `methodology` varchar(45) NOT NULL,
  `enrolled_enrollment` varchar(45) NOT NULL,
  `is_repeat` char(1) NOT NULL,
  `is_new` char(1) NOT NULL,
  `academic_situation_above` varchar(250) NOT NULL,
  `fountain_resources` varchar(150) NOT NULL,
  `student_area` varchar(45) NOT NULL,
  `head_family` char(1) NOT NULL,
  `ben_mad_flia` char(1) NOT NULL,
  `ben_vet_fp` char(1) NOT NULL,
  `ben_her_birth` char(1) NOT NULL,
  `admitted_code` int(11) DEFAULT NULL,
  `valuation_code_1` int(11) DEFAULT NULL,
  `valuation_code_2` int(11) DEFAULT NULL,
  `number_agreement` int(11) DEFAULT NULL,
  `per_id` int(11) NOT NULL,
  `special_academic_support` varchar(45) DEFAULT NULL,
  `criminal_responsibility` varchar(45) DEFAULT NULL,
  `country_origin` varchar(150) DEFAULT NULL,
  `specific_disorders` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1728 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simat`
--

LOCK TABLES `simat` WRITE;
/*!40000 ALTER TABLE `simat` DISABLE KEYS */;
/*!40000 ALTER TABLE `simat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_indicators`
--

DROP TABLE IF EXISTS `student_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_indicators` (
  `student_id` int(11) NOT NULL,
  `indicator_id` int(11) NOT NULL,
  `calc_indicator` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_indicators`
--

LOCK TABLES `student_indicators` WRITE;
/*!40000 ALTER TABLE `student_indicators` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_indicators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_year_id` int(10) unsigned NOT NULL,
  `simat_id` int(11) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `keycloak_sub_id` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_year` (`usuario`,`enrollment_year_id`),
  KEY `fk_users_simat_idx` (`simat_id`),
  KEY `fk_users_enrollment_year_idx` (`enrollment_year_id`),
  CONSTRAINT `fk_users_enrollment_year` FOREIGN KEY (`enrollment_year_id`) REFERENCES `enrollment_years` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_simat` FOREIGN KEY (`simat_id`) REFERENCES `simat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1728 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `towns`
--

DROP TABLE IF EXISTS `towns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `dtop_id` int(11) NOT NULL,
  `codigo_dane` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_dane` (`codigo_dane`),
  KEY `fk_town_dtop` (`dtop_id`),
  CONSTRAINT `fk_town_dtop` FOREIGN KEY (`dtop_id`) REFERENCES `dtops` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `towns`
--

LOCK TABLES `towns` WRITE;
/*!40000 ALTER TABLE `towns` DISABLE KEYS */;
/*!40000 ALTER TABLE `towns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unds`
--

DROP TABLE IF EXISTS `unds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unds` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unds`
--

LOCK TABLES `unds` WRITE;
/*!40000 ALTER TABLE `unds` DISABLE KEYS */;
INSERT INTO `unds` VALUES (1,'percentage'),(2,'hour'),(3,'qualification'),(4,'time'),(5,'quantity');
/*!40000 ALTER TABLE `unds` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-07 17:01:26
