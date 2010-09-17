<?php
function smarty_function_getSlides($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	$oSlideshow = $oApp->loadModel("slideshow");
	
	if(!empty($aParams["assign"]))
		$sAssign = $aParams["assign"];
	else
		$sAssign = "aSlides";
	
	$oSmarty->assign($sAssign, $oSlideshow->getSlides());
}