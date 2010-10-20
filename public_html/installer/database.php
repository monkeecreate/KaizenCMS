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
			"tag" => array("type" => "text","length" => 30),
			"title" => array("type" => "text","length" => 100),
			"content" => array("type" => "clob"),
			"perminate" => array("type" => "boolean"),
			"has_sub_menu" => array("type" => "boolean"),
			"sub_item_of" => array("type" => "integer"),
			"sort_order" => array("type" => "integer"),
			"module" => array("type" => "boolean"),
			"template" => array("type" => "text","length" => 100),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("tag", "perminate", "has_sub_menu", "sub_item_of", "sort_order", "module")
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
						"title" => "Content",
						"menu" => array(
							array(
								"text" => "Manage Pages",
								"link" => "/admin/content/"
							)
						)
					)
				)
			),
			array(
				"tag" => "settings",
				"sort_order" => 2, 
				"info" => json_encode(
					array(
						"title" => "Settings",
						"menu" => array(
							array(
								"text" => "Settings",
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
						)
					)
				)
			),
			array(
				"tag" => "users",
				"sort_order" => 3, 
				"info" => json_encode(
					array(
						"title" => "Users",
						"menu" => array(
							array(
								"text" => "Manage Users",
								"link" => "/admin/users/"
							)
						)
					)
				)
			)
		)
	),
	"plugins" => array(
		"fields" => array(
			"plugin" => array("type" => "text","length" => 50)
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
			"group" => array("type" => "text","length" => 100),
			"tag" => array("type" => "text","length" => 50),
			"title" => array("type" => "text","length" => 100),
			"text" => array("type" => "clob"),
			"value" => array("type" => "clob"),
			"type" => array("type" => "text","length" => 50),
			"sortOrder" => array("type" => "integer"),
			"active" => array("type" => "boolean")
		),
		"index" => array("group", "tag", "sortOrder", "active"),
		"data" => array(
			array(
				"group" => 1,
				"tag" => "analytics_google",
				"title" => "Google Analytics",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 1,
				"tag" => "analytics_woopra",
				"title" => "Woopra",
				"text" => NULL,
				"value" => 0,
				"type" => "bool",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "title",
				"title" => "Site Title",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "keywords",
				"title" => "Keywords",
				"text" => NULL,
				"value" => "",
				"type" => "textarea",
				"sortOrder" => 3,
				"active" => 1
			),
			array(
				"group" => 2,
				"tag" => "description",
				"title" => "Description",
				"text" => NULL,
				"value" => "",
				"type" => "textarea",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "email",
				"title" => "Email Address",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 3,
				"tag" => "contact-subject",
				"title" => "Contact Form Subject",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "twitter_connect",
				"title" => "Twitter Connect",
				"text" => NULL,
				"value" => "",
				"type" => "twitter",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "facebook_connect",
				"title" => "Facebook Connect",
				"text" => NULL,
				"value" => "",
				"type" => "facebook",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "twitterUser",
				"title" => "Twitter Username",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "facebookUser",
				"title" => "Facebook Username",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 4,
				"tag" => "flickrEmail",
				"title" => "Flickr Email Address",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 4,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "twitter_consumer_key",
				"title" => "Twitter - Consumer Key",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "twitter_consumer_secret",
				"title" => "Twitter- Consumer Secret",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 2,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "bitly_user",
				"title" => "Bit.ly User",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 3,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "bitly_key",
				"title" => "Bit.ly Key",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 4,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "facebook_app_id",
				"title" => "Facebook - App ID",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
				"active" => 1
			),
			array(
				"group" => 5,
				"tag" => "facebook_app_secret",
				"title" => "Facebook - App Secret",
				"text" => NULL,
				"value" => "",
				"type" => "text",
				"sortOrder" => 1,
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
			"name" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer"),
			"active" => array("type" => "boolean")
		),
		"index" => array("sort_order", "active"),
		"data" => array(
			array(
				"id" => 1,
				"name" => "Analytics",
				"sort_order" => 1,
				"active" => 1
			),
			array(
				"id" => 2,
				"name" => "SEO",
				"sort_order" => 2,
				"active" => 1
			),
			array(
				"id" => 3,
				"name" => "Contact Info",
				"sort_order" => 3,
				"active" => 1
			),
			array(
				"id" => 4,
				"name" => "Social",
				"sort_order" => 4,
				"active" => 1
			),
			array(
				"id" => 5,
				"name" => "Social Dev",
				"sort_order" => 5,
				"active" => 1
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
			"username" => array("type" => "text","length" => 100),
			"password" => array("type" => "text","length" => 100),
			"fname" => array("type" => "text","length" => 100),
			"lname" => array("type" => "text","length" => 100),
			"email_address" => array("type" => "text","length" => 100),
			"resetCode" => array("type" => "text","length" => 100),
			"last_login" => array("type" => "integer","unsigned" => 1,"default" => null),
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
			"menu" => array("type" => "text","length" => 100)
		),
		"index" => array("userid", "menu")
	)
);
