<?php
function smarty_function_getBanner($aParams, &$oSmarty) {
	$oApp = $oSmarty->getRegisteredObject("appController");
	$aDisplayedBanners = $oSmarty->getTemplateVars("aDisplayedBanners");

	if(empty($aDisplayedBanners))
		$aDisplayedBanners = array();

	$oBanner = $oApp->loadModel("banners");
	$aBanner = $oBanner->getBanner($aParams["tag"], null, implode(",", $aDisplayedBanners));

	if(!empty($aBanner)) {
		$aDisplayedBanners[] = $aBanner["id"];
		$oSmarty->assign("aDisplayedBanners", $aDisplayedBanners);

		$aPosition = $oBanner->getPosition($aParams["tag"]);

		if(!empty($aBanner["link"]))
			echo "<a href=\"/banners/".$aBanner["id"]."/\" class=\"".$aParams["tag"]." banner\">";

		echo "<img src=\"".$oBanner->imageFolder.$aBanner["banner"]."?v=".$aBanner["updated_datetime"]."\" style=\"width:".$aPosition["banner_width"]."px;height:".$aPosition["banner_height"]."px;\" class=\"".$aParams["tag"]." banner\">";

		if(!empty($aBanner["link"]))
			echo "</a>";
	}
}