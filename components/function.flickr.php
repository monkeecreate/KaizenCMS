<?php
function smarty_function_flickr($aParams, &$oSmarty)
{
	if ($aParams["size"] == 1)
		$flickrSize = "s"; //small square
	elseif ($aParams["size"] == 2)
		$flickrSize = "t"; // thumbnail
	elseif ($aParams["size"] == 3)
		$flickrSize = "m"; // small
	elseif ($aParams["size"] == 4)
		$flickrSize = "o"; // original (not always available)
	else
		$flickrSize = "t";
	
	if ($aParams["method"] == "photoStream")
	{
		$flickrAPI = 'http://api.flickr.com/services/rest/?&method=flickr.people.getPublicPhotos&api_key=69f31081cc7123755564c66ae0af159c&user_id=32609765@N00&per_page='.$aParams["number"].'&format=php_serial';

		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok'){

			foreach ($rsp_obj['photos']['photo'] as $flickrPhoto) {
				echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'">'.$flickrPhoto['title'].'</a><br />';
				echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg"><br /><br />';
		
			}
		}else{

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
}