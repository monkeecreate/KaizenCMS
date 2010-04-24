<?php
class events extends appController
{
	function index() {
		$oEvents = $this->loadModel("events");
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aEventPages = array_chunk($oEvents->getEvents($_GET["category"]), $oEvents->perPage);
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

		$this->tplAssign("aCategories", $oEvents->getCategories(false));
		$this->tplAssign("aEvents", $aEvents);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("events/category-".$_GET["category"]."tpl"))
			$this->tplDisplay("events/category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("events/category.tpl"))
			$this->tplDisplay("events/category.tpl");
		else
			$this->tplDisplay("events/index.tpl");
	}
	function event() {
		$oEvents = $this->loadModel("events");
		
		$aEvent = $oEvents->getEvent($this->_urlVars->dynamic["id"]);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tplAssign("aEvent", $aEvent);
		
		if(!empty($aEvent["template"]))
			$this->tplDisplay("events/tpl/".$aEvent["template"]);
		elseif($this->tplExists("events/event-".$aEvent["id"].".tpl"))
			$this->tplDisplay("events/event-".$aEvent["id"].".tpl");
		else
			$this->tplDisplay("events/event.tpl");
	}
}