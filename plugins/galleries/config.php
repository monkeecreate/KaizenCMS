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
  `photo` varchar(100) default NULL,
  `title` varchar(20) NULL,
  `description` longtext,
  `gallery_default` tinyint(1) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `galleryid` (`galleryid`,`sort_order`),
  KEY `gallery_default` (`gallery_default`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;