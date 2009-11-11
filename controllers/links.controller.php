<?php
class links extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `links_categories`"
				." ORDER BY `name`"
			,"links->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
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
			,"links->all_links_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aLinks), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aLinks = $this->db_results(
			"SELECT `links`.* FROM `links` AS `links`"
				." INNER JOIN `links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." INNER JOIN `links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
				." ORDER BY `links`.`name`"
				." LIMIT ".$start.",".$sPerPage
			,"links->current_page"
			,"all"
		);
	
		foreach($aLinks as $x => $aLink)
		{
			/*# Categories #*/
			$aLinkCategories = $this->db_results(
				"SELECT `name` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"links->link_categories"
				,"col"
			);
		
			$aLinks[$x]["categories"] = implode(", ", $aLinkCategories);
			/*# Categories #*/
		}

		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_assign("aLinks", $aLinks);
		$this->tpl_assign("aPaging", $oPage->build_array());
		
		$this->tpl_display("links.tpl");
	}
}