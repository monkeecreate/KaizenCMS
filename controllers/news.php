<?php
class news extends appController
{
	function index() {
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

		$this->tplAssign("aCategories", $oNews->getCategories(false));
		$this->tplAssign("aArticles", $aArticles);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("news/category-".$_GET["category"]."tpl"))
			$this->tplDisplay("news/category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("news/category.tpl"))
			$this->tplDisplay("news/category.tpl");
		else
			$this->tplDisplay("news/index.tpl");
	}
	function rss() {
		$oNews = $this->loadModel("news");
		
		$aArticles = array_slice($oNews->getArticles($_GET["category"]), 0, 15);

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aArticles", $aArticles);
		
		header("Content-Type: application/rss+xml");
		$this->tplDisplay("news/rss.tpl");
	}
	function article() {
		$oNews = $this->loadModel("news");
		
		$aArticle = $oNews->getArticle($this->_urlVars->dynamic["id"]);
		
		if(empty($aArticle))
			$this->error('404');

		$this->tplAssign("aArticle", $aArticle);
		
		if($this->tplExists("news/article-".$aArticle["id"].".tpl"))
			$this->tplDisplay("news/article-".$aArticle["id"].".tpl");
		else
			$this->tplDisplay("news/article.tpl");
	}
}