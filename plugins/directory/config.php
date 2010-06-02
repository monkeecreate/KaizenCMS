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