<?php
$aDatabases = array(
	"links" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
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
				"auto_increment"
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