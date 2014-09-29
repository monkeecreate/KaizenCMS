<?php
function smarty_function_getPost($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oPosts = $oApp->loadModel("posts");
	$aPost = $oPosts->getPost($aParams["id"]);
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aPost);
	else
		$oSmarty->assign("aPost", $aPost);
}