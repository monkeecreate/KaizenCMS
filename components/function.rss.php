<?php
function smarty_function_rss($params, &$smarty)
{
	$oMemcache = $oSmarty->get_registered_object("memcache");
	
	$sFeed = $aParams["feed"];
	$memid = "rss_".md5($sFeed);
	
	if(!$aFeed = $oMemcache->get($memid))
	{
		$feed = file_get_contents($sFeed);
		$converter = new Xml2Array();
		$converter->setXml($feed);
		$aFeed = $converter->get_array(); 
		
		$oMemcache->set($memid, $aFeed, false, strtotime("+".$aParams["cache_time"]." minutes"));
	}
	
	$oSmarty->assign("feed", $aFeed);
}