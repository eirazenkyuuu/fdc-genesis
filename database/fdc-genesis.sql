-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2024 at 02:47 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fdc-genesis`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `content`, `date`) VALUES
(6, 3, 4, 'fasefasfas', '2024-04-14 14:24:01'),
(7, 3, 5, 'fadfasdf', '2024-04-14 14:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `hobby` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `joined_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `gender`, `birthdate`, `hobby`, `img_name`, `last_login`, `joined_date`) VALUES
(3, 'Eirazen Troy', 'asterdaeira@gmail.com', '1dad2ebaaf68468105b6af9ab7ad15033a33a462', 'm', '1996-11-21', 'Aaweaew', '320240413165247.jpg', '2024-04-14 21:06:50', '2024-04-12 19:43:18'),
(4, 'Throy Towercamp', 'tgenesistroy@gmail.com', '1dad2ebaaf68468105b6af9ab7ad15033a33a462', 'm', '1996-11-21', 'Haefafasfasf', '420240413165857.jpg', '2024-04-14 22:25:37', '2024-04-13 16:57:57'),
(5, 'Troy Tower', 'troytower112196@gmail.com', '1dad2ebaaf68468105b6af9ab7ad15033a33a462', NULL, NULL, NULL, NULL, '2024-04-14 15:38:47', '2024-04-14 07:38:44');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
