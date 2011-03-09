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
    "/promos/<id:[0-9]+>/" => array(
		"cmd" => "promos",
		"action" => "index"
	),
	"/admin/promos/" => array(
        "cmd" => "admin_promos",
        "action" => "index"
    ),
	"/admin/promos/add/" => array(
        "cmd" => "admin_promos",
        "action" => "add"
    ),
	"/admin/promos/add/s/" => array(
        "cmd" => "admin_promos",
        "action" => "add_s"
    ),
	"/admin/promos/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_promos",
        "action" => "edit"
    ),
	"/admin/promos/edit/s/" => array(
        "cmd" => "admin_promos",
        "action" => "edit_s"
    ),
	"/admin/promos/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_promos",
        "action" => "delete"
    ),
	"/admin/promos/positions/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_index"
	),
	"/admin/promos/positions/add/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_add"
	),
	"/admin/promos/positions/add/s/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_add_s"
	),
	"/admin/promos/positions/edit/<id:[0-9]+>/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_edit"
	),
	"/admin/promos/positions/edit/s/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_edit_s"
	),
	"/admin/promos/positions/delete/<id:[0-9]+>/" => array(
	    "cmd" => "admin_promos",
	    "action" => "positions_delete"
	)
);
###############################################