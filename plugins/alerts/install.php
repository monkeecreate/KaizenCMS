<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aTables = array(
	"alerts" => array(
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
			"content" => array("type" => "clob"),
			"link" => array("type" => "clob"),
			"datetime_show" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"use_kill" => array("type" => "boolean"),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("use_kill", "active"),
		"unique" => array("tag"),
		"fulltext" => array("title", "content"),
		"search" => array(
			"title" => "title",
			"content" => "content",
			"rows" => array("title", "content"),
			"filter" => "`active` = 1 AND `datetime_show` < {time} AND (`use_kill` = 0 OR `datetime_kill` > {time})"
		)
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Alerts",
	"menu" => array(
		array(
			"text" => "Alerts",
			"link" => "/admin/alerts/"
		)
	)
);