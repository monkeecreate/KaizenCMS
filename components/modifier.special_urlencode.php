<?php
function smarty_modifier_special_urlencode($sURL)
{
	$sURL = strtolower(str_replace("--","-",preg_replace("/([^a-z0-9_-]+)/i", "", str_replace(" ","-",trim($sURL)))));
	
	if(strlen($sURL) > 50)
		return substr($sURL, 0, 50)."...";
	else
		return $sURL;
}