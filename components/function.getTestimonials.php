<?php
function smarty_function_getTestimonials($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	
	$oTestimonial = $oApp->loadModel("testimonials");
	
	if(!empty($aParams["testimonial"])) {
		$aTestimonial = $oTestimonial->getTestimonials($aParams["testimonial"]);
		
		if(!empty($aParams["assign"]))
			$oSmarty->assign($aParams["assign"], $aTestimonial);
		else
			$oSmarty->assign("aTestimonial", $aTestimonial);
	} else {
		$aTestimonials = $oTestimonial->getTestimonials($aParams["category"], $aParams["random"]);
		
		if($aParams["limit"] == 1) {
			if(!empty($aParams["assign"]))
				$oSmarty->assign($aParams["assign"], $aTestimonials[0]);
			else
				$oSmarty->assign("aTestimonial", $aTestimonials[0]);
		} else {
			if(!empty($aParams["assign"]))
				$oSmarty->assign($aParams["assign"], $aTestimonials);
			else
				$oSmarty->assign("aTestimonials", $aTestimonials);
		}
	}
}