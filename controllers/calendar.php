<?php
class calendar extends appController
{
	function index()
	{
		$oCalendar = $this->loadModel("calendar");
		
		## GET CURRENT PAGE EVENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aEventPages = array_chunk($oCalendar->getEvents($_GET["category"]), $oCalendar->perPage);
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
		$this->tplAssign("aCategories", $oCalendar->getCategories());
		$this->tplAssign("aEvents", $aEvents);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("calendar/category-".$_GET["category"]."tpl"))
			$this->tplDisplay("calendar/category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("calendar/category.tpl"))
			$this->tplDisplay("calendar/category.tpl");
		else
			$this->tplDisplay("calendar/index.tpl");
	}
	function ics()
	{
		$oCalendar = $this->loadModel("calendar");
		
		$aEventPages = array_chunk($oCalendar->getEvents($_GET["category"]), 15);
		$aEvents = $aEventPages[0];

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aEvents", $aEvents);
		
		$this->tplDisplay("calendar/ics.tpl");
	}
	function event()
	{
		$oCalendar = $this->loadModel("calendar");
		
		$aEvent = $oCalendar->getEvent($this->_urlVars->dynamic["id"]);
		
		if(empty($aEvent))
			$this->error('404');
		
		$this->tplAssign("aEvent", $aEvent);
		
		if($this->tplExists("calendar/event-".$aEvent["id"].".tpl"))
			$this->tplDisplay("calendar/event-".$aEvent["id"].".tpl");
		else
			$this->tplDisplay("calendar/event.tpl");
	}
	function event_ics()
	{
		$oCalendar = $this->loadModel("calendar");
		
		$aEvent = $oCalendar->getEvent($this->_urlVars->dynamic["id"]);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aEvent", $aEvent);
		
		header("Content-type: text/calendar");
		header("Content-Transfer-Encoding: Binary");
		header("Content-length: ".strlen($sFile));
		header("Content-disposition: attachment; filename=\"event.ics\"");

		$this->tplDisplay("calendar/event_ics.tpl");
	}
}