<?php
$aDatabases = array(
	"directory" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
			),
			"name" => array("type" => "text","length" => 100),
			"address1" => array("type" => "text","length" => 100),
			"adress2" => array("type" => "text","length" => 100),
			"city" => array("type" => "text","length" => 100),
			"state" => array("type" => "text","length" => 3),
			"zip" => array("type" => "text","length" => 12),
			"phone" => array("type" => "text","length" => 20),
			"fax" => array("type" => "text","length" => 100),
			"website" => array("type" => "text","length" => 100),
			"email" => array("type" => "text","length" => 100),
			"file" => array("type" => "text","length" => 100),
			"active" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
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
				"auto_increment"
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
			"text" => "Add Listing",
			"link" => "/admin/directory/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Directory",
			"link" => "/admin/directory/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/directory/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/directory/categories/"
		)
	)
);