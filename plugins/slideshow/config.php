<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Slideshow",
	"version" => "1.0",
	"author" => "monkeeCreate",
	"website" => "http://monkeecreate.com/",
	"email" => "support@monkeecreate.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 262,
		"imageMinHeight" => 100,
		"imageFolder" => "/uploads/slideshow/",
		"shortContentCharacters" => 250, // max characters for short content
		"useDescription" => true
	)
);