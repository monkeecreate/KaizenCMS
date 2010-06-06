<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aDatabases = array(
	"links" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100),
			"description" => array("type" => "clob"),
			"link" => array("type" => "text","length" => 255),
			"active" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active")
	),
	"links_categories" => array(
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
	"links_categories_assign" => array(
		"fields" => array(
			"linkid" => array(
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
		"index" => array("linkid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Links",
	"menu" => array(
		array(
			"text" => "Add Link",
			"link" => "/admin/links/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Links",
			"link" => "/admin/links/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/links/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/links/categories/"
		)
	)
);