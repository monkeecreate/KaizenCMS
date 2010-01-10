<?php
function smarty_function_flickr($aParams, &$oSmarty)
{
	$flickrKey = "69f31081cc7123755564c66ae0af159c";
	$flickrUser = "32609765@N00"; // move to site settings
	
	switch($aParams["size"]):
		case '1': $flickrSize = "s"; break;
		case '2': $flickrSize = "t"; break;
		case '3': $flickrSize = "m"; break;
		case '4': $flickrSize = "o"; break;
		default: $flickrSize = "t"; break;
	endswitch;
	
	## photoStream
	## params: size (required), number (optional, per page limit)
	## example: {flickr method=photoSearch number=5 size=1}
	if ($aParams["method"] == "photoStream")
	{
		$flickrAPI = 'http://api.flickr.com/services/rest/?&method=flickr.people.getPublicPhotos&api_key='.$flickrKey.'&user_id='.$flickrUser.'&per_page='.$aParams["number"].'&format=php_serial';

		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photos']['photo'] as $flickrPhoto) {
				echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'">'.$flickrPhoto['title'].'</a><br />';
				echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg"><br /><br />';
		
			}
		} else {

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
	
	## photoSearch
	## params: size (required), tags (required, to search by), number (optional, per page limit), user (optional, true or false)
	## example: {flickr method=photoSearch user=true number=6 size=2 tags=snow,winter}
	if ($aParams["method"] == "photoSearch")
	{
		$flickrAPI = 'http://api.flickr.com/services/rest/?&method=flickr.photos.search&api_key='.$flickrKey.'&per_page='.$aParams["number"].'&tags='.$aParams["tags"].'&format=php_serial';
		if ($aParams["user"] == true || $aParams["user"] == t)
			$flickrAPI .= '&user_id='.$flickrUser;
		
		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photos']['photo'] as $flickrPhoto) {
				echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'">'.$flickrPhoto['title'].'</a><br />';
				echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg"><br /><br />';
		
			}
		} else {

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
}