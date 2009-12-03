<?php
$aUrlPatterns_import = array(
	"/admin/events/" => array(
        "cmd" => "admin_events",
        "action" => "index"
    ),
	"/admin/events/add/" => array(
        "cmd" => "admin_events",
        "action" => "add"
    ),
	"/admin/events/add/s/" => array(
        "cmd" => "admin_events",
        "action" => "add_s"
    ),
	"/admin/events/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_events",
        "action" => "edit"
    ),
	"/admin/events/edit/s/" => array(
        "cmd" => "admin_events",
        "action" => "edit_s"
    ),
	"/admin/events/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_events",
        "action" => "delete"
    ),
	"/admin/events/image/{id:[0-9]+}/upload/" => array(
        "cmd" => "admin_events",
        "action" => "image_upload"
    ),
	"/admin/events/image/upload/s/" => array(
        "cmd" => "admin_events",
        "action" => "image_upload_s"
    ),
	"/admin/events/image/{id:[0-9]+}/edit/" => array(
        "cmd" => "admin_events",
        "action" => "image_edit"
    ),
	"/admin/events/image/edit/s/" => array(
        "cmd" => "admin_events",
        "action" => "image_edit_s"
    ),
	"/admin/events/image/{id:[0-9]+}/delete/" => array(
        "cmd" => "admin_events",
        "action" => "image_delete"
    ),
	"/admin/events/categories/" => array(
        "cmd" => "admin_events",
        "action" => "categories_index"
    ),
	"/admin/events/categories/add/s/" => array(
        "cmd" => "admin_events",
        "action" => "categories_add_s"
    ),
	"/admin/events/categories/edit/s/" => array(
        "cmd" => "admin_events",
        "action" => "categories_edit_s"
    ),
	"/admin/events/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_events",
        "action" => "categories_delete"
    )
);