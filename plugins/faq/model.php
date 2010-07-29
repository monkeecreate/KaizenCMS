<?php
class faq_model extends appModel
{
	public $useCategories = true;
	public $perPage = 5;
	
	function getQuestions($sCategory = null, $sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `faq`.`id` > 0";// Allways true
		
		if($sAll == false)		
			$sWhere = " AND `faq`.`active` = 1";
			
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($_GET["category"], "integer");
		
		// Get all faq for paging
		$aQuestions = $this->dbQuery(
			"SELECT `faq`.* FROM `{dbPrefix}faq` AS `faq`"
				." LEFT JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`"
				." LEFT JOIN `{dbPrefix}faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `faq`.`sort_order`"
			,"all"
		);
		
		foreach($aQuestions as $x => &$aQuestion)
			$aQuestion = $this->_getQuestionInfo($aQuestion);
		
		return $aQuestions;
	}
	function getQuestion($sId) {
		$aQuestion = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}faq`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		if(!empty($aQuestion))
			$aQuestion = $this->_getQuestionInfo($aQuestion);
		
		return $aQuestion;
	}
	private function _getQuestionInfo($aQuestion) {
		$aQuestion["categories"] = $this->dbQuery(
			"SELECT `id`, `name` FROM `{dbPrefix}faq_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}faq_categories_assign` AS `faq_assign` ON `faq_assign`.`categoryid` = `categories`.`id`"
				." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
			,"all"
		);
		
		return $aQuestion;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}faq_categories_assign`"
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
			"SELECT * FROM `{dbPrefix}faq_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}