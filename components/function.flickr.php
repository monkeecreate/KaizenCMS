<?php
function smarty_function_flickr($aParams, &$oSmarty)
{
	$flickrKey = "69f31081cc7123755564c66ae0af159c";
	$flickrUser = "32609765@N00"; // move to site settings
	$flickrAPI = 'http://api.flickr.com/services/rest/?&api_key='.$flickrKey.'&format=php_serial';
	
	switch($aParams["size"]):
		case '1': $flickrSize = "s"; break;
		case '2': $flickrSize = "t"; break;
		case '3': $flickrSize = "m"; break;
		case '4': $flickrSize = "o"; break;
		default: $flickrSize = "t"; break;
	endswitch;
	
	## photoStream (Get a list of public photos for the given user.)
	## params: size, number (optional, per page limit), title (optional, display photo title, true/false)
	## example: {flickr method=photoStream number=5 size=1 title=false}
	if ($aParams["method"] == "photoStream")
	{
		$flickrAPI .= '&method=flickr.people.getPublicPhotos&user_id='.$flickrUser;
		if (!empty($aParams["number"]))
			$flickrAPI .= '&per_page='.$aParams["number"];

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
		$flickrAPI .= '&method=flickr.photosets.getList&user_id='.$flickrUser;
		
		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photosets']['photoset'] as $flickrSet) {				
				if ($aParams["title"] == true) 
					echo '<a href="http://www.flickr.com/photos/'.$flickrUser.'/sets/'.$flickrSet['id'].'" title="'.$flickrSet['title']['_content'].'" class="flickrTitle">'.$flickrSet['title']['_content'].'</a> ('.$flickrSet['photos'].' photos/'.$flickrSet['videos'].' videos)';
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
		$flickrAPI .= '&method=flickr.photos.search&tags='.$aParams["tags"];
		if (!empty($aParams["number"]))
			$flickrAPI .= '&per_page='.$aParams["number"];
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