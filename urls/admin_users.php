<?php
$aUrlPatterns_import = array(
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
    )
);