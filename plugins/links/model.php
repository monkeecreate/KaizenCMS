<?php
class links_model extends appModel
{
	public $useImage = true;
	// set MinWidth and MinHeight to 0 to not force min diminsions
	public $imageMinWidth = 0;
	public $imageMinHeight = 0;
	public $imageFolder = "/uploads/links/";
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
				." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." INNER JOIN `{dbPrefix}links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aLinks as $x => $aLink) {
			$aLinks[$x]["categories"] = $this->dbQuery(
				"SELECT `name` FROM `{dbPrefix}links_categories` AS `categories`"
					." INNER JOIN `{dbPrefix}links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"col"
			);
		}
		
		return $aLinks;
	}
	function getLink($sId) {
		$aLink = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}links`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		return $aLink;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}links_categories`"
					." ORDER BY `name`"
				,"all"
			);
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
		
		return $aCategory;
	}
}