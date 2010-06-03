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
    "/calendar/" => array(
		"cmd" => "calendar",
		"action" => "index"
	),
	"/calendar/ics/" => array(
		"cmd" => "calendar",
		"action" => "ics"
	),
	"/calendar/{id:[0-9]+}/{title:[^/]+}/" => array(
		"cmd" => "calendar",
		"action" => "event"
	),
	"/calendar/{id:[0-9]+}/{title:[^/]+}/ics/" => array(
		"cmd" => "calendar",
		"action" => "event_ics"
	),
	"/admin/calendar/" => array(
        "cmd" => "admin_calendar",
        "action" => "index"
    ),
	"/admin/calendar/add/" => array(
        "cmd" => "admin_calendar",
        "action" => "add"
    ),
	"/admin/calendar/add/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "add_s"
    ),
	"/admin/calendar/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_calendar",
        "action" => "edit"
    ),
	"/admin/calendar/edit/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "edit_s"
    ),
	"/admin/calendar/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_calendar",
        "action" => "delete"
    ),
	"/admin/calendar/image/{id:[0-9]+}/upload/" => array(
        "cmd" => "admin_calendar",
        "action" => "image_upload"
    ),
	"/admin/calendar/image/upload/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "image_upload_s"
    ),
	"/admin/calendar/image/{id:[0-9]+}/edit/" => array(
        "cmd" => "admin_calendar",
        "action" => "image_edit"
    ),
	"/admin/calendar/image/edit/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "image_edit_s"
    ),
	"/admin/calendar/image/{id:[0-9]+}/delete/" => array(
        "cmd" => "admin_calendar",
        "action" => "image_delete"
    ),
	"/admin/calendar/categories/" => array(
        "cmd" => "admin_calendar",
        "action" => "categories_index"
    ),
	"/admin/calendar/categories/add/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "categories_add_s"
    ),
	"/admin/calendar/categories/edit/s/" => array(
        "cmd" => "admin_calendar",
        "action" => "categories_edit_s"
    ),
	"/admin/calendar/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_calendar",
        "action" => "categories_delete"
    )
);
###############################################