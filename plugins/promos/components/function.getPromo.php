<?php
function smarty_function_getPromo($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$aDisplayedPromos = $oSmarty->getTemplateVars("aDisplayedPromos");

	if(empty($aDisplayedPromos))
		$aDisplayedPromos = array();

	$oPromo = $oApp->loadModel("promos");
	$aPromo = $oPromo->getPromo($aParams["tag"], null, implode(",", $aDisplayedPromos));

	if(!empty($aPromo)) {
		$aDisplayedPromos[] = $aPromo["id"];
		$oSmarty->assign("aDisplayedPromos", $aDisplayedPromos);

		$aPosition = $oPromo->getPosition($aParams["tag"]);

		if(!empty($aPromo["link"]))
			echo "<a href=\"/promos/".$aPromo["id"]."/\" class=\"".$aParams["tag"]." promo\">";

		echo "<img src=\"".$oPromo->imageFolder.$aPromo["promo"]."?v=".$aPromo["updated_datetime"]."\" style=\"width:".$aPosition["promo_width"]."px;height:".$aPosition["promo_height"]."px;\" class=\"".$aParams["tag"]." promo\">";

		if(!empty($aPromo["link"]))
			echo "</a>";
	}
}