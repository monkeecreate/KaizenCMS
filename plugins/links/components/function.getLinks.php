<?php
function smarty_function_getLinks($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	$oLinks = $oApp->loadModel("links");
	
	if(!empty($aParams["limit"])) {
		$aLinks = array_chunk($oLinks->getLinks($aParams["category"], false, $aParams["random"]), $aParams["limit"]);
		$aLinks = $aLinks[0];
	} else
		$aLinks = $oLinks->getLinks($aParams["category"], false, $aParams["random"]);
	
	if(empty($aParams["assign"]))
		$oApp->tplAssign("aLinks", $aLinks);
	else
		$oApp->tplAssign($aParams["assign"], $aLinks);
}