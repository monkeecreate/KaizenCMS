<?php
function formatDateTime($sTimestamp, $sDivider = " - ", $sFormatDate = "", $sFormatTime = "") {
	global $oApp;

	if(empty($sFormatDate)) {
		$sFormatDate = $oApp->settings->formatDate;
	}

	if(empty($sFormatTime)) {
		$sFormatTime = $oApp->settings->formatTime;
	}

	return date($sFormatDate.$sDivider.$sFormatTime, $sTimestamp);
}
