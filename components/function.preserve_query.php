<?php
function smarty_function_preserve_query($aParams, $oSmarty)
{
	$option = $aParams["option"];
	$value = $aParams["value"];
	$url = str_replace("?".$_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]);
	
	$keys = array_keys($_GET); 
	$str = $url.'?'.$option.'='.$value; 

	foreach($keys as $key=>$val)
	{
		$tmp[$val] = addslashes(strip_tags($_GET[$val])); 
		if($tmp[$val] != '' && $option != $val)
			$str .= '&'.$val.'='.$tmp[$val];
	}

	echo $str; 
}