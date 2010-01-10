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
	
	## photoStream (Get a list of public photos for the given user.)
	## params: size, number (optional, per page limit), title (optional, display photo title, true/false)
	## example: {flickr method=photoSearch number=5 size=1}
	if ($aParams["method"] == "photoStream")
	{
		$flickrAPI = 'http://api.flickr.com/services/rest/?&method=flickr.people.getPublicPhotos&api_key='.$flickrKey.'&user_id='.$flickrUser.'&per_page='.$aParams["number"].'&format=php_serial';

		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photos']['photo'] as $flickrPhoto) {
				if ($aParams["title"] == true)
					echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'" title="'.$flickrPhoto['title'].'" class="flickrTitle">'.$flickrPhoto['title'].'</a>';
				echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg" alt="'.$flickrPhoto['title'].'" class="flickrPhoto">';
			}
		} else {

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
	
	## photoSets (Returns the photosets belonging to the specified user.)
	## params: size, title (optional, display photo title, true/false)
	## example: {flickr method=photoSets}
	if ($aParams["method"] == "photoSets")
	{
		$flickrAPI = 'http://api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key='.$flickrKey.'&user_id='.$flickrUser.'&format=php_serial';
		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photosets']['photoset'] as $flickrSet) {				
				if ($aParams["title"] == true) 
				{
					echo '<a href="http://www.flickr.com/photos/'.$flickrUser.'/sets/'.$flickrSet['id'].'" title="'.$flickrSet['title']['_content'].'" class="flickrTitle">'.$flickrSet['title']['_content'].'</a> ('.$flickrSet['photos'].' photos/'.$flickrSet['videos'].' videos)';
				}
				echo '<img src="http://farm'.$flickrSet['farm'].'.static.flickr.com/'.$flickrSet['server'].'/'.$flickrSet['primary'].'_'.$flickrSet['secret'].'_'.$flickrSize.'.jpg" alt="'.$flickrSet['title']['_content'].'" class="flickrPhoto">';
			}
		} else {

			echo "Could not retrieve groups from flickr. Please try again.";
		}
	}
	
	## photoSearch (Return a list of photos matching some criteria.)
	## params: size, tags (required, to search by), number (optional, per page limit), user (optional, true or false)
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
				echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'" title="'.$flickrPhoto['title'].'" class="flickrTitle">'.$flickrPhoto['title'].'</a>';
				echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg" alt="'.$flickrPhoto['title'].'" class="flickrPhoto">';
		
			}
		} else {

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
}