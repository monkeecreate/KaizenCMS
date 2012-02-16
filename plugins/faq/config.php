<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "FAQ",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	"description" => "Give your visitors the perfect place to find answers to their questions. The FAQ plugin gives you the ability to create unlimited questions and assign them to categories for easy sorting by the visitor.",
	
	/* Plugin Configuration */
	"config" => array(
		"useCategories" => true,
		"perPage" => 5,
		"sort" => "manual-asc", // manual, question, created, updated, random - asc, desc
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);