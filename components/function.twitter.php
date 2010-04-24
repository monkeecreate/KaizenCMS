<?php
function smarty_function_twitter($aParams, &$oSmarty) {
	$oMemcache = $oSmarty->get_registered_object("memcache");
	
	$sUser = $aParams["user"];
	$memid = "twitter_".$sUser;
	
	if(!$aTimeline = $oMemcache->get($memid)) {
		$timeline = file_get_contents("http://twitter.com/statuses/user_timeline.xml?screen_name=".$sUser);
		$converter = new Xml2Array();
		$converter->setXml($timeline);
		$aTimeline = $converter->get_array(); 
		
		$oMemcache->set($memid, $aTimeline, false, strtotime("+".$aParams["cache_time"]." minutes"));
	}
	
	$oSmarty->assign("twitter", $aTimeline["statuses"]["status"]);
}