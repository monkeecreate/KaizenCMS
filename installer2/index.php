<?php
define('INSTALLER', dirname(__FILE__).'/');
define('APP_ROOT', dirname(INSTALLER).'/');
define('APP', APP_ROOT.'app/');

function changeConfig($sKey1, $sKey2, $sValue, $sConfig, $sQuote = true) {
	if($sQuote == true)
		$sQuote = "\"";
	else
		$sQuote = "";

	$sOldSetting = "\\\$aConfig\[\"".$sKey1."\"\]";
	$sSetting = "\$aConfig[\"".$sKey1."\"]";

	if(!empty($sKey2)) {
		$sOldSetting .= "\[\"".$sKey2."\"\]";
		$sSetting .= "[\"".$sKey2."\"]";
	}

	$sOldSetting .= " = ".$sQuote.".*".$sQuote.";";
	$sSetting .= " = ".$sQuote."".trim($sValue)."".$sQuote.";";

	$sConfig = preg_replace(
		'/'.$sOldSetting.'/',
		$sSetting,
		$sConfig, 1
	);

	return $sConfig;
}

if($aConfig["installer"] == true) {
	$_GET["step"] = 4;
}

switch($_GET["step"]) {
	case 1: include("step1.php"); break;
	case 2: include("step2.php"); break;
	case 3: include("step3.php"); break;
	case 4: include("step4.php"); break;
	default: include("step1.php");
}
