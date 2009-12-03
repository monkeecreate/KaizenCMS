<?php
$aUrlPatterns_import = array(
	"/admin/links/" => array(
        "cmd" => "admin_links",
        "action" => "index"
    ),
	"/admin/links/add/" => array(
        "cmd" => "admin_links",
        "action" => "add"
    ),
	"/admin/links/add/s/" => array(
        "cmd" => "admin_links",
        "action" => "add_s"
    ),
	"/admin/links/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_links",
        "action" => "edit"
    ),
	"/admin/links/edit/s/" => array(
        "cmd" => "admin_links",
        "action" => "edit_s"
    ),
	"/admin/links/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_links",
        "action" => "delete"
    ),
	"/admin/links/categories/" => array(
        "cmd" => "admin_links",
        "action" => "categories_index"
    ),
	"/admin/links/categories/add/s/" => array(
        "cmd" => "admin_links",
        "action" => "categories_add_s"
    ),
	"/admin/links/categories/edit/s/" => array(
        "cmd" => "admin_links",
        "action" => "categories_edit_s"
    ),
	"/admin/links/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_links",
        "action" => "categories_delete"
    )
);