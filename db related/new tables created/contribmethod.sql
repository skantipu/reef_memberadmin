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


-- Dumping structure for table reef.contribmethod
CREATE TABLE IF NOT EXISTS `contribmethod` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table reef.contribmethod: ~10 rows (approximately)
/*!40000 ALTER TABLE `contribmethod` DISABLE KEYS */;
INSERT INTO `contribmethod` (`id`, `name`) VALUES
	(0, 'Unknown'),
	(1, 'Cash'),
	(2, 'Check'),
	(3, 'Credit Card - Phone'),
	(5, 'Goods'),
	(6, 'In Kind Services'),
	(7, 'Stock'),
	(8, 'Credit Card Website'),
	(9, 'Corporate Matching'),
	(10, 'Credit Card By Mail');
/*!40000 ALTER TABLE `contribmethod` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
