<?php
function smarty_function_getDirectory($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oDirectory = $oApp->loadModel("directory");
	
	if(empty($aParams["assign"])) {
		if($aParams["limit"] == 1)
			$sAssign = "aListing";
		else
			$sAssign = "aListings";
	} else
		$sAssign = $aParams["assign"];
	
	if(!empty($aParams["limit"])) {
		$aListings = array_chunk($oDirectory->getListings($aParams["category"]), $aParams["limit"]);
		$aListings = $aListings[0];
	} else
		$aListings = $oDirectory->getListings($aParams["category"]);
	
	if($aParams["limit"] == 1) {
		$oApp->tplAssign($sAssign, $aListings[0]);
	} else {
		$oApp->tplAssign($sAssign, $aListings);
	}
}