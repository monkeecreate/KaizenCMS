<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Documents",
	"version" => "1.1",
	"author" => "monkeeCreate",
	"website" => "http://monkeecreate.com/",
	"email" => "support@monkeecreate.com",
	
	/* Plugin Configuration */
	"config" => array(
		"allowedExt" => array(), //array("pdf","doc");
		"documentFolder" => "/uploads/documents/",
		"useCategories" => true,
		"perPage" => 5,
		"sort" => "manual-asc", // manual, name, created, updated, random - asc, desc
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);