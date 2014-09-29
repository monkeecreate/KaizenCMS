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
    "/posts/" => array(
		"cmd" => "posts",
		"action" => "index"
	),
	"/posts/rss/" => array(
		"cmd" => "posts",
		"action" => "rss"
	),
	"/posts/<year:[0-9]+>/<month:[0-9]+>/<date:[0-9]+>/<tag:[^/]+>/" => array(
		"cmd" => "posts",
		"action" => "post"
	),
	"/admin/posts/" => array(
        "cmd" => "admin_posts",
        "action" => "index"
    ),
	"/admin/posts/add/" => array(
        "cmd" => "admin_posts",
        "action" => "add"
    ),
	"/admin/posts/add/s/" => array(
        "cmd" => "admin_posts",
        "action" => "add_s"
    ),
	"/admin/posts/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_posts",
        "action" => "edit"
    ),
	"/admin/posts/edit/s/" => array(
        "cmd" => "admin_posts",
        "action" => "edit_s"
    ),
	"/admin/posts/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_posts",
        "action" => "delete"
    ),
	"/admin/posts/image/<id:[0-9]+>/upload/" => array(
        "cmd" => "admin_posts",
        "action" => "image_upload"
    ),
	"/admin/posts/image/upload/s/" => array(
        "cmd" => "admin_posts",
        "action" => "image_upload_s"
    ),
	"/admin/posts/image/<id:[0-9]+>/edit/" => array(
        "cmd" => "admin_posts",
        "action" => "image_edit"
    ),
	"/admin/posts/image/edit/s/" => array(
        "cmd" => "admin_posts",
        "action" => "image_edit_s"
    ),
	"/admin/posts/image/<id:[0-9]+>/delete/" => array(
        "cmd" => "admin_posts",
        "action" => "image_delete"
    ),
	"/admin/posts/categories/" => array(
        "cmd" => "admin_posts",
        "action" => "categories_index"
    ),
	"/admin/posts/categories/add/s/" => array(
        "cmd" => "admin_posts",
        "action" => "categories_add_s"
    ),
	"/admin/posts/categories/edit/s/" => array(
        "cmd" => "admin_posts",
        "action" => "categories_edit_s"
    ),
	"/admin/posts/categories/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_posts",
        "action" => "categories_delete"
    ),
	"/admin/posts/categories/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
        "cmd" => "admin_posts",
        "action" => "categories_sort"
    )
);
###############################################