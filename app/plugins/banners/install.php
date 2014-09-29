<?php
$sFolder = $this->settings->rootPublic."uploads/banners/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"banners" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 100),
			"link" => array("type" => "text","length" => 255),
			"description" => array("type" => "clob"),
			"banner" => array("type" => "text","length" => 100),
			"impressions" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"clicks" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_show" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"use_kill" => array("type" => "boolean"),
			"active" => array("type" => "boolean"),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active", "datetime_show", "use_kill")
	),
	"banners_positions" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"tag" => array("type" => "text","length" => 25),
			"name" => array("type" => "text","length" => 100),
			"banner_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"banner_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		)
	),
	"banners_positions_assign" => array(
		"fields" => array(
			"bannerid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			),
			"positionid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			)
		),
		"index" => array("bannerid", "positionid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Banners",
	"menu" => array(
		array(
			"text" => "Banners",
			"link" => "/admin/banners/"
		),
		array(
			"text" => "Positions",
			"link" => "/admin/banners/positions/",
			"type" => "super"
		)
	)
);
