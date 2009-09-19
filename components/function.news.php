<?php
function smarty_function_news($aParams, &$oSmarty)
{
	$rNews = $objDB->query();
	
	if(PEAR::isError($rNews))
		$this->send_error("smarty->function->news", "dberror", $rNews);
	
	$aNews = $rNews->fetchRow();
	
	$this->SMARTY->assign("news", $aNews);
	$this->SMARTY->assign("paging", $oPaginate->build_array($aParams["paging_around"]));
}
function smarty_function_news_categories($aParams, &$oSmarty)
{
	$rCategories = $objDB->query();
	
	if(PEAR::isError($rCategories))
		$this->send_error("smarty->function->news_categories", "dberror", $rCategories);
	
	$aCategories = $rArticle->fetchRow();
	
	$this->SMARTY->assign("categories", $aCategories);
}
function smarty_function_news_article($aParams, &$oSmarty)
{
	$rArticle = $objDB->query();
	
	if(PEAR::isError($rArticle))
		$this->send_error("smarty->function->news_article", "dberror", $rArticle);
	
	$aArticle = $rArticle->fetchRow();
	
	$this->SMARTY->assign("article", $aArticle);
}