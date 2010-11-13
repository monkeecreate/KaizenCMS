<?php
function smarty_function_getSetting($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	if(empty($aParams["assign"]))
		return $oApp->getSetting($aParams["tag"]);
	else
		$oSmarty->assign($aParams["assign"], $oApp->getSetting($aParams["tag"]));
}