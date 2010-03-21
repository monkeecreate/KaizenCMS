<?php
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

if(is_file("install.php") && $aConfig["installer"] != 1)
	die(require("install.php"));

if($aConfig["options"]["pear"] == "folder")
	ini_set("include_path", ini_get("include_path").":".$site_root.".pear");
##############################################

### NON-DEBUG ################################
if($aConfig["options"]["debug"] == false)
{
	ini_set("display_errors", 0);
	ini_set("log_errors", 1);
	ini_set("error_log", $site_root."php_errors.log");
}
##############################################

### URL VARIABLES ############################
$sURL = array_shift(explode("?", $_SERVER["REQUEST_URI"]));
if(substr($sURL, -1) != "/" && substr($sURL,-4,1) != "." && substr($sURL,-3,1) != ".")
{
	if(!empty($_SERVER["QUERY_STRING"]))
		$sQueryString .= "?".$_SERVER["QUERY_STRING"];
	
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$sURL."/".$sQueryString);
	exit;
}
$aUrl = explode("/", $sURL);
array_shift($aUrl);
array_pop($aUrl);
##############################################

### AUTO CLASSES #############################
require($site_root."appController.php");
##############################################

### MEMCACHE #################################
if($aConfig["software"]["memcache"] == true)
{
	$oMemcache = new Memcache;
	$oMemcache->connect($aConfig["memcache"]["server"], $aConfig["memcache"]["port"]) or die("Could not connect to memcache");
	if($_GET["FLUSHCACHE"])
	{
		$oMemcache->flush();
		
		// Wait for memcache to finish flushing
		$time = time()+1; //one second future
		while(time() < $time) {
			//sleep
		}
	}
}
else
{
	include($site_root."helpers/emptyMemcache.php");
	$oMemcache = new emptyMemcache;
}
##############################################

### ENCRYPTION ###############################
include($site_root."helpers/hash_crypt.php");
$oEnc = new hash_crypt($aConfig["encryption"]["key"]);
$oEnc->set_salt($aConfig["encryption"]["salt"]);
##############################################

### FIREPHP ##################################
if($aConfig["options"]["debug"] == true && $aConfig["software"]["firephp"] == true)
{
	require("FirePHPCore/FirePHP.class.php");
	$oFirePHP = FirePHP::getInstance(true);
}
else
{
	include($site_root."helpers/emptyFirePHP.php");
	$oFirePHP = new emptyFirePHP;
}
##############################################

### PAGE CACHED ##############################
$sPage = $oMemcache->get(md5($aConfig["memcache"]["salt"].$sURL));
if($sPage != false)
	die($oEnc->decrypt($sPage));
##############################################

### PREPARE URL PATTERN ######################
if($aUrl[0] == "admin")
{
	require($site_root."controllers/adminController.php");
	require("../inc_urls_admin.php");
}
else
	require("../inc_urls.php");

$sURLid = md5($aConfig["memcache"]["salt"].$sURL."_pattern");
if(!$oMemcache->get($sURLid) || $aConfig["options"]["urlcache"] == false || $aConfig["options"]["debug"] == true)
{
	$patterns = array_chunk($aUrlPatterns, 80, TRUE);
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
	
		/* Run all patterns at once */
		preg_match("/".str_replace("/","\/",implode("|",$aPatterns))."/i", $sURL, $matches);
	
		/* See if one of the patterns stuck */
		foreach(array_reverse($matches) as $x => $value)
		{
			if(!is_numeric($x) && !empty($value))
			{
				$pattern = str_replace("url",null,$x);
				$pattern = $aKeys[$pattern];
				continue;
			}
		}
		if(!empty($pattern))
			continue;
	}
	
	if($aConfig["options"]["debug"] == true)
		$oFirePHP->log("URL Pattern: ".$pattern);
	
	if($aConfig["options"]["urlcache"] && $aConfig["options"]["debug"] == false)
		$oMemcache->set($sURLid, $pattern, false, strtotime("+".$aConfig["options"]["urlcache"]." minutes"));
}
else
	$pattern = $oMemcache->get($sURLid);
##############################################

### DB CONNECTION ############################
require("MDB2.php");
$objDB = MDB2::factory($aConfig["database"]["dsn"], $aConfig["database"]["options"]);
if (PEAR::isError($objDB))
	die($objDB->getMessage());
$objDB->setFetchMode($aConfig["database"]["fetch"]);
##############################################

### MAIL CONNECTION ##########################
require("Mail.php");
$objMail = Mail::factory($aConfig["mail"]["type"], $aConfig["mail"]["params"]);
##############################################

### START TEMPLATE ###########################
require($aConfig["smarty"]["dir"]["smarty"]);
$oSmarty = new Smarty();
$oSmarty->template_dir = $aConfig["smarty"]["dir"]["templates"];
$oSmarty->compile_dir = $aConfig["smarty"]["dir"]["compile"];

/* Plugins */
foreach($aConfig["smarty"]["dir"]["plugins"] as $plugin)
	$oSmarty->plugins_dir[] = $plugin;

/* Caching */
$oSmarty->cache_dir = $aConfig["smarty"]["dir"]["cache"];
$oSmarty->caching = $aConfig["smarty"]["cache"]["type"];

/* Filters */
foreach($aConfig["smarty"]["filters"] as $filter)
	$oSmarty->load_filter($filter[0], $filter[1]);
	
/* Settings */
$oSmarty->us_sub_dirs = $aConfig["smarty"]["subdirs"];
$oSmarty->debugging = $aConfig["smarty"]["debug"];
$oSmarty->debugging_ctrl = $aConfig["smarty"]["debug_ctrl"];

/* Smarty Access to Database */
$oApp = new appController;
$oSmarty->register_object("appController", $oApp);
##############################################

### INCLUDE CLASS WITH CMD NAME ##############
/* Check Url Pattern for usable pattern */
ob_start();
if(count($aUrlPatterns[$pattern]) > 0)
{
	$pattern_tmp = preg_replace("/\{([a-z]+):([^}]+)\}/i", "(?P<$1>$2)", $pattern);
	preg_match("/".str_replace("/","\/",$pattern_tmp)."/i", $sURL, $matches);
	
	$urlParams = Array();
	foreach($matches as $key => $value)
	{
		if(!is_numeric($key) && !empty($value))
			$urlParams[$key] = $value;
	}
	
	$aURLVars = (object) array(
		"dynamic" => $urlParams,
		"manual" => $aUrlPatterns[$pattern]["params"]
	);
	
	$oClass = $oApp->loadController($aUrlPatterns[$pattern]["cmd"]);
	$oClass->$aUrlPatterns[$pattern]["action"]();
}
/* Complete failure, throw 404 */
else
{
	if($aUrl[0] == "admin")
		$oApp = new adminController;
	else
		$oApp = new appController;
	
	$oApp->error('404');
}
##############################################

$objDB->disconnect();

if($aConfig["options"]["debug"] == true) {
	//Ready extra header info
	$output = ob_get_contents();
	ob_end_clean();
	
	//Send execution time
	$runtimeEnd = microtime(true);
	$oFirePHP->log("Runtime: ".($runtimeEnd - $runtimeStart)." sec");
	
	//Get memory usage
	function convert($size)
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
	$oFirePHP->log("Memory: ".convert(memory_get_usage()));
	
	echo $output;
}