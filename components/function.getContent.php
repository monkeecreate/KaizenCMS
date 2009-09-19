<?php
function smarty_function_getContent($aParams, &$oSmarty)
{
	$objDB = $oSmarty->get_registered_object("objDB");
	
	$sContent = $objDB->query("SELECT `content` FROM `content` WHERE `id` = ".$objDB->quote($aParams["id"], "integer"))->fetchOne();
	
	return $sContent;
}