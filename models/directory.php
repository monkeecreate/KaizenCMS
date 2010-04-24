<?php
class directory_model extends appModel
{
	public $useImage = true;
	public $imageFolder = "/uploads/directory/";
	public $perPage = 5;
	
	function getListings($sCategory, $sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `directory`.`id` > 0";// Allways true
		
		if($sAll == false)
			$sWhere .= " AND `directory`.`active` = 1";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aListings = $this->dbResults(
			"SELECT `directory`.* FROM `directory`"
				." INNER JOIN `directory_categories_assign` AS `directory_assign` ON `directory`.`id` = `directory_assign`.`listingid`"
				." INNER JOIN `directory_categories` AS `categories` ON `directory_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `directory`.`id`"
				." ORDER BY `directory`.`name`"
			,"all"
		);
	
		foreach($aListings as $x => $aListing)
			$aListings[$x] = $this->getListingInfo($aListing);
		
		return $aListings;
	}
	function getListingInfo($aListing) {
		$aCategories = $this->dbResults(
			"SELECT `name` FROM `directory_categories` AS `categories`"
				." INNER JOIN `directory_categories_assign` AS `directory_assign` ON `directory_assign`.`categoryid` = `categories`.`id`"
				." WHERE `directory_assign`.`listingid` = ".$aListing["id"]
			,"col"
		);
	
		$aListing["categories"] = implode(", ", $aCategories);
		
		if(file_exists($this->_settings->rootPublic.substr($this->imageFolder, 1).$aListing["file"])
		 && $this->useImage == true)
			$aListing["image"] = 1;
		else
			$aListing["image"] = 0;
			
		return $aListing;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbResults(
				"SELECT * FROM `directory_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbResults(
				"SELECT * FROM `directory_categories_assign`"
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
		
		$aCategory = $this->dbResults(
			"SELECT * FROM `directory_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}