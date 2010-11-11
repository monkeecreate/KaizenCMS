<?php
$sFolder = $this->settings->rootPublic."uploads/calendar/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"calendar" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 100),
			"tag" => array("type" => "text","length" => 100),
			"short_content" => array("type" => "clob"),
			"content" => array("type" => "clob"),
			"allday" => array("type" => "boolean"),
			"datetime_start" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_end" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_show" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"use_kill" => array("type" => "boolean"),
			"sticky" => array("type" => "boolean"),
			"active" => array("type" => "boolean"),
			"photo_x1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_x2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"facebook_id" => array("type" => "clob"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("tag", "allday", "datetime_start", "datetime_end", "datetime_show", "datetime_kill", "use_kill", "active"),
		"fulltext" => array("title", "short_content", "content"),
		"search" => array(
			"title" => "title",
			"content" => "content",
			"rows" => array("title", "short_content", "content"),
			"filter" => "`active` = 1 AND `datetime_end` > {time} AND `datetime_show` < {time} AND (`use_kill` = 0 OR `datetime_kill` > {time})"
		)
	),
	"calendar_categories" => array(
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
			"text" => "Events",
			"link" => "/admin/calendar/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/calendar/categories/"
		)
	)
);