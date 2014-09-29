<?php
if(!empty($_POST['session_name']))
	session_id($_POST['session_name']);

$runtimeStart = microtime(true);
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
session_start();

### AUTO CONFIG ##############################
define('APP_ROOT', dirname(__FILE__).'/');
define('APP', APP_ROOT.'app/');
##############################################

##############################################
@include(APP.'inc_config.php');

// Check if installer still exists or skipped for dev reasons
if(is_dir('installer') && $aConfig['installer_skip'] != 1)
	die(require('installer/index.php'));

// Set timezone
putenv('TZ='.$aConfig['options']['timezone']);
date_default_timezone_set($aConfig['options']['timezone']);
##############################################

### NON-DEBUG ################################
if($aConfig['options']['debug'] == false) {
	// Hide errors and log in file
	ini_set('display_errors', 0);
	ini_set('log_errors', 1);
	ini_set('error_log', APP.'php_errors.log');
}
##############################################

### URL VARIABLES ############################
// Remove _GET parameters from url
$sRequestURI = $_SERVER['REQUEST_URI'];
$aURL = explode('?', $sRequestURI);
$sURL = array_shift($aURL);

// Force ending slash
if(substr($sURL, -1) != '/' && substr($sURL,-4,1) != '.' && substr($sURL,-3,1) != '.') {
	// Save _GET parameters
	if(!empty($_SERVER['QUERY_STRING']))
		$sQueryString .= '?'.$_SERVER['QUERY_STRING'];

	// Permanently redirect page
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$sURL.'/'.$sQueryString);
	exit;
}

// Break URL into peices
$aUrl = explode('/', $sURL);
array_shift($aUrl); // Remove first array item, always empty
array_pop($aUrl); // Remove last array item, always empty
##############################################

### AUTO CLASSES #############################
require(APP.'appController.php');
##############################################

### ENCRYPTION ###############################
include(APP.'helpers/hash_crypt.php');
$oEnc = new hash_crypt(sha1($aConfig['encryption']['key']));
$oEnc->set_salt(sha1($aConfig['encryption']['salt']));
##############################################

### PREPARE URL PATTERN ######################
require(APP.'inc_urls.php');

// Split patterns into chunks to not choke the server
$patterns = array_chunk($aUrlPatterns, 80, TRUE);

// Run just created pattern chunks
foreach($patterns as $urlPattern) {
	$aPatterns = Array();
	$i=0;

	/* Prepare patterns for matching */
	foreach($urlPattern as $key => $value) {
		$aKeys[$i] = $key;
		$key = preg_replace('/<([a-z]+):(.+?)>/i', '($2)', $key);
		$aPatterns[] = '(?P<url'.$i.'>^'.$key.'$)';
		$i++;
	}

	/* Run pattern chunk */
	preg_match('/'.str_replace('/','\/',implode('|',$aPatterns)).'/i', $sURL, $matches);

	/* See if one of the patterns stuck */
	foreach(array_reverse($matches) as $x => $value) {
		if(!is_numeric($x) && !empty($value)) {
			// Pattern is found
			$pattern = str_replace('url',null,$x);
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
include(APP.'core/database.php');
$objDB = new Stratum();

$objDB->connect(
	$aConfig["database"]["username"],
	$aConfig["database"]['password'],
	$aConfig["database"]['database'],
	$aConfig["database"]['host']
);
$objDB->debug = $aConfig['database']['debug'];
##############################################

### MAIL CONNECTION ##########################

##############################################


##############################################

### INCLUDE CLASS WITH CMD NAME ##############
if($aUrl[0] == 'admin') {
	require(APP.'controllers/adminController.php');
	$oApp = new adminController;
} else {
	$oApp = new appController;
}
##############################################

### START TEMPLATE ###########################
if($aUrl[0] == 'admin') {
	// Change view directory
	$aConfig['views']['dir'] = $aConfig['views']['dir'].'admin/';
}

$oApp->loadComponents();
##############################################

// Check Url Pattern for usable pattern
ob_start();
if(count($aUrlPatterns[$pattern]) > 0) {
	// Pull dynamic variables from url
	$pattern_tmp = preg_replace('/<([a-z]+):(.+?)>/i', '(?P<$1>$2)', $pattern);
	preg_match('/'.str_replace('/','\/',$pattern_tmp).'/i', $sURL, $matches);

	// Put dynamic variables into usable array
	$urlParams = Array();
	foreach($matches as $key => $value) {
		if(!is_numeric($key) && !empty($value))
			$urlParams[$key] = $value;
	}

	// Combine dynamic and manual url variables to be loaded into the appController
	$aURLVars = (object) array(
		'dynamic' => $urlParams,
		'manual' => $aUrlPatterns[$pattern]['params']
	);

	// Find controller given by url pattern
	$oClass = $oApp->loadController($aUrlPatterns[$pattern]['cmd'], true);

	// Check if controller was loaded, and method exists
	if($oClass == false || !method_exists($oClass, $aUrlPatterns[$pattern]['action'])) {
		$oApp->error('404');
	}

	// Call method given by url pattern
	$oClass->$aUrlPatterns[$pattern]['action']();
} else {
	$oApp->error('404');
}
##############################################

### MOVE HEADER/FOOTER #######################
$sOutput = ob_get_contents();
ob_end_clean();

preg_match_all('/{head}(.*){\/head}/isU', $sOutput, $head_matches);
$sOutput = preg_replace('/{head}(.*){\/head}/isU', '', $sOutput);
str_replace('</head>', implode("\n", array_unique($head_matches[1]))."\n".'</head>', $sOutput);

preg_match_all('/{footer}(.*){\/footer}/isU', $sOutput, $footer_matches);
$sOutput = preg_replace('/{footer}(.*){\/footer}/isU', '', $sOutput);
str_replace('</body>', implode("\n", array_unique($footer_matches[1]))."\n".'</body>', $sOutput);

echo $sOutput;
##############################################

// Close connection to database
$objDB->disconnect();
