<?php
function smarty_function_getPostsCategories($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$oPosts = $oApp->loadModel("posts");
	$aCategories = $oPosts->getCategories();
	
	if(!empty($aParams["assign"]))
		$oSmarty->assign($aParams["assign"], $aCategories);
	else
		$oSmarty->assign("aCategories", $aCategories);
}
