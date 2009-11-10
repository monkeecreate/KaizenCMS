<?php
# Custom URL using mod_rewrite

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
$aUrlPatterns = array(
    "/" => array(
        "cmd" => "content",
        "action" => "index"
    ),
	"/info/" => array(
		"cmd" => "content",
		"action" => "siteinfo"
	),
	"/contact/" => array(
		"cmd" => "content",
		"action" => "contact"
	),
	"/sendform/" => array(
		"cmd" => "content",
		"action" => "form_submit"
	),
	"/image/resize/" => array(
		"cmd" => "image",
		"action" => "resize"
	),
	"/{page:[a-z0-9_-]+}/" => array(
		"cmd" => "content",
		"action" => "view"
	)
);
###############################################