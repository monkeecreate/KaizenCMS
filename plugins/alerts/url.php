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
    "/alerts/" => array(
		"cmd" => "alerts",
		"action" => "index"
	),
	"/alerts/<tag:[^/]+>/" => array(
		"cmd" => "alerts",
		"action" => "alert"
	),
	"/admin/alerts/" => array(
        "cmd" => "admin_alerts",
        "action" => "index"
    ),
	"/admin/alerts/add/" => array(
        "cmd" => "admin_alerts",
        "action" => "add"
    ),
	"/admin/alerts/add/s/" => array(
        "cmd" => "admin_alerts",
        "action" => "add_s"
    ),
	"/admin/alerts/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_alerts",
        "action" => "edit"
    ),
	"/admin/alerts/edit/s/" => array(
        "cmd" => "admin_alerts",
        "action" => "edit_s"
    ),
	"/admin/alerts/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_alerts",
        "action" => "delete"
    )
);
###############################################