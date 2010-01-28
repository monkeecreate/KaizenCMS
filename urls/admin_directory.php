<?php
$aUrlPatterns_import = array(
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