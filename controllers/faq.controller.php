<?php
class faq extends appController
{
	function index()
	{
		$sPerPage = 5;
		
		## FIND CATEGORIES ##
		$aCategories = $this->db_results(
			"SELECT * FROM `faq_categories`"
				." ORDER BY `name`"
			,"faq->get_categories->categories"
			,"all"
		);
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$sWhere = " WHERE `faq`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->db_quote($_GET["category"], "integer");
		
		// Get all faq for paging
		$aQuestions = $this->db_results(
			"SELECT `faq`.* FROM `faq` AS `faq`"
				." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`"
				." INNER JOIN `faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `faq`.`id`"
			,"faq->all_faq_pages"
			,"all"
		);
		
		$oPage = new Paginate($sPerPage, count($aQuestions), $sCurrentPage);
	
		$start = $oPage->get_start();
		
		$aQuestions = $this->db_results(
			"SELECT `faq`.* FROM `faq` AS `faq`"
				." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `faq`.`id` = `faq_assign`.`faqid`"
				." INNER JOIN `faq_categories` AS `categories` ON `faq_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `faq`.`id`"
				." ORDER BY `faq`.`sort_order`"
				." LIMIT ".$start.",".$sPerPage
			,"faq->current_page"
			,"all"
		);
	
		foreach($aQuestions as $x => $aQuestion)
		{
			/*# Categories #*/
			$aQuestionCategories = $this->db_results(
				"SELECT `name` FROM `faq_categories` AS `categories`"
					." INNER JOIN `faq_categories_assign` AS `faq_assign` ON `faq_assign`.`categoryid` = `categories`.`id`"
					." WHERE `faq_assign`.`faqid` = ".$aQuestion["id"]
				,"faq->faq_categories"
				,"col"
			);
		
			$aQuestions[$x]["categories"] = implode(", ", $aQuestionCategories);
			/*# Categories #*/
		}

		$this->tpl_assign("aCategories", $aCategories);
		$this->tpl_assign("aQuestions", $aQuestions);
		$this->tpl_assign("aPaging", $oPage->build_array());
		
		$this->tpl_display("faq.tpl");
	}
}