<?php
class links_model extends appModel
{
	public $useImage = true;
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $imageFolder = "/uploads/links/";
	public $useCategories = true;
	public $perPage = 5;
	public $sort = "name-asc"; // manual, name, created, updated, random - asc, desc
	
	function getLinks($sCategory = null, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`links`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
		
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		// Get all links pased on filters given
		$aLinks = $this->dbQuery(
			"SELECT `links`.* FROM `{dbPrefix}links` AS `links`"
				.$sJoin
				.$sWhere
				." GROUP BY `links`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aLinks as $x => &$aLink) {
			$aLink = $this->_getLinkInfo($aLink);
		}
		
		return $aLinks;
	}
	function getLink($sId) {
		$aLink = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(!empty($aLink)) {
			$aLink = $this->_getLinkInfo($aLink);
		}
		
		return $aLink;
	}
	private function _getLinkInfo($aLink) {
		$aLink["name"] = htmlspecialchars(stripslashes($aLink["name"]));
		$aLink["description"] = nl2br(htmlspecialchars(stripslashes($aLink["description"])));
		
		$aLink["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
				." WHERE `links_assign`.`linkid` = ".$aLink["id"]
			,"all"
		);
		
		foreach($aLink["categories"] as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aLink["id"].".jpg")
		 && $aLink["photo_x2"] > 0
		 && $this->useImage == true) {
			$aLink["image"] = 1;
		} else {
			$aLink["image"] = 0;
		}
		
		return $aLink;
	}
	function getURL($sID) {
		$aLink = $this->getLink($sID);
		
		$sURL = "/links/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory) {
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
			}
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
	function getImage($sId) {
		$aLink = $this->getLink($sId);
		
		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aLink
		);
		
		return $aImage;
	}
}