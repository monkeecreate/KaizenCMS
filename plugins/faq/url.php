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
$aPluginUrlPatterns = array(
    "/faq/" => array(
		"cmd" => "faq",
		"action" => "index"
	),
	"/faq/{tag:[^/]+}/" => array(
		"cmd" => "faq",
		"action" => "question"
	),
	"/admin/faq/" => array(
        "cmd" => "admin_faq",
        "action" => "index"
    ),
	"/admin/faq/add/" => array(
        "cmd" => "admin_faq",
        "action" => "add"
    ),
	"/admin/faq/add/s/" => array(
        "cmd" => "admin_faq",
        "action" => "add_s"
    ),
	"/admin/faq/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "edit"
    ),
	"/admin/faq/edit/s/" => array(
        "cmd" => "admin_faq",
        "action" => "edit_s"
    ),
	"/admin/faq/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "delete"
    ),
	"/admin/faq/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "sort"
    ),
	"/admin/faq/categories/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_index"
    ),
	"/admin/faq/categories/add/s/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_add_s"
    ),
	"/admin/faq/categories/edit/s/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_edit_s"
    ),
	"/admin/faq/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_delete"
    )
);
###############################################