<?php
$aUrlPatterns_import = array(
	"/admin/documents/" => array(
        "cmd" => "admin_documents",
        "action" => "index"
    ),
	"/admin/documents/add/" => array(
        "cmd" => "admin_documents",
        "action" => "add"
    ),
	"/admin/documents/add/s/" => array(
        "cmd" => "admin_documents",
        "action" => "add_s"
    ),
	"/admin/documents/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "edit"
    ),
	"/admin/documents/edit/s/" => array(
        "cmd" => "admin_documents",
        "action" => "edit_s"
    ),
	"/admin/documents/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "delete"
    ),
	"/admin/documents/categories/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_index"
    ),
	"/admin/documents/categories/add/s/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_add_s"
    ),
	"/admin/documents/categories/edit/s/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_edit_s"
    ),
	"/admin/documents/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_documents",
        "action" => "categories_delete"
    )
);