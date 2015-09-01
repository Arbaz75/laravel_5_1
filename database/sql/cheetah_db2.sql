-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 01, 2015 at 01:49 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cheetah`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_auth`
--

CREATE TABLE IF NOT EXISTS `admin_auth` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `admin_auth`
--

INSERT INTO `admin_auth` (`admin_id`, `name`, `email_id`, `password`, `is_active`, `date_created`) VALUES
(4, 'arbaz', 'a@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, '2015-08-25 06:56:11'),
(5, 'vikky', 'v@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, '2015-08-25 07:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `member_id` int(11) NOT NULL,
  `distance` float NOT NULL,
  `start_time` datetime NOT NULL,
  `expected_end_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `intervals` int(11) NOT NULL,
  `goal_time` datetime NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `name`, `member_id`, `distance`, `start_time`, `expected_end_time`, `end_time`, `intervals`, `goal_time`, `last_updated`, `date_created`) VALUES
(1, 'running up', 1, 10.1, '2015-09-01 02:06:05', '2015-09-01 06:06:05', '2015-09-01 04:06:05', 4, '2015-09-01 06:06:05', '2015-09-01 08:18:35', '2015-09-01 01:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `interval_records`
--

CREATE TABLE IF NOT EXISTS `interval_records` (
  `interval_records_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`interval_records_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `email_id`, `password`, `city`, `is_active`, `last_activity`, `date_created`, `last_updated`) VALUES
(1, 'arbaz rizvi', 'arbaz.rizvi@sofmen.com', '$2y$10$5Ga8xj2blClZZRt70TIjiuJBNqjkJ8yJBdsPi9R3HZNXBOcKuKsXy', 'indore', 1, NULL, NULL, '2015-08-24 08:11:13'),
(6, 'sandeep', 'sandeep.patel@sofmen.com', '', 'indore', 1, NULL, NULL, '2015-08-25 13:42:30'),
(7, 'sandeep', 'sandeep.patel+1@sofmen.com', '', 'indore', 1, NULL, NULL, '2015-08-25 13:44:47'),
(8, 'paresh', 'paresh.chouhan@sofmen.com', '$2y$10$hJ6vxyVXcCBz8.8aEU81iexYKtA9grPyIreGcyn38dg8l30GHN7eq', 'punjab', 1, '2015-08-26 09:06:26', '2015-08-26 09:06:26', '2015-08-26 09:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `member_notification_setting`
--

CREATE TABLE IF NOT EXISTS `member_notification_setting` (
  `member_notification_setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `notification_type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_notification_setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_token`
--

CREATE TABLE IF NOT EXISTS `member_token` (
  `member_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `device_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_token_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `member_token`
--

INSERT INTO `member_token` (`member_token_id`, `member_id`, `token`, `device_type`, `last_updated`) VALUES
(1, 1, 'a8hg4vrP11yAkDyPviGeQuMS0BA4ajjz67TOaxatytBT5tyjrU', NULL, '2015-08-24 06:50:42'),
(2, 1, 'kVvrsvCYb0Gtr6AFLiES0cxEKS8JtQmJVcfnKXR9lvPVtQSIH4', NULL, '2015-08-31 09:52:09'),
(3, 1, 'X7IABT9ODSKl0jVbM9AkOeRCaRKzv9ron5wZTmnCN74Q8868zA', NULL, '2015-08-31 09:57:51'),
(4, 1, 'sZ7utS2M9xgAscaoq0MMCNPyTrnvVOzGxsO9jDli3ZI26fohC4', NULL, '2015-08-31 09:59:31'),
(5, 1, 'rqZxd683YhuihHD2k3YqAKRynlENvI7PKrLHPwOjlGmIm7LLp2', NULL, '2015-08-31 10:49:13'),
(6, 1, 'QVcYhYg5W66gG6DK8vgZ7QSYVt6rFpPS75zc4ICS0AfhrQNf1v', NULL, '2015-09-01 08:09:22'),
(7, 1, 'tJS890VhDlGIEmrVsj2rOTWYf0t7I0sah78iq5vy0m4BUKrfu6', NULL, '2015-09-01 08:09:33'),
(8, 1, 'KemIRwVyTlgQwu83nkUIXyVNz9nG3Fgc5Py0ZAPqGN2XyyT3kf', NULL, '2015-09-01 08:10:42'),
(9, 1, 'bGluxNGe5yPQacKmdVP3KgBIjuv9deRfWotCyk6S8VLja4UgbV', NULL, '2015-09-01 08:10:47');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
