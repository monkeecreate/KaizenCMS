<?php
function smarty_function_flickr($aParams, &$oSmarty) {
	$oApp = $oSmarty->get_registered_object("appController");
	
	$flickrKey = "69f31081cc7123755564c66ae0af159c";
	$flickrEmail = $oApp->getSetting("flickrEmail");
	$flickrAPI = 'http://api.flickr.com/services/rest/?&api_key='.$flickrKey.'&format=php_serial';
	
	// get the users flickr id to be used by the api
	$getFlickrId = unserialize(file_get_contents($flickrAPI."&method=flickr.people.findByEmail&find_email=".$flickrEmail));
	$flickrId = $getFlickrId["user"]["nsid"];
	if(empty($flickrId)) {
		echo "Your flickr email is invalid or hasn't been set.<br />";
	}
	
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
	if ($aParams["method"] == "photoStream") {
		$flickrAPI .= '&method=flickr.people.getPublicPhotos&user_id='.$flickrId;
		if (!empty($aParams["number"]))
			$flickrAPI .= '&per_page='.$aParams["number"];

		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['photos']['photo'] as $flickrPhoto) {
				if ($aParams["title"] == true) {
					echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'" title="'.$flickrPhoto['title'].'" class="flickrTitle">'.$flickrPhoto['title'].'</a>';
					echo '<img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg" alt="'.$flickrPhoto['title'].'" class="flickrPhoto">';
				} else {
					echo '<a href="http://www.flickr.com/photos/'.$flickrPhoto['owner'].'/'.$flickrPhoto['id'].'" title="'.$flickrPhoto['title'].'"><img src="http://farm'.$flickrPhoto['farm'].'.static.flickr.com/'.$flickrPhoto['server'].'/'.$flickrPhoto['id'].'_'.$flickrPhoto['secret'].'_'.$flickrSize.'.jpg" alt="'.$flickrPhoto['title'].'" class="flickrPhoto"></a>';
				}
			}
		} else {

			echo "Could not retrieve photos from flickr. Please try again.";
		}
	}
	
	## photoSets (Returns the photosets belonging to the specified user.)
	## params: size, title (optional, display photo title, true/false)
	## example: {flickr method=photoSets}
	if ($aParams["method"] == "photoSets") {
		$flickrAPI .= '&method=flickr.photosets.getList&user_id='.$flickrId;
		
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
	
	## photoCollections (Returns the collections belonging to the specified user.)
	## params: title (optional, display photo title, true/false)
	## example: {flickr method=photoCollections}
	if ($aParams["method"] == "photoCollections") {
		$flickrAPI .= '&method=flickr.collections.getTree&user_id='.$flickrId;
		
		$rsp = file_get_contents($flickrAPI);
		$rsp_obj = unserialize($rsp);

		if ($rsp_obj['stat'] == 'ok') {
			foreach ($rsp_obj['collections']['collection'] as $flickrCollection) {	
				$collectionId = explode("-", $flickrCollection['id']);
				echo '<article>';
				echo '<figure>';
				echo '<a href="http://www.flickr.com/photos/'.$flickrId.'/collections/'.$collectionId[1].'/" title="'.$flickrCollection['title'].'" target="_blank"><img src="'.$flickrCollection["iconlarge"].'" alt="'.$flickrCollection['title'].'" class="flickrPhoto"></a>';
				echo '</figure>';
				if ($aParams["title"] == true)
					echo '<h3><a href="http://www.flickr.com/photos/'.$flickrId.'/collections/'.$collectionId[1].'/" title="'.$flickrSet['title'].'" class="flickrTitle" target="_blank">'.$flickrCollection['title'].'</a></h3>';
				echo '</article>';
			}
		} else {

			echo "Could not retrieve collections from flickr. Please try again.";
		}
	}
	
	## photoSearch (Return a list of photos matching some criteria.)
	## params: size, tags (required, to search by), number (optional, per page limit), user (optional, true or false)
	## example: {flickr method=photoSearch user=true number=6 size=2 tags=snow,winter}
	if ($aParams["method"] == "photoSearch") {
		$flickrAPI .= '&method=flickr.photos.search&tags='.$aParams["tags"];
		if (!empty($aParams["number"]))
			$flickrAPI .= '&per_page='.$aParams["number"];
		if ($aParams["user"] == true || $aParams["user"] == t)
			$flickrAPI .= '&user_id='.$flickrId;
		
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