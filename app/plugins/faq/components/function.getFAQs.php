<?php
function smarty_function_getFAQs($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oQuestions = $oApp->loadModel("faq");
	
	$aQuestionPages = array_chunk($oQuestions->getQuestions($aParams["category"]), $aParams["limit"]);
	$aQuestions = $aQuestionPages[0];
	
	if($aParams["limit"] == 1) {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aQuestions[0]);
		else
			$oSmarty->assign("aQuestion", $aQuestions[0]);
	} else {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aQuestions);
		else
			$oSmarty->assign("aQuestions", $aQuestions);
	}
}
