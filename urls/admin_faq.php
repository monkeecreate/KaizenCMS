<?php
$aUrlPatterns_import = array(
	"/admin/faq/" => array(
        "cmd" => "admin_faq",
        "action" => "index"
    ),
	"/admin/faq/add/" => array(
        "cmd" => "admin_faq",
        "action" => "add"
    ),
	"/admin/faq/add/s/" => array(
        "cmd" => "admin_faq",
        "action" => "add_s"
    ),
	"/admin/faq/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "sort"
    ),
	"/admin/faq/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "edit"
    ),
	"/admin/faq/edit/s/" => array(
        "cmd" => "admin_faq",
        "action" => "edit_s"
    ),
	"/admin/faq/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "delete"
    ),
	"/admin/faq/categories/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_index"
    ),
	"/admin/faq/categories/add/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_add"
    ),
	"/admin/faq/categories/add/s/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_add_s"
    ),
	"/admin/faq/categories/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_edit"
    ),
	"/admin/faq/categories/edit/s/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_edit_s"
    ),
	"/admin/faq/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_faq",
        "action" => "categories_delete"
    )
);