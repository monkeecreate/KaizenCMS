<?php
function smarty_function_sec2time($aParams, &$oSmarty)
{
	$sTime = $aParams["time"];
	
	if(is_numeric($sTime))
	{
		$aTime = array(
			"years" => 0, "days" => 0, "hours" => 0,
			"minutes" => 0, "seconds" => 0,
		);
		
		if($sTime >= 31556926){
			$aTime["years"] = floor($sTime/31556926);
			$sTime = ($sTime%31556926);
		}
		
		if($sTime >= 86400){
			$aTime["days"] = floor($sTime/86400);
			$sTime = ($sTime%86400);
		}
		
		if($sTime >= 3600){
			$aTime["hours"] = floor($sTime/3600);
			$sTime = ($sTime%3600);
		}
		
		if($sTime >= 60){
			$aTime["minutes"] = floor($sTime/60);
			$sTime = ($sTime%60);
		}
		
		$aTime["seconds"] = floor($time);
		
		return (array) $aTime;
	}else{
		return (bool) FALSE;
	}
}