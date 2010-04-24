<?php
class faq extends appController
{
	function __construct() {
		// Load model when creating appController
		parent::__construct("faq");
	}
	
	function index() {
		## GET CURRENT PAGE QUESTIONS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aQuestionPages = array_chunk($this->model->getQuestions($_GET["category"]), $this->model->perPage);
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

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aQuestions", $aQuestions);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("faq.tpl");
	}
}