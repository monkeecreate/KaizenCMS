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
  "/banners/<id:[0-9]+>/" => array(
		"cmd" => "banners",
		"action" => "index"
	),
	"/admin/banners/" => array(
        "cmd" => "admin_banners",
        "action" => "index"
    ),
	"/admin/banners/add/" => array(
        "cmd" => "admin_banners",
        "action" => "add"
    ),
	"/admin/banners/add/s/" => array(
        "cmd" => "admin_banners",
        "action" => "add_s"
    ),
	"/admin/banners/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_banners",
        "action" => "edit"
    ),
	"/admin/banners/edit/s/" => array(
        "cmd" => "admin_banners",
        "action" => "edit_s"
    ),
	"/admin/banners/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_banners",
        "action" => "delete"
    ),
	"/admin/banners/positions/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_index"
	),
	"/admin/banners/positions/add/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_add"
	),
	"/admin/banners/positions/add/s/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_add_s"
	),
	"/admin/banners/positions/edit/<id:[0-9]+>/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_edit"
	),
	"/admin/banners/positions/edit/s/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_edit_s"
	),
	"/admin/banners/positions/delete/<id:[0-9]+>/" => array(
	    "cmd" => "admin_banners",
	    "action" => "positions_delete"
	)
);
###############################################