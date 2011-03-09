<?php
# Custom URL using mod_rewrite

### Url Pattern ###############################
/*
 # Function Variable Order:
 #   1. URL parameters (<name:[a-z]+>)
 #   2. Pattern parameters
 #
 # Example URL Patterns:
 #   /page/<name:[a-z0-9]+>/
 #   /<tag:[a-z]+>/
*/
$aPluginUrlPatterns = array(
    "/testimonials/" => array(
		"cmd" => "testimonials",
		"action" => "index"
	),
	"/testimonials/<tag:[^/]+>/" => array(
		"cmd" => "testimonials",
		"action" => "testimonial"
	),
	"/testimonials/<id:[0-9]+>/" => array(
		"cmd" => "testimonials",
		"action" => "index"
	),
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
	"/admin/testimonials/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_testimonials",
        "action" => "edit"
    ),
	"/admin/testimonials/edit/s/" => array(
        "cmd" => "admin_testimonials",
        "action" => "edit_s"
    ),
	"/admin/testimonials/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_testimonials",
        "action" => "delete"
    ),
	"/admin/testimonials/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
        "cmd" => "admin_testimonials",
        "action" => "sort"
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
	"/admin/testimonials/categories/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_delete"
    ),
	"/admin/testimonials/categories/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
        "cmd" => "admin_testimonials",
        "action" => "categories_sort"
    )
);
###############################################