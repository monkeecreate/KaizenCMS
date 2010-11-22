<?php
class documents extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("documents");
	}
	
	function index() {
		## GET CURRENT PAGE DOCUMENTS
		$sCurrentPage = $_GET["page"];
		if(empty($sCurrentPage)) {
			$sCurrentPage = 1;
		}
		
		$aDocumentPages = array_chunk($this->model->getDocuments($_GET["category"]), $this->model->perPage);
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
		
		if(($sCurrentPage - 1) < 1 || $sCurrentPage == 1) {
			$aPaging["back"]["use"] = false;
		}
		
		if($sCurrentPage == count($aDocumentPages) || count($aDocumentPages) == 0) {
			$aPaging["next"]["use"] = false;
		}
		#########################

		$this->tplAssign("aCategories", $this->model->getCategories(false));
		$this->tplAssign("aDocuments", $aDocuments);
		$this->tplAssign("aPaging", $aPaging);
		$this->tplAssign("documentFolder", $this->model->documentFolder);
		
		$this->tplDisplay("documents.tpl");
	}
	function document() {
		$this->tplAssign("aDocument", $this->model->getDocument(null, $this->urlVars->dynamic["tag"]));
		$this->tplAssign("documentFolder", $this->model->documentFolder);
		$this->tplDisplay("document.tpl");
	}
}