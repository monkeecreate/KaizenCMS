<?php
$sFolder = $this->settings->rootPublic."uploads/BASE/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"BASE" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			
			#### SETUP COLUMNS HERE ####
			
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("active"),
		"unique" => array(),
		"fulltext" => array(),
		"search" => array(
			"title" => "",
			"content" => "",
			"rows" => array(),
			"filter" => ""
		)
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "BASE",
	"menu" => array(
		array(
			"text" => "BASE",
			"link" => "/admin/BASE/"
		)
	)
);