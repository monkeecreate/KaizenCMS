<?php
class links_model extends appModel
{
	public $perPage = 5;
	
	function getLinks($sCategory)
	{
		$sWhere = " WHERE `links`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($_GET["category"], "integer");
		
		// Get all links for paging
		$aLinks = $this->dbResults(
			"SELECT `links`.* FROM `links` AS `links`"
				." INNER JOIN `links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." INNER JOIN `links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
			,"all"
		);
	
		foreach($aLinks as $x => $aLink)
		{
			$aLinkCategories = $this->dbResults(
				"SELECT `name` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"col"
			);
		
			$aLinks[$x]["categories"] = implode(", ", $aLinkCategories);
		}
		
		return $aLinks;
	}
	function getLink($sId)
	{
		$aLink = $this->dbResults(
			"SELECT * FROM `links`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		return $aLink;
	}
	function getCategories($sEmpty = true)
	{
		if($sEmpty == true)
		{		
			$aCategories = $this->dbResults(
				"SELECT * FROM `links_categories`"
					." ORDER BY `name`"
				,"all"
			);
		}
		else
		{
			$aCategories = $this->dbResults(
				"SELECT * FROM `links_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory)
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null)
	{
		if(!empty($sId))
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		elseif(!empty($sName))
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		else
			return false;
		
		$aCategory = $this->dbResults(
			"SELECT * FROM `links_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}