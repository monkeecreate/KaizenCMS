<?php
class galleries_model extends appModel {
	public $imageFolder;
	public $useCategories;
	public $perPage;
	public $sortCategory;
	
	function __construct() {
		parent::__construct();
		
		include(dirname(__file__)."/config.php");
		
		foreach($aPluginInfo["config"] as $sKey => $sValue) {
			$this->$sKey = $sValue;
		}
	}
	
	function getGalleries($sCategory = null, $sAll = false) {
		$sWhere = " WHERE `galleries`.`id` > 0";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			
		if($sAll == false) {
			$sWhere .= " AND `galleries`.`active` = 1";
			$sPhotos = " INNER JOIN `{dbPrefix}galleries_photos` AS `photos` ON `galleries`.`id` = `photos`.`galleryid`";
		}
		
		// Get all gallerys for paging
		$aGalleries = $this->dbQuery(
			"SELECT `galleries`.* FROM `{dbPrefix}galleries` AS `galleries`"
				." LEFT JOIN `{dbPrefix}galleries_categories_assign` AS `galleries_assign` ON `galleries`.`id` = `galleries_assign`.`galleryid`"
				." LEFT JOIN `{dbPrefix}galleries_categories` AS `categories` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				.$sPhotos
				.$sWhere
				." GROUP BY `galleries`.`id`"
				." ORDER BY `galleries`.`sort_order`"
			,"all"
		);
		
		foreach($aGalleries as $x => &$aGallery)
			$aGallery = $this->_getGalleryInfo($aGallery);
		
		return $aGalleries;
	}
	function getGallery($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `galleries`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `galleries`.`tag` = ".$this->dbQuote($sTag, "text");
			
		if($sAll == false) {
			$sWhere .= " AND `galleries`.`active` = 1";
		}
	
		$aGallery = $this->dbQuery(
			"SELECT `galleries`.* FROM `{dbPrefix}galleries` AS `galleries`"
			.$sWhere
			,"row"
		);
		
		$aGallery = $this->_getGalleryInfo($aGallery);
		
		return $aGallery;
	}
	private function _getGalleryInfo($aGallery) {
		if(!empty($aGallery)) {
			$aGallery["name"] = htmlspecialchars(stripslashes($aGallery["name"]));
			$aGallery["description"] = nl2br(htmlspecialchars(stripslashes($aGallery["description"])));
			$aGallery["url"] = "/galleries/".$aGallery["tag"]."/";
		
			$aGallery["categories"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `categories`.`id`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
				,"all"
			);
		
			foreach($aGallery["categories"] as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		
			$aGallery["defaultPhoto"] = $this->dbQuery(
				"SELECT `photo` FROM `{dbPrefix}galleries_photos`"
					." WHERE `galleryid` = ".$aGallery["id"]
					." AND `gallery_default` = 1"
				,"one"
			);
		
			$aGallery["photos"] = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}galleries_photos`"
					." WHERE `galleryid` = ".$this->dbQuote($aGallery["id"], "integer")
					." ORDER BY `sort_order`"
				,"all"
			);
		
			foreach($aGallery["photos"] as &$aPhoto) {
				$aPhoto["title"] = htmlspecialchars(stripslashes($aPhoto["title"]));
				$aPhoto["description"] = nl2br(htmlspecialchars(stripslashes($aPhoto["description"])));
			}
		}
		
		return $aGallery;
	}
	function getURL($sID) {
		$aGallery = $this->getGallery($sID);
		
		return $aGallery["url"];
	}
	function getPhoto($sId, $sDefault = false) {
		if($sDefault == true) {
			$sWhere = " WHERE `gallery_default` = 1";
			$sWhere .= " AND `galleryid` = ".$sId;
		} else
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		
		$aPhoto = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}galleries_photos`"
				.$sWhere
			,"row"
		);
		
		$aPhoto["title"] = htmlspecialchars(stripslashes($aPhoto["title"]));
		$aPhoto["description"] = nl2br(htmlspecialchars(stripslashes($aPhoto["description"])));
		
		return $aPhoto;
	}
	function getCategories($sEmpty = true) {
		$sJoin = "";
		
		if($sEmpty == false) {		
			$sJoin .= " INNER JOIN `{dbPrefix}galleries_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		} else {
			$sJoin .= " LEFT JOIN `{dbPrefix}galleries_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`";
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sortCategory));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
		
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sortCategory))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "items":
				$sOrderBy = " ORDER BY `items` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		$aCategories = $this->dbQuery(
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}galleries_categories` AS `categories`"
				.$sJoin
				." GROUP BY `id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aCategories as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
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
			"SELECT `id`, `name`, `sort_order`, COUNT('categoryid') AS `items` FROM `{dbPrefix}galleries_categories` AS `categories`"
				." LEFT JOIN `{dbPrefix}galleries_categories_assign` AS `assign` ON `categories`.`id` = `assign`.`categoryid`"
				.$sWhere
			,"row"
		);
		
		if(!empty($aCategory)) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
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