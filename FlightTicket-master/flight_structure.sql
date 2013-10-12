-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2013 at 05:23 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flight`
--

-- --------------------------------------------------------

--
-- Table structure for table `airlinesinfo`
--

CREATE TABLE IF NOT EXISTS `airlinesinfo` (
  `company` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `airlinecode` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `startdrome` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `arrivedrome` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `starttime` time NOT NULL,
  `arrivetime` time NOT NULL,
  `mode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `airlinestop` int(10) NOT NULL,
  `week` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `startcity` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `arrivecity` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`airlinecode`,`mode`,`week`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `priceinfo`
--

CREATE TABLE IF NOT EXISTS `priceinfo` (
  `price` double NOT NULL,
  `airlinecode` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`airlinecode`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
