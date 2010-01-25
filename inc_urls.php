<?php
# Custom URL using mod_rewrite

### Url Pattern ###############################
/*
 # Function Variable Order:
 #   1. URL parameters ({name:[a-z]+})
 #   2. Pattern parameters
 #
 # Example URL Patterns:
 #   /page/{name:[a-z0-9]+}/
 #   /{tag:[a-z]+}/
*/
$aUrlPatterns = array(
    "/" => array(
        "cmd" => "content",
        "action" => "index"
    ),
	"/info/" => array(
		"cmd" => "content",
		"action" => "info"
	),
	"/sendform/" => array(
		"cmd" => "content",
		"action" => "form_submit"
	),
	"/news/" => array(
		"cmd" => "news",
		"action" => "index"
	),
	"/news/rss/" => array(
		"cmd" => "news",
		"action" => "rss"
	),
	"/news/{id:[0-9]+}/{title:[^/]+}/" => array(
		"cmd" => "news",
		"action" => "article"
	),
	"/events/" => array(
		"cmd" => "events",
		"action" => "index"
	),
	"/events/{id:[0-9]+}/{title:[^/]+}/" => array(
		"cmd" => "events",
		"action" => "event"
	),
	"/calendar/" => array(
		"cmd" => "calendar",
		"action" => "index"
	),
	"/calendar/ics/" => array(
		"cmd" => "calendar",
		"action" => "ics"
	),
	"/calendar/{id:[0-9]+}/{title:[^/]+}/" => array(
		"cmd" => "calendar",
		"action" => "event"
	),
	"/calendar/{id:[0-9]+}/{title:[^/]+}/ics/" => array(
		"cmd" => "calendar",
		"action" => "event_ics"
	),
	"/faq/" => array(
		"cmd" => "faq",
		"action" => "index"
	),
	"/testimonials/" => array(
		"cmd" => "testimonials",
		"action" => "index"
	),
	"/testimonials/{id:[0-9]+}/" => array(
		"cmd" => "testimonials",
		"action" => "index"
	),
	"/documents/" => array(
		"cmd" => "documents",
		"action" => "index"
	),
	"/links/" => array(
		"cmd" => "links",
		"action" => "index"
	),
	"/galleries/" => array(
		"cmd" => "galleries",
		"action" => "index"
	),
	"/galleries/{id:[0-9]+}/" => array(
		"cmd" => "galleries",
		"action" => "gallery"
	),
	"/image/resize/" => array(
		"cmd" => "image",
		"action" => "resize"
	),
	"/image/{model:[a-z]+}/{id:[0-9]+}/" => array(
		"cmd" => "image",
		"action" => "itemImage"
	),
	"/promos/{id:[0-9]+}/" => array(
		"cmd" => "content",
		"action" => "promo"
	),
	"/{page:[a-z0-9_-]+}/" => array(
		"cmd" => "content",
		"action" => "view"
	)
);
###############################################