-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.1.72-community - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             8.1.0.4545
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for reef
CREATE DATABASE IF NOT EXISTS `reef` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `reef`;


-- Dumping structure for table reef.user_table
CREATE TABLE IF NOT EXISTS `user_table` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table reef.user_table: ~3 rows (approximately)
/*!40000 ALTER TABLE `user_table` DISABLE KEYS */;
INSERT INTO `user_table` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `active`) VALUES
	(1, 'Sachin', 'Kantipudi', 'sachk333@gmail.com', 'root', 'e2fc714c4727ee9395f324cd2e7f331f', 1),
	(2, 'Sasha', 'Medlen', 'sasha@reef.org', 'SashaMedlen', '0d2ac217e8e658ab2059e31bc0beac93', 1),
	(3, 'Christy', 'Semmens', 'christy@reef.org', 'christy', '7e85cacc1ddbb6f92e2c477c7e2f1bac', 1);
/*!40000 ALTER TABLE `user_table` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
