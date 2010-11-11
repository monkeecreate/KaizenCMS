<?php
$sFolder = $this->settings->rootPublic."uploads/galleries/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"galleries" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100),
			"tag" => array("type" => "text","length" => 100),
			"description" => array("type" => "clob"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("tag", "sort_order"),
		"fulltext" => array("name", "description"),
		"search" => array(
			"title" => "name",
			"content" => "description",
			"rows" => array("name", "description"),
			"filter" => "`active` = 1"
		)
	),
	"galleries_categories" => array(
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
	"galleries_categories_assign" => array(
		"fields" => array(
			"galleryid" => array(
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
		"index" => array("galleryid", "categoryid")
	),
	"galleries_photos" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"galleryid" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo" => array("type" => "text","length" => 100),
			"title" => array("type" => "text","length" => 20),
			"description" => array("type" => "clob"),
			"gallery_default" => array("type" => "boolean"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("galleryid","gallery_default","sort_order")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Photo Galleries",
	"menu" => array(
		array(
			"text" => "Galleries",
			"link" => "/admin/galleries/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/galleries/categories/"
		)
	)
);