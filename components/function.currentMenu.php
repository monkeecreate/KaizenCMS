<?php
function smarty_function_currentMenu($aParams, &$oSmarty) {
	$aVar = explode(",", $aParams["var"]);

	if(in_array($oSmarty->getTemplateVars("menu"), $aVar))
		return "current";
}