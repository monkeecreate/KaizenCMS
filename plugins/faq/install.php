<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aTables = array(
	"faq" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"question" => array("type" => "text","length" => 100),
			"answer" => array("type" => "clob"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
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
				"autoincrement" => 1
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

$aSettings = array(
	array(
		"group" => "Test"
		,"tag" => "test"
		,"title" => "FAQ Test"
		,"text" => "Something something darkside"
		,"value" => ""
		,"type" => "text"
		,"sortOrder" => 1
	),
	array(
		"group" => "Test"
		,"tag" => "test2"
		,"title" => "FAQ Test2"
		,"text" => "Something something darkside"
		,"value" => ""
		,"type" => "text"
		,"sortOrder" => 2
	)
);

$aMenuAdmin = array(
	"title" => "FAQ",
	"menu" => array(
		array(
			"text" => "Questions",
			"link" => "/admin/faq/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/faq/categories/"
		)
	)
);