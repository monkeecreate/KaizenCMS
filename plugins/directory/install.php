<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aDatabases = array(
	"directory" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100),
			"address1" => array("type" => "text","length" => 100),
			"address2" => array("type" => "text","length" => 100),
			"city" => array("type" => "text","length" => 100),
			"state" => array("type" => "text","length" => 3),
			"zip" => array("type" => "text","length" => 12),
			"phone" => array("type" => "text","length" => 20),
			"fax" => array("type" => "text","length" => 100),
			"website" => array("type" => "text","length" => 100),
			"email" => array("type" => "text","length" => 100),
			"file" => array("type" => "text","length" => 100),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active")
	),
	"directory_categories" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100)
		)
	),
	"directory_categories_assign" => array(
		"fields" => array(
			"listingid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			),
			"categoryid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			)
		),
		"index" => array("listingid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Directory",
	"menu" => array(
		array(
			"text" => "Directory Listings",
			"link" => "/admin/directory/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/directory/categories/"
		)
	)
);