<?php
class documents extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"documents->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `documents`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->_db->quote($_GET["category"], "integer");
		
		// Get all documents for paging
		$aDocuments = $this->db_results(
			"SELECT `documents`.* FROM `documents` AS `documents`"
				." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`"
				." INNER JOIN `documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `documents`.`id`"
			,"documents->all_documents_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aDocuments), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aDocuments = $this->db_results(
			"SELECT `documents`.* FROM `documents` AS `documents`"
				." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`"
				." INNER JOIN `documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `documents`.`id`"
				." ORDER BY `documents`.`name`"
				." LIMIT ".$start.",".$sPerPage
			,"documents->current_page"
			,"all"
		);
	
		foreach($aDocuments as $x => $aDocument)
		{
			/*# Categories #*/
			$aDocumentCategories = $this->db_results(
				"SELECT `name` FROM `documents_categories` AS `categories`"
					." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents_assign`.`categoryid` = `categories`.`id`"
					." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
				,"documents->document_categories"
				,"col"
			);
		
			$aDocuments[$x]["categories"] = implode(", ", $aDocumentCategories);
			/*# Categories #*/
		}

		$this->_smarty->assign("aCategories", $aCategories);
		$this->_smarty->assign("aDocuments", $aDocuments);
		$this->_smarty->assign("aPaging", $oPage->build_array());
		
		$this->_smarty->display("documents.tpl");
	}
}