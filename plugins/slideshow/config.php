<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Slideshow",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 262,
		"imageMinHeight" => 100,
		"imageFolder" => "/uploads/slideshow/",
		"shortContentCharacters" => 250, // max characters for short content
		"sort" => "random-asc", // manual, title, created, updated, random - asc, desc
		"useDescription" => true
	)
);