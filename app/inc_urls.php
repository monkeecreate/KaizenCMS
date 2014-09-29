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
	"/search/" => array(
		"cmd" => "content",
		"action" => "search"
	),
	"/sendform/" => array(
		"cmd" => "content",
		"action" => "form_submit"
	),
	"/mailchimp-subscribe/" => array(
		"cmd" => "content",
		"action" => "mailChimpSubscribe"
	),
	"/image/resize/" => array(
		"cmd" => "image",
		"action" => "resize"
	),
	"/image/crop/" => array(
		"cmd" => "image",
		"action" => "crop"
	),
	"/image/<model:[a-z]+>/<id:[0-9]+>/" => array(
		"cmd" => "image",
		"action" => "itemImage"
	),
	"/admin/" => array(
		"cmd" => "adminController",
		"action" => "index"
	),
	"/admin/login/" => array(
		"cmd" => "adminController",
		"action" => "login"
	),
	"/admin/isloggedin/" => array(
		"cmd" => "adminController",
		"action" => "isloggedin"
	),
	"/admin/logout/" => array(
		"cmd" => "adminController",
		"action" => "logout"
	),
	"/admin/passwordReset/" => array(
		"cmd" => "adminController",
		"action" => "passwordReset"
	),
	"/admin/passwordReset/<code:[a-z0-9]+>/" => array(
		"cmd" => "adminController",
		"action" => "passwordReset_code"
	),
	"/admin/passwordReset/<code:[a-z0-9]+>/s/" => array(
		"cmd" => "adminController",
		"action" => "passwordReset_code_s"
	),
	"/admin/content/" => array(
		"cmd" => "admin_content",
		"action" => "index"
	),
	"/admin/content/add/" => array(
		"cmd" => "admin_content",
		"action" => "add"
	),
	"/admin/content/add/s/" => array(
		"cmd" => "admin_content",
		"action" => "add_s"
	),
	"/admin/content/edit/<id:[0-9]+>/" => array(
		"cmd" => "admin_content",
		"action" => "edit"
	),
	"/admin/content/edit/s/" => array(
		"cmd" => "admin_content",
		"action" => "edit_s"
	),
	"/admin/content/delete/<id:[0-9]+>/" => array(
		"cmd" => "admin_content",
		"action" => "delete"
	),
	"/admin/content/excerpts/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts"
	),
	"/admin/content/excerpts/add/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts_add"
	),
	"/admin/content/excerpts/add/s/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts_add_s"
	),
	"/admin/content/excerpts/edit/<id:[0-9]+>/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts_edit"
	),
	"/admin/content/excerpts/edit/s/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts_edit_s"
	),
	"/admin/content/excerpts/delete/<id:[0-9]+>/" => array(
		"cmd" => "admin_content",
		"action" => "excerpts_delete"
	),
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
	"/admin/settings/manage/edit/<id:[0-9]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "manageEdit"
	),
	"/admin/settings/manage/edit/s/" => array(
		"cmd" => "admin_settings",
		"action" => "manageEdit_s"
	),
	"/admin/settings/manage/delete/<id:[0-9]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "manageDelete"
	),
	"/admin/settings/manage/groups/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsIndex"
	),
	"/admin/settings/manage/groups/add/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsAdd"
	),
	"/admin/settings/manage/groups/add/s/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsAdd_s"
	),
	"/admin/settings/manage/groups/edit/<id:[0-9]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsEdit"
	),
	"/admin/settings/manage/groups/edit/s/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsEdit_s"
	),
	"/admin/settings/manage/groups/delete/<id:[0-9]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsDelete"
	),
	"/admin/settings/manage/groups/sort/<id:[0-9]+>/<sort:[a-z]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "manageGroupsSort"
	),
	"/admin/settings/plugins/" => array(
		"cmd" => "admin_settings",
		"action" => "plugins_index"
	),
	"/admin/settings/plugins/install/<plugin:[a-z0-9_-]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "plugins_install"
	),
	"/admin/settings/plugins/uninstall/<plugin:[a-z0-9_-]+>/" => array(
		"cmd" => "admin_settings",
		"action" => "plugins_uninstall"
	),
	"/admin/settings/admin-menu/" => array(
		"cmd" => "admin_settings",
		"action" => "admin_menu_index"
	),
	"/admin/settings/admin-menu/s/" => array(
		"cmd" => "admin_settings",
		"action" => "admin_menu_s"
	),
	"/admin/settings/twitter/redirect/" => array(
		"cmd" => "admin_settings_social",
		"action" => "twitter_redirect"
	),
	"/admin/settings/twitter/connect/" => array(
		"cmd" => "admin_settings_social",
		"action" => "twitter_connect"
	),
	"/admin/settings/twitter/unlink/" => array(
		"cmd" => "admin_settings_social",
		"action" => "twitter_unlink"
	),
	"/admin/settings/facebook/redirect/" => array(
		"cmd" => "admin_settings_social",
		"action" => "facebook_redirect"
	),
	"/admin/settings/facebook/connect/" => array(
		"cmd" => "admin_settings_social",
		"action" => "facebook_connect"
	),
	"/admin/settings/facebook/unlink/" => array(
		"cmd" => "admin_settings_social",
		"action" => "facebook_unlink"
	),
	"/admin/users/" => array(
		"cmd" => "admin_users",
		"action" => "index"
	),
	"/admin/users/add/" => array(
		"cmd" => "admin_users",
		"action" => "add"
	),
	"/admin/users/add/s/" => array(
		"cmd" => "admin_users",
		"action" => "add_s"
	),
	"/admin/users/edit/<id:[0-9]+>/" => array(
		"cmd" => "admin_users",
		"action" => "edit"
	),
	"/admin/users/edit/s/" => array(
		"cmd" => "admin_users",
		"action" => "edit_s"
	),
	"/admin/users/delete/<id:[0-9]+>/" => array(
		"cmd" => "admin_users",
		"action" => "delete"
	),
	"/admin/users/account/s/" => array(
		"cmd" => "admin_users",
		"action" => "account_s"
	)
);

$oPlugins = dir(APP."plugins");
while (false !== ($sPlugin = $oPlugins->read())) {
	$aPluginUrlPatterns = null;
	if(substr($sPlugin, 0, 1) != "." && is_file(APP."plugins/".$sPlugin."/url.php")) {
		include(APP."plugins/".$sPlugin."/url.php");

		if(is_array($aPluginUrlPatterns))
			$aUrlPatterns = array_merge($aUrlPatterns, $aPluginUrlPatterns);
	}
}
$oPlugins->close();
unset($oPlugins);

$aUrlPattersAfter = array(
	"/<page:[a-z0-9_-]+>/" => array(
		"cmd" => "content",
		"action" => "view"
	)
);
$aUrlPatterns = array_merge($aUrlPatterns, $aUrlPattersAfter);
###############################################
