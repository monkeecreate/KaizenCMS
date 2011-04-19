<?php
function smarty_function_getAlerts($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oAlerts = $oApp->loadModel("alerts");
	
	$aAlertPages = array_chunk($oAlerts->getAlerts(), $aParams["limit"]);
	$aAlerts = $aAlertPages[0];
	
	if($aParams["limit"] == 1) {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aAlerts[0]);
		else
			$oSmarty->assign("aAlert", $aAlerts[0]);
	} else {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aAlerts);
		else
			$oSmarty->assign("aAlerts", $aAlerts);
	}
}