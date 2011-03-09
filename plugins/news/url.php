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
    "/news/" => array(
		"cmd" => "news",
		"action" => "index"
	),
	"/news/rss/" => array(
		"cmd" => "news",
		"action" => "rss"
	),
	"/news/<year:[0-9]+>/<month:[0-9]+>/<date:[0-9]+>/<tag:[^/]+>/" => array(
		"cmd" => "news",
		"action" => "article"
	),
	"/admin/news/" => array(
        "cmd" => "admin_news",
        "action" => "index"
    ),
	"/admin/news/add/" => array(
        "cmd" => "admin_news",
        "action" => "add"
    ),
	"/admin/news/add/s/" => array(
        "cmd" => "admin_news",
        "action" => "add_s"
    ),
	"/admin/news/edit/<id:[0-9]+>/" => array(
        "cmd" => "admin_news",
        "action" => "edit"
    ),
	"/admin/news/edit/s/" => array(
        "cmd" => "admin_news",
        "action" => "edit_s"
    ),
	"/admin/news/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_news",
        "action" => "delete"
    ),
	"/admin/news/image/<id:[0-9]+>/upload/" => array(
        "cmd" => "admin_news",
        "action" => "image_upload"
    ),
	"/admin/news/image/upload/s/" => array(
        "cmd" => "admin_news",
        "action" => "image_upload_s"
    ),
	"/admin/news/image/<id:[0-9]+>/edit/" => array(
        "cmd" => "admin_news",
        "action" => "image_edit"
    ),
	"/admin/news/image/edit/s/" => array(
        "cmd" => "admin_news",
        "action" => "image_edit_s"
    ),
	"/admin/news/image/<id:[0-9]+>/delete/" => array(
        "cmd" => "admin_news",
        "action" => "image_delete"
    ),
	"/admin/news/categories/" => array(
        "cmd" => "admin_news",
        "action" => "categories_index"
    ),
	"/admin/news/categories/add/s/" => array(
        "cmd" => "admin_news",
        "action" => "categories_add_s"
    ),
	"/admin/news/categories/edit/s/" => array(
        "cmd" => "admin_news",
        "action" => "categories_edit_s"
    ),
	"/admin/news/categories/delete/<id:[0-9]+>/" => array(
        "cmd" => "admin_news",
        "action" => "categories_delete"
    ),
	"/admin/news/categories/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
        "cmd" => "admin_news",
        "action" => "categories_sort"
    )
);
###############################################