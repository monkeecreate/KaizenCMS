<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Testimonials",
	"version" => "1.0",
	"author" => "KaizenCMS",
	"website" => "http://monkee-create.com/",
	"email" => "hello@monkee-create.com",
	"description" => "Share your visitors experiences on your website with this plugin. Allows you to provide a name and text testimonial that will display on your website.",

	/* Plugin Configuration */
	"config" => array(
		"useCategories" => true,
		"sort" => "name-asc", // manual, name, subname, created, updated, random - asc, desc
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);