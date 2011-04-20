<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Galleries",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	
	/* Plugin Configuration */
	"config" => array(
		"imageFolder" => "/uploads/galleries/",
		"useCategories" => true,
		"perPage" => 5,
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);