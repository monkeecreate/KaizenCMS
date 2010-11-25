<?php
class links extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("links");
	}
	
	function index() {
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aLinkPaging = array_chunk($this->model->getLinks($_GET["category"]), $this->model->perPage);
		$aLinks = $aLinkPaging[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aLinkPaging) || count($aLinkPaging) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aLinks", $aLinks);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("links.tpl");
	}
	function link() {
		$aLink = $this->model->getLink(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aLink))
			$this->error('404');
		
		$this->tplAssign("aLink", $aLink);
		
		if($this->tplExists("link-".$aLink["id"].".tpl"))
			$this->tplDisplay("link-".$aLink["id"].".tpl");
		else
			$this->tplDisplay("link.tpl");
	}
}