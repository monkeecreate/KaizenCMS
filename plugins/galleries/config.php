<?php
$aPluginInfo = array(
	/* Plugin Details */
	"name" => "Galleries",
	"version" => "1.0",
	"author" => "KaizenCMS",
	"website" => "http://monkee-create.com/",
	"email" => "hello@monkee-create.com",
	"description" => "Create photo galleries for your visitors to browse. You are able to create multiple galleries and upload any number of photos in each gallery. Features include batch uploading, drag and drop sorting, gallery details and photo captions.",

	/* Plugin Configuration */
	"config" => array(
		"imageFolder" => "/uploads/galleries/",
		"useCategories" => true,
		"perPage" => 5,
		"sortCategory" => "manual-asc" // manual, name, items, random - asc, desc
	)
);