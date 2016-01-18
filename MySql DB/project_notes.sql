-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2016 at 04:58 AM
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
-- Table structure for table `project_notes`
--

CREATE TABLE IF NOT EXISTS `project_notes` (
  `notes_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `notes_name` text,
  `notes_desc` text,
  `notes_content` text,
  `Project_notescol` varchar(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`notes_id`,`Project_notescol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `project_notes`
--

INSERT INTO `project_notes` (`notes_id`, `project_id`, `task_id`, `notes_name`, `notes_desc`, `notes_content`, `Project_notescol`, `deleted`, `created_date`, `created_by`, `updated_by`, `updated_date`) VALUES
(6, 4, 0, 'N6', NULL, 'Des N6', '', 1, '2015-04-27 18:23:34', 1, 1, '2015-04-27 18:23:34'),
(7, 5, 0, 'p2 N1', NULL, 'sdfdsfs', '', 0, '2015-04-27 19:54:36', 1, 1, '2015-04-27 19:54:36'),
(8, 4, 0, 'Note 7', NULL, 'Desc for Not 7', '', 0, '2015-04-28 03:36:45', 1, 1, '2015-04-28 03:36:45'),
(9, 4, 0, '111', NULL, 'sadsadad', '', 0, '2015-05-13 03:29:07', 1, 1, '2015-05-13 03:29:07'),
(10, 4, 0, '0', NULL, 'Notes 1', '', 0, '2015-05-16 07:55:51', 1, 1, '2015-05-16 07:55:51'),
(13, 4, 0, '0', NULL, 'zfsdfdsfdsf', '', 0, '2015-05-18 20:21:09', 1, 1, '2015-05-18 20:21:09'),
(14, 4, 0, '0', NULL, '1qqqqqq', '', 0, '2015-05-18 20:27:28', 1, 1, '2015-05-18 20:27:28'),
(15, 4, 0, '0', NULL, '2 new', '', 0, '2015-05-18 20:28:19', 1, 1, '2015-05-18 20:28:19'),
(22, 4, 0, '0', NULL, '444444', '', 0, '2015-05-18 20:43:51', 1, 1, '2015-05-18 20:43:51'),
(23, 4, 0, '0', NULL, '5555', '', 0, '2015-05-18 20:43:55', 1, 1, '2015-05-18 20:43:55'),
(24, 4, 0, '0', NULL, '66', '', 0, '2015-05-18 20:43:59', 1, 1, '2015-05-18 20:43:59'),
(25, 4, 0, '0', NULL, '77', '', 0, '2015-05-18 20:44:03', 1, 1, '2015-05-18 20:44:03'),
(26, 4, 0, '0', NULL, 'safsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad fsafsf fs df da fadf ad fa df ad f ad fa dsf ad f da fad f ad fda f ad f adf d f df ad fa dsf ad fa df ad f ad fa df ad fa df ad f ad fad f ad fad fa dsf ads fa dsf ad f', '', 0, '2015-05-18 20:44:19', 1, 1, '2015-05-18 20:44:19'),
(30, 4, 0, '0', NULL, 'popup', '', 0, '2015-05-29 16:04:18', 1, 1, '2015-05-29 16:04:18'),
(31, 4, 0, '0', NULL, 'popup 2 sdfsdfds', '', 0, '2015-05-30 05:15:43', 1, 1, '2015-05-30 05:15:43'),
(32, 4, 0, '0', NULL, 'p3', '', 0, '2015-05-30 05:17:14', 1, 1, '2015-05-30 05:17:14'),
(33, 4, 0, '0', NULL, 'p4', '', 0, '2015-05-30 05:17:22', 1, 1, '2015-05-30 05:17:22'),
(34, 4, 2, '0', NULL, 'T2 N1', '', 0, '2015-05-30 05:17:37', 1, 1, '2015-05-30 05:17:37'),
(35, 4, 2, '0', NULL, 'T2 N2', '', 0, '2015-05-30 05:17:51', 1, 1, '2015-05-30 05:17:51'),
(36, 4, 2, '0', NULL, 'T2 N3', '', 0, '2015-05-31 09:29:30', 1, 1, '2015-05-31 09:29:30'),
(38, 4, 19, '0', NULL, '123', '', 0, '2015-05-31 09:55:17', 1, 1, '2015-05-31 09:55:17'),
(39, 4, 19, '0', NULL, '222', '', 0, '2015-05-31 09:55:22', 1, 1, '2015-05-31 09:55:22'),
(40, 4, 0, '0', NULL, '1111', '', 0, '2015-09-20 19:21:25', 1, 1, '2015-09-20 19:21:25'),
(41, 4, 0, '0', NULL, '1111', '', 0, '2015-09-20 19:22:05', 1, 1, '2015-09-20 19:22:05'),
(42, 4, 0, '0', NULL, '1111', '', 1, '2015-09-20 19:22:48', 1, 1, '2015-09-20 19:22:48'),
(43, 4, 0, '0', NULL, '1111', '', 1, '2015-09-20 19:23:15', 1, 1, '2015-09-20 19:23:15'),
(44, 4, 0, '0', NULL, '1111', '', 1, '2015-09-20 19:23:35', 1, 1, '2015-09-20 19:23:35'),
(45, 4, 11, '0', NULL, 'sdfsdf', '', 1, '2015-09-20 19:24:05', 1, 1, '2015-09-20 19:24:05'),
(46, 4, 11, '0', NULL, 'sdfsdf', '', 1, '2015-09-20 19:26:03', 1, 1, '2015-09-20 19:26:03'),
(47, 4, 11, '0', NULL, 'dsfdsfdsgfd', '', 1, '2015-09-20 19:40:14', 1, 1, '2015-09-20 19:40:14'),
(48, 4, 0, '0', NULL, 'new 111', '', 0, '2015-09-20 20:57:39', 1, 1, '2015-09-20 20:57:39'),
(49, 4, 0, '0', NULL, 'new 222', '', 1, '2015-09-20 20:58:04', 1, 1, '2015-09-20 20:58:04'),
(50, 4, 0, '0', NULL, 'dfsdfsfs', '', 0, '2015-11-23 07:50:20', 1, 1, '2015-11-23 07:50:20'),
(51, 4, 0, '0', NULL, 'qqqqqqqqqqqqqqqqqqqqqqqq', '', 0, '2015-11-23 07:50:39', 1, 1, '2015-11-23 07:50:39'),
(52, 4, 0, '0', NULL, '1212121', '', 1, '2015-11-23 07:58:11', 1, 1, '2015-11-23 07:58:11'),
(53, 4, 0, '0', NULL, '11', '', 0, '2015-11-23 18:27:38', 1, 1, '2015-11-23 18:27:38');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
