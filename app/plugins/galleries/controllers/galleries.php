<?php
class galleries extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("galleries");
	}
	
	function index() {
		## GET CURRENT PAGE GALLERIES
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aGalleryPages = array_chunk($this->model->getGalleries($_GET["category"]), $this->model->perPage);
		$aGalleries = $aGalleryPages[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aGalleryPages) || count($aGalleryPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aGalleries", $aGalleries);
		$this->tplAssign("aPaging", $aPaging);
		$this->tplAssign("sImageFolder", $this->model->imageFolder);
		
		if(!empty($_GET["category"]) && $this->tplExists("category-".$_GET["category"]."tpl"))
			$this->tplDisplay("category-".$_GET["category"].".php");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.php"))
			$this->tplDisplay("category.php");
		else
			$this->tplDisplay("index.php");
	}
	function gallery() {
		$aGallery = $this->model->getGallery(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aGallery))
			$this->error('404');
		
		$this->tplAssign("aGallery", $aGallery);
		$this->tplAssign("sImageFolder", $this->model->imageFolder);
	
		if($this->tplExists("gallery-".$aGallery["id"].".php"))
			$this->tplDisplay("gallery-".$aGallery["id"].".php");
		else
			$this->tplDisplay("gallery.php");
	}
}