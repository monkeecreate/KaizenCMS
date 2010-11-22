<?php
function smarty_modifier_formatTime($sTimestamp, $sFormatTime = "") {
	global $oApp;
	
	if(empty($sFormatTime)) {
		$sFormatTime = $oApp->settings->formatTime;
	}
	
	return date($sFormatTime, $sTimestamp);
}