<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Posts",
	"version" => "1.0",
	"author" => "KaizenCMS",
	"website" => "http://monkee-create.com/",
	"email" => "hello@monkee-create.com",
	"description" => "Keep your visitors up to date with your latest happenings. Includes the ability to schedule posts to publish and unpublish automatically, upload an image for each post, assign to multiple categories, a full editor along with short description and social sharing to auto post to Facebook and Twitter.",

	/* Plugin Configuration */
	"config" => array(
		"useImage" => true,
		"imageMinWidth" => 200,
		"imageMinHeight" => 100,
		"imageFolder" => "/uploads/posts/",
		"useCategories" => true,
		"perPage" => 10,
		"useComments" => true,
		"excerptCharacters" => 250, // character limit for excerpt
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);