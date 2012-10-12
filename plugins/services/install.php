<?php
$sFolder = $this->settings->rootPublic."uploads/services/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"services" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 255),
			"tag" => array("type" => "text","length" => 255),
			"short_content" => array("type" => "clob"),
			"content" => array("type" => "clob"),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"active" => array("type" => "boolean"),
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
		"index" => array("active"),
		"unique" => array("sort_order", "tag"),
		"fulltext" => array("title", "short_content", "content"),
		"search" => array(
			"title" => "title",
			"content" => "content",
			"rows" => array("title", "short_content", "content"),
			"filter" => "`active` = 1"
		)
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Services",
	"menu" => array(
		array(
			"text" => "Services",
			"link" => "/admin/services/"
		)
	)
);