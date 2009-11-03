<?php
function smarty_function_getContent($aParams, &$oSmarty)
{
	$objDB = $oSmarty->get_registered_object("objDB");
	
	if(!empty($aParams["tag"]))
		$aContent = $objDB->query("SELECT * FROM `content` WHERE `tag` = ".$objDB->quote($aParams["tag"], "text"))->fetchRow();
	elseif(!empty($aParams["id"]))
		$aContent = $objDB->query("SELECT * FROM `content` WHERE `id` = ".$objDB->quote($aParams["id"], "integer"))->fetchRow();
	
	if(empty($aParams["var"]))
		return stripslashes($aContent["content"]);
	else
		$oSmarty->assign($aParams["var"], $aContent);
}