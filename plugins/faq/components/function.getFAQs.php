<?php
function smarty_function_getFAQs($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	$oFAQ = $oApp->loadModel("faq");
	
	$aArticlePages = array_chunk($oFAQ->getQuestions($aParams["category"]), $aParams["limit"]);
	$aArticles = $aArticlePages[0];
	
	if($aParams["limit"] == 1) {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aArticles[0]);
		else
			$oSmarty->assign("aArticle", $aArticles[0]);
	} else {
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aArticles);
		else
			$oSmarty->assign("aArticles", $aArticles);
	}
}
