<?php
function smarty_function_event_time($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
	if(!empty($aParams["formatDate"])) {
		$sFormatDate = $aParams["formatDate"];
	} else {
		$sFormatDate = $oApp->settings->formatDate;
	}
	
	if(!empty($aParams["formatTime"])) {
		$sFormatTime = $aParams["formatTime"];
	} else {
		$sFormatTime = $oApp->settings->formatTime;
	}
	
	if($aParams["allday"] == 1) {
		// Event is all day, don't ever show the time
		
		if(date("d", $aParams["start"]) != date("d", $aParams["end"])) {
			// Start and end dates are different
			// Show a date range
			
			return date($sFormatDate, $aParams["start"])." - ".date($sFormatDate, $aParams["end"]);
		} else {
			// Start and end date are not different
			// Show only the date
			
			return date($sFormatDate, $aParams["start"]);
		}
	
	} else {
		// Event is not all day, so we need to figure out how to show the time
		
		if(date("d", $aParams["start"]) != date("d", $aParams["end"])) {
			// Start and end dates are different
			// Show two sets of date and time
			
			return date($sFormatDate." ".$sFormatTime, $aParams["start"])." - ".date($sFormatDate." ".$sFormatTime, $aParams["end"]);
		} else {
			// Start and end are not different
			
			$sDateString = date($sFormatDate." ".$sFormatTime, $aParams["start"]);
			
			// If start and end times are different, add end time to create time range
			if(date("g:i a", $aParams["start"]) == date("g:i a", $aParams["end"])) {
				$sDateString .= " - ".date($sFormatTime, $aParams["end"]);
			}
			
			return $sDateString;
		}
	}
}