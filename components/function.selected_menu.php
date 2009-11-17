<?php
function smarty_function_selected_menu($aParams, &$oSmarty)
{
	$sVar = $aParams["var"];
	
	if($oSmarty->get_template_vars("menu") == $sVar)
		return " class=\"selected\"";
}