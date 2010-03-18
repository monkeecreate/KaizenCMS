<?php
class documents extends appController
{
	function index()
	{
		$oDocuments = $this->loadModel("documents");
		
		## GET CURRENT PAGE DOCUMENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage))
			$sCurrentPage = 1;
		
		$aDocumentPages = array_chunk($oDocuments->getDocuments($_GET["category"]), $oDocuments->perPage);
		$aDocuments = $aDocumentPages[$sCurrentPage - 1];
		
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
		
		if($sCurrentPage == count($aDocumentPages) || count($aDocumentPages) == 0)
			$aPaging["next"]["use"] = false;
		#########################

		$this->tplAssign("aCategories", $oDocuments->getCategories());
		$this->tplAssign("aDocuments", $aDocuments);
		$this->tplAssign("aPaging", $aPaging);
		
		$this->tplDisplay("documents.tpl");
	}
}