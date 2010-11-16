<?php
function smarty_function_getNews($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	$oNews = $oApp->loadModel("news");
	
	$aArticlePages = array_chunk($oNews->getArticles($aParams["category"]), $aParams["limit"]);
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