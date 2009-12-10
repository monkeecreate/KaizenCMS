<?php
function smarty_function_getPromo($aParams, &$oSmarty)
{
	$oApp = $oSmarty->get_registered_object("appController");
	
	$oPromo = $oApp->loadModel("promos");
	$aPromo = $oPromo->getPromo($aParams["tag"]);
	
	if(!empty($aPromo))
	{
		$aPosition = $oPromo->getPosition($aParams["tag"]);
		
		if(!empty($aPromo["link"]))
			echo "<a href=\"/promos/".$aPromo["id"]."/\">";
		
		echo "<img src=\"/uploads/promos/".$aPromo["promo"]."\" style=\"width:".$aPosition["promo_width"]."px;height:".$aPosition["promo_height"]."px;\" />";
		
		if(!empty($aPromo["link"]))
			echo "</a>";
	}
}