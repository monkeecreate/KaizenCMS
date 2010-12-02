<?php
function smarty_function_getSetting($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	if(empty($aParams["assign"]))
		return stripslashes($oApp->getSetting($aParams["tag"]));
	else
		$oSmarty->assign($aParams["assign"], stripslashes($oApp->getSetting($aParams["tag"])));
}