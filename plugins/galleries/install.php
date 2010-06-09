<?php
$sFolder = $this->_settings->rootPublic."uploads/galleries/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aDatabases = array(
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
			"description" => array("type" => "clob"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("sort_order")
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
			"text" => "Add Gallery",
			"link" => "/admin/galleries/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Galleries",
			"link" => "/admin/galleries/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/galleries/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/galleries/categories/"
		)
	)
);