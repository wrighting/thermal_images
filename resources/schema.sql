-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: image_data
-- ------------------------------------------------------
-- Server version	5.5.31-0+wheezy1

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
-- Table structure for table `fileInfo`
--

DROP TABLE IF EXISTS `fileInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fileInfo` (
  `idfileInfo` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `thumbnailLink` varchar(255) DEFAULT NULL,
  `webContentLink` varchar(255) DEFAULT NULL,
  `googleDriveId` varchar(45) DEFAULT NULL,
  `lastModified` varchar(45) DEFAULT NULL,
  `dateTaken` datetime DEFAULT NULL,
  `parentId` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idfileInfo`),
  UNIQUE KEY `fu` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=2423 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houseInfo`
--

DROP TABLE IF EXISTS `houseInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houseInfo` (
  `idhouseInfo` int(11) NOT NULL AUTO_INCREMENT,
  `village` varchar(45) DEFAULT NULL,
  `street` varchar(45) DEFAULT NULL,
  `houseNum` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idhouseInfo`),
  UNIQUE KEY `hu` (`village`,`street`,`houseNum`)
) ENGINE=InnoDB AUTO_INCREMENT=185463 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photoInfo`
--

DROP TABLE IF EXISTS `photoInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photoInfo` (
  `idphotoInfo` int(11) NOT NULL AUTO_INCREMENT,
  `idhouseInfo` int(11) DEFAULT NULL,
  `idsurveyInfo` int(11) DEFAULT NULL,
  `photoId` int(4) unsigned zerofill DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`idphotoInfo`),
  KEY `fk_photoInfo_1` (`idhouseInfo`),
  KEY `fk_photoInfo_2` (`idsurveyInfo`),
  CONSTRAINT `fk_photoInfo_1` FOREIGN KEY (`idhouseInfo`) REFERENCES `houseInfo` (`idhouseInfo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_photoInfo_2` FOREIGN KEY (`idsurveyInfo`) REFERENCES `surveyInfo` (`idsurveyInfo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=167127 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surveyInfo`
--

DROP TABLE IF EXISTS `surveyInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surveyInfo` (
  `idsurveyInfo` int(11) NOT NULL AUTO_INCREMENT,
  `dateOfSurvey` date DEFAULT NULL,
  `surveyTeam` varchar(45) DEFAULT NULL,
  `weather` varchar(45) DEFAULT NULL,
  `airTemp` int(11) DEFAULT NULL,
  `foilTemp` int(11) DEFAULT NULL,
  PRIMARY KEY (`idsurveyInfo`),
  UNIQUE KEY `su` (`dateOfSurvey`,`surveyTeam`)
) ENGINE=InnoDB AUTO_INCREMENT=154016 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-12 16:49:28
