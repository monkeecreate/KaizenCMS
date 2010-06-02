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