<?php
function smarty_function_getContent($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	
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
	
	$aContent["title"] = htmlspecialchars(stripslashes($aContent["title"]));
	$aContent["content"] = stripslashes($aContent["content"]);
	
	if(empty($aParams["assign"]))
		return $aContent["content"];
	else
		$oApp->tplAssign($aParams["assign"], $aContent);
}