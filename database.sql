CREATE TABLE `calendar` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `calendar_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `calendar_categories_assign` (
  `eventid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `eventid` (`eventid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `content` (
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

CREATE TABLE `directory` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `address1` varchar(100) default NULL,
  `address2` varchar(100) default NULL,
  `city` varchar(100) default NULL,
  `state` varchar(3) default NULL,
  `zip` varchar(12) default NULL,
  `phone` varchar(20) default NULL,
  `fax` varchar(100) default NULL,
  `website` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `file` varchar(100) default NULL,
  `active` tinyint(1) NOT NULL,
  `created_datetime` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_datetime` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `directory_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `directory_categories_assign` (
  `listingid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `listingid` (`listingid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `documents` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `documents_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `documents_categories_assign` (
  `documentid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`documentid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `events_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `events_categories_assign` (
  `eventid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`eventid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `faq` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `faq_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `faq_categories_assign` (
  `faqid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `faqid` (`faqid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `galleries` (
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

CREATE TABLE `galleries_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `galleries_categories_assign` (
  `galleryid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `galleryid` (`galleryid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `galleries_photos` (
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

CREATE TABLE `links` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `links_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `links_categories_assign` (
  `linkid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`linkid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `news_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `news_categories_assign` (
  `articleid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `articleid` (`articleid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `promos` (
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

CREATE TABLE `promos_positions` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(25) default NULL,
  `name` varchar(100) NOT NULL,
  `promo_width` int(11) default NULL,
  `promo_height` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `promos_positions_assign` (
  `promoid` int(11) NOT NULL,
  `positionid` int(11) NOT NULL,
  KEY `promoid` (`promoid`,`positionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `testimonials` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `testimonials_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `testimonials_categories_assign` (
  `testimonialid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  KEY `testimonialid` (`testimonialid`,`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
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

CREATE TABLE `users_privlages` (
  `userid` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL,
  KEY `userid` (`userid`,`menu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
