-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 25, 2024 at 05:09 PM
-- Server version: 8.3.0
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `picnic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` bigint NOT NULL AUTO_INCREMENT,
  `account_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_number` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shaba_number` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `blog_id` bigint NOT NULL AUTO_INCREMENT,
  `blog_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_date_time` date NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `type` tinyint NOT NULL DEFAULT '0',
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs_media`
--

DROP TABLE IF EXISTS `blogs_media`;
CREATE TABLE IF NOT EXISTS `blogs_media` (
  `blog_media_id` bigint NOT NULL AUTO_INCREMENT,
  `blog_media_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_id` bigint DEFAULT NULL,
  `type` tinyint NOT NULL,
  `mime_type` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
CREATE TABLE IF NOT EXISTS `features` (
  `feature_id` bigint NOT NULL AUTO_INCREMENT,
  `feature_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feature_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`feature_id`, `feature_guid`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(13, '5c5ef00425c929.46030186', 'شام', '', '2019-02-09 15:21:40', '2019-02-23 06:49:53', NULL),
(14, '5c5ef311b24846.34564150', 'حمل و نقل', '', '2019-02-09 15:34:41', '2019-02-23 06:55:10', NULL),
(12, '5c5eefe367c524.63489611', 'صبحانه', '', '2019-02-09 15:21:07', '2019-02-23 06:49:58', NULL),
(15, '5c70ee6d6481f5.98686849', 'پارکینگ', '', '2019-02-23 06:55:41', '2019-02-23 06:55:41', NULL),
(16, '5c70ee978ce946.42393042', 'عصرانه', '', '2019-02-23 06:56:23', '2019-02-23 06:56:23', NULL),
(17, '5c70eeae984d48.95855101', 'لباس غواصی', '', '2019-02-23 06:56:46', '2019-02-23 06:56:46', NULL),
(18, '5c70eec97127b6.13501870', 'پذیرایی در مسیر', '', '2019-02-23 06:57:13', '2019-02-23 07:08:11', NULL),
(19, '5c70eee1dabcf5.29759616', 'ورودی', '', '2019-02-23 06:57:37', '2019-02-23 06:57:37', NULL),
(20, '5c70eef793b932.61994354', 'لباس اضافی', '', '2019-02-23 06:57:59', '2019-02-23 06:57:59', NULL),
(21, '5c70ef099ffad1.55844400', 'دستکش', '', '2019-02-23 06:58:17', '2019-02-23 06:58:17', NULL),
(22, '5c70ef139398b0.80085134', 'زیرانداز', '', '2019-02-23 06:58:27', '2019-02-23 06:58:27', NULL),
(23, '5c70ef251cdd79.32448692', 'بیمه', '', '2019-02-23 06:58:45', '2019-02-23 06:58:45', NULL),
(24, '5c70ef47f0cb28.23863830', 'موسیقی', '', '2019-02-23 06:59:19', '2019-02-23 07:12:28', NULL),
(25, '5c70ef55dcd6b5.95786562', 'کلاه (Helmet)', '', '2019-02-23 06:59:33', '2019-02-23 07:03:48', NULL),
(26, '5c70ef5fbf9d81.99380735', 'جی پی اس', '', '2019-02-23 06:59:43', '2019-02-23 06:59:43', NULL),
(27, '5c70efe61a15c8.74356898', 'صندلی صعود (Harness)', 'وسیله ای است که صخره نورد با استفاده از آن از ضریب ایمنی بیشتری برخوردار می گردد', '2019-02-23 07:01:58', '2019-04-07 10:00:49', NULL),
(28, '5c70f014472f88.28638016', 'هشت فرود', 'هشت فرود در صخره نوردی وسیله ایست به شکل عدد ۸ انگلیسی که فقط در فرودها و بر اساس شکست طناب عمل می نماید', '2019-02-23 07:02:44', '2019-04-07 10:00:57', NULL),
(29, '5c70f03021bec4.33175937', 'کارابین (Carabin)', 'کارابین  وسیله ای فلزی و از جنس آلیاژ آلومینیوم می باشد که برای اتصالات ابزار صخره نوردی به کار می رود', '2019-02-23 07:03:12', '2019-04-07 10:01:19', NULL),
(30, '5c70f174269f11.08841919', 'باطری', '', '2019-02-23 07:08:36', '2019-02-23 07:08:36', NULL),
(31, '5c70f18f6ac3e5.43293064', 'محل اقامت', '', '2019-02-23 07:09:03', '2019-02-23 07:09:03', NULL),
(32, '5c70f1dbbddd23.17272231', 'چراغ', '', '2019-02-23 07:10:19', '2019-02-23 07:10:19', NULL),
(33, '5c70f20c3a90c4.59154394', 'راهنمای محلی', '', '2019-02-23 07:11:08', '2019-02-23 07:11:08', NULL),
(34, '5c70f278a57045.11664277', 'سایر', '', '2019-02-23 07:12:56', '2019-02-23 07:12:56', NULL),
(35, '5c70f28ace7350.95344155', 'عکاس', '', '2019-02-23 07:13:14', '2019-02-23 07:13:14', NULL),
(36, '5c70f2a0dcaaa9.06890232', 'پلاستیک', '', '2019-02-23 07:13:36', '2019-02-23 07:13:36', NULL),
(37, '5c70f2b0cdc6e8.31720412', 'راهنمای حرفه ای', '', '2019-02-23 07:13:52', '2019-02-23 07:13:52', NULL),
(38, '5c70f2c57a8133.86996694', 'جلقه نجات', '', '2019-02-23 07:14:13', '2019-02-23 07:14:13', NULL),
(39, '5c70f2dcd181f8.08991034', 'هلال احمر', '', '2019-02-23 07:14:36', '2019-02-23 07:14:36', NULL),
(40, '5c70f2e93a6918.82118344', 'کیسه خواب', '', '2019-02-23 07:14:49', '2019-02-23 07:14:49', NULL),
(41, '5c70f2f538d905.20482926', 'کفش مخصوص', '', '2019-02-23 07:15:01', '2019-02-23 07:15:01', NULL),
(42, '5c70f30218a957.55048798', 'چادر', '', '2019-02-23 07:15:14', '2019-02-23 07:15:14', NULL),
(43, '5c70f321b45037.69315313', 'وت سوت', '', '2019-02-23 07:15:45', '2019-02-23 07:15:45', NULL),
(44, '5ca9b7f29451f3.11144505', 'آب', 'آب آشامیدنی', '2019-04-07 08:42:26', '2019-04-07 10:00:39', NULL),
(45, '5ca9b80e595972.31575664', 'پتو', '', '2019-04-07 08:42:54', '2019-04-17 17:02:40', NULL),
(46, '5cb5973af34c73.99207632', 'نهار', '', '2019-04-16 08:50:02', '2019-04-16 08:50:02', NULL),
(47, '5cb5992f0664d5.54448411', 'عینک آفتابی', '', '2019-04-16 08:58:23', '2019-04-16 08:58:23', NULL),
(48, '5cb5cbf58910b1.72968975', 'کرم ضد آفتاب', '', '2019-04-16 12:35:01', '2019-04-16 12:35:01', NULL),
(49, '5cb5cc057df774.56206361', 'کلاه نقاب دار', '', '2019-04-16 12:35:17', '2019-04-16 12:35:17', NULL),
(50, '5cf24f32b6fbc0.40752940', 'پاور بانک', '', '2019-06-01 10:10:58', '2019-06-01 10:10:58', NULL),
(51, '5cf24f91c533f7.00758158', 'مسواک و خمیر دندان', '', '2019-06-01 10:12:33', '2019-06-01 10:12:33', NULL),
(52, '5cf251c8c19182.86008919', 'تنقلات', '', '2019-06-01 10:22:00', '2019-06-01 10:22:00', NULL),
(53, '5cf251d46b7b23.77924104', 'قایق', '', '2019-06-01 10:22:12', '2019-06-01 10:22:12', NULL),
(54, '5cf2537d447a08.91802555', 'صندلی تاشو', '', '2019-06-01 10:29:17', '2019-06-01 10:29:17', NULL),
(55, '5cf253deb88bd4.95253771', 'پانچو (بارانی)', '', '2019-06-01 10:30:54', '2019-06-01 10:30:54', NULL),
(56, '5cf254a4b578b8.40981227', 'لنسر ماهیگیری', '', '2019-06-01 10:34:12', '2019-06-01 10:34:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `feedback_id` bigint NOT NULL AUTO_INCREMENT,
  `feedback_guid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(260) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `tel` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name_and_family` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  UNIQUE KEY `feedback_guid` (`feedback_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gardens`
--

DROP TABLE IF EXISTS `gardens`;
CREATE TABLE IF NOT EXISTS `gardens` (
  `garden_id` bigint NOT NULL AUTO_INCREMENT,
  `garden_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `media` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `regulation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lat_lon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodic_holidays` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `on_time_holidays` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periodic_costs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `social` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `like_count` int NOT NULL DEFAULT '0',
  `report_count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`garden_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gardens_features`
--

DROP TABLE IF EXISTS `gardens_features`;
CREATE TABLE IF NOT EXISTS `gardens_features` (
  `garden_feature_id` bigint NOT NULL AUTO_INCREMENT,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `garden_feature_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `garden_id` bigint NOT NULL,
  `feature_id` bigint NOT NULL,
  `capacity` int DEFAULT NULL,
  `is_optional` tinyint NOT NULL,
  `is_required` tinyint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`garden_feature_id`),
  KEY `tour_id` (`garden_id`),
  KEY `feature_id` (`feature_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gardens_guards`
--

DROP TABLE IF EXISTS `gardens_guards`;
CREATE TABLE IF NOT EXISTS `gardens_guards` (
  `garden_guard_id` bigint NOT NULL AUTO_INCREMENT,
  `garden_guard_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `garden_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`garden_guard_id`),
  KEY `user_id` (`user_id`),
  KEY `garden_id` (`garden_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gardens_reports`
--

DROP TABLE IF EXISTS `gardens_reports`;
CREATE TABLE IF NOT EXISTS `gardens_reports` (
  `garden_report_id` bigint NOT NULL AUTO_INCREMENT,
  `garden_report_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `garden_id` bigint NOT NULL,
  `report_id` bigint NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`garden_report_id`),
  KEY `tour_id` (`garden_id`),
  KEY `feature_id` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_event`
--

DROP TABLE IF EXISTS `log_event`;
CREATE TABLE IF NOT EXISTS `log_event` (
  `log_event_id` bigint NOT NULL AUTO_INCREMENT,
  `log_event_guid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `controller_name_and_action_name` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`log_event_id`),
  UNIQUE KEY `log_event_guid` (`log_event_guid`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `media_id` bigint NOT NULL AUTO_INCREMENT,
  `media_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_id` bigint DEFAULT NULL,
  `garden_id` bigint DEFAULT NULL,
  `type` tinyint NOT NULL,
  `mime_type` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `view_count` mediumint NOT NULL DEFAULT '0',
  `like_count` mediumint NOT NULL DEFAULT '0',
  `is_approved` tinyint NOT NULL DEFAULT '0',
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` bigint NOT NULL AUTO_INCREMENT,
  `report_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservs`
--

DROP TABLE IF EXISTS `reservs`;
CREATE TABLE IF NOT EXISTS `reservs` (
  `reserve_id` bigint NOT NULL AUTO_INCREMENT,
  `reserve_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `garden_id` bigint DEFAULT NULL,
  `tour_id` bigint DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`reserve_id`),
  KEY `garden_id` (`garden_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
CREATE TABLE IF NOT EXISTS `tours` (
  `tour_id` bigint NOT NULL AUTO_INCREMENT,
  `tour_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_capacity` smallint NOT NULL,
  `remaining_capacity` smallint NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  `deadline_date_time` datetime NOT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `like_count` int NOT NULL DEFAULT '0',
  `report` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `minimum_age` tinyint DEFAULT NULL,
  `maximum_age` tinyint DEFAULT NULL,
  `gender` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hardship_level` tinyint DEFAULT NULL,
  `cost` decimal(20,5) NOT NULL,
  `stroked_cost` decimal(20,5) DEFAULT NULL,
  `gathering_place` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `social` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wholesale_discount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tour_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours_discounts`
--

DROP TABLE IF EXISTS `tours_discounts`;
CREATE TABLE IF NOT EXISTS `tours_discounts` (
  `tour_discount_id` bigint NOT NULL AUTO_INCREMENT,
  `tour_discount_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_id` bigint NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percent` tinyint NOT NULL DEFAULT '0',
  `capacity` int DEFAULT NULL,
  `remaining_capacity` int DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tour_discount_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours_features`
--

DROP TABLE IF EXISTS `tours_features`;
CREATE TABLE IF NOT EXISTS `tours_features` (
  `tour_feature_id` bigint NOT NULL AUTO_INCREMENT,
  `tour_feature_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_id` bigint NOT NULL,
  `feature_id` bigint NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  `count` smallint DEFAULT NULL,
  `is_optional` tinyint NOT NULL,
  `is_required` tinyint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tour_feature_id`),
  KEY `tour_id` (`tour_id`),
  KEY `feature_id` (`feature_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours_leaders`
--

DROP TABLE IF EXISTS `tours_leaders`;
CREATE TABLE IF NOT EXISTS `tours_leaders` (
  `tour_leader_id` bigint NOT NULL AUTO_INCREMENT,
  `tour_leader_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tour_leader_id`),
  KEY `user_id` (`user_id`),
  KEY `garden_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours_reports`
--

DROP TABLE IF EXISTS `tours_reports`;
CREATE TABLE IF NOT EXISTS `tours_reports` (
  `tour_report_id` bigint NOT NULL AUTO_INCREMENT,
  `tour_report_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_id` bigint NOT NULL,
  `report_id` bigint NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tour_report_id`),
  KEY `tour_id` (`tour_id`),
  KEY `feature_id` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` bigint NOT NULL AUTO_INCREMENT,
  `transaction_guid` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `reserve_id` bigint DEFAULT NULL,
  `type` tinyint NOT NULL,
  `amount` decimal(20,5) NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `authority` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` int NOT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `reserve_id` (`reserve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint NOT NULL AUTO_INCREMENT,
  `user_guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  `name_family` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_email` tinyint NOT NULL,
  `notification_sms` tinyint NOT NULL,
  `email` varchar(260) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `social` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_users?accounts` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gardens`
--
ALTER TABLE `gardens`
  ADD CONSTRAINT `fk_users?gardens` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gardens_guards`
--
ALTER TABLE `gardens_guards`
  ADD CONSTRAINT `fk_gardens?gardens_guards` FOREIGN KEY (`garden_id`) REFERENCES `gardens` (`garden_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users?gardens_guards` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservs`
--
ALTER TABLE `reservs`
  ADD CONSTRAINT `fk_gardens?reservs` FOREIGN KEY (`garden_id`) REFERENCES `gardens` (`garden_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tours?reservs` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `fk_users?tours` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tours_leaders`
--
ALTER TABLE `tours_leaders`
  ADD CONSTRAINT `fk_tours?tours_leaders` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users?tours_leaders` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_reservs?transactions` FOREIGN KEY (`reserve_id`) REFERENCES `reservs` (`reserve_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users?transactions` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
