<?php
class calendar extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("calendar");
	}
	
	function index() {
		if(strtolower(trim($this->model->defaultView)) == "month")
			$this->monthView();
		else
			$this->listView();
	}
	
	function listView() {
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
			$this->tplDisplay("list.tpl");
	}
	
	
	function monthView() {
		## GET CURRENT PAGE EVENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
//		$aEventPages = array_chunk($this->model->getEvents($_GET["category"]), $this->model->perPage);
//		$aEvents = $aEventPages[$sCurrentPage - 1];
		$aEvents = $this->model->getEvents($_GET["category"]);
		
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
		
		
// Beginning of MonthView Code...
		$year = $this->urlVars->dynamic["year"];
		$month = $this->urlVars->dynamic["month"];

		if($year < 1)
			$year = (date("Y"));

		if($month < 1)
			$month = (date("m"));

		$date = strtotime("01-" . $month . "-" . $year . " 12:01:00 pm");


		$lNumberOfDaysInMonth = date("t", $date);
		$lFirstDayOfWeek = date("w", $date);

		$lYear = date("Y", $date);
		$sMonth = date("F", $date);
		$lFirstSunday = (7 - $lFirstDayOfWeek) + 1;
		
		$lastdate = strtotime($lNumberOfDaysInMonth . "-" . $month . "-" . $year . " 12:01:00 pm");		
		$lLastDayOfWeek = date("w", $lastdate);
		




		$lDayOfWeek = 0;
		$lWeekNumber = 1;
		if($lFirstDayOfWeek > 0) {
			for($lBuffCount = 0; $lBuffCount < $lFirstDayOfWeek; $lBuffCount++) {
				$aCalendar[$lWeekNumber][$lDayOfWeek] = array(0, array());
				$lDayOfWeek++;
			}
		}

		for($lCurrentDay = 1; $lCurrentDay <= $lNumberOfDaysInMonth; $lCurrentDay++) {
			if(strlen($lCurrentDay) == 1)
				$sThisDay = "0" . $lCurrentDay;
			else
				$sThisDay = $lCurrentDay;
			$lStartOfDay = strtotime($sThisDay . "-" . $month . "-" . $year . " 12:00:01 am");
			$lEndOfDay = strtotime($sThisDay . "-" . $month . "-" . $year . " 11:59:59 pm");	
			
		
			$aDayEvents = array();
			if(count($aEvents) > 0)
				foreach($aEvents as $aEvent) {
					if($aEvent["datetime_start"] >= $lStartOfDay && $aEvent["datetime_start"] <= $lEndOfDay ) {
						$aNewEvent[0] = $aEvent["title"];
						if(strlen($aEvent["title"]) > 30)
							$aNewEvent[3] = "<span title=\"" . $aEvent["title"] . "\">" . substr($aEvent["title"], 0,30 ) . "...</span>";
						else
							$aNewEvent[3] = $aEvent["title"];
						$aNewEvent[1] = $aEvent["id"];
						$aNewEvent[4] = $aEvent["url"];
						//print $lCurrentDay . " - " . $aEvent["title"] . " - " . $aEvent["datetime_start"] . "/" .$aEvent["datetime_end"] . "<br />";
						array_push($aDayEvents, $aNewEvent);
					}
				}
			$aCalendar[$lWeekNumber][$lDayOfWeek] = array($lCurrentDay, $aDayEvents);

			$lDayOfWeek++;
			if($lDayOfWeek == 7) {
				$lDayOfWeek = 0;
				$lWeekNumber++;
			}
		}

		$lLastYear = $year;
		$lNextYear = $year;
		
		$lLastMonth = $month - 1;
		$lNextMonth = $month + 1;	
		
		if($lLastMonth == 0) {
			$lLastYear--;
			$lLastMonth = 12;
		}

		if($lNextMonth == 13) {
			$lLastYear++;
			$lLastMonth = 1;
		}

		if(strlen($lLastMonth) == 1)
			$lLastMonth = "0" . $lLastMonth;	
			
		if(strlen($lNextMonth) == 1)
			$lNextMonth = "0" . $lNextMonth;
			
		$sNextMonthWord = date("F", strtotime("01-" . $lNextMonth . "-" . $lNextYear . " 12:00:01 am"));
		$sLastMonthWord = date("F", strtotime("01-" . $lLastMonth . "-" . $lLastYear . " 12:00:01 am"));

		$sNextMonthURL = "/calendar/month/$lNextYear/$lNextMonth";
		$sLastMonthURL = "/calendar/month/$lLastYear/$lLastMonth";
		$sNextMonthTitle = "Click here to go to $sNextMonthWord $lNextYear";;
		$sLastMonthTitle = "Click here to go to $sLastMonthWord $lLastYear";
		
		$lToday = 0;
		if(date("m") == $month)
			$lToday = date("d") / 1;

		$this->tplAssign("sCalTitle", date("F Y", $date));
		$this->tplAssign("sNextMonthURL", $sNextMonthURL);		
		$this->tplAssign("sLastMonthURL", $sLastMonthURL);		
		$this->tplAssign("sNextMonthTitle", $sNextMonthTitle);		
		$this->tplAssign("sLastMonthTitle", $sLastMonthTitle);		
		$this->tplAssign("aCalendar", $aCalendar);
		$this->tplAssign("lToday", $lToday);
		$this->tplAssign("lNumWeeks", count($aCalendar) + 1);
// End of Month View Additionals

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aEvents", $aEvents);
		$this->tplAssign("aPaging", $aPaging);
		
		if(!empty($_GET["category"]) && $this->tplExists("category-".$_GET["category"]."tpl"))
			$this->tplDisplay("category-".$_GET["category"].".tpl");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.tpl"))
			$this->tplDisplay("category.tpl");
		else
			$this->tplDisplay("month.tpl");
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
		$aEvent = $this->model->getEvent(null, $this->urlVars->dynamic["tag"]);
		
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
