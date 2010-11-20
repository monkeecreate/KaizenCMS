<?php
function smarty_modifier_formatDate($sTimestamp, $sFormatDate = "") {
	global $oApp;
	
	if(empty($sFormatDate)) {
		$sFormatDate = $oApp->settings->formatDate;
	}
	
	return date($sFormatDate, $sTimestamp);
}