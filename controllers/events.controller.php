<?php
class events extends appController
{
	function index()
	{
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

		$this->tplAssign("aCategories", $oEvents->getCategories());
		$this->tplAssign("aEvents", $aEvents);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("events/index.tpl");
	}
	function event($aParams)
	{
		$oEvents = $this->loadModel("events");
		
		$aEvent = $oEvents->getEvent($aParams["id"]);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tplAssign("aEvent", $aEvent);
		
		if(!empty($aEvent["template"]))
			$this->tplDisplay("events/tpl/".$aEvent["template"]);
		else
			$this->tplDisplay("events/event.tpl");
	}
}