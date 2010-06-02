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