<?php
function smarty_function_event_time($aParams, &$oSmarty) {
	if($aParams["allday"] == 1) {
		// Event is all day, don't ever show the time
		
		if(date("d", $aParams["start"]) != date("d", $aParams["end"])) {
			// Start and end dates are different
			// Show a date range
			
			return date("m/d/y", $aParams["start"])." - ".date("m/d/y", $aParams["end"]);
		} else {
			// Start and end date are not different
			// Show only the date
			
			return date("m/d/y", $aParams["start"]);
		}
	
	} else {
		// Event is not all day, so we need to figure out how to show the time
		
		if(date("d", $aParams["start"]) != date("d", $aParams["end"])) {
			// Start and end dates are different
			// Show two sets of date and time
			
			return date("m/d/y g:i a", $aParams["start"])." - ".date("m/d/y g:i a", $aParams["end"]);
		} else {
			// Start and end are not different
			
			$sDateString = date("m/d/y g:i a", $aParams["start"]);
			
			// If start and end times are different, add end time to create time range
			if(date("g:i a", $aParams["start"]) == date("g:i a", $aParams["end"])) {
				$sDateString .= " - ".date("g:i a", $aParams["end"]);
			}
			
			return $sDateString;
		}
	}
}