<?php
class galleries_model extends appModel
{
	public $useCategories = true;
	public $perPage = 5;
	
	function getGalleries($sCategory = null, $sAll = false) {
		$sWhere = " WHERE `galleries`.`id` > 0";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			
		if($sAll == false)	
			$sWhere = " AND `galleries`.`active` = 1";
		
		// Get all gallerys for paging
		$aGalleries = $this->dbQuery(
			"SELECT `galleries`.* FROM `{dbPrefix}galleries` AS `galleries`"
				." LEFT JOIN `{dbPrefix}galleries_categories_assign` AS `galleries_assign` ON `galleries`.`id` = `galleries_assign`.`galleryid`"
				." LEFT JOIN `{dbPrefix}galleries_categories` AS `categories` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `galleries`.`sort_order`"
			,"all"
		);
		
		foreach($aGalleries as $x => &$aGallery)
			$aGallery = $this->_getGalleryInfo($aGallery);
		
		return $aGalleries;
	}
	function getGallery($sId) {
		$aGallery = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(!empty($aGallery))
			$aGallery = $this->_getGalleryInfo($aGallery);
		
		return $aGallery;
	}
	private function _getGalleryInfo($aGallery) {
		$aGallery["name"] = htmlspecialchars(stripslashes($aGallery["name"]));
		$aGallery["description"] = nl2br(htmlspecialchars(stripslashes($aGallery["description"])));
		
		$aGallery["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
			,"all"
		);
		
		foreach($aGallery["categories"] as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		$aGallery["photo"] = $this->dbQuery(
			"SELECT `photo` FROM `{dbPrefix}galleries_photos`"
				." WHERE `galleryid` = ".$aGallery["id"]
				." AND `gallery_default` = 1"
			,"one"
		);
		
		$aGallery["photos"] = count($this->getPhotos($aGallery["id"]));
		
		return $aGallery;
	}
	function getPhotos($sId) {
		$aPhotos = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_photos`"
				." WHERE `galleryid` = ".$this->dbQuote($sId, "integer")
				." ORDER BY `sort_order`"
			,"all"
		);
		
		return $aPhotos;
	}
	function getPhoto($sId, $sDefault = false) {
		if($sDefault == true)
			$sWhere = " WHERE `gallery_default` = 1";
		else
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		
		$aPhoto = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_photos`"
				.$sWhere
			,"row"
		);
		
		return $aPhoto;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory)
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
	function getMaxSort() {
		$sMaxSort = $this->dbQuery(
			"SELECT MAX(`sort_order`) FROM `{dbPrefix}galleries`"
			,"one"
		);
		
		return $sMaxSort;
	}
}