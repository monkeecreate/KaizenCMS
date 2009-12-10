<?php
class faq extends appController
{
	function index()
	{
		$oFAQ = $this->loadModel("faq");
		
		## GET CURRENT PAGE QUESTIONS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aQuestionPages = array_chunk($oFAQ->getQuestions($_GET["category"]), $oFAQ->perPage);
		$aQuestions = $aQuestionPages[$sCurrentPage - 1];
		
		$aPaging = array(
			"back" => array(
				"page" => $sCurrentPage - 1,
				"use" => true
			),
			"next" => array(
				"page" => $sCurrentPage + 1,
				"use" => true
			)
		);
		
		if(($sCurrentPage - 1) < 1 || $sCurrentPage == 1)
			$aPaging["back"]["use"] = false;
		
		if($sCurrentPage == count($aQuestionPages) || count($aQuestionPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tpl_assign("aCategories", $oFAQ->getCategories());
		$this->tpl_assign("aQuestions", $aQuestions);
		$this->tpl_assign("aPaging", $aPaging);
		
		$this->tpl_display("faq.tpl");
	}
}