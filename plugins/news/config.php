<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "News",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	"description" => "Keep your visitors up to date with your latest news. Includes the ability to schedule news to publish and unpublish automatically, upload an image for each article, assign to multiple categories, a full editor along with short description and social sharing to auto post to Facebook and Twitter.",
	
	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 140,
		"imageMinHeight" => 87,
		"imageFolder" => "/uploads/news/",
		"useCategories" => true,
		"perPage" => 5,
		"shortContentCharacters" => 250, // max characters for short content
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);