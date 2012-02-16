<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Links",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	"description" => "Provide your visitor with a link of recommended or important links. Includes the ability to upload an image for each link, provide a description and assign to multiple categories for easy browsing by your visitor.",
	
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