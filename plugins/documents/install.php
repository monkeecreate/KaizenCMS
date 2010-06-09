<?php
$sFolder = $this->_settings->rootPublic."uploads/documents/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aDatabases = array(
	"documents" => array(
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
			"document" => array("type" => "text","length" => 100),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active")
	),
	"documents_categories" => array(
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
	"documents_categories_assign" => array(
		"fields" => array(
			"documentid" => array(
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
		"index" => array("documentid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Documents",
	"menu" => array(
		array(
			"text" => "Add Document",
			"link" => "/admin/documents/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Documents",
			"link" => "/admin/documents/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/documents/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/documents/categories/"
		)
	)
);