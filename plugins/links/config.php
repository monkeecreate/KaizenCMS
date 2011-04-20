<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Links",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 140,
		"imageMinHeight" => 87,
		"imageFolder" => "/uploads/links/",
		"useCategories" => true,
		"perPage" => 5,
		"sort" => "name-asc", // manual, name, created, updated, random - asc, desc
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);