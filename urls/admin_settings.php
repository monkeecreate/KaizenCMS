<?php
$aUrlPatterns_import = array(
	"/admin/settings/" => array(
        "cmd" => "admin_settings",
        "action" => "index"
    ),
	"/admin/settings/save/" => array(
        "cmd" => "admin_settings",
        "action" => "save"
    ),
	"/admin/settings/manage/" => array(
        "cmd" => "admin_settings",
        "action" => "manageIndex"
    ),
	"/admin/settings/manage/add/" => array(
        "cmd" => "admin_settings",
        "action" => "manageAdd"
    ),
	"/admin/settings/manage/add/s/" => array(
        "cmd" => "admin_settings",
        "action" => "manageAdd_s"
    ),
	"/admin/settings/manage/edit/{id:[0-9]+}/" => array(
        "cmd" => "admin_settings",
        "action" => "manageEdit"
    ),
	"/admin/settings/manage/edit/s/" => array(
        "cmd" => "admin_settings",
        "action" => "manageEdit_s"
    ),
	"/admin/settings/manage/delete/{id:[0-9]+}/" => array(
        "cmd" => "admin_settings",
        "action" => "manageDelete"
    )
);