-- MySQL dump 10.9
--
-- Host: 127.0.0.1    Database: TEST_user_directory
-- ------------------------------------------------------
-- Server version	5.0.67-community-nt

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `profileID` int(11) NOT NULL auto_increment,
  `userID` int(11) default NULL,
  `firstName` varchar(254) NOT NULL,
  `lastName` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  PRIMARY KEY  (`profileID`),
  UNIQUE KEY `userID` (`userID`),
  KEY `lastName` (`lastName`),
  KEY `email` (`email`),
  CONSTRAINT `Profiles_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,1,'Theodore','Smith','theodore@xmule.ws'),(2,2,'Mark','Jenssen','mark@givemehope.co.uk'),(3,3,'Daniel','Jackson','djackson@sgc.mil'),(4,4,'Vala','Mal Doran','vmaldoran@sgc.mil'),(5,5,'Jack','O\'Neill','joneill@sgc.mil'),(6,6,'Harold','Landry','hlandry@sgc.mil'),(7,7,'George','Hammond','ghammond@sgc.mil'),(8,8,'Randolf','McKay','rmckay@sgc.mil'),(9,9,'Samantha','Carter','scarter@sgc.mil'),(10,10,'Teal\'C','Jaffa','tealc@sgc.mil'),(11,11,'Elizabeth','Wier','ewier@sgc.mil'),(12,12,'Jonas','Quinn','jquinn@sgc.mil'),(13,13,'Maria','Ortiz','mmmmm@mmmmm.mmm');
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` int(11) NOT NULL auto_increment,
  `username` varchar(254) default NULL,
  `password` varchar(254) default NULL,
  PRIMARY KEY  (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'tsmith','*F7674CF5953FE36111DCF3152E8CC2C9E65A0009'),(2,'mjenssen','*812D768B08A7169CBD3B87BDDCE1CD1D59C02EAC'),(3,'djackson','*F8BB58E0F0C5958F19CB195F3BB56FE89A183A56'),(4,'vmaldoran','*6DFA279223B34735058F4AB170C59DE277655179'),(5,'joneill','*3B1CC4137B6A4B2A6CD47B89733FB9B830EDC874'),(6,'hlandry','*4AE33DBE6D2770E09F9D70D4D73448DF98CBE65E'),(7,'ghammond','*FEAA459370EABC8A414427AC431A214C58335C7F'),(8,'rmckay','*6AC949B5EEDA5B27442069A69C4E45800734CACC'),(9,'scarter','*A5FE0CCD4FC2CA2C40060FAAFC95DBA71A4F2BC7'),(10,'tealc','*15344AC32AA44AB94F4C54E0721159FE91237F8B'),(11,'ewier','*8E8E04E59F17473DF440F060C66C30443D886BAF'),(12,'jquinn','*605B6E9FFF16CDCA202FD024440450D503964FAE'),(13,'maortiz','*F7674CF5953FE36111DCF3152E8CC2C9E65A0009');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vw_userinfo`
--

DROP TABLE IF EXISTS `vw_userinfo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_userinfo` AS select `p`.`userID` AS `userID`,`users`.`username` AS `username`,`p`.`firstName` AS `firstName`,`p`.`lastName` AS `lastName`,`p`.`email` AS `email` from (`profiles` `p` join `users` on((`p`.`userID` = `users`.`userID`)));

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

