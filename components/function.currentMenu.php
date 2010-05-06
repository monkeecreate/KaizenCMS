<?php
function smarty_function_currentMenu($aParams, &$oSmarty) {
	$aVar = explode(",", $aParams["var"]);

	if(in_array($oSmarty->get_template_vars("menu"), $aVar))
		return "current";
}