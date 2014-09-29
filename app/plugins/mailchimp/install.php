<?php
if($sPluginStatus == 1) {
	// Install
} else {
	// Uninstall
}

$aTables = array(
	"mailchimp" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			
			/* ADD PLUGIN FIELDS HERE */
			
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		)
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Newsletter",
	"menu" => array(
		array(
			"text" => "Campaigns",
			"link" => "/admin/mailchimp/campaigns/"
		),
		array(
			"text" => "Lists",
			"link" => "/admin/mailchimp/lists/"
		),
		array(
			"text" => "Reports",
			"link" => "/admin/mailchimp/reports/"
		)
	)
);