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
$urlPatterns = array(
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


$urlPatterns_admin = array(
	"/admin/" => array(
        "cmd" => "adminController",
        "action" => "index"
    ),
	"/admin/login/" => array(
        "cmd" => "adminController",
        "action" => "login"
    ),
	"/admin/logout/" => array(
        "cmd" => "adminController",
        "action" => "logout"
    ),
	"/admin/users/" => array(
        "cmd" => "admin_users",
        "action" => "index"
    ),
	"/admin/users/add/" => array(
        "cmd" => "admin_users",
        "action" => "add"
    ),
	"/admin/users/add/s/" => array(
        "cmd" => "admin_users",
        "action" => "add_s"
    ),
	"/admin/users/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_users",
        "action" => "edit"
    ),
	"/admin/users/edit/s/" => array(
        "cmd" => "admin_users",
        "action" => "edit_s"
    ),
	"/admin/users/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_users",
        "action" => "delete"
    ),
	"/admin/content/" => array(
        "cmd" => "admin_content",
        "action" => "index"
    ),
	"/admin/content/add/" => array(
        "cmd" => "admin_content",
        "action" => "add"
    ),
	"/admin/content/add/s/" => array(
        "cmd" => "admin_content",
        "action" => "add_s"
    ),
	"/admin/content/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_content",
        "action" => "edit"
    ),
	"/admin/content/edit/s/" => array(
        "cmd" => "admin_content",
        "action" => "edit_s"
    ),
	"/admin/content/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_content",
        "action" => "delete"
    )
);
###############################################