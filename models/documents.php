<?php
class documents extends appModel
{
	public $allowedExt = array();//array("pdf","doc");
	public $documentFolder = "/uploads/documents/";
	public $perPage = 5;
	
	function getDocuments($sCategory, $sAll = false)
	{
		// Start the WHERE
		$sWhere = " WHERE `documents`.`id` > 0";// Allways true
		
		if($sAll == false)
			$sWhere .= " AND `documents`.`active` = 1";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		$aDocuments = $this->dbResults(
			"SELECT `documents`.* FROM `documents` AS `documents`"
				." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`"
				." INNER JOIN `documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `documents`.`id`"
				." ORDER BY `documents`.`created_datetime` DESC"
			,"all"
		);
		
		foreach($aDocuments as $x => $aDocument)
			$aDocuments[$x] = $this->getDocumentInfo($aDocument);
		
		return $aDocuments;
	}
	function getDocument($sId)
	{
		$aDocument = $this->dbResults(
			"SELECT `documents`.* FROM `documents` AS `documents`"
				." WHERE `documents`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `documents`.`active` = 1"
			,"row"
		);
	}
	function getDocumentInfo($aDocument)
	{
		$aCategories = $this->dbResults(
			"SELECT `name` FROM `documents_categories` AS `categories`"
				." INNER JOIN `documents_categories_assign` AS `documents_assign` ON `documents_assign`.`categoryid` = `categories`.`id`"
				." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
			,"col"
		);
	
		$aDocument["categories"] = implode(", ", $aCategories);
		
		return $aDocument;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `documents_categories`"
				." ORDER BY `name`"
			,"all"
		);
		
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
			"SELECT * FROM `documents_categories`"
				.$sWhere
				." LIMIT 1"
			,"all"
		);
		
		return $aCategory;
	}
}