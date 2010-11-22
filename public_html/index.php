<?php
if(!empty($_POST["session_name"]))
	session_id($_POST["session_name"]);

$runtimeStart = microtime(true);
ini_set("display_errors", 1);
error_reporting(E_ALL ^ E_NOTICE);
session_start();

### AUTO CONFIG ##############################
$site_public_root = dirname(__FILE__)."/";
$site_root = dirname($site_public_root)."/";
##############################################

##############################################
@include("../inc_config.php");

// Use local copy of PEAR
if($aConfig["options"]["pear"] == "folder")
	ini_set("include_path", ini_get("include_path").":".$site_root.".pear");

// Check if installer still exists or skipped for dev reasons
if(is_dir("installer") && $aConfig["installer_skip"] != 1)
	die(require("installer/index.php"));

// Set timezone
putenv("TZ=".$aConfig["options"]["timezone"]);
date_default_timezone_set($aConfig["options"]["timezone"]);
##############################################

### NON-DEBUG ################################
if($aConfig["options"]["debug"] == false)
{
	// Hide errors and log in file
	ini_set("display_errors", 0);
	ini_set("log_errors", 1);
	ini_set("error_log", $site_root."php_errors.log");
}
##############################################

### URL VARIABLES ############################
// Remove _GET parameters from url
$sURL = array_shift(explode("?", $_SERVER["REQUEST_URI"]));

// Force ending slash
if(substr($sURL, -1) != "/" && substr($sURL,-4,1) != "." && substr($sURL,-3,1) != ".")
{
	// Save _GET parameters
	if(!empty($_SERVER["QUERY_STRING"]))
		$sQueryString .= "?".$_SERVER["QUERY_STRING"];
	
	// Permanently redirect page
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$sURL."/".$sQueryString);
	exit;
}

// Break URL into peices
$aUrl = explode("/", $sURL);
array_shift($aUrl); // Remove first array item, always empty
array_pop($aUrl); // Remove last array item, always empty
##############################################

### AUTO CLASSES #############################
require($site_root."appController.php");
##############################################

### ENCRYPTION ###############################
include($site_root."helpers/hash_crypt.php");
$oEnc = new hash_crypt(sha1($aConfig["encryption"]["key"]));
$oEnc->set_salt(sha1($aConfig["encryption"]["salt"]));
##############################################

### PREPARE URL PATTERN ######################
require("../inc_urls.php");

// Split patterns into chunks to not choke the server
$patterns = array_chunk($aUrlPatterns, 80, TRUE);

// Run just created pattern chunks
foreach($patterns as $urlPattern)
{
	$aPatterns = Array();
	$i=0;
	
	/* Prepare patterns for matching */
	foreach($urlPattern as $key => $value)
	{
		$aKeys[$i] = $key;
		$key = preg_replace("/\{([a-z]+):([^}]+)\}/i", "($2)", $key);
		$aPatterns[] = "(?P<url".$i.">^".$key."$)";
		$i++;
	}

	/* Run pattern chunk */
	preg_match("/".str_replace("/","\/",implode("|",$aPatterns))."/i", $sURL, $matches);

	/* See if one of the patterns stuck */
	foreach(array_reverse($matches) as $x => $value)
	{
		if(!is_numeric($x) && !empty($value))
		{
			// Pattern is found
			$pattern = str_replace("url",null,$x);
			$pattern = $aKeys[$pattern];
			
			// Leave this foreach
			break;
		}
	}
	
	// If pattern is found, don't try anymore chunks
	if(!empty($pattern))
		break;
}
##############################################

### DB CONNECTION ############################
require("MDB2.php");
$objDB = MDB2::connect($aConfig["database"]["dsn"], $aConfig["database"]["options"]);
if (PEAR::isError($objDB))
	die($objDB->getMessage());
$objDB->setFetchMode($aConfig["database"]["fetch"]);
##############################################

### MAIL CONNECTION ##########################
require("Mail.php");
$objMail = Mail::factory($aConfig["mail"]["type"], $aConfig["mail"]["params"]);
##############################################

### START TEMPLATE ###########################
if($aUrl[0] == "admin") {
	require($site_root."controllers/adminController.php");
	$aConfig["smarty"]["dir"]["templates"] = $site_root."views/admin";
	$aConfig["smarty"]["dir"]["compile"] = $site_root.".compiled/admin";
}

require($aConfig["smarty"]["dir"]["smarty"]);
$oSmarty = new Smarty();
$oSmarty->template_dir = $aConfig["smarty"]["dir"]["templates"];
$oSmarty->compile_dir = $aConfig["smarty"]["dir"]["compile"];
$oSmarty->plugins_dir = $aConfig["smarty"]["dir"]["plugins"];

/* Caching */
$oSmarty->cache_dir = $aConfig["smarty"]["dir"]["cache"];
$oSmarty->caching = $aConfig["smarty"]["cache"]["type"];

/* Filters */
$oSmarty->autoload_filters = $aConfig["smarty"]["filters"];
	
/* Settings */
$oSmarty->debugging = $aConfig["smarty"]["debug"];
$oSmarty->debugging_ctrl = $aConfig["smarty"]["debug_ctrl"];
##############################################

### INCLUDE CLASS WITH CMD NAME ##############
if($aUrl[0] == "admin") {
	$oApp = new adminController;
} else {
	$oApp = new appController;
}

// Check Url Pattern for usable pattern
ob_start();
if(count($aUrlPatterns[$pattern]) > 0) {
	// Pull dynamic variables from url
	$pattern_tmp = preg_replace("/\{([a-z]+):([^}]+)\}/i", "(?P<$1>$2)", $pattern);
	preg_match("/".str_replace("/","\/",$pattern_tmp)."/i", $sURL, $matches);
	
	// Put dynamic variables into usable array
	$urlParams = Array();
	foreach($matches as $key => $value) {
		if(!is_numeric($key) && !empty($value))
			$urlParams[$key] = $value;
	}
	
	// Combine dynamic and manual url variables to be loaded into the appController
	$aURLVars = (object) array(
		"dynamic" => $urlParams,
		"manual" => $aUrlPatterns[$pattern]["params"]
	);
	
	// Find controller given by url pattern
	$oClass = $oApp->loadController($aUrlPatterns[$pattern]["cmd"], true);
	
	// Check if controller was loaded, and method exists
	if($oClass == false || !method_exists($oClass, $aUrlPatterns[$pattern]["action"])) {
		$oApp->error('404');
	}
	
	// Call method given by url pattern
	$oClass->$aUrlPatterns[$pattern]["action"]();
} else {
	$oApp->error('404');
}
##############################################

// Close connection to database
$objDB->disconnect();