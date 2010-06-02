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