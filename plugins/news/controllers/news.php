<?php
class news extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("news");
	}
	
	function index() {
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aArticlePages = array_chunk($this->model->getArticles($_GET["category"]), $this->model->perPage);
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

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aArticles", $aArticles);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("category-".$_GET["category"].".tpl"))
			$this->tplDisplay("category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.tpl"))
			$this->tplDisplay("category.tpl");
		else
			$this->tplDisplay("index.tpl");
	}
	function rss() {
		$aArticles = array_slice($this->model->getArticles($_GET["category"]), 0, 15);

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aArticles", $aArticles);
		
		header("Content-Type: application/rss+xml");
		$this->tplDisplay("rss.tpl");
	}
	function article() {
		$aArticle = $this->model->getArticle(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aArticle))
			$this->error('404');

		$this->tplAssign("aArticle", $aArticle);
		
		if($this->tplExists("article-".$aArticle["id"].".tpl"))
			$this->tplDisplay("article-".$aArticle["id"].".tpl");
		else
			$this->tplDisplay("article.tpl");
	}
}