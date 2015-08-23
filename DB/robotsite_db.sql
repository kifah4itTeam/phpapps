-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2015 at 12:07 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `robotsite_db`
--
CREATE DATABASE IF NOT EXISTS `robotsite_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `robotsite_db`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `Category_Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`Category_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_Id`, `Category_name`) VALUES
(1, 'أخبار روبوت'),
(2, 'شركات رائدة'),
(3, 'شخصيات عالمية');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `Department_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Department_name` varchar(500) NOT NULL,
  PRIMARY KEY (`Department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `member_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `member_firstname` varchar(255) NOT NULL,
  `member_surname` varchar(255) NOT NULL,
  `member_email` varchar(500) NOT NULL,
  `member_pass` varchar(255) NOT NULL,
  `member_phone` varchar(255) NOT NULL,
  `departement_Id` varchar(500) NOT NULL,
  `member_year` varchar(300) NOT NULL,
  `Specialization_Id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  KEY `Specialization_Id` (`Specialization_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_firstname`, `member_surname`, `member_email`, `member_pass`, `member_phone`, `departement_Id`, `member_year`, `Specialization_Id`) VALUES
(1, 'nour', 'zetona', 'nour@yahoo.com', '', '', '', '', NULL),
(2, 'nour', 'zetona', 'nour@yahoo.com', '', '', '', '', NULL),
(16, 'nano', 'zetona', 'nano@outlook.com', '123456', '', '', '', NULL),
(17, 'adnan', 'kubah', 'adnan@hotmail.com', '234567', '093065931', '', '', NULL),
(18, 'sawsan', 'shalabi', 'lialno@outlook.com', '1234567890', '0933764434', '', '', NULL),
(19, 'hoseen', 'xetona', 'hossen@outlook.com', '12345678', '0994219510', '', '', NULL),
(20, '123', '123', '1242314@edrf.dfg', '12345', '123', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `post_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(255) NOT NULL,
  `post_text` text NOT NULL,
  `post_date` date NOT NULL,
  `post_img` varchar(1000) NOT NULL,
  `post_tags` varchar(500) NOT NULL,
  `Category_id` bigint(20) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `Category_id` (`Category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `post_title`, `post_text`, `post_date`, `post_img`, `post_tags`, `Category_id`) VALUES
(1, 'روبوت Asimo', 'روبوت أسيمو الجديد يخرج إلى النور روبوت أسيمو الجديد يخرج إلى النور روبوت أسيمو الجديد يخرج إلى النور روبوت أسيمو الجديد يخرج إلى النور روبوت أسيمو الجديد يخرج إلى النور', '2015-08-08', 'images/MiP_Robot-3.jpg', '', 1),
(3, 'شركات رائدة', 'شركة سامسونغ تعتبر من شركات الرائدة في علم الروبوت ومن أشهر الروبوتات التي صممتها عو روبوت ASIMO ويعتبر من أكثر الوربوتات المتطور في العصر الحالي', '2015-08-05', 'images/robots123.png', '', 2),
(4, 'بيلغيتس', 'بيلغيغس وهو من اشهر الشخصيات في العالم وهو مالك شركة microsoft', '2015-06-10', 'images/Bill_Gates_III_20080123_068.jpg', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `project_desc` text NOT NULL,
  `project_supervisor_name` varchar(255) NOT NULL,
  `project_sponsor_name` varchar(255) NOT NULL,
  `project_departement` varchar(1000) NOT NULL,
  `project_year` varchar(500) NOT NULL,
  `project_date` date NOT NULL,
  `project_end_date` date NOT NULL,
  `project_img` varchar(1000) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE IF NOT EXISTS `specialization` (
  `Spec_Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Spec_name` varchar(500) NOT NULL,
  `Department_Id` bigint(20) NOT NULL,
  PRIMARY KEY (`Spec_Id`),
  KEY `Department_Id` (`Department_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workshop`
--

CREATE TABLE IF NOT EXISTS `workshop` (
  `workshop_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `workshop_name` varchar(255) NOT NULL,
  `workshop_desc` text NOT NULL,
  `workshop_supervisor` varchar(255) NOT NULL,
  `workshop_date` date NOT NULL,
  `workshop_end_date` date NOT NULL,
  `workshop_img` varchar(1000) NOT NULL,
  `Tags` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`workshop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `workshop`
--

INSERT INTO `workshop` (`workshop_id`, `workshop_name`, `workshop_desc`, `workshop_supervisor`, `workshop_date`, `workshop_end_date`, `workshop_img`, `Tags`) VALUES
(1, 'روبوت c', 'سنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة cسنتعلم التحكم بالروبوت عن طريق لغة البرمجة c', 'مهند الخضري', '2015-09-01', '2015-10-01', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workshop_reg`
--

CREATE TABLE IF NOT EXISTS `workshop_reg` (
  `wrkshp_reg_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `workshop_id` bigint(20) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  PRIMARY KEY (`wrkshp_reg_id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `member_id` (`member_id`),
  KEY `workshop_id_2` (`workshop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`Specialization_Id`) REFERENCES `specialization` (`Spec_Id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`Category_id`) REFERENCES `category` (`Category_Id`) ON UPDATE CASCADE;

--
-- Constraints for table `specialization`
--
ALTER TABLE `specialization`
  ADD CONSTRAINT `specialization_ibfk_1` FOREIGN KEY (`Department_Id`) REFERENCES `department` (`Department_id`);

--
-- Constraints for table `workshop_reg`
--
ALTER TABLE `workshop_reg`
  ADD CONSTRAINT `foreign_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `foreign_wrkshop` FOREIGN KEY (`workshop_id`) REFERENCES `workshop` (`workshop_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
