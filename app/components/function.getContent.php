<?php
function getContent($id, $tag) {
	global $oApp;

	if(!empty($tag))
		$aContent = $oApp->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." WHERE `tag` = ".$oApp->dbQuote($tag, "text")
			,"row"
		);
	elseif(!empty($id))
		$aContent = $oApp->dbQuery(
			"SELECT * FROM `{dbPrefix}content`"
				." WHERE `id` = ".$oApp->dbQuote($id, "text")
			,"row"
		);

	$aContent["title"] = htmlspecialchars(stripslashes($aContent["title"]));
	$aContent["content"] = stripslashes($aContent["content"]);

	return $aContent["content"];
}
