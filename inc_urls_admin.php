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

$aConfig["smarty"]["dir"]["templates"] = $site_root."views/admin";
$aConfig["smarty"]["dir"]["compile"] = $site_root.".compiled/admin";

$aUrlPatterns = array(
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
	"/admin/password-reset/" => array(
        "cmd" => "adminController",
        "action" => "password_reset"
    )
);

/* Add to $urlPatterns_admin */
$sDir = $site_root."urls";
$aFiles = scandir($sDir);
foreach($aFiles as $sFile)
{
	if($sFile != "." && $sFile != ".." && array_shift(explode("_", $sFile)) == "admin")
	{
		require($sDir."/".$sFile);
		$aUrlPatterns = array_merge($aUrlPatterns, $aUrlPatterns_import);
		$aUrlPatterns_import = null;
	}
}
###############################################