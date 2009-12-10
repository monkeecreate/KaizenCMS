<?php
class news extends appController
{
	function index()
	{
		$oNews = $this->loadModel("news");
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aArticlePages = array_chunk($oNews->getArticles($_GET["category"]), $oNews->perPage);
		$aArticles = $aArticlePages[$sCurrentPage - 1];
		
		$aPaging = array(
			"back" => array(
				"page" => $sCurrentPage - 1,
				"use" => true
			),
			"next" => array(
				"page" => $sCurrentPage + 1,
				"use" => true
			)
		);
		
		if(($sCurrentPage - 1) < 1 || $sCurrentPage == 1)
			$aPaging["back"]["use"] = false;
		
		if($sCurrentPage == count($aArticlePages) || count($aArticlePages) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tpl_assign("aCategories", $oNews->getCategories());
		$this->tpl_assign("aArticles", $aArticles);
		$this->tpl_assign("aPaging", $aPaging);
		
		$this->tpl_display("news/index.tpl");
	}
	function rss()
	{
		$oNews = $this->loadModel("news");
		
		$aArticles = array_slice($oNews->getArticles($_GET["category"]), 0, 15);

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aArticles", $aArticles);
		
		header("Content-Type: application/rss+xml");
		$this->tpl_display("news/rss.tpl");
	}
	function article($aParams)
	{
		$oNews = $this->loadModel("news");
		
		$aArticle = $oNews->getArticle($aParams["id"]);
		
		if(empty($aArticle))
			$this->error('404');

		$this->tpl_assign("aArticle", $aArticle);
		
		$this->tpl_display("news/article.tpl");
	}
}