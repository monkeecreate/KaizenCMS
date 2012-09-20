<?php
class services extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("services");
	}

	function index() {
		## GET CURRENT PAGE SERVICES
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;

		$aServicePaging = array_chunk($this->model->getServices(), $this->model->perPage);
		$aServices = $aServicePaging[$sCurrentPage - 1];

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

		if($sCurrentPage == count($aServicePaging) || count($aServicePaging) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("aServices", $aServices);
		$this->tplAssign("aPaging", $aPaging);

		$this->tplDisplay("index.tpl");
	}
	function service() {
		$aService = $this->model->getService(null, $this->urlVars->dynamic["tag"]);

		if(empty($aService))
			$this->error('404');

		$this->tplAssign("aServices", $this->model->getServices());
		$this->tplAssign("aService", $aService);

		if($this->tplExists("service-".$aService["id"].".tpl"))
			$this->tplDisplay("service-".$aService["id"].".tpl");
		else
			$this->tplDisplay("service.tpl");
	}
}