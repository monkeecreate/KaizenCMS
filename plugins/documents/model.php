<?php
class documents_model extends appModel
{
	public $allowedExt = array();//array("pdf","doc");
	public $documentFolder = "/uploads/documents/";
	public $useCategories = true;
	public $perPage = 5;
	
	function getDocuments($sCategory, $sAll = false, $sRandom = false) {
		// Start the WHERE
		$sWhere = " WHERE `documents`.`id` > 0";// Allways true
		
		if($sAll == false)
			$sWhere .= " AND `documents`.`active` = 1";
		
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			
		if($sRandom != false)
			$sOrderBy = " ORDER BY rand()";
		else
			$sOrderBy = " ORDER BY `documents`.`created_datetime` DESC";
		
		$aDocuments = $this->dbQuery(
			"SELECT `documents`.* FROM `{dbPrefix}documents` AS `documents`"
				." LEFT JOIN `{dbPrefix}documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`"
				." LEFT JOIN `{dbPrefix}documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `documents`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aDocuments as $x => &$aDocument)
			$aDocument = $this->_getDocumentInfo($aDocument);
		
		return $aDocuments;
	}
	function getDocument($sId) {
		$aDocument = $this->dbQuery(
			"SELECT `documents`.* FROM `{dbPrefix}documents` AS `documents`"
				." WHERE `documents`.`id` = ".$this->dbQuote($sId, "integer")
				." AND `documents`.`active` = 1"
			,"row"
		);
		
		if(!empty($aDocument))
			$aDocument = $this->_getDocumentInfo($aDocument);
		
		return $aDocument;
	}
	private function _getDocumentInfo($aDocument) {
		$aDocument["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}documents_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}documents_categories_assign` AS `documents_assign` ON `documents_assign`.`categoryid` = `categories`.`id`"
				." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
			,"all"
		);
		
		return $aDocument;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents_categories_assign`"
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
			"SELECT * FROM `{dbPrefix}documents_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}