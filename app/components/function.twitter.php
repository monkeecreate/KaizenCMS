<?php
function twitter($aParams) {
	$sUser = $aParams["user"];
	$memid = "twitter_".$sUser;

	$timeline = file_get_contents("http://twitter.com/statuses/user_timeline.xml?screen_name=".$sUser);
	$converter = new Xml2Array();
	$converter->setXml($timeline);
	$aTimeline = $converter->get_array();

	return $aTimeline["statuses"]["status"];
}
