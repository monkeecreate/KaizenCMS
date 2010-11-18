<?php
class faq_model extends appModel
{
	public $useCategories = true;
	public $perPage = 5;
	public $sort = "manual-asc"; // manual, question, created, updated, random - asc, desc
	
	function getQuestions($sCategory = null, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`faq`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($_GET["category"])) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($_GET["category"], "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`";
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
				$sOrderBy = " ORDER BY `question` ".$sSortDirection;
		}
		
		// Get all faq for paging
		$aQuestions = $this->dbQuery(
			"SELECT `faq`.* FROM `{dbPrefix}faq` AS `faq`"
				.$sJoin
				.$sWhere
				.$sOrderBy
			,"all"
		);
		
		foreach($aQuestions as $x => &$aQuestion) {
			$aQuestion = $this->_getQuestionInfo($aQuestion);
		}
		
		return $aQuestions;
	}
	function getQuestion($sId) {
		$aQuestion = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}faq`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(!empty($aQuestion)) {
			$aQuestion = $this->_getQuestionInfo($aQuestion);
		}
		
		return $aQuestion;
	}
	private function _getQuestionInfo($aQuestion) {
		$aQuestion["question"] = nl2br(htmlspecialchars(stripslashes($aQuestion["question"])));
		$aQuestion["answer"] = stripslashes($aQuestion["answer"]);
		
		$aQuestion["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}faq_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq_assign`.`categoryid` = `categories`.`id`"
				." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
			,"all"
		);
		
		foreach($aQuestion["categories"] as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		return $aQuestion;
	}
	function getURL($sID) {
		$aQuestion = $this->getQuestion($sID);
		
		$sURL = "/faq/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory) {
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
			}
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}faq_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
}