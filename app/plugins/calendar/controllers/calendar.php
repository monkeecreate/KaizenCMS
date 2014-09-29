<?php
class calendar extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("calendar");
	}
	
	function index() {
		if(strtolower(trim($this->model->calendarView)) == "month")
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
			$this->tplDisplay("category-".$_GET["category"].".php");
		elseif(!empty($_GET["category"]) && $this->tplExists("category.php"))
			$this->tplDisplay("category.php");
		else
			$this->tplDisplay("list.php");
	}
	
	function monthView() {
		$aEvents = $this->model->getEvents($_GET["category"], false, true);
		
		#### Beginning of MonthView Code ####
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
			$lStartOfDay = strtotime($sThisDay . "-" . $month . "-" . $year . " 12:00:00 am");
			$lEndOfDay = strtotime($sThisDay . "-" . $month . "-" . $year . " 11:59:59 pm");	

			$aDayEvents = array();
			if(count($aEvents) > 0)
				foreach($aEvents as &$aEvent) {
					if($aEvent["allday"] > 0) {
						$aEvent["datetime_start"] = strtotime(date("d-M-Y",$aEvent["datetime_start"]) . " 12:00:00 am");
						$aEvent["datetime_end"] = strtotime(date("d-M-Y",$aEvent["datetime_end"]) . " 12:00:00 pm");
					}
					if(!isset($aEvent["event_day_number"]))
						$aEvent["event_day_number"] = 0;


					$bPrintEvent = false;
					
					// Single Day Event...
					if( $aEvent["datetime_start"] >= $lStartOfDay && $aEvent["datetime_start"] <= $lEndOfDay ) {
						$bPrintEvent = true;
					}
					
					// Multiple Day Event...
					if( $aEvent["event_day_number"] > 0 && $aEvent["datetime_end"] >= $lStartOfDay ) {
						$bPrintEvent = true;
					}
/*					
					print "<hr />";
					print "\$lCurrentDay = '$lCurrentDay'<br />";					
					print "\$bPrintEvent = '$bPrintEvent'<br />";
					print "\$lStartOfDay = '$lStartOfDay' (" . date("M/d/Y", $lStartOfDay) . ")<br />";
					print "\$lEndOfDay = '$lEndOfDay' (" . date("M/d/Y", $lEndOfDay) . ")<br />";
					print "\$aEvent[\"datetime_start\"] = '" . $aEvent["datetime_start"] ."' (" . date("M/d/Y", $aEvent["datetime_start"]) . ")<br />";
					print "\$aEvent[\"datetime_end\"] = '" . $aEvent["datetime_end"] ."' (" . date("M/d/Y", $aEvent["datetime_end"]) . ")<br />";
*/				
					if($bPrintEvent) {
						$aEvent["event_day_number"]++;
						$aNewEvent[0] = $aEvent["title"];
						if($aEvent["event_day_number"] > 1)
							$sTitle = $aEvent["title"] . " (day " . $aEvent["event_day_number"] . ")";
						else
							$sTitle = $aEvent["title"];
						if(strlen($aEvent["title"]) > 30)
							$aNewEvent[3] = "<span title=\"" . $sTitle . "\">" . substr($sTitle, 0,30 ) . "...</span>";
						else
							$aNewEvent[3] = $sTitle;
						$aNewEvent[1] = $aEvent["id"];
						$aNewEvent[4] = $aEvent["url"];
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
			$lNextYear++;
			$lNextMonth = 1;
		}

		if(strlen($lLastMonth) == 1)
			$lLastMonth = "0" . $lLastMonth;	
			
		if(strlen($lNextMonth) == 1)
			$lNextMonth = "0" . $lNextMonth;
			
		$sNextMonthWord = date("F", strtotime("01-" . $lNextMonth . "-" . $lNextYear . " 12:00:01 am"));
		$sLastMonthWord = date("F", strtotime("01-" . $lLastMonth . "-" . $lLastYear . " 12:00:01 am"));

		$aNextMonth["url"] = "/calendar/month/$lNextYear/$lNextMonth";
		$aNextMonth["title"] = "$sNextMonthWord $lNextYear";
		$aLastMonth["url"] = "/calendar/month/$lLastYear/$lLastMonth";
		$aLastMonth["title"] = "$sLastMonthWord $lLastYear";
		
		$lToday = 0;
		if(date("m") == $month)
			$lToday = date("d") / 1;

		$this->tplAssign("sCurrentMonth", date("F Y", $date));
		$this->tplAssign("aNextMonth", $aNextMonth);
		$this->tplAssign("aLastMonth", $aLastMonth);
		$this->tplAssign("aCalendar", $aCalendar);
		$this->tplAssign("lToday", $lToday);
		$this->tplAssign("lNumWeeks", count($aCalendar) + 1);

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aEvents", $aEvents);
		
		$this->tplDisplay("month.php");
	}
	
	function ics() {
		$aEventPages = array_chunk($this->model->getEvents($_GET["category"]), 15);
		$aEvents = $aEventPages[0];

		$this->tplAssign("domain", $_SERVER["SERVER_NAME"]);
		$this->tplAssign("aEvents", $aEvents);
		
		$this->tplDisplay("ics.php");
	}
	function event() {
		$aEvent = $this->model->getEvent(null, $this->urlVars->dynamic["tag"], false, true);
		
		if(empty($aEvent))
			$this->error('404');
		
		$this->tplAssign("aEvent", $aEvent);
		
		if($this->tplExists("event-".$aEvent["id"].".php"))
			$this->tplDisplay("event-".$aEvent["id"].".php");
		else
			$this->tplDisplay("event.php");
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

		$this->tplDisplay("event_ics.php");
	}
}
