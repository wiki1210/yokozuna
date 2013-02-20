-- WordPress Backup to Dropbox SQL Dump
-- Version 1.4.2
-- http://wpb2d.com
-- Generation Time: January 5, 2013 at 18:04

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Create and use the backed up database
--

CREATE DATABASE IF NOT EXISTS d12708189eeac4f5fa639e7dd682f5385;
USE d12708189eeac4f5fa639e7dd682f5385;

--
-- Table structure for table `wp_emailSub_addresses`
--

CREATE TABLE `wp_emailSub_addresses` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_emailSub_addresses` is empty
--

--
-- Table structure for table `wp_emailSub_spool`
--

CREATE TABLE `wp_emailSub_spool` (
  `spool_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`spool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_emailSub_spool` is empty
--

--
-- Table structure for table `wp_email_subscription`
--

CREATE TABLE `wp_email_subscription` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `subscribe_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_email_subscription` is empty
--

--
-- Table structure for table `wp_follow_blog_post`
--

CREATE TABLE `wp_follow_blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) NOT NULL DEFAULT '0',
  `comment_author_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `follow_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_follow_blog_post` is empty
--

--
-- Table structure for table `wp_follow_blog_post_log`
--

CREATE TABLE `wp_follow_blog_post_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mail_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `log_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_follow_blog_post_log` is empty
--

--
-- Table structure for table `wp_sml`
--

CREATE TABLE `wp_sml` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `sml_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sml_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table `wp_sml` is empty
--

