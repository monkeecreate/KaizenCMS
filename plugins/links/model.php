<?php
class links_model extends appModel
{
	public $useImage = true;
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $imageFolder = "/uploads/links/";
	public $useCategories = true;
	public $perPage = 5;
	
	function getLinks($sCategory = null, $sAll = false, $sRandom = false) {
		// Start the WHERE
		$sWhere = " WHERE `links`.`id` > 0";// Allways true
		
		if($sAll == false)	
			$sWhere = " AND `links`.`active` = 1";
			
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			
		if($sRandom != false)
			$sOrderBy = " ORDER BY rand()";
		
		// Get all links for paging
		$aLinks = $this->dbQuery(
			"SELECT `links`.* FROM `{dbPrefix}links` AS `links`"
				." LEFT JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." LEFT JOIN `{dbPrefix}links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aLinks as $x => &$aLink)
			$aLink = $this->_getLinkInfo($aLink);
		
		return $aLinks;
	}
	function getLink($sId) {
		$aLink = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(!empty($aLink))
			$aLink = $this->_getLinkInfo($aLink);
		
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
		 && $this->useImage == true)
			$aLink["image"] = 1;
		else
			$aLink["image"] = 0;
		
		return $aLink;
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