<?php
class calendar extends appController
{
	function index()
	{
		$oCalendar = $this->loadModule("calendar");
		
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

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aCategories", $oCalendar->getCategories());
		$this->tpl_assign("aEvents", $aEvents);
		$this->tpl_assign("aPaging", $aPaging);
		
		$this->tpl_display("calendar/index.tpl");
	}
	function ics()
	{
		$oCalendar = $this->loadModule("calendar");
		
		$aEventPages = array_chunk($oCalendar->getEvents($_GET["category"]), 15);
		$aEvents = $aEventPages[0];

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aEvents", $aEvents);
		
		$this->tpl_display("calendar/ics.tpl");
	}
	function event($aParams)
	{
		$oCalendar = $this->loadModule("calendar");
		
		$aEvent = $oCalendar->getEvent($aParams["id"]);
		
		if(empty($aEvent))
			$this->error('404');
		
		$this->tpl_assign("aEvent", $aEvent);
		$this->tpl_display("calendar/event.tpl");
	}
	function event_ics($aParams)
	{
		$oCalendar = $this->loadModule("calendar");
		
		$aEvent = $oCalendar->getEvent($aParams["id"]);
		
		if(empty($aEvent))
			$this->error('404');

		$this->tpl_assign("domain", $_SERVER["SERVER_NAME"]);
		$this->tpl_assign("aEvent", $aEvent);
		
		header("Content-type: text/calendar");
		header("Content-Transfer-Encoding: Binary");
		header("Content-length: ".strlen($sFile));
		header("Content-disposition: attachment; filename=\"event.ics\"");

		$this->tpl_display("calendar/event_ics.tpl");
	}
}