<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aTables = array(
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
			"image" => array("type" => "text","length" => 100),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active"),
		"fulltext" => array("name", "description"),
		"search" => array(
			"title" => "name",
			"content" => "description",
			"rows" => array("name", "description")
		)
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
			"text" => "Links",
			"link" => "/admin/links/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/links/categories/"
		)
	)
);