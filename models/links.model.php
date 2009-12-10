<?php
class links_model extends appModel
{
	public $perPage = 5;
	
	function getLinks($sCategory)
	{
		$sWhere = " WHERE `links`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		// Get all links for paging
		$aLinks = $this->db_results(
			"SELECT `links`.* FROM `links` AS `links`"
				." INNER JOIN `links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." INNER JOIN `links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
			,"model->links->getLinks"
			,"all"
		);
	
		foreach($aLinks as $x => $aLink)
		{
			$aLinkCategories = $this->db_results(
				"SELECT `name` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"model->links->getLinks->link_categories"
				,"col"
			);
		
			$aLinks[$x]["categories"] = implode(", ", $aLinkCategories);
		}
		
		return $aLinks;
	}
	function getCategories()
	{
		$aCategories = $this->db_results(
			"SELECT * FROM `links_categories`"
				." ORDER BY `name`"
			,"model->links->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
}