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
    "/galleries/" => array(
		"cmd" => "galleries",
		"action" => "index"
	),
	"/galleries/{tag:[^/]+}/" => array(
		"cmd" => "galleries",
		"action" => "gallery"
	),
	"/admin/galleries/" => array(
        "cmd" => "admin_galleries",
        "action" => "index"
    ),
	"/admin/galleries/add/" => array(
        "cmd" => "admin_galleries",
        "action" => "add"
    ),
	"/admin/galleries/add/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "add_s"
    ),
	"/admin/galleries/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "sort"
    ),
	"/admin/galleries/edit/" => array(
        "cmd" => "admin_galleries",
        "action" => "edit_s"
    ),
	"/admin/galleries/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "delete"
    ),
	"/admin/galleries/categories/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_index"
    ),
	"/admin/galleries/categories/add/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_add_s"
    ),
	"/admin/galleries/categories/edit/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_edit_s"
    ),
	"/admin/galleries/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_delete"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_index"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/add/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_add"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/manage/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_manage"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/manage/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_manage_s"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/edit/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_edit"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_delete"
    )
);
###############################################