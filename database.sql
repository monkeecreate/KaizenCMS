-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2010 at 01:59 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cranewest_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `short_content` longtext,
  `content` longtext,
  `allday` tinyint(1) NOT NULL default '0',
  `datetime_start` int(11) NOT NULL default '0',
  `datetime_end` int(11) NOT NULL default '0',
  `datetime_show` int(11) NOT NULL default '0',
  `datetime_kill` int(11) NOT NULL default '0',
  `use_kill` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `photo_x1` int(11) NOT NULL default '0',
  `photo_y1` int(11) NOT NULL default '0',
  `photo_x2` int(11) NOT NULL default '0',
  `photo_y2` int(11) NOT NULL default '0',
  `photo_width` int(11) NOT NULL default '0',
  `photo_height` int(11) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `allday` (`allday`,`datetime_start`,`datetime_end`,`datetime_show`,`datetime_kill`,`use_kill`,`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_categories`
--

CREATE TABLE IF NOT EXISTS `calendar_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_categories_assign`
--

CREATE TABLE IF NOT EXISTS `calendar_categories_assign` (
  `eventid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `eventid` (`eventid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(30) default NULL,
  `title` varchar(100) default NULL,
  `content` longtext,
  `perminate` int(1) NOT NULL,
  `has_sub_menu` int(1) NOT NULL,
  `sub_item_of` int(11) NOT NULL,
  `sort_order` int(11) default NULL,
  `module` int(1) NOT NULL,
  `template` varchar(100) default NULL,
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`,`perminate`,`has_sub_menu`,`sub_item_of`,`sort_order`,`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  `document` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents_categories`
--

CREATE TABLE IF NOT EXISTS `documents_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents_categories_assign`
--

CREATE TABLE IF NOT EXISTS `documents_categories_assign` (
  `documentid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`documentid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `short_content` longtext,
  `content` longtext,
  `allday` tinyint(1) NOT NULL default '0',
  `datetime_start` int(11) NOT NULL,
  `datetime_end` int(11) NOT NULL,
  `datetime_show` int(11) NOT NULL,
  `datetime_kill` int(11) NOT NULL,
  `use_kill` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `photo_x1` int(11) NOT NULL default '0',
  `photo_y1` int(11) NOT NULL default '0',
  `photo_x2` int(11) NOT NULL default '0',
  `photo_y2` int(11) NOT NULL default '0',
  `photo_width` int(11) NOT NULL default '0',
  `photo_height` int(11) NOT NULL default '0',
  `template` varchar(100) default NULL,
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `use_kill` (`use_kill`,`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_categories`
--

CREATE TABLE IF NOT EXISTS `events_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_categories_assign`
--

CREATE TABLE IF NOT EXISTS `events_categories_assign` (
  `eventid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`eventid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `answer` longtext,
  `sort_order` int(11) NOT NULL default '1',
  `active` tinyint(1) NOT NULL,
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE IF NOT EXISTS `faq_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories_assign`
--

CREATE TABLE IF NOT EXISTS `faq_categories_assign` (
  `faqid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `faqid` (`faqid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  `sort_order` int(11) NOT NULL default '1',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `order` (`sort_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_categories`
--

CREATE TABLE IF NOT EXISTS `galleries_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_categories_assign`
--

CREATE TABLE IF NOT EXISTS `galleries_categories_assign` (
  `galleryid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `galleryid` (`galleryid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_photos`
--

CREATE TABLE IF NOT EXISTS `galleries_photos` (
  `id` int(11) NOT NULL auto_increment,
  `galleryid` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` longtext,
  `gallery_default` tinyint(1) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `galleryid` (`galleryid`,`sort_order`),
  KEY `gallery_default` (`gallery_default`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  `link` varchar(100) default NULL,
  `active` tinyint(1) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links_categories`
--

CREATE TABLE IF NOT EXISTS `links_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links_categories_assign`
--

CREATE TABLE IF NOT EXISTS `links_categories_assign` (
  `linkid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`linkid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `short_content` longtext,
  `content` longtext,
  `datetime_show` int(11) NOT NULL,
  `datetime_kill` int(11) NOT NULL,
  `use_kill` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `photo_x1` int(11) NOT NULL default '0',
  `photo_y1` int(11) NOT NULL default '0',
  `photo_x2` int(11) NOT NULL default '0',
  `photo_y2` int(11) NOT NULL default '0',
  `photo_width` int(11) NOT NULL default '0',
  `photo_height` int(11) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `use_kill` (`use_kill`,`sticky`,`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE IF NOT EXISTS `news_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news_categories_assign`
--

CREATE TABLE IF NOT EXISTS `news_categories_assign` (
  `articleid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`articleid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promos`
--

CREATE TABLE IF NOT EXISTS `promos` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) default NULL,
  `promo` varchar(100) default NULL,
  `impressions` int(11) NOT NULL default '0',
  `clicks` int(11) NOT NULL default '0',
  `datetime_show` int(11) NOT NULL,
  `datetime_kill` int(11) NOT NULL,
  `use_kill` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `datetime_show` (`datetime_show`),
  KEY `use_kill` (`use_kill`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `promos_positions`
--

CREATE TABLE IF NOT EXISTS `promos_positions` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(25) default NULL,
  `name` varchar(100) NOT NULL,
  `promo_width` int(11) default NULL,
  `promo_height` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `promos_positions_assign`
--

CREATE TABLE IF NOT EXISTS `promos_positions_assign` (
  `promoid` int(11) NOT NULL,
  `positionid` int(11) NOT NULL,
  KEY `promoid` (`promoid`,`positionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `sub_name` varchar(100) default NULL,
  `text` longtext,
  `video` varchar(100) default NULL,
  `poster` varchar(100) default NULL,
  `active` int(11) NOT NULL default '0',
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials_categories`
--

CREATE TABLE IF NOT EXISTS `testimonials_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials_categories_assign`
--

CREATE TABLE IF NOT EXISTS `testimonials_categories_assign` (
  `testimonialid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `testimonialid` (`testimonialid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fname` varchar(100) default NULL,
  `lname` varchar(100) default NULL,
  `created_datetime` int(11) NOT NULL default '0',
  `created_by` int(11) NOT NULL default '0',
  `updated_datetime` int(11) NOT NULL default '0',
  `updated_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `users` VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'User', 0, 0, 0, 0);
