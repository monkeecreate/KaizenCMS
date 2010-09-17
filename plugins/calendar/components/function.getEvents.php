<?php
function smarty_function_getEvents($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	$oCalendar = $oApp->loadModel("calendar");
	
	if(empty($aParams["assign"])) {
		if($aParams["limit"] == 1)
			$sAssign = "aEvent";
		else
			$sAssign = "aEvents";
	} else
		$sAssign = $aParams["assign"];
	
	if(!empty($aParams["limit"])) {
		$aEvents = array_chunk($oCalendar->getEvents($aParams["category"]), $aParams["limit"]);
		$aEvents = $aEvents[0];
	} else
		$aEvents = $oCalendar->getEvents($aParams["category"]);
	
	if($aParams["limit"] == 1) {
		$oApp->tplAssign($sAssign, $aEvents[0]);
	} else {
		$oApp->tplAssign($sAssign, $aEvents);
	}
}