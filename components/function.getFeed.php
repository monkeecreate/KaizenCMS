<?php
function smarty_function_getFeed($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	require_once $oApp->_settings->root.'helpers/Xml2Array.php';
	
	$sFeed = $aParams["feed"];
	
	$feed = file_get_contents($sFeed);
	$converter = new Xml2Array();
	$converter->setXml($feed);
	$aFeed = $converter->get_array(); 
	
	$oSmarty->assign("aFeed", $aFeed);
}