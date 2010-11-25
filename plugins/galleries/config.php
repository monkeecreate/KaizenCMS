<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Galleries",
	"version" => "1.1",
	"author" => "monkeeCreate",
	"website" => "http://monkeecreate.com/",
	"email" => "support@monkeecreate.com",
	
	/* Plugin Configuration */
	"config" => array(
		"imageFolder" => "/uploads/galleries/",
		"useCategories" => true,
		"perPage" => 5,
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);