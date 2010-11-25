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
    "/documents/" => array(
		"cmd" => "documents",
		"action" => "index"
	),
	"/documents/{tag:[^/]+}/" => array(
		"cmd" => "documents",
		"action" => "document"
	),
	"/admin/documents/" => array(
        "cmd" => "admin_documents",
        "action" => "index"
    ),
	"/admin/documents/add/" => array(
        "cmd" => "admin_documents",
        "action" => "add"
    ),
	"/admin/documents/add/s/" => array(
        "cmd" => "admin_documents",
        "action" => "add_s"
    ),
	"/admin/documents/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "edit"
    ),
	"/admin/documents/edit/s/" => array(
        "cmd" => "admin_documents",
        "action" => "edit_s"
    ),
	"/admin/documents/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "delete"
    ),
	"/admin/documents/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "sort"
    ),
	"/admin/documents/categories/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_index"
    ),
	"/admin/documents/categories/add/s/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_add_s"
    ),
	"/admin/documents/categories/edit/s/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_edit_s"
    ),
	"/admin/documents/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_delete"
    ),
	"/admin/documents/categories/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_sort"
    )
);
###############################################