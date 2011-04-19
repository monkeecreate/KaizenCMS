<?php
class alerts extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("alerts");
	}
	
	function index() {
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aAlertPages = array_chunk($this->model->getAlerts(), $this->model->perPage);
		$aAlerts = $aAlertPages[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aAlertPages) || count($aAlertPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################
		
		$this->tplAssign("aAlerts", $aAlerts);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("index.tpl");
	}
	function alert() {
		$aAlert = $this->model->getAlert(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aAlert))
			$this->error('404');

		$this->tplAssign("aAlert", $aAlert);
		
		if($this->tplExists("alert-".$aAlert["id"].".tpl"))
			$this->tplDisplay("alert-".$aAlert["id"].".tpl");
		else
			$this->tplDisplay("alert.tpl");
	}
}