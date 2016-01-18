-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2016 at 08:59 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fixit`
--

-- --------------------------------------------------------

--
-- Table structure for table `claim_notes`
--

CREATE TABLE IF NOT EXISTS `claim_notes` (
  `notes_id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) NOT NULL DEFAULT '0',
  `notes_name` text,
  `notes_desc` text,
  `notes_content` text,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`notes_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `claim_notes`
--

INSERT INTO `claim_notes` (`notes_id`, `claim_id`, `notes_name`, `notes_desc`, `notes_content`, `is_deleted`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, 1, NULL, NULL, 'Notes 1', 1, '2016-01-12 20:00:13', 1, '2016-01-12 20:00:13', 1),
(2, 1, NULL, NULL, 'Notes 2', 1, '2016-01-12 20:00:22', 1, '2016-01-12 20:00:22', 1),
(3, 1, NULL, NULL, 'dxcvcxv', 0, '2016-01-12 20:44:39', 1, '2016-01-12 20:44:39', 1),
(4, 2, NULL, NULL, 'zxcxzc', 0, '2016-01-12 20:46:22', 1, '2016-01-12 20:46:22', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
