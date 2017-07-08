-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2017 at 01:08 AM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agromarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bids`
--

DROP TABLE IF EXISTS `tbl_bids`;
CREATE TABLE IF NOT EXISTS `tbl_bids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_price` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_bids_id_uindex` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_bids`
--

INSERT INTO `tbl_bids` (`id`, `user_id`, `product_id`, `total_price`) VALUES
(1, 19, 1, '456');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(2000) NOT NULL,
  `location` varchar(2000) NOT NULL,
  `deadline` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` varchar(100) NOT NULL,
  `unit` varchar(500) NOT NULL,
  `baseprice` varchar(500) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `total_baseprice` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `image` (`image`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id`, `name`, `location`, `deadline`, `amount`, `unit`, `baseprice`, `user_id`, `image`, `description`, `category`, `total_baseprice`) VALUES
(1, 'à¦¯à¦•à§à¦¸à¦š', 'à¦¯à¦•à§à¦¸à¦š', '2018-04-27 10:55:00', 'à¦¯à¦•à§à¦¸à¦š', 'à¦¡à¦œà¦¨', 'à¦¯à¦•à§à¦¸à¦š', 20, '7feq2jdhzAY4WUuoQDdlvB7wF9H2w7TrMRaRHevSYynAOGKAXW5YBdmkuN2KPz6P.jpeg', 'à¦¸à¦¾à¦šà¦«à¦¸à¦¯', 'à¦¶à¦¸à§à¦¯', NULL),
(2, 'à¦¯à¦•à§à¦¸à¦š', 'à¦¯à¦•à§à¦¸à¦š', '2018-04-27 10:55:00', 'à¦¯à¦•à§à¦¸à¦š', 'à¦¡à¦œà¦¨', 'à¦¯à¦•à§à¦¸à¦š', 20, 'kSZFexGReTFuyOUaWDQXzcuubQgP0pbAKfGQDcKdnCPLblV6NHukpyt2Lfni4C8J.jpeg', 'à¦¸à¦¾à¦šà¦«à¦¸à¦¯', 'à¦¶à¦¸à§à¦¯', NULL),
(3, 'à¦«à¦˜', 'à¦«à¦˜', '2018-12-31 00:00:00', 'à¦«à¦˜', 'à¦¡à¦œà¦¨', 'à¦«à¦˜', 20, 'DdWcwpIRd5MO2JdhSkDY1j77mGgrdSa9rTqYnc76RJRieZIKmWjby2IOHgxm64R3.jpeg', 'à¦«à¦˜', 'à¦¶à¦¾à¦à¦•', NULL),
(4, 'à¦«à¦˜', 'à¦«à¦˜', '1899-12-31 00:00:00', 'à¦«à¦˜', 'à¦¡à¦œà¦¨', 'à¦«à¦˜', 20, 'kD4MPfkFig7yGe7MuhAbFZd6GsenvhmnCxOVYPjm2f5yNpPOInL0KKDtnDD3zDv6.jpeg', 'à¦«à¦˜', 'à¦¶à¦¾à¦à¦•', NULL),
(5, 'à¦«à¦˜', 'sdf', '1899-12-31 00:00:00', 'dsf', 'à¦ªà¦¿à¦¸', 'dsf', 20, 'zVQ3VpxYHEkT6vapSkxCdXp7RJf00u1RoAcMeRKmeE9HDOlo8GPwY6yHQq0ZYt81.jpeg', 'sdf', 'à¦¶à¦¾à¦à¦•', 'sdf'),
(6, 'à¦«à¦˜', 'sdf', '1899-12-31 00:00:00', 'dsf', 'à¦ªà¦¿à¦¸', 'dsf', 20, 'df9MLOEDK04UuiuPRDv5bvdWiSd4zdAJPuWCqanXcdssu7im9OumwN7Gr1M3gYAH.jpeg', 'sdf', 'à¦¶à¦¾à¦à¦•', 'sdf'),
(7, 'sdf', 'df', '1899-12-31 00:00:00', 'dfg', 'notselected', 'df', 19, 'PB7jsos82jdSSOtLPSQufXojX3JUkBXxbBETSuVceglaCn22SNzvl02ORW3EL7Hg.jpeg', 'df', 'à¦¶à¦¸à§à¦¯', 'dfg'),
(8, 'dfg', 'dfg', '2049-07-08 03:00:00', 'dfg', 'à¦ªà¦¿à¦¸', 'dfg', 19, 'PdIuessTO4QsVw0qoMvuzkrSj7qjWDx0jPnHLb2A9E6nNIGkzlm7NZe0kESFvLpm.jpeg', 'fdg', 'à¦¶à¦¾à¦à¦•', 'fdg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

DROP TABLE IF EXISTS `tbl_rating`;
CREATE TABLE IF NOT EXISTS `tbl_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rater_id` int(11) NOT NULL,
  `ratee_id` int(11) NOT NULL,
  `rating` double DEFAULT '2.5',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_rating`
--

INSERT INTO `tbl_rating` (`id`, `rater_id`, `ratee_id`, `rating`) VALUES
(1, 19, 20, 3.5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('approved','pending') NOT NULL DEFAULT 'pending',
  `phone` varchar(30) DEFAULT NULL,
  `verification` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `name`, `pass`, `email`, `status`, `phone`, `verification`) VALUES
(20, 'à¦ªà§à¦°à¦¤à§€à¦¤à¦¿ à¦ªà§à¦°à¦®à¦¾', 'f283d88e9ec7552695ef47f10a7accc4', 'proteeti13@gmail.com', 'approved', 'à§¦à§§à§¨à§©à§ªà§«à§¬à§­à§®à§¯', 'qbhcG5f9S7D0lKw1VWvSY9GOjnEyj3ui'),
(19, 'asd', 'f283d88e9ec7552695ef47f10a7accc4', 'smfaisal1648@gmail.com', 'approved', 'asd', 'xEVuhhepD7ifN08EwiaF0OoUXVhtDtMW');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_visitor`
--

DROP TABLE IF EXISTS `tbl_visitor`;
CREATE TABLE IF NOT EXISTS `tbl_visitor` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ipv4` varchar(100) NOT NULL,
  `last_access` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visit_count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `visitor_ipv4_unique` (`ipv4`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_visitor`
--

INSERT INTO `tbl_visitor` (`id`, `ipv4`, `last_access`, `visit_count`) VALUES
(11, '127.0.0.1', '2017-04-23 18:07:54', 1),
(10, '::1', '2017-05-06 01:06:24', 132);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
