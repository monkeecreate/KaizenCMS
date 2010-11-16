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
			"tag" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("sort_order", "active"),
		"unique" => array("tag"),
		"fulltext" => array("question", "answer"),
		"search" => array(
			"title" => "question",
			"content" => "answer",
			"rows" => array("question", "answer"),
			"filter" => "`active` = 1"
		)
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

$aSettings = array();

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