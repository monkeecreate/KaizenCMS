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
    "/services/" => array(
		"cmd" => "services",
		"action" => "index"
	),
	"/services/<tag:[^/]+>/" => array(
		"cmd" => "services",
		"action" => "service"
	),
	"/admin/services/" => array(
        "cmd" => "admin_services",
        "action" => "index"
    ),
	"/admin/services/add/" => array(
        "cmd" => "admin_services",
        "action" => "add"
    ),
	"/admin/services/add/s/" => array(
        "cmd" => "admin_services",
        "action" => "add_s"
    ),
	"/admin/services/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_services",
        "action" => "edit"
    ),
	"/admin/services/edit/s/" => array(
        "cmd" => "admin_services",
        "action" => "edit_s"
    ),
	"/admin/services/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_services",
        "action" => "delete"
    ),
	"/admin/services/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
        "cmd" => "admin_services",
        "action" => "sort"
    ),
    "/admin/services/image/<id:[0-9]+>/upload/" => array(
        "cmd" => "admin_services",
        "action" => "image_upload"
    ),
    "/admin/services/image/upload/s/" => array(
        "cmd" => "admin_services",
        "action" => "image_upload_s"
    ),
    "/admin/services/image/<id:[0-9]+>/edit/" => array(
        "cmd" => "admin_services",
        "action" => "image_edit"
    ),
    "/admin/services/image/edit/s/" => array(
        "cmd" => "admin_services",
        "action" => "image_edit_s"
    ),
    "/admin/services/image/<id:[0-9]+>/delete/" => array(
        "cmd" => "admin_services",
        "action" => "image_delete"
    )
);
###############################################