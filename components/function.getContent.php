<?php
function smarty_function_getContent($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	
	if(!empty($aParams["tag"]))
		$aContent = $oApp->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." WHERE `tag` = ".$oApp->dbQuote($aParams["tag"], "text")
			,"row"
		);
	elseif(!empty($aParams["id"]))
		$aContent = $oApp->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." WHERE `id` = ".$oApp->dbQuote($aParams["id"], "text")
			,"row"
		);
	
	if(empty($aParams["var"]))
		return stripslashes($aContent["content"]);
	else
		$oApp->tplAssign($aParams["var"], $aContent);
}