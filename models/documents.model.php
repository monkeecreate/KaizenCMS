<?php
class documents_model extends appModel
{
	public $perPage = 5;
	
	function getDocuments($sCategory)
	{
		$sWhere = " WHERE `documents`.`active` = 1";
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		// Get all documents for paging
		$aDocuments = $this->dbResults(
			"SELECT `documents`.* FROM `documents` AS `documents`"
				." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`"
				." INNER JOIN `documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `documents`.`id`"
				." ORDER BY `documents`.`created_datetime` DESC"
			,"model->documents->getDocuments"
			,"all"
		);
		
		foreach($aDocuments as $x => $aDocument)
		{
			$aDocumentCategories = $this->dbResults(
				"SELECT `name` FROM `documents_categories` AS `categories`"
					." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents_assign`.`categoryid` = `categories`.`id`"
					." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
				,"model->documents->getDocuments->document_categories"
				,"col"
			);
		
			$aDocuments[$x]["categories"] = implode(", ", $aDocumentCategories);
		}
		
		return $aDocuments;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"model->documents->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
}