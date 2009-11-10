<?php
$aUrlPatterns_import = array(
	"/admin/promos/" => array(
        "cmd" => "admin_promos",
        "action" => "index"
    ),
	"/admin/promos/add/" => array(
        "cmd" => "admin_promos",
        "action" => "add"
    ),
	"/admin/promos/add/s/" => array(
        "cmd" => "admin_promos",
        "action" => "add_s"
    ),
	"/admin/promos/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_promos",
        "action" => "edit"
    ),
	"/admin/promos/edit/s/" => array(
        "cmd" => "admin_promos",
        "action" => "edit_s"
    ),
	"/admin/promos/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_promos",
        "action" => "delete"
    )
);