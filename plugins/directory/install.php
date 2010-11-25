<?php
$sFolder = $this->settings->rootPublic."uploads/directory/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
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
			"tag" => array("type" => "text","length" => 100),
			"address1" => array("type" => "text","length" => 100),
			"address2" => array("type" => "text","length" => 100),
			"city" => array("type" => "text","length" => 100),
			"state" => array("type" => "text","length" => 100),
			"zip" => array("type" => "text","length" => 12),
			"phone" => array("type" => "text","length" => 20),
			"fax" => array("type" => "text","length" => 100),
			"website" => array("type" => "text","length" => 100),
			"email" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
			"photo_x1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_x2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active"),
		"unique" => array("tag", "sort_order"),
		"fulltext" => array("name", "address1", "address2", "city", "state", "zip", "phone", "fax", "website", "email"),
		"search" => array(
			"title" => "name",
			"content" => null,
			"rows" => array("name", "address1", "address2", "city", "state", "zip", "phone", "fax", "website", "email"),
			"filter" => "`active` = 1"
		)
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
			"name" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"unique" => array("sort_order")
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
			"text" => "Listings",
			"link" => "/admin/directory/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/directory/categories/"
		)
	)
);