-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2016 at 05:10 AM
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
  `claim_details` varchar(45) DEFAULT NULL,
  `claim_admin` varchar(45) DEFAULT NULL,
  `claim_adjuster_id` int(11) DEFAULT NULL,
  `claim_start_date` datetime DEFAULT NULL,
  `claim_end_date` datetime DEFAULT NULL,
  `claim_complete_date` datetime DEFAULT NULL,
  `claim_close_date` datetime DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `create_id` varchar(45) DEFAULT NULL,
  `last_updated_id` varchar(45) DEFAULT NULL,
  `Property_damage_reserve` int(11) DEFAULT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
