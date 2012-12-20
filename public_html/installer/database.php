<?php
$aTables = array(
	"content" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 255),
			"tag" => array("type" => "text","length" => 255),
			"content" => array("type" => "clob"),
			"tags" => array("type" => "clob"),
			"permanent" => array("type" => "boolean"),
			"has_sub_menu" => array("type" => "boolean"),
			"sub_item_of" => array("type" => "integer"),
			"sort_order" => array("type" => "integer"),
			"template" => array("type" => "text","length" => 255),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("permanent", "has_sub_menu", "sub_item_of", "sort_order", "active"),
		"unique" => array("tag"),
		"fulltext" => array("title", "content")
	),
	"content_excerpts" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 255),
			"tag" => array("type" => "text","length" => 255),
			"content" => array("type" => "clob"),
			"description" => array("type" => "clob"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array(),
		"unique" => array("tag"),
		"fulltext" => array("title", "content")
	),
	"menu_admin" => array(
		"fields" => array(
			"tag" => array("type" => "text","length" => 30),
			"sort_order" => array("type" => "integer"),
			"info" => array("type" => "clob")
		),
		"index" => array("tag", "sort_order"),
		"data" => array(
			array(
				"tag" => "content",
				"sort_order" => 1,
				"info" => json_encode(
					array(
						"title" => "Content Pages",
						"menu" => array(
							array(
								"text" => "Pages",
								"link" => "/admin/content/"
							),
							array(
								"text" => "Excerpts",
								"link" => "/admin/content/excerpts/"
							)
						),
						"icon" => "icon-book"
					)
				)
			),
			array(
				"tag" => "users",
				"sort_order" => 2,
				"info" => json_encode(
					array(
						"title" => "Users",
						"menu" => array(
							array(
								"text" => "Manage Users",
								"link" => "/admin/users/"
							)
						),
						"icon" => "icon-user"
					)
				)
			),
			array(
				"tag" => "settings",
				"sort_order" => 3,
				"info" => json_encode(
					array(
						"title" => "Site Settings",
						"menu" => array(
							array(
								"text" => "Site Settings",
								"link" => "/admin/settings/"
							),
							array(
								"text" => "Manage Settings",
								"link" => "/admin/settings/manage/",
								"type" => "super"
							),
							array(
								"text" => "Plugins",
								"link" => "/admin/settings/plugins/",
								"type" => "super"
							),
							array(
								"text" => "Admin Menu",
								"link" => "/admin/settings/admin-menu/",
								"type" => "super"
							)
						),
						"icon" => "icon-cog"
					)
				)
			)
		)
	),
	"plugins" => array(
		"fields" => array(
			"plugin" => array("type" => "text","length" => 255)
		),
		"index" => array("plugin")
	),
	"settings" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"group" => array("type" => "text","length" => 255),
			"tag" => array("type" => "text","length" => 255),
			"title" => array("type" => "text","length" => 255),
			"text" => array("type" => "clob"),
			"value" => array("type" => "clob"),
			"type" => array("type" => "text","length" => 255),
			"validation" => array("type" => "clob"),
			"sortOrder" => array("type" => "integer"),
			"active" => array("type" => "boolean")
		),
		"index" => array("group", "tag", "sortOrder", "active"),
		"data" => array(
			array(
				"group" => 1,
				"tag" => "analytics-google",
				"title" => "Google Analytics",
				"text" => "Enter only your Google Analytics Property ID for this website, not the entire tracking code. This ID should look like UA-XXXXXXX-XX.",
				"value" => "",
				"type" => "text",
				"sortOrder" => 3,
				"active" => 1
			),
			array(
				"group" => 1,
				"tag" => "site-title",
				"title" => "Site Title",
				"text" => "Use brief, but descriptive titles. Titles can be both short and informative. If the title is too long, Google will show only a portion of it in the search result.",
				"value" => "",
				"type" => "text",
				"validation" => json_encode(array("required")),
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 1,
				"tag" => "site-description",
				"title" => "Site Description",
				"text" => "Accurately summarize the site's content. Write a description that would both inform and interest users if they saw your description meta tag as a snippet in a search result.",
				"value" => "",
				"type" => "textarea",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-subject",
				"title" => "Contact Form Subject",
				"text" => "This subject will be used for emails sent from your contact page. A descriptive subject for the site will help you filter out emails sent from visitors.",
				"value" => "Website Contact Form",
				"type" => "text",
				"validation" => json_encode(array("required")),
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-email",
				"title" => "Email Address",
				"text" => "Emails from your contact page will be sent to this email address.",
				"value" => "",
				"type" => "text",
				"validation" => json_encode(array("required", "email")),
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-company",
				"title" => "Company Name",
				"text" => "This name will appear with your mailing address. It can either be a contact persons name or we recommend it being your company name.",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 3,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-address",
				"title" => "Street Address",
				"text" => "",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 4,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-address2",
				"title" => "Street Address 2",
				"text" => "PO Box, suite number, lot, etc.",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 5,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-city",
				"title" => "City",
				"text" => "",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 6,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-state",
				"title" => "State",
				"text" => "",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 7,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-zip",
				"title" => "Zip Code",
				"text" => "",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 8,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-phone",
				"title" => "Phone Number",
				"text" => "Include area code and extension is needed.",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 9,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "contact-fax",
				"title" => "Fax Number",
				"text" => "",
				"value" => "",
				"type" => "text",
				"validation" => null,
				"sortOrder" => 10,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "twitter_connect",
				"title" => "Twitter Connect",
				"text" => NULL,
				"value" => "",
				"type" => "twitter",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "facebook_connect",
				"title" => "Facebook Connect",
				"text" => NULL,
				"value" => "",
				"type" => "facebook",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "twitter-username",
				"title" => "Twitter Username",
				"text" => "Do not include your full Twitter URL, this is just your username without the @.",
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "facebook-url",
				"title" => "Facebook URL",
				"text" => "This should be the full url to your Facebook profile or page including http://facebook.com/.",
				"value" => "",
				"type" => "text",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "twitter_consumer_key",
				"title" => "Twitter - Consumer Key",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "twitter_consumer_secret",
				"title" => "Twitter- Consumer Secret",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "bitly_user",
				"title" => "Bit.ly User",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 3,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "bitly_key",
				"title" => "Bit.ly Key",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 4,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "facebook_app_id",
				"title" => "Facebook - App ID",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "facebook_app_secret",
				"title" => "Facebook - App Secret",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "mailchimp-api",
				"title" => "MailChimp API Key",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 6,
				"active" => 1
			)
		)
	),
	"settings_groups" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 255),
			"description" => array("type" => "clob"),
			"sort_order" => array("type" => "integer"),
			"active" => array("type" => "boolean"),
			"restricted" => array("type" => "boolean")
		),
		"index" => array("sort_order", "active"),
		"data" => array(
			array(
				"id" => 1,
				"name" => "General Settings",
				"description" => "",
				"sort_order" => 1,
				"active" => 1,
				"restricted" => 0
			),
			array(
				"id" => 2,
				"name" => "Contact Info",
				"description" => "",
				"sort_order" => 2,
				"active" => 1,
				"restricted" => 0
			),
			array(
				"id" => 3,
				"name" => "Social Settings",
				"description" => "",
				"sort_order" => 3,
				"active" => 1,
				"restricted" => 0
			),
			array(
				"id" => 4,
				"name" => "Social Developer Settings",
				"description" => "The following social settings are for developer use only. Changing or removing any of the following fields could break an aspect of the website and the social sharing. Please use with caution.",
				"sort_order" => 4,
				"active" => 1,
				"restricted" => 1
			)
		)
	),
	"search" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"plugin" => array("type" => "text","length" => 50),
			"table" => array("type" => "text","length" => 64),
			"column_title" => array("type" => "text","length" => 64),
			"column_content" => array("type" => "text","length" => 64),
			"rows" => array("type" => "clob"),
			"filter" => array("type" => "clob")
		),
		"index" => array("plugin"),
		"data" => array(
			array(
				"plugin" => "content",
				"table" => "content",
				"column_title" => "title",
				"column_content" => "content",
				"rows" => json_encode(array("title", "content")),
				"filter" => "`active` = 1"
			)
		)
	),
	"users" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"username" => array("type" => "text","length" => 255),
			"password" => array("type" => "text","length" => 255),
			"fname" => array("type" => "text","length" => 255),
			"lname" => array("type" => "text","length" => 255),
			"email_address" => array("type" => "text","length" => 255),
			"super" => array("type" => "boolean"),
			"resetCode" => array("type" => "text","length" => 255),
			"last_login" => array("type" => "integer","unsigned" => 1,"default" => null),
			"last_password" => array("type" => "integer","unsigned" => 1,"default" => null),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("username")
	),
	"users_privileges" => array(
		"fields" => array(
			"userid" => array("type" => "integer"),
			"menu" => array("type" => "text","length" => 255)
		),
		"index" => array("userid", "menu")
	)
);
