<?php
class calendar extends appController
{
	function __construct() {
		// Load model when creating appController
		parent::__construct("calendar");
	}
	
	function index() {
		## GET CURRENT PAGE EVENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aEventPages = array_chunk($this->model->getEvents($_GET["category"]), $this->model->perPage);
		$aEvents = $aEventPages[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aEventPages) || count($aEventPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aEvents", $aEvents);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("category-".$_GET["category"]."tpl"))
			$this->tplDisplay("category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.tpl"))
			$this->tplDisplay("category.tpl");
		else
			$this->tplDisplay("index.tpl");
	}
	function ics() {
		$aEventPages = array_chunk($this->model->getEvents($_GET["category"]), 15);
		$aEvents = $aEventPages[0];

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aEvents", $aEvents);
		
		$this->tplDisplay("ics.tpl");
	}
	function event() {
		$aEvent = $this->model->getEvent(null, $this->urlVars->dynamic["tag"]);
		
		if(empty($aEvent))
			$this->error('404');
		
		$this->tplAssign("aEvent", $aEvent);
		
		if($this->tplExists("event-".$aEvent["id"].".tpl"))
			$this->tplDisplay("event-".$aEvent["id"].".tpl");
		else
			$this->tplDisplay("event.tpl");
	}
	function event_ics() {
		$aEvent = $this->model->getEvent($this->urlVars->dynamic["id"]);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aEvent", $aEvent);
		
		header("Content-type: text/calendar");
		header("Content-Transfer-Encoding: Binary");
		header("Content-length: ".strlen($sFile));
		header("Content-disposition: attachment; filename=\"event.ics\"");

		$this->tplDisplay("event_ics.tpl");
	}
}