<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Calendar",
	"version" => "1.0",
	"author" => "KaizenCMS",
	"website" => "http://monkee-create.com/",
	"email" => "hello@monkee-create.com",
	"description" => "A full featured calendar to manage your events. Includes publishing events to the website and auto creating the event on Facebook. Features include event details, start and end date, publish and unpublish date, social sharing, event image/logo, event location and google maps.",

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