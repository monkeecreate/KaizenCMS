<?php
$aDatabases = array(
	"promos" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
			),
			"name" => array("type" => "text","length" => 100),
			"link" => array("type" => "text","length" => 255),
			"promo" => array("type" => "text","length" => 100),
			"impressions" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"clicks" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_show" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"datetime_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"use_kill" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active", "datetime_show", "use_kill")
	),
	"promos_positions" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"auto_increment"
			),
			"tag" => array("type" => "text","length" => 25),
			"name" => array("type" => "text","length" => 100),
			"promo_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"promo_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		)
	),
	"promos_positions_assign" => array(
		"fields" => array(
			"promoid" => array(
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
		"index" => array("promoid", "positionid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Promos",
	"menu" => array(
		array(
			"text" => "Add Promo",
			"link" => "/admin/promos/add/",
			"icon" => "circle-plus"
		),
		array(
			"text" => "Manage Promos",
			"link" => "/admin/promos/"
		),
		array(
			"text" => "Add Position",
			"link" => "/admin/promos/positions/add/",
			"icon" => "circle-plus",
			"type" => "super"
		),
		array(
			"text" => "Manage Positions",
			"link" => "/admin/promos/positions/",
			"type" => "super"
		)
	)
);