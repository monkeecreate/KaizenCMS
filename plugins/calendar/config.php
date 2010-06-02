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