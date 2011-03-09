<?php
# Custom URL using mod_rewrite

### Url Pattern ###############################
/*
 # Function Variable Order:
 #   1. URL parameters (<name:[a-z]+>)
 #   2. Pattern parameters
 #
 # Example URL Patterns:
 #   /page/<name:[a-z0-9]+>/
 #   /<tag:[a-z]+>/
*/
$aPluginUrlPatterns = array(
	"/admin/mailchimp/" => array(
        "cmd" => "admin_mailchimp",
        "action" => "campaigns_index"
    ),
	"/admin/mailchimp/campaigns/" => array(
        "cmd" => "admin_mailchimp",
        "action" => "campaigns_index"
    ),
	"/admin/mailchimp/lists/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_index"
	),
	"/admin/mailchimp/lists/<id:[^/]+>/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_show"
	),
	"/admin/mailchimp/lists/<id:[^/]+>/members/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_members"
	),
	"/admin/mailchimp/lists/<id:[^/]+>/members/load/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_load_members"
	),
	"/admin/mailchimp/lists/<id:[^/]+>/member/<email:[^/]+>/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_member"
	),
	"/admin/mailchimp/lists/<id:[^/]+>/members/s/" => array(
	    "cmd" => "admin_mailchimp",
	    "action" => "lists_member_s"
	)
);
###############################################