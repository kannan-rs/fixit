-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2016 at 05:29 AM
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
-- Table structure for table `claim`
--

CREATE TABLE IF NOT EXISTS `claim` (
  `claim_id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_customer_id` int(11) NOT NULL,
  `claim_type` varchar(10) DEFAULT NULL,
  `linked_to_disaster` varchar(3) DEFAULT NULL,
  `natational_disaster` varchar(45) DEFAULT NULL,
  `national_disaster_type` varchar(15) DEFAULT NULL,
  `insurer_name` varchar(45) DEFAULT NULL,
  `insurer_id` varchar(15) DEFAULT NULL,
  `property_name` varchar(45) DEFAULT NULL,
  `addr1` text,
  `addr2` text,
  `addr_city` text,
  `addr_state` varchar(10) DEFAULT NULL,
  `addr_country` varchar(10) DEFAULT NULL,
  `addr_pin` int(5) DEFAULT NULL,
  `customer_contact_no` varchar(15) DEFAULT NULL,
  `customer_email_id` varchar(50) DEFAULT NULL,
  `claim_number` varchar(50) NOT NULL,
  `claim_description` text NOT NULL,
  `claim_details` varchar(45) DEFAULT NULL,
  `claim_admin` varchar(45) DEFAULT NULL,
  `claim_adjuster_id` int(11) DEFAULT NULL,
  `claim_start_date` datetime DEFAULT NULL,
  `claim_end_date` datetime DEFAULT NULL,
  `claim_complete_date` datetime DEFAULT NULL,
  `claim_close_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `Property_damage_reserve` int(11) DEFAULT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `claim`
--

INSERT INTO `claim` (`claim_id`, `claim_customer_id`, `claim_type`, `linked_to_disaster`, `natational_disaster`, `national_disaster_type`, `insurer_name`, `insurer_id`, `property_name`, `addr1`, `addr2`, `addr_city`, `addr_state`, `addr_country`, `addr_pin`, `customer_contact_no`, `customer_email_id`, `claim_number`, `claim_description`, `claim_details`, `claim_admin`, `claim_adjuster_id`, `claim_start_date`, `claim_end_date`, `claim_complete_date`, `claim_close_date`, `is_deleted`, `created_on`, `updated_on`, `created_by`, `updated_by`, `Property_damage_reserve`) VALUES
(1, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '121', 'werwer', 'San Anselmo', 'CA', 'USA', 94960, 'dsfsdf', 'kannan2k6@gmail.com', '1111', 'fdsdfdsfds', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2016-01-10 09:21:44', '2016-01-10 09:21:44', 1, 1, NULL),
(2, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'add123', 'add222', 'San Bruno', 'CA', 'USA', 94066, '1234567890', 'kannan2k6@gmail.com', '1212', 'description description description description description description description description description description description ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2016-01-10 09:24:32', '2016-01-11 05:09:41', 1, 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
