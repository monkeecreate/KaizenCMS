<?php
class directory_ extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("directory");
	}
	
	function index() {
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aListingPages = array_chunk($this->model->getListings($_GET["category"]), $this->model->perPage);
		$aListings = $aListingPages[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aListingPages) || count($aListingPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################
		
		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aListings", $aListings);
		$this->tplAssign("aPaging", $aPaging);
		$this->tplDisplay("directory.tpl");
	}
	function listing() {
		$aListing = $this->model->getListing(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aListing))
			$this->error('404');
		
		$this->tplAssign("aListing", $aListing);
		
		if($this->tplExists("listing-".$aListing["id"].".tpl"))
			$this->tplDisplay("listing-".$aListing["id"].".tpl");
		else
			$this->tplDisplay("listing.tpl");
	}
}