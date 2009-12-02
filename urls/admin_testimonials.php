<?php
$aUrlPatterns_import = array(
	"/admin/testimonials/" => array(
        "cmd" => "admin_testimonials",
        "action" => "index"
    ),
	"/admin/testimonials/add/" => array(
        "cmd" => "admin_testimonials",
        "action" => "add"
    ),
	"/admin/testimonials/add/s/" => array(
        "cmd" => "admin_testimonials",
        "action" => "add_s"
    ),
	"/admin/testimonials/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_testimonials",
        "action" => "edit"
    ),
	"/admin/testimonials/edit/s/" => array(
        "cmd" => "admin_testimonials",
        "action" => "edit_s"
    ),
	"/admin/testimonials/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_testimonials",
        "action" => "delete"
    ),
	"/admin/testimonials/categories/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_index"
    ),
	"/admin/testimonials/categories/add/s/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_add_s"
    ),
	"/admin/testimonials/categories/edit/s/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_edit_s"
    ),
	"/admin/testimonials/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_delete"
    )
);