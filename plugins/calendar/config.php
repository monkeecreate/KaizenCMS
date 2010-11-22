<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Calendar",
	"version" => "1.1",
	"author" => "monkeeCreate",
	"website" => "http://monkeecreate.com/",
	"email" => "support@monkeecreate.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 320,
		"imageMinHeight" => 200,
		"imageFolder" => "/uploads/calendar/",
		"useCategories" => true,
		"perPage" => 5,
		"shortContentCharacters" => 250 // max characters for short content
	)
);