<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "News",
	"version" => "1.0",
	"author" => "monkeeCreate",
	"website" => "http://monkeecreate.com/",
	"email" => "support@monkeecreate.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 140,
		"imageMinHeight" => 87,
		"imageFolder" => "/uploads/news/",
		"useCategories" => true,
		"perPage" => 5,
		"shortContentCharacters" => 250 // max characters for short content
	)
);