<?php
class promos extends appController {
	function __construct() {
		// Load model when creating appController
		parent::__construct("promos");
	}
	
	function index() {
		$aPromo = $this->dbQuery(
			"SELECT `promos`.* FROM `{dbPrefix}promos` AS `promos`"
				." WHERE `id` = ".$this->dbQuote($this->urlVars->dynamic["id"], "integer")
			,"row"
		);
		
		$this->dbUpdate(
			"promos",
			array(
				"clicks" => ($aPromo["clicks"] + 1)
			),
			$aPromo["id"]
		);
		
		$this->forward($aPromo["link"]);
	}
}