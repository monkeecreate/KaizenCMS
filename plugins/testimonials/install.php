<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aTables = array(
	"testimonials" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100),
			"sub_name" => array("type" => "text","length" => 100),
			"text" => array("type" => "clob"),
			"tag" => array("type" => "text","length" => 100),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active"),
		"unique" => array("sort_order", "tag"),
		"fulltext" => array("name", "sub_name", "text"),
		"search" => array(
			"title" => "name",
			"content" => "text",
			"rows" => array("name", "sub_name", "text"),
			"filter" => "`active` = 1"
		)
	),
	"testimonials_categories" => array(
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
	"testimonials_categories_assign" => array(
		"fields" => array(
			"testimonialid" => array(
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
		"index" => array("testimonialid", "categoryid")
	)
);

$aSettings = array(
	// array(
	// 	"group" => "Testimonials",
	// 	"tag" => "testimonial_tag1",
	// 	"title" => "Tag 1",
	// 	"text" => "Test 1",
	// 	"value" => "Value 1",
	// 	"type" => "text",
	// 	"order" => 1
	// )
);

$aMenuAdmin = array(
	"title" => "Testimonials",
	"menu" => array(
		array(
			"text" => "Testimonials",
			"link" => "/admin/testimonials/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/testimonials/categories/"
		)
	)
);