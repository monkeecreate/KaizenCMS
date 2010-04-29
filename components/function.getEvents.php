<?php
function smarty_function_getEvents($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	$oEvents = $oApp->loadModel("events");
	
	if(!empty($aParams["limit"])) {
		$aEvents = array_chunk($oEvents->getEvents($aParams["category"]), $aParams["limit"]);
		$aEvents = $aEvents[0];
	} else
		$aEvents = $oEvents->getEvents($aParams["category"]);
	
	if(empty($aParams["assign"]))
		$oApp->tplAssign("aEvents", $aEvents);
	else
		$oApp->tplAssign($aParams["assign"], $aEvents);
}