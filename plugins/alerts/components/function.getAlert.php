<?php
function smarty_function_getAlert($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oAlerts = $oApp->loadModel("alerts");
	
	$aAlert = $oAlerts->getAlert($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aAlert);
	else
		$oSmarty->assign("aAlert", $aAlert);
}