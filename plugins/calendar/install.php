<?php
$aDatabases = array(
	"calendar" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
			),
			"title" => array("type" => "text","length" => 100),
			"short_content" => array("type" => "clob"),
			"content" => array("type" => "clob"),
			"allday" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_start" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_end" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_show" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"use_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"sticky" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
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
		"index" => array("allday", "datetime_start", "datetime_end", "datetime_show", "datetime_kill", "use_kill", "active")
	),
	"calendar_categories" => array(
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
	"calendar_categories_assign" => array(
		"fields" => array(
			"eventid" => array(
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
		"index" => array("eventid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Calendar",
	"menu" => array(
		array(
			"text" => "Add Calendar Event",
			"link" => "/admin/calendar/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Calendar Events",
			"link" => "/admin/calendar/"
		),
		array(
			"text" => "Add Category",
			"link" => "/admin/calendar/categories/?addcategory=1",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Categories",
			"link" => "/admin/calendar/categories/"
		)
	)
);