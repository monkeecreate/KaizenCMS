<?php
$sFolder = $this->settings->rootPublic."uploads/posts/";
if($sPluginStatus == 1) {
	// Install
	mkdir($sFolder);
} else {
	// Uninstall
	$this->deleteDir($sFolder);
}

$aTables = array(
	"posts" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"title" => array("type" => "text","length" => 255),
			"tag" => array("type" => "text","length" => 255),
			"excerpt" => array("type" => "clob"),
			"content" => array("type" => "clob"),
			"tags" => array("type" => "clob"),
			"publish_on" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"allow_comments" => array("type" => "boolean"),
			"allow_sharing" => array("type" => "boolean"),
			"sticky" => array("type" => "boolean"),
			"active" => array("type" => "boolean"),
			"authorid" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"views" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_x1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y1" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_x2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_y2" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_width" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"photo_height" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"created_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_datetime" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0),
			"updated_by" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"index" => array("sticky", "active"),
		"unique" => array("tag"),
		"fulltext" => array("title", "excerpt", "content", "tags"),
		"search" => array(
			"title" => "title",
			"content" => "content",
			"rows" => array("title", "excerpt", "content", "tags"),
			"filter" => "`active` = 1 AND `publish_on` < {time}"
		)
	),
	"posts_categories" => array(
		"fields" => array(
			"id" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0,
				"autoincrement" => 1
			),
			"name" => array("type" => "text","length" => 255),
			"sort_order" => array("type" => "integer","unsigned" => 1,"notnull" => 1,"default" => 0)
		),
		"unique" => array("sort_order")
	),
	"posts_categories_assign" => array(
		"fields" => array(
			"postid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			),
			"categoryid" => array(
				"type" => "integer",
				"unsigned" => 1,
				"notnull" => 1,
				"default" => 0
			)
		),
		"index" => array("postid", "categoryid")
	)
);

$aSettings = array();

$aMenuAdmin = array(
	"title" => "Posts",
	"menu" => array(
		array(
			"text" => "Posts",
			"link" => "/admin/posts/"
		),
		array(
			"text" => "Categories",
			"link" => "/admin/posts/categories/"
		)
	)
);