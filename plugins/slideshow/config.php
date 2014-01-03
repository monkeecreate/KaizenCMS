<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Slideshow",
	"version" => "1.0",
	"author" => "KaizenCMS",
	"website" => "http://monkee-create.com/",
	"email" => "hello@monkee-create.com",
	"description" => "Keep your homepage dynamic with this slideshow plugin. Allows you to upload any number of photos that will animate/rotate through a slideshow on your website.",

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