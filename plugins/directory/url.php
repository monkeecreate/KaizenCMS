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
    "/directory/" => array(
		"cmd" => "directory_",
		"action" => "index"
	),
	"/admin/directory/" => array(
        "cmd" => "admin_directory",
        "action" => "index"
    ),
	"/admin/directory/add/" => array(
        "cmd" => "admin_directory",
        "action" => "add"
    ),
	"/admin/directory/add/s/" => array(
        "cmd" => "admin_directory",
        "action" => "add_s"
    ),
	"/admin/directory/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_directory",
        "action" => "edit"
    ),
	"/admin/directory/edit/s/" => array(
        "cmd" => "admin_directory",
        "action" => "edit_s"
    ),
	"/admin/directory/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_directory",
        "action" => "delete"
    ),
	"/admin/directory/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_directory",
        "action" => "sort"
    ),
	"/admin/directory/image/{id:[0-9]+}/upload/" => array(
        "cmd" => "admin_directory",
        "action" => "image_upload"
    ),
	"/admin/directory/image/upload/s/" => array(
        "cmd" => "admin_directory",
        "action" => "image_upload_s"
    ),
	"/admin/directory/image/{id:[0-9]+}/edit/" => array(
        "cmd" => "admin_directory",
        "action" => "image_edit"
    ),
	"/admin/directory/image/edit/s/" => array(
        "cmd" => "admin_directory",
        "action" => "image_edit_s"
    ),
	"/admin/directory/image/{id:[0-9]+}/delete/" => array(
        "cmd" => "admin_directory",
        "action" => "image_delete"
    ),
	"/admin/directory/categories/" => array(
        "cmd" => "admin_directory",
        "action" => "categories_index"
    ),
	"/admin/directory/categories/add/s/" => array(
        "cmd" => "admin_directory",
        "action" => "categories_add_s"
    ),
	"/admin/directory/categories/edit/s/" => array(
        "cmd" => "admin_directory",
        "action" => "categories_edit_s"
    ),
	"/admin/directory/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_directory",
        "action" => "categories_delete"
    )
);
###############################################