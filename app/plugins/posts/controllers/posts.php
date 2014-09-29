<?php
class posts extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("posts");
	}
	
	function index() {
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aPostPages = array_chunk($this->model->getPosts($_GET["category"]), $this->model->perPage);
		$aPosts = $aPostPages[$sCurrentPage - 1];
		
		$aPaging = array(
			"total" => count($aPostPages),
			"current" => $sCurrentPage,
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
		
		if($sCurrentPage == count($aPostPages) || count($aPostPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################
		
		if(!empty($_GET["category"]))
			$aCategory = $this->model->getCategory($_GET["category"]);

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aPosts", $aPosts);
		$this->tplAssign("aPaging", $aPaging);
		$this->tplAssign("aCategory", $aCategory);
		
		if(!empty($_GET["category"]) && $this->tplExists("category-".$_GET["category"].".php"))
			$this->tplDisplay("category-".$_GET["category"].".php");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.php"))
			$this->tplDisplay("category.php");
		else
			$this->tplDisplay("index.php");
	}
	function post() {
		$aPost = $this->model->getPost(null, $this->urlVars->dynamic["tag"]);

		if(empty($aPost))
			$this->error('404');

		$this->dbUpdate("posts", array("views" => ($aPost["views"] + 1)), $aPost["id"]);
		$this->tplAssign("aPost", $aPost);
		
		if($this->tplExists("post-".$aPost["id"].".php"))
			$this->tplDisplay("post-".$aPost["id"].".php");
		else
			$this->tplDisplay("post.php");
	}
	function rss() {
		$aPosts = array_slice($this->model->getPosts($_GET["category"]), 0, 15);

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aPosts", $aPosts);
		
		header("Content-Type: application/rss+xml");
		$this->tplDisplay("rss.php");
	}
}