<?php
function getFeed($feed) {
  global $oApp;

	require_once $oApp->settings->root.'helpers/Xml2Array.php';

	$sFeed = $feed;

	$feed = file_get_contents($sFeed);
	$converter = new Xml2Array();
	$converter->setXml($feed);
	$aFeed = $converter->get_array();

	return $aFeed;
}
