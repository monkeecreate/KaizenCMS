<?php
$aUrlPatterns_import = array(
	"/admin/galleries/" => array(
        "cmd" => "admin_galleries",
        "action" => "index"
    ),
	"/admin/galleries/add/" => array(
        "cmd" => "admin_galleries",
        "action" => "add"
    ),
	"/admin/galleries/add/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "add_s"
    ),
	"/admin/galleries/sort/{id:[0-9]+}/{sort:[a-z]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "sort"
    ),
	"/admin/galleries/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "edit"
    ),
	"/admin/galleries/edit/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "edit_s"
    ),
	"/admin/galleries/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "delete"
    ),
	"/admin/galleries/categories/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_index"
    ),
	"/admin/galleries/categories/add/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_add_s"
    ),
	"/admin/galleries/categories/edit/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_edit_s"
    ),
	"/admin/galleries/categories/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "categories_delete"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_index"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/add/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_add"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/add/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_add_s"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/sort/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_sort"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/default/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_default"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_edit"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/edit/s/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_edit_s"
    ),
	"/admin/galleries/{gallery:[0-9]+}/photos/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_galleries",
        "action" => "photos_delete"
    )
);