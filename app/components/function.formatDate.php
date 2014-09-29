<?php
function formatDate($sTimestamp, $sFormatDate = "") {
	global $oApp;

	if(empty($sFormatDate)) {
		$sFormatDate = $oApp->settings->formatDate;
	}

	return date($sFormatDate, $sTimestamp);
}
