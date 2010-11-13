<?php
function smarty_function_getNewsCategories($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	$oNews = $oApp->loadModel("news");
	
	$aCategories = $oNews->getCategories();
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aCategories);
	else
		$oSmarty->assign("aCategories", $aCategories);
}
