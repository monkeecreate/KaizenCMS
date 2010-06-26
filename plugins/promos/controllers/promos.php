<?php
class promos extends appController
{
	function __construct() {
		// Load model when creating appController
		parent::__construct("promos");
	}
	
	function index() {
		$aPromo = $this->dbQuery(
			"SELECT `promos`.* FROM `{dbPrefix}promos` AS `promos`"
				." WHERE `id` = ".$this->dbQuote($this->_urlVars->dynamic["id"], "integer")
			,"row"
		);
		
		$this->dbQuery(
			"UPDATE `{dbPrefix}promos` SET"
				." `clicks` = `clicks` + 1"
				." WHERE `id` = ".$aPromo["id"]
			,"update"
		);
		
		$this->forward($aPromo["link"]);
	}
}