<?php
function smarty_function_getServices($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oServices = $oApp->loadModel("services");
	
	$aServices = $oServices->getServices();
	
	if(empty($aParams["assign"]))
		$oApp->tplAssign("aServicesList", $aServices);
	else
		$oApp->tplAssign($aParams["assign"], $aServices);
}