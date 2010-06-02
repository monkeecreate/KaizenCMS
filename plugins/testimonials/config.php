CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `sub_name` varchar(100) default NULL,
  `text` longtext,
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