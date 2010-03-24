<?php
function smarty_function_currentMenu($aParams, &$oSmarty)
{
	$sVar = $aParams["var"];
	
	if($oSmarty->get_template_vars("menu") == $sVar)
		return " class=\"current\"";
}