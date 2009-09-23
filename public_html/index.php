<?php
### AUTO CONFIG ##############################
$site_public_root = dirname(__FILE__)."/";
$site_root = dirname($site_public_root)."/";
##############################################

##############################################
session_start();

require("../inc_config.php");
require("../inc_urls.php");

if($options_pear == "folder")
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
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$sURL."/");
	exit;
}
$aUrl = explode("/", $sURL);
##############################################

### AUTO CLASS CALL ##########################
function __autoload($class_name) {
	global $site_root;
	
	if(!class_exists($class_name))
	{
		if($class_name == "appController")
			require($site_root."appController.php");
    	elseif(is_file($site_root."controllers/".$class_name.".controller.php"))
			require($site_root."controllers/".$class_name.".controller.php");
		elseif(is_file($site_root."helpers/".$class_name.".helper.php"))
			require($site_root."helpers/".$class_name.".helper.php");
	}
}
##############################################

### MEMCACHE #################################
$oMemcache = new Memcache;
$oMemcache->connect($aConfig["memcache"]["server"]) or die("Could not connect to memcache");
if($_GET["FLUSHCACHE"])
{
	$oMemcache->flush();
	$time = time()+1; //one second future
	while(time() < $time) {
	  //sleep
	}
}
##############################################

### ENCRYPTION ###############################
$oEnc = new hash_crypt($aConfig["encryption"]["key"]);
$oEnc->set_salt($aConfig["encryption"]["salt"]);
##############################################

### FIREPHP ##################################
if($aConfig["options"]["debug"] == true)
{
	require("FirePHPCore/FirePHP.class.php");
	$oFirePHP = FirePHP::getInstance(true);
}
else
	$oFirePHP = (object) array();
##############################################

### PAGE CACHED ##############################
$sPage = $oMemcache->get(md5($aConfig["memcache"]["salt"].$sURL));
if($sPage != false)
	die("<h2>Cache:</h2> ".$oEnc->decrypt($sPage));
##############################################

### PREPARE URL PATTERN #######################
$sURLid = md5($aConfig["memcache"]["salt"].$sURL."_pattern");
if(!$oMemcache->get($sURLid) || $aConfig["options"]["urlcache"] == false || $aConfig["options"]["debug"] == true)
{
	$patterns = array_chunk($urlPatterns, 80, TRUE);
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
		$oFirePHP->log($pattern);
	
	if($aConfig["options"]["urlcache"] && $aConfig["options"]["debug"] == false)
		$oMemcache->set($sURLid, $pattern, false, strtotime("+".$aConfig["options"]["urlcache"]." minutes"));
}
else
	$pattern = $oMemcache->get($sURLid);
##############################################

### DB CONNECTION ############################
require("MDB2.php");
$objDB = MDB2::factory($aConfig["db"]["dsn"], $aConfig["db"]["options"]);
if (PEAR::isError($objDB))
    die($objDB->getMessage());
$objDB->setFetchMode($aConfig["db"]["fetch"]);
##############################################

### MAIL CONNECTION ##########################
require("Mail.php");
$objMail = Mail::factory($aConfig["mail"]["type"], $aConfig["mail"]["params"]);
##############################################

### START TEMPLATE ###########################
require($aConfig["smarty"]["dir"]["smarty"]);
$oSmarty = new Smarty();
$oSmarty->template_dir = $aConfig["smarty"]["dir"]["tpl"];
$oSmarty->compile_dir = $aConfig["smarty"]["dir"]["tplc"];

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
$oSmarty->register_object("objDB", $objDB);
$oSmarty->register_object("memcache", $oMemcache);
##############################################

### INCLUDE CLASS WITH CMD NAME ###############
/* Check Url Pattern for usable pattern */
if(count($urlPatterns[$pattern]) > 0)
{
	$pattern_tmp = preg_replace("/\{([a-z]+):([^}]+)\}/i", "(?P<$1>$2)", $pattern);
	preg_match("/".str_replace("/","\/",$pattern_tmp)."/i", $sURL, $matches);
	
	$urlParams = Array();
	foreach($matches as $key => $value)
	{
		if(!is_numeric($key) && !empty($value))
			$urlParams[$key] = $value;
	}
	
	$oClass = new $urlPatterns[$pattern]["cmd"];
	$oClass->$urlPatterns[$pattern]["action"]($urlParams, $urlPatterns[$pattern]["params"]);
}
/* Complete failure, throw 404 */
else
{
	$oApp = new appController;
	$oApp->error('404');
}
##############################################

$objDB->disconnect();