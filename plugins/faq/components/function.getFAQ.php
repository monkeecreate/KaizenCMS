<?php
function smarty_function_getFAQ($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oQuestions = $oApp->loadModel("faq");
	
	$aQuestion = $oQuestions->getQuestion($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aQuestion);
	else
		$oSmarty->assign("aQuestion", $aQuestion);
}
