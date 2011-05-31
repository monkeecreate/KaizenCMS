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
    "/BASE/" => array(
		"cmd" => "BASE",
		"action" => "index"
	),
	"/admin/BASE/" => array(
        "cmd" => "admin_BASE",
        "action" => "index"
    )
);
###############################################