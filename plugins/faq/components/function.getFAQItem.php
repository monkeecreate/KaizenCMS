<?php
function smarty_function_getFAQItem($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	$oNews = $oApp->loadModel("faq");
	
	$aQuestion = $oNews->getQuestion($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aQuestion);
	else
		$oSmarty->assign("aQuestion", $aQuestion);
}
