<?php
class faq_model extends appModel
{
	public $perPage = 5;
	
	function getQuestions($sCategory = null, $sAll = false) {
		// Start the WHERE
		$sWhere = " WHERE `faq`.`id` > 0";// Allways true
		
		if($sAll == false)		
			$sWhere = " AND `faq`.`active` = 1";
			
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($_GET["category"], "integer");
		
		// Get all faq for paging
		$aQuestions = $this->dbResults(
			"SELECT `faq`.* FROM `faq` AS `faq`"
				." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`"
				." INNER JOIN `faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `faq`.`id`"
			,"all"
		);
	
		foreach($aQuestions as $x => $aQuestion) {
			$aQuestionCategories = $this->dbResults(
				"SELECT `name` FROM `faq_categories` AS `categories`"
					." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `faq_assign`.`categoryid` = `categories`.`id`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
				,"col"
			);
		
			$aQuestions[$x]["categories"] = implode(", ", $aQuestionCategories);
		}
		
		return $aQuestions;
	}
	function getQuestion($sId) {
		$aQuestion = $this->dbResults(
			"SELECT * FROM `faq`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		return $aQuestion;
	}
	function getCategories($sEmpty = true) {
		if($sEmpty == true) {
			$aCategories = $this->dbResults(
				"SELECT * FROM `faq_categories`"
					." ORDER BY `name`"
				,"all"
			);
		} else {
			$aCategories = $this->dbResults(
				"SELECT * FROM `faq_categories_assign`"
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
		
		$aCategory = $this->dbResults(
			"SELECT * FROM `faq_categories`"
				.$sWhere
			,"row"
		);
		
		return $aCategory;
	}
}