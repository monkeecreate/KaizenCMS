<?php
function formatTime($sTimestamp, $sFormatTime = "") {
	global $oApp;

	if(empty($sFormatTime)) {
		$sFormatTime = $oApp->settings->formatTime;
	}

	return date($sFormatTime, $sTimestamp);
}
