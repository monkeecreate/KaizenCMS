<?php
$aDatabases = array(
	"faq" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
			),
			"question" => array("type" => "text","length" => 100),
			"answer" => array("type" => "clob"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("sort_order", "active")
	),
	"faq_categories" => array(
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
	"faq_categories_assign" => array(
		"fields" => array(
			"faqid" => array(
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
		"index" => array("faqid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "FAQ",
	"menu" => array(
		array(
			"text" => "Add Question & Answer",
			"link" => "/admin/faq/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Questions",
			"link" => "/admin/faq/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/faq/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/faq/categories/"
		)
	)
);