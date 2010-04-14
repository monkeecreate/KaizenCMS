<?php
function smarty_function_getNewsArticle($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	
	$oNews = $this->loadModel("news");
	
	$aArticle = $oNews->getArticle($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aArticle);
	else
		$oSmarty->assign("aArticle", $aArticle);
}