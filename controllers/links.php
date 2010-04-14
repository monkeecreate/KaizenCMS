<?php
class links extends appController
{
	function index()
	{
		$oLinks = $this->loadModel("links");
		
		## GET CURRENT PAGE NEWS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aLinkPaging = array_chunk($oLinks->getLinks($_GET["category"]), $oLinks->perPage);
		$aLinks = $aLinkPaging[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aLinkPaging) || count($aLinkPaging) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("aCategories", $oLinks->getCategories(false));
		$this->tplAssign("aLinks", $aLinks);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("links.tpl");
	}
}