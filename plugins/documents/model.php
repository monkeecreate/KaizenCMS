<?php
class documents_model extends appModel
{
	public $allowedExt = array();//array("pdf","doc");
	public $documentFolder = "/uploads/documents/";
	public $useCategories = true;
	public $perPage = 5;
	public $sort = "manual-asc"; // manual, name, created, updated, random - asc, desc
	
	function getDocuments($sCategory, $sAll = false, $sRandom = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`documents`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}documents_categories_assign` AS `documents_assign` ON `documents`.`id` = `documents_assign`.`documentid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}documents_categories` AS `categories` ON `documents_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
			
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		// Get all documents based on filters given
		$aDocuments = $this->dbQuery(
			"SELECT `documents`.* FROM `{dbPrefix}documents` AS `documents`"
				.$sJoin
				.$sWhere
				." GROUP BY `documents`.`id`"
				.$sOrderBy
			,"all"
		);
		
		foreach($aDocuments as $x => &$aDocument) {
			$aDocument = $this->_getDocumentInfo($aDocument);
		}
		
		return $aDocuments;
	}
	function getDocument($sId, $sAll = false) {
		if($sAll == false) {
			$sWhere = " AND `documents`.`active` = 1";
		}
		
		$aDocument = $this->dbQuery(
			"SELECT `documents`.* FROM `{dbPrefix}documents` AS `documents`"
				." WHERE `documents`.`id` = ".$this->dbQuote($sId, "integer")
				.$sWhere
			,"row"
		);
		
		if(!empty($aDocument))
			$aDocument = $this->_getDocumentInfo($aDocument);
		
		return $aDocument;
	}
	private function _getDocumentInfo($aDocument) {
		$aDocument["name"] = htmlspecialchars(stripslashes($aDocument["name"]));
		$aDocument["description"] = nl2br(htmlspecialchars(stripslashes($aDocument["description"])));
		
		$aDocument["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}documents_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}documents_categories_assign` AS `documents_assign` ON `documents_assign`.`categoryid` = `categories`.`id`"
				." WHERE `documents_assign`.`documentid` = ".$aDocument["id"]
			,"all"
		);
		
		foreach($aDocument["categories"] as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		return $aDocument;
	}
	function getURL($sID) {
		$aDocument = $this->getDocument($sID);
		
		$sURL = "/documents/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}documents_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
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
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
}