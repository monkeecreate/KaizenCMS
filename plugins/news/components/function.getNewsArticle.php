<?php
function smarty_function_getNewsArticle($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	$oNews = $oApp->loadModel("news");
	
	$aArticle = $oNews->getArticle($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aArticle);
	else
		$oSmarty->assign("aArticle", $aArticle);
}