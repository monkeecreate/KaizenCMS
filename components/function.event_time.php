<?php
function smarty_function_event_time($aParams, &$oSmarty) {
	if($aParams["allday"] == 1) {
		if(date("d", $aParams["start"]) != date("d", $aParams["end"]))
			return date("m/d/y", $aParams["start"])." - ".date("m/d/y", $aParams["end"]);
		else
			return date("m/d/y", $aParams["start"]);
	} else {
		if(date("d", $aParams["start"]) != date("d", $aParams["end"]))
			return date("m/d/y g:i a", $aParams["start"])." - ".date("m/d/y g:i a", $aParams["end"]);
		else
			return date("m/d/y g:i a", $aParams["start"])." - ".date("g:i a", $aParams["end"]);
	}
}