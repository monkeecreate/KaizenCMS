<?php
function smarty_function_getDocuments($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	$oDocuments = $oApp->loadModel("documents");
	
	if(!empty($aParams["limit"])) {
		$aDocuments = array_chunk($oDocuments->getDocuments($aParams["category"], false, $aParams["random"]), $aParams["limit"]);
		$aDocuments = $aDocuments[0];
	} else
		$aDocuments = $oDocuments->getDocuments($aParams["category"], false, $aParams["random"]);
	
	$oApp->tplAssign("documentFolder", $oDocuments->documentFolder);
	
	if(empty($aParams["assign"]))
		$oApp->tplAssign("aDocuments", $aDocuments);
	else
		$oApp->tplAssign($aParams["assign"], $aDocuments);
}