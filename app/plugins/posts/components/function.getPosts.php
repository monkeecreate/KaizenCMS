<?php
function smarty_function_getPosts($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oPosts = $oApp->loadModel("posts");
	
	$aPostPages = array_chunk($oPosts->getPosts($aParams["category"], false, $aParams["popular"]), $aParams["limit"]);
	$aPosts = $aPostPages[0];
	
	if($aParams["limit"] == 1) {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aPosts[0]);
		else
			$oSmarty->assign("aPost", $aPosts[0]);
	} else {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aPosts);
		else
			$oSmarty->assign("aPosts", $aPosts);
	}
}