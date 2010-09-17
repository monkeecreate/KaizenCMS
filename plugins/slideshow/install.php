<?php
$sFolder = $this->_settings->rootPublic."uploads/slideshow/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"slideshow" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 100),
			"description" => array("type" => "clob"),
			"active" => array("type" => "boolean"),
			"photo_x1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_x2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		)
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Slideshow",
	"menu" => array(
		array(
			"text" => "Slideshow",
			"link" => "/admin/slideshow/"
		)
	)
);