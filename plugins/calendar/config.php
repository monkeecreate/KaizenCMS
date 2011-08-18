<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Calendar",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 320,
		"imageMinHeight" => 200,
		"imageFolder" => "/uploads/calendar/",
		"useCategories" => true,
		"perPage" => 5,
		"shortContentCharacters" => 250, // max characters for short content
		"calendarView" => "list", // month, list
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);