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
	"/admin/slideshow/" => array(
        "cmd" => "admin_slideshow",
        "action" => "index"
    ),
	"/admin/slideshow/add/" => array(
        "cmd" => "admin_slideshow",
        "action" => "add"
    ),
	"/admin/slideshow/add/s/" => array(
        "cmd" => "admin_slideshow",
        "action" => "add_s"
    ),
	"/admin/slideshow/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_slideshow",
        "action" => "edit"
    ),
	"/admin/slideshow/edit/s/" => array(
        "cmd" => "admin_slideshow",
        "action" => "edit_s"
    ),
	"/admin/slideshow/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_slideshow",
        "action" => "delete"
    ),
	"/admin/slideshow/image/{id:[0-9]+}/upload/" => array(
        "cmd" => "admin_slideshow",
        "action" => "image_upload"
    ),
	"/admin/slideshow/image/upload/s/" => array(
        "cmd" => "admin_slideshow",
        "action" => "image_upload_s"
    ),
	"/admin/slideshow/image/{id:[0-9]+}/edit/" => array(
        "cmd" => "admin_slideshow",
        "action" => "image_edit"
    ),
	"/admin/slideshow/image/edit/s/" => array(
        "cmd" => "admin_slideshow",
        "action" => "image_edit_s"
    )
);
###############################################