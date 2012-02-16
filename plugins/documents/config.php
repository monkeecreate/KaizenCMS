<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Documents",
	"version" => "1.0",
	"author" => "Crane | West",
	"website" => "http://crane-west.com/",
	"email" => "support@crane-west.com",
	"description" => "Make documents available to your visitors for download. Ability to upload any document type, provide document description and view download stats.",
	
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