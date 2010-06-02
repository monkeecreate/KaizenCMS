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