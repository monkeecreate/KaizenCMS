<?php
function smarty_function_getNews($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	$oNews = $this->loadModel("news");
	
	$aArticlePages = array_chunk($oNews->getArticles($aParams["category"]), $aParams["limit"]);
	$aArticles = $aArticlePages[0];
	
	$this->SMARTY->assign("aArticles", $aArticles);
}
function smarty_function_getNewsCategories($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	$oNews = $this->loadModel("news");
	
	$aCategories = $oNews->getCategories();
	
	$this->SMARTY->assign("aCategories", $aCategories);
}
function smarty_function_getNewsArticle($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	$oNews = $this->loadModel("news");
	
	$aArticle = $oNews->getArticle($aParams["id"]);
	
	$this->SMARTY->assign("aArticle", $aArticle);
}