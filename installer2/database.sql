DROP TABLE IF EXISTS `{tablePrefix}content`;

CREATE TABLE `{tablePrefix}content` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`title` varchar(255),
	`tag` varchar(255),
	`content` longtext,
	`tags` longtext,
	`permanent` tinyint(1),
	`has_sub_menu` tinyint(1),
	`sub_item_of` int,
	`sort_order` int,
	`template` varchar(255),
	`active` tinyint(1),
	`created_datetime` datetime NOT NULL,
	`created_by` int(11) unsigned NOT NULL,
	`updated_datetime` datetime NOT NULL,
	`updated_by` int(11) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `index` (`permanent`, `has_sub_menu`, `sub_item_of`, `sort_order`, `active`),
	UNIQUE KEY `unique_index` (`tag`),
	FULLTEXT (`title`, `content`)
) Engine=MyISAM;

DROP TABLE IF EXISTS `{tablePrefix}content_excerpts`;

CREATE TABLE `{tablePrefix}content_excerpts` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`title` varchar(255) NOT NULL,
	`tag` varchar(255),
	`content` longtext,
	`description` longtext,
	`created_datetime` datetime NOT NULL,
	`created_by` int(11) unsigned NOT NULL,
	`updated_datetime` datetime NOT NULL,
	`updated_by` int(11) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `unique_index` (`tag`),
	FULLTEXT (`title`, `content`)
) Engine=MyISAM;

DROP TABLE IF EXISTS `{tablePrefix}menu_admin`;

CREATE TABLE `{tablePrefix}menu_admin` (
	`tag` varchar(30) NOT NULL,
	`sort_order` int(11),
	`info` longtext,
	INDEX `index` (`tag`, `sort_order`)
) Engine=MyISAM;

INSERT INTO `{tablePrefix}menu_admin` (`tag`, `sort_order`, `info`) VALUES
('content', 1, '{"title":"Content Pages","menu":[{"text":"Pages","link":"/admin/content/"},{"text":"Excerpts","link":"/admin/content/excerpts/"}],"icon":"icon-book"}'),
('users', 2, '{"title":"Users","menu":[{"text":"Manage Users","link":"/admin/users/"}],"icon":"icon-user"}'),
('settings', 3, '{"title":"Site Settings","menu":[{"text":"Site Settings","link":"/admin/settings/"},{"text":"Manage Settings","link":"/admin/settings/manage/","type":"super"},{"text":"Plugins","link":"/admin/settings/plugins/","type":"super"},{"text":"Admin Menu","link":"/admin/settings/admin-menu/","type":"super"},"icon" => "icon-cog"}');

DROP TABLE IF EXISTS `{tablePrefix}plugins`;

CREATE TABLE `{tablePrefix}plugins` (
	`plugin` varchar(255),
	INDEX `index` (`plugin`)
) Engine=MyISAM;

DROP TABLE IF EXISTS `{tablePrefix}settings`;

CREATE TABLE `{tablePrefix}settings` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`group` varchar(255),
	`tag` varchar(255),
	`title` varchar(255),
	`text` longtext,
	`value` longtext,
	`type` varchar(255),
	`validation` longtext,
	`sortOrder` int(11),
	`active` tinyint(1),
	PRIMARY KEY (`id`),
	INDEX `index` (`group`, `tag`, `sortOrder`, `active`)
) Engine=MyISAM;

INSERT INTO `{tablePrefix}settings` (`group`, `tag`, `title`, `text`, `value`, `type`, `validation`, `sortOrder`, `active`) VALUES
(1, 'analytics-google', 'Google Analytics', 'Enter only your Google Analytics Property ID for this website, not the entire tracking code. This ID should look like UA-XXXXXXX-XX.', '', 'text', '', 3, 1),
(1, 'site-title', 'Site Title', 'Use brief, but descriptive titles. Titles can be both short and informative. If the title is too long, Google will show only a portion of it in the search result.', '', 'text', '["required"]', 1, 1),
(1, 'site-description', 'Site Description', 'Accurately summarize the site\'s content. Write a description that would both inform and interest users if they saw your description meta tag as a snippet in a search result.', '', 'textarea', '', 2, 1),
(2, 'contact-subject', 'Contact Form Subject', 'This subject will be used for emails sent from your contact page. A descriptive subject for the site will help you filter out emails sent from visitors.', 'Website Contact Form', 'text', '["required"]', 1, 1),
(2, 'contact-email', 'Email Address', 'Emails from your contact page will be sent to this email address.', '', 'text', '["required","email"]', 2, 1),
(2, 'contact-company', 'Company Name', 'This name will appear with your mailing address. It can either be a contact persons name or we recommend it being your company name.', '', 'text', '', 3, 1),
(2, 'contact-address', 'Street Address', '', '', 'text', '', 4, 1),
(2, 'contact-address2', 'Street Address 2', 'PO Box, suite number, lot, etc.', '', 'text', '', 5, 1),
(2, 'contact-city', 'City', '', '', 'text', '', 6, 1),
(2, 'contact-zip', 'Zip Code', '', '', 'text', '', 8, 1),
(2, 'contact-phone', 'Phone Number', 'Include area code and extension is needed.', '', 'text', '', 9, 1),
(2, 'contact-fax', 'Fax Number', '', '', 'text', '', 10, 1),
(3, 'twitter_connect', 'Twitter Connect', '', '', 'twitter', '', 1, 1),
(3, 'facebook_connect', 'Facebook Connect', '', '', 'facebook', '', 2, 1),
(3, 'twitter-username', 'Twitter Username', 'Do not include your full Twitter URL, this is just your username without the @.', '', 'text', '', 3, 1),
(3, 'facebook-url', 'Facebook URL', 'This should be the full url to your Facebook profile or page including http://facebook.com/.', '', 'text', '', 4, 1),
(4, 'twitter_consumer_key', 'Twitter - Consumer Key', '', '', 'text', '', 1, 1),
(4, 'twitter_consumer_secret', 'Twitter - Consumer Secret', '', '', 'text', '', 2, 1),
(4, 'bitly_user', 'Bit.ly User', '', '', 'text', '', 3, 1),
(4, 'bitly_key', 'Bit.ly Key', '', '', 'text', '', 4, 1),
(4, 'facebook_app_id', 'Facebook - App ID', '', '', 'text', '', 5, 1),
(4, 'facebook_app_secret', 'Facebook - App Secret', '', '', 'text', '', 6, 1),
(4, 'mailchimp-api', 'MailChimp API Key', '', '', 'text', '', 7, 1);

DROP TABLE IF EXISTS `{tablePrefix}settings_groups`;

CREATE TABLE `{tablePrefix}settings_groups` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`name` varchar(255),
	`description` longtext,
	`sort_order` int(11),
	`active` tinyint(1),
	`restricted` tinyint(1),
	PRIMARY KEY (`id`),
	INDEX `index` (`sort_order`, `active`)
) Engine=MyISAM;

INSERT INTO `{tablePrefix}settings_groups` (`name`, `description`, `sort_order`, `active`, `restricted`) VALUES
('General Settings', '', 1, 1, 0),
('Contact Info', '', 2, 1, 0),
('Social Settings', '', 3, 1, 0),
('Social Developer Settings', 'The following social settings are for developer use only. Changing or removing any of the following fields could break an aspect of the website and the social sharing. Please use with caution.', 4, 1, 0);

DROP TABLE IF EXISTS `{tablePrefix}search`;

CREATE TABLE `{tablePrefix}search` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`plugin` varchar(50),
	`table` varchar(64),
	`column_title` varchar(64),
	`column_content` varchar(64),
	`rows` longtext,
	`filter` longtext,
	PRIMARY KEY (`id`),
	INDEX `index` (`plugin`)
) Engine=MyISAM;

INSERT INTO `{tablePrefix}search` (`plugin`, `table`, `column_title`, `column_content`, `rows`, `filter`) VALUES ('content', 'content', 'title', 'content', '["title","content"]', '`active` = 1');

DROP TABLE IF EXISTS `{tablePrefix}users`;

CREATE TABLE `{tablePrefix}users`(
	`id` int(11) unsigned NOT NULL auto_increment,
	`username` varchar(255),
	`password` varchar(255),
	`fname` varchar(255),
	`lname` varchar(255),
	`email_address` varchar(255),
	`super` tinyint(1),
	`resetCode` varchar(255),
	`last_login` int(11) unsigned default null,
	`last_password` int(11) unsigned default null,
	PRIMARY KEY (`id`),
	INDEX `index` (`username`)
) Engine=MyISAM;

DROP TABLE IF EXISTS `{tablePrefix}users_privileges`;

CREATE TABLE `{tablePrefix}users_privileges`(
	`userid` int(11),
	`menu` varchar(255),
	INDEX `index` (`userid`, `menu`)
) Engine=MyISAM;
