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