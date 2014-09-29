<?php
$sFolder = $this->settings->rootPublic."uploads/documents/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
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
			"tag" => array("type" => "text","length" => 100),
			"description" => array("type" => "clob"),
			"document" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active"),
		"unique" => array("tag", "sort_order"),
		"fulltext" => array("name", "description"),
		"search" => array(
			"title" => "name",
			"content" => "description",
			"rows" => array("name", "description"),
			"filter" => "`active` = 1"
		)
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
			"name" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"unique" => array("sort_order")
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
			"text" => "Documents",
			"link" => "/admin/documents/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/documents/categories/"
		)
	)
);