<?php
# Custom URL .htaccess mod_rewrite

# ORDER OF URL OPERATIONS:
# 1: Url Pattern ($urlPatterns)
# (dead)2: Url Controls ($aUrl)
# (dead)3: Home (/)
# (dead)4: Url Input (/$cmd/$action/)

### Url Pattern ###############################
/*
 # Function Variable Order:
 #   1. URL parameters ({name:[a-z]+})
 #   2. Pattern parameters
 #
 # Example URL Patterns:
 #   /page/{name:[a-z0-9]+}/
 #   /{tag:[a-z]+}/
*/
$urlPatterns = array(
    "/" => array(
        "cmd" => "content",
        "action" => "index"
    ),
	"/info/" => array(
		"cmd" => "content",
		"action" => "siteinfo"
	),
	"/listings/search/" => array(
		"cmd" => "listings",
		"action" => "search"
	),
	"/listings/profile/{id:[0-9]+}/" => array(
		"cmd" => "listings",
		"action" => "profile"
	),
	"/image/profile/{id:[0-9]+}/" => array(
		"cmd" => "image",
		"action" => "profile"
	),
	"/{page:[a-z0-9]+}/" => array(
		"cmd" => "content",
		"action" => "view"
	)
);
###############################################