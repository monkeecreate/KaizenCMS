<?php
$aUrlPatterns_import = array(
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